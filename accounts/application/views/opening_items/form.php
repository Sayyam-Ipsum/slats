<div class="card mb-3">    <div class="bg-holder d-none d-lg-block bg-card"         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">    </div>    <!--/.bg-holder-->    <div class="card-body position-relative">        <div class="row">            <div class="col-lg-12">                <h3><?php echo $transTypeText ?></h3>                <p class="mb-0">                    <?php if ($this->session->flashdata('message_op')) { ?>                <div id="save_msg" class="alert alert-success" style="text-align:center"                     onclick="document.getElementById('save_msg').style.display = 'none'">                    <strong><?php echo $this->session->flashdata('message_op') ?></strong>                </div>                <?php } ?>                </p>            </div>        </div>    </div></div><div class="row g-0">    <div class="col-lg-12 pe-lg-2">        <div class="card mb-3">            <div class="card-header bg-light">                <p class="text-right" id="listbtn">                    <?php echo anchor('accounts/index', 'Back', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1 " id="bgback"') ?>                </p>            </div>            <?php echo form_open('', 'id="transactionForm" name="transactionForm" class="form-horizontal" role="form"  onsubmit="return validation();" novalidate') ?>            <div class="card-body">                <input name="id" id="id" type="hidden" value="<?php echo $this->Transaction->get_field('id') ?>"/>                <input name="item_id" id="item_line_id" type="hidden" value="<?php echo $item_id ?>"/>                <div class="row">                    <div class="col-md-6">                        <div class="mb-3">                            <label class="form-label"                                   for="auto_no">Opening Items #</label>                            <?php                            echo form_input('trans[auto_no]', $this->Transaction->get_field('auto_no'), 'id="auto_no" class="form-control form-control-sm" readonly="true" required '),                            $this->Transaction->get_error('auto_no')                            ?>                        </div>                    </div>                    <div class="col-md-6">                        <div class="mb-3">                            <label class="form-label"                                   for="trans_date">Date</label>                            <?php echo form_input('trans[trans_date]', $trans_date, 'id="trans_date" class="form-control form-control-sm" required'), $this->Transaction->get_error('trans_date') ?>                            <div id="error_trans_date" style="text-align:center"                                 onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>                        </div>                    </div>                    <div class="col-md-6">                        <div class="mb-3">                            <label class="form-label"                                   for="currency_code">Currency</label>                            <?php echo form_input('trans[currency_code]', "€", 'id="currency_code" class="form-control form-control-sm" readonly="true" ') ?>                        </div>                    </div>                    <div class="col-md-6">                        <div class="mb-3">                            <label class="form-label"                                   for="currency_id">Currency Rate</label>                            <?php echo form_input('trans[currency_rate]', $currency_rate, 'id="currency_rate" class="form-control form-control-sm"'), $this->Transaction->get_error('currency_rate') ?>                            <div id="error_currency_rate" style="text-align:center"                                 onclick="document.getElementById('error_currency_rate').style.display = 'none'"></div>                        </div>                    </div>                    <hr>                    <div class="row">                        <div class="col-md-6">                            <h6>Line Items</h6>                        </div>                        <div class="col-md-6">                            <button id="addline" class="btn btn-falcon-default btn-sm me-1 mb-1 text-end">Add Line                            </button>                        </div>                    </div>                    <div class="row">                        <div class="col-md-12">                            <table class="table table-bordered mb-0 fs--1 display compact" style="width:100%">                                <thead style="background-color: #D13131; color: white">                                <tr class="danger">                                    <th class="col-sm-1">Delete</th>                                    <th class="col-sm-1">EAN</th>                                    <th class="col-sm-1">Artical Number</th>                                    <th class="col-sm-2">Warehouse</th>                                    <th class="col-sm-2">Shelf</th>                                    <th class="col-sm-1">Open Quantity</th>                                </tr>                                </thead>                                <tbody id="transactionLines">                                <?php                                $this->load->view('opening_items/edit_opening_items_form_items_line', ['transItems' => isset($trans_items) ? $trans_items : []]);                                ?>                                </tbody>                            </table>                            <div id="error_transactionLines" style="text-align:center"                                 onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>                        </div>                    </div>                </div>            </div>            <div class="card-footer border-top border-200 d-flex flex-between-center">                <div class="d-flex align-items-center">                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-danger me-1 mb-1" id="bgsave" accesskey="s"'), ' ', form_reset('reset', $this->lang->line('reset'), 'class="btn btn-link"') ?>                </div>            </div>            <?php            echo form_close();            ?>        </div>    </div></div><script type="text/javascript">    const ItemLineTpl = <?php echo json_encode($this->load->view('opening_items/opening_items_form_items_line', [], true)) ?>;</script>