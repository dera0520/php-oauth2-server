<?php
namespace App\Entities;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
class ClientEntity implements ClientEntityInterface
{
    use EntityTrait, ClientTrait;

    protected $scope;

    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function setRedirectUri($uri)
    {
        $this->redirectUri = $uri;
    }

    public function setScope($s){
        $this->scope = $s;
    }

    public function getScope(){
        return $this->scope;
    }

    public function getScopes(){
        return explode(' ', $this->scope);
    }

}