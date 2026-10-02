<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    <title><?= htmlspecialchars($title ?? 'Freelance Manager') ?></title>
=======
    <title><?= htmlspecialchars($title ?? "Freelance Manager") ?></title>
>>>>>>> dccf881 (feat: clean auth and seed)
=======
    <title><?= htmlspecialchars($title ?? 'Freelance Manager') ?></title>
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
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
<<<<<<< HEAD
<<<<<<< HEAD
            <span><?= htmlspecialchars($user['email']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
=======
            <span><?= htmlspecialchars($user["email"]) ?> (<?= htmlspecialchars(
     $user["role"],
 ) ?>)</span>
>>>>>>> dccf881 (feat: clean auth and seed)
=======
            <span><?= htmlspecialchars($user['email']) ?> (<?= htmlspecialchars($user['role']) ?>)</span>
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
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
