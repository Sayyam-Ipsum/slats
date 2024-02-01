<style>
    table td {
        word-break: break-word;
        vertical-align: top;
        white-space: normal !important;
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
                <h3><?php echo $transTypeText ?></h3>
                <p class="mb-0">
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
            <div class="card-header bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-right">
                            <button class="btn btn-falcon-default btn-sm me-1 mb-1" onclick="window.history.back()">Back</button>
                            <?php echo anchor('quotations/exit/' . $this->Transaction->get_field('id'), 'Exit', 'class="btn btn-falcon-default btn-sm me-1 mb-1"  id="bgback"') ?>
                            <?php echo anchor('quotations/preview/' . $this->Transaction->get_field('id'), 'Print', 'accesskey="p" class="btn btn-falcon-default btn-sm me-1 mb-1"  id="print"') ?>
                            <?php echo anchor('quotations/add_to_order/' . $this->Transaction->get_field('id'), 'To Order', 'class="btn btn-falcon-default btn-sm me-1 mb-1 btntoorder"  id="to_order"') ?>
                            <?php echo anchor('quotations/to_invoice/' . $this->Transaction->get_field('id'), 'To Invoice', 'class="btn btn-falcon-default btn-sm me-1 mb-1 btntoinvoice"  id="to_invoice"') ?>
                            <?php echo anchor('accounts/customer_info_preview/' . $this->Transaction->get_field('id'), 'Customer Info', 'class="btn btn-falcon-default btn-sm me-1 mb-1 btntopersonalinfo"  id="customer_print"') ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <?php if ($this->Transaction->get_field('id')) { ?>
                            <div class="col-sm-12">
                                <h6>Created on: <span style="color: red;"><b><?php echo $created_on ?></b></span>
                                    by:
                                    <span style="color: red;"><b><?php echo $user_add ?></b></span> & Edited by:
                                    <span
                                            style="color: red;"><b><?php echo $user_edit ?></b></span></h6>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
            <?php echo form_open('', 'id="transactionForm" name="transactionForm" class="form-horizontal" role="form" onsubmit="return validation();" autocomplete="off" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Transaction->get_field('id') ?>"/>
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="status">Checked</label>
                                    <input type='hidden' value='0' name='trans[status]'>
                                    <input name="trans[status]" value='1' type="checkbox" style="transform: scale(1.5);"
                                           id="status"
                                           class="input.i-status" <?php echo($status == 1 ? 'checked' : ''); ?>>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="auto_no">Quotation #</label>
                                    <?php
                                    echo form_input('trans[auto_no]', $this->Transaction->get_field('auto_no'), 'id="auto_no" class="form-control form-control-sm form-control form-control-sm-sm " readonly="true" required '),
                                    $this->Transaction->get_error('auto_no')
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="acctLkupTo">Customer Account</label>
                                    <?php echo form_input('', $account, 'id="acctLkupTo" maxlength="10" class="form-control form-control-sm form-control form-control-sm-sm "') ?>
                                    <div class="input-group-addon">
                                        <button type="button" id="add_acc_modal"><i class="fas fa-plus text-danger"></i>
                                        </button>
                                        <button type="button" id="edit_acc_modal"><i
                                                    class="fas fa-edit text-danger"></i>
                                        </button>
                                    </div>
                                    <input name="trans[account_id]"
                                           value="<?php echo $this->Transaction->get_field('account_id') ?>"
                                           id="account_id"
                                           type="hidden"/>
                                    <div id="error_acctLkupTo" style="text-align:center"
                                         onclick="document.getElementById('error_acctLkupTo').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="acctLkupFrom">Sales Account</label>
                                    <?php echo form_input('', $account2, 'id="acctLkupFrom" maxlength="10" class="form-control form-control-sm form-control form-control-sm-sm " readonly="true"') ?>
                                    <input name="trans[account2_id]"
                                           value="<?php echo $this->Transaction->get_field('account2_id') ?>"
                                           id="account2_id"
                                           type="hidden"/>
                                    <div id="error_acctLkupFrom" style="text-align:center"
                                         onclick="document.getElementById('error_acctLkupFrom').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="currency_id"><?php echo $this->lang->line('currency') ?></label>
                                    <?php
                                    echo form_dropdown('currency_id_list', $currenciesList, $this->Transaction->get_field('currency_id'), 'id="currency_id_list" class="form-select form-select-sm"'),
                                    $this->Transaction->get_error('currency_id');
                                    ?>
                                    <input name="trans[currency_id]" id="currency_id" type="hidden"
                                           value="<?php echo $this->Transaction->get_field('currency_id') ?>"/>
                                    <div id="error_currency_id" style="text-align:center"
                                         onclick="document.getElementById('error_currency_id').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="currency_rate">Currency Rate</label>
                                    <?php
                                    echo form_input('trans[currency_rate]', $this->Transaction->get_field('currency_rate'), 'id="currency_rate" class="form-control form-control-sm form-control form-control-sm-sm " required'),
                                    $this->Transaction->get_error('currency_rate')
                                    ?>
                                    <div id="error_currency_rate" style="text-align:center"
                                         onclick="document.getElementById('error_currency_rate').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="trans_date">Date</label>
                                    <?php echo form_input('trans[trans_date]', $trans_date, 'id="trans_date" class="form-control form-control-sm form-control form-control-sm-sm " required'), $this->Transaction->get_error('trans_date')
                                    ?>
                                    <div id="error_trans_date" style="text-align:center"
                                         onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label" for="value_date">Due Date</label>
                                    <?php echo form_input('trans[value_date]', $value_date, 'id="value_date" class="form-control form-control-sm form-control form-control-sm-sm "'), $this->Transaction->get_error('value_date')
                                    ?>
                                    <div id="error_value_date" style="text-align:center"
                                         onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="VIN">VIN</label>
                                    <?php echo form_input('trans[VIN]', $this->Transaction->get_field('VIN'), 'id="VIN" class="form-control form-control-sm form-control form-control-sm-sm "') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="model">Model</label>

                                    <?php echo form_textarea('trans[model]', $this->Transaction->get_field('model'), 'id="OE" class="form-control form-control-sm form-control form-control-sm-sm " style="height:100px;"') ?>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="TVA">TVA</label>
                                    <?php echo form_dropdown('trans[TVA]', $TVA, $this->Transaction->get_field('TVA'), 'id="TVA1" class="form-select form-select-sm"')
                                    ?>
                                    <div id="error_TVA" style="text-align:center"
                                         onclick="document.getElementById('error_TVA').style.display = 'none'"></div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="OE">OE</label>
                                    <?php echo form_textarea('trans[OE]', $this->Transaction->get_field('OE'), 'id="OE" class="form-control form-control-sm form-control form-control-sm-sm " style="height:100px;"') ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <?php echo form_textarea('trans[description]', $this->Transaction->get_field('description'), 'id="description" class="form-control form-control-sm form-control form-control-sm-sm " style="height:100px;"') ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="itemLookup">Select Item</label>
                                    <?php echo form_input('', '', 'id="itemLookup" class="form-control form-control-sm form-control form-control-sm-sm " required') ?>
                                    <div class="input-group-addon">
                                        <button type="button" id="add_item_modal"><i
                                                    class="fas fa-plus text-danger"></i></button>
                                        <button type="button" id="edit_item_modal"><i
                                                    class="fas fa-edit text-danger"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Transaction Items</label>

                            <div class="table-responsive scrollbar">
                                <table class="table table-bordered mb-0 fs--1 display compact overflow-hidden" style="width:100%"
                                       id="transItemsTbl">

                                    <thead style="background-color: #D13131; color: white">

                                    <tr class="danger">
                                        <th style="width: 20px;">Actions</th>
                                        <th>#</th>
                                        <th style="width: 120px;">Name</th>
                                        <th>Brand</th>
                                        <th style="width: 100px;">Artical Nb</th>
                                        <th style="width: 120px">Warehouse</th>
                                        <th style="width: 120px">Shelf</th>
                                        <th style="width: 40px">Qty</th>
                                        <th>Cost</th>
                                        <th>%</th>
                                        <th style="width: 100px;">Price</th>
                                        <th>Subtotal</th>
                                        <th style="width: 80px;">Discount %</th>
                                        <th>Total</th>
                                        <th class="text-right">Profit</th>
                                        <th class="text-right">Profit %</th>
                                    </tr>
                                    </thead>
                                    <tbody id="transactionLines">
                                    <?php
                                    $this->load->view('quotations/edit_quotation_form_items_line', ['transItems' => isset($trans_items) ? $trans_items : []]);
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div id="error_transactionLines" style="text-align:center"
                                 onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>

                        </div>
                    </div>
                    <hr>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" for="sub_total">Subtotal</label>
                            <?php
                            echo form_input('trans[sub_total]', '', 'id="sub_total" class="form-control form-control-sm form-control form-control-sm-sm " readonly="true"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" for="discount">Discount</label>
                            <?php
                            echo form_input('trans[discount]', $this->Transaction->get_field('discount'), 'id="disc" class="form-control form-control-sm form-control form-control-sm-sm " required'),
                            $this->Transaction->get_error('discount')
                            ?>
                            <div id="error_disc" style="text-align:center"
                                 onclick="document.getElementById('error_disc').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" for="delivery_charge">Delivery Charge</label>
                            <?php
                            echo form_input('trans[delivery_charge]', $this->Transaction->get_field('delivery_charge'), 'id="delivery_charge" class="form-control form-control-sm form-control form-control-sm-sm " required'),
                            $this->Transaction->get_error('delivery_charge')
                            ?>
                            <div id="error_delivery_charge" style="text-align:center"
                                 onclick="document.getElementById('error_delivery_charge').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" for="pfand">Pfand</label>
                            <?php
                            echo form_input('trans[pfand]', $this->Transaction->get_field('pfand'), 'id="pfand" class="form-control form-control-sm form-control form-control-sm-sm " required'),
                            $this->Transaction->get_error('pfand')
                            ?>
                            <div id="error_pfand" style="text-align:center"
                                 onclick="document.getElementById('error_pfand').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="final_total">Total</label>
                            <?php
                            echo form_input('trans[final_total]', '', 'id="final_total" class="form-control form-control-sm form-control form-control-sm-sm " readonly="true"')
                            ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">
                    <?php echo form_submit('submitBtn', "Save & Exit", 'class="btn btn-falcon-default btn-sm me-1 mb-1"') ?>
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-falcon-default btn-sm me-1 mb-1" accesskey="s"') ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <!-- Availability Table -->
                    <div id="AvTbDiv" class="col-md-6">
                        <h4>Availability Table</h4>
                        <hr>
                        <table class="table table-sm table-borderless fs--1" id="activityitemsTbl">
                            <thead>
                            <tr style="background-color:#404040; color:white;">
                                <th><?php echo $this->lang->line('name'); ?></th>
                                <th><?php echo $this->lang->line('brand'); ?></th>
                                <th><?php echo $this->lang->line('artical_number'); ?></th>
                                <th><?php echo $this->lang->line('warehouse'); ?></th>
                                <th><?php echo $this->lang->line('shelf'); ?></th>
                                <th><?php echo $this->lang->line('qty'); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan='6' style='text-align: center;'>No Data Found</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div id="itemAvTbDiv" class="col-md-6">
                        <h4 id="item_info">Item Info</h4>
                        <hr>
                        <input type="hidden" id="row_id">
                        <table class="table table-sm table-borderless fs--1" id="activityitemTbl">
                            <thead>
                            <tr style="background-color:#404040; color:white;">
                                <th><?php echo $this->lang->line('brand'); ?></th>
                                <th><?php echo $this->lang->line('artical_number'); ?></th>
                                <th><?php echo $this->lang->line('warehouse'); ?></th>
                                <th><?php echo $this->lang->line('shelf'); ?></th>
                                <th><?php echo $this->lang->line('qty'); ?></th>
                            </tr>
                            </thead>
                            <tbody id="activity_tbody">
                            <tr>
                                <td colspan='5' style='text-align: center;'>No Data Found</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const ItemLineTpl = <?php echo json_encode($this->load->view('quotations/quotation_form_items_line', [], true)) ?>;
</script>
<!-- Account Form Modal -->
<?php
$this->load->view('accounts/modal_form');
?>
<!-- Item Form Modal -->
<?php
$this->load->view('items/modal_form');
?>

<!-- confirm Modal -->
<div class="modal fade" id="confirmSubmitModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 500px">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirm_submit">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- Order Modal -->
<div class="modal fade" id="OrderItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content position-relative">
            <div class="position-absolute top-0 end-0 mt-2 me-2 z-1">
                <button class="btn-close btn btn-sm btn-circle d-flex flex-center transition-base"
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="rounded-top-3 py-3 ps-4 pe-6 bg-light">
                    <h4 class="mb-1" id="accModalLabel">Customer Order </h4>
                </div>
                <div class="p-4 pb-0">
                    <input type="text" id="row_id" hidden>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="activate_customer">Activate</label>
                                <input type="checkbox" id="activate_customer" name="activate_customer"
                                       style="transform: scale(1.5); margin-top:15px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="oi_warehouse">Warehouse:</label>
                                <?php echo form_dropdown('oi_warehouse', $warehouse_list, '', 'id="oi_warehouse" class="form-select form-select-sm"') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="oi_shelf">Shelf:</label>
                                <?php echo form_dropdown('oi_shelf', '', '', 'id="oi_shelf" class="form-select form-select-sm"') ?>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-top-3 py-3 ps-4 pe-6 bg-light">
                        <h4 class="mb-1">SLATS Order</h4>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="activate_checkbox">Activate</label>
                                <input type="checkbox" id="activate_checkbox" name="activate_checkbox"
                                       style="transform: scale(1.5); margin-top:15px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="ois_account">Account:</label>
                                <?php echo form_input('', '', 'id="ois_account" class="form-control form-control-sm form-control form-control-sm-sm "') ?>
                                <input name="ois_account_id" id="ois_account_id" type="hidden"/>
                                <div id="error_ois_account_id" style="text-align:center; font-size:small;"
                                     onclick="document.getElementById('error_ois_account_id').style.display = 'none'"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="ois_qty">Qty:</label>
                                <?php echo form_input('ois_qty', '', 'id="ois_qty" class="form-control form-control-sm form-control form-control-sm-sm "') ?>
                                <div id="error_ois_qty" style="text-align:center; font-size:small;"
                                     onclick="document.getElementById('error_ois_qty').style.display = 'none'"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="ois_warehouse">Warehouse:</label>
                                <?php echo form_dropdown('ois_warehouse', $warehouse_list, '', 'id="ois_warehouse" class="form-select form-select-sm"') ?>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="col-form-label"
                                       for="ois_shelf">Shelf:</label>
                                <?php echo form_dropdown('ois_shelf', '', '', 'id="ois_shelf" class="form-select form-select-sm"') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="order_submit">Submit</button>
            </div>
        </div>
    </div>
</div>