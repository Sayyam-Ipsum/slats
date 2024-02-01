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
    <div class="bg-holder d-none d-lg-block bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-8">
                <h3><?php echo $title ?></h3>
                <p class="mb-0">
                    <?php if ($this->session->flashdata('message_inventory')) { ?>
                <div id="msg" class="alert alert-success" style="text-align:center" onclick="document.getElementById('msg').style.display = 'none'">
                    <strong><?php echo $this->session->flashdata('message_inventory') ?></strong>
                </div>
            <?php } ?>
            <?php if ($this->session->flashdata('erorr_inventory')) { ?>
                <div id="erorr_inventory" class="alert alert-danger" style="text-align:center" onclick="document.getElementById('erorr_inventory').style.display = 'none'">
                    <strong><?php echo $this->session->flashdata('erorr_inventory') ?></strong>
                </div>
            <?php } ?>
            </p>
            </div>
            <div class="col-lg-4">
                <div class="mb-1">
                    <div <?php echo ($total_permission == '0') ? 'hidden' : ''; ?>>

                        <label for="tot">Total Cost</label>
                        <input type="text" name="tot" value="<?php echo $tot ?>" class="form-control form-control-sm" id="tot" style="text-align: center;" readonly>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row g-0">
    <div class="col-lg-12 pe-lg-2">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <?php echo form_open('', 'class="form-horizontal" ') ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-1">
                                    <label class="form-label" for="artical_number_alt">Alternative</label>
                                    <?php echo form_input('artical_number_alt', '', 'id="artical_number_alt" class="form-control form-control-sm" placeholder="By Artical number"') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top border-200 d-flex flex-between-center">
                        <div class="d-flex align-items-center">
                            <button name="search" type="submit" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm" title="search"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <?php echo form_close() ?>

                </div>
            </div>
            <div class="col-md-8">
                <div class="card mb-3">
                    <?php echo form_open('', 'class="form-horizontal" onsubmit="return false" id="dtAdvFltrs"') ?>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 d-none">
                                <div class="mb-1">
                                    <label for="EAN">Alternative</label>
                                    <?php echo form_input('artical_number', '', 'id="artical_number" class="form-control form-control-sm" placeholder="By Artical number"') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="warehouse">Warehouse</label>
                                    <?php echo form_dropdown('warehouse', $warehouses, '', 'id="filter_warehouse" class="form-select form-select-sm"') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-1">
                                    <label for="shelf">Shelf</label>
                                    <input type="text" list="shelfs" id="filter_shelf" name="shelf" value="" class="form-control form-control-sm" />

                                    <datalist id="shelfs">

                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer border-top border-200 d-flex flex-between-center">
                        <div class="d-flex align-items-center">
                            <button name="search" type="submit" id="filter" class="btn btn-falcon-default btn-sm me-1 bt-link" title="Filter"><i class="fas fa-filter"></i></button>

                            <button type="button" name="reset_filters" id="reset_filters" title="Refresh" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm btn-small-design"><i class="fas fa-redo"></i></button>

                            <button name="adjust" type="button" id="adjust" title="Adjust Minus" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm"><i class="fas fa-minus"></i></button>

                            <button name="adjust_plus" type="button" id="adjust_plus" title="Adjust Plus" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm"><i class="fas fa-plus"></i>
                            </button>

                            <button name="adjust_shelf" type="button" id="adjust_shelf" title="Clear Shelf" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm"><i class="fas fa-trash"></i></button>

                            <?php echo anchor('warehouses/find_alternatives', 'Alternatives', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm" id="bgsearch"') ?>
                            <?php echo anchor('opening_items/add', 'Add Opening', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 bt-link btn-sm" id="bgadd"') ?>

                        </div>
                    </div>
                    <?php echo form_close() ?>

                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered mb-0 fs--1 display compact" style="width:100%" id="warehousesTbl" data-num-rows="<?php echo $this->Warehouse->get('paginationTotalRows') ?>">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th style="width: 10%;"><?php echo $this->lang->line('barcode') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('EAN') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('description') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('artical_number') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('brand') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('alternative') ?></th>
                                    <th style="width: 5%;" data-no-search="0"><?php echo $this->lang->line('qty') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('warehouse') ?></th>
                                    <th style="width: 10%;"><?php echo $this->lang->line('shelf') ?></th>
                                    <th class="text-center" data-no-search="0" style="width: 15%;"><?php echo $this->lang->line('actions') ?></th>
                                </tr>
                            </thead>
                            <tbody id=" tableLines" class="list">
                                        <?php foreach ($records as $record) { ?>
                                <tr>
                                    <td><?php echo $record['barcode'] ?></td>
                                    <td><?php echo $record['EAN'] ?></td>
                                    <td><?php echo $record['description'] ?></td>
                                    <td><?php echo $record['artical_number'] ?></td>
                                    <td><?php echo $record['brand'] ?></td>
                                    <td><?php echo $record['alternative'] ?></td>
                                    <td><?php echo $record['total_qty'] ?></td>
                                    <td><?php echo $record['warehouse'] ?></td>
                                    <td><?php echo $record['shelf'] ?></td>
                                    <td><?php echo $record['item_id'] ?></td>
                                    <!--<td class="text-center">
                                        <?php /*echo $record['item_id'] */ ?>
                                        <a href="opening_items/product_openings/<?php /*echo $record['item_id'] */ ?>"
                                           class="btn bt-link btn-sm"
                                           title="Opening Item">
                                            <i class="fas fa-info"></i>
                                        </a>
                                        <button type="button"
                                                class="btn bt-link btn-sm i-transfer"
                                                onclick="openItemModalEdit(<?php /*echo $record['item_id']; */ ?>, '<?php /*echo $record['warehouse']; */ ?>', '<?php /*echo $record['shelf']; */ ?>')"
                                                title="Transfer">
                                            <i class="fas fa-crop"></i>
                                        </button>
                                        <button type="button"
                                                class="btn bt-link btn-sm i-adjust"
                                                onclick="openAdjustModal(<?php /*echo $record['item_id'] */ ?>, '<?php /*echo $record['warehouse'] */ ?>', '<?php /*echo $record['shelf'] */ ?>', <?php /*echo $record['total_qty'] */ ?>, '<?php /*echo $record['barcode'] */ ?>', '<?php /*echo $record['EAN'] */ ?>')"
                                                title="Adjust">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </td>-->
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <h6>Alternatives List</h6>
                            </div>
                        </div>
                        <hr>
                        <table class="table mb-0 data-table fs--1" id="alternativesTbl">
                            <thead class="bg-200 text-900">
                                <tr>
                                    <th class="sort"><?php echo $this->lang->line('brand') ?></th>
                                    <th class="sort"><?php echo $this->lang->line('artical_number') ?></th>
                                    <th class="sort"><?php echo $this->lang->line('warehouse') ?></th>
                                    <th class="sort"><?php echo $this->lang->line('shelf') ?></th>
                                    <th class="sort"><?php echo $this->lang->line('qty') ?></th>
                                </tr>
                            </thead>
                            <tbody class="list">
                                <?php if ($rows) {

                                    foreach ($rows as $row) { ?>
                                        <tr style="<?php echo ($row['artical_number'] != $artical_nb) ? 'background: #FEC5BF;' : ''; ?>">
                                            <td><?php echo $row['brand'] ?></td>
                                            <td><?php echo $row['artical_number'] ?></td>
                                            <td><?php echo $row['warehouse'] ?></td>
                                            <td><?php echo $row['shelf'] ?></td>
                                            <td><?php echo $row['total_qty'] ?></td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="5">
                                            <center>No Data Found</center>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="itemTransferModalForm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-3 py-3 ps-4 pe-6 bg-slats-red">
                    <h4 class="mb-1" id="accModalLabel" style="color: white">Transfer Products </h4>
                </div>
                <div class="p-4 pb-0 transfer-body">

                </div>
            </div>
        </div>
    </div>
</div>


<!-- Adjust Modal -->

<?php

$this->load->view('adjust/modal');

?>

<!-- Adjust row Modal -->

<?php

$this->load->view('adjust/row_adjust_modal');

?>

<!-- Confirm modal -->
<div class="modal fade" id="confirm_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-3 py-3 ps-4 pe-6 bg-slats-red">
                    <h4 class="mb-1" id="accModalLabel" style="color: white">Transfer Products </h4>
                </div>
                <div class="p-4 pb-0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label" for="account_type">Confirm</label>
                                <input type="text" id="confirm_warehouse_id" hidden>

                                <h3><b>Confirm Action Clear Shelf: </b><span id="confirm_shelf_name"></span></h3>

                                <h3><b>Are You Sure?</b></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button type="button" id="submit_confirm" class="btn btn-primary">Confirm</button>
            </div>
        </div>
    </div>
</div>