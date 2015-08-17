<?php

$sensitive = require(__DIR__.'/sensitive.php');
return array_merge([
    'class' => 'yii\db\Connection',
    'charset' => 'utf8',
], $sensitive);
