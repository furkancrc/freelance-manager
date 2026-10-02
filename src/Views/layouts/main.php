<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title ?? "Freelance Manager") ?></title>
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body>
    <header>
        <strong>Freelance Manager</strong>
        <?php if ($user = \App\Core\Security::currentUser()): ?>
            <span><?= htmlspecialchars($user["email"]) ?> (<?= htmlspecialchars(
     $user["role"],
 ) ?>)</span>
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
