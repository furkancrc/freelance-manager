<?php
/** @var string $csrfToken */
/** @var string|null $error */
<<<<<<< HEAD
ob_start();
?>
=======
ob_start(); ?>
>>>>>>> dccf881 (feat: clean auth and seed)
<h1>Connexion</h1>

<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" action="/login">
<<<<<<< HEAD
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
=======
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
        $csrfToken,
    ) ?>">
>>>>>>> dccf881 (feat: clean auth and seed)

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required autofocus>

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
<?php
$content = ob_get_clean();
<<<<<<< HEAD
$title = 'Connexion';
require __DIR__ . '/../layouts/auth.php';
=======
$title = "Connexion";
require __DIR__ . "/../layouts/auth.php";

>>>>>>> dccf881 (feat: clean auth and seed)
