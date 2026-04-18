<?php

namespace Midair\Webmentions\Controllers;

use App\Controllers\BaseController;
use Midair\Webmentions\Models\Webmention;

class Receive extends BaseController
{
    public function index()
    {
        if ($this->request->getMethod() === 'get') {
            return $this->response
                ->setStatusCode(200)
                ->setContentType('text/plain')
                ->setBody('Webmention endpoint. Send a POST request with source and target parameters.');
        }

        $source = $this->request->getPost('source');
        $target = $this->request->getPost('target');

        if (empty($source) || empty($target) || !filter_var($source, FILTER_VALIDATE_URL) || !filter_var($target, FILTER_VALIDATE_URL)) {
            return $this->response->setStatusCode(400)->setBody('Invalid source or target URL.');
        }

        $result = $this->processWebmention($source, $target);

        return $this->response->setStatusCode($result['status'])->setBody($result['message']);
    }

    public function manual(): \CodeIgniter\HTTP\RedirectResponse
    {
        $source = $this->request->getPost('source');
        $target = $this->request->getPost('target');

        $redirectUrl = filter_var($target, FILTER_VALIDATE_URL) ? $target : base_url();

        if (empty($source) || !filter_var($source, FILTER_VALIDATE_URL)) {
            session()->setFlashdata('webmention_error', 'Please enter a valid URL.');
            return redirect()->to($redirectUrl);
        }

        if (empty($target) || !filter_var($target, FILTER_VALIDATE_URL)) {
            session()->setFlashdata('webmention_error', 'Invalid target URL.');
            return redirect()->to($redirectUrl);
        }

        $result = $this->processWebmention($source, $target);

        if ($result['success']) {
            session()->setFlashdata('webmention_success', 'Your webmention has been received. Thank you!');
        } else {
            session()->setFlashdata('webmention_error', $result['message']);
        }

        return redirect()->to($redirectUrl);
    }

    private function processWebmention(string $source, string $target): array
    {
        $baseUrl      = rtrim(env('app.baseURL'), '/');
        $parsedTarget = parse_url($target);
        $parsedBase   = parse_url($baseUrl);

        if (($parsedTarget['host'] ?? '') !== ($parsedBase['host'] ?? '')) {
            return ['success' => false, 'status' => 400, 'message' => 'Target does not belong to this site.'];
        }

        $targetPath = $parsedTarget['path'] ?? '';
        if (!preg_match('#^/blog/#', $targetPath)) {
            return ['success' => false, 'status' => 400, 'message' => 'Target must be a blog post URL.'];
        }

        $model    = new Webmention();
        $existing = $model->where('source', $source)->where('target', $target)->asObject()->first();
        if ($existing) {
            return ['success' => true, 'status' => 200, 'message' => 'Already received.'];
        }

        $html = $this->fetchUrl($source);
        if ($html === false) {
            return ['success' => false, 'status' => 400, 'message' => 'Could not fetch source URL.'];
        }

        if (!$this->sourceLinksToTarget($html, $target)) {
            return ['success' => false, 'status' => 400, 'message' => 'Source does not contain a link to target.'];
        }

        $parsed = $this->parseMicroformats($html, $source);

        $model->save([
            'source'       => $source,
            'target'       => $target,
            'author_name'  => $parsed['author_name'],
            'author_url'   => $parsed['author_url'],
            'author_photo' => $parsed['author_photo'],
            'content'      => $parsed['content'],
            'mention_type' => $parsed['mention_type'],
        ]);

        return ['success' => true, 'status' => 202, 'message' => 'Webmention accepted.'];
    }

    private function fetchUrl(string $url): string|false
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_USERAGENT      => 'Webmention/1.0 (matthewpennell.com)',
            CURLOPT_MAXREDIRS      => 5,
        ]);
        $body  = curl_exec($ch);
        $error = curl_errno($ch);
        curl_close($ch);

        return ($error || $body === false) ? false : $body;
    }

    private function sourceLinksToTarget(string $html, string $target): bool
    {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        libxml_clear_errors();

        foreach ($doc->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            if ($href === $target || rtrim($href, '/') === rtrim($target, '/')) {
                return true;
            }
        }
        return false;
    }

    private function parseMicroformats(string $html, string $sourceUrl): array
    {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        libxml_clear_errors();
        $xpath = new \DOMXPath($doc);

        $result = [
            'author_name'  => null,
            'author_url'   => null,
            'author_photo' => null,
            'content'      => null,
            'mention_type' => 'mention',
        ];

        $typeMap = [
            'u-like-of'     => 'like',
            'u-repost-of'   => 'repost',
            'u-in-reply-to' => 'reply',
            'u-bookmark-of' => 'bookmark',
        ];
        foreach ($typeMap as $class => $type) {
            $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");
            if ($nodes->length > 0) {
                $result['mention_type'] = $type;
                break;
            }
        }

        foreach (['p-content', 'e-content'] as $cls) {
            $nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $cls ')]");
            if ($nodes->length > 0) {
                $text             = trim($nodes->item(0)->textContent);
                $result['content'] = mb_substr($text, 0, 500);
                break;
            }
        }

        $authorNodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' p-author ')]");
        if ($authorNodes->length === 0) {
            $authorNodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' h-card ')]");
        }

        if ($authorNodes->length > 0) {
            $author      = $authorNodes->item(0);
            $authorXpath = new \DOMXPath($author->ownerDocument);

            $nameNodes = $authorXpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' p-name ')]", $author);
            if ($nameNodes->length > 0) {
                $result['author_name'] = trim($nameNodes->item(0)->textContent);
            }

            $urlNodes = $authorXpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' u-url ')]", $author);
            if ($urlNodes->length > 0) {
                $result['author_url'] = $urlNodes->item(0)->getAttribute('href');
            }

            $photoNodes = $authorXpath->query(".//*[contains(concat(' ', normalize-space(@class), ' '), ' u-photo ')]", $author);
            if ($photoNodes->length > 0) {
                $node                  = $photoNodes->item(0);
                $result['author_photo'] = $node->getAttribute('src') ?: $node->getAttribute('href');
            }
        }

        return $result;
    }
}
