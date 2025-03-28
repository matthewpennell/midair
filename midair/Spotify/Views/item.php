<article class="spotify">
    <div class="meta">
        <img class="source-icon" src="/images/spotify.svg" width="20" height="20" alt="" />
        <span class="ma-type spotify">Spotify</span>
        <span aria-hidden="true">&bull;</span>
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        <span aria-hidden="true">&bull;</span>
        <a href="<?= $data->source ?>" class="external" target="_blank" aria-label="Go to this album on Spotify"><span class="material-symbols-outlined">open_in_new</span></a>
    </div>
    <div class="item">
        <img src="<?= $data->url ?>" width="150" height="150" />
        <p>Added <em><?= $data->title ?></em> by <?= $data->excerpt ?> (from the album <em><?= $data->content ?></em>) to favourite tracks.</p>
    </div>
</article>
