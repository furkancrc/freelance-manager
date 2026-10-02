<?php
use App\Core\View;

/** @var array $favorites */
ob_start();
?>
<div class="page-head">
    <div>
        <h1>Mes favoris</h1>
        <p class="muted">Les missions que vous avez mises de côté.</p>
    </div>
</div>

<?php if ($favorites === []): ?>
    <p class="empty">
        Aucun favori. <a href="/missions">Parcourir les missions</a>
    </p>
<?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <th>Mission</th>
                <th>Lieu</th>
                <th class="num">TJM</th>
                <th>Statut</th>
                <th class="num">Ajoutée le</th>
                <th><span class="visually-hidden">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($favorites as $mission): ?>
                <tr>
                    <td><a href="/missions/<?= (int) $mission[
                        "id"
                    ] ?>"><?= View::e($mission["title"]) ?></a></td>
                    <td data-label="Lieu"><?= View::e(
                        $mission["location"] ?? "—",
                    ) ?></td>
                    <td data-label="TJM" class="num"><?= View::money(
                        $mission["daily_rate"],
                    ) ?></td>
                    <td data-label="Statut"><?= View::tag(
                        $mission["status"],
                    ) ?></td>
                    <td data-label="Ajoutée le" class="num"><?= View::date(
                        $mission["favorited_at"],
                    ) ?></td>
                    <td class="row-actions">
                        <button class="button secondary small" type="button"
                                data-api="/api/missions/<?= (int) $mission[
                                    "id"
                                ] ?>/favorites"
                                data-method="DELETE"
                                data-flash="Retirée des favoris.">
                            Retirer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php
$content = ob_get_clean();
$title = "Mes favoris - Freelance-Manager";
require __DIR__ . "/../layouts/main.php";

