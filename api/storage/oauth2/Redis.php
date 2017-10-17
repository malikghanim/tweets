<?php

namespace api\storage\oauth2;

use OAuth2\Storage\Redis AS BaseRedis;
use OAuth2\Storage\PublicKeyInterface;

/**
 * Redis storage wrapper for OAuth2 server implementation. 
 * @author Malik Ghanim
 */
class Redis extends BaseRedis implements
   PublicKeyInterface 
{
    /**
     * The name of Redis Yii2 component/connection.
     * @var string
     */
    public $connection = 'redis';

    /**
     * Construct Redis storage for OAuth2.
     * @return void
     */
    public function __construct()
    {
        try{
            $redis = \Yii::$app->get($this->connection);
            parent::__construct($redis);
        } catch (\Exception $e) {
            throw new \Exception("Make sure to well configure '{$this->connection}' in your yii2 components.", 1);
        }
    }

    public function getPublicKey($client_id = null){;}
    public function getPrivateKey($client_id = null){;}
    public function getEncryptionAlgorithm($client_id = null){;}
}