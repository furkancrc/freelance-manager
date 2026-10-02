<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
<<<<<<< HEAD
    <title><?= htmlspecialchars($title ?? 'Freelance Manager') ?></title>
=======
    <title><?= htmlspecialchars($title ?? "Freelance Manager") ?></title>
>>>>>>> dccf881 (feat: clean auth and seed)
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body>
    <header>
        <strong>Freelance Manager</strong>
        <?php if ($user = \App\Core\Security::currentUser()): ?>
<<<<<<< HEAD
            <span><?= htmlspecialchars($user['email']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
=======
            <span><?= htmlspecialchars($user["email"]) ?> (<?= htmlspecialchars(
     $user["role"],
 ) ?>)</span>
>>>>>>> dccf881 (feat: clean auth and seed)
            <form method="post" action="/logout" style="display:inline">
                <button type="submit">Se déconnecter</button>
            </form>
        <?php endif; ?>
    </header>
    <main>
        <?= $content ?>
    </main>
</body>
</html>
