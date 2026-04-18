<?php
$flashSuccess = session()->getFlashdata('webmention_success');
$flashError   = session()->getFlashdata('webmention_error');
?>

<div class="webmentions-block">

<section class="webmention-submit">
    <div>
        <h2>Have you written about this?</h2>
        <p>Submit a URL containing a link back to this page. (<a href="https://indieweb.org/Webmention" target="_blank">What is this?</a>)</p>
    </div>
    <?php if ($flashSuccess): ?>
        <p class="webmention-submit__message webmention-submit__message--success"><?= esc($flashSuccess) ?></p>
    <?php endif ?>
    <?php if ($flashError): ?>
        <p class="webmention-submit__message webmention-submit__message--error"><?= esc($flashError) ?></p>
    <?php endif ?>
    <form class="webmention-submit__form" method="post" action="/webmention/manual">
        <?= csrf_field() ?>
        <input type="hidden" name="target" value="<?= esc(current_url()) ?>">
        <label for="webmention-source">URL of your post</label>
        <input type="url" id="webmention-source" name="source" required placeholder="https://example.com/your-post">
        <button type="submit">Submit</button>
    </form>
</section>

<?php if (!empty($webmentions)): ?>

<?php
$likes   = array_filter($webmentions, fn($m) => in_array($m->mention_type, ['like', 'bookmark']));
$reposts = array_filter($webmentions, fn($m) => $m->mention_type === 'repost');
$replies = array_filter($webmentions, fn($m) => in_array($m->mention_type, ['reply', 'mention']));
$totalCount = count($webmentions);
?>

<div class="webmentions__wrapper">

    <section class="webmentions">
        <h2 class="webmentions__heading">
            Webmentions
        </h2>

        <?php if (!empty($replies)): ?>
            <div class="webmentions__replies">
                <h3 class="webmentions__subheading"><?= count($replies) ?> replies/mentions</h3>
                <ul class="webmentions__reply-list">
                    <?php foreach ($replies as $mention): ?>
                        <li class="webmentions__reply">
                            <div class="webmentions__reply-author">
                                <?php if ($mention->author_photo): ?>
                                    <img src="<?= esc($mention->author_photo) ?>" alt="" width="48" height="48" loading="lazy">
                                <?php else: ?>
                                    <span class="webmentions__avatar-placeholder"><?= mb_substr($mention->author_name ?? '?', 0, 1) ?></span>
                                <?php endif ?>
                                <div class="webmentions__reply-meta">
                                    <time datetime="<?= esc($mention->created_at) ?>">
                                        <?= date('j M Y', strtotime($mention->created_at)) ?>
                                    </time>
                                    <?php if ($mention->author_url): ?>
                                        <a href="<?= esc($mention->author_url) ?>"><?= esc($mention->author_name ?? $mention->author_url) ?></a>
                                    <?php else: ?>
                                        <?= esc($mention->author_name ?? 'Anonymous') ?>
                                    <?php endif ?>
                                </div>
                            </div>
                            <?php if ($mention->content): ?>
                                <blockquote>
                                    &ldquo;<?= esc($mention->content) ?>&rdquo;
                                    <a href="<?= esc($mention->source) ?>" class="webmentions__source">View original →</a>
                                </blockquote>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <?php if (!empty($reposts)): ?>
            <div class="webmentions__facepile">
                <h3 class="webmentions__subheading"><?= count($reposts) ?> reposts</h3>
                <ul class="webmentions__avatars">
                    <?php foreach ($reposts as $mention): ?>
                        <li>
                            <?php $name = esc($mention->author_name ?? $mention->source); ?>
                            <?php if ($mention->author_url): ?>
                                <a href="<?= esc($mention->author_url) ?>" title="<?= $name ?>">
                            <?php endif ?>
                            <?php if ($mention->author_photo): ?>
                                <img src="<?= esc($mention->author_photo) ?>" alt="<?= $name ?>" width="48" height="48" loading="lazy">
                            <?php else: ?>
                                <span class="webmentions__avatar-placeholder"><?= mb_substr($mention->author_name ?? '?', 0, 1) ?></span>
                            <?php endif ?>
                            <?php if ($mention->author_url): ?>
                                </a>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <?php if (!empty($likes)): ?>
            <div class="webmentions__facepile">
                <h3 class="webmentions__subheading"><?= count($likes) ?> likes/bookmarks</h3>
                <ul class="webmentions__avatars">
                    <?php foreach ($likes as $mention): ?>
                        <li>
                            <?php $name = esc($mention->author_name ?? $mention->source); ?>
                            <?php if ($mention->author_url): ?>
                                <a href="<?= esc($mention->author_url) ?>" title="<?= $name ?>">
                            <?php endif ?>
                            <?php if ($mention->author_photo): ?>
                                <img src="<?= esc($mention->author_photo) ?>" alt="<?= $name ?>" width="48" height="48" loading="lazy">
                            <?php else: ?>
                                <span class="webmentions__avatar-placeholder"><?= mb_substr($mention->author_name ?? '?', 0, 1) ?></span>
                            <?php endif ?>
                            <?php if ($mention->author_url): ?>
                                </a>
                            <?php endif ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

    </section>

</div>

<?php endif ?>

</div>
