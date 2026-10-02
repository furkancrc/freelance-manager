<?php
/** @var array $user */
ob_start(); ?>
<h1>Bienvenue</h1>

<p>
    Vous êtes connecté en tant que <?= htmlspecialchars($user["email"]) ?>
    (<?= htmlspecialchars($user["role"]) ?>).
</p>

<p><a href="/mentions-legales">Mentions légales</a></p>
<?php
$content = ob_get_clean();
$title = "Accueil";
require __DIR__ . "/../layouts/main.php";
