<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Connexion à Freelance Manager, la plateforme interne de gestion des missions freelance.">
    <title><?= htmlspecialchars(
        $title ?? "Connexion",
    ) ?> — Freelance Manager</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=Source+Serif+4:wght@500;600&display=swap">
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body class="auth-page">
    <main class="auth-box">
        <p class="brand">Freelance<span>/</span>Manager</p>
        <?= $content ?>
    </main>
</body>
</html>
