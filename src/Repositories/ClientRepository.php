<?php
namespace App\Repositories;

use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use App\Entities\ClientEntity;
use App\Models\ClientModel;

class ClientRepository implements ClientRepositoryInterface
{
    protected $db;
    function __construct($d) {
        $this->db = $d;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true)
    {
        # clientsからユーザが存在するか検索
        $model = new ClientModel($this->db);
        $clientArray = $model->findByClientId($clientIdentifier);
        if(empty($clientArray)){
            return;
        }

        if(password_verify($clientSecret, $clientArray['secret']) === false){
            return;
        }

        # client情報をreturn
        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setScope($clientArray['scope']);

        return $client;
    }
}