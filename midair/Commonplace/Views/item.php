<article class="commonplace">
    <div class="meta">
        <span class="ma-type commonplace">Commonplace</span>
        &bull;
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        &bull;
        <a href="<?= $data->source ?>" class="permalink" target="_blank"><span class="material-symbols-outlined">link</span></a>
    </div>
    <?= $data->content ?>
</article>
