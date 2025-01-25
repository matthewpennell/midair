<article class="review">
    <div class="meta">
        <span class="ma-type review">Review</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="/review/<?= $data->url ?>">🔗</a>
    </div>
    <h2>
        <?= $data->title ?>
    </h2>
    <?= $data->excerpt ?>
</article>
