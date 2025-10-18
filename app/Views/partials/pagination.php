<?php
$total_pages = ceil($total_count / $per_page);
if ($total_pages <= 1) {
    echo '<nav class="pagination"></nav>';
    return;
}

// Calculate the range of pages to show (up to 5 pages)
$range = 2; // Number of pages to show on each side of the current page
$start = max(1, $page - $range);
$end = min($total_pages, $page + $range);

// Adjust if we're near the start or end
if ($page <= $range + 1) {
    $end = min(2 * $range + 1, $total_pages);
}
if ($page >= $total_pages - $range) {
    $start = max(1, $total_pages - 2 * $range);
}
?>

<nav class="pagination" role="navigation" aria-label="Pagination">
    <?php if ($page > 1): ?>
        <a href="/<?= stristr($search, ',') ? $type : $search ?>/<?= $page - 1 ?>" class="pagination__link pagination__link--prev" rel="prev">&lt;</a>
    <?php endif; ?>

    <div class="pagination__pages">
        <?php if ($start > 1): ?>
            <a href="/<?= stristr($search, ',') ? $type : $search ?>/1" class="pagination__link">1</a>
            <?php if ($start > 2): ?>
                <span class="pagination__ellipsis">…</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="pagination__link pagination__link--current" aria-current="page"><?= $i ?></span>
            <?php else: ?>
                <a href="/<?= stristr($search, ',') ? $type : $search ?>/<?= $i ?>" class="pagination__link"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($end < $total_pages): ?>
            <?php if ($end < $total_pages - 1): ?>
                <span class="pagination__ellipsis">…</span>
            <?php endif; ?>
            <a href="/<?= stristr($search, ',') ? $type : $search ?>/<?= $total_pages ?>" class="pagination__link"><?= $total_pages ?></a>
        <?php endif; ?>
    </div>

    <?php if ($page < $total_pages): ?>
        <a href="/<?= stristr($search, ',') ? $type : $search ?>/<?= $page + 1 ?>" class="pagination__link pagination__link--next" rel="next">&gt;</a>
    <?php endif; ?>
</nav>
