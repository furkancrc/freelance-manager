<?php
use App\Core\View;

/** @var array $user */
/** @var array $freelance */
/** @var array $reviews */
/** @var float|null $average */
/** @var array $closedMissions */
$id = (int) $freelance["id"];
$fullName = $freelance["first_name"] . " " . $freelance["last_name"];
ob_start(); ?>
<p class="back"><a href="/freelances">← Freelances</a></p>

<article>
    <div class="page-head">
        <div>
            <p class="eyebrow"><?= View::e($freelance["title"] ?? "Freelance") ?></p>
            <h1><?= View::e($fullName) ?></h1>
        </div>

        <?php if ($user["role"] === "admin"): ?>
            <div class="actions">
                <a class="button secondary" href="/freelances/<?= $id ?>/modifier">Modifier</a>
                <button class="button danger" type="button"
                        data-api="/api/freelances/<?= $id ?>"
                        data-method="DELETE"
                        data-confirm="Supprimer ce freelance et son compte ?"
                        data-redirect="/freelances"
                        data-flash="Freelance supprimé.">
                    Supprimer
                </button>
            </div>
        <?php endif; ?>
    </div>
    <p class="form-error" id="action-error"></p>

    <div class="split">
        <aside>
            <dl class="meta">
                <div><dt>Disponibilité</dt><dd><?= View::tag($freelance["availability"]) ?></dd></div>
                <div><dt>Email</dt><dd><a href="mailto:<?= View::e($freelance["email"]) ?>"><?= View::e($freelance["email"]) ?></a></dd></div>
                <div><dt>Lieu</dt><dd><?= View::e($freelance["location"] ?? "—") ?></dd></div>
                <div><dt>TJM</dt><dd class="num"><?= View::money($freelance["daily_rate"]) ?></dd></div>
                <div>
                    <dt>Note moyenne</dt>
                    <dd class="num"><?= $average === null ? "—" : number_format($average, 1, ",", "") . " / 5" ?></dd>
                </div>
                <div><dt>Inscrit le</dt><dd class="num"><?= View::date($freelance["created_at"]) ?></dd></div>
            </dl>
        </aside>

        <div>
            <p class="description"><?= View::e($freelance["bio"] ?? "Pas de présentation.") ?></p>

            <section>
                <h2 class="section-title">Évaluations (<?= count($reviews) ?>)</h2>

                <?php if ($reviews === []): ?>
                    <p class="empty">Aucune évaluation pour le moment.</p>
                <?php else: ?>
                    <ul class="reviews">
                        <?php foreach ($reviews as $review): ?>
                            <li>
                                <p class="review-head">
                                    <span class="rating" aria-label="<?= (int) $review["rating"] ?> sur 5">
                                        <?= str_repeat("■", (int) $review["rating"]) ?><span><?= str_repeat("■", 5 - (int) $review["rating"]) ?></span>
                                    </span>
                                    <?= View::e($review["mission_title"]) ?>
                                </p>
                                <?php if (!empty($review["comment"])): ?>
                                    <p><?= View::e($review["comment"]) ?></p>
                                <?php endif; ?>
                                <p class="muted">
                                    <?= View::e($review["manager_first_name"] . " " . $review["manager_last_name"]) ?>
                                    · <?= View::date($review["created_at"]) ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <?php if ($user["role"] === "manager"): ?>
                <section>
                    <h2 class="section-title">Évaluer ce freelance</h2>

                    <?php if ($closedMissions === []): ?>
                        <p class="note muted">Vous n'avez aucune mission terminée à évaluer.</p>
                    <?php else: ?>
                        <form class="form" novalidate
                              data-api="/api/freelances/<?= $id ?>/reviews"
                              data-method="POST"
                              data-flash="Évaluation enregistrée.">
                            <div class="field-row">
                                <label class="field">
                                    <span>Mission</span>
                                    <select name="mission_id">
                                        <?php foreach ($closedMissions as $mission): ?>
                                            <option value="<?= (int) $mission["id"] ?>"><?= View::e($mission["title"]) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <label class="field">
                                    <span>Note</span>
                                    <select name="rating">
                                        <?php for ($rating = 5; $rating >= 1; $rating--): ?>
                                            <option value="<?= $rating ?>"><?= $rating ?> / 5</option>
                                        <?php endfor; ?>
                                    </select>
                                </label>
                            </div>
                            <label class="field">
                                <span>Commentaire</span>
                                <textarea name="comment"></textarea>
                            </label>
                            <p class="form-error"></p>
                            <div class="form-actions">
                                <button class="button" type="submit">Enregistrer l'évaluation</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </div>
    </div>
</article>
<?php
$content = ob_get_clean();
$title = $fullName . " — Freelance Manager";
require __DIR__ . "/../layouts/main.php";
