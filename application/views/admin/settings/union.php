<?= message_box('success'); ?>
<?= message_box('error'); ?>
<?php
$created = can_action('122', 'created');
$edited = can_action('122', 'edited');
$deleted = can_action('122', 'deleted');
?>
<div class="panel panel-custom">
    <header class="panel-heading ">UNION Setup</header>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped ">
                <thead>
                <tr>
                    <th>UNION</th>
                    <th>Code</th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_union = $this->db->get('tbl_union')->result();
                if (!empty($all_union)) {
                    foreach ($all_union as $union) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $union->union_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/union/update_union/<?php
                                      if (!empty($union_info)) {
                                          echo $union_info->union_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="union" value="<?php
                                    if (!empty($union_info)) {
                                        echo $union_info->union;
                                    }
                                    ?>" class="form-control" placeholder="DIVISION" required>
                                <?php } else {
                                    echo $union->union;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $union->union_id) { ?>
                                    <input type="text" name="union code" class="form-control" value="<?php
                                    if (!empty($union_info)) {
                                        echo $union_info->union_code;
                                    }
                                    ?>"/>
                                <?php } else {
                                    echo $union->union_code;
                                }
                                ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $union->union_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/union/') ?>
                                    <?php } else { ?>
                                        <?php if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/union/edit_union/' . $union->union_id) ?>
                                        <?php } ?>
                                        <?php if (!empty($deleted)) { ?>
                                            <?= btn_delete('admin/settings/delete_union/' . $union->union_id) ?>
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
                    <form method="post" action="<?= base_url() ?>admin/settings/union/update_union"
                          class="form-horizontal">
                        <tr>
                            <td><input type="text" name="union" class="form-control"
                                       placeholder="union" required></td>
                            <td>
                                <input name="union_code" placeholder=""
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