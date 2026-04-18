<?php

namespace Midair\Webmentions\Controllers;

use App\Controllers\BaseController;

class Send extends BaseController
{
    public function index(): string
    {
        return view('Midair\Webmentions\Views\send', [
            'title'   => 'Send Webmentions',
            'type'    => 'writing',
            'results' => null,
        ]);
    }

    public function submit(): string
    {
        $postUrl = $this->request->getPost('post_url');

        if (empty($postUrl) || !filter_var($postUrl, FILTER_VALIDATE_URL)) {
            return view('Midair\Webmentions\Views\send', [
                'title'   => 'Send Webmentions',
                'type'    => 'writing',
                'results' => null,
                'error'   => 'Please enter a valid URL.',
            ]);
        }

        $html = $this->fetchUrl($postUrl);
        if ($html === false) {
            return view('Midair\Webmentions\Views\send', [
                'title'   => 'Send Webmentions',
                'type'    => 'writing',
                'results' => null,
                'error'   => 'Could not fetch the provided URL.',
            ]);
        }

        $externalLinks = $this->extractExternalLinks($html, $postUrl);
        $results = [];

        foreach ($externalLinks as $target) {
            $endpoint = $this->discoverEndpoint($target);
            if ($endpoint === null) {
                $results[] = ['target' => $target, 'status' => 'no_endpoint'];
                continue;
            }

            $sent = $this->sendWebmention($endpoint, $postUrl, $target);
            $results[] = ['target' => $target, 'endpoint' => $endpoint, 'status' => $sent ? 'sent' : 'error'];
        }

        return view('Midair\Webmentions\Views\send', [
            'title'    => 'Send Webmentions',
            'type'     => 'writing',
            'post_url' => $postUrl,
            'results'  => $results,
        ]);
    }

    private function fetchUrl(string $url, bool $headersOnly = false): string|false
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_USERAGENT      => 'Webmention/1.0 (matthewpennell.com)',
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_HEADER         => $headersOnly,
            CURLOPT_NOBODY         => $headersOnly,
        ]);
        $body = curl_exec($ch);
        $error = curl_errno($ch);
        curl_close($ch);

        return ($error || $body === false) ? false : $body;
    }

    private function fetchWithHeaders(string $url): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_USERAGENT      => 'Webmention/1.0 (matthewpennell.com)',
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_HEADER         => true,
        ]);
        $response = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $error = curl_errno($ch);
        curl_close($ch);

        if ($error || $response === false) {
            return ['headers' => '', 'body' => ''];
        }

        return [
            'headers' => substr($response, 0, $headerSize),
            'body'    => substr($response, $headerSize),
        ];
    }

    private function extractExternalLinks(string $html, string $sourceUrl): array
    {
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($html);
        libxml_clear_errors();

        $sourceHost = parse_url($sourceUrl, PHP_URL_HOST);
        $links = [];

        foreach ($doc->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');
            if (empty($href) || !filter_var($href, FILTER_VALIDATE_URL)) {
                continue;
            }
            $host = parse_url($href, PHP_URL_HOST);
            if ($host && $host !== $sourceHost && !in_array($href, $links)) {
                $links[] = $href;
            }
        }

        return $links;
    }

    private function discoverEndpoint(string $url): ?string
    {
        $response = $this->fetchWithHeaders($url);

        // Check Link response header.
        if (preg_match('/<([^>]+)>\s*;\s*rel=["\']?webmention["\']?/i', $response['headers'], $m)) {
            return $m[1];
        }
        if (preg_match('/rel=["\']?webmention["\']?\s*;\s*<([^>]+)>/i', $response['headers'], $m)) {
            return $m[1];
        }

        if (empty($response['body'])) {
            return null;
        }

        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        $doc->loadHTML($response['body']);
        libxml_clear_errors();
        $xpath = new \DOMXPath($doc);

        // Check <link rel="webmention"> in <head>.
        $links = $xpath->query('//head/link[contains(concat(" ", normalize-space(@rel), " "), " webmention ")]');
        if ($links->length > 0) {
            return $links->item(0)->getAttribute('href');
        }

        // Check <a rel="webmention">.
        $anchors = $xpath->query('//a[contains(concat(" ", normalize-space(@rel), " "), " webmention ")]');
        if ($anchors->length > 0) {
            return $anchors->item(0)->getAttribute('href');
        }

        return null;
    }

    private function sendWebmention(string $endpoint, string $source, string $target): bool
    {
        $ch = curl_init($endpoint);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query(['source' => $source, 'target' => $target]),
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_USERAGENT      => 'Webmention/1.0 (matthewpennell.com)',
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_errno($ch);
        curl_close($ch);

        return !$error && $httpCode >= 200 && $httpCode < 300;
    }
}
