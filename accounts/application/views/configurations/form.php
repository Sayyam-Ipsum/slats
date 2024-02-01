<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <h3>Configurations</h3>
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

            </div>

            <p id="user_id" hidden><?php echo $this->violet_auth->get_user_id() ?></p>

            <?php echo form_open_multipart('', 'role="form" class="form-horizontal" id="frmConfigurations" autocomplete="off"') ?>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="year_name">Company Info</label>
                            <hr>
                            <?php
                            foreach ($configSet1 as $conf):
                                echo $conf;
                            endforeach;
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="start_date">Settings</label>
                            <hr>
                            <?php
                            foreach ($configSet2 as $conf):
                                echo $conf;
                            endforeach;
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">

                    <?php
                    echo form_submit('saveConfigurations', $this->lang->line('save'), 'class="btn btn-primary btn-sm px-5 me-2" id="bgsave" ');
                    ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>



