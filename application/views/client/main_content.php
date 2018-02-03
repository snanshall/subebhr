<link href="<?php echo base_url() ?>asset/css/fullcalendar.css" rel="stylesheet" type="text/css">
<style type="text/css">
    .datepicker {
        z-index: 1151 !important;
    }

    .easypiechart {
        margin: 0px auto;
    }
</style>
<?php echo message_box('success');

$user_id = $this->session->userdata('user_id');

$client_id = $this->session->userdata('client_id');
?>

<div class="row mt-lg">

    <div class="col-md-6">

        <div class="panel panel-custom" style="height: 437px;overflow-y: scroll;">
            <header class="panel-heading mb0">
                <h3 class="panel-title"><?= lang('announcements') ?></h3>
            </header>

            <?php
            $all_announcements = $this->db->where('all_client', '1')->get('tbl_announcements')->result();

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
                            <a href="<?php echo base_url() ?>client/dashboard/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
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
                                                <a href="<?php echo base_url() ?>client/dashboard/announcements_details/<?php echo $v_announcements->announcements_id; ?>"
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
    <div class="col-sm-6 ">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <h3 class="panel-title"><?= lang('recent') . ' ' . lang('tickets') ?></h3>
            </div>
            <div class="panel-body">
                <section class="comment-list block">
                    <section class="slim-scroll" style="height:400px;overflow-x: scroll">
                        <?php
                        $all_tickets_info = $this->db
                            ->where('reporter', $user_id)
                            ->order_by('created', 'desc')
                            ->get('tbl_tickets', 10)
                            ->result();
                        ?>
                        <div class="table-responsive">
                            <table class="table table-striped " cellspacing="0" width="100%">
                                <thead>
                                <tr>

                                    <th><?= lang('ticket_code') ?></th>
                                    <th class="col-date"><?= lang('date') ?></th>
                                    <th><?= lang('department') ?></th>
                                    <th><?= lang('status') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_tickets_info)) {
                                    foreach ($all_tickets_info as $v_tickets_info) {
                                        if ($v_tickets_info->status == 'open') {
                                            $s_label = 'danger';
                                        } elseif ($v_tickets_info->status == 'closed') {
                                            $s_label = 'success';
                                        } else {
                                            $s_label = 'default';
                                        }
                                        $dept_info = $this->db->where(array('departments_id' => $v_tickets_info->departments_id))->get('tbl_departments')->row();
                                        if (!empty($dept_info)) {
                                            $dept_name = $dept_info->deptname;
                                        } else {
                                            $dept_name = '-';
                                        }
                                        ?>
                                        <tr>

                                            <td><a class="text-info"
                                                   href="<?= base_url() ?>client/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><span
                                                        class="label label-success"><?= $v_tickets_info->ticket_code ?></span></a>
                                            </td>

                                            <td><?= strftime(config_item('date_format'), strtotime($v_tickets_info->created)); ?></td>
                                            <td><?= $dept_name ?></td>
                                            <?php
                                            if ($v_tickets_info->status == 'in_progress') {
                                                $status = 'In Progress';
                                            } else {
                                                $status = $v_tickets_info->status;
                                            }
                                            ?>
                                            <td><span
                                                    class="label label-<?= $s_label ?>"><?= ucfirst($status) ?></span>
                                            </td>

                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </section>
            </div>
        </div>
    </div>

</div>
<div class="row mt-lg">
    <div class="col-sm-6 ">
        <div class="panel panel-custom">
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
    <div class="col-sm-6 ">
        <div class="panel panel-custom">
            <div class="panel-heading">
                <h3 class="panel-title"><?= lang('recent_activities') ?></h3>
            </div>
            <div class="panel-body">
                <section class="comment-list block">
                    <section class="slim-scroll" style="height:400px;overflow-x: scroll">
                        <?php
                        $activities = $this->db
                            ->where('user', $user_id)
                            ->order_by('activity_date', 'desc')
                            ->get('tbl_activities', 50)
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
                                            <?php echo sprintf(lang($v_activities->activity) . ' <strong style="color:#000"> <em>' . $v_activities->value1 . '</em>' . '<em>' . $v_activities->value2 . '</em></strong>'); ?>
                                        </div>
                                        <hr/>
                                    </section>
                                </article>


                                <?php
                            }
                        }
                        ?>
                    </section>
                </section>
            </div>
        </div>
    </div>
</div>
