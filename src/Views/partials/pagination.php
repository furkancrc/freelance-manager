<?php

use App\Core\View;
/** @var int $page */
/** @var int $pages */
/** @var int $total */
?>
<nav class="pagination" aria-label="Pagination">
    <?php if ($page > 1): ?>
        <a href="<?= View::e(
            View::pageUrl($page - 1),
        ) ?>" rel="prev">← Précédent</a>
    <?php else: ?>
        <span class="pagination-off">← Précédent</span>
    <?php endif; ?>

    <span class="pagination-info">
        Page <?= $page ?> / <?= $pages ?> · <?= $total ?> résultat<?= $total > 1
     ? "s"
     : "" ?>
    </span>

    <?php if ($page < $pages): ?>
        <a href="<?= View::e(
            View::pageUrl($page + 1),
        ) ?>" rel="next">Suivant →</a>
    <?php else: ?>
        <span class="pagination-off">Suivant →</span>
    <?php endif; ?>
</nav>
