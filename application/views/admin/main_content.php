<link href="<?php echo base_url() ?>asset/css/fullcalendar.css" rel="stylesheet" type="text/css">
<style type="text/css">
    .datepicker {
        z-index: 1151 !important;
    }

    .mt-sm {
        font-size: 14px;
    }
</style>
<?php
echo message_box('success');
echo message_box('error');
$curency = $this->admin_model->check_by(array('code' => config_item('default_currency')), 'tbl_currencies');

$all_tickets = $this->admin_model->get_permission('tbl_tickets');
$today_tickets = 0;
$open_tickets = 0;
if (!empty($all_tickets)) {
    foreach ($all_tickets as $tickets) {
        $tickets_time = date('Y-m-d', strtotime($tickets->created));
        if ($tickets_time == date('Y-m-d')) {
            $today_tickets += count($tickets);
        }
        if ($tickets->status == 'open') {
            $open_tickets += count($tickets);
        }

    }
}

// tasks
$all_task = $this->admin_model->get_permission('tbl_task');
$task_today = 0;
if (!empty($all_task)):
    foreach ($all_task as $task):
        $task_time = date('Y-m-d', strtotime($task->task_created_date));
        if ($task_time == date('Y-m-d')) {
            $task_today += count($task->task_id);
        }
    endforeach;
endif;

