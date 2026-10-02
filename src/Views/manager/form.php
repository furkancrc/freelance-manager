<?php
use App\Core\View;

/** @var array $manager */
/** @var bool $isProfile */
$isEdit = isset($manager["user_id"]);

if ($isProfile) {
    $heading = "Mon profil";
    $redirect = "/profil";
    $flash = "Profil mis à jour.";
} elseif ($isEdit) {
    $heading = "Modifier le manager";
    $redirect = "/managers";
    $flash = "Manager mis à jour.";
} else {
    $heading = "Nouveau manager";
    $redirect = "/managers";
    $flash = "Manager créé.";
}
ob_start(); ?>
<?php if (!$isProfile): ?>
    <p class="back"><a href="/managers">← Managers</a></p>
<?php endif; ?>

<div class="page-head">
    <div>
        <h1><?= $heading ?></h1>
        <?php if ($isEdit): ?>
            <p class="muted"><?= View::e($manager["email"]) ?></p>
        <?php endif; ?>
    </div>
</div>

<form class="form" novalidate
      data-api="<?= $isEdit ? "/api/managers/" . (int) $manager["user_id"] : "/api/managers" ?>"
      data-method="<?= $isEdit ? "PUT" : "POST" ?>"
      data-redirect="<?= $redirect ?>"
      data-flash="<?= $flash ?>">
    <?php if (!$isEdit): ?>
        <div class="field-row">
            <label class="field">
                <span>Email</span>
                <input name="email" type="email" autocomplete="off">
            </label>
            <label class="field">
                <span>Mot de passe</span>
                <input name="password" type="password" autocomplete="new-password">
            </label>
        </div>
    <?php endif; ?>
    <div class="field-row">
        <label class="field">
            <span>Prénom</span>
            <input name="first_name" value="<?= View::e($manager["first_name"] ?? "") ?>">
        </label>
        <label class="field">
            <span>Nom</span>
            <input name="last_name" value="<?= View::e($manager["last_name"] ?? "") ?>">
        </label>
    </div>
    <div class="field-row">
        <label class="field">
            <span>Département</span>
            <input name="department" value="<?= View::e($manager["department"] ?? "") ?>">
        </label>
        <label class="field">
            <span>Téléphone</span>
            <input name="phone" type="tel" value="<?= View::e($manager["phone"] ?? "") ?>">
        </label>
    </div>
    <p class="form-error"></p>
    <div class="form-actions">
        <button class="button" type="submit"><?= $isEdit ? "Enregistrer" : "Créer le manager" ?></button>
        <?php if (!$isProfile): ?>
            <a class="button secondary" href="/managers">Annuler</a>
        <?php endif; ?>
    </div>
</form>
<?php
$content = ob_get_clean();
$title = $heading . " — Freelance Manager";
require __DIR__ . "/../layouts/main.php";
