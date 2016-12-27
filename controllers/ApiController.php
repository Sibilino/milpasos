<?php

namespace app\controllers;

use app\models\Group;
use yii\rest\Controller;

class ApiController extends Controller
{
    public function actionGroupSearch($term = '')
    {
        return Group::find()->where(['like', 'name', $term])->all();
    }
}
