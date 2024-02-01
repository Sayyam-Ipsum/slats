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
                <p class="text-right" id="listbtn">
                    <?php echo anchor('receipts/index', 'Back', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback"') ?>
                    <?php echo anchor('receipts/preview/' . $this->Journal->get_field('id'), 'Print', 'accesskey="p" class="btn btn-falcon-default btn-sm me-1 mb-1"  id="print"') ?>
                </p>
            </div>
            <?php echo form_open('', 'id="transactionForm" name="transactionForm" class="form-horizontal" role="form" onsubmit="return validation();" autocomplete="off" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Journal->get_field('id') ?>"/>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="auto_no">Payment #</label>
                            <?php
                            echo form_input('trans[auto_no]', $this->Journal->get_field('auto_no'), 'id="auto_no" class="form-control form-control-sm" readonly="true" required '),
                            $this->Journal->get_error('auto_no')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_id"><?php echo $this->lang->line('currency') ?></label>
                            <?php
                            echo form_dropdown('currency_id_list', $currenciesList, $this->Journal->get_field('currency_id'), 'id="currency_id_list" class="form-select form-select-sm"'),
                            $this->Journal->get_error('currency_id');
                            ?>
                            <input name="trans[currency_id]" id="currency_id" type="hidden"
                                   value="<?php echo $this->Journal->get_field('currency_id') ?>"/>
                            <div id="error_currency_id" style="text-align:center"
                                 onclick="document.getElementById('error_currency_id').style.display = 'none'"></div>

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_rate">Currency Rate</label>
                            <?php
                            echo form_input('trans[currency_rate]', $this->Journal->get_field('currency_rate'), 'id="currency_rate" class="form-control form-control-sm" required'),
                            $this->Journal->get_error('currency_rate')
                            ?>
                            <div id="error_currency_rate" style="text-align:center"
                                 onclick="document.getElementById('error_currency_rate').style.display = 'none'"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="acctLkupTo">Account From</label>
                            <?php echo form_input('', $account, 'id="acctLkupTo" maxlength="10" class="form-control form-control-sm"') ?>
                            <input name="trans[account_id]"
                                   value="<?php echo $this->Journal->get_field('account_id') ?>" id="account_id"
                                   type="hidden"/>
                            <div id="error_acctLkupTo" style="text-align:center"
                                 onclick="document.getElementById('error_acctLkupTo').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="acctLkupFrom">Account To</label>
                            <?php echo form_input('', $account2, 'id="acctLkupFrom" maxlength="10" class="form-control form-control-sm"') ?>
                            <input name="trans[account2_id]"
                                   value="<?php echo $this->Journal->get_field('account2_id') ?>" id="account2_id"
                                   type="hidden"/>
                            <div id="error_acctLkupFrom" style="text-align:center"
                                 onclick="document.getElementById('error_acctLkupFrom').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="journal_date">Date</label>
                            <?php echo form_input('trans[journal_date]', $journal_date, 'id="journal_date" class="form-control form-control-sm" required'), $this->Journal->get_error('journal_date')
                            ?>
                            <div id="error_trans_date" style="text-align:center"
                                 onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="balance">Balance</label>
                            <?php echo form_input('trans[balance]', "0", 'id="balance"  class="form-control form-control-sm" readonly="true"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="amount">Amount</label>
                            <?php
                            echo form_input('trans[amount]', $this->Journal->get_field('amount'), 'id="amount" class="form-control form-control-sm" style="border-color:grey;" autocomplete="off" required'),
                            $this->Journal->get_error('currency_rate')
                            ?>
                            <div id="error_amount" style="text-align:center"
                                 onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="description">Description</label>
                            <?php echo form_textarea('trans[description]', $this->Journal->get_field('description'), 'id="description" class="form-control form-control-sm" style="height:100px;"') ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-primary btn-sm px-5 me-2" id="bgsave" accesskey="s"'), ' ', form_reset('reset', $this->lang->line('reset'), 'class="btn btn-link"') ?>

                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    const ItemLineTpl = <?php echo json_encode($this->load->view('transactions/purhcase_form_items_line', [], true)) ?>;
</script>