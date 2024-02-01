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
                            <?php echo anchor('purchases/exit/' . $this->Transaction->get_field('id'), 'Exit', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback"') ?>
                            <?php echo anchor('purchases/preview/' . $this->Transaction->get_field('id'), 'Print', 'accesskey="p" class="btn btn-falcon-default btn-sm me-1 mb-1"  id="print"') ?>
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
            <?php echo form_open('', 'id="transactionForm" name="transactionForm" class="form-horizontal" role="form"  onsubmit="return validation();" autocomplete="off" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Transaction->get_field('id') ?>"/>
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="status">Checked</label>
                            <input type='hidden' value='0' name='trans[status]'>
                            <input name="trans[status]" value='1' type="checkbox" style="transform: scale(1.5);"
                                   id="status" class="input.i-status" <?php echo($status == 1 ? 'checked' : ''); ?>>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="auto_no">Purchase #</label>
                            <?php echo form_input('trans[auto_no]', $this->Transaction->get_field('auto_no'), 'id="auto_no" class="form-control form-control-sm" readonly="true" required '), $this->Transaction->get_error('auto_no') ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_id"><?php echo $this->lang->line('purchase_currency') ?></label>
                            <?php echo form_dropdown('currency_id_list', $currenciesList, $this->Transaction->get_field('currency_id'), 'id="currency_id_list" class="form-select form-select-sm"'), $this->Transaction->get_error('currency_id'); ?>
                            <input name="trans[currency_id]" id="currency_id" type="hidden"
                                   value="<?php echo $this->Transaction->get_field('currency_id') ?>"/>
                            <div id="error_currency_id" style="text-align:center"
                                 onclick="document.getElementById('error_currency_id').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_rate">Currency Rate</label>
                            <?php echo form_input('trans[currency_rate]', $this->Transaction->get_field('currency_rate'), 'id="currency_rate" class="form-control form-control-sm" required'), $this->Transaction->get_error('currency_rate') ?>
                            <div id="error_currency_rate" style="text-align:center"
                                 onclick="document.getElementById('error_currency_rate').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="acctLkupTo">Supplier Account</label>
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
                                   for="acctLkupFrom">Purchases Account</label>
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
                            <label class="form-label" for="warehouse">Warehouse</label>
                            <?php echo form_dropdown('trans[warehouse]', $warehouses_list, $trans_warehouse, 'id="trans_warehouse" class="form-select form-select-sm"')
                            ?>
                            <div id="error_warehouse" style="text-align:center"
                                 onclick="document.getElementById('error_warehouse').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="shelf">Shelf</label>
                            <?php echo form_dropdown('trans[shelf]', $trans_shelf_list, $trans_shelf, 'id="trans_shelf" class="form-select form-select-sm"')
                            ?>
                            <div id="error_shelf" style="text-align:center"
                                 onclick="document.getElementById('error_shelf').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="invoice_related_nb">Invoice Nb</label>
                            <?php
                            echo form_input('trans[invoice_related_nb]', $this->Transaction->get_field('invoice_related_nb'), 'id="invoice_related_nb" class="form-control form-control-sm" required ')
                            ?>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="description">Description</label>
                            <?php echo form_textarea('trans[description]', $this->Transaction->get_field('description'), 'id="description" class="form-control form-control-sm" style="height:100px;"') ?>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <hr>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Items</label>
                            <div class="table-responsive fs--1">

                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr class="danger">
                                        <th class="col-sm-1">#</th>
                                        <th class="col-sm-1">Name</th>
                                        <th class="col-sm-1">Brand</th>
                                        <th class="col-sm-1">Artical Nb</th>
                                        <th class="col-sm-1">EAN</th>
                                        <th class="col-sm-1">Alternative</th>
                                        <th class="col-sm-2 d-none">Warehouse</th>
                                        <th class="col-sm-2 d-none">Shelf</th>
                                        <th class="col-sm-1">Quantity</th>
                                        <th class="col-sm-1 d-none">Unit Cost</th>
                                        <th class="col-sm-1 d-none">Extra Cost %</th>
                                        <th class="col-sm-1 d-none">Discount %</th>
                                        <th class="col-sm-1 text-center d-none">Net Cost</th>
                                        <th class="col-sm-1 d-none">Total</th>
                                        <th class="col-sm-1 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="transactionLines">
                                    <?php
                                    $this->load->view('transactions/edit_purhcase_form_items_line', ['transItems' => isset($trans_items) ? $trans_items : []]);
                                    ?>
                                    </tbody>
                                </table>
                                <script>
                                    $(function () {
                                        $("#transactionLines").sortable();
                                    });
                                </script>
                                <div id="error_transactionLines" style="text-align:center"
                                     onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>
                            </div>

                        </div>
                    </div>
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
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgsave" accesskey="s"') ?>
                    <?php echo form_submit('submitBtn', "Save & Exit", 'class="btn btn-falcon-default btn-sm me-1 mb-1"') ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
        <div class="card mb3" <?php echo ($show_tables === 1) ? '' : 'hidden'; ?>>
            <div class="card-body">
                <div class="row" >
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Missing Products</label>
                            <div class="table-responsive fs--1">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr class="danger">
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Artical Nb</th>
                                        <th>EAN</th>
                                        <th>qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($missing_items) {
                                        $count = 0;
                                        foreach ($missing_items as $m) {
                                            $count++; ?>
                                            <tr>
                                                <td class="col-sm-1"><?php echo $count ?></td>
                                                <td class="col-sm-1"><?php echo $m['description'] ?></td>
                                                <td class="col-sm-1"><?php echo $m['brand'] ?></td>
                                                <td class="col-sm-1"><?php echo $m['artical_number'] ?></td>
                                                <td class="col-sm-1"><?php echo $m['EAN'] ?></td>
                                                <td class="<?php echo (array_key_exists('qty_diff', $m)) ? 'danger' : ''; ?>"><?php echo $m['qty'] ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6">
                                                <center>No Data Found</center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Extra Products</label>
                            <div class="table-responsive fs--1">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr class="danger">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Artical Nb</th>
                                        <th>EAN</th>
                                        <th>qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($extra_items) {
                                        $count = 0;
                                        foreach ($extra_items as $m) {
                                            $count++; ?>
                                            <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $m['description'] ?></td>
                                                <td><?php echo $m['brand'] ?></td>
                                                <td><?php echo $m['artical_number'] ?></td>
                                                <td><?php echo $m['EAN'] ?></td>
                                                <td class="<?php echo (array_key_exists('qty_diff', $m)) ? 'danger' : ''; ?>"><?php echo $m['qty'] ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6">
                                                <center>No Data Found</center>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="transaction_number">Return Purchases</label>
                            <div class="table-responsive fs--1">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                    <tr class="danger">
                                        <th>#</th>
                                        <th>Return#</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Artical Nb</th>
                                        <th>EAN</th>
                                        <th>Warehouse</th>
                                        <th>Shelf</th>
                                        <th>Qty</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php if ($returns) {
                                        $count = 0;
                                        foreach ($returns as $r) {
                                            $count++; ?>
                                            <tr>
                                                <td><?php echo $count ?></td>
                                                <td><?php echo $r['auto_no'] ?></td>
                                                <td><?php echo $r['trans_date'] ?></td>
                                                <td><?php echo $r['description'] ?></td>
                                                <td><?php echo $r['brand'] ?></td>
                                                <td><?php echo $r['artical_number'] ?></td>
                                                <td><?php echo $r['EAN'] ?></td>
                                                <td><?php echo $r['warehouse'] ?></td>
                                                <td><?php echo $r['shelf'] ?></td>
                                                <td><?php echo $r['qty'] ?></td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="10">
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

        <script type="text/javascript">
            const ItemLineTpl = <?php echo json_encode($this->load->view('transactions/purhcase_form_items_line', [], true)) ?>;
        </script>
        <!-- Account Form Modal -->
        <?php
        $this->load->view('accounts/modal_form');
        ?>
        <!-- Item Form Modal -->
<?php
$this->load->view('items/modal_form');
?>