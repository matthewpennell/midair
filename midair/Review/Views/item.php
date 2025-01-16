<article>
    <h2>
        <a href="/review/<?= $data->url ?>">
            <?= $data->title ?>
        </a>
    </h2>
    <p><?= $data->excerpt ?></p>
    <small><?= date('jS M Y', strtotime($data->date)) ?></small>
</article>
