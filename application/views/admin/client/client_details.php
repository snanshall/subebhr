<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php

$all_tickets_info = $this->client_model->get_permission('tbl_tickets');
$total_tickets = 0;
if (!empty($all_tickets_info)) {
    foreach ($all_tickets_info as $v_tickets_info) {
        if (!empty($v_tickets_info)) {
            $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
            if (!empty($profile_info->company))
                if ($profile_info->company == $client_details->client_id) {
                    $total_tickets += count($v_tickets_info->tickets_id);
                }
        }
    }
}
$client_currency = $this->client_model->client_currency_sambol($client_details->client_id);
if (!empty($client_currency)) {
    $cur = $client_currency->symbol;
} else {
    $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
    $cur = $currency->symbol;
}
$edited = can_action('4', 'edited');
?>
<?php
$url = $this->uri->segment(5);
?>
<div class="row mt-lg">
    <div class="col-sm-3">
        <ul class="nav nav-pills nav-stacked navbar-custom-nav">
            <li class="<?= empty($url) ? 'active' : '' ?>"><a href="#task_details" data-toggle="tab"
                                                              aria-expanded="true"><?= lang('details') ?></a>
            </li>
            <li class="<?= $url == 'add_contacts' ? 'active' : '' ?>"><a href="#contacts"
                                                                         data-toggle="tab"
                                                                         aria-expanded="false"><?= lang('contacts') ?>
                    <strong
                        class="pull-right"><?= (!empty($client_contacts) ? count($client_contacts) : null) ?></strong></a>
            </li>
            <li class=""><a href="#ticket" data-toggle="tab" aria-expanded="false"><?= lang('tickets') ?><strong
                        class="pull-right"><?= (!empty($total_tickets) ? count($total_tickets) : null) ?></strong></a>
            </li>
            <li class="<?= $url == 'map' ? 'active' : '' ?>"><a
                    href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>/map"><?= lang('map') ?>
                    <strong class="pull-right"></strong></a></li>
        </ul>
    </div>
    <div class="col-sm-9">
        <div class="tab-content" style="border: 0;padding:0;">
            <!-- Task Details tab Starts -->
            <div class="tab-pane <?= empty($url) ? 'active' : '' ?> " id="task_details"
                 style="position: relative;">
                <div class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title"><strong><?= $client_details->name ?> - <?= lang('details') ?> </strong>

                        </div>
                    </div>
                    <div class="panel-body">
                        <!-- Details START -->
                        <div class="col-md-6">
                            <div class="group">
                                <h4 class="subdiv text-muted"><?= lang('contact_details') ?></h4>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('name') ?></div>
                                    <div class="col-md-6"><?= $client_details->name ?></div>
                                </div>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('contact_person') ?></div>
                                    <div class="col-md-6">
                                        <?php
                                        if ($client_details->primary_contact != 0) {
                                            $contacts = $client_details->primary_contact;
                                        } else {
                                            $contacts = NULL;
                                        }
                                        $primary_contact = $this->client_model->check_by(array('account_details_id' => $contacts), 'tbl_account_details');
                                        if ($primary_contact) {
                                            echo $primary_contact->fullname;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="row inline-fields">
                                    <div class="col-md-4"><?= lang('email') ?></div>
                                    <div class="col-md-6"><?= $client_details->email ?></div>
                                </div>
                            </div>

                            <div class="row inline-fields">
                                <div class="col-md-4"><?= lang('city') ?></div>
                                <div class="col-md-6"><?= $client_details->city ?></div>
                            </div>
                            <div class="row inline-fields">
                                <div class="col-md-4"><?= lang('country') ?></div>
                                <div class="col-md-6 text-success"><?= $client_details->country ?></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-lg">
                            <div class="group">
                                <div class="row" style="margin-top: 5px">
                                    <div class="rec-pay col-md-12">
                                        <h4 class="subdiv text-muted"><?= lang('received_amount') ?></h4>

                                        <div class="row inline-fields">
                                            <div class="col-md-4"><?= lang('address') ?></div>
                                            <div class="col-md-6"><?= $client_details->address ?></div>
                                        </div>
                                        <div class="row inline-fields">
                                            <div class="col-md-4"><?= lang('phone') ?></div>
                                            <div class="col-md-6"><a
                                                    href="tel:<?= $client_details->phone ?>"><?= $client_details->phone ?></a>
                                            </div>
                                        </div>
                                        <div class="row inline-fields">
                                            <div class="col-md-4"><?= lang('website') ?></div>
                                            <div class="col-md-6"><a href="<?= $client_details->website ?>"
                                                                     class="text-info"
                                                                     target="_blank"><?= $client_details->website ?></a>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Details END -->
                    </div>
                    <div class="panel-footer">

                    </div>
                </div>
            </div>
            <!--            *************** contact tab start ************-->
            <div class="tab-pane <?= $url == 'add_contacts' ? 'active' : '' ?>" id="contacts"
                 style="position: relative;">
                <?php if (!empty($company)): ?>
                    <?php include_once 'asset/admin-ajax.php'; ?>
                    <?php
                    $eeror_message = $this->session->userdata('error');

                    if (!empty($eeror_message)):foreach ($eeror_message as $key => $message):
                        ?>
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                        <?php
                    endforeach;
                    endif;
                    $this->session->unset_userdata('error');
                    $edited = can_action('4', 'edited');
                    if (!empty($edited)) {
                        ?>
                        <form role="form" enctype="multipart/form-data" id="form"
                              action="<?php echo base_url(); ?>admin/client/save_contact/<?php
                              if (!empty($account_details)) {
                                  echo $account_details->user_id;
                              }
                              ?>" method="post" class="form-horizontal  ">

                            <div class="panel panel-custom">
                                <!-- Default panel contents -->
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <?= lang('add_contact') ?>.
                                        <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>"
                                           class="btn-sm pull-right">Return to Details</a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="col-sm-8">
                                        <input type="hidden" name="r_url"
                                               value="<?= base_url() ?>admin/client/client_details/<?= $company ?>">
                                        <input type="hidden" name="company" value="<?= $company ?>">
                                        <input type="hidden" name="role_id" value="2">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('full_name') ?> <span
                                                    class="text-danger"> *</span></label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->fullname;
                                                }
                                                ?>" placeholder="E.g John Doe" name="fullname" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('email') ?><span
                                                    class="text-danger"> *</span></label>
                                            <div class="col-lg-7">
                                                <input class="form-control" id='email' type="email" value="<?php
                                                if (!empty($user_info)) {
                                                    echo $user_info->email;
                                                }
                                                ?>" placeholder="me@domin.com" name="email" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('phone') ?> </label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->phone;
                                                }
                                                ?>" name="phone" placeholder="+52 782 983 434">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('mobile') ?> <span
                                                    class="text-danger"> *</span></label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->mobile;
                                                }
                                                ?>" name="mobile" placeholder="+8801723611125">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('skype_id') ?> </label>
                                            <div class="col-lg-7">
                                                <input type="text" class="form-control" value="<?php
                                                if (!empty($account_details)) {
                                                    echo $account_details->skype;
                                                }
                                                ?>" name="skype" placeholder="john">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('language') ?></label>
                                            <div class="col-lg-7">
                                                <select name="language" class="form-control">
                                                    <?php foreach ($languages as $lang) : ?>
                                                        <option value="<?= $lang->name ?>"<?php
                                                        if (!empty($account_details->language) && $account_details->language == $lang->name) {
                                                            echo 'selected="selected"';
                                                        } else {
                                                            echo($this->config->item('language') == $lang->name ? ' selected="selected"' : '');
                                                        }
                                                        ?>><?= ucfirst($lang->name) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label"><?= lang('locale') ?></label>
                                            <div class="col-lg-7">
                                                <select class="  form-control" name="locale">
                                                    <?php foreach ($locales as $loc) : ?>
                                                        <option lang="<?= $loc->code ?>"
                                                                value="<?= $loc->locale ?>"<?= ($this->config->item('locale') == $loc->locale ? ' selected="selected"' : '') ?>><?= $loc->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if (empty($account_details)): ?>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label"><?= lang('username') ?> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-lg-7">
                                                    <input class="form-control" id='username' type="text"
                                                           value="<?= set_value('username') ?>"
                                                           onchange="check_user_name(this.value)" placeholder="johndoe"
                                                           name="username" required>
                                                    <div class="required" id="username_result"></div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label"><?= lang('password') ?> <span
                                                        class="text-danger"> *</span></label>
                                                <div class="col-lg-7">
                                                    <input type="password" class="form-control" id="password"
                                                           value="<?= set_value('password') ?>" name="password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-lg-4 control-label"><?= lang('confirm_password') ?>
                                                    <span
                                                        class="text-danger"> *</span></label>
                                                <div class="col-lg-7">
                                                    <input type="password" class="form-control"
                                                           value="<?= set_value('confirm_password') ?>"
                                                           name="confirm_password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label
                                                    class="col-lg-4 control-label"><?= lang('send_email') . ' ' . lang('password') ?></label>
                                                <div class="col-lg-6">
                                                    <div class="checkbox c-checkbox">
                                                        <label class="needsclick">
                                                            <input type="checkbox" name="send_email_password">
                                                            <span class="fa fa-check"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="panel ">
                                            <div class="panel-title">
                                                <strong><?= lang('permission') ?></strong>
                                            </div>
                                        </div>
                                        <?php
                                        $all_client_menu = $this->db->where('parent', 0)->order_by('sort')->get('tbl_client_menu')->result();
                                        if (!empty($user_info)) {
                                            $user_menu = $this->db->where('user_id', $user_info->user_id)->get('tbl_client_role')->result();
                                        }
                                        foreach ($all_client_menu as $key => $v_menu) {
                                            if ($v_menu->label != 'dashboard') {
                                                ?>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-6 control-label"><?= lang($v_menu->label) ?></label>
                                                    <div class="col-lg-5 checkbox">
                                                        <input data-id="" data-toggle="toggle"
                                                               name="<?= $v_menu->label ?>"
                                                               value="<?= $v_menu->menu_id ?>" <?php
                                                        if (!empty($user_menu)) {
                                                            foreach ($user_menu as $v_u_menu) {
                                                                if ($v_u_menu->menu_id == $v_menu->menu_id) {
                                                                    echo 'checked';
                                                                }
                                                            }
                                                        } ?> data-on="<?= lang('yes') ?>" data-off="<?= lang('no') ?>"
                                                               data-onstyle="success btn-xs"
                                                               data-offstyle="danger btn-xs" type="checkbox">
                                                    </div>
                                                </div>
                                            <?php } else {
                                                ?>
                                                <input name="<?= $v_menu->label ?>"
                                                       value="<?= $v_menu->menu_id ?>" <?php echo 'checked'; ?>
                                                       type="hidden">
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label"></label>
                                    <div class="col-lg-4">
                                        <button type="submit" id="sbtn"
                                                class="btn btn-primary btn-block"><?= lang('save') . ' ' . lang('client_contact') ?></button>
                                    </div>

                                </div>
                            </div>
                        </form>
                    <?php } ?>
                <?php else: ?>
                    <section class="panel panel-custom">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <strong><?= lang('contacts') ?></strong>
                                <?php
                                $edited = can_action('4', 'edited');
                                if (!empty($edited)) {
                                    ?>
                                    <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>/add_contacts"
                                       class="btn-sm pull-right"><?= lang('add_contact') ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?= lang('full_name') ?></th>
                                    <th><?= lang('email') ?></th>
                                    <th><?= lang('phone') ?> </th>
                                    <th><?= lang('mobile') ?> </th>
                                    <th><?= lang('skype_id') ?></th>
                                    <th class="col-date"><?= lang('last_login') ?> </th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($client_contacts)) {
                                    foreach ($client_contacts as $key => $contact) {
                                        ?>
                                        <tr>
                                            <td><?= $contact->fullname ?></td>
                                            <td class="text-info"><?= $contact->email ?> </td>
                                            <td><a href="tel:<?= $contact->phone ?>"><?= $contact->phone ?></a></td>
                                            <td><a href="tel:<?= $contact->mobile ?>"><?= $contact->mobile ?></a></td>
                                            <td><a href="skype:<?= $contact->skype ?>?call"><?= $contact->skype ?></a>
                                            </td>
                                            <?php
                                            if ($contact->last_login == '0000-00-00 00:00:00') {
                                                $login_time = "-";
                                            } else {
                                                $login_time = strftime(config_item('date_format') . " %H:%M:%S", strtotime($contact->last_login));
                                            }
                                            ?>
                                            <td><?= $login_time ?> </td>
                                            <td>
                                                <a href="<?= base_url() ?>admin/client/make_primary/<?= $contact->user_id ?>/<?= $client_details->client_id ?>"
                                                   data-toggle="tooltip" class="btn <?php
                                                if ($client_details->primary_contact == $contact->user_id) {
                                                    echo "btn-success";
                                                } else {
                                                    echo "btn-default";
                                                }
                                                ?> btn-xs " title="<?= lang('primary_contact') ?>">
                                                    <i class="fa fa-chain"></i> </a>
                                                <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id . '/add_contacts/' . $contact->user_id ?>"
                                                   class="btn btn-primary btn-xs" title="<?= lang('edit') ?>">
                                                    <i class="fa fa-edit"></i> </a>
                                                <a href="<?= base_url() ?>admin/client/delete_contacts/<?= $client_details->client_id . '/' . $contact->user_id ?>"
                                                   class="btn btn-danger btn-xs" title="<?= lang('delete') ?>">
                                                    <i class="fa fa-trash-o"></i> </a>
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
                <?php endif ?>

            </div>
            <!--            *************** Tickets tab start ************-->
            <div class="tab-pane" id="ticket" style="position: relative;">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?= lang('tickets') ?>
                            <a href="<?= base_url() ?>admin/tickets/index/edit_tickets/"
                               class="btn-sm pull-right"><?= lang('new_ticket') ?></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th><?= lang('subject') ?></th>
                                    <th class="col-date"><?= lang('date') ?></th>
                                    <?php if ($this->session->userdata('user_type') == '1') { ?>
                                        <th><?= lang('reporter') ?></th>
                                    <?php } ?>
                                    <th><?= lang('status') ?></th>
                                    <th><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                if (!empty($all_tickets_info)) {
                                    foreach ($all_tickets_info as $v_tickets_info) {
                                        $profile_info = $this->db->where(array('user_id' => $v_tickets_info->reporter))->get('tbl_account_details')->row();
                                        if (!empty($profile_info->company)) {
                                            if ($profile_info->company == $client_details->client_id) {
                                                if ($v_tickets_info->status == 'open') {
                                                    $s_label = 'danger';
                                                } elseif ($v_tickets_info->status == 'closed') {
                                                    $s_label = 'success';
                                                } else {
                                                    $s_label = 'default';
                                                }
                                                ?>
                                                <tr>
                                                    <td><a class="text-info"
                                                           href="<?= base_url() ?>admin/tickets/index/tickets_details/<?= $v_tickets_info->tickets_id ?>"><?= $v_tickets_info->subject ?></a>
                                                    </td>
                                                    <td><?= strftime(config_item('date_format'), strtotime($v_tickets_info->created)); ?></td>
                                                    <?php if ($this->session->userdata('user_type') == '1') { ?>

                                                        <td>
                                                            <a class="pull-left recect_task  ">
                                                                <?php if (!empty($profile_info)) {
                                                                    ?>
                                                                    <img style="width: 30px;margin-left: 18px;
                                                         height: 29px;
                                                         border: 1px solid #aaa;"
                                                                         src="<?= base_url() . $profile_info->avatar ?>"
                                                                         class="img-circle">
                                                                <?php } ?>

                                                                <?=
                                                                ($profile_info->fullname)
                                                                ?>
                                                            </a>
                                                        </td>

                                                    <?php } ?>
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
                                                    <td>
                                                        <?= btn_edit('admin/tickets/index/edit_tickets/' . $v_tickets_info->tickets_id) ?>
                                                        <?= btn_delete('admin/tickets/delete/delete_tickets/' . $v_tickets_info->tickets_id) ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
            <!--            *************** invoice tab start ************-->
            <div class="tab-pane <?= $url == 'map' ? 'active' : '' ?>" id="client_map">
                <section class="panel panel-custom">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <?= lang('map') ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <style type="text/css">
                            .client_map {
                                height: 500px;
                            }
                        </style>
                        <?php
                        $google_api_key = config_item('google_api_key');
                        if ($google_api_key !== '') {
                            if ($client_details->longitude == '' && $client_details->latitude == '') {
                                echo lang('map_notice');
                            } else {
                                echo '<div id="map" class="client_map"></div>';
                            } ?>
                            <script>
                                var latitude = '<?= $client_details->latitude?>';
                                var longitude = '<?= $client_details->longitude?>';
                                var marker = '<?= $client_details->name?>';
                            </script>
                            <script src="<?= base_url() ?>assets/plugins/map/map.js"></script>
                            <script async defer
                                    src="https://maps.googleapis.com/maps/api/js?key=<?= $google_api_key ?>&callback=initMap"></script>

                        <?php } else {
                            echo lang('setup_google_api_key_map');
                        }
                        ?>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

