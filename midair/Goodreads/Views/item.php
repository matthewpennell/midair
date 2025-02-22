<article class="goodreads">
    <div class="meta">
        <img class="source-icon" src="/images/goodreads.png" width="20" height="20" alt="" />
        <span class="ma-type goodreads">Goodreads</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="<?= $data->source ?>" class="external" target="_blank"><span class="material-symbols-outlined">open_in_new</span></a>
    </div>
    <h2>
        <a href="<?= $data->source ?>" target="_blank">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->content ?></p>
</article>
