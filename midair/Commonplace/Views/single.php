<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('type') ?>article<?= $this->endSection() ?>
<?= $this->section('description') ?><?= $data->description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <article class="commonplace">
        <div class="meta">
            <span class="ma-type commonplace">Commonplace</span>
            &bull;
            <time datetime="<?= $data->pubDate ?>"><?= date('jS M Y', strtotime($data->pubDate)) ?></time>
        </div>
        <?= $data->content ?>
    </article>
<?= $this->endSection() ?>
