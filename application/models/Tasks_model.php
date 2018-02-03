<?php

class Tasks_Model extends MY_Model
{

    public $_table_name;
    public $_order_by;
    public $_primary_key;


    public function get_statuses()
    {
        $statuses = array(
            array(
                'id' => 1,
                'value' => 'not_started',
                'name' => lang('not_started'),
                'order' => 1,
            ),
            array(
                'id' => 2,
                'value' => 'in_progress',
                'name' => lang('in_progress'),
                'order' => 2,
            ),
            array(
                'id' => 3,
                'value' => 'completed',
                'name' => lang('completed'),
                'order' => 3,
            ),
            array(
                'id' => 4,
                'value' => 'deferred',
                'name' => lang('deferred'),
                'order' => 4,
            ),
            array(
                'id' => 5,
                'value' => 'waiting_for_someone',
                'name' => lang('waiting_for_someone'),
                'order' => 5,
            )
        );
        return $statuses;
    }
}
