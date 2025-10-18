<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?> | Reviews<?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?> | Reviews<?= $this->endSection() ?>
<?= $this->section('description') ?><?= $data->description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <header class="title">
            <h1><?= $data->title ?></h1>
            <div class="source tumblr">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                        <path d="M 72.028 0 H 17.972 C 8.09 0 0 8.09 0 17.972 v 54.056 C 0 81.91 8.09 90 17.972 90 h 54.056 C 81.91 90 90 81.91 90 72.028 V 17.972 C 90 8.09 81.91 0 72.028 0 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,25,53); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                        <path d="M 60.502 73.03 h -9.484 c -8.54 0 -14.905 -4.394 -14.905 -14.905 V 41.292 h -7.76 v -9.115 c 8.54 -2.218 12.112 -9.567 12.523 -15.931 h 8.868 v 14.453 H 60.09 v 10.593 H 49.744 V 55.95 c 0 4.393 2.218 5.912 5.748 5.912 h 5.01 V 73.03 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(255,255,255); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round"/>
                    </g>
                </svg>
                <a href="<?= $data->link ?>" target="_blank">This review was first published on <strong>This Reviewer's Life</strong>. Visit the original here →</a>
            </div>
        </header>
        <article>
            <div class="hexagon content__date">
                <div class="hexagon__inner">
                    <span class="month"><?= date('M', strtotime($data->pubDate)) ?></span>
                    <span class="day"><?= date('d', strtotime($data->pubDate)) ?></span>
                </div>
            </div>
            <div class="content__body">
                <?= $data->content ?>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
