<?php
namespace api\modules\v1\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\ActiveController;
use api\controllers\MainController;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;

use common\models\Keyword;
use common\models\Tweet;



class TweetController extends MainController
{
    public $modelClass = 'common\models\Package';
    public $response = ['status' => 200, 'massage' => 'Success!'];

    public function actions()
    {
        $actions = parent::actions();
        //var_dump($actions);die;
        unset($actions['view']);
        unset($actions['index']);
        unset($actions['create']);
        unset($actions['update']);
        unset($actions['delete']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new PackageSearch();
        return $searchModel->search(Yii::$app->request->queryParams);
    }

    public function formName()
    {
        return '';
    }

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            // 'except' => ['index', 'view'],
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        $request = json_decode(Yii::$app->request->rawBody);
        if (empty($request) || 
            !isset($request->keyword) || 
            !isset($request->tweets) || 
            !(is_array($request->tweets) && !empty($request->tweets))
        ) {
            $this->response['status'] = 400;
            $this->response['msg'] = 'Invalid Json Structure!';
            return $this->response;
        }

        $keyword = new Keyword();
        $keyword->keyword = $request->keyword;
        $keyword->user_id = Yii::$app->user->identity->id;
        if (!$keyword->validate() || !$keyword->save()) {
            $this->response['status'] = 400;
            $this->response['massage'] = "Validation Errors!";
            $this->response['errors'] = $keyword->errors;
            return $this->response;
        }

        if (!empty($request->tweets)) {
            foreach ($request->tweets as $tweet) {
                if (!empty($tweet)) {
                    $newTweet = new Tweet();
                    $newTweet->load(['Tweet' => (array)$tweet]);
                    $newTweet->keyword_id = $keyword->id;
                    $newTweet->user_id = Yii::$app->user->identity->id;
                    if ($newTweet->validate())
                        $newTweet->save();
                }
            }
        }

        $this->response['status'] = 201;
        $this->response['massage'] = "Tweets saved successfully!";
        return $this->response;
    }

}