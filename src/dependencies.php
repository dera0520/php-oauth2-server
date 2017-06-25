<?php
// DIC configuration
$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// # DB接続
$container['db'] = function ($c){
    $settings = $c->get('settings')['db'];
    $pdo = new PDO("mysql:host=" . $settings['host'] . ";dbname=" . $settings['dbname'] . ";port=" . $settings['port'],
        $settings['user'], $settings['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

# コンテナに oauth2_server オブジェクトを設定
$container['oauth2_server'] = function ($c){

    // Init our repositories
    $clientRepository = new App\Repositories\ClientRepository($c->db); // instance of ClientRepositoryInterface
    $scopeRepository = new App\Repositories\ScopeRepository($c->db); // instance of ScopeRepositoryInterface
    $accessTokenRepository = new App\Repositories\AccessTokenRepository($c->db); // instance of AccessTokenRepositoryInterface

    // Path to public and private keys
    $privateKey = 'file:///Users/nobuhiro/Projects/php-auth2-server/private.key';
    //$privateKey = new CryptKey('file://path/to/private.key', 'passphrase'); // if private key has a pass phrase
    $publicKey = 'file:///Users/nobuhiro/Projects/php-auth2-server/public.key';

    // Setup the authorization server
    $server = new \League\OAuth2\Server\AuthorizationServer(
        $clientRepository,
        $accessTokenRepository,
        $scopeRepository,
        $privateKey,
        $publicKey
    );

    // Enable the client credentials grant on the server
    $server->enableGrantType(
        new \League\OAuth2\Server\Grant\ClientCredentialsGrant(),
        new \DateInterval('P1M') // access tokens will expire after 1 hour
    );
    return $server;
};
