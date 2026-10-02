<?php
/** @var string $csrfToken */
/** @var string|null $error */
<<<<<<< HEAD
<<<<<<< HEAD
ob_start();
?>
=======
ob_start(); ?>
>>>>>>> dccf881 (feat: clean auth and seed)
=======
ob_start();
?>
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
<h1>Connexion</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="/login">
<<<<<<< HEAD
<<<<<<< HEAD
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
=======
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
        $csrfToken,
    ) ?>">
>>>>>>> dccf881 (feat: clean auth and seed)
=======
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required autofocus>

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
<?php
$content = ob_get_clean();
<<<<<<< HEAD
<<<<<<< HEAD
$title = 'Connexion';
require __DIR__ . '/../layouts/auth.php';
=======
$title = "Connexion";
require __DIR__ . "/../layouts/auth.php";

>>>>>>> dccf881 (feat: clean auth and seed)
=======
$title = 'Connexion';
require __DIR__ . '/../layouts/auth.php';
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
