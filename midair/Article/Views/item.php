<article>
    <a href="/article/<?= $data->url ?>">
        <h2><?= $data->title ?></h2>
        <p><?= $data->excerpt ?></p>
        <p><?= date('jS M Y', strtotime($data->date)) ?></p>
    </a>
</article>
