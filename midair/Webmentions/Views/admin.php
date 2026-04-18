<?= $this->extend('default') ?>

<?= $this->section('title') ?>Webmentions Admin<?= $this->endSection() ?>
<?= $this->section('og-title') ?>Webmentions Admin<?= $this->endSection() ?>
<?= $this->section('description') ?>Received webmentions admin view.<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="content">
    <header class="title">
        <h1>Received Webmentions</h1>
    </header>
    <article>
        <?php if (empty($mentions)): ?>
            <p>No webmentions received yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Source</th>
                        <th>Target</th>
                        <th>Author</th>
                        <th>Received</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($mentions as $mention): ?>
                        <tr>
                            <td><?= esc($mention->mention_type) ?></td>
                            <td><a href="<?= esc($mention->source) ?>"><?= esc(parse_url($mention->source, PHP_URL_HOST)) ?></a></td>
                            <td><a href="<?= esc($mention->target) ?>"><?= esc(parse_url($mention->target, PHP_URL_PATH)) ?></a></td>
                            <td>
                                <?php if ($mention->author_url): ?>
                                    <a href="<?= esc($mention->author_url) ?>"><?= esc($mention->author_name ?? $mention->author_url) ?></a>
                                <?php else: ?>
                                    <?= esc($mention->author_name ?? '—') ?>
                                <?php endif ?>
                            </td>
                            <td><?= esc($mention->created_at) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        <?php endif ?>
    </article>
</div>
<?= $this->endSection() ?>
