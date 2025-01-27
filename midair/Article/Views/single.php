<?= $this->extend('default') ?>

<?= $this->section('content') ?>
    <div id="article">
        <h1><?= $data->title ?></h1>
        <?= $data->content ?>
    </div>
<?= $this->endSection() ?>
