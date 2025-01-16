<?= $this->extend('default') ?>

<?= $this->section('content') ?>
    <h1>matthewpennell.com</h1>
    <?= $content ?>
    <div hx-get="/?p=<?= $next_page ?>" hx-trigger="revealed" hx-swap="outerHTML">
        spinner.gif
    </div>
<?= $this->endSection() ?>
