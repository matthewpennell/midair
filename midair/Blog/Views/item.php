<article class="blog">
    <div class="meta">
        <span class="ma-type blog">Blog</span>
        <span aria-hidden="true">&bull;</span>
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        <span aria-hidden="true">&bull;</span>
        <a href="/blog/<?= $data->url ?>" class="permalink" aria-label="Permalink"><span class="material-symbols-outlined">link</span></a>
    </div>
    <h2>
        <a href="/blog/<?= $data->url ?>">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->excerpt ?></p>
    <p class="read-more"><a href="/blog/<?= $data->url ?>">Read full blog post</a></p>
</article>
