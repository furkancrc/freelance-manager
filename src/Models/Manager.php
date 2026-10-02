<?php

namespace App\Models;

final class Manager{


    private $db;

    public function __construct($dbConnection)
    {
        $this->db = $dbConnection;
    }

    public function createManager($userID, $nom, $prenom, $dept, $tel)
    {
        $sql = "INSERT INTO managers (user_id, first_name, last_name, department, phone) 
                VALUES (:user_id, :nom, :prenom, :dept, :tel)";

        $stmt = $this->db->prepare($sql);

        $success = $stmt->execute([
            ':user_id'  => $userID,
            ':nom'      => $nom,
            ':prenom'   => $prenom,
            ':dept'     => $dept,
            ':tel'      => $tel
        ]);

        return $success;
    }

    public function getManager($userID)
    {
        $sql = "SELECT * FROM managers WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userID]);
        
        // Retourne un tableau associatif avec les données, ou 'false' s'il n'existe pas
        return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }

    public function updateManager($userID, $nom, $prenom, $dept, $tel)
    {
        $sql = "UPDATE managers 
                SET first_name = :nom, last_name = :prenom, department = :dept, phone = :tel 
                WHERE user_id = :user_id";
                
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id'  => $userID,
            ':nom'      => $nom,
            ':prenom'   => $prenom,
            ':dept'     => $dept,
            ':tel'      => $tel
        ]);
    }

    public function deleteManager($userID)
    {
        $sql = "DELETE FROM managers WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([':user_id' => $userID]);
    }
}
