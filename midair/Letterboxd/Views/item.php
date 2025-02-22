<article class="letterboxd">
    <div class="meta">
        <img class="source-icon" src="/images/letterboxd.svg" width="20" height="20" alt="" />
        <span class="ma-type letterboxd">Letterboxd</span>
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
    <p><?= $data->excerpt ?></p>
    <p class="read-more"><a href="<?= $data->source ?>" target="_blank">Read full entry on Letterboxd</a></p>
</article>
