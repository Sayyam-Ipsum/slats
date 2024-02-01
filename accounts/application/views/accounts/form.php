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

                </p>

            </div>
        </div>
    </div>
</div>
<div class="row g-0">
    <div class="col-lg-12 pe-lg-2">
        <div class="card mb-3">
            <div class="card-header bg-slats-red">
                <p class="text-right" id="listbtn">
                    <?php echo anchor('accounts/index', 'Back', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1 " id="bgback"') ?>
                </p>
            </div>
            <?php echo form_open('', 'id="accountForm" name="accountForm" class="form-horizontal" role="form" onsubmit="return validation();" autocomplete="off" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Account->get_field('id') ?>"/>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="account_type"><?php echo $this->lang->line('account_type') ?></label>
                            <?php
                            echo form_dropdown('account_type', $account_type, $this->Account->get_field('account_type'), 'id="account_type" class="form-select form-select-sm"'),
                            $this->Account->get_error('account_type');
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="account_number"><?php echo $this->lang->line('account_number') ?></label>
                            <?php
                            echo form_input('account_number', $this->Account->get_field('account_number'), 'id="account_number" class="form-control form-control-sm" required readonly'),
                            $this->Account->get_error('account_number')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="account_name"><?php echo $this->lang->line('account_name') ?></label>
                            <?php
                            echo form_input('account_name', $this->Account->get_field('account_name'), 'id="account_name" maxlength="255" class="form-control form-control-sm" required'),
                            $this->Account->get_error('account_name')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_id"><?php echo $this->lang->line('currency_type') ?></label>
                            <?php
                            echo form_dropdown('currency_id', $currenciesList, $this->Account->get_field('currency_id'), 'id="currency_id" class="form-select form-select-sm"'),
                            $this->Account->get_error('currency_id');
                            ?>
                            <div id="error_currency_id" style="text-align:center"
                                 onclick="document.getElementById('error_currency_id').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="opening_date"><?php echo $this->lang->line('opening_date') ?></label>
                            <?php
                            echo form_input('opening_date', $opening_date, 'id="opening_date" class="form-control form-control-sm" required'),
                            $this->Account->get_error('opening_date')
                            ?>
                            <div id="error_date" style="text-align:center"
                                 onclick="document.getElementById('error_date').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="payment_method"><?php echo $this->lang->line('payment_method') ?></label>
                            <?php
                            echo form_dropdown('payment_method', array('' => '', 'Monthly' => 'Monthly'), $this->Account->get_field('payment_method'), 'id="payment_method" class="form-select form-select-sm"'),
                            $this->Account->get_error('payment_method');
                            ?>
                            <div id="error_payment_method" style="text-align:center"
                                 onclick="document.getElementById('error_payment_method').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="type"><?php echo $this->lang->line('type') ?></label>
                            <?php
                            echo form_dropdown('type', $types, $this->Account->get_field('type'), 'id="type" maxlength="255" class="form-select form-select-sm"'),
                            $this->Account->get_error('type')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="phone"><?php echo $this->lang->line('phone') ?></label>
                            <?php
                            echo form_input('phone', $this->Account->get_field('phone'), 'id="phone" maxlength="255" class="form-control form-control-sm"'),
                            $this->Account->get_error('phone')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label" for="email"><?php echo $this->lang->line('email') ?></label>
                            <?php
                            echo form_input('email', $this->Account->get_field('email'), 'id="email" maxlength="255" class="form-control form-control-sm"'),
                            $this->Account->get_error('email')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="address"><?php echo $this->lang->line('address') ?></label>
                            <?php
                            echo form_input('address', $this->Account->get_field('address'), 'id="address" maxlength="255" class="form-control form-control-sm"'),
                            $this->Account->get_error('address')
                            ?>                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="open_balance"><?php echo $this->lang->line('open_balance') ?></label>
                            <?php
                            echo form_input('open_balance', $open_balance, 'id="open_balance" class="form-control form-control-sm"'),
                            $this->Account->get_error('open_balance')
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'id="savebtn" class="btn btn-danger me-1 mb-1" accesskey="s"'), ' ' // form_reset('reset', $this->lang->line('reset'), 'class="btn btn-link"')
                    ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>


