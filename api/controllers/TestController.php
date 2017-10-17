<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;

class TestController extends Controller
{
    public function actionIndex()
    {
        var_dump('expression');die;
    }
}