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
                    <th>DIVISION</th>
                    <th>Code</th>
                    <?php if (!empty($edited) || !empty($deleted)) { ?>
                        <th><?= lang('action') ?></th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $all_division = $this->db->get('tbl_division')->result();
                if (!empty($all_division)) {
                    foreach ($all_division as $division) {
                        ?>
                        <tr>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $division->division_id) { ?>
                                <form method="post"
                                      action="<?= base_url() ?>admin/settings/division/update_division/<?php
                                      if (!empty($division_info)) {
                                          echo $division_info->division_id;
                                      }
                                      ?>" class="form-horizontal">
                                    <input type="text" name="division" value="<?php
                                    if (!empty($division_info)) {
                                        echo $division_info->division;
                                    }
                                    ?>" class="form-control" placeholder="DIVISION" required>
                                <?php } else {
                                    echo $division->division;
                                }
                                ?></td>
                            <td><?php
                                $id = $this->uri->segment(5);
                                if (!empty($id) && $id == $division->division_id) { ?>
                                    <input type="text" name="division code" class="form-control" value="<?php
                                    if (!empty($division_info)) {
                                        echo $division_info->division_code;
                                    }
                                    ?>"/>
                                <?php } else {
                                    echo $division->division_code;
                                }
                                ?></td>
                            <?php if (!empty($edited) || !empty($deleted)) { ?>
                                <td>
                                    <?php
                                    $id = $this->uri->segment(5);
                                    if (!empty($id) && $id == $division->division_id) { ?>
                                        <?= btn_update() ?>
                                        </form>
                                        <?= btn_cancel('admin/settings/division/') ?>
                                    <?php } else { ?>
                                        <?php if (!empty($edited)) { ?>
                                            <?= btn_edit('admin/settings/division/edit_division/' . $division->division_id) ?>
                                        <?php } ?>
                                        <?php if (!empty($deleted)) { ?>
                                            <?= btn_delete('admin/settings/delete_division/' . $division->division_id) ?>
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
                    <form method="post" action="<?= base_url() ?>admin/settings/division/update_division"
                          class="form-horizontal">
                        <tr>
                            <td><input type="text" name="division" class="form-control"
                                       placeholder="division" required></td>
                            <td>
                                <input name="division_code" placeholder=""
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