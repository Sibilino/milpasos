<?php

namespace app\controllers;

use app\models\Group;
use yii\helpers\ArrayHelper;
use yii\rest\Controller;

/**
 * Actions for AJAX an other JSON calls.
 */
class ApiController extends Controller
{
    /**
     * Returns a list of group names, indexed by their group ids, that include the specified search term.
     * Returns empty array if search term is empty.
     * @param string $term The partial search term.
     */
    public function actionGroupSearch($term = '')
    {
        if ($term) {
            $groups = Group::find()->select(['id', 'name'])->where(['like', 'name', $term])->all();
            return ArrayHelper::map($groups, 'id', 'name');
        }
        return [];
    }
}
