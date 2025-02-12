<article class="spotify">
    <div class="meta">
        <span class="ma-type spotify">Spotify</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="<?= $data->source ?>" class="permalink" target="_blank"><span class="material-symbols-outlined">link</span></a>
    </div>
    <img src="<?= $data->url ?>" width="150" height="150" />
    <p><strong><?= $data->title ?> by <?= $data->excerpt ?> (from the album <em><?= $data->content ?></em>)</p>
</article>
