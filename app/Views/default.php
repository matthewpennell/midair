<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= $this->renderSection('title') ?> | matthewpennell.com</title>
        <meta name="description" content="<?= $this->renderSection('description', true) ?>">

        <meta property="og:title" content="<?= $this->renderSection('og-title') ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?= (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://" . $_SERVER['HTTP_HOST'] . strtok($_SERVER['REQUEST_URI'], '?') ?>">
        <meta property="og:site_name" content="matthewpennell.com" />
        <meta property="og:description" content="<?= $this->renderSection('description') ?>" />

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inclusive+Sans:ital,wght@0,300..700;1,300..700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0&icon_names=format_quote" />
        <link rel="stylesheet" href="/css/midair.css?v=<?= filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/midair.css') ?>">
        <?php if ($_SERVER['HTTP_HOST'] !== 'localhost' && stristr($_SERVER['HTTP_HOST'], '192.168.') === false): ?>
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-YRPVEZTZH7"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', 'G-YRPVEZTZH7');
            </script>
        <?php endif ?>
    </head>
    <body class="<?= $type ?> h-card">
        <div class="layout">
            <div class="layout__pane--meta">
                <div class="logo" role="button" tabindex="0">
                    <svg class="logomark" width="41.5" height="48" viewBox="0 0 447 520" fill="none" xmlns="http://www.w3.org/2000/svg" class="u-logo">
                        <path d="M225 0L450.167 130V390L225 520L-0.166611 390V130L225 0Z" fill="#000"/>
                        <path d="M37.5 309V196.25C37.5 175.677 54.1774 159 74.75 159V159C95.3226 159 112 175.677 112 196.25V309" stroke-width="33"/>
                        <path d="M186.5 309V196.25C186.5 175.677 203.177 159 223.75 159V159C244.323 159 261 175.677 261 196.25V309" stroke-width="33"/>
                        <path d="M335.5 309V196.25C335.5 175.677 352.177 159 372.75 159V159C393.323 159 410 175.677 410 196.25V309" stroke-width="33"/>
                        <path d="M186.5 196L186.5 308.75C186.5 329.323 169.823 346 149.25 346V346C128.677 346 112 329.323 112 308.75L112 196" stroke-width="33"/>
                        <path d="M37.5 196L37.5 308.75C37.5 329.323 20.8226 346 0.25 346V346C-20.3226 346 -37 329.323 -37 308.75L-37 196" stroke-width="33"/>
                        <path d="M335.5 196L335.5 308.75C335.5 329.323 318.823 346 298.25 346V346C277.677 346 261 329.323 261 308.75L261 196" stroke-width="33"/>
                        <path d="M484.5 196L484.5 308.75C484.5 329.323 467.823 346 447.25 346V346C426.677 346 410 329.323 410 308.75L410 196" stroke-width="33"/>
                        <line x1="335" y1="219" x2="335" y2="582" stroke-width="33"/>
                        <g filter="url(#filter0_d_114_31)">
                        <line x1="335" y1="314" x2="335" y2="359" stroke-width="33"/>
                        </g>
                        <line x1="335" y1="304" x2="335" y2="372" stroke-width="33"/>
                    </svg>
                </div>
                <div class="mobile-nav">
                    <button class="mobile-nav__button" aria-label="Toggle mobile navigation">
                        <span class="mobile-nav__button__bar"></span>
                        <span class="mobile-nav__button__bar"></span>
                        <span class="mobile-nav__button__bar"></span>
                    </button>
                </div>
                <nav class="primary">
                    <ul>
                        <li><a class="u-url nav-home" href="/">Home</a></li>
                        <li><a class="nav-about" href="/about-me">About me</a></li>
                        <li><a class="nav-work" href="/work">My work</a></li>
                        <li><a class="nav-now" href="/now">Now</a></li>
                        <li><a class="nav-writing" href="/writing">Writing</a></li>
                        <li><a class="nav-consuming" href="/consuming">Consuming</a></li>
                        <li><a class="nav-contact" href="/contact">Contact</a></li>
                    </ul>
                </nav>
                <?= $this->include('partials/meta/' . $type) ?>
            </div>
            <div class="layout__pane--content">
                <?= $this->renderSection('content') ?>
            </div>
            <?= $this->include('partials/footer') ?>
        </div>
        <script type="module" src="/js/midair.js"></script>
    </body>
</html>