?>
<div class="dashboard row">

    <div class="">
        <!--        ******** transactions ************** -->
        <?php if ($this->session->userdata('user_type') == 1) { ?>
            <div class="col-sm-4">
                <div class="panel widget">
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 bb br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-plane fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?= $today_leave ?></h4>
                                        <p class="mb0 text-muted"><?= lang('leave') . ' ' . lang('today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/leave_management"
                                                  class="mt0 mb0"><?= lang('more_info') ?><i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 bb">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-ticket fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?= $today_tickets ?></h4>
                                        <p class="mb0 text-muted"><?= lang('tickets') . ' ' . lang('today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tickets"
                                                  class=" small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-info">
                                    <em class="fa fa-file-text fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?= count($this->db->where(array('date_in' => date('Y-m-d'), 'attendance_status' => '1'))->get('tbl_attendance')->result()) ?></h4>
                                        <p class="mb0 text-muted"><?= lang('present') . ' ' . lang('today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-folder-open-o fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?= count($this->db->where(array('date_in' => date('Y-m-d'), 'attendance_status' => '0'))->get('tbl_attendance')->result()) ?></h4>
                                        <p class="mb0 text-muted"><?= lang('absent') . ' ' . lang('today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                  class="small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--        ******** Sales ************** -->
            <div class="col-sm-4">
                <div class="panel widget">
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 bb br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center ">
                                    <em class="icon-user fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            if (!empty($total_absent)) {
                                                echo $total_absent;
                                            } else {
                                                echo '0';
                                            }
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('absent') ?></p>
                                        <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-6 bb">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-purple">
                                    <em class="icon-user fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            if (!empty($total_attendance)) {
                                                echo $total_attendance;
                                            } else {
                                                echo '0';
                                            }
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('present') ?></p>
                                        <small><a href="<?= base_url() ?>admin/attendance/attendance_report"
                                                  class=" small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-purple">
                                    <em class="fa fa-plane fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            if (!empty($total_leave)) {
                                                echo $total_leave;
                                            } else {
                                                echo '0';
                                            }
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('monthly') . ' ' . lang('leave') ?></p>
                                        <small><a href="<?= base_url() ?>admin/leave_management"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-trophy fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?= count($this->db->get('tbl_employee_award')->result()) ?></h4>
                                        <p class="mb0 text-muted"><?= lang('total') . ' ' . lang('award') ?></p>
                                        <small><a href="<?= base_url() ?>admin/award"
                                                  class="small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--        ******** Ticket ************** -->
            <div class="col-sm-4">
                <div class="panel widget">
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 bb br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-tasks fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            echo count($this->db->where('task_status', 'in_progress')->get('tbl_task')->result());
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('in_progress') . ' ' . lang('task') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tasks/all_task"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6 bb">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-info">
                                    <em class="fa fa-tasks fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?= $task_today ?></h4>
                                        <p class="mb0 text-muted"><?= lang('task') . ' ' . lang('today') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tasks/all_task" class="mt0 mb0">More
                                                info <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="row row-table row-flush">
                        <div class="col-xs-6 br">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-bug fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"><?php
                                            echo count($this->db->where('status', 'in_progress')->get('tbl_tickets')->result());
                                            ?></h4>
                                        <p class="mb0 text-muted"><?= lang('in_progress') . ' ' . lang('tickets') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tickets/in_progress"
                                                  class="mt0 mb0"><?= lang('more_info') ?> <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="row row-table row-flush">
                                <div class="col-xs-2 text-center text-danger">
                                    <em class="fa fa-ticket fa-2x"></em>
                                </div>
                                <div class="col-xs-10">
                                    <div class="text-center">
                                        <h4 class="mt-sm mb0"> <?= count($this->db->where('status', 'open')->get('tbl_tickets')->result()); ?></h4>
                                        <p class="mb0 text-muted"><?= lang('open') . ' ' . lang('tickets') ?></p>
                                        <small><a href="<?= base_url() ?>admin/tickets/open"
                                                  class=" small-box-footer"><?= lang('more_info') ?>
                                                <i
                                                    class="fa fa-arrow-circle-right"></i></a></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="clearfix visible-sm-block "></div>
        <?php
        // tasks
        $task_all_info = $this->admin_model->get_permission('tbl_task');

        $task_overdue = 0;

        if (!empty($task_all_info)):
            foreach ($task_all_info as $v_task_info):
                $due_date = $v_task_info->due_date;
                $due_time = strtotime($due_date);
                $current_time = time();
                if ($current_time > $due_time && $v_task_info->task_progress < 100) {
                    $task_overdue += count($v_task_info->task_id);
                }
            endforeach;
        endif;

        $m = date("m"); // Month value
        $y = date("Y"); // Year value
        $num = cal_days_in_month(CAL_GREGORIAN, $m, $y);
        $employee = $this->db->where('company', '-')->get('tbl_account_details')->result();

        for ($i = 0; $i < count($employee); $i++) {
            if (!empty($employee[$i]->date_of_birth)) {
                $mem_bod_explode = explode("-", $employee[$i]->date_of_birth);

                $m_bday = mktime(0, 0, 0, $mem_bod_explode[1], $mem_bod_explode[2], $y);

                $start_date = date('Y-m', $m_bday) . '-01';

                $end_date = date('Y-m', $m_bday) . '-' . $num;


                if (date('Y-m-d') == date('Y-m-d', $m_bday)) {
                    $present_bday[] = $employee[$i];
                    $date = date('Y-m-d', $m_bday);
                    $pdate[] = date('d M Y', strtotime($date));
                } else if (date('Y-m-d') > $start_date && date('Y-m-d') <= $end_date) {
                    $future_bday[] = $employee[$i];
                    $date = date('Y-m-d', $m_bday);
                    $fdate[] = date('d M Y', strtotime($date));
                }

                $last_date = date('Y-m-d', $m_bday);
                $current_time = date('Y-m-d');
                if ($current_time > $last_date) {
                    $ribon = 'danger';
                    $today = date('Y-m-d');
                    $datetime1 = new DateTime($last_date);
                    $datetime2 = new DateTime($today);
                    $interval = $datetime1->diff($datetime2);
                    $text = $interval->days . ' ' . lang('days') . ' ' . lang('ago');
                } elseif ($current_time == $last_date) {
                    $ribon = 'info';
                    $text = lang('today');
                } else {
                    $today = date('Y-m-d');
                    $datetime1 = new DateTime($today);
                    $datetime2 = new DateTime($last_date);
                    $interval = $datetime1->diff($datetime2);

                    $ribon = 'success';
                    $text = $interval->days . lang('days') . ' ' . lang('left');
                }
            }
            $designation = $this->db->where('designations_id', $employee[$i]->designations_id)->get('tbl_designations')->row();
            if (!empty($designation)) {
                $department = $this->db->where('departments_id', $designation->departments_id)->get('tbl_departments')->row();
            }

        }

        $pending_leave = 0;
        $all_leave_application = $this->db->get('tbl_leave_application')->result();
        if (!empty($all_leave_application)) {
            foreach ($all_leave_application as $v_all_leave) {
                if ($v_all_leave->application_status == '1') {
                    $pending_leave += count($v_all_leave);
                }
            }
        }

        ?>
        <div class="col-md-12 mt-lg">
            <section class="panel panel-custom">
                <aside class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class=""><a href="#birthday"
                                        data-toggle="tab"><?= lang('birthday') . '-' . date("F") ?>
                                <strong class="pull-right ">(<?php
                                    if (!empty($present_bday)) {
                                        $total_bday[] = count($present_bday);
                                    }
                                    if (!empty($future_bday)) {
                                        $total_bday[] = count($future_bday);
                                    }
                                    if (!empty($total_bday)) {
                                        echo count($total_bday);
                                    }
                                    ?>)</strong>
                            </a></li>
                        <li class=""><a href="#tasks" data-toggle="tab"><?= lang('overdue') . ' ' . lang('tasks') ?>
                                <strong class="pull-right ">(<?= $task_overdue ?>)</strong>
                            </a></li>
                        <li class=""><a href="#pending_leave"
                                        data-toggle="tab"><?= lang('pending') . ' ' . lang('leave') ?>
                                <strong class="pull-right ">(<?= $pending_leave ?>)</strong>
                            </a></li>
                        <li class=""><a href="#timechange_request"
                                        data-toggle="tab"><?= lang('timechange_request') ?>
                                <strong class="pull-right ">(<?= 0 ?>)</strong>
                            </a></li>

                        <li class=""><a href="#recent_applications"
                                        data-toggle="tab"><?= lang('recent') . ' ' . lang('applications') ?>
                            </a></li>
                        <li class=""><a href="#open_tickets"
                                        data-toggle="tab"><?= lang('open') . ' ' . lang('tickets') ?>
                                <strong class="pull-right ">(<?= $open_tickets ?>)</strong>
                            </a></li>
                    </ul>
                    <section class="scrollable">
                        <div class="tab-content">
                            <div class="tab-pane " id="birthday">
                                <div class="table-responsive">
                                    <table id="table-ext-1" class="table table-striped m-b-none text-sm">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><?= lang('photo') ?></th>
                                            <th><?= lang('fullname') ?></th>
                                            <th><?= lang('designation') ?></th>
                                            <th><?= lang('birthday') ?></th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (!empty($present_bday)):foreach ($present_bday as $key => $v_bday): ?>

                                            <tr>

                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_bday->user_id ?>"><?= $v_bday->employment_id ?>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_bday->user_id ?>">
                                                        <div class="media">
                                                            <img src="<?php echo base_url() . $v_bday->avatar ?>"
                                                                 alt="Image" class="img-responsive img-circle">
                                                        </div>
                                                    </a>
                                                </td>

                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_bday->user_id ?>">
                                                        <?php echo $v_bday->fullname ?>
                                                        </span>
                                                        <div
                                                            class="pull-right label label-<?= $ribon ?>"><?= $text ?></div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo "$department->deptname" . ' &rArr; ' . $designation->designations;
                                                    if (!empty($department->department_head_id) && $department->department_head_id == $v_bday->user_id) { ?>
                                                        <strong
                                                            class="label label-warning"><?= lang('department_head') ?></strong>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td><?php
                                                    echo $pdate[$key];
                                                    ?></td>
                                                </a>

                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>

                                        <?php if (!empty($future_bday)):foreach ($future_bday as $key => $v_fbday): ?>
                                            <tr>

                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_fbday->user_id ?>"><?= $v_fbday->employment_id ?></a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_fbday->user_id ?>">
                                                        <div class="media">
                                                            <img src="<?php echo base_url() . $v_fbday->avatar ?>"
                                                                 alt="Image" class="img-responsive img-circle">
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url() ?>admin/user/user_details/<?= $v_fbday->user_id ?>"><?php echo $v_fbday->fullname ?>
                                                        <div
                                                            class="pull-right label label-<?= $ribon ?>"><?= $text ?></div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <?php echo "$department->deptname" . ' &rArr; ' . $designation->designations;
                                                    if (!empty($department->department_head_id) && $department->department_head_id == $v_fbday->user_id) { ?>
                                                        <strong
                                                            class="label label-warning"><?= lang('department_head') ?></strong>
                                                    <?php }
                                                    ?>
                                                </td>
                                                <td>

                                                    <?php
                                                    echo $fdate[$key];
                                                    ?></td>
                                                </a>

                                            </tr>
                                        <?php endforeach; ?>
                                        <?php endif; ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tasks">
                                <table class="table table-striped m-b-none text-sm" id="DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('task_name') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                        <th><?= lang('progress') ?></th>
                                        <th class="col-options no-sort col-md-1"><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($task_all_info)):foreach ($task_all_info as $v_task):
                                        $due_date = $v_task->due_date;
                                        $due_time = strtotime($due_date);
                                        $current_time = time();
                                        if ($current_time > $due_time && $v_task->task_progress < 100) {
                                            ?>
                                            <tr>
                                                <td><a class="text-info" style="<?php
                                                    if ($v_task->task_progress >= 100) {
                                                        echo 'text-decoration: line-through;';
                                                    }
                                                    ?>"
                                                       href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                </td>
                                                <td>
                                                    <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                                    <?php if ($current_time > $due_time && $v_task->task_progress < 100) { ?>
                                                        <span
                                                            class="label label-danger"><?= lang('overdue') ?></span>
                                                    <?php } ?></td>
                                                <td>
                                                    <div class="inline ">
                                                        <div class="easypiechart text-success" style="margin: 0px;"
                                                             data-percent="<?= $v_task->task_progress ?>"
                                                             data-line-width="5" data-track-Color="#f0f0f0"
                                                             data-bar-color="#<?php
                                                             if ($v_task->task_progress == 100) {
                                                                 echo '8ec165';
                                                             } else {
                                                                 echo 'fb6b5b';
                                                             }
                                                             ?>" data-rotate="270" data-scale-Color="false"
                                                             data-size="50"
                                                             data-animate="2000">
                                                        <span class="small text-muted"><?= $v_task->task_progress ?>
                                                            %</span>
                                                        </div>
                                                    </div>

                                                </td>

                                                <td><?= btn_view('admin/tasks/view_task_details/' . $v_task->task_id) ?></td>
                                            </tr>
                                            <?php
                                        }
                                    endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="pending_leave">
                                <table class="table table-striped m-b-none text-sm" id="DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('start_date') ?></th>
                                        <th><?= lang('end_date') ?></th>
                                        <th><?= lang('leave_category') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    <?php
                                    $all_leave_application = $this->db->get('tbl_leave_application')->result();
                                    if (!empty($all_leave_application)) {
                                        foreach ($all_leave_application as $v_all_leave):
                                            if ($v_all_leave->application_status == '1') {
                                                $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                                $my_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                                ?>
                                                <tr>
                                                    <td><?= $my_profile->fullname ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_start_date)) ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_end_date)) ?></td>
                                                    <td><?= $my_leave_category->leave_category ?></td>
                                                </tr>
                                                <?php
                                            }
                                        endforeach;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="timechange_request">
                                <table class="table table-striped DataTables" id="">
                                    <thead>
                                    <tr>
                                        <th><?= lang('emp_id') ?></th>
                                        <th><?= lang('name') ?></th>
                                        <th><?= lang('time_in') ?></th>
                                        <th><?= lang('time_out') ?></th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $all_clock_history = $this->admin_model->get_all_clock_history();
                                    if (!empty($all_clock_history)):foreach ($all_clock_history as $key => $v_clock_history):
                                        ?>
                                        <tr>
                                            <td><?php echo $v_clock_history->employment_id; ?></td>
                                            <td><?php echo $v_clock_history->fullname; ?></td>
                                            <td><?php
                                                if ($v_clock_history->clockin_edit != "00:00:00") {
                                                    echo date('h:i A', strtotime($v_clock_history->clockin_edit));
                                                }
                                                ?></td>
                                            <td><?php
                                                if ($v_clock_history->clockout_edit != "00:00:00") {
                                                    echo date('h:i A', strtotime($v_clock_history->clockout_edit));
                                                }
                                                ?></td>
                                            <td>
                                                <a href="<?= base_url() ?>admin/attendance/view_timerequest/<?= $v_clock_history->clock_history_id ?>"
                                                   class="btn btn-primary btn-xs"
                                                   title="<?= lang('view') ?>" data-toggle="modal" data-placement="top"
                                                   data-target="#myModal"><span
                                                        class="fa fa-list-alt"></span></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="open_tickets">
                                <div class="table-responsive">
                                    <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                           width="100%">
                                        <thead>
                                        <tr>

                                            <th><?= lang('ticket_code') ?></th>
                                            <th><?= lang('subject') ?></th>
                                            <?php if ($this->session->userdata('user_type') == '1') { ?>
                                                <th><?= lang('reporter') ?></th>
                                            <?php } ?>
                                            <th><?= lang('status') ?></th>
                                            <th><?= lang('action') ?></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

                                        if (!empty($all_tickets)) {
                                            foreach ($all_tickets as $v_tickets_info) {
                                                $can_edit = $this->admin_model->can_action('tbl_tickets', 'edit', array('tickets_id' => $v_tickets_info->tickets_id));
                                                $can_delete = $this->admin_model->can_action('tbl_tickets', 'delete', array('tickets_id' => $v_tickets_info->tickets_id));
                                                if ($v_tickets_info->status == 'open') {
                                                    $s_label = 'danger';
                                                }
                                                if ($v_tickets_info->status == 'open') {
                                                    $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                                    ?>
                                                    <tr>

                                                        <td><span
                                                                class="label label-success"><?= $v_tickets_info->ticket_code ?></span>
                                                        </td>
                                                        <td><a class="text-info"
                                                               href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                        </td>
                                                        <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                            <td>
                                                                <?php if (!empty($profile_info)) { ?>
                                                                    <a href="#" class="pull-left recect_task  ">
                                                                        <img style="width: 30px;margin-left: 18px;
                                                         height: 29px;
                                                         border: 1px solid #aaa;"
                                                                             src="<?= base_url() . $profile_info->avatar ?>"
                                                                             class="img-circle">

                                                                        <?=
                                                                        ($profile_info->fullname)
                                                                        ?>
                                                                    </a>
                                                                <?php } else {
                                                                    echo '-';
                                                                } ?>
                                                            </td>

                                                        <?php } ?>
                                                        <td>
                                                            <?php if (!empty($can_edit)) { ?>
                                                                <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                                            <?php }
                                                            if (!empty($can_delete)) { ?>
                                                                <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                                            <?php } ?>
                                                            <?= btn_view('admin/tickets/index/tickets_details/' . $v_tickets_info->tickets_id) ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="recent_applications">
                                <div class="table-responsive">
                                    <table class="table table-striped DataTables " id="">
                                        <thead>
                                        <tr>
                                            <th><?= lang('name') ?></th>
                                            <th><?= lang('start_date') ?></th>
                                            <th><?= lang('end_date') ?></th>
                                            <th><?= lang('leave_category') ?></th>
                                            <th><?= lang('status') ?></th>
                                            <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                <th class="col-sm-2"><?= lang('action') ?></th>
                                            <?php } ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $all_leave_application = $this->db->limit(5)->get('tbl_leave_application')->result();

                                        if (!empty($all_leave_application)) {
                                            foreach ($all_leave_application as $v_all_leave):
                                                $my_profile = $this->db->where('user_id', $v_all_leave->user_id)->get('tbl_account_details')->row();
                                                $my_leave_category = $this->db->where('leave_category_id', $v_all_leave->leave_category_id)->get('tbl_leave_category')->row();
                                                ?>
                                                <tr>
                                                    <td><?= $my_profile->fullname ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_start_date)) ?></td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_all_leave->leave_end_date)) ?></td>
                                                    <td><?= $my_leave_category->leave_category ?></td>
                                                    <td><?php
                                                        if ($v_all_leave->application_status == '1') {
                                                            echo '<span class="label label-warning">' . lang('pending') . '</span>';
                                                        } elseif ($v_all_leave->application_status == '2') {
                                                            echo '<span class="label label-success">' . lang('accepted') . '</span>';
                                                        } else {
                                                            echo '<span class="label label-danger">' . lang('rejected') . '</span>';
                                                        }
                                                        ?></td>
                                                    <?php if ($this->session->userdata('user_type') == 1) { ?>
                                                        <td>
                                                            <?php echo btn_view('admin/leave_management/index/view_details/' . $v_all_leave->leave_application_id) ?>
                                                            <?php if ($v_all_leave->application_status != '2') { ?>
                                                                <?php echo btn_delete('admin/leave_management/delete_application/' . $v_all_leave->leave_application_id) ?>
                                                            <?php } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                                <?php
                                            endforeach;
                                        }
                                        ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </aside>
            </section>
        </div>
        <?php
        $my_task = $this->admin_model->my_permission('tbl_task', $this->session->userdata('user_id'));
        ?>
        <?php include_once 'assets/admin-ajax.php'; ?>
        <div class="col-md-6" style="margin-top: 20px;">

            <div class="panel panel-custom" style="height: 437px;overflow-y: scroll;">
                <header class="panel-heading mb0">
                    <h3 class="panel-title"><?= lang('my_tasks') ?></h3>
                </header>
                <div class="">
                    <table class="table table-striped m-b-none text-sm">
                        <thead>
                        <tr>
                            <th data-check-all>

                            </th>
                            <th><?= lang('task_name') ?></th>
                            <th><?= lang('end_date') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if (!empty($my_task)):foreach ($my_task as $v_my_task):


                            if ($v_my_task->task_status == 'not_started' || $v_my_task->task_status == 'in_progress' || $v_my_task->task_progress < 100) {
                                $due_date = $v_my_task->due_date;
                                $due_time = strtotime($due_date);
                                $current_time = time();
                                ?>
                                <tr>
                                    <td class="col-sm-1">
                                        <div class="complete checkbox c-checkbox">
                                            <label>
                                                <input type="checkbox" data-id="<?= $v_my_task->task_id ?>"
                                                       style="position: absolute;" <?php
                                                if ($v_my_task->task_progress >= 100) {
                                                    echo 'checked';
                                                }
                                                ?>>
                                                <span class="fa fa-check"></span>
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <a class="text-info"
                                           href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_my_task->task_id ?>">
                                            <?php echo $v_my_task->task_name; ?></a>
                                        <?php if ($current_time > $due_time && $v_my_task->task_progress < 100) { ?>
                                            <span
                                                class="label label-danger pull-right"><?= lang('overdue') ?></span>
                                        <?php } ?>

                                        <div class="progress progress-xs progress-striped active">
                                            <div
                                                class="progress-bar progress-bar-<?php echo ($v_my_task->task_progress >= 100) ? 'success' : 'primary'; ?>"
                                                data-toggle="tooltip"
                                                data-original-title="<?= $v_my_task->task_progress ?>%"
                                                style="width: <?= $v_my_task->task_progress; ?>%"></div>
                                        </div>

                                    </td>

                                    <td>
                                        <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                    </td>


                                </tr>
                                <?php
                            }
                        endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div><!-- ./box-body -->

            </div>
        </div>
        <div class="col-md-6" style="margin-top: 20px;">

            <div class="panel panel-custom" style="height: 437px;overflow-y: scroll;">
                <header class="panel-heading mb0">
                    <h3 class="panel-title"><?= lang('announcements') ?></h3>
                </header>

                <?php
                $all_announcements = $this->db->get('tbl_announcements')->result();
                if (!empty($all_announcements)):foreach ($all_announcements as $v_announcements):

                    ?>
                    <div class="notice-calendar-list panel-body">
                        <div class="notice-calendar">
                                    <span
                                        class="month"><?php echo date('M', strtotime($v_announcements->created_date)) ?></span>
                                    <span
                                        class="date"><?php echo date('d', strtotime($v_announcements->created_date)) ?></span>
                        </div>

                        <div class="notice-calendar-heading">
                            <h5 class="notice-calendar-heading-title">
                                <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
                                   title="View" data-toggle="modal"
                                   data-target="#myModal_lg"><?php echo $v_announcements->title ?></a>
                            </h5>
                            <div class="notice-calendar-date">
                                <?php
                                $str = strlen($v_announcements->description);
                                if ($str > 90) {
                                    $ss = '<strong> ......</strong>';
                                } else {
                                    $ss = '&nbsp';
                                }
                                echo substr($v_announcements->description, 0, 90) . $ss;
                                ?>
                            </div>
                        </div>
                        <div style="margin-top: 5px; padding-top: 5px; padding-bottom: 10px;">
                                        <span style="font-size: 10px;" class="pull-right">
                                            <strong>
                                                <a href="<?php echo base_url() ?>admin/announcements/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
                                                   title="View" data-toggle="modal"
                                                   data-target="#myModal_lg"><?= lang('view_details') ?></a></strong>
                                        </span>
                        </div>
                    </div>
                    <?php

                endforeach; ?>
                <?php endif; ?>

            </div><!-- ./box-body -->

        </div>
    </div>

    <div class="col-sm-6 mt-lg">

        <div class="panel panel-custom">
            <header class="panel-heading">
                <h3 class="panel-title"><?= lang('recent_activities') ?></h3>
            </header>
            <div class="panel-body">
                <section class="comment-list block">
                    <section class="slim-scroll" style="height:400px;overflow-x: scroll">
                        <?php
                        $activities = $this->db
                            ->order_by('activity_date', 'desc')
                            ->get('tbl_activities', 10)
                            ->result();
                        if (!empty($activities)) {
                            foreach ($activities as $v_activities) {
                                $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();
                                ?>
                                <article id="comment-id-1" class="comment-item" style="font-size: 11px;">
                                    <div class="pull-left recect_task  ">
                                        <a class="pull-left recect_task  ">
                                            <?php if (!empty($profile_info)) {
                                                ?>
                                                <img style="width: 30px;margin-left: 18px;
                                                             height: 29px;
                                                             border: 1px solid #aaa;"
                                                     src="<?= base_url() . $profile_info->avatar ?>"
                                                     class="img-circle">
                                            <?php } ?>
                                        </a>
                                    </div>
                                    <section class="comment-body m-b-lg">
                                        <header class=" ">
                                            <strong>
                                                <?= $profile_info->fullname ?></strong>
                                                    <span class="text-muted text-xs"> <?php
                                                        $today = time();
                                                        $activity_day = strtotime($v_activities->activity_date);
                                                        echo $this->admin_model->get_time_different($today, $activity_day);
                                                        ?> <?= lang('ago') ?>
                                                    </span>
                                        </header>
                                        <div>
                                            <?= lang($v_activities->activity) ?>
                                            <strong> <?= $v_activities->value1 . ' ' . $v_activities->value2 ?></strong>
                                        </div>
                                        <hr/>
                                    </section>
                                </article>


                                <?php
                            }
                        }
                        ?>
                    </section>
            </div>
        </div>
    </div>
    <div class="col-sm-6 mt-lg">
        <div class="panel panel-custom ">
            <div class="panel-heading">
                <h3 class="panel-title"><?= lang('recent_mail') ?>
                    <span class="pull-right text-white">
                            <a href="<?php echo base_url() ?>client/mailbox" class=" view-all-front">View All</a></span>
                </h3>
            </div>
            <div class="panel-body slim-scroll">
                <form method="post" action="<?php echo base_url() ?>client/mailbox/delete_mail/inbox">
                    <!-- Main content -->
                    <section class="content">
                        <div class="box box-primary">
                            <div class="box-body no-padding">
                                <div class="mailbox-controls">

                                    <!-- Check all button -->
                                    <div class="mail_checkbox">
                                        <input type="checkbox" id="parent_present">
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i>
                                        </button>
                                    </div><!-- /.btn-group -->
                                    <a href="#" onClick="history.go(0)" class="btn btn-default btn-sm"><i
                                            class="fa fa-refresh"></i></a>
                                    <a href="<?php echo base_url() ?>client/mailbox/index/compose"
                                       class="btn btn-danger">Compose +</a>
                                </div>
                                <br/>
                                <div class="table-responsive mailbox-messages slim-scroll">
                                    <table class="table table-hover table-striped">
                                        <tbody>

                                        <?php
                                        $get_inbox_message = $this->db
                                            ->where(array('deleted' => 'no', 'to' => $this->session->userdata('email')))
                                            ->order_by('message_time', 'desc')
                                            ->get('tbl_inbox', 10)
                                            ->result();
                                        if (!empty($get_inbox_message)):foreach ($get_inbox_message as $v_inbox_msg):
                                            ?>
                                            <tr>
                                                <td><input class="child_present" type="checkbox"
                                                           name="selected_id[]"
                                                           value="<?php echo $v_inbox_msg->inbox_id; ?>"/></td>

                                                <td class="mailbox-star">
                                                    <?php if ($v_inbox_msg->favourites == 1) { ?>
                                                        <a href="<?php echo base_url() ?>client/mailbox/index/added_favourites/<?php echo $v_inbox_msg->inbox_id ?>/0"><i
                                                                class="fa fa-star text-yellow"></i></a>
                                                    <?php } else { ?>
                                                        <a href="<?php echo base_url() ?>client/mailbox/index/added_favourites/<?php echo $v_inbox_msg->inbox_id ?>/1"><i
                                                                class="fa fa-star-o text-yellow"></i></a>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name"><a
                                                        href="<?php echo base_url() ?>client/mailbox/index/read_inbox_mail/<?php echo $v_inbox_msg->inbox_id ?>"><?php
                                                        $string = (strlen($v_inbox_msg->to) > 13) ? substr($v_inbox_msg->to, 0, 10) . '...' : $v_inbox_msg->to;
                                                        if ($v_inbox_msg->view_status == 1) {
                                                            echo '<span style="color:#000">' . $string . '</span>';
                                                        } else {
                                                            echo '<b style="color:#000;font-size:13px;">' . $string . '</b>';
                                                        }
                                                        ?></a></td>
                                                <td class="mailbox-subject" style="font-size:13px"><b
                                                        class="pull-left"><?php
                                                        $subject = (strlen($v_inbox_msg->subject) > 20) ? substr($v_inbox_msg->subject, 0, 15) . '...' : $v_inbox_msg->subject;
                                                        echo $subject;
                                                        ?> - &nbsp;</b> <span class="pull-left "> <?php
                                                        $body = (strlen($v_inbox_msg->message_body) > 40) ? substr($v_inbox_msg->message_body, 0, 40) . '...' : $v_inbox_msg->message_body;
                                                        echo $body;
                                                        ?></span></td>
                                                <td style="font-size:13px">
                                                    <?php
                                                    //$oldTime = date('h:i:s', strtotime($v_inbox_msg->send_time));
                                                    // Past time as MySQL DATETIME value
                                                    $oldtime = date('Y-m-d H:i:s', strtotime($v_inbox_msg->message_time));

                                                    // Current time as MySQL DATETIME value
                                                    $csqltime = date('Y-m-d H:i:s');
                                                    // Current time as Unix timestamp
                                                    $ptime = strtotime($oldtime);
                                                    $ctime = strtotime($csqltime);

                                                    //Now calc the difference between the two
                                                    $timeDiff = floor(abs($ctime - $ptime) / 60);

                                                    //Now we need find out whether or not the time difference needs to be in
                                                    //minutes, hours, or days
                                                    if ($timeDiff < 2) {
                                                        $timeDiff = "Just now";
                                                    } elseif ($timeDiff > 2 && $timeDiff < 60) {
                                                        $timeDiff = floor(abs($timeDiff)) . " minutes ago";
                                                    } elseif ($timeDiff > 60 && $timeDiff < 120) {
                                                        $timeDiff = floor(abs($timeDiff / 60)) . " hour ago";
                                                    } elseif ($timeDiff < 1440) {
                                                        $timeDiff = floor(abs($timeDiff / 60)) . " hours ago";
                                                    } elseif ($timeDiff > 1440 && $timeDiff < 2880) {
                                                        $timeDiff = floor(abs($timeDiff / 1440)) . " day ago";
                                                    } elseif ($timeDiff > 2880) {
                                                        $timeDiff = floor(abs($timeDiff / 1440)) . " days ago";
                                                    }
                                                    echo $timeDiff;
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td><strong>There is no email to display</strong></td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table><!-- /.table -->
                                </div><!-- /.mail-box-messages -->
                            </div><!-- /.box-body -->
                        </div><!-- /. box -->
                    </section><!-- /.content -->
                </form>
            </div>
        </div>
    </div>
</div>