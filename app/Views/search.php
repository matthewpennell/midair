<?= $this->extend('default') ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('og-title') ?><?= $title ?><?= $this->endSection() ?>
<?= $this->section('description') ?><?= $description ?><?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="list">
        <div class="list__container">
            <div class="list__line"></div>
        </div>
        <ul>
        <?php
        $currentYear = null;
        $isFirstItem = true;

        foreach ($articles as $article):
            $articleYear = date('Y', strtotime($article->date));
            
            // Display year header if this is the first item or year has changed
            if ($currentYear !== $articleYear):
                $currentYear = $articleYear;
                // Close previous list if not the first item
                if (!$isFirstItem) {
                    echo "</ul>";
                }
                ?>
                <div class="list__year">
                    <h2><?= $currentYear ?></h2>
                </div>
                <ul>
                <?php 
                $isFirstItem = false;
            endif; 
            ?>
            <li>
                <?= view('Midair\\' . ucfirst($article->type) . '\Views\item', [
                    'data' => $article,
                ], [
                    'cache' => 3600, // cache view for 1 hour
                    'cache_name' => ucfirst($article->type) . '-item-' . $article->id,
                ]); ?>
            </li>
        <?php endforeach; ?>
        <?php if (!$isFirstItem): ?>
            </ul>
        <?php endif; ?>
    </div>
<?= $this->endSection() ?>
