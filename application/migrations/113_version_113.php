<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Version_113 extends CI_Migration
{
    function __construct()
    {
        parent::__construct();
    }

    public function up()
    {
        $this->db->query("ALTER TABLE `tbl_task_comment` ADD (comments_attachment text NULL)");

        $this->db->query("ALTER TABLE `tbl_task` ADD (billable varchar(100) NULL DEFAULT 'No',index_no int(11) NULL DEFAULT 0)");

        $this->db->query("ALTER TABLE `tbl_client` ADD (latitude varchar(100) NULL,longitude varchar(100) NULL,customer_group_id int(11) NULL DEFAULT 0,active varchar(100) NULL)");

        $this->db->query("CREATE TABLE `tbl_customer_group` (`customer_group_id` int(11) NOT NULL,`customer_group` varchar(200) NOT NULL,`description` varchar(200) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->db->query("ALTER TABLE `tbl_customer_group` ADD PRIMARY KEY (`customer_group_id`)");
        $this->db->query("ALTER TABLE `tbl_customer_group` MODIFY `customer_group_id` int(11) NOT NULL AUTO_INCREMENT");

        $this->db->query("CREATE TABLE `tbl_client_menu` (`menu_id` int(11) NOT NULL,`label` varchar(20) DEFAULT NULL,`link` varchar(200) DEFAULT NULL,`icon` varchar(50) NOT NULL,`parent` int(11) NOT NULL,`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,`sort` int(11) NOT NULL,`status` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1");

        $this->db->query("ALTER TABLE `tbl_client_menu` ADD PRIMARY KEY (`menu_id`)");

        $this->db->query("ALTER TABLE `tbl_client_menu` MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21");

        $this->db->query("INSERT INTO `tbl_client_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `time`, `sort`, `status`) VALUES
(6, 'tickets', 'client/tickets', 'fa fa-ticket', 0, '2017-06-12 14:34:52', 8, 0),
(8, 'users', 'client/user/user_list', 'fa fa-users', 0, '2017-04-19 20:18:59', 10, 0),
(9, 'settings', 'client/settings', 'fa fa-cogs', 0, '2017-04-19 20:19:03', 11, 0),
(17, 'dashboard', 'client/dashboard', 'icon-speedometer', 0, '2017-04-19 20:17:21', 1, 0),
(18, 'mailbox', 'client/mailbox', 'fa fa-envelope', 0, '2017-04-19 20:17:21', 2, 0),
(19, 'private_chat', 'client/message', 'fa fa-envelope', 0, '2017-04-19 20:19:25', 12, 0);");

        $this->db->query("CREATE TABLE `tbl_client_role` (`client_role_id` int(11) NOT NULL,`user_id` int(11) NOT NULL,`menu_id` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        $this->db->query("ALTER TABLE `tbl_client_role` ADD PRIMARY KEY (`client_role_id`)");
        $this->db->query("ALTER TABLE `tbl_client_role` MODIFY `client_role_id` int(11) NOT NULL AUTO_INCREMENT");

        $this->db->query("ALTER TABLE `tbl_user_role` ADD (view int(11) NULL DEFAULT 0,created int(11) NULL DEFAULT 0,edited int(11) NULL DEFAULT 0,deleted int(11) NULL DEFAULT 0)");

        $this->db->query("DROP TABLE tbl_menu");

        $this->db->query("CREATE TABLE `tbl_menu` (`menu_id` int(11) NOT NULL,`label` varchar(100) NOT NULL,`link` varchar(100) NOT NULL,`icon` varchar(100) NOT NULL,`parent` int(11) NOT NULL DEFAULT '0',`sort` int(11) NOT NULL DEFAULT '0',`time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,`status` tinyint(1) DEFAULT '1' COMMENT '1= active 0=inactive') ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        $this->db->query("INSERT INTO `tbl_menu` (`menu_id`, `label`, `link`, `icon`, `parent`, `sort`, `time`, `status`) VALUES
(1, 'dashboard', 'admin/dashboard', 'fa fa-dashboard', 0, 0, '2017-04-22 18:09:36', 1),
(2, 'calendar', 'admin/calendar', 'fa fa-calendar', 0, 1, '2017-04-23 12:27:23', 1),
(4, 'client', 'admin/client/manage_client', 'fa fa-users', 0, 2, '2017-06-11 17:48:21', 1),
(5, 'mailbox', 'admin/mailbox', 'fa fa-envelope-o', 0, 3, '2017-06-12 14:37:51', 1),
(6, 'tickets', 'admin/tickets', 'fa fa-ticket', 0, 5, '2017-06-12 14:37:51', 1),
(24, 'user', 'admin/user/user_list', 'fa fa-users', 0, 20, '2017-06-12 14:37:52', 1),
(25, 'settings', 'admin/settings', 'fa fa-cogs', 0, 21, '2017-06-12 14:37:52', 1),
(26, 'database_backup', 'admin/settings/database_backup', 'fa fa-database', 0, 22, '2017-06-12 14:37:52', 1),
(42, 'report', '#', 'fa fa-bar-chart', 0, 18, '2017-06-12 14:37:52', 1),
(54, 'tasks', 'admin/tasks/all_task', 'fa fa-tasks', 0, 7, '2017-06-12 14:37:51', 1),
(60, 'tasks_report', 'admin/report/tasks_report', 'fa fa-circle-o', 42, 0, '2017-06-11 17:49:48', 1),
(63, 'client_report', 'admin/report/client_report', 'fa fa-circle-o', 42, 2, '2017-06-11 17:49:48', 1),
(66, 'tasks_assignment', 'admin/report/tasks_assignment', 'fa fa-dot-circle-o', 59, 0, '2017-06-11 17:23:37', 0),
(70, 'departments', 'admin/departments', 'fa fa-user-secret', 0, 9, '2017-06-12 14:37:51', 1),
(71, 'holiday', 'admin/holiday', 'fa fa-calendar-plus-o', 73, 0, '2017-06-10 06:35:47', 1),
(72, 'leave_management', 'admin/leave_management', 'fa fa-plane', 0, 15, '2017-06-12 14:37:52', 1),
(73, 'utilities', '#', 'fa fa-gift', 0, 19, '2017-06-12 14:37:52', 1),
(74, 'overtime', 'admin/utilities/overtime', 'fa fa-clock-o', 89, 9, '2017-06-10 06:34:23', 1),
(75, 'stock', '#', 'fa fa-codepen', 0, 10, '2017-06-12 14:37:51', 1),
(76, 'stock_category', 'admin/stock/stock_category', 'fa fa-sliders', 75, 0, '2016-05-30 01:20:23', 1),
(77, 'manage_stock', '#', 'fa fa-archive', 75, 2, '2017-04-26 08:41:10', 1),
(78, 'assign_stock', '#', 'fa fa-align-left', 75, 3, '2017-04-26 08:41:10', 1),
(79, 'stock_report', 'admin/stock/report', 'fa fa-line-chart', 75, 4, '2017-04-25 11:18:25', 1),
(81, 'stock_list', 'admin/stock/stock_list', 'fa fa-stack-exchange', 75, 1, '2017-04-26 08:41:10', 1),
(82, 'assign_stock', 'admin/stock/assign_stock', 'fa fa-align-left', 78, 0, '2016-05-30 01:25:02', 1),
(83, 'assign_stock_report', 'admin/stock/assign_stock_report', 'fa fa-bar-chart', 78, 1, '2016-05-30 01:25:02', 1),
(84, 'stock_history', 'admin/stock/stock_history', 'fa fa-file-text-o', 77, 0, '2016-05-30 01:25:02', 1),
(85, 'performance', '#', 'fa fa-dribbble', 0, 14, '2017-06-12 14:37:52', 1),
(86, 'performance_indicator', 'admin/performance/performance_indicator', 'fa fa-random', 85, 0, '2016-05-30 01:20:23', 1),
(87, 'performance_report', 'admin/performance/performance_report', 'fa fa-calendar-o', 85, 2, '2016-05-30 01:20:23', 1),
(88, 'give_appraisal', 'admin/performance/give_performance_appraisal', 'fa fa-plus', 85, 1, '2016-05-30 01:20:23', 1),
(89, 'payroll', '#', 'fa fa-usd', 0, 13, '2017-06-12 14:37:52', 1),
(90, 'manage_salary_details', 'admin/payroll/manage_salary_details', 'fa fa-usd', 89, 2, '2017-04-23 00:36:37', 1),
(91, 'employee_salary_list', 'admin/payroll/employee_salary_list', 'fa fa-user-secret', 89, 3, '2017-04-23 00:36:37', 1),
(92, 'make_payment', 'admin/payroll/make_payment', 'fa fa-tasks', 89, 4, '2017-04-23 00:36:37', 1),
(93, 'generate_payslip', 'admin/payroll/generate_payslip', 'fa fa-list-ul', 89, 5, '2017-04-23 00:36:37', 1),
(94, 'salary_template', 'admin/payroll/salary_template', 'fa fa-money', 89, 0, '2017-04-23 00:36:37', 1),
(95, 'hourly_rate', 'admin/payroll/hourly_rate', 'fa fa-clock-o', 89, 1, '2017-04-23 00:36:37', 1),
(96, 'payroll_summary', 'admin/payroll/payroll_summary', 'fa fa-camera-retro', 89, 6, '2017-04-23 00:36:37', 1),
(97, 'provident_fund', 'admin/payroll/provident_fund', 'fa fa-briefcase', 89, 8, '2017-06-10 06:34:23', 1),
(98, 'advance_salary', 'admin/payroll/advance_salary', 'fa fa-cc-mastercard', 89, 7, '2017-06-10 06:34:23', 1),
(99, 'employee_award', 'admin/award', 'fa fa-trophy', 89, 10, '2017-06-10 06:35:47', 1),
(100, 'announcements', 'admin/announcements', 'fa fa-bullhorn icon', 0, 17, '2017-06-12 14:37:52', 1),
(101, 'training', 'admin/training', 'fa fa-suitcase', 0, 16, '2017-06-12 14:37:52', 1),
(102, 'job_circular', '#', 'fa fa-globe', 0, 12, '2017-06-12 14:37:52', 1),
(103, 'jobs_posted', 'admin/job_circular/jobs_posted', 'fa fa-ticket', 102, 0, '2016-05-30 01:20:21', 1),
(104, 'jobs_applications', 'admin/job_circular/jobs_applications', 'fa fa-compass', 102, 1, '2016-05-30 01:20:21', 1),
(105, 'attendance', '#', 'fa fa-file-text', 0, 11, '2017-06-12 14:37:52', 1),
(106, 'timechange_request', 'admin/attendance/timechange_request', 'fa fa-calendar-o', 105, 1, '2016-05-30 01:20:21', 1),
(107, 'attendance_report', 'admin/attendance/attendance_report', 'fa fa-file-text', 105, 2, '2016-05-30 01:20:21', 1),
(108, 'time_history', 'admin/attendance/time_history', 'fa fa-clock-o', 105, 0, '2016-05-30 01:20:21', 1),
(109, 'pull-down', '', '', 0, 0, '2017-06-11 17:45:53', 0),
(111, 'company_details', 'admin/settings', 'fa fa-fw fa-info-circle', 25, 1, '2017-04-25 09:38:46', 2),
(112, 'system_settings', 'admin/settings/system', 'fa fa-fw fa-desktop', 25, 2, '2017-04-25 09:38:46', 2),
(113, 'email_settings', 'admin/settings/email', 'fa fa-fw fa-envelope', 25, 3, '2017-04-25 09:38:46', 2),
(114, 'email_templates', 'admin/settings/templates', 'fa fa-fw fa-pencil-square', 25, 4, '2017-04-25 09:38:46', 2),
(115, 'email_integration', 'admin/settings/email_integration', 'fa fa-fw fa-envelope-o', 25, 5, '2017-04-25 09:38:46', 2),
(119, 'tickets_leads_settings', 'admin/settings/tickets', 'fa fa-fw fa-ticket', 25, 0, '2017-04-25 09:38:46', 2),
(120, 'theme_settings', 'admin/settings/theme', 'fa fa-fw fa-code', 25, 0, '2017-04-25 09:38:46', 2),
(121, 'working_days', 'admin/settings/working_days', 'fa fa-fw fa-calendar', 25, 0, '2017-04-25 09:43:41', 2),
(122, 'leave_category', 'admin/settings/leave_category', 'fa fa-fw fa-pagelines', 25, 0, '2017-04-25 09:43:41', 2),
(125, 'customer_group', 'admin/settings/customer_group', 'fa fa-fw fa-users', 25, 0, '2017-04-25 09:43:41', 2),
(130, 'custom_field', 'admin/settings/custom_field', 'fa fa-fw fa-star-o', 25, 0, '2017-04-25 09:43:41', 2),
(131, 'payment_method', 'admin/settings/payment_method', 'fa fa-fw fa-money', 25, 0, '2017-04-25 09:43:41', 2),
(132, 'cronjob', 'admin/settings/cronjob', 'fa fa-fw fa-contao', 25, 0, '2017-04-25 09:46:20', 2),
(133, 'menu_allocation', 'admin/settings/menu_allocation', 'fa fa-fw fa fa-compass', 25, 0, '2017-04-25 09:46:20', 2),
(134, 'notification', 'admin/settings/notification', 'fa fa-fw fa-bell-o', 25, 0, '2017-04-25 09:46:20', 2),
(135, 'email_notification', 'admin/settings/email_notification', 'fa fa-fw fa-bell-o', 25, 0, '2017-04-25 09:46:20', 2),
(136, 'database_backup', 'admin/settings/database_backup', 'fa fa-fw fa-database', 25, 0, '2017-04-25 09:46:20', 2),
(138, 'system_update', 'admin/settings/system_update', 'fa fa-fw fa-pencil-square-o', 25, 0, '2017-04-25 09:46:20', 2),
(139, 'private_chat', 'admin/message', 'fa fa-envelope', 0, 23, '2017-06-12 14:37:52', 1);");

        $this->db->query("ALTER TABLE `tbl_menu` ADD PRIMARY KEY (`menu_id`)");
        $this->db->query("ALTER TABLE `tbl_menu` MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140");

        $this->db->query("ALTER TABLE `tbl_clock` ADD (ip_address text NULL)");

        $this->db->query("ALTER TABLE `tbl_job_circular` CHANGE `employment_type` `employment_type` ENUM('contractual','full_time','part_time') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'full_time';");
        $this->db->query("ALTER TABLE `tbl_job_circular` ADD (experience varchar(100) NULL,age varchar(100) NULL,salary_range varchar(100) NULL)");
        $this->db->query("ALTER TABLE `tbl_account_details` ADD (passport varchar(100) NULL)");
        $this->db->query("ALTER TABLE `tbl_attendance` ADD (clocking_status tinyint(1) NULL DEFAULT 0)");

    }
}