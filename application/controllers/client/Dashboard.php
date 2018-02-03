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
class Dashboard extends Client_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');

    }

    public function index($action = NULL)
    {
        $data['title'] = config_item('company_name');
        $data['page'] = lang('dashboard');
        $data['breadcrumbs'] = lang('dashboard');
        // get all project by client id
        $data['subview'] = $this->load->view('client/main_content', $data, TRUE);
        $this->load->view('client/_layout_main', $data);
    }

    public function set_language($lang)
    {
        $this->session->set_userdata('lang', $lang);
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function announcements_details($id)
    {
        $data['title'] = lang('announcements_details'); //Page title
        $this->admin_model->_table_name = "tbl_announcements"; // table name
        $this->admin_model->_order_by = "announcements_id"; // $id
        $data['announcements_details'] = $this->admin_model->get_by(array('announcements_id' => $id), TRUE);
        $this->admin_model->_primary_key = 'announcements_id';
        $updata['view_status'] = '1';
        $this->admin_model->save($updata, $id);

        $data['subview'] = $this->load->view('admin/announcements/announcements_details', $data, FALSE);
        $this->load->view('admin/_layout_modal_lg', $data); //page load
    }

}
