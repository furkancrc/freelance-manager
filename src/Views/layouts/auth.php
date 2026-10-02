<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
    <title><?= htmlspecialchars($title ?? 'Connexion') ?> — Freelance Manager</title>
=======
    <title><?= htmlspecialchars(
        $title ?? "Connexion",
    ) ?> — Freelance Manager</title>
>>>>>>> dccf881 (feat: clean auth and seed)
=======
    <title><?= htmlspecialchars($title ?? 'Connexion') ?> — Freelance Manager</title>
>>>>>>> 9301ee5 (feat(auth): add login/logout with session-based roles (F08, #27))
=======
    <title><?= htmlspecialchars(
        $title ?? "Connexion",
    ) ?> — Freelance Manager</title>
>>>>>>> dccf881 (feat: clean auth and seed)
=======
    <title><?= htmlspecialchars(
        $title ?? "Connexion",
    ) ?> — Freelance Manager</title>
>>>>>>> 604a8fc1a48dcb72643fa4ddb9af5c465397bd31
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body class="auth-page">
    <main class="auth-box">
        <?= $content ?>
    </main>
</body>
</html>
