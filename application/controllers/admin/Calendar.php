<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admistrator
 *
 * @author pc mart ltd
 */
class Calendar extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
    }

    public function index($action = NULL)
    {
        $data['title'] = config_item('company_name');
        $data['page'] = lang('calendar');
        if (!empty($action) && $action == 'search') {
            $data['searchType'] = $this->uri->segment(5);

        } else {
            $data['searchType'] = 'all';
        }
        $data['subview'] = $this->load->view('admin/calendar', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }


    public function calendar_settings()
    {
        $data['title'] = lang('calendar_settings');
        $data['modal_subview'] = $this->load->view('admin/settings/calendar_settings', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function save_settings()
    {
        $input_data = $this->admin_model->array_from_post(array('gcal_api_key', 'gcal_id', 'milestone_on_calendar', 'tasks_on_calendar', 'bugs_on_calendar', 'invoice_on_calendar', 'payments_on_calendar', 'estimate_on_calendar', 'opportunities_on_calendar', 'goal_tracking_on_calendar', 'holiday_on_calendar', 'absent_on_calendar', 'on_leave_on_calendar',
            'milestone_color', 'tasks_color', 'bugs_color', 'invoice_color', 'payments_color', 'estimate_color', 'opportunities_color', 'goal_tracking_color', 'absent_color', 'on_leave_color'));

        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_save_settings'),
            'value1' => $input_data['gcal_api_key']
        );

        $this->admin_model->_table_name = 'tbl_activities';
        $this->admin_model->_primary_key = 'activities_id';
        $this->admin_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('save_settings');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

}
