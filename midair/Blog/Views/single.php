<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= $data->description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content h-entry">
        <header class="title">
            <h1 class="p-name"><?= $data->title ?></h1>
        </header>
        <article>
            <div class="hexagon content__date">
                <div class="hexagon__inner dt-published">
                    <span class="month"><?= date('M', strtotime($data->pubDate)) ?></span>
                    <span class="day"><?= date('d', strtotime($data->pubDate)) ?></span>
                </div>
            </div>
            <div class="content__body e-content">
                <?= $data->content ?>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
