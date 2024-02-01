<style>
    table td {
        word-break: break-word;
        vertical-align: top;
        white-space: normal !important;
    }

    td {
        height: 30px;
    }
</style>
<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <h3><?php echo $title ?></h3>
                <p class="mb-0">

                    <?php if ($this->session->flashdata('message')) { ?>
                <div id="delete_ignore" class="alert alert-danger" style="text-align:center"
                     onclick="document.getElementById('delete_ignore').style.display = 'none'">
                    <strong><?php echo $this->session->flashdata('message') ?></strong>
                </div>
                <?php } ?>
                <?php if ($this->session->flashdata('message_success')) { ?>
                    <div id="save_msg" class="alert alert-success" style="text-align:center"
                         onclick="document.getElementById('save_msg').style.display = 'none'">
                        <strong><?php echo $this->session->flashdata('message_success') ?></strong>
                    </div>
                <?php } ?>

                </p>

            </div>
        </div>
    </div>
</div>
<div class="row g-0">
    <div class="col-lg-12 pe-lg-2">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-0">
                    <div class="col-md-1 text-start">
                        <h5 class="mb-0" id="account"><p
                                    class="text-right"><?php echo anchor('items/add', 'Add Product', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgadd"') ?></p>
                        </h5>
                    </div>
                    <div class="col-md-2 text-start">
                        <button id="group_action" name="group_action"
                                class="btn btn-falcon-default btn-sm me-1 mb-1 d-none text-end">Group Actions
                        </button>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered table-striped mb-0 fs--1 display compact" style="width:100%"
                       id="itemsTbl"
                       data-num-rows="<?php echo $this->Item->get('paginationTotalRows') ?>">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th><?php echo $this->lang->line('barcode') ?></th>
                        <th><?php echo $this->lang->line('EAN') ?></th>
                        <th><?php echo $this->lang->line('artical_number') ?></th>
                        <th><?php echo $this->lang->line('name') ?></th>
                        <th><?php echo $this->lang->line('brand') ?></th>
                        <th data-no-search="0"><?php echo $this->lang->line('purchase_cost_euro') ?></th>
                        <th data-no-search="0"><?php echo $this->lang->line('cost_CHF') ?></th>
                        <th data-no-search="0"><?php echo $this->lang->line('quantity') ?></th>
                        <th style="width: 150px" data-no-search="0" class="text-center"><?php echo $this->lang->line('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td><?php echo $record['barcode'] ?></td>
                            <td><?php echo $record['EAN'] ?></td>
                            <td><?php echo $record['artical_number'] ?></td>
                            <td><?php echo $record['description'] ?></td>
                            <td><?php echo $record['brand'] ?></td>
                            <td><?php echo $record['purchase_cost'] ?></td>
                            <td><?php echo $record['cost'] ?></td>
                            <td><?php echo $record['qty'] ?></td>
                            <td class="text-center">
                                <?php echo $record['id'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Large modal -->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
     id="group_action_modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel"><b>Group Actions</b></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <?php echo form_open('items/group_items_update', 'id="groupActionsForm" name="groupActionsForm" class="form-horizontal" onsubmit="return groupActionValidation();" role="form" autocomplete="off" novalidate') ?>
                <div class="form-group row">
                    <div class="col-md-4">
                        <h4><b>Filters:</b></h4>
                        <hr>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="col-md-3 control-label"
                               for="brand"><?php echo $this->lang->line('brand') ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown('brand', $brands, '', 'id="brand" class="form-control form-control-sm"')
                            ?>
                            <div id="error_brand" style="text-align:center"
                                 onclick="document.getElementById('error_brand').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="col-md-3 control-label"
                               for="name_list"><?php echo $this->lang->line('name') ?></label>
                        <div class="col-md-9">
                            <?php
                            echo form_dropdown('name_list', '', '', 'id="name_list" class="form-control form-control-sm"')
                            ?>
                            <div id="error_name_list" style="text-align:center"
                                 onclick="document.getElementById('error_name_list').style.display = 'none'"></div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <h4><b>Action on:</b></h4>
                        <hr>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <label class="col-md-3 control-label"
                               for="profit"><?php echo $this->lang->line('profit') ?></label>
                        <!-- <div class="col-md-3">
							<?php
                        //echo form_dropdown('operation', $operations, '', 'id="operation" class="form-control form-control-sm"')
                        ?>
						</div> -->
                        <div class="col-md-9">
                            <?php
                            echo form_input('profit', '', 'id="profit" class="form-control form-control-sm" autocomplete="off"')
                            ?>
                            <div id="error_profit" style="text-align:center"
                                 onclick="document.getElementById('error_profit').style.display = 'none'"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" id="update" class="btn btn-primary">Update</button>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
-->
<script>
    // Bind click event to the delete button
    $('#itemsTbl').on('click', '.deleteBtn', function (e) {
        e.preventDefault();

        // Get the data-id attribute from the clicked delete button
        var id = $(this).data('id');
        var action = $(this).data('action');

        // Call confirmAndExecuteAction function with appropriate parameters
        confirmAndExecuteAction(action + '/' + id, {id: id});
    });

</script>