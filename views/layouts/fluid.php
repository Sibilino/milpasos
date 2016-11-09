<?php
/* @var string $content */

use yii\widgets\Breadcrumbs;

$this->beginContent('@app/views/layouts/base.php');
?>
    <div class="container-fluid main-container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <?= $content; ?>
    </div>

<?php $this->endContent(); ?>