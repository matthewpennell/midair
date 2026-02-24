<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?> | Letterboxd entry<?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?> | Letterboxd entry<?= $this->endSection() ?>
<?= $this->section('description') ?><?= $description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <div class="content__image">
            <img src="<?= $data->image ?>" alt="<?= $data->title ?>">
        </div>
        <header class="title">
            <h1><?= $data->title ?></h1>
            <div class="source medium">
                <img class="source-icon" src="/images/letterboxd.svg" width="20" height="20" alt="" />
                <a href="<?= $data->link ?>" target="_blank">This review was originally posted on <strong>Letterboxd</strong>. Visit the original here →</a>
            </div>
        </header>
        <article>
            <div class="hexagon content__date">
                <div class="hexagon__inner">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 100 100">
                            <path d="m93.723 35.145v50.766c0 2.0703-0.82422 4.0586-2.2891 5.5234s-3.4492 2.2891-5.5234 2.2891h-71.852c-2.0703 0-4.0586-0.82422-5.5234-2.2891s-2.2891-3.4492-2.2891-5.5234v-50.766zm-33.883 25.332-14.785-9.1992c-1.4375-0.89453-3.2461-0.9375-4.7227-0.11719-1.4805 0.82031-2.3984 2.3789-2.3984 4.0742v18.402c0 1.6914 0.91797 3.25 2.3984 4.0742 1.4805 0.82031 3.2891 0.77734 4.7227-0.11719l14.785-9.1992c1.3672-0.85156 2.1992-2.3477 2.1992-3.957s-0.83203-3.1055-2.1992-3.957z" fill-rule="evenodd" fill="#ffffff"/>
                            <path d="m50.699 16.793h-10.695l-7.8633-10.543h10.695zm-2.9922-10.543h10.805l7.8633 10.543h-10.805zm-4.8711 24.992h-10.695l7.8633-10.543h10.695zm12.734-10.543h10.805l-7.8633 10.543h-10.805zm15.676 0h10.805l-7.8633 10.543h-10.805zm-7.8633-14.449h10.805l7.8633 10.543h-10.805zm15.676 0h6.8555c2.0703 0 4.0586 0.82422 5.5234 2.2891 1.4648 1.4648 2.2891 3.4492 2.2891 5.5234v2.7344h-6.8047l-7.8633-10.543zm7.8633 14.449h6.8047v10.543h-14.664l7.8633-10.543zm-51.785-3.9062h-10.805l-7.8633-10.543h10.805zm-7.8633 14.449h-10.805l7.8633-10.543h10.805zm-15.676 0h-5.3477v-10.543h13.207l-7.8633 10.543zm-5.3477-14.449v-2.7344c0-2.0703 0.82422-4.0586 2.2891-5.5234 0.92969-0.92969 2.0664-1.5977 3.3008-1.9648l7.6211 10.223z" fill-rule="evenodd" fill="#ffffff"/>
                        </svg>
                    </span>
                </div>
                <span class="month"><?= date('M', strtotime($data->pubDate)) ?></span>
                <span class="day"><?= date('d', strtotime($data->pubDate)) ?></span>
            </div>
            <div class="content__body">
                <h2>Rating</h2>
                <p><?= $data->rating ?></p>
                <?php if (strlen($data->review) > 1): ?>
                    <h2>Review</h2>
                    <p><?= $data->review ?></p>
                <?php endif; ?>
                <h2>Release year</h2>
                <p><?= $data->release_year ?></p>
                <!--
                <h2>Plot summary</h2>
                <p><small><?= $data->description ?></small></p>
                -->
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
