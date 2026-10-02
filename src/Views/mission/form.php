<?php
use App\Core\View;

/** @var array $mission */
/** @var string[] $statuses */
$isEdit = isset($mission["id"]);
$back = $isEdit ? "/missions/" . (int) $mission["id"] : "/missions";
ob_start();
?>
<p class="back"><a href="<?= $back ?>">← Retour</a></p>

<div class="page-head">
    <h1><?= $isEdit ? "Modifier la mission" : "Nouvelle mission" ?></h1>
</div>

<form class="form" novalidate
      data-api="<?= $isEdit
          ? "/api/missions/" . (int) $mission["id"]
          : "/api/missions" ?>"
      data-method="<?= $isEdit ? "PUT" : "POST" ?>"
      data-redirect="/missions/{id}"
      data-flash="<?= $isEdit ? "Mission mise à jour." : "Mission publiée." ?>">
    <label class="field">
        <span>Titre</span>
        <input name="title" value="<?= View::e($mission["title"] ?? "") ?>">
    </label>
    <label class="field">
        <span>Description</span>
        <textarea name="description"><?= View::e(
            $mission["description"] ?? "",
        ) ?></textarea>
    </label>
    <div class="field-row">
        <label class="field">
            <span>Budget (€)</span>
            <input name="budget" type="number" min="0" step="1" value="<?= View::e(
                $mission["budget"] ?? "",
            ) ?>">
        </label>
        <label class="field">
            <span>TJM (€)</span>
            <input name="daily_rate" type="number" min="0" step="1" value="<?= View::e(
                $mission["daily_rate"] ?? "",
            ) ?>">
        </label>
    </div>
    <div class="field-row">
        <label class="field">
            <span>Début</span>
            <input name="start_date" type="date" value="<?= View::e(
                $mission["start_date"] ?? "",
            ) ?>">
        </label>
        <label class="field">
            <span>Fin</span>
            <input name="end_date" type="date" value="<?= View::e(
                $mission["end_date"] ?? "",
            ) ?>">
        </label>
    </div>
    <div class="field-row">
        <label class="field">
            <span>Lieu</span>
            <input name="location" value="<?= View::e(
                $mission["location"] ?? "",
            ) ?>">
        </label>
        <label class="field">
            <span>Statut</span>
            <select name="status"><?= View::options(
                $statuses,
                $mission["status"],
            ) ?></select>
        </label>
    </div>
    <p class="form-error"></p>
    <div class="form-actions">
        <button class="button" type="submit"><?= $isEdit
            ? "Enregistrer"
            : "Publier la mission" ?></button>
        <a class="button secondary" href="<?= $back ?>">Annuler</a>
    </div>
</form>
<?php
$content = ob_get_clean();
$title =
    ($isEdit ? "Modifier la mission" : "Nouvelle mission") .
    " - Freelance-Manager";
require __DIR__ . "/../layouts/main.php";

