<?= $this->extend('default') ?>

<?= $this->section('head-extras') ?>
<link rel="webmention" href="/webmention">
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= $data->description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content h-entry">
        <link class="u-url" href="<?= current_url() ?>">
        <header class="title">
            <h1 class="p-name"><?= $data->title ?></h1>
        </header>
        <article>
            <div class="hexagon content__date">
                <time class="hexagon__inner dt-published" datetime="<?= date('c', strtotime($data->pubDate)) ?>">
                    <span class="month"><?= date('M', strtotime($data->pubDate)) ?></span>
                    <span class="day"><?= date('d', strtotime($data->pubDate)) ?></span>
                </time>
            </div>
            <div class="content__body e-content">
                <?= $data->content ?>
            </div>
        </article>
        <?= $this->include('partials/share') ?>
        <?= $this->include('Midair\Webmentions\Views\partials\webmentions', ['webmentions' => $webmentions]) ?>
    </div>
<?= $this->endSection() ?>
