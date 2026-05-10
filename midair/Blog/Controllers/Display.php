<?php

namespace Midair\Blog\Controllers;

use App\Controllers\BaseController;

class Display extends BaseController
{

    public $per_page = 10;

    public function index($page = 1): string
    {
        // Connect to the database.
        $db = db_connect();
        $MidairModel = new \App\Models\Midair();

        // Retrieve a total count of posts to use for pagination calculations.
        $total_count = $MidairModel->where('status', 'published')->where('type', 'blog')->countAllResults();

        // Calculate the offset for the current page.
        $offset = ($page - 1) * $this->per_page;

        // Retrieve the latest entries from the Writing feed.
        $articles = $MidairModel->asObject()->where('status', 'published')->where('type', 'blog')->orderBy('date', 'DESC')->limit($this->per_page)->offset($offset)->findAll();

        // Load the main content view and pass in the data.
        return view('writing', [
            'title' => 'Blog',
            'type' => 'writing',
            'search' => 'blog',
            'description' => 'The blog archive, containing stuff I\'ve written on this site and its predecessors over the years. Not everything, but most of the recent stuff at least.',
            'articles' => $articles,
            'total_count' => $total_count,
            'per_page' => $this->per_page,
            'page' => $page,
        ]);
    }

    public function single($url = ''): string
    {
       // Connect to the database and instantiate the relevant models.
       $db = db_connect();
       $BlogModel = new \Midair\Blog\Models\Blog();

       // Retrieve the blog entry from the database.
       $sql = 'SELECT * FROM blog WHERE link REGEXP ?';
       $pattern = '^' . env('blog.rss_link_root') . '[0-9]{4}/[0-9]{2}/[0-9]{2}/' . $url . '/$';
       $blog = $db->query($sql, [$pattern])->getRow();

        // If the blog doesn't exist, throw a 404 error.
        if (empty($blog)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $WebmentionModel = new \Midair\Webmentions\Models\Webmention();
        $webmentions = $WebmentionModel->asObject()->like('target', '/blog/' . $url, 'both')->orderBy('created_at', 'ASC')->findAll();

        // Load the main content view and pass in the data.
        return view('Midair\Blog\Views\single', [
            'data'        => $blog,
            'title'       => $blog->title,
            'type'        => 'writing',
            'description' => $blog->description,
            'search'      => 'blog',
            'webmentions' => $webmentions,
            //'og_image'    => $this->generateOgImage($blog),
            'og_image'    => base_url('images/og.png'),
        ]);
    }

    private function generateOgImage(object $blog): string
    {
        $wordCount = str_word_count(strip_tags($blog->content));
        $readingTime = max(1, (int) round($wordCount / 200));

        $payload = json_encode([
            'name' => 'blog:magazine',
            'params' => [
                'title' => [
                    'text'       => $blog->title,
                    'fontFamily' => 'inter',
                    'fontWeight' => 800,
                    'fontSize'   => 48,
                    'color'      => '#111827',
                ],
                'subtitle' => [
                    'text'       => $blog->description,
                    'fontFamily' => 'inter',
                    'fontWeight' => 400,
                    'fontSize'   => 24,
                    'color'      => '#4b5563',
                ],
                'category' => [
                    'text'       => 'BLOG',
                    'fontFamily' => 'inter',
                    'fontWeight' => 600,
                    'fontSize'   => 16,
                    'color'      => '#2563eb',
                ],
                'author' => [
                    'text'       => 'Matthew Pennell',
                    'fontFamily' => 'inter',
                    'fontWeight' => 500,
                    'fontSize'   => 18,
                    'color'      => '#374151',
                ],
                'publishDate' => [
                    'text'       => date('j F Y', strtotime($blog->pubDate)),
                    'fontFamily' => 'inter',
                    'fontWeight' => 400,
                    'fontSize'   => 16,
                    'color'      => '#6b7280',
                ],
                'readTime' => [
                    'text'       => $readingTime . ' min read',
                    'fontFamily' => 'inter',
                    'fontWeight' => 400,
                    'fontSize'   => 16,
                    'color'      => '#6b7280',
                ],
                'featuredImage' => [
                    'url' => base_url('images/portrait-bw.jpg'),
                ],
                'logo' => [
                    'url' => base_url('apple-touch-icon.png'),
                ],
            ],
        ]);

        $ch = curl_init('https://myogimage.com/api/v1/images');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT        => 5,
        ]);
        $body       = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);
        curl_close($ch);

        if ($body !== false && $statusCode === 200) {
            $data = json_decode($body, true);
            if (!empty($data['url'])) {
                return $data['url'];
            }
            $trimmed = trim($body);
            if (filter_var($trimmed, FILTER_VALIDATE_URL)) {
                return $trimmed;
            }
        }

        return base_url('images/og.png');
    }
}
