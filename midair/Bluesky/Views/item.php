<article class="bluesky">
    <div class="meta">
        <svg aria-hidden="true" fill="none" viewBox="0 0 64 57" width="20" style="width: 20px; height: 17.8125px;">
            <path fill="#0085ff" d="M13.873 3.805C21.21 9.332 29.103 20.537 32 26.55v15.882c0-.338-.13.044-.41.867-1.512 4.456-7.418 21.847-20.923 7.944-7.111-7.32-3.819-14.64 9.125-16.85-7.405 1.264-15.73-.825-18.014-9.015C1.12 23.022 0 8.51 0 6.55 0-3.268 8.579-.182 13.873 3.805ZM50.127 3.805C42.79 9.332 34.897 20.537 32 26.55v15.882c0-.338.13.044.41.867 1.512 4.456 7.418 21.847 20.923 7.944 7.111-7.32 3.819-14.64-9.125-16.85 7.405 1.264 15.73-.825 18.014-9.015C62.88 23.022 64 8.51 64 6.55c0-9.818-8.578-6.732-13.873-2.745Z"></path>
        </svg>
        <span class="ma-type bluesky">Bluesky</span>
        <span aria-hidden="true">&bull;</span>
        <time datetime="<?= $data->date ?>"><?= date('jS M Y', strtotime($data->date)) ?></time>
        <span aria-hidden="true">&bull;</span>
        <a href="/bluesky/<?= $data->url ?>" class="permalink" aria-label="Permalink"><span class="material-symbols-outlined">link</span></a>
        <span aria-hidden="true">&bull;</span>
        <a href="<?= $data->source ?>" class="external" target="_blank" aria-label="Open the original Bluesky post in a new tab"><span class="material-symbols-outlined">open_in_new</span></a>
    </div>
    <p><?= str_replace("\n", "<br>", $data->excerpt) ?></p>
</article>
