<div class="hexagon content__date">
    <div class="hexagon__inner">
        <span class="month"><?= date('M', strtotime($data->date)) ?></span>
        <span class="day"><?= date('d', strtotime($data->date)) ?></span>
    </div>
</div>
<div class="content__item">
    <h3>
        <a href="/flashfiction" class="category">Flash fiction</a>
        <a href="/flashfiction/<?= $data->url ?>"><?= $data->title ?></a>
    </h3>
    <p><?= $data->excerpt ?></p>
</div>
