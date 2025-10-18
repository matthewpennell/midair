<?= $this->extend('default') ?>

<?= $this->section('title') ?>/now<?= $this->endSection() ?>
<?= $this->section('og-title') ?>/now<?= $this->endSection() ?>
<?= $this->section('description') ?>What's going on with me right now?<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <header class="title">
            <h1>What's going on with me right now?</h1>
        </header>
        <article>
            <div class="content__body">
                <p>(Wondering what the hell a Now page is? Find out at <a href="https://nownownow.com/about" target="_blank" rel="noopener noreferrer">nownownow.com</a>.)</p>
                <p><em>Last updated: 23rd September 2025</em></p>
                <ul>
                    <li>I'm still working at Booking.com, for coming up on 20 years now. Not worked on the customer-facing side for a while though; I spent several years in the Customer Service tech area, and since 2024 I've been working on internal tooling UX for engineers.</li>
                    <li>We moved from our tiny village in Cambridgeshire to an even tinier village in North Norfolk in mid-2025. There are lots of beautiful dog walks and running routes, but also more hills than I'm used to after living in the Fens for the last 20 years.</li>
                    <li>The kids are now all (technically) grown-ups and working, but still living at home.</li>
                    <li>I started running again in 2025, after a year off with a heel injury. No plans to sign up for anything like another marathon, it's purely for the exercise now.</li>
                    <li>I bought rather a lot of home studio recording equipment and taught myself to use Reaper to record and mix a few songs. It turns out that writing anything good is more work than I'm willing to put in right now, though. Maybe another year.</li>
                    <li>I've been playing a lot of video games since buying a decent PC in 2020, mostly MMOs and RPGs. I think the last thing I finished was probably Uncharted 4.</li>
                </ul>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
