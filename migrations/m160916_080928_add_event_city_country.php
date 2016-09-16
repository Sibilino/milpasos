<?php

use yii\db\Migration;

class m160916_080928_add_event_city_country extends Migration
{
    public function up()
    {
        $this->addColumn(
            'event',
            'city',
            'string'
        );$this->addColumn(
            'event',
            'country',
            'string'
        );

    }

    public function down()
    {
        $this->dropColumn(
            'event',
            'city'
        );
        $this->dropColumn(
            'event',
            'country'
        );
    }
}
