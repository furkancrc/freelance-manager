<?php

namespace App\Controllers;

use App\Models\Manager;

final class ManagerController{


    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function testCreate()
    {
        $managerModel = new Manager($this->db);

        // On utilise un seul ID pour le test (qui doit déjà exister dans la table users !)
        $testManagers = [
            [
                'id' => 5, 
                'nom' => 'Dupont', 
                'prenom' => 'Jean', 
                'dept' => 'IT', 
                'tel' => '0600000000', 
                'new_dept' => 'Direction Générale' // La donnée qui servira pour l'Update
            ]
        ];

        echo "<h2>Début du test CRUD complet</h2>";

        foreach ($testManagers as $manager) {
            echo "<h3>--- Test pour l'ID Utilisateur : {$manager['id']} ---</h3>";

            // 1. CREATE (Création)
            echo "<strong>1. CREATE :</strong> Insertion du manager...<br>";
            $created = $managerModel->createManager($manager['id'], $manager['nom'], $manager['prenom'], $manager['dept'], $manager['tel']);
            
            if ($created) {
                echo "✅ Succès de l'insertion.<br>";
            } else {
                echo "❌ Échec de l'insertion.<br>";
                continue; // On arrête le test ici si la création a échoué
            }

            // 2. READ (Lecture)
            echo "<br><strong>2. READ :</strong> Récupération des données...<br>";
            $data = $managerModel->getManager($manager['id']);
            
            if ($data) {
                echo "✅ Données trouvées : " . $data['first_name'] . " " . $data['last_name'] . " (Département : " . $data['departement'] . ")<br>";
            } else {
                echo "❌ Introuvable en base.<br>";
            }

            // 3. UPDATE (Mise à jour)
            echo "<br><strong>3. UPDATE :</strong> Changement du département vers '{$manager['new_dept']}'...<br>";
            $updated = $managerModel->updateManager($manager['id'], $manager['nom'], $manager['prenom'], $manager['new_dept'], $manager['tel']);
            
            if ($updated) {
                echo "✅ Mise à jour réussie.<br>";
                // On refait un petit Read pour prouver que ça a marché
                $newData = $managerModel->getManager($manager['id']);
                echo "ℹ️ Nouveau département vérifié en base : " . $newData['departement'] . "<br>";
            } else {
                echo "❌ Échec de la mise à jour.<br>";
            }

            // 4. DELETE (Suppression)
            echo "<br><strong>4. DELETE :</strong> Suppression du manager...<br>";
            $deleted = $managerModel->deleteManager($manager['id']);
            
            if ($deleted) {
                echo "✅ Suppression réussie.<br>";
                // On vérifie qu'il a bien disparu
                $checkDelete = $managerModel->getManager($manager['id']);
                if (!$checkDelete) {
                    echo "ℹ️ Confirmé : Le manager n'existe plus dans la table.<br>";
                }
            } else {
                echo "❌ Échec de la suppression.<br>";
            }
            
            echo "<hr>";
        }

        echo "<strong>Fin des tests.</strong>";
    }

    public function show($id)
    {
        $managerModel = new Manager($this->db);
        $manager = $managerModel->getManager($id);

        if ($manager) {
            echo "Profil du manager : " . $manager['first_name'] . " " . $manager['last_name'] . " (Département : " . $manager['departement'] . ")";
            // Plus tard : require __DIR__ . '/../Views/manager/show.php';
        } else {
            echo "Ce manager n'existe pas.";
        }
    }

    public function update($id)
    {
        $managerModel = new Manager($this->db);
        
        // En conditions réelles, ces données proviendront de $_POST
        $success = $managerModel->updateManager($id, 'Dupont', 'Marc', 'Ressources Humaines', '0700000000');

        if ($success) {
            echo "Le profil a été mis à jour avec succès.";
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    }

    public function destroy($id)
    {
        $managerModel = new Manager($this->db);
        $success = $managerModel->deleteManager($id);

        if ($success) {
            echo "Le manager a été supprimé.";
        } else {
            echo "Erreur lors de la suppression.";
        }
    }
}