<article class="strava">
    <div class="meta">
        <img class="source-icon" src="/images/strava.svg" width="20" height="20" alt="" />
        <span class="ma-type strava">Strava</span>
        <span aria-hidden="true">&bull;</span>
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        <span aria-hidden="true">&bull;</span>
        <a href="<?= $data->source ?>" class="external" target="_blank" aria-label="Open Strava activity in new tab"><span class="material-symbols-outlined">open_in_new</span></a>
    </div>
    <p><?= str_replace(', ', '<br />', $data->content) ?></p>
    <p class="read-more"><a href="<?= $data->source ?>" target="_blank">View &lsquo;<?= $data->title ?>&rsquo; on Strava</a></p>
</article>
