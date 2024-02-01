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
                            <button accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback">Back
                            </button>
                            <?php echo anchor('orders/preview/' . $this->Transaction->get_field('id'), 'Print', 'accesskey="p" class="btn btn-falcon-default btn-sm me-1 mb-1"  id="print"') ?>
                            <?php echo anchor('orders/to_invoice/' . $this->Transaction->get_field('id'), 'To invoice', 'class="btn btn-falcon-default btn-sm me-1 mb-1"  id="to_invoice"') ?>
                        </p>
                    </div>
                    <div class="col-md-6">

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
                                    <input name="transaction_items[status]" type="checkbox" style="transform: scale(1.5);"
                                           id="status"
                                           class="input.i-status" <?php echo($status == 1 ? 'checked' : ''); ?>
                                           onclick="return false;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="auto_no">Order #</label>
                                    <?php
                                    echo form_input('trans[auto_no]', $this->Transaction->get_field('auto_no'), 'id="auto_no" class="form-control form-control-sm" readonly="true" required '),
                                    $this->Transaction->get_error('auto_no')
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="acctLkupTo">Customer Account</label>
                                    <?php echo form_input('', $account, 'id="acctLkupTo" maxlength="10" class="form-control form-control-sm"') ?>
                                    <div class="input-group-addon">
                                        <button type="button" id="add_acc_modal"><i class="fas fa-plus text-danger"></i>
                                        </button>
                                        <button type="button" id="edit_acc_modal"><i class="fas fa-edit text-danger"></i>
                                        </button>
                                    </div>
                                    <input name="trans[account_id]"
                                           value="<?php echo $this->Transaction->get_field('account_id') ?>" id="account_id"
                                           type="hidden"/>
                                    <div id="error_acctLkupTo" style="text-align:center"
                                         onclick="document.getElementById('error_acctLkupTo').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label"
                                           for="acctLkupFrom">Sales Account</label>
                                    <?php echo form_input('', $account2, 'id="acctLkupFrom" maxlength="10" class="form-control form-control-sm" readonly="true"') ?>
                                    <input name="trans[account2_id]"
                                           value="<?php echo $this->Transaction->get_field('account2_id') ?>"
                                           id="account2_id"
                                           type="hidden"/>
                                    <div id="error_acctLkupFrom" style="text-align:center"
                                         onclick="document.getElementById('error_acctLkupFrom').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="delivery_type">Delivery Type</label>
                                    <?php echo form_dropdown('trans[delivery_type]', $delivery_type, $this->Transaction->get_field('delivery_type'), 'id="delivery_type" class="form-select form-select-sm"')
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="TVA">TVA</label>
                                    <?php echo form_dropdown('trans[TVA]', $TVA, $this->Transaction->get_field('TVA'), 'id="TVA1" class="form-select form-select-sm"')
                                    ?>
                                    <div id="error_TVA" style="text-align:center"
                                         onclick="document.getElementById('error_TVA').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="description">Description</label>
                                    <?php echo form_textarea('trans[description]', $this->Transaction->get_field('description'), 'id="description" class="form-control form-control-sm" style="height:100px;"') ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
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
                                    echo form_input('trans[currency_rate]', $this->Transaction->get_field('currency_rate'), 'id="currency_rate" class="form-control form-control-sm" required'),
                                    $this->Transaction->get_error('currency_rate')
                                    ?>
                                    <div id="error_currency_rate" style="text-align:center"
                                         onclick="document.getElementById('error_currency_rate').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="trans_date">Date</label>
                                    <?php echo form_input('trans[trans_date]', $trans_date, 'id="trans_date" class="form-control form-control-sm" required'), $this->Transaction->get_error('trans_date')
                                    ?>
                                    <div id="error_trans_date" style="text-align:center"
                                         onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="value_date">Due Date</label>
                                    <?php echo form_input('trans[value_date]', $value_date, 'id="value_date" class="form-control form-control-sm"'), $this->Transaction->get_error('value_date')
                                    ?>
                                    <div id="error_value_date" style="text-align:center"
                                         onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="VIN">VIN</label>
                                    <?php echo form_textarea('trans[VIN]', $this->Transaction->get_field('VIN'), 'id="VIN" class="form-control form-control-sm" style="height:100px;"') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="model">Model</label>
                                    <?php echo form_textarea('trans[model]', $this->Transaction->get_field('model'), 'id="model" class="form-control form-control-sm" style="height:100px;"') ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="driver_id">Select Driver</label>
                                    <?php echo form_dropdown('driver_list', $drivers, $this->Transaction->get_field('driver_id'), 'id="driver_list" class="form-select form-select-sm"') ?>
                                    <input name="trans[driver_id]" value="" id="driver_id" type="hidden"/>
                                    <div id="error_driver_id" style="text-align:center"
                                         onclick="document.getElementById('error_driver_id').style.display = 'none'"></div>

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="tracking_number">Tracking #</label>
                                    <?php
                                    echo form_input('trans[tracking_number]', $this->Transaction->get_field('tracking_number'), 'id="tracking_number" class="form-control form-control-sm"')
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="employee_id">Select Employee</label>
                                    <?php echo form_dropdown('employee_list', $employees, $this->Transaction->get_field('employee_id'), 'id="employee_list" class="form-select form-select-sm"') ?>
                                    <input name="trans[employee_id]" value="" id="employee_id" type="hidden"/>
                                    <div id="error_employee_id" style="text-align:center"
                                         onclick="document.getElementById('error_employee_id').style.display = 'none'"></div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="OE">OE</label>
                                    <?php echo form_textarea('trans[OE]', $this->Transaction->get_field('OE'), 'id="OE" class="form-control form-control-sm" style="height:100px;"') ?>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label" for="itemLookup">Select Item</label>
                                    <?php echo form_input('', '', 'id="itemLookup" class="form-control form-control-sm" required') ?>
                                    <div class="input-group-addon">
                                        <button type="button" id="add_item_modal"><i class="fas fa-plus text-danger"></i>
                                        </button>
                                        <button type="button" id="edit_item_modal"><i class="fas fa-edit text-danger"></i>
                                        </button>
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
                                <table class="table table-sm table-bordered fs--1 display compact" style="width:100%" id="transItemsTbl">

                                    <thead style="background-color: #D13131; color: white">
                                    <tr class="danger">
                                        <th>Actions</th>
                                        <th style="width: 150px">Name</th>
                                        <th style="width: 150px">Brand</th>
                                        <th style="width: 110px">Artical Nb</th>
                                        <th style="width: 150px">Warehouse</th>
                                        <th style="width: 150px">Shelf</th>
                                        <th>Quantity</th>
                                        <th>Cost</th>
                                        <th>Profit</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                        <th>Discount %</th>
                                        <th>Profit</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody id="transactionLines">
                                    <?php
                                    $this->load->view('qu_to_os/edit_order_form_items_line', ['transItems' => isset($trans_items) ? $trans_items : []]);
                                    ?>
                                    </tbody>
                                </table>
                                <div id="error_transactionLines" style="text-align:center"
                                     onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>
                            </div>

                        </div>
                    </div>
                    <hr>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" for="sub_total">Subtotal</label>
                            <?php
                            echo form_input('trans[sub_total]', '', 'id="sub_total" class="form-control form-control-sm" readonly="true"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label" for="discount">Discount</label>
                            <?php
                            echo form_input('trans[discount]', $this->Transaction->get_field('discount'), 'id="disc" class="form-control form-control-sm" required'),
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
                            echo form_input('trans[delivery_charge]', $this->Transaction->get_field('delivery_charge'), 'id="delivery_charge" class="form-control form-control-sm" required'),
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
                            echo form_input('trans[pfand]', $this->Transaction->get_field('pfand'), 'id="pfand" class="form-control form-control-sm" required'),
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
                            echo form_input('trans[final_total]', '', 'id="final_total" class="form-control form-control-sm" readonly="true"')
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
                    <div class="col-md-6">
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
    const ItemLineTpl = <?php echo json_encode($this->load->view('qu_to_os/order_form_items_line', [], true)) ?>;
</script>
<!-- Account Form Modal -->
<?php
$this->load->view('accounts/modal_form');
?>
<!-- Item Form Modal -->
<?php
$this->load->view('items/modal_form');
?>
