<?php
/** @var string $content */

$user = \App\Core\Security::currentUser();
$currentPath = (string) parse_url($_SERVER["REQUEST_URI"] ?? "/", PHP_URL_PATH);

$links = [];
if ($user !== null) {
    $links["/missions"] = "Missions";
    if ($user["role"] !== "freelance") {
        $links["/freelances"] = "Freelances";
    }
    if ($user["role"] === "admin") {
        $links["/managers"] = "Managers";
    }
    if ($user["role"] === "freelance") {
        $links["/candidatures"] = "Candidatures";
        $links["/favoris"] = "Favoris";
    }
    if ($user["role"] !== "admin") {
        $links["/profil"] = "Profil";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars(
        $description ??
            "Plateforme interne de gestion des missions freelance : missions, freelances et candidatures.",
    ) ?>">
    <meta name="csrf-token" content="<?= htmlspecialchars(
        \App\Core\Security::csrfToken(),
    ) ?>">
    <title><?= htmlspecialchars($title ?? "Freelance Manager") ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600&family=Source+Serif+4:wght@500;600&display=swap">
    <link rel="stylesheet" href="/assets/css/index.css">
    <script src="/assets/js/main.js" defer></script>
</head>
<body>
    <header class="topbar">
        <div class="wrap topbar-inner">
            <a class="brand" href="/">Freelance<span>-</span>Manager</a>

            <?php if ($user !== null): ?>
                <div class="session">
                    <span class="session-user"><?= htmlspecialchars(
                        $user["email"],
                    ) ?></span>
                    <span class="session-role"><?= htmlspecialchars(
                        $user["role"],
                    ) ?></span>
                    <form method="post" action="/logout">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(
                            \App\Core\Security::csrfToken(),
                        ) ?>">
                        <button type="submit" class="link-button">Se déconnecter</button>
                    </form>
                </div>

                <nav class="nav" aria-label="Navigation principale">
                    <?php foreach ($links as $href => $label): ?>
                        <a href="<?= $href ?>"<?= str_starts_with(
    $currentPath,
    $href,
)
    ? ' aria-current="page"'
    : "" ?>><?= $label ?></a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="wrap page">
        <div id="flash"></div>
        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="wrap footer-inner">
            <a href="/mentions-legales">Mentions légales</a>
        </div>
    </footer>
</body>
</html>
