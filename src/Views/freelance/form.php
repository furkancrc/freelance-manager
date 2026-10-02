<?php
use App\Core\View;

/** @var array $freelance */
/** @var string[] $availabilities */
/** @var bool $isProfile */
$isEdit = isset($freelance["id"]);
$back = $isEdit ? "/freelances/" . (int) $freelance["id"] : "/freelances";

if ($isProfile) {
    $heading = "Mon profil";
    $redirect = "/profil";
    $flash = "Profil mis à jour.";
} elseif ($isEdit) {
    $heading = "Modifier le freelance";
    $redirect = "/freelances/{id}";
    $flash = "Freelance mis à jour.";
} else {
    $heading = "Nouveau freelance";
    $redirect = "/freelances/{id}";
    $flash = "Freelance créé.";
}
ob_start();
?>
<?php if (!$isProfile): ?>
    <p class="back"><a href="<?= $back ?>">← Retour</a></p>
<?php endif; ?>

<div class="page-head">
    <div>
        <h1><?= $heading ?></h1>
        <?php if ($isProfile): ?>
            <p class="muted"><?= View::e($freelance["email"]) ?></p>
        <?php endif; ?>
    </div>
</div>

<form class="form" novalidate
      data-api="<?= $isEdit
          ? "/api/freelances/" . (int) $freelance["id"]
          : "/api/freelances" ?>"
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
            <input name="first_name" value="<?= View::e(
                $freelance["first_name"] ?? "",
            ) ?>">
        </label>
        <label class="field">
            <span>Nom</span>
            <input name="last_name" value="<?= View::e(
                $freelance["last_name"] ?? "",
            ) ?>">
        </label>
    </div>
    <label class="field">
        <span>Titre</span>
        <input name="title" value="<?= View::e(
            $freelance["title"] ?? "",
        ) ?>" placeholder="Ex. Développeuse PHP">
    </label>
    <label class="field">
        <span>Présentation</span>
        <textarea name="bio"><?= View::e($freelance["bio"] ?? "") ?></textarea>
    </label>
    <div class="field-row">
        <label class="field">
            <span>TJM (€)</span>
            <input name="daily_rate" type="number" min="0" step="1" value="<?= View::e(
                $freelance["daily_rate"] ?? "",
            ) ?>">
        </label>
        <label class="field">
            <span>Disponibilité</span>
            <select name="availability"><?= View::options(
                $availabilities,
                $freelance["availability"],
            ) ?></select>
        </label>
    </div>
    <label class="field">
        <span>Lieu</span>
        <input name="location" value="<?= View::e(
            $freelance["location"] ?? "",
        ) ?>">
    </label>
    <p class="form-error"></p>
    <div class="form-actions">
        <button class="button" type="submit"><?= $isEdit
            ? "Enregistrer"
            : "Créer le freelance" ?></button>
        <?php if (!$isProfile): ?>
            <a class="button secondary" href="<?= $back ?>">Annuler</a>
        <?php endif; ?>
    </div>
</form>
<?php
$content = ob_get_clean();
$title = $heading . " - Freelance-Manager";
require __DIR__ . "/../layouts/main.php";

