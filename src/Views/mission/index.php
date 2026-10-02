<?php
use App\Core\View;

/** @var array $user */
/** @var array $missions */
/** @var array $filters */
/** @var array $stats */
/** @var string[] $statuses */
$isManager = $user["role"] === "manager";
$onlyMine = $filters["manager_user_id"] !== null;
ob_start(); ?>
<div class="page-head">
    <div>
        <h1>Missions</h1>
        <p class="muted">
            <?= (int) $stats["total_missions"] ?> missions publiées ·
            budget moyen <?= View::money($stats["avg_budget"]) ?>
        </p>
    </div>
    <?php if ($isManager): ?>
        <a class="button" href="/missions/nouvelle">Nouvelle mission</a>
    <?php endif; ?>
</div>

<form class="filters" method="get" action="/missions" role="search">
    <input name="q" type="search" value="<?= View::e($filters["q"]) ?>"
           placeholder="Titre, description…" aria-label="Recherche">
    <select name="status" aria-label="Statut">
        <option value="">Tous les statuts</option>
        <?= View::options($statuses, $filters["status"]) ?>
    </select>
    <input name="location" value="<?= View::e($filters["location"]) ?>"
           placeholder="Lieu" aria-label="Lieu">
    <?php if ($isManager): ?>
        <label class="check">
            <input type="checkbox" name="mine" value="1" <?= $onlyMine ? "checked" : "" ?>>
            Mes missions
        </label>
    <?php endif; ?>
    <button class="button secondary" type="submit">Filtrer</button>
</form>

<?php if ($missions === []): ?>
    <p class="empty">Aucune mission ne correspond à la recherche.</p>
<?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <th>Mission</th>
                <th>Lieu</th>
                <th class="num">TJM</th>
                <th class="num">Budget</th>
                <th>Période</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($missions as $mission): ?>
                <tr>
                    <td><a href="/missions/<?= (int) $mission["id"] ?>"><?= View::e($mission["title"]) ?></a></td>
                    <td data-label="Lieu"><?= View::e($mission["location"] ?? "—") ?></td>
                    <td data-label="TJM" class="num"><?= View::money($mission["daily_rate"]) ?></td>
                    <td data-label="Budget" class="num"><?= View::money($mission["budget"]) ?></td>
                    <td data-label="Période" class="nowrap">
                        <?= View::date($mission["start_date"]) ?> → <?= View::date($mission["end_date"]) ?>
                    </td>
                    <td data-label="Statut"><?= View::tag($mission["status"]) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . "/../partials/pagination.php"; ?>
<?php
$content = ob_get_clean();
$title = "Missions — Freelance Manager";
$description = "Rechercher et consulter les missions freelance publiées.";
require __DIR__ . "/../layouts/main.php";
