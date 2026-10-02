<?php
use App\Core\View;

/** @var array $applications */
/** @var string|null $status */
/** @var string[] $statuses */
ob_start();
?>
<div class="page-head">
    <div>
        <h1>Mes candidatures</h1>
        <p class="muted">Suivez les réponses à vos candidatures.</p>
    </div>
</div>

<form class="filters filters-narrow" method="get" action="/candidatures">
    <select name="status" aria-label="Statut">
        <option value="">Tous les statuts</option>
        <?= View::options($statuses, $status) ?>
    </select>
    <button class="button secondary" type="submit">Filtrer</button>
</form>

<?php if ($applications === []): ?>
    <p class="empty">
        Aucune candidature. <a href="/missions?status=open">Voir les missions ouvertes</a>
    </p>
<?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <th>Mission</th>
                <th>Lieu</th>
                <th class="num">TJM proposé</th>
                <th>Statut</th>
                <th class="num">Envoyée le</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applications as $application): ?>
                <tr>
                    <td>
                        <a href="/missions/<?= (int) $application[
                            "mission_id"
                        ] ?>">
                            <?= View::e($application["mission_title"]) ?>
                        </a>
                    </td>
                    <td data-label="Lieu"><?= View::e(
                        $application["mission_location"] ?? "—",
                    ) ?></td>
                    <td data-label="TJM proposé" class="num"><?= View::money(
                        $application["proposed_rate"],
                    ) ?></td>
                    <td data-label="Statut"><?= View::tag(
                        $application["status"],
                    ) ?></td>
                    <td data-label="Envoyée le" class="num"><?= View::date(
                        $application["created_at"],
                    ) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php
$content = ob_get_clean();
$title = "Mes candidatures - Freelance-Manager";
require __DIR__ . "/../layouts/main.php";

