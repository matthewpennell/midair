<article class="article">
    <div class="meta">
        <span class="ma-type article">Article</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="/bluesky/<?= $data->url ?>">🔗</a>
    </div>
    <h2>
        <a href="/article/<?= $data->url ?>">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->excerpt ?></p>
    <p class="read-more"><a href="/article/<?= $data->url ?>">Read full article</a></p>
</article>
