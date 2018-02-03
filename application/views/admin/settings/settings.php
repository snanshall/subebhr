<?= message_box('error'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="col-md-3">
            <ul class="nav nav-pills nav-stacked navbar-custom-nav">
                <?php
                $can_do = can_do(111);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'general') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings">
                            <i class="fa fa-fw fa-info-circle"></i>
                            <?php echo lang('company_details') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(112);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'system') || ($load_setting == 'all_currency') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/system">
                            <i class="fa fa-fw fa-desktop"></i>
                            <?php echo lang('system_settings') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(113);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'email_settings') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/email">
                            <i class="fa fa-fw fa-envelope"></i>
                            <?php echo lang('email_settings') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(114);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'templates') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/templates">
                            <i class="fa fa-fw fa-pencil-square"></i>
                            <?php echo lang('email_templates') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(115);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'email_integration') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/email_integration">
                            <i class="fa fa-fw fa-envelope-o"></i>
                            <?php echo lang('email_integration') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(119);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'tickets') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/tickets">
                            <i class="fa fa-fw fa-ticket"></i>
                            <?php echo lang('tickets') . ' ' . lang('settings') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(120);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'theme') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/theme">
                            <i class="fa fa-fw fa-code"></i>
                            <?php echo lang('theme_settings') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(121);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'working_days') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/working_days">
                            <i class="fa fa-fw fa-calendar"></i>
                            <?php echo lang('working_days') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(122);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'leave_category') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/leave_category">
                            <i class="fa fa-fw fa-pagelines"></i>
                            <?php echo lang('leave_category') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(125);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'customer_group') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/customer_group">
                            <i class="fa fa-fw fa-users"></i>
                            <?php echo lang('customer_group') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(130);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'custom_field') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/custom_field">
                            <i class="fa fa-fw fa-star-o "></i>
                            <?php echo lang('custom_field') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(131);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'payment_method') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/payment_method">
                            <i class="fa fa-fw fa-money"></i>
                            <?php echo lang('payment_method') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(132);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'cronjob') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/cronjob">
                            <i class="fa fa-fw fa-contao"></i>
                            <?php echo lang('cronjob') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(133);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'menu_allocation') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/menu_allocation">
                            <i class="fa fa-fw fa fa-compass"></i>
                            <?php echo lang('menu_allocation') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(134);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'notification') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/notification">
                            <i class="fa fa-fw fa-bell-o"></i>
                            <?php echo lang('notification') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(135);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'email_notification') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/email_notification">
                            <i class="fa fa-fw fa-bell-o"></i>
                            <?php echo lang('email_notification') ?>
                        </a>
                    </li>
                    <?php }
                    $can_do = can_do(122);
                    if (!empty($can_do)) { ?>
                        <li class="<?php echo ($load_setting == 'lga') ? 'active' : ''; ?>">
                            <a href="<?= base_url() ?>admin/settings/lga">
                            <i class="fa fa-fw fa fa-compass"></i>
                                <?php echo 'LGA Setup'; ?>
                            </a>
                        </li>
                    <?php }
                
                $can_do = can_do(122);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'division') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/division">
                            <i class="fa fa-fw fa fa-compass"></i>
                            <?php echo 'Division Setup'; ?>
                        </a>
                    </li>
                <?php }
                
                $can_do = can_do(122);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'union') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/union">
                           <i class="fa fa-fw fa fa-compass"></i>
                            <?php echo 'Union Setup'; ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(131);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'bank') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/bank">
                            <i class="fa fa-fw fa-money"></i>
                            <?php echo 'Banks Setup'; ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(136);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'database_backup') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/database_backup">
                            <i class="fa fa-fw fa-database"></i>
                            <?php echo lang('database_backup') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(137);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'translations') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/translations">
                            <i class="fa fa-fw fa-language"></i>
                            <?php echo lang('translations') ?>
                        </a>
                    </li>
                <?php }
                $can_do = can_do(138);
                if (!empty($can_do)) { ?>
                    <li class="<?php echo ($load_setting == 'system_update') ? 'active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/settings/system_update">
                            <i class="fa fa-fw fa-pencil-square-o"></i>
                            <?php echo lang('system_update') ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>

        <section class="col-sm-9">
            <div class="col-sm-8  ">
                <?php if ($load_setting == 'email') { ?>
                    <div style="margin-bottom: 10px;margin-left: -15px" class="<?php
                    if ($load_setting != 'email') {
                        echo 'hidden';
                    }
                    ?>">
                        <a href="<?= base_url() ?>admin/settings/email&view=alerts" class="btn btn-info"><i
                                class="fa fa fa-inbox text"></i>
                            <span class="text"><?php echo lang('alert_settings') ?></span>
                        </a>
                    </div>
                <?php } ?>

            </div>
            <section class="">
                <!-- Load the settings form in views -->
                <?php $this->load->view('admin/settings/' . $load_setting) ?>
                <!-- End of settings Form -->
            </section>
        </section>
    </div>
</div>
