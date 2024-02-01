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
            <div class="card-header bg-light">
                <p class="text-right" id="listbtn">
                    <?php echo anchor('fiscal_years/index', 'Back', 'accesskey="b" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback"') ?>
                </p>
            </div>
            <?php echo form_open('', 'id="fiscalYearForm" class="form-horizontal" role="form" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Fiscal_year->get_field('id') ?>" />
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="year_name">Name</label>
                            <?php
                            echo form_input('year_name', $this->Fiscal_year->get_field('year_name'), 'autofocus id="year_name" maxlength="255" class="form-control form-control-sm" required'),
                            $this->Fiscal_year->get_error('year_name')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="start_date">Start Date</label>
                            <?php
                            echo form_input('start_date', $this->Fiscal_year->get_field('start_date'), 'id="start_date" class="form-control form-control-sm" required'),
                            $this->Fiscal_year->get_error('start_date')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="end_date">End Date</label>
                            <?php echo form_input('end_date', $this->Fiscal_year->get_field('end_date'), 'id="end_date" class="form-control form-control-sm" required'), $this->Fiscal_year->get_error('end_date')
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">

                    <?php
                    echo form_submit('submitBtn', 'Save', 'class="btn btn-danger me-1 mb-1" id="bgsave" accesskey="s"');?>
                    <?php
                    echo form_reset('reset', 'Reset', 'class="btn btn-primary btn-sm px-5 me-2" id="bgreset"')
                    ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>


