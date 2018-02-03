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
class Dashboard extends Admin_Controller
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

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $data['total_attendance'] = count($this->total_attendace_in_month());

        $data['total_absent'] = count($this->total_attendace_in_month('absent'));

        $data['total_leave'] = count($this->total_attendace_in_month('leave'));

        $data['today_leave'] = $this->today_leave();

        $data['subview'] = $this->load->view('admin/main_content', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function today_leave()
    {
        $total_leave = 0;
        $today = date('Y-m-d');
        $all_application = $this->db->get('tbl_leave_application')->result();
        if (!empty($all_application)) {
            foreach ($all_application as $v_application) {
                $date = date('Y-m-d', strtotime($v_application->application_date));
                if ($today == $date) {
                    $total_leave += count($date);
                }
            }
        }
        if (empty($total_leave)) {
            $total_leave = 0;
        }
        return $total_leave;
    }

    public function total_attendace_in_month($flag = NULL)
    {
        $month = date('m');
        $year = date('Y');

        if ($month >= 1 && $month <= 9) { // if i<=9 concate with Mysql.becuase on Mysql query fast in two digit like 01.
            $start_date = $year . "-" . '0' . $month . '-' . '01';
            $end_date = $year . "-" . '0' . $month . '-' . '31';
        } else {
            $start_date = $year . "-" . $month . '-' . '01';
            $end_date = $year . "-" . $month . '-' . '31';
        }
        if (!empty($flag) && $flag == 1) { // if flag is not empty that means get pulic holiday
            $get_public_holiday = $this->global_model->get_holiday_list_by_date($start_date, $end_date);

            if (!empty($get_public_holiday)) { // if not empty the public holiday
                foreach ($get_public_holiday as $v_holiday) {
                    if ($v_holiday->start_date == $v_holiday->end_date) { // if start date and end date is equal return one data
                        $total_holiday[] = $v_holiday->start_date;
                    } else { // if start date and end date not equan return all date
                        for ($j = $v_holiday->start_date; $j <= $v_holiday->end_date; $j++) {
                            $total_holiday[] = $j;
                        }
                    }
                }
                return $total_holiday;
            }
        } elseif (!empty($flag)) { // if flag is not empty that means get pulic holiday
            $get_total_absent = $this->admin_model->get_total_attendace_by_date($start_date, $end_date, $flag); // get all attendace by start date and in date
            return $get_total_absent;
        } else {
            $get_total_attendance = $this->admin_model->get_total_attendace_by_date($start_date, $end_date); // get all attendace by start date and in date
            return $get_total_attendance;
        }
    }


    public function set_language($lang)
    {
        $this->session->set_userdata('lang', $lang);
        redirect($_SERVER["HTTP_REFERER"]);
    }

    public function set_clocking($id = NULL)
    {
        // sate into attendance table
        $adata['user_id'] = $this->session->userdata('user_id');
        $clocktime = $this->input->post('clocktime', TRUE);
        $date = $this->input->post('clock_date', TRUE);
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $time = $this->input->post('clock_time', TRUE);
        if (empty($time)) {
            $time = date('h:i:s');;
        }
        if ($clocktime == 1) {
            $adata['date_in'] = $date;
        } else {
            $adata['date_out'] = $date;
        }
        if (!empty($adata['date_in'])) {
            // check existing date is here or not
            $check_date = $this->admin_model->check_by(array('user_id' => $adata['user_id'], 'date_in' => $adata['date_in']), 'tbl_attendance');
        }
        if (!empty($check_date)) { // if exis do not save date and return id
            $this->admin_model->_table_name = "tbl_attendance"; // table name
            $this->admin_model->_primary_key = "attendance_id"; // $id

            if ($check_date->attendance_status != '1') {
                $udata['attendance_status'] = 1;
                $this->admin_model->save($udata, $check_date->attendance_id);
            }
            if ($check_date->clocking_status == 0) {
                $udata['date_out'] = $date;
                $udata['clocking_status'] = 1;
                $this->admin_model->save($udata, $check_date->attendance_id);
            }
            $data['attendance_id'] = $check_date->attendance_id;
        } else { // else save data into tbl attendance
            // get attendance id by clock id into tbl clock
            // if attendance id exist that means he/she clock in
            // return the id
            // and update the day out time
            $check_existing_data = $this->admin_model->check_by(array('clock_id' => $id), 'tbl_clock');
            $this->admin_model->_table_name = "tbl_attendance"; // table name
            $this->admin_model->_primary_key = "attendance_id"; // $id
            if (!empty($check_existing_data)) {
                $adata['clocking_status'] = 0;
                $this->admin_model->save($adata, $check_existing_data->attendance_id);
            } else {
                $adata['attendance_status'] = 1;
                $adata['clocking_status'] = 1;
                //save data into attendance table
                $data['attendance_id'] = $this->admin_model->save($adata);
            }
        }
        // save data into clock table
        if ($clocktime == 1) {
            $data['clockin_time'] = $time;
        } else {
            $data['clockout_time'] = $time;
            $data['comments'] = $this->input->post('comments', TRUE);
        }
        $data['ip_address'] = $this->input->ip_address();

        //save data in database
        $this->admin_model->_table_name = "tbl_clock"; // table name
        $this->admin_model->_primary_key = "clock_id"; // $id
        if (!empty($id)) {
            $data['clocking_status'] = 0;
            $this->admin_model->save($data, $id);
        } else {
            $data['clocking_status'] = 1;
            $id = $this->admin_model->save($data);
            if (!empty($check_date)) {
                if ($check_date->clocking_status == 1) {
                    $data['clockout_time'] = $time;
                    $data['clocking_status'] = 0;
                    $this->admin_model->save($data, $id);
                }
            }
        }
        redirect($_SERVER["HTTP_REFERER"]);
    }

}
