<?php if ($this->session->flashdata('message')) { ?>

    <div id="save_msg" class="alert alert-danger" style="text-align:center"
         onclick="document.getElementById('save_msg').style.display = 'none'">

        <strong><?php echo $this->session->flashdata('message') ?></strong>

    </div>

<?php } ?>

<div class="row flex-center min-vh-100 py-6">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4"><a class="d-flex flex-center mb-4"
                                                                   href=""><img class="me-2"
                                                                                                   src="assets/img/logos/logo-white-2.png"
                                                                                                   alt=""
                                                                                                   width="300"/></a>
        <div class="card">
            <div class="card-body p-4 p-sm-5">
                <div class="row flex-between-center mb-2">
                    <div class="col-auto">
                        <h5>Log in</h5>
                    </div>
                    <div class="col-auto fs--1 text-600 d-none"><span class="mb-0 undefined">or</span> <span><a
                                    href="../../../pages/authentication/simple/register.html">Create an account</a></span>
                    </div>
                </div>
                <?php echo form_open('', 'id="loginForm" name="loginForm" class="form-horizontal" role="form"  onsubmit="return validation();" autocomplete="off" novalidate'); ?>

                <div class="mb-3">
                    <input class="form-control form-control-sm" name="username" id="username" type="text"/>
                    <div id="error_username" style="text-align:center"
                         onclick="document.getElementById('error_username').style.display = 'none'"></div>
                </div>
                <div class="mb-3">
                    <input class="form-control form-control-sm" type="password" name="password" id="password"/>
                    <div id="error_password" style="text-align:center"
                         onclick="document.getElementById('error_password').style.display = 'none'"></div>
                </div>
                <div class="row flex-between-center">
                    <div class="col-auto">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="basic-checkbox" checked="checked"/>
                            <label class="form-check-label mb-0" for="basic-checkbox">Remember me</label>
                        </div>
                    </div>
                    <div class="col-auto"><a class="fs--1"
                                             href="../../../pages/authentication/simple/forgot-password.html">Forgot
                            Password?</a></div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-block w-100 mt-3" type="submit" name="submit">Log in</button>
                </div>
                <hr>
                <div class="mb-3">
                    <a class="btn btn-outline-dark border-2 rounded-pill btn-lg mt-4 fs-0 py-2" href="https://mioparts.com">Home<span class="fas fa-play ms-2" data-fa-transform="shrink-6 down-1"></span></a>
                </div>
                <?php echo form_close(); ?>
                <div class="position-relative mt-4 d-none">
                    <hr/>
                    <div class="divider-content-center">or log in with</div>
                </div>
                <div class="row g-2 mt-2 d-none">
                    <div class="col-sm-6"><a class="btn btn-outline-google-plus btn-sm d-block w-100" href="#"><span
                                    class="fab fa-google-plus-g me-2" data-fa-transform="grow-8"></span> google</a>
                    </div>
                    <div class="col-sm-6"><a class="btn btn-outline-facebook btn-sm d-block w-100" href="#"><span
                                    class="fab fa-facebook-square me-2" data-fa-transform="grow-8"></span> facebook</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
