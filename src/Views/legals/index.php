<?php
ob_start(); ?>
<div class="prose">
    <h1>Mentions Légales</h1>

    <section>
        <h2>1. Éditeur du site</h2>
        <p>
            Le présent site, développé dans le cadre d'un projet d'études, est édité par :<br>
            <strong>Pierre Houllière</strong><br>
            Étudiant en cycle ingénieur<br>
            Campus CESI Rouen<br>
            80 Avenue du Maryse Bastié, 76800 Saint-Étienne-du-Rouvray<br>
            Email : contact@entreprise.test (adresse fictive)
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
        <p>
            La reproduction de tout ou partie de ce site sur un support électronique quel qu'il soit est formellement interdite sauf autorisation expresse du directeur de la publication.
        </p>
    </section>

    <section>
        <h2>4. Données personnelles</h2>
        <p>
            Dans le cadre de la fonctionnalité "Plateforme de gestion des missions freelance en entreprise", ce site simule la collecte de données personnelles (noms, prénoms, adresses email). Ces données sont fictives ou générées pour les besoins de l'évaluation (seed) et ne font l'objet d'aucun traitement commercial.
        </p>
    </section>
</div>
<?php
$content = ob_get_clean();
$title = "Mentions légales — Freelance Manager";
$description = "Mentions légales de Freelance Manager, projet d'études CESI.";
require __DIR__ . "/../layouts/main.php";
