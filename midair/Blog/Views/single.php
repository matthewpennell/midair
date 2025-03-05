<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('type') ?>article<?= $this->endSection() ?>
<?= $this->section('description') ?><?= $data->description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div id="blog">
        <h1><?= $data->title ?></h1>
        <?= $data->content ?>
    </div>
<?= $this->endSection() ?>
