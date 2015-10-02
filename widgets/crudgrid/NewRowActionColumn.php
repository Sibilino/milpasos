<?php

namespace app\widgets\crudgrid;

use Yii;
use yii\base\Model;
use yii\grid\ActionColumn;
use yii\helpers\Html;

class NewRowActionColumn extends ActionColumn implements NewRowColumn
{
    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();
        if (!isset($this->buttons['add'])) {
            $this->buttons['add'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Add'),
                    'aria-label' => Yii::t('yii', 'Add'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, $options);
            };
        }
    }


    public function renderNewRowCell(Model $model)
    {
        $url = 'test';
        return Html::tag('td', call_user_func($this->buttons['add'], $url, null, null));
    }
}