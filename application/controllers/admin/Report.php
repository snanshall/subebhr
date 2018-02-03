<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class report extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('report_model');
        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "99.8%",
                'height' => "400px"
            )
        );
    }

    public function tasks_assignment()
    {
        $data['title'] = lang('tasks_assignment');
        // get permission user by menu id
        $permission_user = $this->report_model->all_permission_user('57');
        // get all admin user
        $admin_user = $this->db->where('role_id', 1)->get('tbl_users')->result();
        // if not exist data show empty array.
        if (!empty($permission_user)) {
            $permission_user = $permission_user;
        } else {
            $permission_user = array();
        }
        if (!empty($admin_user)) {
            $admin_user = $admin_user;
        } else {
            $admin_user = array();
        }
        $data['assign_user'] = array_merge($admin_user, $permission_user);

        $data['user_tasks'] = $this->get_tasks_by_user($data['assign_user']);

        $data['subview'] = $this->load->view('admin/report/project_tasks_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }


    function get_tasks_by_user($assign_user, $tasks = null)
    {
        $tasks_info = $this->report_model->get_permission('tbl_task');
        if (!empty($tasks_info)):foreach ($tasks_info as $v_tasks):
            if (!empty($tasks)) {
                if ($v_tasks->permission == 'all') {
                    $permission[$v_tasks->permission][$v_tasks->task_status][] = $v_tasks->task_status;
                } else {
                    $get_permission = json_decode($v_tasks->permission);
                    if (!empty($get_permission)) {
                        foreach ($get_permission as $id => $v_permission) {
                            if (!empty($assign_user)) {
                                foreach ($assign_user as $v_user) {
                                    if ($v_user->user_id == $id) {
                                        $permission[$v_user->user_id][$v_tasks->task_status][] = $v_tasks->task_status;
                                    }
                                }
                            }

                        }
                    }
                }
            }
        endforeach;
        endif;
        if (empty($permission)) {
            $permission = array();
        }
        return $permission;
    }

    public function tasks_report()
    {
        $data['title'] = lang('project_report');
        // get permission user by menu id
        $permission_user = $this->report_model->all_permission_user('54');
        // get all admin user
        $admin_user = $this->db->where('role_id', 1)->get('tbl_users')->result();
        // if not exist data show empty array.
        if (!empty($permission_user)) {
            $permission_user = $permission_user;
        } else {
            $permission_user = array();
        }
        if (!empty($admin_user)) {
            $admin_user = $admin_user;
        } else {
            $admin_user = array();
        }
        $data['assign_user'] = array_merge($admin_user, $permission_user);

        $data['all_tasks'] = $this->report_model->get_permission('tbl_task');
        $data['user_tasks'] = $this->get_tasks_by_user($data['assign_user'], true);
        $data['subview'] = $this->load->view('admin/report/tasks_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function client_report()
    {
        $data['title'] = lang('client_report');
        $data['all_client_info'] = $this->db->get('tbl_client')->result();

        $data['subview'] = $this->load->view('admin/report/client_report', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }


}
