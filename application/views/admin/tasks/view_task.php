<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<style>
    .note-editor .note-editable {
        height: 150px;
    }

    a:hover {
        text-decoration: none;
    }
</style>
<?php
$edited = can_action('54', 'edited');

$can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
// get all comments by tasks id
$comment_details = $this->db->where('task_id', $task_details->task_id)->get('tbl_task_comment')->result();
// get all $total_timer by tasks id
$total_timer = $this->db->where(array('task_id' => $task_details->task_id, 'start_time !=' => 0, 'end_time !=' => 0,))->get('tbl_tasks_timer')->result();
$activities_info = $this->db->where(array('module' => 'tasks', 'module_field_id' => $task_details->task_id))->order_by('activity_date', 'desc')->get('tbl_activities')->result();

$where = array('user_id' => $this->session->userdata('user_id'), 'module_id' => $task_details->task_id, 'module_name' => 'tasks');

?>
<div class="row mt-lg">
    <div class="col-sm-3">
        <!-- Tabs within a box -->
        <ul class="nav nav-pills nav-stacked navbar-custom-nav">

            <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#task_details"
                                                               data-toggle="tab"><?= lang('tasks') . ' ' . lang('details') ?></a>
            </li>
            <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#task_comments"
                                                               data-toggle="tab"><?= lang('comments') ?> <strong
                        class="pull-right"><?= (!empty($comment_details) ? count($comment_details) : null) ?></strong></a>
            </li>
            <li class="<?= $active == 3 ? 'active' : '' ?>"><a href="#task_attachments"
                                                               data-toggle="tab"><?= lang('attachment') ?>
                    <strong
                        class="pull-right"><?= (!empty($project_files_info) ? count($project_files_info) : null) ?></strong></a>
            </li>
            <li class="<?= $active == 4 ? 'active' : '' ?>"><a href="#task_notes"
                                                               data-toggle="tab"><?= lang('notes') ?></a></li>
            <li class="<?= $active == 5 ? 'active' : '' ?>"><a href="#timesheet"
                                                               data-toggle="tab"><?= lang('timesheet') ?><strong
                        class="pull-right"><?= (!empty($total_timer) ? count($total_timer) : null) ?></strong></a></li>
            <li class="<?= $active == 6 ? 'active' : '' ?>"><a href="#activities"
                                                               data-toggle="tab"><?= lang('activities') ?><strong
                        class="pull-right"></strong><strong
                        class="pull-right"><?= (!empty($activities_info) ? count($activities_info) : null) ?></strong></a>
            </li>

        </ul>
    </div>
    <div class="col-sm-9">
        <div class="tab-content" style="border: 0;padding:0;">
            <!-- Task Details tab Starts -->
            <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="task_details"
                 style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php if (!empty($task_details->task_name)) echo $task_details->task_name; ?>

                            <div class="pull-right ml-sm">
                                <a data-toggle="tooltip" data-placement="top" title="<?= lang('export_report') ?>"
                                   href="<?= base_url() ?>admin/tasks/export_report/<?= $task_details->task_id ?>"
                                   class="btn-xs btn btn-success"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                            <?php

                            if (!empty($can_edit) && !empty($edited)) {
                                ?>
                                <span class="btn-xs pull-right"><a
                                        href="<?= base_url() ?>admin/tasks/all_task/<?= $task_details->task_id ?>"><?= lang('edit') . ' ' . lang('task') ?></a>
                                </span>
                            <?php } ?>


                        </h3>
                    </div>
                    <div class="panel-body row form-horizontal task_details">

                        <div class="form-group col-sm-6">
                            <label class="control-label col-sm-5"><strong><?= lang('task_status') ?>
                                    :</strong></label>
                            <div class="pull-left mt">
                                <?php
                                if ($task_details->task_status == 'completed') {
                                    $label = 'success';
                                } elseif ($task_details->task_status == 'not_started') {
                                    $label = 'info';
                                } elseif ($task_details->task_status == 'deferred') {
                                    $label = 'danger';
                                } else {
                                    $label = 'warning';
                                }
                                ?>
                                <p class="form-control-static label label-<?= $label ?>  "><?= lang($task_details->task_status) ?></p>
                            </div>
                            <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                <div class="col-sm-1 mt">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">
                                            <?= lang('change') ?>
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu animated zoomIn">
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/not_started' ?>"><?= lang('not_started') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/in_progress' ?>"><?= lang('in_progress') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/completed' ?>"><?= lang('completed') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/deferred' ?>"><?= lang('deferred') ?></a>
                                            </li>
                                            <li>
                                                <a href="<?= base_url() ?>admin/tasks/change_status/<?= $task_details->task_id . '/waiting_for_someone' ?>"><?= lang('waiting_for_someone') ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-4"><strong><?= lang('timer_status') ?>:</strong></label>
                            <div class="col-sm-8 mt">
                                <?php if ($task_details->timer_status == 'on') { ?>
                                    <span class="label label-success"><?= lang('on') ?></span>

                                    <a class="btn btn-xs btn-danger "
                                       href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $task_details->task_id ?>/details"><?= lang('stop_timer') ?> </a>
                                <?php } else {
                                    ?>
                                    <span class="label label-danger"><?= lang('off') ?></span>
                                    <?php $this_permission = $this->tasks_model->can_action('tbl_task', 'view', array('task_id' => $task_details->task_id), true);
                                    if (!empty($this_permission)) { ?>
                                        <a class="btn btn-xs btn-success"
                                           href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $task_details->task_id ?>/details"><?= lang('start_timer') ?> </a>
                                    <?php }
                                }
                                ?>
                            </div>
                        </div>

                        <?php
                        if (!empty($task_details->goal_tracking_id)):
                            $goal_tracking_info = $this->db->where('goal_tracking_id', $task_details->goal_tracking_id)->get('tbl_goal_tracking')->row();
                            ?>
                            <div class="form-group  col-sm-10">
                                <label class="control-label col-sm-3 "><strong
                                        class="mr-sm"><?= lang('goal_tracking') ?></strong></label>
                                <div class="col-sm-8 " style="margin-left: -5px;">
                                    <p class="form-control-static"><?php if (!empty($goal_tracking_info->subject)) echo $goal_tracking_info->subject; ?></p>
                                </div>
                            </div>
                        <?php endif ?>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-5"><strong><?= lang('start_date') ?>
                                    :</strong></label>
                            <div class="col-sm-7 ">
                                <p class="form-control-static"><?php
                                    if (!empty($task_details->task_start_date)) {
                                        echo strftime(config_item('date_format'), strtotime($task_details->task_start_date));
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <div class="form-group  col-sm-6">
                            <?php
                            $due_date = $task_details->due_date;
                            $due_time = strtotime($due_date);
                            $current_time = time();
                            if ($current_time > $due_time) {
                                $text = 'text-danger';
                            } else {
                                $text = null;
                            }
                            ?>

                            <label class="control-label col-sm-4"><strong
                                    class="<?= $text ?>"><?= lang('due_date') ?>
                                    :</strong></label>
                            <div class="col-sm-8 ">
                                <p class="form-control-static"><?php
                                    if (!empty($task_details->due_date)) {
                                        echo strftime(config_item('date_format'), strtotime($task_details->due_date));
                                    }
                                    ?></p>

                            </div>
                        </div>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-5"><strong><?= lang('created_by') ?>
                                    :</strong></label>
                            <div class="col-sm-7 ">
                                <p class="form-control-static"><?php
                                    if (!empty($task_details->created_by)) {
                                        echo $this->db->where('user_id', $task_details->created_by)->get('tbl_account_details')->row()->fullname;
                                    }
                                    ?></p>

                            </div>
                        </div>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-4"><strong><?= lang('created_date') ?>:</strong></label>
                            <div class="col-sm-8 ">
                                <p class="form-control-static"><?php
                                    if (!empty($task_details->due_date)) {
                                        echo strftime(config_item('date_format'), strtotime($task_details->task_created_date));
                                    }
                                    ?></p>

                            </div>
                        </div>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-5"><strong><?= lang('project_hourly_rate') ?>
                                    :</strong></label>
                            <div class="col-sm-7 ">
                                <p class="form-control-static"><?php
                                    if (!empty($task_details->hourly_rate)) {
                                        echo $task_details->hourly_rate;
                                    }
                                    ?></p>
                            </div>
                        </div>

                        <?php $show_custom_fields = custom_form_label(3, $task_details->task_id);

                        if (!empty($show_custom_fields)) {
                            foreach ($show_custom_fields as $c_label => $v_fields) {
                                if (!empty($v_fields)) {
                                    if (count($v_fields) == 1) {
                                        $col = 'col-sm-10';
                                        $sub_col = 'col-sm-3';
                                        $style = 'padding-left:8px';
                                    } else {
                                        $col = 'col-sm-6';
                                        $sub_col = 'col-sm-5';
                                        $style = null;
                                    }

                                    ?>
                                    <div class="form-group  <?= $col ?>" style="<?= $style ?>">
                                        <label class="control-label <?= $sub_col ?>"><strong><?= $c_label ?>
                                                :</strong></label>
                                        <div class="col-sm-7 ">
                                            <p class="form-control-static">
                                                <strong><?= $v_fields ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                <?php }
                            }
                        }
                        ?>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-5"><strong><?= lang('estimated_hour') ?>
                                    :</strong></label>
                            <div class="col-sm-7 ">
                                <p class="form-control-static">
                                    <strong><?php if (!empty($task_details->task_hour)) echo $task_details->task_hour; ?> <?= lang('hours') ?></strong>
                                </p>
                            </div>
                        </div>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-5"><strong><?= lang('billable') ?>
                                    :</strong></label>
                            <div class="col-sm-7 ">
                                <p class="form-control-static">
                                    <?php if (!empty($task_details->billable)) {
                                        if ($task_details->billable == 'Yes') {
                                            $billable = 'success';
                                            $text = lang('yes');
                                        } else {
                                            $billable = 'danger';
                                            $text = lang('no');
                                        };
                                    } else {
                                        $billable = '';
                                        $text = '-';
                                    }; ?>
                                    <strong class="label label-<?= $billable ?>">
                                        <?= $text ?>
                                    </strong>
                                </p>
                            </div>
                        </div>
                        <div class="form-group  col-sm-6">
                            <label class="control-label col-sm-4"><strong><?= lang('participants') ?>
                                    :</strong></label>
                            <div class="col-sm-8 ">
                                <?php
                                if ($task_details->permission != 'all') {
                                    $get_permission = json_decode($task_details->permission);
                                    if (is_object($get_permission)) :
                                        foreach ($get_permission as $permission => $v_permission) :
                                            $user_info = $this->db->where(array('user_id' => $permission))->get('tbl_users')->row();
                                            if ($user_info->role_id == 1) {
                                                $label = 'circle-danger';
                                            } else {
                                                $label = 'circle-success';
                                            }
                                            $profile_info = $this->db->where(array('user_id' => $permission))->get('tbl_account_details')->row();
                                            ?>


                                            <a href="#" data-toggle="tooltip" data-placement="top"
                                               title="<?= $profile_info->fullname ?>"><img
                                                    src="<?= base_url() . $profile_info->avatar ?>"
                                                    class="img-circle img-xs" alt="">
                                                <span style="margin: 0px 0 8px -10px;"
                                                      class="circle <?= $label ?>  circle-lg"></span>
                                            </a>
                                            <?php
                                        endforeach;
                                    endif;
                                } else { ?>
                                <p class="form-control-static"><strong><?= lang('everyone') ?></strong>
                                    <i
                                        title="<?= lang('permission_for_all') ?>"
                                        class="fa fa-question-circle" data-toggle="tooltip"
                                        data-placement="top"></i>

                                    <?php
                                    }
                                    ?>
                                    <?php
                                    $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $task_details->task_id));
                                    if (!empty($can_edit) && !empty($edited)) {
                                    ?>
                                    <span data-placement="top" data-toggle="tooltip"
                                          title="<?= lang('add_more') ?>">
                                            <a data-toggle="modal" data-target="#myModal"
                                               href="<?= base_url() ?>admin/tasks/update_users/<?= $task_details->task_id ?>"
                                               class="text-default ml"><i class="fa fa-plus"></i></a>
                                                </span>
                                </p>
                            <?php
                            }
                            ?>

                            </div>
                        </div>

                        <div class="form-group  col-sm-10">
                            <label class="control-label col-sm-3 "><strong class="mr-sm"><?= lang('completed') ?>
                                    :</strong></label>
                            <div class="col-sm-9 " style="margin-left: -5px;">
                                <?php
                                if ($task_details->task_progress < 49) {
                                    $progress = 'progress-bar-danger';
                                } elseif ($task_details->task_progress > 50 && $task_details->task_progress < 99) {
                                    $progress = 'progress-bar-primary';
                                } else {
                                    $progress = 'progress-bar-success';
                                }
                                ?>
                                <span class="">
                                <div class="mt progress progress-striped ">
                                    <div class="progress-bar <?= $progress ?> " data-toggle="tooltip"
                                         data-original-title="<?= $task_details->task_progress ?>%"
                                         style="width: <?= $task_details->task_progress ?>%"></div>
                                </div>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <?php

                            $task_time = $this->tasks_model->task_spent_time_by_id($task_details->task_id);
                            ?>
                            <?= $this->tasks_model->get_time_spent_result($task_time) ?>
                            <?php
                            if (!empty($task_details->billable) && $task_details->billable == 'Yes') {
                                $total_time = $task_time / 3600;
                                $total_cost = $total_time * $task_details->hourly_rate;
                                $currency = $this->db->where('code', config_item('default_currency'))->get('tbl_currencies')->row();
                                ?>
                                <h2 class="text-center"><?= lang('total_bill') ?>
                                    : <?= display_money($total_cost, $currency->symbol) ?></h2>
                            <?php }
                            $estimate_hours = $task_details->task_hour;
                            $percentage = $this->tasks_model->get_estime_time($estimate_hours);

                            if ($task_time < $percentage) {
                                $total_time = $percentage - $task_time;
                                $worked = '<storng style="font-size: 15px;"  class="required">' . lang('left_works') . '</storng>';
                            } else {
                                $total_time = $task_time - $percentage;
                                $worked = '<storng style="font-size: 15px" class="required">' . lang('extra_works') . '</storng>';
                            }

                            ?>
                            <div class="text-center">
                                <div class="">
                                    <?= $worked ?>
                                </div>
                                <div class="">
                                    <?= $this->tasks_model->get_spent_time($total_time) ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <blockquote
                                style="font-size: 12px; margin-top: 5px;word-wrap: break-word;width: 100%"><?php if (!empty($task_details->task_description)) echo $task_details->task_description; ?></blockquote>
                        </div>

                    </div>
                </div>

            </div>
            <!-- Task Details tab Ends -->


            <!-- Task Comments Panel Starts --->
            <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="task_comments"
                 style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= lang('comments') ?></h3>
                    </div>
                    <div class="panel-body chat" id="chat-box">
                        <form id="form_validation"
                              action="<?php echo base_url() ?>admin/tasks/save_comments" method="post"
                              class="form-horizontal">
                            <input type="hidden" name="task_id" value="<?php
                            if (!empty($task_details->task_id)) {
                                echo $task_details->task_id;
                            }
                            ?>" class="form-control">
                            <div class="form-group">
                                <div class="col-sm-12">
                                                <textarea class="form-control textarea"
                                                          placeholder="<?= $task_details->task_name . ' ' . lang('comments') ?>"
                                                          name="comment" style="height: 70px;"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <div class="pull-right">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary"><?= lang('post_comment') ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr/>

                        <?php

                        if (!empty($comment_details)):foreach ($comment_details as $key => $v_comment):
                            $user_info = $this->db->where(array('user_id' => $v_comment->user_id))->get('tbl_users')->row();
                            $profile_info = $this->db->where(array('user_id' => $v_comment->user_id))->get('tbl_account_details')->row();
                            if ($user_info->role_id == 1) {
                                $label = '<small style="font-size:10px;padding:2px;" class="label label-danger ">' . lang('admin') . '</small>';
                            } elseif ($user_info->role_id == 3) {
                                $label = '<small style="font-size:10px;padding:2px;" class="label label-primary">' . lang('staff') . '</small>';
                            } else {
                                $label = '<small style="font-size:10px;padding:2px;" class="label label-success">' . lang('client') . '</small>';
                            }
                            ?>

                            <div class="col-sm-12 item ">

                                <img src="<?php echo base_url() . $profile_info->avatar ?>" alt="user image"
                                     class="img-xs img-circle"/>


                                <p class="message">
                                    <?php
                                    $today = time();
                                    $comment_time = strtotime($v_comment->comment_datetime);
                                    ?>
                                    <small class="text-muted pull-right"><i
                                            class="fa fa-clock-o"></i> <?= $this->tasks_model->get_time_different($today, $comment_time) ?> <?= lang('ago') ?>
                                        <?php if ($v_comment->user_id == $this->session->userdata('user_id')) { ?>
                                            <?= btn_delete('admin/tasks/delete_task_comments/' . $v_comment->task_id . '/' . $v_comment->task_comment_id) ?>
                                        <?php } ?></small>
                                    <a href="#" class="name">
                                        <?= ($profile_info->fullname) . ' ' . $label ?>
                                    </a>

                                    <?php if (!empty($v_comment->comment)) echo $v_comment->comment; ?>
                                </p>

                            </div><!-- /.item -->
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Task Comments Panel Ends--->

            <!-- Task Attachment Panel Starts --->
            <div class="tab-pane <?= $active == 3 ? 'active' : '' ?>" id="task_attachments"
                 style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= lang('attachment') ?></h3>
                    </div>
                    <div class="panel-body">

                        <form action="<?= base_url() ?>admin/tasks/save_task_attachment/<?php
                        if (!empty($add_files_info)) {
                            echo $add_files_info->task_attachment_id;
                        }
                        ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('file_title') ?> <span
                                        class="text-danger">*</span></label>
                                <div class="col-lg-6">
                                    <input name="title" class="form-control" value="<?php
                                    if (!empty($add_files_info)) {
                                        echo $add_files_info->title;
                                    }
                                    ?>" required placeholder="<?= lang('file_title') ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('description') ?></label>
                                <div class="col-lg-6">
                                        <textarea name="description" class="form-control"
                                                  placeholder="<?= lang('description') ?>"><?php
                                            if (!empty($add_files_info)) {
                                                echo $add_files_info->description;
                                            }
                                            ?></textarea>
                                </div>
                            </div>
                            <?php if (empty($add_files_info)) { ?>
                                <div id="add_new">
                                    <div class="form-group" style="margin-bottom: 0px">
                                        <label for="field-1"
                                               class="col-sm-3 control-label"><?= lang('upload_file') ?></label>
                                        <div class="col-sm-6">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <?php if (!empty($project_files)):foreach ($project_files as $v_files_image): ?>
                                                    <span class=" btn btn-default btn-file"><span
                                                            class="fileinput-new"
                                                            style="display: none">Select file</span>
                                                                <span class="fileinput-exists"
                                                                      style="display: block"><?= lang('change') ?></span>
                                                                <input type="hidden" name="task_files[]"
                                                                       value="<?php echo $v_files_image->files ?>">
                                                                <input type="file" name="task_files[]">
                                                            </span>
                                                    <span
                                                        class="fileinput-filename"> <?php echo $v_files_image->file_name ?></span>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span class="btn btn-default btn-file"><span
                                                            class="fileinput-new"><?= lang('select_file') ?></span>
                                                            <span class="fileinput-exists"><?= lang('change') ?></span>
                                                            <input type="file" name="task_files[]">
                                                        </span>
                                                    <span class="fileinput-filename"></span>
                                                    <a href="#" class="close fileinput-exists"
                                                       data-dismiss="fileinput"
                                                       style="float: none;">&times;</a>
                                                <?php endif; ?>
                                            </div>
                                            <div id="msg_pdf" style="color: #e11221"></div>
                                        </div>
                                        <div class="col-sm-2">
                                            <strong><a href="javascript:void(0);" id="add_more"
                                                       class="addCF "><i
                                                        class="fa fa-plus"></i>&nbsp;<?= lang('add_more') ?>
                                                </a></strong>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <br/>
                            <input type="hidden" name="task_id" value="<?php
                            if (!empty($task_details->task_id)) {
                                echo $task_details->task_id;
                            }
                            ?>" class="form-control">
                            <div class="form-group">
                                <div class="col-sm-3">
                                </div>
                                <div class="col-sm-3">
                                    <button type="submit"
                                            class="btn btn-primary"><?= lang('upload_file') ?></button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <?php
                if (!empty($project_files_info)) {
                    ?>
                    <div class="panel">
                        <div class="panel-heading" style="border-bottom: 2px solid #00BCD4">
                            <strong><?= lang('attach_file_list') ?></strong></div>
                        <div class="panel-body">
                            <?php
                            $this->load->helper('file');
                            foreach ($project_files_info as $key => $v_files_info) {
                                ?>
                                <div class="panel-group" id="accordion" style="margin:8px 0px"
                                     role="tablist" aria-multiselectable="true">
                                    <div class="box box-info" style="border-radius: 0px ">
                                        <div class="panel-heading pl-sm" role="tab" id="headingOne" style="">
                                            <h4 class="panel-title">
                                                <a data-toggle="collapse" data-parent="#accordion"
                                                   href="#<?php echo $key ?>" aria-expanded="true"
                                                   aria-controls="collapseOne">
                                                    <strong
                                                        style="text-decoration: underline"><?php echo $files_info[$key]->title; ?> </strong>
                                                    <small style="color:#ffffff " class="pull-right">
                                                        <?php if ($files_info[$key]->user_id == $this->session->userdata('user_id')) { ?>
                                                            <?= btn_delete('admin/tasks/delete_task_files/' . $files_info[$key]->task_id . '/' . $files_info[$key]->task_attachment_id) ?>
                                                        <?php } ?></small>
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="<?php echo $key ?>" class="panel-collapse collapse <?php
                                        if (!empty($in) && $files_info[$key]->files_id == $in) {
                                            echo 'in';
                                        }
                                        ?>" role="tabpanel" aria-labelledby="headingOne">
                                            <div class="content">
                                                <div class="table-responsive">
                                                    <table id="table-files" class="table table-striped ">
                                                        <thead>
                                                        <tr>
                                                            <th width="45%"><?= lang('files') ?></th>
                                                            <th class=""><?= lang('size') ?></th>
                                                            <th><?= lang('date') ?></th>
                                                            <th><?= lang('uploaded_by') ?></th>
                                                            <th><?= lang('action') ?></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        $this->load->helper('file');

                                                        if (!empty($v_files_info)) {
                                                            foreach ($v_files_info as $v_files) {
                                                                $user_info = $this->db->where(array('user_id' => $files_info[$key]->user_id))->get('tbl_users')->row();
                                                                ?>
                                                                <tr class="file-item">
                                                                    <td>
                                                                        <?php if ($v_files->is_image == 1) : ?>
                                                                            <div class="file-icon"><a
                                                                                    href="<?= base_url() ?>admin/tasks/download_files/<?= $files_info[$key]->task_id ?>/<?= $v_files->uploaded_files_id ?>">
                                                                                    <img
                                                                                        style="width: 50px;border-radius: 5px;"
                                                                                        src="<?= base_url() . $v_files->files ?>"/></a>
                                                                            </div>
                                                                        <?php else : ?>
                                                                            <div class="file-icon"><i
                                                                                    class="fa fa-file-o"></i>
                                                                                <a href="<?= base_url() ?>admin/tasks/download_files/<?= $files_info[$key]->task_id ?>/<?= $v_files->uploaded_files_id ?>"><?= $v_files->file_name ?></a>
                                                                            </div>
                                                                        <?php endif; ?>

                                                                        <a data-toggle="tooltip"
                                                                           data-placement="top"
                                                                           data-original-title="<?= $files_info[$key]->description ?>"
                                                                           class="text-info"
                                                                           href="<?= base_url() ?>admin/tasks/download_files/<?= $files_info[$key]->task_id ?>/<?= $v_files->uploaded_files_id ?>">
                                                                            <?= $files_info[$key]->title ?>
                                                                            <?php if ($v_files->is_image == 1) : ?>
                                                                                <em><?= $v_files->image_width . "x" . $v_files->image_height ?></em>
                                                                            <?php endif; ?>
                                                                        </a>
                                                                        <p class="file-text"><?= $files_info[$key]->description ?></p>
                                                                    </td>
                                                                    <td class=""><?= $v_files->size ?>Kb
                                                                    </td>
                                                                    <td class="col-date"><?= date('Y-m-d' . "<br/> h:m A", strtotime($files_info[$key]->upload_time)); ?></td>
                                                                    <td>
                                                                        <?= $user_info->username ?>
                                                                    </td>
                                                                    <td>
                                                                        <a class="btn btn-xs btn-dark"
                                                                           data-toggle="tooltip"
                                                                           data-placement="top"
                                                                           title="Download"
                                                                           href="<?= base_url() ?>admin/tasks/download_files/<?= $files_info[$key]->task_id ?>/<?= $v_files->uploaded_files_id ?>"><i
                                                                                class="fa fa-download"></i></a>
                                                                    </td>

                                                                </tr>
                                                                <?php
                                                            }
                                                        } else {
                                                            ?>
                                                            <tr>
                                                                <td colspan="5">
                                                                    <?= lang('nothing_to_display') ?>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <!-- Task Attachment Panel Ends --->
            <div class="tab-pane <?= $active == 4 ? 'active' : '' ?>" id="task_notes"
                 style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= lang('notes') ?></h3>
                    </div>
                    <div class="panel-body">

                        <form action="<?= base_url() ?>admin/tasks/save_tasks_notes/<?php
                        if (!empty($task_details)) {
                            echo $task_details->task_id;
                        }
                        ?>" enctype="multipart/form-data" method="post" id="form" class="form-horizontal">
                            <div class="form-group">
                                <div class="col-lg-12">
                                                <textarea class="form-control textarea"
                                                          name="tasks_notes"><?= $task_details->tasks_notes ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2">
                                    <button type="submit" id="sbtn"
                                            class="btn btn-primary"><?= lang('updates') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane <?= $active == 5 ? 'active' : '' ?>" id="timesheet"
                 style="position: relative;">
                <style>
                    .tooltip-inner {
                        white-space: pre-wrap;
                    }
                </style>
                <div class="nav-tabs-custom">
                    <!-- Tabs within a box -->
                    <ul class="nav nav-tabs">
                        <li class="<?= $time_active == 1 ? 'active' : ''; ?>"><a href="#general"
                                                                                 data-toggle="tab"><?= lang('timesheet') ?></a>
                        </li>
                        <li class="<?= $time_active == 2 ? 'active' : ''; ?>"><a href="#contact"
                                                                                 data-toggle="tab"><?= lang('manual_entry') ?></a>
                        </li>
                    </ul>
                    <div class="tab-content bg-white">
                        <!-- ************** general *************-->
                        <div class="tab-pane <?= $time_active == 1 ? 'active' : ''; ?>" id="general">
                            <div class="table-responsive">
                                <table id="table-tasks-timelog" class="table table-striped     DataTables">
                                    <thead>
                                    <tr>
                                        <th><?= lang('user') ?></th>
                                        <th><?= lang('start_time') ?></th>
                                        <th><?= lang('stop_time') ?></th>

                                        <th><?= lang('task_name') ?></th>
                                        <th class="col-time"><?= lang('time_spend') ?></th>
                                        <th><?= lang('action') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($total_timer)) {
                                        foreach ($total_timer as $v_tasks) {
                                            $task_info = $this->db->where(array('task_id' => $v_tasks->task_id))->get('tbl_task')->row();
                                            if (!empty($task_info)) {
                                                ?>
                                                <tr>
                                                    <td class="small">

                                                        <a class="pull-left recect_task  ">
                                                            <?php
                                                            $profile_info = $this->db->where(array('user_id' => $v_tasks->user_id))->get('tbl_account_details')->row();
                                                            $user_info = $this->db->where(array('user_id' => $v_tasks->user_id))->get('tbl_users')->row();
                                                            if (!empty($user_info)) {
                                                                ?>
                                                                <img style="width: 30px;margin-left: 18px;
                                                                             height: 29px;
                                                                             border: 1px solid #aaa;"
                                                                     src="<?= base_url() . $profile_info->avatar ?>"
                                                                     class="img-circle">

                                                                <?= ucfirst($user_info->username) ?>
                                                            <?php } else {
                                                                echo '-';
                                                            } ?>
                                                        </a>


                                                    </td>

                                                    <td><span
                                                            class="label label-success"><?= strftime(config_item('date_format') . ' %H:%M', $v_tasks->start_time) ?></span>
                                                    </td>
                                                    <td><span
                                                            class="label label-danger"><?= strftime(config_item('date_format') . ' %H:%M', $v_tasks->end_time) ?></span>
                                                    </td>

                                                    <td>
                                                        <a href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_tasks->task_id ?>"
                                                           class="text-info small"><?= $task_info->task_name ?>
                                                            <?php
                                                            if (!empty($v_tasks->reason)) {
                                                                $edit_user_info = $this->db->where(array('user_id' => $v_tasks->edited_by))->get('tbl_users')->row();
                                                                echo '<i class="text-danger" data-html="true" data-toggle="tooltip" data-placement="top" title="Reason : ' . $v_tasks->reason . '<br>' . ' Edited By : ' . $edit_user_info->username . '">Edited</i>';
                                                            }
                                                            ?>
                                                        </a></td>
                                                    <td>
                                                        <small
                                                            class="small text-muted"><?= $this->tasks_model->get_time_spent_result($v_tasks->end_time - $v_tasks->start_time) ?></small>
                                                    </td>
                                                    <td>
                                                        <?= btn_edit('admin/tasks/view_task_details/' . $v_tasks->tasks_timer_id . '/5/edit') ?>
                                                        <?= btn_delete('admin/tasks/update_tasks_timer/' . $v_tasks->tasks_timer_id . '/delete_task_timmer') ?>
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
                        <div class="tab-pane <?= $time_active == 2 ? 'active' : ''; ?>" id="contact">
                            <form role="form" enctype="multipart/form-data" id="form"
                                  action="<?php echo base_url(); ?>admin/tasks/update_tasks_timer/<?php
                                  if (!empty($tasks_timer_info)) {
                                      echo $tasks_timer_info->tasks_timer_id;
                                  }
                                  ?>" method="post" class="form-horizontal">
                                <?php
                                if (!empty($tasks_timer_info)) {
                                    $start_date = date('Y-m-d', $tasks_timer_info->start_time);
                                    $start_time = date('H:i', $tasks_timer_info->start_time);
                                    $end_date = date('Y-m-d', $tasks_timer_info->end_time);
                                    $end_time = date('H:i', $tasks_timer_info->end_time);
                                } else {
                                    $start_date = '';
                                    $start_time = '';
                                    $end_date = '';
                                    $end_time = '';
                                }
                                ?>
                                <?php if (empty($tasks_timer_info->tasks_timer_id)) { ?>
                                    <div class="form-group margin">
                                        <div class="col-sm-8 center-block">
                                            <label class="control-label"><?= lang('select') . ' ' . lang('tasks') ?>
                                                <span
                                                    class="required">*</span></label>
                                            <select class="form-control select_box" name="task_id"
                                                    required="" style="width: 100%">
                                                <?php
                                                $all_tasks_info = $this->db->get('tbl_task')->result();
                                                if (!empty($all_tasks_info)):foreach ($all_tasks_info as $v_task_info):
                                                    ?>
                                                    <option
                                                        value="<?= $v_task_info->task_id ?>" <?= $v_task_info->task_id == $task_details->task_id ? 'selected' : null ?>><?= $v_task_info->task_name ?></option>
                                                <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <input type="hidden" name="task_id"
                                           value="<?= $tasks_timer_info->task_id ?>">
                                <?php } ?>
                                <div class="form-group margin">
                                    <div class="col-sm-4">
                                        <label class="control-label"><?= lang('start_date') ?> </label>
                                        <div class="input-group">
                                            <input type="text" name="start_date"
                                                   class="form-control datepicker"
                                                   value="<?= $start_date ?>"
                                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label"><?= lang('start_time') ?></label>
                                        <div class="input-group">
                                            <input type="text" name="start_time"
                                                   class="form-control timepicker2"
                                                   value="<?= $start_time ?>">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-clock-o"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin">
                                    <div class="col-sm-4">
                                        <label class="control-label"><?= lang('end_date') ?></label>
                                        <div class="input-group">
                                            <input type="text" name="end_date"
                                                   class="form-control datepicker" value="<?= $end_date ?>"
                                                   data-date-format="<?= config_item('date_picker_format'); ?>">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-calendar"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="control-label"><?= lang('end_time') ?></label>
                                        <div class="input-group">
                                            <input type="text" name="end_time"
                                                   class="form-control timepicker2"
                                                   value="<?= $end_time ?>">
                                            <div class="input-group-addon">
                                                <a href="#"><i class="fa fa-clock-o"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group margin">
                                    <div class="col-sm-8 center-block">
                                        <label class="control-label"><?= lang('edit_reason') ?><span
                                                class="required">*</span></label>
                                        <div>
                                                <textarea class="form-control" name="reason" required="" rows="6"><?php
                                                    if (!empty($tasks_timer_info)) {
                                                        echo $tasks_timer_info->reason;
                                                    }
                                                    ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group" style="margin-top: 20px;">
                                    <div class="col-lg-6">
                                        <button type="submit"
                                                class="btn btn-sm btn-primary"><?= lang('updates') ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
            <div class="tab-pane " id="activities">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= lang('activities') ?>
                            <?php
                            $role = $this->session->userdata('user_type');
                            if ($role == 1) {
                                ?>
                                <span class="btn-xs pull-right">
                            <a href="<?= base_url() ?>admin/tasks/claer_activities/tasks/<?= $task_details->task_id ?>"><?= lang('clear') . ' ' . lang('activities') ?></a>
                            </span>
                            <?php } ?>
                        </h3>
                    </div>
                    <div class="panel-body " id="chat-box">
                        <div id="activity">
                            <ul class="list-group no-radius   mt-xs list-group-lg no-border">
                                <?php
                                if (!empty($activities_info)) {
                                    foreach ($activities_info as $v_activities) {
                                        $profile_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_account_details')->row();

                                        $user_info = $this->db->where(array('user_id' => $v_activities->user))->get('tbl_users')->row();
                                        ?>
                                        <li class="list-group-item">
                                            <a class="recect_task pull-left mr-sm">

                                                <?php if (!empty($profile_info)) {
                                                    ?>
                                                    <img src="<?= base_url() . $profile_info->avatar ?>"
                                                         class="img-xs img-circle">
                                                <?php } ?>
                                            </a>
                                            <a class="clear">
                                                <small
                                                    class="pull-right"><?= strftime(config_item('date_format') . " %H:%M:%S", strtotime($v_activities->activity_date)) ?></small>
                                                <strong
                                                    class="block"><?= ucfirst($user_info->username) ?></strong>
                                                <small>
                                                    <?php
                                                    echo sprintf(lang($v_activities->activity) . ' <strong style="color:#000"><em>' . $v_activities->value1 . '</em>' . '<em>' . $v_activities->value2 . '</em></strong>');
                                                    ?>
                                                </small>
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var maxAppend = 0;
        $("#add_more").click(function () {
            if (maxAppend >= 4) {
                alert("Maximum 5 File is allowed");
            } else {
                var add_new = $('<div class="form-group" style="margin-bottom: 0px">\n\
                    <label for="field-1" class="col-sm-3 control-label"><?= lang('upload_file') ?></label>\n\
        <div class="col-sm-5">\n\
        <div class="fileinput fileinput-new" data-provides="fileinput">\n\
<span class="btn btn-default btn-file"><span class="fileinput-new" >Select file</span><span class="fileinput-exists" >Change</span><input type="file" name="task_files[]" ></span> <span class="fileinput-filename"></span><a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none;">&times;</a></div></div>\n\<div class="col-sm-2">\n\<strong>\n\
<a href="javascript:void(0);" class="remCF"><i class="fa fa-times"></i>&nbsp;Remove</a></strong></div>');
                maxAppend++;
                $("#add_new").append(add_new);
            }
        });

        $("#add_new").on('click', '.remCF', function () {
            $(this).parent().parent().parent().remove();
        });
    });
</script>