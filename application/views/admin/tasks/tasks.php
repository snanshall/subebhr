<?php include_once 'assets/admin-ajax.php'; ?>
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
<?php $complete = 0;
$not_started = 0;

if (!empty($all_task_info)):foreach ($all_task_info as $v_task):
    if ($v_task->task_status == 'completed') {
        $complete += count($v_task->task_id);
    }
    if ($v_task->task_status == 'not_started') {
        $not_started += count($v_task->task_id);
    }
endforeach;
endif;
$created = can_action('54', 'created');

$edited = can_action('54', 'edited');
$deleted = can_action('54', 'deleted');

$kanban = $this->session->userdata('task_kanban');
$uri_segment = $this->uri->segment(4);
if (!empty($kanban)) {
    $tasks = 'kanban';
} elseif ($uri_segment == 'kanban') {
    $tasks = 'kanban';
} else {
    $tasks = 'list';
}

if ($tasks == 'kanban') {
    $text = 'list';
    $btn = 'purple';
} else {
    $text = 'kanban';
    $btn = 'danger';
}

?>
<div class="mb-lg pull-left">
    <div class="pull-left pr-lg">
        <a href="<?= base_url() ?>admin/tasks/all_task/<?= $text ?>"
           class="btn btn-xs btn-<?= $btn ?> pull-right"
           data-toggle="tooltip"
           data-placement="top" title="<?= lang('switch_to_' . $text) ?>">
            <i class="fa fa-undo"> </i><?= ' ' . lang('switch_to_' . $text) ?>
        </a>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php if ($tasks == 'kanban') { ?>
            <link rel="stylesheet" href="<?= base_url() ?>assets/plugins/kanban/kan-app.css"/>
            <div class="app-wrapper">
                <p class="total-card-counter" id="totalCards"></p>
                <div class="board" id="board"></div>
            </div>
            <?php include_once 'assets/plugins/kanban/tasks_kan-app.php'; ?>
        <?php } else { ?>
            <div class="nav-tabs-custom">
                <!-- Tabs within a box -->
                <ul class="nav nav-tabs">
                    <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#task_list"
                                                                       data-toggle="tab"><?= lang('all_task') ?></a>
                    </li>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#assign_task"
                                                                           data-toggle="tab"><?= lang('assign_task') ?></a>
                        </li>
                        <li><a style="background-color: #1797be;color: #ffffff"
                               href="<?= base_url() ?>admin/tasks/import"><?= lang('import') . ' ' . lang('tasks') ?></a>
                        </li>
                    <?php }
                    $tasks_status = array_reverse($this->tasks_model->get_statuses());
                    foreach ($tasks_status as $v_status) {
                        $total_status = count($this->db->where('task_status', $v_status['value'])->get('tbl_task')->result());;
                        ?>
                        <li class="pull-right <?= $active == $v_status['value'] ? 'active' : ''; ?>"><a
                                href="<?= base_url() ?>admin/tasks/all_task/<?= $v_status['value'] ?>"
                            ><?= lang($v_status['value']) ?>
                                <small class="label label-danger"
                                       style="top: 11%;position: absolute;right: 5%;}"><?php if ($total_status != 0) {
                                        echo $total_status;
                                    } ?></small>
                            </a>
                        </li>
                    <?php }
                    ?>
                </ul>
                <div class="tab-content bg-white">
                    <!-- Stock Category List tab Starts -->
                    <div
                        class="tab-pane <?= $active == 1 || $active == 'not_started' || $active == 'in_progress' || $active == 'completed' || $active == 'deferred' || $active == 'waiting_for_someone' ? 'active' : '' ?>"
                        id="task_list">
                        <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                            <div class="box-body">
                                <!-- Table -->
                                <table class="table table-striped DataTables " id="DataTables" cellspacing="0"
                                       width="100%">
                                    <thead>
                                    <tr>
                                        <?php if (!empty($created) || !empty($edited)) { ?>
                                            <th data-check-all>

                                            </th>
                                        <?php } ?>
                                        <th class="col-sm-3"><?= lang('task_name') ?></th>
                                        <th class="col-sm-2"><?= lang('due_date') ?></th>
                                        <th class="col-sm-1"><?= lang('status') ?></th>
                                        <th class="col-sm-1"><?= lang('progress') ?></th>
                                        <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                        <th class="col-sm-3"><?= lang('changes/view') ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                        if ($v_task->task_status != 'completed' || !empty($completed)) {
                                            $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $v_task->task_id));
                                            $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $v_task->task_id));
                                            ?>
                                            <tr>
                                                <?php if (!empty($created) || !empty($edited)) { ?>
                                                    <td class="col-sm-1">
                                                        <div class="complete checkbox c-checkbox">
                                                            <label>
                                                                <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                                                       style="position: absolute;" <?php
                                                                if ($v_task->task_progress >= 100) {
                                                                    echo 'checked';
                                                                }
                                                                ?>>
                                                                <span class="fa fa-check"></span>
                                                            </label>
                                                        </div>
                                                    </td>
                                                <?php } ?>
                                                <td>
                                                    <a style="<?php
                                                    if ($v_task->task_progress >= 100) {
                                                        echo 'text-decoration: line-through;';
                                                    }
                                                    ?>"
                                                       href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                                </td>
                                                <td><?php
                                                    $due_date = $v_task->due_date;
                                                    $due_time = strtotime($due_date);
                                                    $current_time = time();
                                                    if ($v_task->task_progress == 100) {
                                                        $c_progress = 100;
                                                    } elseif ($v_task->task_status == 'completed') {
                                                        $c_progress = 100;
                                                    } else {
                                                        $c_progress = 0;
                                                    }

                                                    ?>
                                                    <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                                    <?php if ($current_time > $due_time && $c_progress < 100) { ?>
                                                        <span class="label label-danger"><?= lang('overdue') ?></span>
                                                    <?php } ?></td>
                                                <td>
                                                    <?php
                                                    if ($v_task->task_status == 'completed') {
                                                        $label = 'success';
                                                    } elseif ($v_task->task_status == 'not_started') {
                                                        $label = 'info';
                                                    } elseif ($v_task->task_status == 'deferred') {
                                                        $label = 'danger';
                                                    } else {
                                                        $label = 'warning';
                                                    }
                                                    ?>
                                                    <span
                                                        class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                                                </td>
                                                <td class="col-sm-1" style="padding-bottom: 0px;padding-top: 3px">

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
                                                        <span class="small "><?= $v_task->task_progress ?>
                                                            %</span>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <?php
                                                    if ($v_task->permission != 'all') {
                                                        $get_permission = json_decode($v_task->permission);
                                                        if (!empty($get_permission)) :
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
                                                        <strong><?= lang('everyone') ?></strong>
                                                        <i
                                                            title="<?= lang('permission_for_all') ?>"
                                                            class="fa fa-question-circle" data-toggle="tooltip"
                                                            data-placement="top"></i>
                                                        <?php
                                                    }
                                                    ?>
                                                    <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                        <span data-placement="top" data-toggle="tooltip"
                                                              title="<?= lang('add_more') ?>">
                                            <a data-toggle="modal" data-target="#myModal"
                                               href="<?= base_url() ?>admin/tasks/update_users/<?= $v_task->task_id ?>"
                                               class="text-default ml"><i class="fa fa-plus"></i></a>
                                                </span>
                                                    <?php } ?>
                                                </td>

                                                <td>
                                                    <?php if (!empty($can_edit) && !empty($edited)) {
                                                        echo btn_edit('admin/tasks/all_task/' . $v_task->task_id) . ' ';
                                                    } ?>
                                                    <?php if (!empty($can_delete) && !empty($deleted)) {
                                                        echo btn_delete('admin/tasks/delete_task/' . $v_task->task_id) . ' ';
                                                    } ?>
                                                    <?php

                                                    if ($v_task->timer_status == 'on') { ?>
                                                        <a class="btn btn-xs btn-danger"
                                                           href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?> </a>

                                                    <?php } else { ?>
                                                        <a class="btn btn-xs btn-success"
                                                           href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?> </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }endforeach; ?>
                                    <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <!-- Add Stock Category tab Starts -->
                        <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="assign_task"
                             style="position: relative;">
                            <div class="box" style="border: none; padding-top: 15px;" data-collapsed="0">
                                <div class="panel-body">
                                    <form data-parsley-validate="" novalidate=""
                                          action="<?php echo base_url() ?>admin/tasks/save_task/<?php if (!empty($task_info->task_id)) echo $task_info->task_id; ?>"
                                          method="post" class="form-horizontal">


                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"><?= lang('task_name') ?><span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="task_name" required class="form-control"
                                                       value="<?php if (!empty($task_info->task_name)) echo $task_info->task_name; ?>"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('start_date') ?></label>
                                            <div class="col-lg-5">
                                                <div class="input-group">
                                                    <input type="text" name="task_start_date"
                                                           class="form-control datepicker"
                                                           value="<?php
                                                           if (!empty($task_info->task_start_date)) {
                                                               echo $task_info->task_start_date;
                                                           } else {
                                                               echo date('Y-m-d');
                                                           }
                                                           ?>"
                                                           data-date-format="<?= config_item('date_picker_format'); ?>">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-lg-3 control-label"><?= lang('due_date') ?><span
                                                    class="required">*</span></label>
                                            <div class="col-lg-5">
                                                <div class="input-group">
                                                    <input type="text" name="due_date" required="" value="<?php
                                                    if (!empty($task_info->due_date)) {
                                                        echo $task_info->due_date;
                                                    }
                                                    ?>" class="form-control datepicker" data-format="yyyy-mm-dd">
                                                    <div class="input-group-addon">
                                                        <a href="#"><i class="fa fa-calendar"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"><?= lang('project_hourly_rate') ?>
                                                <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" data-parsley-type="number" name="hourly_rate"
                                                       required
                                                       class="form-control"
                                                       value="<?php if (!empty($task_info->hourly_rate)) echo $task_info->hourly_rate; ?>"/>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label"><?= lang('estimated_hour') ?> <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="number" step="0.01" data-parsley-type="number"
                                                       name="task_hour"
                                                       required
                                                       class="form-control"
                                                       value="<?php
                                                       if (!empty($task_info->task_hour)) {
                                                           $result = explode(':', $task_info->task_hour);
                                                           if (empty($result[1])) {
                                                               $result1 = 0;
                                                           } else {
                                                               $result1 = $result[1];
                                                           }
                                                           echo $result[0] . '.' . $result1;
                                                       }
                                                       ?>"/>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-3"><?= lang('progress') ?></label>
                                            <div class="col-sm-5">
                                                <input name="task_progress" data-ui-slider="" type="text"
                                                       value="<?php if (!empty($task_info->task_progress)) echo $task_info->task_progress; ?>"
                                                       data-slider-min="0" data-slider-max="100" data-slider-step="1"
                                                       data-slider-value="<?php if (!empty($task_info->task_progress)) echo $task_info->task_progress; ?>"
                                                       data-slider-orientation="horizontal"
                                                       class="slider slider-horizontal"
                                                       data-slider-id="red">
                                            </div>
                                        </div>
                                        <div class="form-group" id="border-none">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('task_status') ?> <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-5">
                                                <select name="task_status" class="form-control" required>
                                                    <option
                                                        value="not_started" <?= (!empty($task_info->task_status) && $task_info->task_status == 'not_started' ? 'selected' : '') ?>> <?= lang('not_started') ?> </option>
                                                    <option
                                                        value="in_progress" <?= (!empty($task_info->task_status) && $task_info->task_status == 'in_progress' ? 'selected' : '') ?>> <?= lang('in_progress') ?> </option>
                                                    <option
                                                        value="completed" <?= (!empty($task_info->task_status) && $task_info->task_status == 'completed' ? 'selected' : '') ?>> <?= lang('completed') ?> </option>
                                                    <option
                                                        value="deferred" <?= (!empty($task_info->task_status) && $task_info->task_status == 'deferred' ? 'selected' : '') ?>> <?= lang('deferred') ?> </option>
                                                    <option
                                                        value="waiting_for_someone" <?= (!empty($task_info->task_status) && $task_info->task_status == 'waiting_for_someone' ? 'selected' : '') ?>> <?= lang('waiting_for_someone') ?> </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('task_description') ?>
                                            </label>
                                            <div class="col-sm-8">
                                        <textarea class="form-control textarea"
                                                  name="task_description"><?php if (!empty($task_info->task_description)) echo $task_info->task_description; ?></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('billable') ?>
                                                <span class="required">*</span></label>
                                            <div class="col-sm-8">
                                                <input data-toggle="toggle" name="billable" value="Yes" <?php
                                                if (!empty($task_info) && $task_info->billable == 'Yes') {
                                                    echo 'checked';
                                                }
                                                ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                       data-onstyle="success" data-offstyle="danger" type="checkbox">
                                            </div>
                                        </div>
                                        <?php if (!empty($project_id)): ?>
                                            <div class="form-group">
                                                <label for="field-1"
                                                       class="col-sm-3 control-label"><?= lang('visible_to_client') ?>
                                                    <span class="required">*</span></label>
                                                <div class="col-sm-8">
                                                    <input data-toggle="toggle" name="client_visible" value="Yes" <?php
                                                    if (!empty($task_info) && $task_info->client_visible == 'Yes') {
                                                        echo 'checked';
                                                    }
                                                    ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                           data-onstyle="success" data-offstyle="danger"
                                                           type="checkbox">
                                                </div>
                                            </div>
                                        <?php endif ?>

                                        <?php
                                        if (!empty($task_info)) {
                                            $task_id = $task_info->task_id;
                                        } else {
                                            $task_id = null;
                                        }
                                        ?>
                                        <?= custom_form_Fields(3, $task_id); ?>

                                        <div class="form-group" id="border-none">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('assined_to') ?> <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-9">
                                                <div class="checkbox c-radio needsclick">
                                                    <label class="needsclick">
                                                        <input id="" <?php
                                                        if (!empty($task_info->permission) && $task_info->permission == 'all') {
                                                            echo 'checked';
                                                        } elseif (empty($task_info)) {
                                                            echo 'checked';
                                                        }
                                                        ?> type="radio" name="permission" value="everyone">
                                                        <span class="fa fa-circle"></span><?= lang('everyone') ?>
                                                        <i title="<?= lang('permission_for_all') ?>"
                                                           class="fa fa-question-circle" data-toggle="tooltip"
                                                           data-placement="top"></i>
                                                    </label>
                                                </div>
                                                <div class="checkbox c-radio needsclick">
                                                    <label class="needsclick">
                                                        <input id="" <?php
                                                        if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                            echo 'checked';
                                                        }
                                                        ?> type="radio" name="permission" value="custom_permission"
                                                        >
                                                        <span
                                                            class="fa fa-circle"></span><?= lang('custom_permission') ?>
                                                        <i
                                                            title="<?= lang('permission_for_customization') ?>"
                                                            class="fa fa-question-circle" data-toggle="tooltip"
                                                            data-placement="top"></i>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group <?php
                                        if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                            echo 'show';
                                        }
                                        ?>" id="permission_user_1">
                                            <label for="field-1"
                                                   class="col-sm-3 control-label"><?= lang('select') . ' ' . lang('users') ?>
                                                <span
                                                    class="required">*</span></label>
                                            <div class="col-sm-9">
                                                <?php
                                                if (!empty($assign_user)) {
                                                    foreach ($assign_user as $key => $v_user) {

                                                        if ($v_user->role_id == 1) {
                                                            $disable = true;
                                                            $role = '<strong class="badge btn-danger">' . lang('admin') . '</strong>';
                                                        } else {
                                                            $disable = false;
                                                            $role = '<strong class="badge btn-primary">' . lang('staff') . '</strong>';
                                                        }

                                                        ?>
                                                        <div class="checkbox c-checkbox needsclick">
                                                            <label class="needsclick">
                                                                <input type="checkbox"
                                                                    <?php
                                                                    if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                                        $get_permission = json_decode($task_info->permission);
                                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                                            if ($user_id == $v_user->user_id) {
                                                                                echo 'checked';
                                                                            }
                                                                        }

                                                                    }
                                                                    ?>
                                                                       value="<?= $v_user->user_id ?>"
                                                                       name="assigned_to[]"
                                                                       class="needsclick">
                                                        <span
                                                            class="fa fa-check"></span><?= $v_user->username . ' ' . $role ?>
                                                            </label>

                                                        </div>
                                                        <div class="action_1 p
                                                <?php

                                                        if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                            $get_permission = json_decode($task_info->permission);

                                                            foreach ($get_permission as $user_id => $v_permission) {
                                                                if ($user_id == $v_user->user_id) {
                                                                    echo 'show';
                                                                }
                                                            }

                                                        }
                                                        ?>
                                                " id="action_1<?= $v_user->user_id ?>">
                                                            <label class="checkbox-inline c-checkbox">
                                                                <input id="<?= $v_user->user_id ?>" checked
                                                                       type="checkbox"
                                                                       name="action_1<?= $v_user->user_id ?>[]"
                                                                       disabled
                                                                       value="view">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('view') ?>
                                                            </label>
                                                            <label class="checkbox-inline c-checkbox">
                                                                <input <?php if (!empty($disable)) {
                                                                    echo 'disabled' . ' ' . 'checked';
                                                                } ?> id="<?= $v_user->user_id ?>"
                                                                    <?php

                                                                    if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                                        $get_permission = json_decode($task_info->permission);

                                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                                            if ($user_id == $v_user->user_id) {
                                                                                if (in_array('edit', $v_permission)) {
                                                                                    echo 'checked';
                                                                                };

                                                                            }
                                                                        }

                                                                    }
                                                                    ?>
                                                                     type="checkbox"
                                                                     value="edit"
                                                                     name="action_<?= $v_user->user_id ?>[]">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('edit') ?>
                                                            </label>
                                                            <label class="checkbox-inline c-checkbox">
                                                                <input <?php if (!empty($disable)) {
                                                                    echo 'disabled' . ' ' . 'checked';
                                                                } ?> id="<?= $v_user->user_id ?>"
                                                                    <?php

                                                                    if (!empty($task_info->permission) && $task_info->permission != 'all') {
                                                                        $get_permission = json_decode($task_info->permission);
                                                                        foreach ($get_permission as $user_id => $v_permission) {
                                                                            if ($user_id == $v_user->user_id) {
                                                                                if (in_array('delete', $v_permission)) {
                                                                                    echo 'checked';
                                                                                };
                                                                            }
                                                                        }

                                                                    }
                                                                    ?>
                                                                     name="action_<?= $v_user->user_id ?>[]"
                                                                     type="checkbox"
                                                                     value="delete">
                                                        <span
                                                            class="fa fa-check"></span><?= lang('can') . ' ' . lang('delete') ?>
                                                            </label>
                                                            <input id="<?= $v_user->user_id ?>" type="hidden"
                                                                   name="action_<?= $v_user->user_id ?>[]" value="view">

                                                        </div>


                                                        <?php
                                                    }
                                                }
                                                ?>


                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="field-1" class="col-sm-3 control-label"></label>
                                            <div class="col-sm-8">
                                                <button type="submit" id="sbtn"
                                                        class="btn btn-primary"><?= lang('save') ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="tab-pane <?= $active == 4 ? 'active' : ''; ?>" id="not_started">

                        <div class="table-responsive">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <?php if (!empty($created) || !empty($edited)) { ?>
                                        <th data-check-all>

                                        </th>
                                    <?php } ?>
                                    <th class="col-sm-3"><?= lang('task_name') ?></th>
                                    <th class="col-sm-2"><?= lang('due_date') ?></th>
                                    <th class="col-sm-1"><?= lang('status') ?></th>
                                    <th class="col-sm-1"><?= lang('progress') ?></th>
                                    <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                    <th class="col-sm-3"><?= lang('changes/view') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                    if ($v_task->task_status == 'not_started') {
                                        $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $v_task->task_id));
                                        $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $v_task->task_id));
                                        ?>
                                        <tr>
                                            <td class="col-sm-1">
                                                <div class="complete checkbox c-checkbox">
                                                    <label>
                                                        <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                                               style="position: absolute;" <?php
                                                        if ($v_task->task_progress >= 100) {
                                                            echo 'checked';
                                                        }
                                                        ?>>
                                                        <span class="fa fa-check"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a style="<?php
                                                if ($v_task->task_progress >= 100) {
                                                    echo 'text-decoration: line-through;';
                                                }
                                                ?>"
                                                   href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                            </td>
                                            <td><?php
                                                $due_date = $v_task->due_date;
                                                $due_time = strtotime($due_date);
                                                $current_time = time();
                                                if ($v_task->task_progress == 100) {
                                                    $c_progress = 100;
                                                } elseif ($v_task->task_status == 'completed') {
                                                    $c_progress = 100;
                                                } else {
                                                    $c_progress = 0;
                                                }
                                                ?>
                                                <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                                <?php if ($current_time > $due_time && $c_progress < 100) { ?>
                                                    <span class="label label-danger"><?= lang('overdue') ?></span>
                                                <?php } ?></td>
                                            <td>
                                                <?php
                                                if ($v_task->task_status == 'completed') {
                                                    $label = 'success';
                                                } elseif ($v_task->task_status == 'not_started') {
                                                    $label = 'info';
                                                } elseif ($v_task->task_status == 'deferred') {
                                                    $label = 'danger';
                                                } else {
                                                    $label = 'warning';
                                                }
                                                ?>
                                                <span
                                                    class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                                            </td>
                                            <td class="col-sm-1" style="padding-bottom: 0px;padding-top: 3px">

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
                                                        <span class="small "><?= $v_task->task_progress ?>
                                                            %</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <?php
                                                if ($v_task->permission != 'all') {
                                                    $get_permission = json_decode($v_task->permission);
                                                    if (!empty($get_permission)) :
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
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i
                                                        title="<?= lang('permission_for_all') ?>"
                                                        class="fa fa-question-circle" data-toggle="tooltip"
                                                        data-placement="top"></i>
                                                    <?php
                                                }
                                                ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <span data-placement="top" data-toggle="tooltip"
                                                          title="<?= lang('add_more') ?>">
                                            <a data-toggle="modal" data-target="#myModal"
                                               href="<?= base_url() ?>admin/tasks/update_users/<?= $v_task->task_id ?>"
                                               class="text-default ml"><i class="fa fa-plus"></i></a>
                                                </span>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <?php if (!empty($can_edit) && !empty($edited)) {
                                                    echo btn_edit('admin/tasks/all_task/' . $v_task->task_id) . ' ';
                                                } ?>
                                                <?php if (!empty($can_delete) && !empty($deleted)) {
                                                    echo btn_delete('admin/tasks/delete_task/' . $v_task->task_id) . ' ';
                                                } ?>
                                                <?php

                                                if ($v_task->timer_status == 'on') { ?>
                                                    <a class="btn btn-xs btn-danger"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?> </a>

                                                <?php } else { ?>
                                                    <a class="btn btn-xs btn-success"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane <?= $active == 3 ? 'active' : ''; ?>" id="archived">

                        <div class="table-responsive">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <?php if (!empty($created) || !empty($edited)) { ?>
                                        <th data-check-all>

                                        </th>
                                    <?php } ?>
                                    <th class="col-sm-3"><?= lang('task_name') ?></th>
                                    <th class="col-sm-2"><?= lang('due_date') ?></th>
                                    <th class="col-sm-1"><?= lang('status') ?></th>
                                    <th class="col-sm-1"><?= lang('progress') ?></th>
                                    <th class="col-sm-2"><?= lang('assigned_to') ?></th>
                                    <th class="col-sm-3"><?= lang('changes/view') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_task_info)):foreach ($all_task_info as $key => $v_task):
                                    if ($v_task->task_status == 'completed') {
                                        $can_edit = $this->tasks_model->can_action('tbl_task', 'edit', array('task_id' => $v_task->task_id));
                                        $can_delete = $this->tasks_model->can_action('tbl_task', 'delete', array('task_id' => $v_task->task_id));
                                        ?>
                                        <tr>
                                            <td class="col-sm-1">
                                                <div class="complete checkbox c-checkbox">
                                                    <label>
                                                        <input type="checkbox" data-id="<?= $v_task->task_id ?>"
                                                               style="position: absolute;" <?php
                                                        if ($v_task->task_progress >= 100) {
                                                            echo 'checked';
                                                        }
                                                        ?>>
                                                        <span class="fa fa-check"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a style="<?php
                                                if ($v_task->task_progress >= 100) {
                                                    echo 'text-decoration: line-through;';
                                                }
                                                ?>"
                                                   href="<?= base_url() ?>admin/tasks/view_task_details/<?= $v_task->task_id ?>"><?php echo $v_task->task_name; ?></a>
                                            </td>
                                            <td><?php
                                                $due_date = $v_task->due_date;
                                                $due_time = strtotime($due_date);
                                                $current_time = time();
                                                if ($v_task->task_progress == 100) {
                                                    $c_progress = 100;
                                                } elseif ($v_task->task_status == 'completed') {
                                                    $c_progress = 100;
                                                } else {
                                                    $c_progress = 0;
                                                }
                                                ?>
                                                <?= strftime(config_item('date_format'), strtotime($due_date)) ?>
                                                <?php if ($current_time > $due_time && $c_progress < 100) { ?>
                                                    <span class="label label-danger"><?= lang('overdue') ?></span>
                                                <?php } ?></td>
                                            <td>
                                                <?php
                                                if ($v_task->task_status == 'completed') {
                                                    $label = 'success';
                                                } elseif ($v_task->task_status == 'not_started') {
                                                    $label = 'info';
                                                } elseif ($v_task->task_status == 'deferred') {
                                                    $label = 'danger';
                                                } else {
                                                    $label = 'warning';
                                                }
                                                ?>
                                                <span
                                                    class="label label-<?= $label ?>"><?= lang($v_task->task_status) ?> </span>
                                            </td>
                                            <td class="col-sm-1" style="padding-bottom: 0px;padding-top: 3px">

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
                                                        <span class="small "><?= $v_task->task_progress ?>
                                                            %</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <?php
                                                if ($v_task->permission != 'all') {
                                                    $get_permission = json_decode($v_task->permission);
                                                    if (!empty($get_permission)) :
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
                                                    <strong><?= lang('everyone') ?></strong>
                                                    <i
                                                        title="<?= lang('permission_for_all') ?>"
                                                        class="fa fa-question-circle" data-toggle="tooltip"
                                                        data-placement="top"></i>
                                                    <?php
                                                }
                                                ?>
                                                <?php if (!empty($can_edit) && !empty($edited)) { ?>
                                                    <span data-placement="top" data-toggle="tooltip"
                                                          title="<?= lang('add_more') ?>">
                                            <a data-toggle="modal" data-target="#myModal"
                                               href="<?= base_url() ?>admin/tasks/update_users/<?= $v_task->task_id ?>"
                                               class="text-default ml"><i class="fa fa-plus"></i></a>
                                                </span>
                                                <?php } ?>
                                            </td>

                                            <td>
                                                <?php if (!empty($can_edit) && !empty($edited)) {
                                                    echo btn_edit('admin/tasks/all_task/' . $v_task->task_id) . ' ';
                                                } ?>
                                                <?php if (!empty($can_delete) && !empty($deleted)) {
                                                    echo btn_delete('admin/tasks/delete_task/' . $v_task->task_id) . ' ';
                                                } ?>
                                                <?php

                                                if ($v_task->timer_status == 'on') { ?>
                                                    <a class="btn btn-xs btn-danger"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/off/<?= $v_task->task_id ?>"><?= lang('stop_timer') ?> </a>

                                                <?php } else { ?>
                                                    <a class="btn btn-xs btn-success"
                                                       href="<?= base_url() ?>admin/tasks/tasks_timer/on/<?= $v_task->task_id ?>"><?= lang('start_timer') ?> </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
