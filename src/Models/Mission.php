<?php

namespace App\Models;

final class Mission {

    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    // SF7 : Rechercher / Récupérer toutes les missions ou une mission par ID
    public function getAllMissions()
    {
        $sql = "SELECT * FROM missions ORDER BY created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getMission($id)
    {
        $sql = "SELECT * FROM missions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // SF8 : Créer une mission
    public function createMission($managerId, $title, $description, $budget, $dailyRate, $startDate, $endDate, $location, $status)
    {
        $sql = "INSERT INTO missions (manager_id, title, description, budget, daily_rate, start_date, end_date, location, status, created_at) 
                VALUES (:manager_id, :title, :description, :budget, :daily_rate, :start_date, :end_date, :location, :status, NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':manager_id'  => $managerId,
            ':title'       => $title,
            ':description' => $description,
            ':budget'      => $budget,
            ':daily_rate'  => $dailyRate,
            ':start_date'  => $startDate,
            ':end_date'    => $endDate,
            ':location'    => $location,
            ':status'      => $status
        ]);
    }

    // SF9 : Modifier une mission
    public function updateMission($id, $title, $description, $budget, $dailyRate, $startDate, $endDate, $location, $status)
    {
        $sql = "UPDATE missions 
                SET title = :title, description = :description, budget = :budget, 
                    daily_rate = :daily_rate, start_date = :start_date, end_date = :end_date, 
                    location = :location, status = :status 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id'          => $id,
            ':title'       => $title,
            ':description' => $description,
            ':budget'      => $budget,
            ':daily_rate'  => $dailyRate,
            ':start_date'  => $startDate,
            ':end_date'    => $endDate,
            ':location'    => $location,
            ':status'      => $status
        ]);
    }

    // SF10 : Supprimer une mission
    public function deleteMission($id)
    {
        $sql = "DELETE FROM missions WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    // SF11 : Statistiques missions (ex: nombre total et budget moyen)
    public function getStatistics()
    {
        $sql = "SELECT COUNT(*) as total_missions, AVG(budget) as avg_budget FROM missions";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}