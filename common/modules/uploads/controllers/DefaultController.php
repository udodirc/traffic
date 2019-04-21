<?php

namespace app\modules\uploads\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionUpload()
    {
        return $this->render('index');
    }
}
