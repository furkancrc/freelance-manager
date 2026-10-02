<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars(
        $title ?? "Connexion",
    ) ?> — Freelance Manager</title>
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body class="auth-page">
    <main class="auth-box">
        <?= $content ?>
    </main>
</body>
</html>
