<?php

namespace App\Controllers;

use App\Models\Mission;

final class MissionController {

    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    
    // SF7 : Afficher la liste de toutes les missions
    public function index()
    {
        $missionModel = new Mission($this->db);
        $missions = $missionModel->getAllMissions();

        echo "<h2>Liste des missions</h2>";
        echo "<pre>";
        print_r($missions);
        echo "</pre>";
        // Plus tard : require __DIR__ . '/../Views/missions/index.php';
    }

     // SF7 : Afficher une mission spécifique par son ID
    public function show(int $id)
    {
        $missionModel = new Mission($this->db);
        $mission = $missionModel->getMission($id);

        if ($mission) {
            echo "<h2>Détails de la mission : " . htmlspecialchars($mission['title']) . "</h2>";
            echo "<pre>";
            print_r($mission);
            echo "</pre>";
        } else {
            echo "Mission introuvable.";
        }
    }

    // SF8 : Créer une mission
    public function store()
    {
        $missionModel = new Mission($this->db);
        
        // Exemple d'insertion (à adapter plus tard avec les données d'un formulaire $_POST)
        $success = $missionModel->createMission(
            1, // manager_id
            'Refonte API v2', 
            'Migration complète vers une architecture moderne', 
            12000.00, 
            450.00, 
            '2026-11-01', 
            '2026-12-31', 
            'Télétravail', 
            'open'
        );

        if ($success) {
            echo "✅ Mission créée avec succès !";
        } else {
            echo "❌ Erreur lors de la création de la mission.";
        }
    }

    // SF9 : Modifier une mission
    public function update(int $id)
    {
        $missionModel = new Mission($this->db);
        
        $success = $missionModel->updateMission(
            $id,
            'Refonte API v2 (Mise à jour)', 
            'Description actualisée', 
            13000.00, 
            500.00, 
            '2026-11-01', 
            '2027-01-15', 
            'Paris', 
            'in_progress'
        );

        if ($success) {
            echo "✅ Mission mise à jour avec succès !";
        } else {
            echo "❌ Erreur lors de la mise à jour.";
        }
    }

    // SF10 : Supprimer une mission
    public function destroy(int $id)
    {
        $missionModel = new Mission($this->db);
        $success = $missionModel->deleteMission($id);

        if ($success) {
            echo "✅ Mission supprimée avec succès !";
        } else {
            echo "❌ Erreur lors de la suppression.";
        }
    }

    // SF11 : Statistiques des missions
    public function stats()
    {
        $missionModel = new Mission($this->db);
        $stats = $missionModel->getStatistics();

        echo "<h2>Statistiques des missions</h2>";
        echo "Nombre total de missions : " . $stats['total_missions'] . "<br>";
        echo "Budget moyen : " . number_format((float)$stats['avg_budget'], 2) . " €";
    }
}