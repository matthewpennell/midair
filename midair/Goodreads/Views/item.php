<article class="goodreads">
    <div class="meta">
        <span class="ma-type goodreads">Goodreads</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="<?= $data->source ?>" class="permalink" target="_blank"><span class="material-symbols-outlined">link</span></a>
    </div>
    <h2>
        <a href="<?= $data->source ?>" target="_blank">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->content ?></p>
    <p class="read-more"><a href="<?= $data->source ?>" target="_blank">Read full entry on Goodreads</a></p>
</article>
