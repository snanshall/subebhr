<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        ini_set('max_input_vars', '3000');
        $this->load->model('settings_model');
        $this->auth_key = config_item('api_key'); // Set our API KEY

        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array(
            'id' => 'ck_editor',
            'path' => 'asset/js/ckeditor',
            'config' => array(
                'toolbar' => "Full",
                'width' => "100%",
                'height' => "400px"
            )
        );
        $this->language_files = $this->settings_model->all_files();
    }

    public function index()
    {
        $settings = $this->input->get('settings', TRUE) ? $this->input->get('settings', TRUE) : 'general';
        $data['title'] = lang('company_details'); //Page title
        $can_do = can_do(111);
        if (!empty($can_do)) {
            $data['load_setting'] = $settings;
        } else {
            $data['load_setting'] = 'not_found';
        }
        $data['page'] = lang('settings');
        $this->settings_model->_table_name = "tbl_countries"; //table name
        $this->settings_model->_order_by = "id";
        $data['countries'] = $this->settings_model->get();
        $data['translations'] = $this->settings_model->translations();

        $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load

    }

    public function save_settings()
    {
        $input_data = $this->settings_model->array_from_post(array('company_name', 'company_legal_name',
            'contact_person', 'company_address', 'company_city', 'company_zip_code',
            'company_country', 'company_phone', 'company_email', 'company_domain', 'company_vat'));

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
            'activity' => ('activity_save_general_settings'),
            'value1' => $input_data['company_name']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('save_general_settings');
        set_message($type, $message);
        redirect('admin/settings');
    }

    public function system()
    {

        $data['page'] = lang('settings');
        $data['load_setting'] = 'system';
        $data['title'] = lang('system_settings'); //Page title
        $data['languages'] = $this->settings_model->get_active_languages();
        // get all location
        $this->settings_model->_table_name = 'tbl_locales';
        $this->settings_model->_order_by = 'name';
        $data['locales'] = $this->settings_model->get();

        // get all timezone
        $data['timezones'] = $this->settings_model->timezones();
        // get all currencies
        $this->settings_model->_table_name = 'tbl_currencies';
        $this->settings_model->_order_by = 'name';
        $data['currencies'] = $this->settings_model->get();
        $can_do = can_do(112);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load

    }

    public function save_system()
    {
        $input_data = $this->settings_model->array_from_post(array('default_language', 'locale',
            'timezone', 'default_currency', 'default_account', 'default_tax', 'date_format',
            'enable_languages', 'allow_client_registration', 'currency_position', 'money_format', 'allowed_files', 'google_api_key',
            'auto_close_ticket', 'attendance_report', 'tables_pagination_limit', 'max_file_size'));

        foreach ($input_data as $key => $value) {
            if (strtolower($value) == 'on') {
                $value = 'TRUE';
            } elseif (strtolower($value) == 'off') {
                $value = 'FALSE';
            }

            if ($key == 'default_account') {
                if (empty($value)) {
                    $value = '1';
                }
            }
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        $date_format = $this->input->post('date_format', true);
        //Set date format for date picker
        switch ($date_format) {
            case "%d-%m-%Y":
                $picker = "dd-mm-yyyy";
                $phptime = "d-m-Y";
                break;
            case "%m-%d-%Y":
                $picker = "mm-dd-yyyy";
                $phptime = "m-d-Y";
                break;
            case "%Y-%m-%d":
                $picker = "yyyy-mm-dd";
                $phptime = "Y-m-d";
                break;
            case "%m.%d.%Y":
                $picker = "yyyy.mm.dd";
                $phptime = "Y.m.d";
                break;
            case "%d.%m.%Y":
                $picker = "dd.mm.yyyy";
                $phptime = "d.m.Y";
                break;
            case "%Y.%m.%d":
                $picker = "yyyy.mm.dd";
                $phptime = "Y.m.d";
                break;
        }

        $this->db->where('config_key', 'date_picker_format')->update('tbl_config', array("value" => $picker));
        $this->db->where('config_key', 'date_php_format')->update('tbl_config', array("value" => $phptime));

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_save_system_settings'),
            'value1' => $input_data['default_language']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('save_system_settings');
        set_message($type, $message);
        redirect('admin/settings/system');
    }

    public function theme()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'theme';
        $data['title'] = lang('theme_settings'); //Page title
        $can_do = can_do(120);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function email()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'email_settings';
        $data['title'] = lang('email_settings'); //Page title              
        $can_do = can_do(113);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function update_email()
    {
        $input_data = $this->settings_model->array_from_post(array('company_email', 'use_postmark',
            'postmark_api_key', 'postmark_from_address', 'protocol', 'smtp_host', 'smtp_user', 'smtp_port', 'smtp_encryption'));
        foreach ($input_data as $key => $value) {
            if (strtolower($value) == 'on') {
                $value = 'TRUE';
            } elseif (strtolower($value) == 'off') {
                $value = 'FALSE';
            }
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        $smtp_pass = $this->input->post('smtp_pass', true);

        if (!empty($smtp_pass)) {
            $this->load->library('encrypt');
            $raw_smtp_pass = $this->input->post('smtp_pass');
            $smtp_pass = $raw_smtp_pass;

            $data = array('value' => $smtp_pass);
            $this->db->where('config_key', 'smtp_pass')->update('tbl_config', $data);
            $exists = $this->db->where('config_key', 'smtp_pass')->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_save_email_settings'),
            'value1' => $input_data['company_email']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('save_email_settings');
        set_message($type, $message);
        redirect('admin/settings/email');
    }

    public function save_theme()
    {
        $input_data = $this->settings_model->array_from_post(array('website_name', 'logo_or_icon', 'sidebar_theme', 'aside-float', 'aside-collapsed', 'layout-boxed', 'layout-fixed'));

        //image Process

        if (!empty($_FILES['company_logo']['name'])) {
            $val = $this->settings_model->uploadImage('company_logo');
            $val == TRUE || redirect('admin/settings/theme');
            $input_data['company_logo'] = $val['path'];
        }
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
            'activity' => ('activity_save_theme_settings'),
            'value1' => $input_data['website_name']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        // messages for user
        $type = "success";
        $message = lang('save_theme_settings');
        set_message($type, $message);
        redirect('admin/settings/theme');
    }

    public function templates()
    {
        if ($_POST) {
            $data = array(
                'subject' => $this->input->post('subject'),
                'template_body' => $this->input->post('email_template'),
            );

            $this->db->where(array('email_group' => $_POST['email_group']))->update('tbl_email_templates', $data);
            $return_url = $_POST['return_url'];
            redirect($return_url);
        } else {
            $data['page'] = lang('settings');
            $data['load_setting'] = 'templates';
            $data['title'] = lang('email_templates'); //Page title              
            $can_do = can_do(114);
            if (!empty($can_do)) {
                $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
            } else {
                $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
            }
            $this->load->view('admin/_layout_main', $data); //page load
        }
    }

    public function translations($lang = null)
    {
        $data['page'] = lang('settings');


        if (!empty($lang)) {
            $data['language'] = $lang;
            $data['language_files'] = $this->language_files;
        } else {
            $data['active_language'] = $this->settings_model->get_active_languages();
            $data['availabe_language'] = $this->settings_model->available_translations();
        }

        $data['translation_stats'] = $this->settings_model->translation_stats($this->language_files);

        $data['load_setting'] = 'translations';
        $data['title'] = lang('translations'); //Page title        
        $can_do = can_do(137);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function translations_status($language, $status)
    {
        $data['active'] = $status;
        $this->db->where('name', $language)->update('tbl_languages', $data);
        $type = 'success';
        if ($status == 1) {
            $message = lang('language_active_successfully');
        } else {
            $message = lang('language_deactive_successfully');
        }
        set_message($type, $message);
        redirect('admin/settings/translations');
    }

    public function add_language()
    {
        $language = $this->input->post('language', TRUE);
        $this->settings_model->add_language($language, $this->language_files);
        $type = 'success';
        $message = lang('language_added_successfully');
        set_message($type, $message);
        redirect('admin/settings/translations');
    }

    public function edit_translations($lang, $file)
    {
        $path = $this->language_files[$file . '_lang.php'];

        $data['language'] = $lang;
        //CI will record your lang file is loaded, unset it and then you will able to load another
        //unset the lang file to allow the loading of another file
        if (isset($this->lang->is_loaded)) {
            $loaded = sizeof($this->lang->is_loaded);
            if ($loaded < 3) {
                for ($i = 3; $i <= $loaded; $i++) {
                    unset($this->lang->is_loaded[$i]);
                }
            } else {
                for ($i = 0; $i <= $loaded; $i++) {
                    unset($this->lang->is_loaded[$i]);
                }
            }
        }
        $data['english'] = $this->lang->load($file, 'english', TRUE, TRUE, $path);
        if ($lang == 'english') {
            $data['translation'] = $data['english'];
        } else {
            $data['translation'] = $this->lang->load($file, $lang, TRUE, TRUE);
        }
        $data['active_language_files'] = $file;

        $data['load_setting'] = 'translations';
        $data['current_languages'] = $lang;

        $data['title'] = "Edit Translations"; //Page title
        $can_do = can_do(137);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function set_translations($lang, $file)
    {
        $this->settings_model->save_translation($lang, $file);
        // messages for user
        $type = "success";
        $message = '<strong style=color:#000>' . $lang . '</strong>' . " Information Successfully Update!";
        set_message($type, $message);
        redirect('admin/settings/translations');
    }

    public function payment_method($action = NULL, $id = NULL)
    {
        $data['page'] = lang('settings');
        if ($action == 'edit_payment_method') {
            $data['active'] = 2;
            if (!empty($id)) {
                $data['method_info'] = $this->settings_model->check_by(array('payment_methods_id' => $id), 'tbl_payment_methods');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('payment_method');
        if ($action == 'update_payment_method') {
            $this->settings_model->_table_name = 'tbl_payment_methods';
            $this->settings_model->_primary_key = 'payment_methods_id';
            $cate_data['method_name'] = $this->input->post('method_name', TRUE);
            // update root category
            $where = array('method_name' => $cate_data['method_name']);
            // duplicate value check in DB
            if (!empty($id)) { // if id exist in db update data
                $payment_methods_id = array('payment_methods_id !=' => $id);
            } else { // if id is not exist then set id as null
                $payment_methods_id = null;
            }
            // check whether this input data already exist or not
            $check_category = $this->settings_model->check_update('tbl_payment_methods', $where, $payment_methods_id);
            if (!empty($check_category)) { // if input data already exist show error alert
                // massage for user
                $type = 'error';
                $msg = "<strong style='color:#000'>" . $cate_data['method_name'] . '</strong>  ' . lang('already_exist');
            } else { // save and update query                        
                $id = $this->settings_model->save($cate_data, $id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_added_a_payment_method'),
                    'value1' => $cate_data['method_name']
                );
                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);

                // messages for user
                $type = "success";
                $msg = lang('payment_method_added');
            }
            $message = $msg;
            set_message($type, $message);
            redirect('admin/settings/payment_method');
        } else {
            $data['title'] = lang('payment_method'); //Page title                  
            $data['load_setting'] = 'payment_method';
        }

        $this->settings_model->_table_name = 'tbl_payment_methods';
        $this->settings_model->_order_by = 'payment_methods_id';
        $data['all_method_info'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;
        $can_do = can_do(131);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_payment_method($id)
    {
        $method_info = $this->settings_model->check_by(array('payment_methods_id' => $id), 'tbl_payment_methods');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $id,
            'activity' => ('activity_delete_a_method'),
            'value1' => $method_info->method_name,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $this->settings_model->_table_name = 'tbl_payment_methods';
        $this->settings_model->_primary_key = 'payment_methods_id';
        $this->settings_model->delete($id);
        // messages for user
        $type = "success";
        $message = lang('payment_method_deleted');
        set_message($type, $message);
        redirect('admin/settings/payment_method');
    }


    public function customer_group($action = NULL, $id = NULL)
    {
        $created = can_action('125', 'created');
        $edited = can_action('125', 'edited');
        $data['page'] = lang('settings');
        if ($action == 'edit_customer_group') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['customer_group_info'] = $this->settings_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
            }
        } else {
            $data['active'] = 1;
        }
        $data['page'] = lang('settings');
        $data['sub_active'] = lang('customer_group');
        if ($action == 'update_customer_group') {
            if (!empty($created) || !empty($edited)) {
                $this->settings_model->_table_name = 'tbl_customer_group';
                $this->settings_model->_primary_key = 'customer_group_id';

                $cate_data['customer_group'] = $this->input->post('customer_group', TRUE);
                $cate_data['description'] = $this->input->post('description', TRUE);

                // update root category
                $where = array('customer_group' => $cate_data['customer_group']);
                // duplicate value check in DB
                if (!empty($id)) { // if id exist in db update data
                    $customer_group_id = array('customer_group_id !=' => $id);
                } else { // if id is not exist then set id as null
                    $customer_group_id = null;
                }
                // check whether this input data already exist or not
                $check_category = $this->settings_model->check_update('tbl_customer_group', $where, $customer_group_id);
                if (!empty($check_category)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['customer_group'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('customer_group_added'),
                        'value1' => $cate_data['customer_group']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('customer_group_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/customer_group');
        } else {
            $data['title'] = lang('customer_group'); //Page title
            $data['load_setting'] = 'customer_group';
        }
        $this->settings_model->_table_name = 'tbl_customer_group';
        $this->settings_model->_order_by = 'customer_group_id';
        $data['all_customer_group'] = $this->settings_model->get();

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(125);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_customer_group($id)
    {
        $deleted = can_action('125', 'deleted');
        if (!empty($deleted)) {
            $customer_group = $this->settings_model->check_by(array('customer_group_id' => $id), 'tbl_customer_group');
            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_customer_group'),
                'value1' => $customer_group->customer_group,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $this->settings_model->_table_name = 'tbl_customer_group';
            $this->settings_model->_primary_key = 'customer_group_id';
            $this->settings_model->delete($id);
            // messages for user
            $type = "success";
            $message = lang('category_deleted');
            set_message($type, $message);
        }
        redirect('admin/settings/customer_group');
    }

    public function notification()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('notification_settings');
        // check notififation status by where
        $where = array('notify_me' => '1');
        // check email notification status
        $data['email'] = $this->settings_model->check_by($where, 'tbl_inbox');
        $data['load_setting'] = 'notification';
        $can_do = can_do(134);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function set_noticifation()
    {
// get input data
        $email = $this->input->post('email', TRUE);

        $where = array('notify_me' => '0');
        $action = array('notify_me' => '1');
// set notifucation into tbl inox
// notify status 1= on and 0=off
        if (!empty($email)) {

// check existing mail 
            $this->settings_model->_table_name = "tbl_inbox"; //table name        
            $this->settings_model->_order_by = "inbox_id";    //id
            $check_email = $this->settings_model->get();

            if (empty($check_email)) {
                $type = "danger";
                $message = lang('no_email_notify');
                $error_message['error_type'][] = $type;
                $error_message['error_message'][] = $message;
            }
            $status['notify_me'] = $email;

            $this->settings_model->set_action($where, $status, 'tbl_inbox'); // get result
        } else {
            $this->settings_model->set_action($action, $where, 'tbl_inbox'); // get result
        }


        $type = "success";
        $message = lang('notification_settings_changes');
        $error_message['error_type'][] = $type;
        $error_message['error_message'][] = $message;
        $this->session->set_userdata($error_message);
        redirect('admin/settings/notification'); //redirect page
    }

    public function tickets()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'tickets';
        $data['title'] = lang('tickets_settings'); //Page title
        $data['assign_user'] = $this->settings_model->allowad_user('55');

        $can_do = can_do(119);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_tickets()
    {

        $input_data = $this->settings_model->array_from_post(array('default_department', 'default_status', 'default_priority'));

        $permission = $this->input->post('default_lead_permission', true);
        if (!empty($permission)) {

            if ($permission == 'everyone') {
                $assigned = 'all';
            } else {
                $assigned_to = $this->settings_model->array_from_post(array('assigned_to'));
                if (!empty($assigned_to['assigned_to'])) {
                    foreach ($assigned_to['assigned_to'] as $assign_user) {
                        $assigned[$assign_user] = $this->input->post('action_' . $assign_user, true);
                    }
                }
            }
            if ($assigned != 'all') {
                $assigned = json_encode($assigned);
            }
            $input_data['default_lead_permission'] = $assigned;

        } else {
            set_message('error', lang('assigned_to') . ' Field is required');
            redirect($_SERVER['HTTP_REFERER']);
        }

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
            'activity' => ('activity_save_tickets_settings'),
            'value1' => $input_data['default_status']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('save_tickets_settings');
        set_message($type, $message);
        redirect('admin/settings/tickets');
    }

    public function update_profile()
    {
        $data['title'] = lang('update_profile');
        $data['subview'] = $this->load->view('admin/settings/update_profile', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function profile_updated()
    {
        $user_id = $this->session->userdata('user_id');
        $profile_data = $this->settings_model->array_from_post(array('fullname', 'phone', 'language', 'locale'));

        if (!empty($_FILES['avatar']['name'])) {
            $val = $this->settings_model->uploadImage('avatar');
            $val == TRUE || redirect('admin/settings/update_profile');
            $profile_data['avatar'] = $val['path'];
        }

        $this->settings_model->_table_name = 'tbl_account_details';
        $this->settings_model->_primary_key = 'user_id';
        $this->settings_model->save($profile_data, $user_id);

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => ('activity_update_profile'),
            'value1' => $profile_data['fullname'],
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $client_id = $this->input->post('client_id', TRUE);
        if (!empty($client_id)) {
            $client_data = $this->settings_model->array_from_post(array('name', 'email', 'address'));
            $this->settings_model->_table_name = 'tbl_client';
            $this->settings_model->_primary_key = 'client_id';
            $this->settings_model->save($client_data, $client_id);
        }
        $type = "success";
        $message = lang('profile_updated');
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    public function set_password()
    {
        $user_id = $this->session->userdata('user_id');
        $password = $this->hash($this->input->post('old_password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $new_password = $this->input->post('new_password', true);
            $confirm_password = $this->input->post('confirm_password', true);
            if ($new_password == $confirm_password) {
                $data['password'] = $this->hash($new_password);
                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $this->settings_model->save($data, $user_id);
                $type = "success";
                $message = lang('password_updated');
                $action = ('activity_password_update');
            } else {
                $type = "error";
                $message = lang('password_does_not_match');
                $action = ('activity_password_error');
            }
        } else {
            $type = "error";
            $message = lang('password_error');
            $action = ('activity_password_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->username,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    public function change_email()
    {
        $user_id = $this->session->userdata('user_id');
        $password = $this->hash($this->input->post('password', TRUE));
        $check_old_pass = $this->settings_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $new_email = $this->input->post('email', TRUE);
            if ($check_old_pass->email == $new_email) {
                $type = 'error';
                $message = lang('current_email');
                $action = lang('trying_update_email');
            } elseif ($this->is_email_available($new_email)) {
                $data = array(
                    'new_email' => $new_email,
                    'new_email_key' => md5(rand() . microtime()),
                );

                $this->settings_model->_table_name = 'tbl_users';
                $this->settings_model->_primary_key = 'user_id';
                $this->settings_model->save($data, $user_id);
                $data['user_id'] = $user_id;
                $this->send_email_change_email($new_email, $data);
                $type = "success";
                $message = lang('succesffuly_change_email');
                $action = lang('activity_updated_email');
            } else {
                $type = "error";
                $message = lang('duplicate_email');
                $action = ('trying_update_email');
            }
        } else {
            $type = "error";
            $message = lang('password_error');
            $action = ('trying_update_email');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->email,
            'value2' => $new_email,
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    function send_email_change_email($email, $data)
    {
        $email_template = $this->settings_model->check_by(array('email_group' => 'change_email'), 'tbl_email_templates');
        $message = $email_template->template_body;
        $subject = $email_template->subject;

        $email_key = str_replace("{NEW_EMAIL_KEY_URL}", base_url() . 'login/reset_email/' . $data['user_id'] . '/' . $data['new_email_key'], $message);
        $new_email = str_replace("{NEW_EMAIL}", $data['new_email'], $email_key);
        $site_url = str_replace("{SITE_URL}", base_url(), $new_email);
        $message = str_replace("{SITE_NAME}", config_item('company_name'), $site_url);

        $params['recipient'] = $email;

        $params['subject'] = '[ ' . config_item('company_name') . ' ]' . ' ' . $subject;
        $params['message'] = $message;

        $params['resourceed_file'] = '';
        $this->settings_model->send_email($params);
    }

    function is_email_available($email)
    {

        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        $this->db->or_where('LOWER(new_email)=', strtolower($email));
        $query = $this->db->get('tbl_users');
        return $query->num_rows() == 0;
    }

    public function hash($string)
    {
        return hash('sha512', $string . config_item('encryption_key'));
    }

    public function change_username()
    {
        $user_id = $this->session->userdata('user_id');
        $password = $this->hash($this->input->post('password', TRUE));
        $check_old_pass = $this->admin_model->check_by(array('password' => $password), 'tbl_users');
        $user_info = $this->admin_model->check_by(array('user_id' => $user_id), 'tbl_users');
        if (!empty($check_old_pass)) {
            $data['username'] = $this->input->post('username');
            $this->settings_model->_table_name = 'tbl_users';
            $this->settings_model->_primary_key = 'user_id';
            $this->settings_model->save($data, $user_id);
            $type = "success";
            $message = lang('username_updated');
            $action = ('activity_username_updated');
        } else {
            $type = "error";
            $message = lang('password_error');
            $action = ('username_changed_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $user_id,
            'activity' => $action,
            'value1' => $user_info->username,
            'value2' => $this->input->post('username'),
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);
        set_message($type, $message);
        redirect('admin/settings/update_profile'); //redirect page
    }

    public function database_backup()
    {
        $data['title'] = lang('database_backup');
        $data['page'] = lang('database_backup');
        $data['load_setting'] = 'database_backup';
        $this->load->helper('file');
        $data['backups'] = get_filenames('./uploads/backup/');

        $can_do = can_do(136);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);

    }

    public function db_backup()
    {
        $this->load->helper('file');
        $this->load->dbutil();
        $prefs = array('format' => 'zip', 'filename' => 'BD-backup_' . date('Y-m-d_H-i'));

        $backup = $this->dbutil->backup($prefs);
        if (!write_file('./uploads/backup/BD-backup_' . date('Y-m-d_H-i') . '.zip', $backup)) {
            $type = 'success';
            $message = lang('backup_error');
        } else {
            $type = 'success';
            $message = lang('backup_success');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => 'activity_database_backup',
            'value1' => $prefs['filename']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        set_message($type, $message);
        redirect('admin/settings/database_backup');
    }

    function download_backup($file)
    {
        $this->load->helper('file');
        $this->load->helper('download');
        $data = file_get_contents('./uploads/backup/' . $file);
        force_download($file, $data);
        redirect('admin/settings/database_backup');
    }

    public function delete_backup($file)
    {
        if (unlink('./uploads/backup/' . $file)) {
            $type = 'success';
            $message = lang('backup_delete_success');
        } else {
            $type = 'error';
            $message = lang('backup_error');
        }
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => 'activity_backup_delete_success',
            'value1' => $file
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        set_message($type, $message);
        redirect('admin/settings/database_backup');
    }

    function restore_database()
    {
        if ($_POST) {
            $this->load->helper('file');
            $this->load->helper('unzip');
            $this->load->database();

            $config['upload_path'] = './uploads/temp/';
            $config['allowed_types'] = '*';
            $config['max_size'] = '9000';
            $config['overwrite'] = TRUE;

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('upload_file')) {
                $error = $this->upload->display_errors('', ' ');
                $type = 'error';
                $message = $error;
                set_message($type, $message);
                redirect('admin/settings/database_backup');
            } else {
                $data = array('upload_data' => $this->upload->data());
                $backup = "uploads/temp/" . $data['upload_data']['file_name'];

            }
            if (!unzip($backup, "uploads/temp/", true, true)) {
                $type = 'error';
                $message = lang('backup_restore_error');
            } else {
                $this->load->dbforge();
                $backup = str_replace('.zip', '', $backup);
                $file_content = file_get_contents($backup . ".sql");
                $this->db->query('USE ' . $this->db->database . ';');
                foreach (explode(";\n", $file_content) as $sql) {
                    $sql = trim($sql);
                    if ($sql) {
                        $this->db->query($sql);
                    }
                }
                $type = 'success';
                $message = lang('backup_restore_success');

            }
            unlink($backup . ".sql");
            unlink($backup . ".zip");

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => 'activity_restore_database',
                'value1' => $backup
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            set_message($type, $message);
            redirect('admin/settings/database_backup');
        } else {
            $data['title'] = lang('restore_database');
            $data['subview'] = $this->load->view('admin/settings/restore_database', $data, FALSE);
            $this->load->view('admin/_layout_modal', $data);
        }
    }

    public function activities()
    {
        $data['title'] = lang('activities');
        $data['activities_info'] = $this->db->where(array('user' => $this->session->userdata('user_id')))->order_by('activity_date', 'DESC')->get('tbl_activities')->result();

        $data['subview'] = $this->load->view('admin/settings/activities', $data, TRUE);
        $this->load->view('admin/_layout_main', $data);
    }

    public function clear_activities()
    {
        $this->db->where(array('user' => $this->session->userdata('user_id')))->delete('tbl_activities');
        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => 'activity_deleted',
            'value1' => lang('all_activity') . date('Y-m-d')
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        $type = "success";
        $message = lang('activities_deleted');
        set_message($type, $message);
        redirect('admin/dashboard');
    }

    public function new_currency($action = null, $code = null)
    {
        if (!empty($action)) {

            $data = $this->settings_model->array_from_post(array('code', 'name', 'symbol'));
            if (!empty($code)) {
                $this->db->set($data);
                $this->db->where('code', $code);
                $this->db->update('tbl_currencies');
                redirect('admin/settings/all_currency');
            } else {
                $this->settings_model->_table_name = 'tbl_currencies';
                $this->settings_model->save($data);
                redirect('admin/settings/system');
            }

        }
        $data['title'] = lang('activities');
        $data['modal_subview'] = $this->load->view('admin/settings/_modal_new_currency', $data, FALSE);
        $this->load->view('admin/_layout_modal', $data);
    }

    public function custom_field($id = null)
    {
        $edited = can_action('130', 'edited');
        $data['page'] = lang('settings');
        $data['load_setting'] = 'custom_field';
        if (!empty($id) && !empty($edited)) {
            $data['active'] = 2;
            $data['field_info'] = $this->db->where('custom_field_id', $id)->get('tbl_custom_field')->row();
        } else {
            $data['active'] = 1;
        }

        $data['title'] = lang('custom_field'); //Page title
        $can_do = can_do(130);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public
    function save_custom_field($id = null)
    {
        $created = can_action('130', 'created');
        $edited = can_action('130', 'edited');
        if (!empty($created) || !empty($edited)) {
            $data = $this->settings_model->array_from_post(array('form_id', 'field_label', 'field_type', 'help_text'));

            $data['default_value'] = json_encode($this->input->post('default_value', true));

            $required = $this->input->post('required', true);
            if (empty($required)) {
                $data['required'] = 'false';
            }
            $required = $this->input->post('show_on_details', true);
            if (empty($required)) {
                $data['show_on_details'] = 'No';
            }

            $form_info = $this->db->where('form_id', $data['form_id'])->get('tbl_form')->row();


            $fieldName = strtolower(preg_replace('/\s+/', '_', $data['field_label']));
            $this->load->dbforge();
            if (!empty($id)) {
                $field_info = $this->db->where('custom_field_id', $id)->get('tbl_custom_field')->row();
                $fieldName = strtolower(preg_replace('/\s+/', '_', $field_info->field_label));
                $form = $this->db->where('form_id', $field_info->form_id)->get('tbl_form')->row();
                if ($this->db->field_exists($fieldName, $form_info->tbl_name)) {
                    $this->dbforge->drop_column($form->tbl_name, $fieldName);
                    $type = "success";
                    $message = lang('save_custom_field');
                }
            }
            if ($this->db->field_exists($fieldName, $form_info->tbl_name)) {
                $fieldName = strtolower(preg_replace('/\s+/', '__', $fieldName));
            }
            $fields = array(
                $fieldName => array(
                    'type' => 'TEXT',
                    'null' => true
                )
            );
            $result = $this->dbforge->add_column($form_info->tbl_name, $fields);
            if ($data['form_id'] == 1 || $data['form_id'] == 2) {
                $this->settings_model->_table_name = 'tbl_custom_field';
                $this->settings_model->_primary_key = 'custom_field_id';
                $this->settings_model->save($data, $id);
                $type = "success";
                $message = lang('save_custom_field');
            } elseif (!empty($result)) {
                $this->settings_model->_table_name = 'tbl_custom_field';
                $this->settings_model->_primary_key = 'custom_field_id';
                $this->settings_model->save($data, $id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $this->session->userdata('user_id'),
                    'activity' => ('activity_new_custom_field'),
                    'value1' => $data['field_label']
                );

                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);
                // messages for user
                $type = "success";
                $message = lang('save_custom_field');
            } else {
                $fieldName = strtolower(preg_replace('/\s+/', '_', $field_info->field_label));
                $fields = array(
                    $fieldName => array(
                        'type' => 'TEXT',
                        'null' => true
                    )
                );
                $this->dbforge->add_column($form->tbl_name, $fields);
                $type = "error";
                $message = lang('custom_field_already_exist');
            }
            $type = $type;
            $message = $message;
            set_message($type, $message);
        }
        redirect('admin/settings/custom_field');
    }

    public
    function change_field_status($id = null)
    {
        $data['status'] = $this->input->post('status');
        $this->settings_model->_table_name = 'tbl_custom_field';
        $this->settings_model->_primary_key = 'custom_field_id';
        $this->settings_model->save($data, $id);
        echo true;
    }

    public function detele_custom_field($id)
    {
        $deleted = can_action('130', 'deleted');
        if (!empty($deleted)) {
            $field_info = $this->db->where('custom_field_id', $id)->get('tbl_custom_field')->row();

            $fName = strtolower(preg_replace('/\s+/', '_', $field_info->field_label));
            $form = $this->db->where('form_id', $field_info->form_id)->get('tbl_form')->row();

            $this->load->dbforge();
            $this->dbforge->drop_column($form->tbl_name, $fName);

            $this->settings_model->_table_name = 'tbl_custom_field';
            $this->settings_model->_primary_key = 'custom_field_id';
            $this->settings_model->delete($id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $this->session->userdata('user_id'),
                'activity' => ('activity_delete_custom_field'),
                'value1' => $field_info->field_label
            );

            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);
            // messages for user
            $type = "success";
            $message = lang('delete_custom_field');
            set_message($type, $message);
        }
        redirect('admin/settings/custom_field');


    }

    public function email_integration()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'email_integration';
        $data['title'] = lang('email_integration'); //Page title
        $can_do = can_do(115);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_email_integration()
    {
        $input_data = $this->settings_model->array_from_post(array('config_imap_or_pop', 'config_ssl',
            'delete_mail_after_import', 'config_host', 'config_username', 'config_password',
            'config_port', 'config_mailbox', 'config_additional_flag', 'imap_search_for_tickets', 'tickets_keyword', 'for_tickets'));

        $input_data['notified_user'] = json_encode($this->input->post('notified_user'), true);

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
            'activity' => ('activity_save_email_integration'),
            'value1' => $input_data['config_host']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('save_email_integration');
        set_message($type, $message);
        redirect('admin/settings/email_integration');
    }

    public function test_email()
    {
        $config['login'] = config_item('config_username');
        $config['pass'] = config_item('config_password');
        $config['host'] = config_item('config_host');
        $config['port'] = config_item('config_port');
        $config['mailbox'] = config_item('config_mailbox');

        if (config_item('config_imap_or_pop') == "on") {
            $flags = "/imap";
        } else {
            $flags = "/pop3";
        }
        if (config_item('config_ssl') == "on") {
            $flags .= "/ssl";
        }

        $config['service_flags'] = $flags . config_item('config_additional_flag');

        $this->load->library('peeker_connect');
        $this->peeker_connect->initialize($config);

        if ($this->peeker_connect->is_connected()) {
            $type = "success";
            $header = lang('connection_success');
        } else {
            $type = "error";
            $header = lang('connection_error');
        }
        $this->peeker_connect->message_waiting();
        $this->peeker_connect->close();

        $s_data['trace'] = $this->peeker_connect->trace();
        $s_data['header'] = $header;
        $this->session->set_userdata($s_data);

        set_message($type, $header);
        redirect('admin/settings/email_integration');

    }

    public function cronjob()
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'cronjob';
        $data['title'] = lang('cronjob'); //Page title
        $can_do = can_do(132);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function all_currency($code = null)
    {
        $data['page'] = lang('settings');
        $data['load_setting'] = 'all_currency';
        $data['title'] = lang('cronjob'); //Page title
        if (!empty($code)) {
            $data['currency'] = $this->db->where('code', $code)->get('tbl_currencies')->row();

        }
        $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_cronjob()
    {
        $input_data = $this->settings_model->array_from_post(array('active_cronjob', 'automatic_database_backup'));
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
            'activity' => ('activity_save_cronjob'),
            'value1' => $input_data['active_cronjob']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('save_cronjob');
        set_message($type, $message);
        redirect('admin/settings/cronjob');
    }

    public function working_days($action = NULL, $id = NULL)
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('working_days'); //Page title
        $data['load_setting'] = 'working_days';

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(121);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function save_working_days()
    {
        // delete all working days after save and again save
        $this->db->truncate('tbl_working_days');

        // save office time into tbl_config
        $office_time['office_time'] = $this->input->post('office_time', TRUE);
        if (!empty($office_time)) {
            foreach ($office_time as $key => $value) {
                $office_data = array('value' => $value);
                $this->db->where('config_key', $key)->update('tbl_config', $office_data);
                $exists = $this->db->where('config_key', $key)->get('tbl_config');
                if ($exists->num_rows() == 0) {
                    $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
                }
            }
        }

        $start_hours = $this->input->post('start_hours', TRUE);
        $end_hours = $this->input->post('end_hours', TRUE);

        $get_day = $this->input->post('day', TRUE);

        $day_id = $this->input->post('day_id', TRUE);

        foreach ($day_id as $skey => $day) {
            $data['flag'] = 0;
            $data['day_id'] = $day;
            $data['start_hours'] = '00:00:00';
            $data['end_hours'] = '00:00:00';
            // if it's same time so input same time data else get different time
            if ($office_time['office_time'] == 'same_time') {
                $data['start_hours'] = date('H:i:s', strtotime($start_hours[0]));
                $data['end_hours'] = date('H:i:s', strtotime($end_hours[0]));
            }
            if (!empty($get_day)) {
                foreach ($get_day as $key => $days) {
                    if ($day == $days) {
                        if ($office_time['office_time'] == 'different_time') {
                            if (!empty($start_hours[$key])) {
                                $data['start_hours'] = date('H:i:s', strtotime($start_hours[$key]));
                            }
                            if (!empty($end_hours[$key])) {
                                $data['end_hours'] = date('H:i:s', strtotime($end_hours[$key]));
                            }
                        }
                        $data['flag'] = 1;
                    }
                }
            }
            $this->settings_model->_table_name = "tbl_working_days"; // table name
            $this->settings_model->_primary_key = "working_days_id"; // $id
            $this->settings_model->save($data);
        }

        $activity = array(
            'user' => $this->session->userdata('user_id'),
            'module' => 'settings',
            'module_field_id' => $this->session->userdata('user_id'),
            'activity' => ('activity_update_working_days'),
            'value1' => $office_time['office_time']
        );
        $this->settings_model->_table_name = 'tbl_activities';
        $this->settings_model->_primary_key = 'activities_id';
        $this->settings_model->save($activity);

        // messages for user
        $type = "success";
        $message = lang('update_working_days');
        set_message($type, $message);
        redirect('admin/settings/working_days');
    }

    public function leave_category($action = NULL, $id = NULL)
    {
        $edited = can_action('122', 'edited');
        $created = can_action('122', 'created');

        $data['page'] = lang('settings');
        if ($action == 'edit_leave_category') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['leave_category_info'] = $this->settings_model->check_by(array('leave_category_id' => $id), 'tbl_leave_category');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_leave_category') {
            if (!empty($created) || !empty($edited)) {
                $this->settings_model->_table_name = 'tbl_leave_category';
                $this->settings_model->_primary_key = 'leave_category_id';
                // input data
                $cate_data = $this->settings_model->array_from_post(array('leave_category', 'leave_quota')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $leave_category_id = array('leave_category_id !=' => $id);
                } else {
                    $leave_category_id = null;
                }
                // check check_leave_category by where
                // if not empty show alert message else save data
                $check_leave_category = $this->settings_model->check_update('tbl_leave_category', $where = array('leave_category' => $cate_data['leave_category']), $leave_category_id);

                if (!empty($check_leave_category)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['leave_category'] . '</strong>  ' . lang('already_exist');
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_a_leave_category'),
                        'value1' => $cate_data['leave_category']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = lang('leave_category_added');
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/leave_category');
        } else {
            $data['title'] = lang('leave_category'); //Page title
            $data['load_setting'] = 'leave_category';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(122);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_leave_category($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            // check into application list
            $where = array('leave_category_id' => $id);
            // check existing leave category into tbl_application_list
            $check_existing_ctgry = $this->settings_model->check_by($where, 'tbl_leave_application');
            // check existing leave category into tbl_attendance
            $check_into_attendace = $this->settings_model->check_by($where, ' tbl_attendance');
            if (!empty($check_into_attendace) || !empty($check_existing_ctgry)) { // if not empty do not delete this else delete
                // messages for user
                $type = "error";
                $message = lang('leave_category_used');

            } else {
                $this->settings_model->_table_name = "tbl_leave_category"; //table name
                $this->settings_model->_primary_key = "leave_category_id";    //id
                $this->settings_model->delete($id);

                $activity = array(
                    'user' => $this->session->userdata('user_id'),
                    'module' => 'settings',
                    'module_field_id' => $id,
                    'activity' => ('activity_delete_a_leave_category'),
                    'value1' => $this->db->where('leave_category_id', $id)->get('tbl_leave_category')->row()->leave_category,
                );
                $this->settings_model->_table_name = 'tbl_activities';
                $this->settings_model->_primary_key = 'activities_id';
                $this->settings_model->save($activity);

                $type = "success";
                $message = lang('leave_category_deleted');
            }
            set_message($type, $message);
        }
        redirect('admin/settings/leave_category'); //redirect page
    }    
    public function lga($action = NULL, $id = NULL)
    {
        $edited = can_action('122', 'edited');
        $created = can_action('122', 'created');

        $data['page'] = lang('settings');
        if ($action == 'edit_lga') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['lga_info'] = $this->settings_model->check_by(array('lga_id' => $id), 'tbl_lga');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_lga') {
            if (!empty($created) || !empty($edited)) {
                $this->settings_model->_table_name = 'tbl_lga';
                $this->settings_model->_primary_key = 'lga_id';
                // input data
                //print_r($_POST);
                $cate_data = $this->settings_model->array_from_post(array('lga', 'lga_code')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $lga_id = array('lga_id !=' => $id);
                } else {
                    $lga_id = null;
                }
                // check check_leave_category by where
                // if not empty show alert message else save data
                $check_lga = $this->settings_model->check_update('tbl_lga', $where = array('lga' => $cate_data['lga']), $lga_id);

                if (!empty($check_lga)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['lga'] . '</strong> already exists  ';
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_lga'),
                        'value1' => $cate_data['lga']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = 'lga_added';
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/lga');
        } else {
            $data['title'] = 'lga'; //Page title
            $data['load_setting'] = 'lga';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(122);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }


    public function delete_lga($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            // check into application list
            $where = array('lga_id' => $id);
            // check existing leave category into tbl_application_list
            $check_existing_ctgry = $this->settings_model->check_by($where, 'tbl_lga');
            // check existing leave category into tbl_attendance
            /*$check_into_attendace = $this->settings_model->check_by($where, ' tbl_attendance');
            if (!empty($check_into_attendace) || !empty($check_existing_ctgry)) { // if not empty do not delete this else delete
                // messages for user
                $type = "error";
                $message = lang('leave_category_used');

            } else {*/
            $this->settings_model->_table_name = "tbl_lga"; //table name
            $this->settings_model->_primary_key = "lga_id";    //id
            $this->settings_model->delete($id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_lga'),
                'value1' => $this->db->where('lga_id', $id)->get('tbl_lga')->row()->lga,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $type = "success";
            $message = "LGA Deleted";
            //}
            set_message($type, $message);
        }
        redirect('admin/settings/lga'); //redirect page
    }

    public function division($action = NULL, $id = NULL)
    {
        $edited = can_action('122', 'edited');
        $created = can_action('122', 'created');

        $data['page'] = lang('settings');
        if ($action == 'edit_division') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['division_info'] = $this->settings_model->check_by(array('division_id' => $id), 'tbl_division');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_division') {
            if (!empty($created) || !empty($edited)) {
                $this->settings_model->_table_name = 'tbl_division';
                $this->settings_model->_primary_key = 'division_id';
                // input data
                //print_r($_POST);
                $cate_data = $this->settings_model->array_from_post(array('division', 'division_code')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $division_id = array('division_id !=' => $id);
                } else {
                    $division_id = null;
                }
                // check check_leave_category by where
                // if not empty show alert message else save data
                $check_division = $this->settings_model->check_update('tbl_division', $where = array('division' => $cate_data['division']), $division_id);

                if (!empty($check_division)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['division'] . '</strong> already exists  ';
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_division'),
                        'value1' => $cate_data['division']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = 'Division added';
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/division');
        } else {
            $data['title'] = 'division'; //Page title
            $data['load_setting'] = 'division';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(122);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_division($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            // check into application list
            $where = array('division_id' => $id);
            // check existing leave category into tbl_application_list
            $check_existing_ctgry = $this->settings_model->check_by($where, 'tbl_division');
            // check existing leave category into tbl_attendance
            /*$check_into_attendace = $this->settings_model->check_by($where, ' tbl_attendance');
            if (!empty($check_into_attendace) || !empty($check_existing_ctgry)) { // if not empty do not delete this else delete
                // messages for user
                $type = "error";
                $message = lang('leave_category_used');

            } else {*/
            $this->settings_model->_table_name = "tbl_division"; //table name
            $this->settings_model->_primary_key = "division_id";    //id
            $this->settings_model->delete($id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_division'),
                'value1' => $this->db->where('division_id', $id)->get('tbl_division')->row()->division,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $type = "success";
            $message = "Division Deleted";
            //}
            set_message($type, $message);
        }
        redirect('admin/settings/division'); //redirect page
    }

    public function union($action = NULL, $id = NULL)
    {
        $edited = can_action('122', 'edited');
        $created = can_action('122', 'created');

        $data['page'] = lang('settings');
        if ($action == 'edit_union') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['union_info'] = $this->settings_model->check_by(array('union_id' => $id), 'tbl_union');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_union') {
            if (!empty($created) || !empty($edited)) {
                $this->settings_model->_table_name = 'tbl_union';
                $this->settings_model->_primary_key = 'union_id';
                // input data
                //print_r($_POST);
                $cate_data = $this->settings_model->array_from_post(array('union', 'union_code')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $union_id = array('union_id !=' => $id);
                } else {
                    $union_id = null;
                }
                // check check_leave_category by where
                // if not empty show alert message else save data
                $check_union = $this->settings_model->check_update('tbl_union', $where = array('union' => $cate_data['union']), $union_id);

                if (!empty($check_union)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['union'] . '</strong> already exists  ';
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_union'),
                        'value1' => $cate_data['union']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = 'Union added';
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/union');
        } else {
            $data['title'] = 'union'; //Page title
            $data['load_setting'] = 'union';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(122);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_union($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            // check into application list
            $where = array('union_id' => $id);
            // check existing leave category into tbl_application_list
            $check_existing_ctgry = $this->settings_model->check_by($where, 'tbl_union');
            // check existing leave category into tbl_attendance
            /*$check_into_attendace = $this->settings_model->check_by($where, ' tbl_attendance');
            if (!empty($check_into_attendace) || !empty($check_existing_ctgry)) { // if not empty do not delete this else delete
                // messages for user
                $type = "error";
                $message = lang('leave_category_used');

            } else {*/
            $this->settings_model->_table_name = "tbl_union"; //table name
            $this->settings_model->_primary_key = "union_id";    //id
            $this->settings_model->delete($id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_union'),
                'value1' => $this->db->where('union_id', $id)->get('tbl_union')->row()->union,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $type = "success";
            $message = "Union Deleted";
            //}
            set_message($type, $message);
        }
        redirect('admin/settings/union'); //redirect page
    }
    
    public function bank($action = NULL, $id = NULL)
    {
        $edited = can_action('122', 'edited');
        $created = can_action('122', 'created');

        $data['page'] = lang('settings');
        if ($action == 'edit_bank') {
            $data['active'] = 2;
            if (!empty($id) && !empty($edited)) {
                $data['bank_info'] = $this->settings_model->check_by(array('bank_id' => $id), 'tbl_bank');
            }
        } else {
            $data['active'] = 1;
        }

        if ($action == 'update_bank') {
            if (!empty($created) || !empty($edited)) {
                $this->settings_model->_table_name = 'tbl_bank';
                $this->settings_model->_primary_key = 'bank_id';
                // input data
                //print_r($_POST);
                $cate_data = $this->settings_model->array_from_post(array('bank', 'bank_code')); //input post
                // dublicacy check
                if (!empty($id)) {
                    $bank_id = array('bank_id !=' => $id);
                } else {
                    $bank_id = null;
                }
                // check check_leave_category by where
                // if not empty show alert message else save data
                $check_bank = $this->settings_model->check_update('tbl_bank', $where = array('bank' => $cate_data['bank']), $bank_id);

                if (!empty($check_bank)) { // if input data already exist show error alert
                    // massage for user
                    $type = 'error';
                    $msg = "<strong style='color:#000'>" . $cate_data['bank'] . '</strong> already exists  ';
                } else { // save and update query
                    $id = $this->settings_model->save($cate_data, $id);

                    $activity = array(
                        'user' => $this->session->userdata('user_id'),
                        'module' => 'settings',
                        'module_field_id' => $id,
                        'activity' => ('activity_added_bank'),
                        'value1' => $cate_data['bank']
                    );
                    $this->settings_model->_table_name = 'tbl_activities';
                    $this->settings_model->_primary_key = 'activities_id';
                    $this->settings_model->save($activity);

                    // messages for user
                    $type = "success";
                    $msg = 'bank_added';
                }
                $message = $msg;
                set_message($type, $message);
            }
            redirect('admin/settings/bank');
        } else {
            $data['title'] = 'bank'; //Page title
            $data['load_setting'] = 'bank';
        }

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;

        $can_do = can_do(122);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function delete_bank($id)
    {
        $deleted = can_action('122', 'deleted');
        if (!empty($deleted)) {
            // check into application list
            $where = array('bank_id' => $id);
            // check existing leave category into tbl_application_list
            $check_existing_ctgry = $this->settings_model->check_by($where, 'tbl_bank');
            // check existing leave category into tbl_attendance
            /*$check_into_attendace = $this->settings_model->check_by($where, ' tbl_attendance');
            if (!empty($check_into_attendace) || !empty($check_existing_ctgry)) { // if not empty do not delete this else delete
                // messages for user
                $type = "error";
                $message = lang('leave_category_used');

            } else {*/
            $this->settings_model->_table_name = "tbl_bank"; //table name
            $this->settings_model->_primary_key = "bank_id";    //id
            $this->settings_model->delete($id);

            $activity = array(
                'user' => $this->session->userdata('user_id'),
                'module' => 'settings',
                'module_field_id' => $id,
                'activity' => ('activity_delete_a_bank'),
                'value1' => $this->db->where('bank_id', $id)->get('tbl_bank')->row()->bank,
            );
            $this->settings_model->_table_name = 'tbl_activities';
            $this->settings_model->_primary_key = 'activities_id';
            $this->settings_model->save($activity);

            $type = "success";
            $message = "BANK Deleted";
            //}
            set_message($type, $message);
        }
        redirect('admin/settings/bank'); //redirect page
    }

    public function menu_allocation()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('menu_allocation'); //Page title
        $data['load_setting'] = 'menu_allocation';

        $user_id = $this->session->userdata('user_id');
        $user_info = $this->settings_model->check_by(array('user_id' => $user_id), 'tbl_users');
        $data['role'] = $user_info->role_id;
        $data['active_menu'] = $this->all_active_menu();
        $data['inactive_menu'] = $this->all_inactive_menu();

        $can_do = can_do(133);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data); //page load
    }

    public function all_active_menu()
    {
        $user_menu = $this->db->where('status', 1)->order_by('sort')->get('tbl_menu')->result();
        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        // Builds the array lists with data from the menu table
        foreach ($user_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }
        return $output = $this->buildMenu(0, $menu);
    }

    public function all_inactive_menu()
    {
        $user_menu = $this->db->where('status', 0)->order_by('sort', 'time')->get('tbl_menu')->result();

        $menu = array(
            'items' => array(),
            'parents' => array()
        );
        // Builds the array lists with data from the menu table
        foreach ($user_menu as $v_menu) {
            $menu['items'][$v_menu->menu_id] = $v_menu;
            $menu['parents'][$v_menu->parent][] = $v_menu->menu_id;
        }
        return $output = $this->buildMenu(0, $menu);
    }

    public function buildMenu($parent, $menu, $sub = NULL)
    {
        $html = "";
        if (isset($menu['parents'][$parent])) {
            if (!empty($sub)) {
                $html .= "<ol id=" . $sub . " class='dd-list'>\n";
            } else {
                $html .= "<ol class='dd-list'>\n";
            }
            foreach ($menu['parents'][$parent] as $itemId) {
                $active = '';

                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html .= "<li data-id='" . $itemId . "' class='dd-item' >\n  <div class='dd-handle'>" . lang($menu['items'][$itemId]->label) . "</div> \n</li> \n";
                }
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li data-id='" . $itemId . "' class='dd-item'>\n<div class='dd-handle'>" . lang($menu['items'][$itemId]->label) . "</div>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]->label);
                    $html .= "</li> \n";
                }
            }
            $html .= "</ol> \n";
        }
        return $html;
    }

    public function update_menu_allocation()
    {
        $all_menu = json_decode($this->input->post('all_active_menu', true));

        foreach ($all_menu as $r_sort => $root_menu) {

            $r_data['sort'] = $r_sort;
            $r_data['status'] = 1;
            $r_data['parent'] = 0;
            $this->settings_model->_table_name = "tbl_menu"; //table name
            $this->settings_model->_primary_key = "menu_id"; // $id
            $this->settings_model->save($r_data, $root_menu->id);

            if (!empty($root_menu->children)) {
                foreach ($root_menu->children as $child_sort => $sub_menu) {
                    $c_data['sort'] = $child_sort;
                    $c_data['status'] = 1;
                    $c_data['parent'] = $root_menu->id;
                    $this->settings_model->_table_name = "tbl_menu"; //table name
                    $this->settings_model->_primary_key = "menu_id"; // $id
                    $this->settings_model->save($c_data, $sub_menu->id);

                    if (!empty($sub_menu->children)) {
                        foreach ($sub_menu->children as $sub_child_sort => $sub_child_menu) {

                            $c_s_data['sort'] = $sub_child_sort;
                            $c_s_data['status'] = 1;
                            $c_s_data['parent'] = $sub_menu->id;
                            $this->settings_model->_table_name = "tbl_menu"; //table name
                            $this->settings_model->_primary_key = "menu_id"; // $id
                            $this->settings_model->save($c_s_data, $sub_child_menu->id);

                        }
                    }

                }
            }


        }
        $all_inactive_menu = json_decode($this->input->post('all_inactive_menu', true));
        foreach ($all_inactive_menu as $i_r_sort => $in_root_menu) {

            $in_r_data['sort'] = $i_r_sort;
            $in_r_data['status'] = 0;
            $in_r_data['parent'] = 0;

            $this->settings_model->_table_name = "tbl_menu"; //table name
            $this->settings_model->_primary_key = "menu_id"; // $id
            $this->settings_model->save($in_r_data, $in_root_menu->id);

            if (!empty($in_root_menu->children)) {
                foreach ($in_root_menu->children as $in_child_sort => $in_sub_menu) {
                    $in_c_data['sort'] = $in_child_sort;
                    $in_c_data['status'] = 0;
                    $in_c_data['parent'] = $in_root_menu->id;

                    $this->settings_model->_table_name = "tbl_menu"; //table name
                    $this->settings_model->_primary_key = "menu_id"; // $id
                    $this->settings_model->save($in_c_data, $in_sub_menu->id);

                    if (!empty($in_sub_menu->children)) {
                        foreach ($in_sub_menu->children as $in_sub_child_sort => $in_sub_child_menu) {

                            $in_c_s_data['sort'] = $in_sub_child_sort;
                            $in_c_s_data['status'] = 0;
                            $in_c_s_data['parent'] = $in_sub_menu->id;
                            $this->settings_model->_table_name = "tbl_menu"; //table name
                            $this->settings_model->_primary_key = "menu_id"; // $id
                            $this->settings_model->save($in_c_s_data, $in_sub_child_menu->id);

                        }
                    }

                }
            }
        }


        redirect('admin/settings/menu_allocation');

    }

    public function email_notification()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('email') . ' ' . lang('notification');

        $data['load_setting'] = 'email_notification';
        $can_do = can_do(135);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);
    }

    public function save_email_notification()
    {
        $input_data = $this->settings_model->array_from_post(array('leave_email', 'overtime_email',
            'payslip_email', 'advance_salary_email', 'award_email', 'job_circular_email', 'announcements_email', 'training_email'
        , 'expense_email', 'deposit_email'));
        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        // messages for user
        $type = "success";
        $message = lang('notification_settings_changes');
        set_message($type, $message);
        redirect('admin/settings/email_notification');
    }

    public function set_default($key, $value)
    {
        $input_data = array($key => $value);
        foreach ($input_data as $key => $value) {
            $data = array('value' => $value);
            $this->db->where('config_key', $key)->update('tbl_config', $data);
            $exists = $this->db->where('config_key', $key)->get('tbl_config');
            if ($exists->num_rows() == 0) {
                $this->db->insert('tbl_config', array("config_key" => $key, "value" => $value));
            }
        }
        // messages for user
        $type = "success";
        $message = lang('successfully_set_default');
        set_message($type, $message);
        redirect($_SERVER['HTTP_REFERER']);

    }


    public function system_update()
    {
        $data['page'] = lang('settings');
        $data['title'] = lang('system_update');
        if (!extension_loaded('curl')) {
            $data['update_errors'][] = 'CURL Extension not enabled';
            $data['latest_version'] = 0;
            $data['update_info'] = json_decode("");
        } else {
            $data['update_info'] = $this->admin_model->get_update_info();

            if (strpos($data['update_info'], 'Curl Error -') !== FALSE) {
                $data['update_errors'][] = $data['update_info'];
                $data['latest_version'] = 0;
                $data['update_info'] = json_decode("");
            } else {
                $data['update_info'] = json_decode($data['update_info']);
                $data['latest_version'] = $data['update_info']->latest_version;
                $data['update_errors'] = array();
            }
        }
        if (!extension_loaded('zip')) {
            $data['update_errors'][] = 'ZIP Extension not enabled';
        }
        $data['current_version'] = $this->db->get('tbl_migrations')->row()->version;
        $data['load_setting'] = 'system_update';
        $can_do = can_do(138);
        if (!empty($can_do)) {
            $data['subview'] = $this->load->view('admin/settings/settings', $data, TRUE);
        } else {
            $data['subview'] = $this->load->view('admin/settings/not_found', $data, TRUE);
        }
        $this->load->view('admin/_layout_main', $data);

    }


}