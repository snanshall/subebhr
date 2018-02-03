<?php echo message_box('success'); ?>
<?php echo message_box('error'); ?>
<?php
$all_customer_group = $this->db->order_by('customer_group_id', 'DESC')->get('tbl_customer_group')->result();

$id = $this->uri->segment(5);
$search_by = $this->uri->segment(4);
$created = can_action('4', 'created');
$edited = can_action('4', 'edited');
$deleted = can_action('4', 'deleted');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="btn-group pull-right btn-with-tooltip-group _filter_data" data-toggle="tooltip"
             data-title="<?php echo lang('filter_by'); ?>">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-filter" aria-hidden="true"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                <li class="<?php
                if (empty($search_by)) {
                    echo 'active';
                } ?>"><a
                        href="<?= base_url() ?>admin/client/manage_client"><?php echo lang('all'); ?></a>
                </li>
                <li class="divider"></li>
                <?php if (count($all_customer_group) > 0) { ?>
                    <li class="dropdown-submenu pull-left groups <?php if (!empty($id)) {
                        if ($search_by == 'group') {
                            echo 'active';
                        }
                    } ?>">
                        <a href="#" tabindex="-1"><?php echo lang('customer_group'); ?></a>
                        <ul class="dropdown-menu dropdown-menu-left">
                            <?php foreach ($all_customer_group as $group) {
                                ?>
                                <li class="<?php if (!empty($id)) {
                                    if ($search_by == 'group') {
                                        if ($id == $group->customer_group_id) {
                                            echo 'active';
                                        }
                                    }
                                } ?>">
                                    <a href="<?= base_url() ?>admin/client/manage_client/group/<?php echo $group->customer_group_id; ?>"><?php echo $group->customer_group; ?></a>
                                </li>
                            <?php }
                            ?>
                        </ul>
                    </li>
                    <div class="clearfix"></div>
                    <li class="divider"></li>
                <?php } ?>
            </ul>
        </div>
        <?php if (!empty($created) || !empty($edited)){ ?>
        <div class="nav-tabs-custom">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs">
                <li class="<?= $active == 1 ? 'active' : '' ?>"><a href="#client_list"
                                                                   data-toggle="tab"><?= lang('client_list') ?></a></li>
                <li class="<?= $active == 2 ? 'active' : '' ?>"><a href="#new_client"
                                                                   data-toggle="tab"><?= lang('new_client') ?></a></li>
                <li><a style="background-color: #1797be;color: #ffffff"
                       href="<?= base_url() ?>admin/client/import"><?= lang('import') . ' ' . lang('client') ?></a>
                </li>
            </ul>
            <div class="tab-content bg-white">
                <!-- Stock Category List tab Starts -->
                <div class="tab-pane <?= $active == 1 ? 'active' : '' ?>" id="client_list" style="position: relative;">
                    <?php } else { ?>
                    <div class="panel panel-custom">
                        <header class="panel-heading ">
                            <div class="panel-title"><strong><?= lang('client_list') ?></strong></div>
                        </header>
                        <?php } ?>
                        <div class="box">
                            <table class="table table-striped DataTables " id="DataTables" cellspacing="0" width="100%">
                                <thead>
                                <tr>

                                    <th><?= lang('name') ?> </th>
                                    <th><?= lang('contacts') ?></th>
                                    <th class="hidden-sm"><?= lang('primary_contact') ?></th>
                                    <th><?= lang('group') ?> </th>
                                    <th class="hidden-print"><?= lang('action') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($all_client_info)) {
                                    foreach ($all_client_info as $client_details) {
                                        $customer_group = $this->db->where('customer_group_id', $client_details->customer_group_id)->get('tbl_customer_group')->row();
                                        $client_currency = $this->client_model->client_currency_sambol($client_details->client_id);
                                        if (!empty($client_currency)) {
                                            $cur = $client_currency->symbol;
                                        } else {
                                            $currency = $this->db->where(array('code' => config_item('default_currency')))->get('tbl_currencies')->row();
                                            $cur = $currency->symbol;
                                        }
                                        ?>
                                        <tr>
                                            <td>
                                                <a href="<?= base_url() ?>admin/client/client_details/<?= $client_details->client_id ?>"
                                                   class="text-info">
                                                    <?= $client_details->name ?></a></td>
                                            <td><span class="label label-success" data-toggle="tooltip"
                                                      data-palcement="top"
                                                      title="<?= lang('contacts') ?>"><?= $this->client_model->count_rows('tbl_account_details', array('company' => $client_details->client_id)) ?></span>
                                            </td>
                                            <td class="hidden-sm"><?php
                                                if ($client_details->primary_contact != 0) {
                                                    $contacts = $client_details->primary_contact;
                                                } else {
                                                    $contacts = NULL;
                                                }
                                                $primary_contact = $this->client_model->check_by(array('account_details_id' => $contacts), 'tbl_account_details');
                                                if ($primary_contact) {
                                                    echo $primary_contact->fullname;
                                                }
                                                ?></td>
                                            <td><span class="label label-default"><?php
                                                    if (!empty($customer_group->customer_group)) {
                                                        echo $customer_group->customer_group;
                                                    }
                                                    ?></span></td>
                                            <td>
                                                <?php if (!empty($edited)) { ?>
                                                    <?php echo btn_edit('admin/client/manage_client/' . $client_details->client_id) ?>
                                                <?php }
                                                if (!empty($deleted)) {
                                                    ?>
                                                    <?php echo btn_delete('admin/client/delete_client/' . $client_details->client_id) ?>
                                                <?php } ?>
                                                <?php echo btn_view('admin/client/client_details/' . $client_details->client_id) ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="7">
                                            <?= lang('no_data') ?>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if (!empty($created) || !empty($edited)) { ?>
                        <div class="tab-pane <?= $active == 2 ? 'active' : '' ?>" id="new_client"
                        style="position: relative;">
                        <form role="form" enctype="multipart/form-data" id="form"
                              action="<?php echo base_url(); ?>admin/client/save_client/<?php
                              if (!empty($client_info)) {
                                  echo $client_info->client_id;
                              }
                              ?>" method="post" class="form-horizontal  ">
                            <div class="panel-body">
                                <label class="control-label col-sm-3"></label
                                <div class="col-sm-6">
                                    <div class="nav-tabs-custom">
                                        <!-- Tabs within a box -->
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a href="#general_compnay"
                                                                  data-toggle="tab"><?= lang('general') ?></a>
                                            </li>
                                            <li><a href="#contact_compnay"
                                                   data-toggle="tab"><?= lang('client_contact') . ' ' . lang('details') ?></a>
                                            </li>
                                            <li><a href="#web_compnay" data-toggle="tab"><?= lang('web') ?></a></li>
                                            <li><a href="#hosting_compnay" data-toggle="tab"><?= lang('hosting') ?></a>
                                            </li>
                                        </ul>
                                        <div class="tab-content bg-white">
                                            <!-- ************** general *************-->
                                            <div class="chart tab-pane active" id="general_compnay">
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label"><?= lang('company_name') ?>
                                                        <span
                                                            class="text-danger"> *</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" required=""
                                                               value="<?php
                                                               if (!empty($client_info->name)) {
                                                                   echo $client_info->name;
                                                               }
                                                               ?>" name="name">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label"><?= lang('company_email') ?>
                                                        <span
                                                            class="text-danger"> *</span></label>
                                                    <div class="col-lg-5">
                                                        <input type="email" class="form-control" required=""
                                                               value="<?php
                                                               if (!empty($client_info->email)) {
                                                                   echo $client_info->email;
                                                               }
                                                               ?>" name="email">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_vat') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->vat)) {
                                                            echo $client_info->vat;
                                                        }
                                                        ?>" name="vat">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-sm-3 control-label"><?= lang('customer_group') ?></label>
                                                    <div class="col-sm-5">
                                                        <select name="customer_group_id" class="form-control select_box"
                                                                style="width: 100%">
                                                            <?php
                                                            if (!empty($all_customer_group)) {
                                                                foreach ($all_customer_group as $customer_group) : ?>
                                                                    <option
                                                                        value="<?= $customer_group->customer_group_id ?>"<?php
                                                                    if (!empty($client_info->customer_group_id) && $client_info->customer_group_id == $customer_group->customer_group_id) {
                                                                        echo 'selected';
                                                                    } ?>
                                                                    ><?= $customer_group->customer_group; ?></option>
                                                                <?php endforeach;
                                                            } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-sm-3 control-label"><?= lang('language') ?></label>
                                                    <div class="col-sm-5">
                                                        <select name="language" class="form-control select_box"
                                                                style="width: 100%">
                                                            <?php

                                                            foreach ($languages as $lang) : ?>
                                                                <option
                                                                    value="<?= $lang->name ?>"<?php
                                                                if (!empty($client_info->language) && $client_info->language == $lang->name) {
                                                                    echo 'selected';
                                                                } elseif (empty($client_info->language) && $this->config->item('language') == $lang->name) {
                                                                    echo 'selected';
                                                                } ?>
                                                                ><?= ucfirst($lang->name) ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('currency') ?></label>
                                                    <div class="col-lg-5">
                                                        <select name="currency" class="form-control select_box"
                                                                style="width: 100%">

                                                            <?php if (!empty($currencies)): foreach ($currencies as $currency): ?>
                                                                <option
                                                                    value="<?= $currency->code ?>"
                                                                    <?php
                                                                    if (!empty($client_info->currency) && $client_info->currency == $currency->code) {
                                                                        echo 'selected';
                                                                    } elseif (empty($client_info->currency) && $this->config->item('default_currency') == $currency->code) {
                                                                        echo 'selected';
                                                                    } ?>
                                                                ><?= $currency->name ?></option>
                                                                <?php
                                                            endforeach;
                                                            endif;
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('short_note') ?></label>
                                                    <div class="col-lg-5">
                                            <textarea class="form-control" name="short_note"><?php
                                                if (!empty($client_info->short_note)) {
                                                    echo $client_info->short_note;
                                                }
                                                ?></textarea>
                                                    </div>
                                                </div>
                                            </div><!-- ************** general *************-->

                                            <!-- ************** Contact *************-->
                                            <div class="chart tab-pane" id="contact_compnay">
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_phone') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->phone)) {
                                                            echo $client_info->phone;
                                                        }
                                                        ?>" name="phone">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_mobile') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control"
                                                               value="<?php
                                                               if (!empty($client_info->mobile)) {
                                                                   echo $client_info->mobile;
                                                               }
                                                               ?>" name="mobile">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_fax') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->fax)) {
                                                            echo $client_info->fax;
                                                        }
                                                        ?>" name="fax">
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_city') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->city)) {
                                                            echo $client_info->city;
                                                        }
                                                        ?>" name="city">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_country') ?></label>
                                                    <div class="col-lg-5">
                                                        <select name="country" class="form-control select_box"
                                                                style="width: 100%">
                                                            <optgroup label="Default Country">
                                                                <option
                                                                    value="<?= $this->config->item('company_country') ?>"><?= $this->config->item('company_country') ?></option>
                                                            </optgroup>
                                                            <optgroup label="<?= lang('other_countries') ?>">
                                                                <?php if (!empty($countries)): foreach ($countries as $country): ?>
                                                                    <option
                                                                        value="<?= $country->value ?>" <?= (!empty($client_info->country) && $client_info->country == $country->value ? 'selected' : NULL) ?>><?= $country->value ?>
                                                                    </option>
                                                                    <?php
                                                                endforeach;
                                                                endif;
                                                                ?>
                                                            </optgroup>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_address') ?></label>
                                                    <div class="col-lg-5">
                                            <textarea class="form-control" name="address"><?php
                                                if (!empty($client_info->address)) {
                                                    echo $client_info->address;
                                                }
                                                ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label">
                                                        <a href="#"
                                                           onclick="fetch_lat_long_from_google_cprofile(); return false;"
                                                           data-toggle="tooltip"
                                                           data-title="<?php echo lang('fetch_from_google') . ' - ' . lang('customer_fetch_lat_lng_usage'); ?>"><i
                                                                id="gmaps-search-icon" class="fa fa-google"
                                                                aria-hidden="true"></i></a>
                                                        <?= lang('latitude') . '( ' . lang('google_map') . ' )' ?>
                                                    </label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->latitude)) {
                                                            echo $client_info->latitude;
                                                        }
                                                        ?>" name="latitude">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('longitude') . '( ' . lang('google_map') . ' )' ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control"
                                                               value="<?php
                                                               if (!empty($client_info->longitude)) {
                                                                   echo $client_info->longitude;
                                                               }
                                                               ?>" name="longitude">
                                                    </div>
                                                </div>
                                            </div><!-- ************** Contact *************-->
                                            <!-- ************** Web *************-->
                                            <div class="chart tab-pane" id="web_compnay">
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('company_domain') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->website)) {
                                                            echo $client_info->website;
                                                        }
                                                        ?>" name="website">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('skype_id') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->skype_id)) {
                                                            echo $client_info->skype_id;
                                                        }
                                                        ?>" name="skype_id">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('facebook_profile_link') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->facebook)) {
                                                            echo $client_info->facebook;
                                                        }
                                                        ?>" name="facebook">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('twitter_profile_link') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->twitter)) {
                                                            echo $client_info->twitter;
                                                        }
                                                        ?>" name="twitter">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('linkedin_profile_link') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->linkedin)) {
                                                            echo $client_info->linkedin;
                                                        }
                                                        ?>" name="linkedin">
                                                    </div>
                                                </div>
                                            </div><!-- ************** Web *************-->
                                            <!-- ************** Hosting *************-->
                                            <div class="chart tab-pane" id="hosting_compnay">
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('hosting_company') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->hosting_company)) {
                                                            echo $client_info->hosting_company;
                                                        }
                                                        ?>" name="hosting_company">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('hostname') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->hostname)) {
                                                            echo $client_info->hostname;
                                                        }
                                                        ?>" name="hostname">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('username') ?> </label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->username)) {
                                                            echo $client_info->username;
                                                        }
                                                        ?>" name="username">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        class="col-lg-3 control-label"><?= lang('password') ?></label>
                                                    <div class="col-lg-5">
                                                        <?php
                                                        if (!empty($client_info->password)) {
                                                            $password = strlen($client_info->password);
                                                        }
                                                        ?>
                                                        <input type="password" name="password" value="<?php
                                                        if (!empty($password)) {
                                                            for ($p = 1; $p <= $password; $p++) {
                                                                echo '*';
                                                            }
                                                        }
                                                        ?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-3 control-label"><?= lang('port') ?></label>
                                                    <div class="col-lg-5">
                                                        <input type="text" class="form-control" value="<?php
                                                        if (!empty($client_info->port)) {
                                                            echo $client_info->port;
                                                        }
                                                        ?>" name="port">
                                                    </div>
                                                </div>
                                            </div><!-- ************** Hosting *************-->
                                        </div>
                                    </div><!-- /.nav-tabs-custom -->
                                    <div class="form-group mt">
                                        <label class="col-lg-3"></label>
                                        <div class="col-lg-1">
                                            <button type="submit"
                                                    class="btn btn-sm btn-primary"><?= lang('save') ?></button>
                                        </div>
                                        <div class="col-lg-3">
                                            <button type="submit" name="save_and_create_contact" value="1"
                                                    class="btn btn-sm btn-primary"><?= lang('save_and_create_contact') ?></button>
                                        </div>

                                    </div>
                                </div>
                        </form>
                    <?php } else { ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function fetch_lat_long_from_google_cprofile() {
        var data = {};
        data.address = $('textarea[name="address"]').val();
        data.city = $('input[name="city"]').val();
        data.country = $('select[name="country"] option:selected').text();
        console.log(data);
        $('#gmaps-search-icon').removeClass('fa-google').addClass('fa-spinner fa-spin');
        $.post('<?= base_url()?>admin/global_controller/fetch_address_info_gmaps', data).done(function (data) {
            data = JSON.parse(data);
            $('#gmaps-search-icon').removeClass('fa-spinner fa-spin').addClass('fa-google');
            if (data.response.status == 'OK') {
                $('input[name="latitude"]').val(data.lat);
                $('input[name="longitude"]').val(data.lng);
            } else {
                if (data.response.status == 'ZERO_RESULTS') {
                    toastr.warning("<?php echo lang('g_search_address_not_found'); ?>");
                } else {
                    toastr.warning(data.response.status);
                }
            }
        });
    }
</script>