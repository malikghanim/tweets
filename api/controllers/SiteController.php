<?php

namespace api\controllers;

use Yii;
use yii\rest\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return [
            'message' => 'Nothing useful here, please leave this place :(',
            'status' => 'Boob Noob...'
        ];
    }
}
