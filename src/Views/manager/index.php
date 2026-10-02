<?php
use App\Core\View;

/** @var array $managers */
ob_start(); ?>
<div class="page-head">
    <div>
        <h1>Managers</h1>
        <p class="muted"><?= count($managers) ?> manager<?= count($managers) > 1 ? "s" : "" ?> · chefs de projet qui publient les missions.</p>
    </div>
    <a class="button" href="/managers/nouveau">Nouveau manager</a>
</div>
<p class="form-error" id="action-error"></p>

<?php if ($managers === []): ?>
    <p class="empty">Aucun manager pour le moment.</p>
<?php else: ?>
    <table class="list">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Département</th>
                <th>Téléphone</th>
                <th><span class="visually-hidden">Actions</span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($managers as $manager): ?>
                <tr>
                    <td>
                        <a href="/managers/<?= (int) $manager["user_id"] ?>/modifier">
                            <?= View::e($manager["first_name"] . " " . $manager["last_name"]) ?>
                        </a>
                    </td>
                    <td data-label="Email"><?= View::e($manager["email"]) ?></td>
                    <td data-label="Département"><?= View::e($manager["department"] ?? "—") ?></td>
                    <td data-label="Téléphone" class="nowrap"><?= View::e($manager["phone"] ?? "—") ?></td>
                    <td class="row-actions">
                        <a class="button secondary small" href="/managers/<?= (int) $manager["user_id"] ?>/modifier">Modifier</a>
                        <button class="button danger small" type="button"
                                data-api="/api/managers/<?= (int) $manager["user_id"] ?>"
                                data-method="DELETE"
                                data-confirm="Supprimer ce manager et son compte ?"
                                data-flash="Manager supprimé.">
                            Supprimer
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php
$content = ob_get_clean();
$title = "Managers — Freelance Manager";
require __DIR__ . "/../layouts/main.php";
