<?php
namespace App\Repositories;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use App\Entities\ScopeEntity;
use App\Models\ScopeModel;
use League\OAuth2\Server\Exception\OAuthServerException;

class ScopeRepository implements ScopeRepositoryInterface
{
    protected $db;
    function __construct($d) {
        $this->db = $d;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopeEntityByIdentifier($scopeIdentifier)
    {
        $model = new ScopeModel($this->db);
        $scopeArray = $model->findByScope($scopeIdentifier);
        if(empty($scopeArray)){
            return;
        }
        
        $scope = new ScopeEntity();
        $scope->setIdentifier($scopeIdentifier);
        return $scope;
    }
    /**
     * {@inheritdoc}
     */
    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ) {
        // scopeが未指定の場合は client の scope をすべて設定
        // scopeが指定されている場合は client の scope に含まれている引数の socpe を追加して返却
        $clientScopes = $clientEntity->getScopes();
        $ret = [];
        if(count($scopes) === 0) {
            foreach($clientScopes as $s){
                $scope = new ScopeEntity();
                $scope->setIdentifier($s);
                $ret[] = $scope;
            }
        } else {
            foreach($scopes as $s){
                $paramScope = $s->getIdentifier();
                if(!in_array($s->getIdentifier(), $clientScopes)){
                    throw OAuthServerException::invalidScope($paramScope);
                }
                $scope = new ScopeEntity(); 
                $scope->setIdentifier($paramScope);
                $ret[] = $scope;
            }
        }
        return $ret;
    }
}