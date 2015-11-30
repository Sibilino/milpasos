<?php

namespace app\components;

use yii\base\ActionEvent;
use yii\base\Behavior;
use yii\base\Controller;

/**
 * Behavior to be attached to controllers that remembers the last DIFFERENT url in $lastPage.
 * @property string $lastPage The last different url the current user accessed. This property is read-only.
 */
class RememberLastPageBehavior extends Behavior
{
    public function events() {
        return [
            Controller::EVENT_BEFORE_ACTION => 'updateLastPage',
        ];
    }

    public function updateLastPage(ActionEvent $event) {
        if (\Yii::$app->request->referrer != \Yii::$app->request->absoluteUrl) {
            \Yii::$app->session['lastPage'] = \Yii::$app->request->referrer;
        }
    }

    public function getLastPage() {
        if (!isset(\Yii::$app->session['lastPage'])) {
            \Yii::$app->session['lastPage'] = \Yii::$app->request->referrer;
        }
        return \Yii::$app->session['lastPage'];
    }
}