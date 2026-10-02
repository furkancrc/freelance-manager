<?php
use App\Core\View;

/** @var array $user */
/** @var array $freelances */
/** @var array $filters */
/** @var string[] $availabilities */
ob_start();
?>
<div class="page-head">
    <div>
        <h1>Freelances</h1>
        <p class="muted">Le vivier de freelances de l'entreprise.</p>
    </div>
    <?php if ($user["role"] === "admin"): ?>
        <a class="button" href="/freelances/nouveau">Nouveau freelance</a>
    <?php endif; ?>
</div>

<form class="filters filters-wide" method="get" action="/freelances" role="search">
    <input name="q" type="search" value="<?= View::e($filters["q"]) ?>"
           placeholder="Nom, prénom ou titre…" aria-label="Recherche">
    <select name="availability" aria-label="Disponibilité">
        <option value="">Toutes disponibilités</option>
        <?= View::options($availabilities, $filters["availability"]) ?>
    </select>
    <input name="location" value="<?= View::e($filters["location"]) ?>"
           placeholder="Lieu" aria-label="Lieu">
    <input name="min_rate" type="number" min="0" value="<?= View::e(
        $filters["min_rate"],
    ) ?>"
           placeholder="TJM min" aria-label="TJM minimum">
    <input name="max_rate" type="number" min="0" value="<?= View::e(
        $filters["max_rate"],
    ) ?>"
           placeholder="TJM max" aria-label="TJM maximum">
    <button class="button secondary" type="submit">Filtrer</button>
</form>

<?php if ($freelances === []): ?>
    <p class="empty">Aucun freelance ne correspond à la recherche.</p>
<?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Titre</th>
                <th>Lieu</th>
                <th class="num">TJM</th>
                <th>Disponibilité</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($freelances as $freelance): ?>
                <tr>
                    <td>
                        <a href="/freelances/<?= (int) $freelance["id"] ?>">
                            <?= View::e(
                                $freelance["first_name"] .
                                    " " .
                                    $freelance["last_name"],
                            ) ?>
                        </a>
                    </td>
                    <td data-label="Titre"><?= View::e(
                        $freelance["title"] ?? "—",
                    ) ?></td>
                    <td data-label="Lieu"><?= View::e(
                        $freelance["location"] ?? "—",
                    ) ?></td>
                    <td data-label="TJM" class="num"><?= View::money(
                        $freelance["daily_rate"],
                    ) ?></td>
                    <td data-label="Disponibilité"><?= View::tag(
                        $freelance["availability"],
                    ) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require __DIR__ . "/../partials/pagination.php"; ?>
<?php
$content = ob_get_clean();
$title = "Freelances - Freelance Manager";
require __DIR__ . "/../layouts/main.php";

