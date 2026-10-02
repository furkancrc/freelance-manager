<?php
ob_start(); ?>

<main class="container">
    <h1>Mentions Légales</h1>

    <section>
        <h2>1. Éditeurs du site</h2>
        <p>
            Le présent site, développé dans le cadre d'un projet d'études, est édité par :<br>
                Pierre, Furkan et Yanis<br>
            Étudiants en cycle ingénieur<br>
            Campus CESI Rouen
        </p>
    </section>

    <section>
        <h2>2. Hébergement</h2>
        <p>
            Ce site est hébergé localement à des fins de démonstration technique et de soutenance. Il n'est pas accessible sur le réseau public internet.
        </p>
    </section>

    <section>
        <h2>3. Propriété intellectuelle</h2>
        <p>
            L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.
        </p>
    </section>

    <section>
        <h2>4. Données personnelles</h2>
        <p>
            Dans le cadre de la fonctionnalité "Plateforme de gestion des missions freelance en entreprise", ce site simule la collecte de données personnelles. Ces données sont fictives ou générées pour les besoins de l'évaluation et ne font l'objet d'aucun traitement commercial.
        </p>
    </section>
</main>

<?php
$content = ob_get_clean();
$title = "Mentions Légales";
require __DIR__ . "/../layouts/main.php";

