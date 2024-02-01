
<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative hide-print">
        <div class="row">
            <div class="col-lg-12">
                <h3>Dashboard</h3>
                <p class="mb-0">

                </p>

            </div>
        </div>
    </div>
</div>
<div class="row g-0">
    <div class="col-lg-12 pe-lg-2">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-xxl-12">
                        <div class="card rounded-3 overflow-hidden h-10">
                            <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-1.png);">
                            </div>
                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="row align-items-center g-0">
                                    <div class="col">
                                        <h3 class="text-danger mb-0">Today <?php echo $sum_of_sales_today . ' CHF' ?></h3>
                                        <p class="fs--1 fw-semi-bold text-danger">Yesterday <span class="opacity-50"><?php echo $sum_of_sales_yesterday . ' CHF' ?></span></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-xxl-12">
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6 col-md-4">
                                <div class="card overflow-hidden" style="min-width: 12rem">
                                    <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-1.png);">
                                    </div>
                                    <!--/.bg-holder-->

                                    <div class="card-body position-relative">
                                        <h6>Customers</h6>
                                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-warning" data-countup='{"endValue":58.386,"decimalPlaces":2,"suffix":"k"}'><?php echo $count_of_customers ?></div><a class="fw-semi-bold fs--1 text-nowrap" href="accounts/index">See all<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="card overflow-hidden" style="min-width: 12rem">
                                    <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-2.png);">
                                    </div>
                                    <!--/.bg-holder-->

                                    <div class="card-body position-relative">
                                        <h6>Orders</h6>
                                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info" data-countup='{"endValue":23.434,"decimalPlaces":2,"suffix":"k"}'><?php echo $count_of_orders ?></div><a class="fw-semi-bold fs--1 text-nowrap" href="orders/index">All orders<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card overflow-hidden" style="min-width: 12rem">
                                    <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-3.png);">
                                    </div>
                                    <!--/.bg-holder-->

                                    <div class="card-body position-relative">
                                        <h6>Products</h6>
                                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-warning" data-countup='{"endValue":43594,"prefix":"$"}'><?php echo $count_of_products ?></div><a class="fw-semi-bold fs--1 text-nowrap" href="items/index">Statistics<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card overflow-hidden" style="min-width: 12rem">
                                    <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
                                    </div>
                                    <!--/.bg-holder-->

                                    <div class="card-body position-relative">
                                        <h6>System Users</h6>
                                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-danger" data-countup='{"endValue":43594,"prefix":"$"}'><?php echo $count_of_users ?></div><a class="fw-semi-bold fs--1 text-nowrap" href="users/index">Statistics<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card overflow-hidden" style="min-width: 12rem">
                                    <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-5.png);">
                                    </div>
                                    <!--/.bg-holder-->

                                    <div class="card-body position-relative">
                                        <h6>Suppliers</h6>
                                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif text-info" data-countup='{"endValue":43594,"prefix":"$"}'><?php echo $count_of_suppliers ?></div><a class="fw-semi-bold fs--1 text-nowrap" href="accounts/index">Statistics<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card overflow-hidden" style="min-width: 12rem">
                                    <div class="bg-holder bg-card" style="background-image:url(assets/img/icons/spot-illustrations/corner-6.png);">
                                    </div>
                                    <!--/.bg-holder-->

                                    <div class="card-body position-relative">
                                        <h6>Warehouses</h6>
                                        <div class="display-4 fs-4 mb-2 fw-normal font-sans-serif" data-countup='{"endValue":43594,"prefix":"$"}'><?php echo $count_of_warehouses ?></div><a class="fw-semi-bold fs--1 text-nowrap" href="warehouses/index">Statistics<span class="fas fa-angle-right ms-1" data-fa-transform="down-1"></span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-8">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <div class="row justify-content-between gx-0">
                                            <div class="col-auto">
                                                <h1 class="fs-0 text-900">Gross revenue</h1>
                                                <div class="d-flex">
                                                    <h4 class="text-primary mb-0">$165.50</h4>
                                                    <div class="ms-3"><span class="badge rounded-pill badge-subtle-primary"><span class="fas fa-caret-up"></span> 5%</span></div>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <select class="form-select form-select-sm form-select form-select-sm-sm pe-4" id="select-gross-revenue-month">
                                                    <option value="0">Jan</option>
                                                    <option value="1">Feb</option>
                                                    <option value="2">Mar</option>
                                                    <option value="3">Apr</option>
                                                    <option value="4">May</option>
                                                    <option value="5">Jun</option>
                                                    <option value="6">Jul</option>
                                                    <option value="7">Aug</option>
                                                    <option value="8">Sep</option>
                                                    <option value="9">Oct</option>
                                                    <option value="10">Nov</option>
                                                    <option value="11">Dec</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-0 pb-3 h-100">
                                        <div class="mx-nx1">
                                            <table class="table table-borderless font-sans-serif fw-medium fs--1">
                                                <tr>
                                                    <td class="pb-2 pt-0">Point of sale</td>
                                                    <td class="pb-2 pt-0 text-end" style="width: 20%">$791.64</td>
                                                    <td class="pb-2 pt-0 text-end text-700" style="max-width: 20%"><span class="me-1 fas fa-long-arrow-alt-down text-danger"></span>13%</td>
                                                </tr>
                                                <tr>
                                                    <td class="pb-2 pt-0">Online Store</td>
                                                    <td class="pb-2 pt-0 text-end" style="width: 20%">$113.86</td>
                                                    <td class="pb-2 pt-0 text-end text-700" style="max-width: 20%"><span class="me-1 fas fa-long-arrow-alt-up text-success"></span>178%</td>
                                                </tr>
                                                <tr>
                                                    <td class="pb-2 pt-0">Online Store</td>
                                                    <td class="pb-2 pt-0 text-end" style="width: 20%">$0.00</td>
                                                    <td class="pb-2 pt-0 text-end text-700" style="max-width: 20%"><span class="me-1 false text-success"></span>-</td>
                                                </tr>
                                            </table>
                                            <!-- Find the JS file for the following calendar at: src/js/charts/echarts/gross-revenue.js-->
                                            <!-- If you are not using gulp based workflow, you can find the transpiled code at: public/assets/js/theme.js-->
                                            <div class="echart-gross-revenue-chart px-3 h-100" data-echart-responsive="true" data-options='{"target":"gross-revenue-footer","monthSelect":"select-gross-revenue-month","optionOne":"currentMonth","optionTwo":"prevMonth"}'></div>
                                        </div>
                                    </div>
                                    <div class="card-footer border-top py-2 d-flex flex-between-center">
                                        <div class="d-flex" id="gross-revenue-footer">
                                            <div class="btn btn-sm btn-text d-flex align-items-center p-0 shadow-none" id="currentMonth" data-month="current"><span class="fas fa-circle text-primary fs--2 me-1"></span><span class="text">Jan</span></div>
                                            <div class="btn btn-sm btn-text d-flex align-items-center p-0 shadow-none ms-2" id="prevMonth" data-month="prev"><span class="fas fa-circle text-300 fs--2 me-1"></span><span class="text">Dec</span></div>
                                        </div><a class="btn btn-link btn-sm px-0" href="#!">View report<span class="fas fa-chevron-right ms-1 fs--2"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex flex-between-center border-bottom border-200 py-2">
                                        <h6 class="mb-0">Customer Satisfaction</h6>
                                        <div class="dropdown font-sans-serif btn-reveal-trigger">
                                            <button class="btn btn-link text-600 btn-sm dropdown-toggle dropdown-caret-none btn-reveal" type="button" id="dropdown-customer-satisfaction" data-bs-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false"><svg class="svg-inline--fa fa-ellipsis-h fa-w-16 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-h" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg><!-- <span class="fas fa-ellipsis-h fs--2"></span> Font Awesome fontawesome.com --></button>
                                            <div class="dropdown-menu dropdown-menu-end border py-2" aria-labelledby="dropdown-customer-satisfaction"><a class="dropdown-item" href="#!">View</a><a class="dropdown-item" href="#!">Export</a>
                                                <div class="dropdown-divider"></div><a class="dropdown-item text-danger" href="#!">Remove</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3 d-flex aligm-items-center">
                                        <div class="echart-customer-setisfaction w-100" data-echart-responsive="true" _echarts_instance_="ec_1680626613123" style="user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;"><div style="position: relative; width: 357px; height: 318px; padding: 0px; margin: 0px; border-width: 0px; cursor: pointer;"><canvas data-zr-dom-id="zr_0" width="357" height="318" style="position: absolute; left: 0px; top: 0px; width: 357px; height: 318px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div class="" style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; background-color: rgb(249, 250, 253); border-width: 1px; border-radius: 4px; color: rgb(11, 23, 39); font: 14px / 21px &quot;Microsoft YaHei&quot;; padding: 7px 10px; top: 0px; left: 0px; transform: translate3d(107px, 236px, 0px); border-color: rgb(216, 226, 239); pointer-events: none; visibility: hidden; opacity: 0;"><div style="margin: 0px 0 0;line-height:1;"><div style="margin: 0px 0 0;line-height:1;"><span style="display:inline-block;margin-right:4px;border-radius:10px;width:10px;height:10px;background-color:#2c7be5;"></span><span style="font-size:14px;color:#0b1727;font-weight:400;margin-left:2px">Positive</span><span style="float:right;margin-left:20px;font-size:14px;color:#0b1727;font-weight:900">1,100</span><div style="clear:both"></div></div><div style="clear:both"></div></div></div></div>
                                    </div>
                                    <div class="card-footer border-top border-200 py-0">
                                        <div class="row">
                                            <div class="col-6 border-end border-200 py-3 d-flex justify-content-center">
                                                <div>
                                                    <h6 class="text-600 mb-1 fs--2">Positive</h6>
                                                    <h6 class="fs-0 mb-0 d-flex align-items-center">150<small class="badge px-0 text-success bg-transparent d-flex align-items-start"><svg class="svg-inline--fa fa-caret-up fa-w-10 ms-2 me-1 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-up" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M288.662 352H31.338c-17.818 0-26.741-21.543-14.142-34.142l128.662-128.662c7.81-7.81 20.474-7.81 28.284 0l128.662 128.662c12.6 12.599 3.676 34.142-14.142 34.142z"></path></svg><!-- <span class="fas fa-caret-up ms-2 me-1 fs--2"></span> Font Awesome fontawesome.com --><span class="fs--1">23.3%</span></small></h6>
                                                </div>
                                            </div>
                                            <div class="col-6 py-3 d-flex justify-content-center">
                                                <div>
                                                    <h6 class="text-600 mb-1 fs--2">Negative</h6>
                                                    <h6 class="fs-0 mb-0 d-flex align-items-center">20<small class="badge px-0 text-danger d-flex align-items-start"><svg class="svg-inline--fa fa-caret-down fa-w-10 ms-2 me-1 fs--2" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="caret-down" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" data-fa-i2svg=""><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z"></path></svg><!-- <span class="fas fa-caret-down ms-2 me-1 fs--2"></span> Font Awesome fontawesome.com --><span class="fs--1">5.23%</span></small></h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>