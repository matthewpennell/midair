<?= $content ?>
<?php if ($show_next > 0): ?>
    <div id="loading" hx-get="?p=<?= $next_page ?>" hx-trigger="revealed" hx-swap="outerHTML" aria-busy="true"></div>
</div>
<?php endif; ?>
