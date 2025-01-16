<?= $this->extend('default') ?>

<?= $this->section('content') ?>
    <h1><?= $data->title ?></h1>
    <?= $data->content ?>
<?= $this->endSection() ?>
