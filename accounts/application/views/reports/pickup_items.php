<style>    table td {        word-break: break-word;        vertical-align: top;        white-space: normal !important;    }</style><div class="row g-0">    <div id="report_form_div" class="col-lg-12 pe-lg-2">        <div class="card mb-1">            <?php echo form_open('reports/pickup_items', 'id="pickup_form"'); ?>            <div class="card-body">                <div class="row">                    <div class="col-md-2">                        <div class="mb-1"><h3><?php echo $title ?></h3></div>                    </div>                </div>                <hr>                <div class="row">                    <div class="col-md-2">                        <div class="mb-1">                            <label class="form-label"                                   for="trans_date">From Date</label>                            <?php echo form_input('trans_date', $date, 'id="trans_date" class="form-control form-control-sm i-trans_date" autocomplete="off"') ?>                        </div>                    </div>                    <div class="col-md-2">                        <div class="mb-1">                            <label class="form-label"                                   for="to_date">To Date</label>                            <?php echo form_input('to_date', $to_date, 'id="to_date" class="form-control form-control-sm i-trans_date" autocomplete="off"') ?>                        </div>                    </div>                    <div class="col-md-3">                        <div class="mb-3">                            <label class="form-label"                                   for="type">Customer</label>                            <?php echo form_input('customer_name', $customer_name, 'id="customer_name" class="form-control form-control-sm" autocomplete="off"') ?>                            <input type="text" name="customer_id" id="customer_id" value="<?php echo $customer_id ?>"                                   hidden>                        </div>                    </div>                    <div class="col-md-1">                        <div class="mb-1">                            <label class="form-label"                                   for="order_nb"><?php echo $this->lang->line('order') . "#"; ?></label>                            <?php echo form_input('order_nb', $order_nb, 'id="order_nb" class="form-control form-control-sm" autocomplete="off"') ?>                        </div>                    </div>                    <div class="col-md-4">                        <div class="mb-0">                            <label class="form-label"                                   for="item"><?php echo $this->lang->line('select_item'); ?></label>                            <?php echo form_input('item', $item_name, 'id="item" class="form-control form-control-sm" autocomplete="off"') ?>                            <input type="text" name="item_id" id="item_id" value="<?php echo $item_id ?>" hidden>                        </div>                    </div>                    <div class="col-md-6">                        <div class="mb-0">                            <div class="d-flex align-items-center">                                <br>                                <button type="submit" class="btn btn-primary btn-sm px-5 me-2" id="search_btn">Search                                </button>                                <button id="reset" type="reset" class="btn btn-primary btn-sm px-5 me-2">Reset                                </button>                                <button onClick="window.print()" class="btn btn-primary btn-sm px-5 me-2"                                        id="print">                                    Print                                </button>                            </div>                        </div>                    </div>                </div>            </div>            <?php            echo form_close();            ?>        </div>    </div>    <div class="card mb-1">        <div class="card-body">            <div class="row">                <?php if ($records !== []) { ?>                    <div class="col-md-3 table-responsive fs--1">                        <h6>All Customers</h6>                        <div class="table-responsive fs--1">                            <table class="table table-bordered  mb-0 fs--1"                                   id="customers_table">                                <thead style="background-color: #D13131; color: white">                                <tr>                                    <th style="width: 30px;">#</th>                                    <th style="text-align: left;">Customer</th>                                    <th style="text-align: left;">Select</th>                                </tr>                                </thead>                                <tbody>                                <?php if ($customer_id && !$date && !$to_date) {                                    foreach ($records as $k => $record) {                                        if ($customer_id == $record['account_id']) {                                            ?>                                            <tr>                                                <td><b><?php echo 1 ?></b></td>                                                <td style="text-align: center; color: white"                                                    class="<?php echo ($record['done'] == 1) ? 'bg-success' : '' ?>">                                                    <strong><?php echo $record['account_number'] . ' - ' . $record['account_name'] ?></strong>                                                </td>                                                <td style="text-align: left; color: white">                                                    <p hidden><?php echo $record['account_number'] . ' - ' . $record['account_name'] ?></p>                                                    <input type="text" class="c-acc_id"                                                           value="<?php echo $record['account_id'] ?>" hidden>                                                    <button type="button" style="background-color: #444; color: white;"                                                            class="c-acc_btn"><i                                                                class="fas fa-check-circle"></i></button>                                                </td>                                            </tr>                                            <?php                                            // break;                                        }                                    }                                } else { ?>                                    <?php foreach ($records as $k => $record) {                                        ?>                                        <tr>                                            <td><?php echo $k + 1 ?></td>                                            <td style="text-align: left; color: white"                                                class="<?php echo ($record['done'] == 1) ? 'bg-success' : 'bg-danger' ?>">                                                <strong><?php echo $record['account_number'] . ' - ' . $record['account_name'] ?></strong>                                            </td>                                            <td style="text-align: left; color: white">                                                <p hidden><?php echo $record['account_number'] . ' - ' . $record['account_name'] ?></p>                                                <input type="text" class="c-acc_id"                                                       value="<?php echo $record['account_id'] ?>" hidden>                                                <button type="button" style="background-color: #444; color: white;"                                                        class="c-acc_btn"><i                                                            class="fas fa-check-circle"></i></button>                                            </td>                                        </tr>                                    <?php }                                } ?>                                </tbody>                            </table>                        </div>                    </div>                <?php } ?>                <?php if ($records !== [] && $orders !== []) { ?>                    <div class="col-md-9 table-responsive fs--1">                        <h6><?php echo $customer_name ?> &nbsp;Orders</h6>                        <table class="table table-bordered  mb-0 fs--1" id="customers_table">                            <thead style="background-color: #D13131; color: white">                            <tr>                                <th>#</th>                                <th style="text-align: left;">Order #</th>                                <th class="d-none" style="text-align: left;">Invoice #</th>                                <th class="d-none" style="text-align: left;">Quote #</th>                                <th style="text-align: left;">Order Date</th>                                <th style="text-align: left;">Select</th>                            </tr>                            </thead>                            <tbody>                            <?php if ($order_nb && !$date && !$to_date) {                                foreach ($orders as $k => $order) {                                    if ($order_nb == $order['auto_no']) {                                        if (!$order['sa_delivered'] || $order['sa_delivered'] == '0') { ?>                                            <tr>                                                <td><b><?php echo 1 ?></b></td>                                                <td class="<?php echo ($order['sa_id']) ? 'bg-success' : (($order['pick_not_sale'] == 1) ? 'bg-warning' : ''); ?>"                                                    style="text-align: left;"><b>                                                        <?php echo $order['auto_no'] ?></b>                                                </td>                                                <td class="d-none" style="text-align: left;">                                                    <b><?php echo $order['auto_no'] ?></b></td>                                                <td class="d-none" style="text-align: left;">                                                    <b><?php echo $order['auto_no'] ?></b></td>                                                <td style="text-align: left;"><b>                                                        <?php echo date("d-m-Y", strtotime($order['trans_date'])) ?></b>                                                </td>                                                <td style="text-align: left;">                                                    <input type="text" class="c-order_nb"                                                           value="<?php echo $order['auto_no'] ?>"                                                           hidden>                                                    <button style="background-color: #444; color: white;"                                                            class="c-order_btn"><i                                                                class="fas fa-check-circle"></i></button>                                                </td>                                            </tr>                                        <?php }                                        // break;                                    }                                }                            } else {                                $count = 1;                                foreach ($orders as $k => $order) {                                    if (!$order['sa_delivered'] || $order['sa_delivered'] == '0') {                                        ?>                                        <tr>                                            <td><b><?php echo $count ?></b></td>                                            <td class="<?php echo ($order['sa_id']) ? 'bg-success' : (($order['pick_not_sale'] == 1) ? 'bg-warning' : ''); ?>"                                                style="text-align: left;"><b>                                                    <?php echo $order['auto_no'] ?></b>                                            </td>                                            <td style="text-align: left;"><b>                                                    <?php echo date("d-m-Y", strtotime($order['trans_date'])) ?></b>                                            </td>                                            <td style="text-align: center;">                                                <input type="text" class="c-order_nb"                                                       value="<?php echo $order['auto_no'] ?>" hidden>                                                <button style="background-color: #444; color: white;"                                                        class="c-order_btn"><i                                                            class="fas fa-check-circle"></i></button>                                            </td>                                        </tr>                                        <?php $count++;                                    }                                }                            }                            ?>                            </tbody>                        </table>                        <hr>                        <?php if ($records !== [] && $orders !== [] && $order_items !== []) { ?>                            <input type="text" name="os_id" id="os_id"                                   value="<?php echo $order_items[0]['os_id'] ?>" hidden>                            <div class="table-responsive fs--1">                                <table class="table table-bordered  mb-0 fs--1"                                       id="customers_table">                                    <thead style="background-color: #D13131; color: white">                                    <tr>                                        <th colspan="12" style="text-align: left; color: white"><h6                                                    style="color: white">Order #<?php echo $order_nb ?></h6></th>                                        <th>                                            <input type="hidden" class="form-control form-control-sm" id="focus_inp">                                            <button type="button"                                                    class="btn btn-falcon-default btn-sm me-1 mb-1 <?php echo ($hide_SA_btn == 1) ? 'hide' : '' ?>"                                                    id="to_invoice_btn"                                                    title="To Invoice"                                                    style="background-color: #18bc9c; color: white;">                                                <i class="fas fa-file-invoice"></i>                                            </button>                                        </th>                                    </tr>                                    <tr>                                        <th>#</th>                                        <th style="text-align: center;" hidden>op #</th>                                        <th style="text-align: center;">Desc.</th>                                        <th style="text-align: center; width: 130px">EAN</th>                                        <th style="text-align: center; width: 130px">Artical Nb</th>                                        <th style="text-align: center; width: 100px">Warehouse</th>                                        <th style="text-align: center; width: 100px">Shelf</th>                                        <th style="text-align: center;">Brand</th>                                        <th style="text-align: center;">Ordered</th>                                        <th style="text-align: center;">Pickedup</th>                                        <th style="text-align: center;">Needed</th>                                        <th style="text-align: center;">Status</th>                                        <th style="text-align: center;">Scan</th>                                        <th style="text-align: center;">Count</th>                                    </tr>                                    </thead>                                    <tbody id="order_items_tbody">                                    <?php foreach ($order_items as $k => $item) {                                        ?>                                        <tr>                                            <td>                                                <b><?php echo $k + 1 ?></b>                                                <input type="text" name="transItems[<?php echo $k ?>][item_id]"                                                       class="c-item_id"                                                       value="<?php echo $item['item_id'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][trans_item_id]"                                                       class="c-trans_item_id" value="<?php echo $item['id'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][op_id]"                                                       value="<?php echo $item['op_id'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][warehouse_id]"                                                       value="<?php echo $item['warehouse_id'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][cost]"                                                       value="<?php echo $item['cost'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][item_profit]"                                                       value="<?php echo $item['item_profit'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][price]"                                                       value="<?php echo $item['price'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][discount]"                                                       value="<?php echo $item['discount'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][net_cost]"                                                       value="<?php echo $item['net_cost'] ?>" hidden>                                                <input type="text" name="transItems[<?php echo $k ?>][mvt_type]"                                                       value="-1" hidden>                                            </td>                                            <td style="text-align: center;" hidden>                                                <b><?php echo $item['op_nb'] ?></b>                                            </td>                                            <td style="text-align: center; <?php echo ($item_id == $item['item_id']) ? 'background: #888; color: white;' : '' ?>">                                                <b><?php echo $item['description'] ?></b>                                            </td>                                            <td style="text-align: center; <?php echo ($item_id == $item['item_id']) ? 'background: #888; color: white;' : '' ?>"                                                class="td-ean">                                                <b><?php echo $item['EAN'] ?></b>                                            </td>                                            <td style="text-align: center; <?php echo ($item_id == $item['item_id']) ? 'background: #888; color: white;' : '' ?>"                                                class="td-artical_nb">                                                <b><?php echo $item['artical_number'] ?></b>                                            </td>                                            <td style="text-align: center;">                                                <b><?php echo $item['warehouse'] ?></b>                                            </td>                                            <td style="text-align: center;">                                                <b><?php echo $item['shelf'] ?></b>                                            </td>                                            <td style="text-align: center;">                                                <b><?php echo $item['brand'] ?></b>                                            </td>                                            <td class="td-ordered_qty" style="text-align: center;">                                                <b><?php echo $item['qty'] ?></b>                                            </td>                                            <td class="td-pickedup_qty" style="text-align: center;">                                                <b><?php echo ($item['pickedup_qty']) ? $item['pickedup_qty'] : 0; ?></b>                                                <input type="text" name="transItems[<?php echo $k ?>][pickedup_qty]"                                                       value="<?php echo ($item['pickedup_qty']) ? $item['pickedup_qty'] : 0; ?>"                                                       hidden>                                            </td>                                            <td style="text-align: center;" class="td-needed_qty">                                                <b><?php echo $item['needed_qty'] ?></b>                                            </td>                                            <td style="text-align: center; <?php echo ($item['status'] == 'Done') ? 'color:green;' : 'color:red;'; ?>">                                                <b><?php echo $item['status'] ?></b>                                            </td>                                            <td>                                                <input type="text"                                                       class="form-control form-control-sm c-inp_scan" <?php echo ($item['status'] == 'Done') ? 'readonly' : ''; ?>>                                            </td>                                            <td style="text-align: center;" class="td-count">                                                <p class="c-order_count">0</p>                                                <input type="text" name="transItems[<?php echo $k ?>][qty]"                                                       class="c-order_qty"                                                       value="0"                                                       hidden>                                            </td>                                        </tr>                                    <?php } ?>                                    </tbody>                                </table>                                <div id="error_transactionLines" style="text-align:right"                                     onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>                                <hr>                                <p style="text-align: right;">                                    <button type="button" class="btn btn-falcon-default btn-sm me-1 mb-1"                                            id="pickup_update_btn">Submit                                    </button>                                </p>                            </div>                        <?php } ?>                        <hr>                        <div id="AvTbDiv">                            <h6>Availability Table</h6>                            <div class="col-md-12 table-responsive fs--1">                                <table class="table table-bordered  mb-0 fs--1"                                       id="activityitemsTbl">                                    <thead style="background-color: #D13131; color: white">                                    <tr>                                        <th><?php echo $this->lang->line('name'); ?></th>                                        <th><?php echo $this->lang->line('brand'); ?></th>                                        <th><?php echo $this->lang->line('artical_number'); ?></th>                                        <th><?php echo $this->lang->line('warehouse'); ?></th>                                        <th><?php echo $this->lang->line('shelf'); ?></th>                                        <th><?php echo $this->lang->line('qty'); ?></th>                                    </tr>                                    </thead>                                    <tbody>                                    <tr>                                        <td colspan='6' style='text-align: center;'>No Data Found</td>                                    </tr>                                    </tbody>                                </table>                            </div>                        </div>                    </div>                <?php } ?>            </div>        </div>    </div>    <!--   <div class="card">           <div class="card-body">           </div>       </div>--></div></div>