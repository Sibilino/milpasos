<?php

use yii\db\Migration;

class m160912_175026_add_event_summary extends Migration
{
    public function up()
    {
        $this->addColumn(
            'event',
            'summary',
            'text'
        );

    }

    public function down()
    {
        $this->dropColumn(
            'event',
            'summary'
        );
    }
}