<article class="letterboxd">
    <div class="meta">
        <img class="source-icon" src="/images/letterboxd.svg" width="20" height="20" alt="" />
        <span class="ma-type letterboxd">Letterboxd</span>
        <span aria-hidden="true">&bull;</span>
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        <span aria-hidden="true">&bull;</span>
        <a href="<?= $data->source ?>" class="external" target="_blank" aria-label="Open original Letterboxd entry in new tab"><span class="material-symbols-outlined">open_in_new</span></a>
    </div>
    <h2>
        <a href="<?= $data->source ?>" target="_blank">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->excerpt ?></p>
    <p class="read-more"><a href="<?= $data->source ?>" target="_blank">Read full entry on Letterboxd</a></p>
</article>
