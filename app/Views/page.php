<?= $content ?>
<?php if ($show_next > 0): ?>
    <div hx-get="/?p=<?= $next_page ?>" hx-trigger="revealed" hx-swap="outerHTML">
        spinner.gif
</div>
<?php endif; ?>
