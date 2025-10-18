<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?>matthewpennell.com<?= $this->endSection() ?>
<?= $this->section('description') ?><?= $description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <nav class="toc">
        <h2>Contents.</h2>
        <ul>
        <li>
            <a href="/about-me">About me</a>
            <p>A life, condensed into a LinkedIn profile.</p>
        </li>
        <li>
            <a href="/work">My work</a>
            <p>Not so much a portfolio, more a loosely connected collection of vague memories.</p>
        </li>
        <li>
            <a href="/now">Now</a>
            <p>What’s going on right now(ish). Irregularly updated.</p>
        </li>
        <li>
            <a href="/contact">Get in touch</a>
            <p>Find out how to send me things, if that’s a thing you want to do.</p>
        </li>
        <li>
            <a href="/writing">Writing</a>
            <p>Irregular updates on life, work, hobbies and stuff. Most recently ‘<a href="/<?= $latest_writing->type ?>/<?= $latest_writing->url ?>"><?= $latest_writing->title ?></a>’ was posted on <?= date('jS M Y', strtotime($latest_writing->date)) ?>.</p>
        </li>
        <li>
            <a href="/consuming">Consuming</a>
            <p>Movies, books, music and reviews. Most recently ‘<a href="/<?= $latest_consuming->type ?>/<?= $latest_consuming->url ?>"><?= $latest_consuming->title ?></a>’ was posted on <?= date('jS M Y', strtotime($latest_consuming->date)) ?>.</p>
        </li>
        <li>
            <a href="/colophon">Colophon</a>
            <p>What went into the making of this site.</p>
        </li>
        </ul>
    </nav>
    <div id="latest-bluesky"><?= $latest_bluesky->excerpt ?></div>
<?= $this->endSection() ?>
