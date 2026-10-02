<?php
use App\Core\View;

/** @var array $user */
/** @var array $mission */
/** @var bool $canEdit */
/** @var array $applications */
/** @var bool $isFavorite */
/** @var array|null $myApplication */
$id = (int) $mission["id"];
ob_start(); ?>
<p class="back"><a href="/missions">← Missions</a></p>

<article>
    <div class="page-head">
        <div>
            <p class="eyebrow">Mission n°<?= $id ?></p>
            <h1><?= View::e($mission["title"]) ?></h1>
        </div>

        <div class="actions">
            <?php if ($user["role"] === "freelance"): ?>
                <button class="button secondary" type="button"
                        data-api="/api/missions/<?= $id ?>/favorites"
                        data-method="<?= $isFavorite ? "DELETE" : "POST" ?>"
                        data-flash="<?= $isFavorite ? "Retirée des favoris." : "Ajoutée aux favoris." ?>">
                    <?= $isFavorite ? "★ Retirer des favoris" : "☆ Ajouter aux favoris" ?>
                </button>
            <?php endif; ?>

            <?php if ($canEdit): ?>
                <a class="button secondary" href="/missions/<?= $id ?>/modifier">Modifier</a>
                <button class="button danger" type="button"
                        data-api="/api/missions/<?= $id ?>"
                        data-method="DELETE"
                        data-confirm="Supprimer définitivement cette mission ?"
                        data-redirect="/missions"
                        data-flash="Mission supprimée.">
                    Supprimer
                </button>
            <?php endif; ?>
        </div>
    </div>
    <p class="form-error" id="action-error"></p>

    <div class="split">
        <aside>
            <dl class="meta">
                <div><dt>Statut</dt><dd><?= View::tag($mission["status"]) ?></dd></div>
                <div><dt>Lieu</dt><dd><?= View::e($mission["location"] ?? "—") ?></dd></div>
                <div><dt>Budget</dt><dd class="num"><?= View::money($mission["budget"]) ?></dd></div>
                <div><dt>TJM</dt><dd class="num"><?= View::money($mission["daily_rate"]) ?></dd></div>
                <div><dt>Début</dt><dd class="num"><?= View::date($mission["start_date"]) ?></dd></div>
                <div><dt>Fin</dt><dd class="num"><?= View::date($mission["end_date"]) ?></dd></div>
                <div><dt>Publiée le</dt><dd class="num"><?= View::date($mission["created_at"]) ?></dd></div>
            </dl>
        </aside>

        <div>
            <p class="description"><?= View::e($mission["description"]) ?></p>

            <?php if ($user["role"] === "freelance"): ?>
                <section>
                    <h2 class="section-title">Candidature</h2>

                    <?php if ($myApplication !== null): ?>
                        <p class="note">
                            Vous avez postulé le <?= View::date($myApplication["created_at"]) ?>
                            — <?= View::tag($myApplication["status"]) ?>
                        </p>
                    <?php elseif ($mission["status"] === "open"): ?>
                        <form class="form" novalidate
                              data-api="/api/missions/<?= $id ?>/applications"
                              data-method="POST"
                              data-redirect="/candidatures"
                              data-flash="Candidature envoyée.">
                            <label class="field">
                                <span>Message</span>
                                <textarea name="message" placeholder="Présentez-vous en quelques lignes"></textarea>
                            </label>
                            <label class="field">
                                <span>TJM proposé (€)</span>
                                <input name="proposed_rate" type="number" min="0" step="1"
                                       value="<?= View::e($mission["daily_rate"]) ?>">
                            </label>
                            <p class="form-error"></p>
                            <div class="form-actions">
                                <button class="button" type="submit">Envoyer ma candidature</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="note muted">Cette mission n'accepte plus de candidatures.</p>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <?php if ($canEdit): ?>
                <section>
                    <h2 class="section-title">Candidatures reçues (<?= count($applications) ?>)</h2>

                    <?php if ($applications === []): ?>
                        <p class="empty">Aucune candidature pour le moment.</p>
                    <?php else: ?>
                        <table class="list">
                            <thead>
                                <tr>
                                    <th>Freelance</th>
                                    <th>Message</th>
                                    <th class="num">TJM proposé</th>
                                    <th>Statut</th>
                                    <th class="num">Reçue le</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($applications as $application): ?>
                                    <tr>
                                        <td>
                                            <a href="/freelances/<?= (int) $application["freelance_id"] ?>">
                                                <?= View::e($application["first_name"] . " " . $application["last_name"]) ?>
                                            </a>
                                            <br><span class="muted"><?= View::e($application["email"]) ?></span>
                                        </td>
                                        <td data-label="Message" class="cell-text"><?= View::e($application["message"] ?? "—") ?></td>
                                        <td data-label="TJM proposé" class="num"><?= View::money($application["proposed_rate"]) ?></td>
                                        <td data-label="Statut"><?= View::tag($application["status"]) ?></td>
                                        <td data-label="Reçue le" class="num"><?= View::date($application["created_at"]) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </div>
    </div>
</article>
<?php
$content = ob_get_clean();
$title = $mission["title"] . " — Freelance Manager";
$description = "Mission freelance : " . $mission["title"];
require __DIR__ . "/../layouts/main.php";
