<?= $this->extend('default') ?>

<?= $this->section('title') ?>About me<?= $this->endSection() ?>
<?= $this->section('og-title') ?>About me<?= $this->endSection() ?>
<?= $this->section('description') ?>A potted summary, covering the essentials: who I am, what I do, and how I do it.<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="content">
        <header class="title">
            <h1>About me</h1>
        </header>
        <article>
            <div class="content__body">
                <p>
                    I'm a British UX designer with 25 years' experience making websites. Yes, that 
                    means I started out in the era of tables and spacer GIFs, but my skills have 
                    mostly managed to keep up with the changes in our industry over the last 
                    two-and-a-half decades.
                </p>

                <p>
                    During that time period I have worked in finance during the birth of online 
                    banking, freelanced for clients around the world, been a part of community 
                    organising and publishing, and worked for a Fortune 500 company during a period 
                    of hypergrowth and expansion.
                </p>

                <p>
                    I live in Norfolk, which is the sticky-out bit on the easternmost edge of the 
                    United Kingdom, with my wife and dog. Sometimes I work in Amsterdam, occasionally
                    I work in Cambridge, but most of the time I work from home.
                </p>

                <p>
                    In my spare time (which I now seem to have plenty of, with the kids all grown up) 
                    I enjoy running, reading, listening to new music, and PC gaming. I thought I was 
                    going to get back into 8-bit retro gaming at one point, but it wasn't as fun as I 
                    remembered from my childhood. Either that or I just got too slow.
                </p>

                <p>
                    I used to be mentioned (in passing) on Wikipedia, but that article has since been 
                    deemed Not Important Enough To Exist and deleted. Boo.
                </p>
            </div>
        </article>
    </div>
<?= $this->endSection() ?>
