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
                            <?php echo anchor('delivery_notes/preview/' . $this->Transaction->get_field('id'), 'Delivery Note', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1"  id="bgadd"') ?> &nbsp;&nbsp;
                            <?php echo anchor('orders/index', 'Back', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback"') ?>
                        </p>
                    </div>
             <!--       <div class="col-md-6">
                        <?php /*if ($this->Transaction->get_field('id')) { */?>
                            <div class="col-sm-12">
                                <h6>Created on: <span style="color: red;"><b><?php /*echo $created_on */?></b></span>
                                    by:
                                    <span style="color: red;"><b><?php /*echo $user_add */?></b></span> & Edited by:
                                    <span
                                            style="color: red;"><b><?php /*echo $user_edit */?></b></span></h6>
                            </div>
                        <?php /*} */?>
                    </div>-->
                </div>

            </div>
            <?php echo form_open('', 'id="transactionForm" name="transactionForm" class="form-horizontal" role="form" onsubmit="return validation_order_to_invoice();" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Transaction->get_field('id') ?>" />
                <div class="row">
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="status">Unpaid</label>
                            <input type="radio" class="form-check-input" style="transform: scale(1.5);" id="unpaid" name="trans[status]" value="0">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="status">Paid</label>
                            <input type="radio" class="form-check-input" style="transform: scale(1.5);" id="paid" name="trans[status]" value="1">
                            <div id="error_paid" style="text-align:center" onclick="document.getElementById('error_paid').style.display = 'none'"></div>

                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="status">Delivered</label>
                            <input type="radio" class="form-check-input" style="transform: scale(1.5);" id="delivered" name="trans[delivered]" value="1">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="auto_no">Sales #</label>
                            <?php
                            echo form_input('trans[auto_no]', $this->Transaction->get_field('auto_no'), 'id="auto_no" class="form-control form-control-sm" readonly="true" required '),
                            $this->Transaction->get_error('auto_no')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="trans_date">Date</label>
                            <?php echo form_input('trans[trans_date]', $trans_date, 'id="trans_date" class="form-control form-control-sm" required'), $this->Transaction->get_error('trans_date')
                            ?>
                            <div id="error_trans_date" style="text-align:center"
                                 onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="value_date">Due Date</label>
                            <?php echo form_input('trans[value_date]', $value_date, 'id="value_date" class="form-control form-control-sm"'), $this->Transaction->get_error('value_date')
                            ?>
                            <div id="error_value_date" style="text-align:center"
                                 onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="VIN">VIN</label>
                            <?php echo form_input('trans[VIN]', $this->Transaction->get_field('VIN'), 'id="VIN" class="form-control form-control-sm"') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="model">Model</label>
                            <?php echo form_textarea('trans[model]', $this->Transaction->get_field('model'), 'id="model" class="form-control form-control-sm" style="height:100px;"') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="TVA">TVA</label>
                            <?php echo form_dropdown('trans[TVA]', $TVA, $this->Transaction->get_field('TVA'), 'id="TVA1" class="form-select form-select-sm"')
                            ?>
                            <div id="error_TVA" style="text-align:center"
                                 onclick="document.getElementById('error_TVA').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="employee_id">Select Employee</label>
                            <?php echo form_dropdown('employee_list', $employees, $this->Transaction->get_field('employee_id'), 'id="employee_list" class="form-select form-select-sm"') ?>
                            <input name="trans[employee_id]"
                                   value="<?php echo $this->Transaction->get_field('employee_id') ?>"
                                   id="employee_id"
                                   type="hidden"/>
                            <div id="error_employee_id" style="text-align:center"
                                 onclick="document.getElementById('error_employee_id').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="delivery_type">Delivery Type</label>
                            <?php echo form_dropdown('trans[delivery_type]', $delivery_type, $this->Transaction->get_field('delivery_type'), 'id="delivery_type" class="form-select form-select-sm"')
                            ?>
                        </div>
                            <div class="mb-3">
                                <label class="form-label" for="payment_method">Payment Method</label>
                                <?php
                                echo form_dropdown('trans[payment_method]', $payment_methods, $this->Transaction->get_field('payment_method'), 'id="payment_method" class="form-select form-select-sm"')
                                ?>
                            </div>
                        <div class="mb-6">
                            <label class="form-label" for="itemLookup">Select Item</label>
                            <?php echo form_input('', '', 'id="itemLookup" class="form-control form-control-sm" required') ?>
                            <div class="input-group-addon">
                                <button type="button" id="add_item_modal"><i
                                            class="fas fa-plus text-danger"></i></button>
                                <button type="button" id="edit_item_modal"><i
                                            class="fas fa-edit text-danger"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="driver_id">Select Driver</label>
                            <?php echo form_dropdown('driver_list', $drivers, $this->Transaction->get_field('driver_id'), 'id="driver_list" class="form-select form-select-sm"') ?>
                            <input name="trans[driver_id]"
                                   value="<?php echo $this->Transaction->get_field('driver_id') ?>" id="driver_id"
                                   type="hidden"/>
                            <div id="error_driver_id" style="text-align:center"
                                 onclick="document.getElementById('error_driver_id').style.display = 'none'"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Transaction Number</label>
                            <?php
                            echo form_input('trans[transaction_number]', $this->Transaction->get_field('transaction_number'), 'id="transaction_number" class="form-control form-control-sm"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="OE">OE</label>
                            <?php echo form_textarea('trans[OE]', $this->Transaction->get_field('OE'), 'id="OE" class="form-control form-control-sm" style="height:200px;"') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <?php echo form_textarea('trans[description]', $this->Transaction->get_field('description'), 'id="description" class="form-control form-control-sm" style="height:200px;"') ?>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Transaction Items</label>
                            <div class="table-responsive fs--1">
                                <table class="table table-bordered mb-0 fs--1 display compact" id="trans_items_table">
                                    <thead style="background-color: #D13131; color: white">
                                    <tr class="danger">
                                        <th class="col-sm-1">Actions</th>
                                        <th class="col-sm-2">Name</th>
                                        <th class="col-sm-2">Brand</th>
                                        <th class="col-sm-1">Artical Nb</th>
                                        <th class="col-sm-2">Warehouse</th>
                                        <th class="col-sm-2">Shelf</th>
                                        <th class="col-sm-1">Quantity</th>
                                        <th class="col-sm-2">Cost</th>
                                        <th class="col-sm-2">Profit</th>
                                        <th class="col-sm-1">Price</th>
                                        <th class="col-sm-1">Discount %</th>
                                        <th class="col-sm-2 text-right">Total</th>
                                    </tr>
                                    </thead>
                                    <tbody id="transactionLines">
                                    <?php
                                    $this->load->view('order_to_invoice/edit_form_items_line', ['transItems' => isset($trans_items) ? $trans_items : []]);
                                    ?>
                                    </tbody>
                                </table>
                                <div id="error_transactionLines" style="text-align:center"
                                     onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="sub_total">Subtotal</label>
                            <?php
                            echo form_input('trans[sub_total]', '', 'id="sub_total" class="form-control form-control-sm" readonly="true"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <div class="col-md-3">
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
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-danger me-1 mb-1" accesskey="s"'), ' ', form_reset('reset', $this->lang->line('reset'), 'class="btn btn-link"') ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 table-responsive fs--1">
                        <h4 id="item_info" hidden></h4>
                        <input type="text" id="row_id" hidden>
                        <table class="table table-striped border-bottom" id="activityitemTbl">
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
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const ItemLineTpl = <?php echo json_encode($this->load->view('order_to_invoice/form_items_line', [], true)) ?>;
</script>
<!-- Account Form Modal -->
<?php
$this->load->view('accounts/modal_form');
?>
<!-- Item Form Modal -->
<?php
$this->load->view('items/modal_form');
?>