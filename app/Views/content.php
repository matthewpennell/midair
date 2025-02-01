<?= $this->extend('default') ?>

<?= $this->section('content') ?>
    <?= $content ?>
    <div id="loading" hx-get="/?p=<?= $next_page ?>" hx-trigger="revealed" hx-swap="outerHTML"></div>
<?= $this->endSection() ?>
