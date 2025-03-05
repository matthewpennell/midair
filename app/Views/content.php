<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?>matthewpennell.com<?= $this->endSection() ?>
<?= $this->section('type') ?>website<?= $this->endSection() ?>
<?= $this->section('description') ?><?= $description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <?= $content ?>
    <div id="loading" hx-get="?p=<?= $next_page ?>" hx-trigger="revealed" hx-swap="outerHTML"></div>
<?= $this->endSection() ?>
