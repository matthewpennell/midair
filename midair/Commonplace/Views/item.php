<article class="commonplace">
    <div class="meta">
        <span class="ma-type commonplace">Commonplace</span>
        <span aria-hidden="true">&bull;</span>
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        <span aria-hidden="true">&bull;</span>
        <a href="/commonplace/<?= $data->url ?>" class="permalink" aria-label="Permalink"><span class="material-symbols-outlined">link</span></a>
    </div>
    <?= $data->content ?>
</article>
