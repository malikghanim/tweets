<?php
namespace api\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;

class MainController extends ActiveController
{
    protected $user = null;
    public $status = 200;

    public function init() {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
    
    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'except' => ['textures'],
            'auth' => [$this, 'auth']
        ];
       /* $behaviors['authenticator'] = [
        'class' => HttpBasicAuth::className(),
    ];*/
        return $behaviors;
    }
    

    public function auth($username, $password)
    {
        $user = new \common\models\User();
        return $user->checkUserCredentials($username, $password);
    }
    
    // public function afterAction($action,$result) {
    //     Yii::$app->response->format = 'json';
    //     Yii::$app->response->setStatusCode($action->controller->response['status']);
    //     return parent::afterAction($action,$result);
    // }

    public function afterAction($action, $result) {
        Yii::$app->response->format = 'json';
        if (is_array($result) && isset($result['status']))
            Yii::$app->response->setStatusCode($result['status']);
        else
            Yii::$app->response->setStatusCode($this->status);

        return parent::afterAction($action,$result);
    }
  
}
