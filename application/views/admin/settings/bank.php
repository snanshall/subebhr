<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php
$created = can_action('122', 'created');
$edited = can_action('122', 'edited');
$deleted = can_action('122', 'deleted');
?>
<div class="panel panel-custom">
    <header class="panel-heading ">BANK Setup</header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                <tr>
                    <th>BANK</th>
                    <th>Code</th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_bank = $this->db->get('tbl_bank')->result();
                if (!empty($all_bank)) {
                    foreach ($all_bank as $bank) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $bank->bank_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/bank/update_bank/<?php
                                      if (!empty($bank_info)) {
                                          echo $bank_info->bank_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="bank" value="<?php
                                    if (!empty($bank_info)) {
                                        echo $bank_info->bank;
                                    }
                                    ?>" class="form-control" placeholder="DIVISION" required>
                                <?php } else {
                                    echo $bank->bank;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $bank->bank_id) { ?>
                                    <input type="text" name="bank code" class="form-control" value="<?php
                                    if (!empty($bank_info)) {
                                        echo $bank_info->bank_code;
                                    }
                                    ?>"/>
                                <?php } else {
                                    echo $bank->bank_code;
                                }
                                ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $bank->bank_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/bank/') ?>
                                    <?php } else { ?>
                                        <?php if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/bank/edit_bank/' . $bank->bank_id) ?>
                                        <?php } ?>
                                        <?php if (!empty($deleted)) { ?>
                                            <?= btn_delete('admin/settings/delete_bank/' . $bank->bank_id) ?>
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
                    <form method="post" action="<?= base_url() ?>admin/settings/bank/update_bank"
                          class="form-horizontal">
                        <tr>
                            <td><input type="text" name="bank" class="form-control"
                                       placeholder="bank" required></td>
                            <td>
                                <input name="bank_code" placeholder=""
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