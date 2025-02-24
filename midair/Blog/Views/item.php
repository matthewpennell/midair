<blog class="blog">
    <div class="meta">
        <span class="ma-type blog">Blog</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="/blog/<?= $data->url ?>" class="permalink"><span class="material-symbols-outlined">link</span></a>
    </div>
    <h2>
        <a href="/blog/<?= $data->url ?>">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->excerpt ?></p>
    <p class="read-more"><a href="/blog/<?= $data->url ?>">Read full blog post</a></p>
</blog>
