<?php

namespace app\widgets;

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

class GridForm extends ActiveForm
{
    public $formPjaxOptions = [];
    public $gridPjaxOptions = [];
    public $gridOptions = [];

    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        $this->options = ArrayHelper::merge([
            'data-pjax' => true,
        ], $this->options);
        $this->formPjaxOptions = ArrayHelper::merge([
            'id' => $this->options['id']."-pjax-form",
        ], $this->formPjaxOptions);
        $this->gridPjaxOptions = ArrayHelper::merge([
            'id' => $this->options['id']."-pjax-grid",
        ], $this->gridPjaxOptions);

        Pjax::begin($this->formPjaxOptions);
        parent::init();
    }

    public function run()
    {
        parent::run();
        Pjax::end();

        $formPjaxId = $this->formPjaxOptions['id'];
        $gridPjaxId = $this->gridPjaxOptions['id'];
        $this->view->registerJs("
            // Reload link grid after submitting a new link
            $('#$formPjaxId').on('pjax:end', function () {
                $.pjax.reload({container: '#$gridPjaxId'});
            });
        ");

        Pjax::begin($this->gridPjaxOptions);
        echo GridView::widget($this->gridOptions);
        Pjax::end();
    }
}