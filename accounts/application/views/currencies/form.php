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
                    <?php echo anchor('currencies/index', 'Back', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback" ') ?>
                </p>
            </div>
            <?php echo form_open('', 'class="form-horizontal" role="form" autocomplete="off" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Currency->get_field('id') ?>"/>
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_name"><?php echo $this->lang->line('currency_name') ?></label>
                            <?php
                            echo form_input('currency_name', $this->Currency->get_field('currency_name'), 'id="currency_name" maxlength="255" class="form-control form-control-sm" required'),
                            $this->Currency->get_error('currency_name')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_code"><?php echo $this->lang->line('currency_code') ?></label>
                            <?php
                            echo form_input('currency_code', $this->Currency->get_field('currency_code'), 'id="currency_code" maxlength="3" class="form-control form-control-sm" required'),
                            $this->Currency->get_error('currency_code')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="currency_rate"><?php echo $this->lang->line('currency_rate') ?></label>
                            <?php
                            echo form_input('currency_rate', $this->Currency->get_field('currency_rate'), 'id="currency_rate" class="form-control form-control-sm" required'),
                            $this->Currency->get_error('currency_rate')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <div id="error_trans_date"
                                 style="text-align: left; color: white; background-color: #808080; border-color: #404040; padding: 7px; border-radius:5px;"
                                 onclick="document.getElementById('error_trans_date').style.display = 'none'">
                                <strong>
                                    <p>Example:</p>
                                    <p>If System Currency: Dollar</p>
                                    <p>To calculate currency rate in Euro:</p>
                                    <p>1$ ----> 0.85€</p>
                                    <p>Then, currency rate = 0.85</p>
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-danger me-1 mb-1" id="bgsave" accesskey="s"'), ' ', form_reset('reset', $this->lang->line('reset'), 'class="btn btn-link"') ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>

