<?= $this->extend('default') ?>

<?= $this->section('title') ?>Send Webmentions<?= $this->endSection() ?>
<?= $this->section('og-title') ?>Send Webmentions<?= $this->endSection() ?>
<?= $this->section('description') ?>Manually send webmentions for a blog post URL.<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <header class="title">
        <h1>Send Webmentions</h1>
    </header>
    <article>
        <p>Enter a blog post URL to discover all external links and send webmentions to any that support them.</p>
        <?php if (!empty($error)): ?>
            <p class="error"><?= esc($error) ?></p>
        <?php endif ?>
        <form method="post" action="/webmention/send">
            <?= csrf_field() ?>
            <label for="post_url">Post URL</label>
            <input type="url" id="post_url" name="post_url" required value="<?= esc($post_url ?? '') ?>">
            <button type="submit">Send Webmentions</button>
        </form>
        <?php if ($results !== null): ?>
            <h2>Results</h2>
            <?php if (empty($results)): ?>
                <p>No external links found in that post.</p>
            <?php else: ?>
                <ul class="webmention-send-results">
                    <?php foreach ($results as $r): ?>
                        <li>
                            <a href="<?= esc($r['target']) ?>"><?= esc($r['target']) ?></a>
                            <?php if ($r['status'] === 'sent'): ?>
                                — <strong>Sent</strong> to <?= esc($r['endpoint']) ?>
                            <?php elseif ($r['status'] === 'error'): ?>
                                — <strong>Error</strong> sending to <?= esc($r['endpoint']) ?>
                            <?php else: ?>
                                — No webmention endpoint
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif ?>
        <?php endif ?>
    </article>
</div>
<?= $this->endSection() ?>
