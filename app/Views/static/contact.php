<?= $this->extend('default') ?>

<?= $this->section('title') ?>Get in touch<?= $this->endSection() ?>
<?= $this->section('og-title') ?>Get in touch<?= $this->endSection() ?>
<?= $this->section('description') ?>How to get in touch with me, if that's something you need to do.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <header class="title">
            <h1>How to reach me</h1>
        </header>
        <article>
            <div class="content__body">
                <p>There are a few ways to get in touch with me, if that's something you particularly need to do for some reason. I'm not interested in spams, scams, or unsolicited offers of work.</p>

                <ul>
                    <li>Bluesky: <a href="https://bsky.app/profile/matthewpennell.com">If you're on Bluesky, you can send me a DM</a></li>
                    <li>LinkedIn: <a href="https://www.linkedin.com/in/matthewpennell/">Work stuff belongs on LinkedIn</a></li>
                    <li>Amazon wishlist: <a href="https://www.amazon.co.uk/hz/wishlist/ls/2FL1U4TCT0LI5?ref_=wl_share">Buy me a gift!</a></li>
                </ul>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
