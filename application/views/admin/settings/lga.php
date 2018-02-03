<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php
$created = can_action('122', 'created');
$edited = can_action('122', 'edited');
$deleted = can_action('122', 'deleted');
?>
<div class="panel panel-custom">
    <header class="panel-heading ">LGA Setup</header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                <tr>
                    <th>LGA</th>
                    <th>Code</th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_lga = $this->db->get('tbl_lga')->result();
                if (!empty($all_lga)) {
                    foreach ($all_lga as $lga) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $lga->lga_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/lga/update_lga/<?php
                                      if (!empty($lga_info)) {
                                          echo $lga_info->lga_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="lga" value="<?php
                                    if (!empty($lga_info)) {
                                        echo $lga_info->lga;
                                    }
                                    ?>" class="form-control" placeholder="LGA" required>
                                <?php } else {
                                    echo $lga->lga;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $lga->lga_id) { ?>
                                    <input type="text" name="lga code" class="form-control" value="<?php
                                    if (!empty($lga_info)) {
                                        echo $lga_info->lga_code;
                                    }
                                    ?>"/>
                                <?php } else {
                                    echo $lga->lga_code;
                                }
                                ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $lga->lga_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/lga/') ?>
                                    <?php } else { ?>
                                        <?php if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/lga/edit_lga/' . $lga->lga_id) ?>
                                        <?php } ?>
                                        <?php if (!empty($deleted)) { ?>
                                            <?= btn_delete('admin/settings/delete_lga/' . $lga->lga_id) ?>
                                        <?php }
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                }
                ?>
                <?php if (!empty($created) || !empty($edited)) { ?>
                    <form method="post" action="<?= base_url() ?>admin/settings/lga/update_lga"
                          class="form-horizontal">
                        <tr>
                            <td><input type="text" name="lga" class="form-control"
                                       placeholder="lga" required></td>
                            <td>
                                <input name="lga_code" placeholder=""
                                       class="form-control"/>
                            </td>
                            <td><?= btn_add() ?></td>
                        </tr>
                    </form>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>