<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $this->renderSection('title') ?> | matthewpennell.com</title>
        <meta name="description" content="UK-based UX designer and developer with over two decades of experience and a passion for writing, music, and gaming.">

        <meta property="og:title" content="<?= $this->renderSection('og-title') ?>">
        <meta property="og:type" content="<?= $this->renderSection('type') ?>">
        <meta property="og:url" content="<?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?') ?>">
        <meta property="og:site_name" content="matthewpennell.com" />
        <meta property="og:description" content="<?= $this->renderSection('description') ?>" />

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="icon.png">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=link,open_in_new,rss_feed" />
        <link rel="stylesheet" href="/css/midair.css?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/midair.css') ?>">
        <script src="https://unpkg.com/htmx.org@2.0.4"></script>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-YRPVEZTZH7"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'G-YRPVEZTZH7');
        </script>
        <script>
            document.addEventListener('htmx:afterRequest', function(event) {
                var announceDiv = document.getElementById('announce');
                var message = document.createElement('div');
                message.textContent = 'New content has been loaded below.';
                announceDiv.appendChild(message);
                setTimeout(function() {
                    announceDiv.removeChild(message);
                }, 2000); // remove the message after 2 seconds
            });
        </script>
    </head>
    <body hx-boost="true">
        <div id="announce" aria-live="polite"></div>
        <a href="/"><img src="/images/mp.png" alt="matthewpennell.com" id="logo"></a>
        <a href="/rss/blog,commonplace,bluesky,flashfiction,medium,review" id="rss" title="Subscribe to the blog-plus-other-bits-of-writing RSS feed" hx-boost="false"><span class="material-symbols-outlined">rss_feed</span></a>
        <main>
            <?= $this->renderSection('content') ?>
        </main>
        <?= $this->include('partials/footer') ?>
    </body>
</html>