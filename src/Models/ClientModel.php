<?php
namespace App\Models;

class ClientModel
{
    private $db;
    function __construct($d)
    {
        $this->db = $d;
    }

    public function findByClientId($clientId) {
        $sql = 'select * from clients where client_id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clientId]);
        
        return $stmt->fetch();
    }

}