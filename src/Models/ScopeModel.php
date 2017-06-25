<?php
namespace App\Models;

/**
* 
*/
class ScopeModel
{
    private $db;
    function __construct($d)
    {
        $this->db = $d;
    }

    public function findByScope($scope){
        $sql = 'select * from scopes where scope = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$scope]);
        
        return $stmt->fetch();
    }
    
}