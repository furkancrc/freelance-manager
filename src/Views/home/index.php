<?php
/** @var array{id:int,email:string,role:string} $user */
ob_start();
?>
<h1>Bienvenue, <?= htmlspecialchars($user['email']) ?></h1>
<p>Vous êtes connecté avec le rôle : <strong><?= htmlspecialchars($user['role']) ?></strong>.</p>
<?php
$content = ob_get_clean();
$title = 'Accueil';
require __DIR__ . '/../layouts/main.php';
