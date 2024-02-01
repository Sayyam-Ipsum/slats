<nav class="navbar navbar-light navbar-vertical navbar-expand-xl">
    <script>
        var navbarStyle = localStorage.getItem("navbarStyle");
        if (navbarStyle && navbarStyle !== 'transparent') {
            document.querySelector('.navbar-vertical').classList.add(`navbar-${navbarStyle}`);
        }
    </script>
    <div class="d-flex align-items-center">
        <?php
        if ($this->session->userdata('vauth_fiscal_year_id')) {
            if ($this->violet_auth->get_user_type() === "Admin" || $this->violet_auth->get_user_type() === "Master Admin") {
                ?>
                <div class="toggle-icon-wrapper">

                    <button class="btn navbar-toggler-humburger-icon navbar-vertical-toggle" data-bs-toggle="tooltip"
                            data-bs-placement="left" title="Toggle Navigation"><span class="navbar-toggle-icon"><span
                                    class="toggle-line"></span></span></button>

                </div>
                <?php
            }
        }
        ?>
        <a class="navbar-brand" href="">
            <div class="d-flex align-items-center py-3"><img class="me-2" src="assets/img/logos/logo-white-2.png" alt=""
                                                             width="120"/><span class="font-sans-serif" style="color: red"></span>
            </div>
        </a>
    </div>
    <?php
    if ($this->session->userdata('vauth_fiscal_year_id')) {
        if ($this->violet_auth->get_user_type() === "Admin" || $this->violet_auth->get_user_type() === "Master Admin") {
            ?>
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <div class="navbar-vertical-content scrollbar">
                    <ul class="navbar-nav flex-column mb-3" id="navbarVerticalNav">
                        <li class="nav-item">
                            <!-- label-->
                            <div class="row navbar-vertical-label-wrapper mt-3 mb-2">
                                <div class="col-auto navbar-vertical-label">Menu
                                </div>
                                <div class="col ps-0">
                                    <hr class="mb-0 navbar-vertical-divider"/>
                                </div>
                            </div>
                            <!-- parent pages--><a class="nav-link" href="<?php echo site_url('dashboard/index') ?>" role="button">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-chart-pie"></span></span><span
                                            class="nav-link-text ps-1">Dashboard</span>
                                </div>
                            </a>
                            <!-- parent pages--><a class="nav-link" href="<?php echo site_url('accounts/index') ?>" role="button">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-users-cog"></span></span><span class="nav-link-text ps-1">Contacts</span>
                                </div>
                            </a>
                            <!-- parent pages--><a class="nav-link dropdown-indicator" href="#products" role="button"
                                                   data-bs-toggle="collapse" aria-expanded="false"
                                                   aria-controls="products">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-boxes"></span></span><span
                                            class="nav-link-text ps-1">Products</span>
                                </div>
                            </a>
                            <ul class="nav collapse" id="products">
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('items/index') ?>">
                                        <div class="d-flex align-items-center"><span
                                                    class="nav-link-text ps-1">All Products</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('opening_items/index') ?>">
                                        <div class="d-flex align-items-center"><span
                                                    class="nav-link-text ps-1">Opening Products</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('transfers/index') ?>">
                                        <div class="d-flex align-items-center"><span
                                                    class="nav-link-text ps-1">Transfer Products</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                            <!-- parent pages--><a class="nav-link" href="<?php echo site_url('warehouses/inventory') ?>" role="button">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-warehouse"></span></span><span class="nav-link-text ps-1">Inventory</span>
                                </div>
                            </a>
                            <!-- parent pages--><a class="nav-link dropdown-indicator" href="#sales" role="button"
                                                   data-bs-toggle="collapse" aria-expanded="false"
                                                   aria-controls="sales">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-shopping-bag"></span></span><span
                                            class="nav-link-text ps-1">Sales</span>
                                </div>
                            </a>
                            <ul class="nav collapse" id="sales">
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('quotations/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Quotations</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('orders/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Orders</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('sales/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Invoices</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/pickup_items') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Pickup</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('delivery_notes/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Delivery Notes</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('return_sales/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Return Invoices</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                            <!-- parent pages--><a class="nav-link dropdown-indicator" href="#purchases" role="button"
                                                   data-bs-toggle="collapse" aria-expanded="false"
                                                   aria-controls="purchases">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-shopping-bag"></span></span><span
                                            class="nav-link-text ps-1">Purchases</span>
                                </div>
                            </a>
                            <ul class="nav collapse" id="purchases">
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('order_purchases/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Order Purchases</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('purchases/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Receiving</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('return_purchases/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Return</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                            <!-- parent pages--><a class="nav-link" href="<?php echo site_url('payments/index') ?>" role="button">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-money-bill-wave"></span></span><span class="nav-link-text ps-1">Payments</span>
                                </div>
                            </a>
                            <!-- parent pages--><a class="nav-link" href="<?php echo site_url('receipts/index') ?>" role="button">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-receipt"></span></span><span class="nav-link-text ps-1">Receipts</span>
                                </div>
                            </a>
                            <!-- parent pages--><a class="nav-link dropdown-indicator" href="#reports" role="button"
                                                   data-bs-toggle="collapse" aria-expanded="false"
                                                   aria-controls="reports">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-chart-bar"></span></span><span
                                            class="nav-link-text ps-1">Reports</span>
                                </div>
                            </a>
                            <ul class="nav collapse" id="reports">
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('journal_accounts/index') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Journals Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('warehouses/reports') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Warehouse Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/orders') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Orders Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/employees') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Employee Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/activity') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Activity Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/customer_receiving_items') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Customer  Receiving Items Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/purchase_orders') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Order Purchase Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/pfand') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Pfand Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/monthly_accounts') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Monthly Accounts Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo site_url('reports/receiving_missing_items') ?>">
                                        <div class="d-flex align-items-center"><span class="nav-link-text ps-1">Receiving Missing Items Report</span>
                                        </div>
                                    </a>
                                    <!-- more inner pages-->
                                </li>
                            </ul>
                            <!-- parent pages--><a class="nav-link" href="<?php echo site_url('suppliers/autopartner_search') ?>" role="button">
                                <div class="d-flex align-items-center"><span class="nav-link-icon"><span
                                                class="fas fa-search"></span></span><span class="nav-link-text ps-1">Amigo</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="settings mb-3 d-none">
                        <div class="card shadow-none">
                            <div class="card-body alert mb-0" role="alert">
                                <div class="btn-close-falcon-container">
                                    <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"
                                            data-bs-dismiss="alert"></button>
                                </div>
                                <div class="text-center"><img
                                            src="assets/img/icons/spot-illustrations/navbar-vertical.png" alt=""
                                            width="80"/>
                                    <p class="fs--2 mt-2">Loving what you see? <br/>Get your copy of <a
                                                href="#!">Falcon</a></p>
                                    <div class="d-grid"><a class="btn btn-sm btn-purchase"
                                                           href="https://themes.getbootstrap.com/product/falcon-admin-dashboard-webapp-template/"
                                                           target="_blank">Purchase</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            no
            <?php
        }
    }
    ?>
</nav>
<div class="content">
    <?php if ($this->session->userdata('vauth_fiscal_year_id')) {
    if ($this->violet_auth->get_user_type() === "Admin" || $this->violet_auth->get_user_type() === "Master Admin") {
        ?>
        <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">

            <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                    aria-controls="navbarVerticalCollapse" aria-expanded="false"
                    aria-label="Toggle Navigation"><span
                        class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
            <a class="navbar-brand me-1 me-sm-3" href="index.html">
                <div class="d-flex align-items-center"><img class="me-2"
                                                            src="assets/img/logos/logo-white-2.png"
                                                            alt=""
                                                            width="120"/>
                </div>
            </a>
            <!--<ul class="navbar-nav align-items-center d-none d-lg-block">
                <li class="nav-item">
                    <div class="search-box" data-list='{"valueNames":["title"]}'>
                        <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                            <input class="form-control form-control-sm search-input fuzzy-search" type="search"
                                   placeholder="Search..."
                                   aria-label="Search"/>
                            <span class="fas fa-search search-box-icon"></span>

                        </form>
                        <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none"
                             data-bs-dismiss="search">
                            <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"></button>
                        </div>
                        <div class="dropdown-menu border font-base start-0 mt-2 py-0 overflow-hidden w-100">
                            <div class="scrollbar list py-3" style="max-height: 24rem;">
                                <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">Recently
                                    Browsed</h6><a class="dropdown-item fs--1 px-x1 py-1 hover-primary"
                                                   href="app/events/event-detail.html">
                                    <div class="d-flex align-items-center">
                                        <span class="fas fa-circle me-2 text-300 fs--2"></span>

                                        <div class="fw-normal title">Pages <span
                                                    class="fas fa-chevron-right mx-1 text-500 fs--2"
                                                    data-fa-transform="shrink-2"></span> Events
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item fs--1 px-x1 py-1 hover-primary"
                                   href="app/e-commerce/customers.html">
                                    <div class="d-flex align-items-center">
                                        <span class="fas fa-circle me-2 text-300 fs--2"></span>

                                        <div class="fw-normal title">E-commerce <span
                                                    class="fas fa-chevron-right mx-1 text-500 fs--2"
                                                    data-fa-transform="shrink-2"></span> Customers
                                        </div>
                                    </div>
                                </a>

                                <hr class="text-200 dark__text-900"/>
                                <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">Suggested
                                    Filter</h6><a class="dropdown-item px-x1 py-1 fs-0"
                                                  href="app/e-commerce/customers.html">
                                    <div class="d-flex align-items-center"><span
                                                class="badge fw-medium text-decoration-none me-2 badge-subtle-warning">customers:</span>
                                        <div class="flex-1 fs--1 title">All customers list</div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-x1 py-1 fs-0" href="app/events/event-detail.html">
                                    <div class="d-flex align-items-center"><span
                                                class="badge fw-medium text-decoration-none me-2 badge-subtle-success">events:</span>
                                        <div class="flex-1 fs--1 title">Latest events in current month</div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-x1 py-1 fs-0"
                                   href="app/e-commerce/product/product-grid.html">
                                    <div class="d-flex align-items-center"><span
                                                class="badge fw-medium text-decoration-none me-2 badge-subtle-info">products:</span>
                                        <div class="flex-1 fs--1 title">Most popular products</div>
                                    </div>
                                </a>

                                <hr class="text-200 dark__text-900"/>
                                <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">
                                    Files</h6><a
                                        class="dropdown-item px-x1 py-2" href="#!">
                                    <div class="d-flex align-items-center">
                                        <div class="file-thumbnail me-2"><img
                                                    class="border h-100 w-100 object-fit-cover rounded-3"
                                                    src="assets/img/products/3-thumb.png" alt=""/></div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 title">iPhone</h6>
                                            <p class="fs--2 mb-0 d-flex"><span
                                                        class="fw-semi-bold">Antony</span><span
                                                        class="fw-medium text-600 ms-2">27 Sep at 10:30 AM</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-x1 py-2" href="#!">
                                    <div class="d-flex align-items-center">
                                        <div class="file-thumbnail me-2"><img class="img-fluid"
                                                                              src="assets/img/icons/zip.png"
                                                                              alt=""/></div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 title">Falcon v1.8.2</h6>
                                            <p class="fs--2 mb-0 d-flex"><span class="fw-semi-bold">John</span><span
                                                        class="fw-medium text-600 ms-2">30 Sep at 12:30 PM</span>
                                            </p>
                                        </div>
                                    </div>
                                </a>

                                <hr class="text-200 dark__text-900"/>
                                <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">
                                    Members</h6><a
                                        class="dropdown-item px-x1 py-2" href="pages/user/profile.html">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-l status-online me-2">
                                            <img class="rounded-circle" src="assets/img/team/1.jpg" alt=""/>

                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 title">Anna Karinina</h6>
                                            <p class="fs--2 mb-0 d-flex">Technext Limited</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-x1 py-2" href="pages/user/profile.html">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-l me-2">
                                            <img class="rounded-circle" src="assets/img/team/2.jpg" alt=""/>

                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 title">Antony Hopkins</h6>
                                            <p class="fs--2 mb-0 d-flex">Brain Trust</p>
                                        </div>
                                    </div>
                                </a>
                                <a class="dropdown-item px-x1 py-2" href="pages/user/profile.html">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-l me-2">
                                            <img class="rounded-circle" src="assets/img/team/3.jpg" alt=""/>

                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 title">Emma Watson</h6>
                                            <p class="fs--2 mb-0 d-flex">Google</p>
                                        </div>
                                    </div>
                                </a>

                            </div>
                            <div class="text-center mt-n3">
                                <p class="fallback fw-bold fs-1 d-none">No Result Found.</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>-->
            <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
                <li class="nav-item px-2">
                    <div class="theme-control-toggle fa-icon-wait">
                        <input class="form-check-input ms-0 theme-control-toggle-input" id="themeControlToggle"
                               type="checkbox" data-theme-control="theme" value="dark"/>
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-light"
                               for="themeControlToggle"
                               data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to light theme"><span
                                    class="fas fa-sun fs-0"></span></label>
                        <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark"
                               for="themeControlToggle"
                               data-bs-toggle="tooltip" data-bs-placement="left" title="Switch to dark theme"><span
                                    class="fas fa-moon fs-0"></span></label>
                    </div>
                </li>
      <!--          <li class="nav-item d-sm-block">
                    <a class="nav-link px-0 notification-indicator notification-indicator-warning notification-indicator-fill fa-icon-wait"
                       href="app/e-commerce/shopping-cart.html"><span class="fas fa-shopping-cart"
                                                                      data-fa-transform="shrink-7"
                                                                      style="font-size: 33px;"></span><span
                                class="notification-indicator-number">1</span></a>

                </li>-->
                <li class="nav-item d-none dropdown">
                    <a class="nav-link notification-indicator notification-indicator-primary px-0 fa-icon-wait"
                       id="navbarDropdownNotification" role="button" data-bs-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false" data-hide-on-body-scroll="data-hide-on-body-scroll"><span
                                class="fas fa-bell"
                                data-fa-transform="shrink-6"
                                style="font-size: 33px;"></span></a>
           <!--         <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end dropdown-menu-card dropdown-menu-notification dropdown-caret-bg"
                         aria-labelledby="navbarDropdownNotification">
                        <div class="card card-notification shadow-none">
                            <div class="card-header">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-auto">
                                        <h6 class="card-header-title mb-0">Notifications</h6>
                                    </div>
                                    <div class="col-auto ps-0 ps-sm-3"><a class="card-link fw-normal" href="#">Mark
                                            all as
                                            read</a></div>
                                </div>
                            </div>
                            <div class="scrollbar-overlay" style="max-height:19rem">
                                <div class="list-group list-group-flush fw-normal fs--1">
                                    <div class="list-group-title border-bottom">NEW</div>
                                    <div class="list-group-item">
                                        <a class="notification notification-flush notification-unread" href="#!">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-2xl me-3">
                                                    <img class="rounded-circle" src="assets/img/team/1-thumb.png"
                                                         alt=""/>

                                                </div>
                                            </div>
                                            <div class="notification-body">
                                                <p class="mb-1"><strong>Emma Watson</strong> replied to your comment
                                                    :
                                                    "Hello world 😍"</p>
                                                <span class="notification-time"><span class="me-2" role="img"
                                                                                      aria-label="Emoji">💬</span>Just now</span>

                                            </div>
                                        </a>

                                    </div>
                                    <div class="list-group-item">
                                        <a class="notification notification-flush notification-unread" href="#!">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-2xl me-3">
                                                    <div class="avatar-name rounded-circle"><span>AB</span></div>
                                                </div>
                                            </div>
                                            <div class="notification-body">
                                                <p class="mb-1"><strong>Albert Brooks</strong> reacted to <strong>Mia
                                                        Khalifa's</strong> status</p>
                                                <span class="notification-time"><span
                                                            class="me-2 fab fa-gratipay text-danger"></span>9hr</span>

                                            </div>
                                        </a>

                                    </div>
                                    <div class="list-group-title border-bottom">EARLIER</div>
                                    <div class="list-group-item">
                                        <a class="notification notification-flush" href="#!">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-2xl me-3">
                                                    <img class="rounded-circle"
                                                         src="assets/img/icons/weather-sm.jpg"
                                                         alt=""/>

                                                </div>
                                            </div>
                                            <div class="notification-body">
                                                <p class="mb-1">The forecast today shows a low of 20&#8451; in
                                                    California.
                                                    See today's weather.</p>
                                                <span class="notification-time"><span class="me-2" role="img"
                                                                                      aria-label="Emoji">🌤️</span>1d</span>

                                            </div>
                                        </a>

                                    </div>
                                    <div class="list-group-item">
                                        <a class="border-bottom-0 notification-unread  notification notification-flush"
                                           href="#!">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-xl me-3">
                                                    <img class="rounded-circle" src="assets/img/logos/oxford.png"
                                                         alt=""/>

                                                </div>
                                            </div>
                                            <div class="notification-body">
                                                <p class="mb-1"><strong>University of Oxford</strong> created an
                                                    event :
                                                    "Causal Inference Hilary 2019"</p>
                                                <span class="notification-time"><span class="me-2" role="img"
                                                                                      aria-label="Emoji">✌️</span>1w</span>

                                            </div>
                                        </a>

                                    </div>
                                    <div class="list-group-item">
                                        <a class="border-bottom-0 notification notification-flush" href="#!">
                                            <div class="notification-avatar">
                                                <div class="avatar avatar-xl me-3">
                                                    <img class="rounded-circle" src="assets/img/team/10.jpg"
                                                         alt=""/>

                                                </div>
                                            </div>
                                            <div class="notification-body">
                                                <p class="mb-1"><strong>James Cameron</strong> invited to join the
                                                    group:
                                                    United Nations International Children's Fund</p>
                                                <span class="notification-time"><span class="me-2" role="img"
                                                                                      aria-label="Emoji">🙋‍</span>2d</span>

                                            </div>
                                        </a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-center border-top"><a class="card-link d-block"
                                                                               href="app/social/notifications.html">View
                                    all</a></div>
                        </div>
                    </div>-->

                </li>
                <li class="nav-item dropdown d-none px-1">
                    <a class="nav-link fa-icon-wait nine-dots p-1" id="navbarDropdownMenu" role="button"
                       data-hide-on-body-scroll="data-hide-on-body-scroll" data-bs-toggle="dropdown"
                       aria-haspopup="true"
                       aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="43" viewBox="0 0 16 16"
                             fill="none">
                            <circle cx="2" cy="2" r="2" fill="#6C6E71"></circle>
                            <circle cx="2" cy="8" r="2" fill="#6C6E71"></circle>
                            <circle cx="2" cy="14" r="2" fill="#6C6E71"></circle>
                            <circle cx="8" cy="8" r="2" fill="#6C6E71"></circle>
                            <circle cx="8" cy="14" r="2" fill="#6C6E71"></circle>
                            <circle cx="14" cy="8" r="2" fill="#6C6E71"></circle>
                            <circle cx="14" cy="14" r="2" fill="#6C6E71"></circle>
                            <circle cx="8" cy="2" r="2" fill="#6C6E71"></circle>
                            <circle cx="14" cy="2" r="2" fill="#6C6E71"></circle>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end dropdown-menu-card dropdown-caret-bg"
                         aria-labelledby="navbarDropdownMenu">
                        <div class="card shadow-none">
                            <div class="scrollbar-overlay nine-dots-dropdown">
                                <div class="card-body px-3">
<!--                                    <div class="row text-center gx-0 gy-0">
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="pages/user/profile.html" target="_blank">
                                                <div class="avatar avatar-2xl"><img class="rounded-circle"
                                                                                    src="assets/img/team/3.jpg"
                                                                                    alt=""/>
                                                </div>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2">Account</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="https://themewagon.com/" target="_blank"><img
                                                        class="rounded"
                                                        src="assets/img/nav-icons/themewagon.png"
                                                        alt="" width="40"
                                                        height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Themewagon</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="https://mailbluster.com/" target="_blank"><img
                                                        class="rounded"
                                                        src="assets/img/nav-icons/mailbluster.png"
                                                        alt="" width="40"
                                                        height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Mailbluster</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/google.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Google</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/spotify.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Spotify</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/steam.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Steam</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/github-light.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Github</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/discord.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Discord</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/xbox.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">xbox</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/trello.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Kanban</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/hp.png"
                                                                                   alt=""
                                                                                   width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">Hp</p>
                                            </a></div>
                                        <div class="col-12">
                                            <hr class="my-3 mx-n3 bg-200"/>
                                        </div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/linkedin.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Linkedin</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/twitter.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Twitter</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/facebook.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Facebook</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/instagram.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Instagram</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/pinterest.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Pinterest</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/slack.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Slack</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="#!" target="_blank"><img class="rounded"
                                                                                   src="assets/img/nav-icons/deviantart.png"
                                                                                   alt="" width="40" height="40"/>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2 pt-1">
                                                    Deviantart</p>
                                            </a></div>
                                        <div class="col-4"><a
                                                    class="d-block hover-bg-200 px-2 py-3 rounded-3 text-center text-decoration-none"
                                                    href="app/events/event-detail.html" target="_blank">
                                                <div class="avatar avatar-2xl">
                                                    <div class="avatar-name rounded-circle bg-primary-subtle text-primary">
                                                        <span class="fs-2">E</span></div>
                                                </div>
                                                <p class="mb-0 fw-medium text-800 text-truncate fs--2">Events</p>
                                            </a></div>
                                        <div class="col-12"><a class="btn btn-outline-primary btn-sm mt-4"
                                                               href="#!">Show
                                                more</a></div>
                                    </div>
-->                                </div>
                            </div>
                        </div>
                    </div>

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link pe-0 ps-2" id="navbarDropdownSwitch" role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                       <span
                               class="fas fa-building"
                               data-fa-transform="shrink-6"
                               style="font-size: 33px;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0"
                         aria-labelledby="navbarDropdownSwitch">
                        <div class="bg-white dark__bg-1000 rounded-2 py-2">
                            <a class="dropdown-item"
                               href="https://wt-autoteile.de/">WT-Autoteile</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button"
                       data-bs-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                       <span
                               class="fas fa-cog"
                               data-fa-transform="shrink-6"
                               style="font-size: 33px;"></span>
                    </a>
                    <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0"
                         aria-labelledby="navbarDropdownUser">
                        <div class="bg-white dark__bg-1000 rounded-2 py-2">
                            <a class="dropdown-item" href="<?php echo site_url('fiscal_years/index') ?>">Fiscal
                                Years</a>
                            <a class="dropdown-item"
                               href="<?php echo site_url('currencies/index') ?>">Currencies</a>
                            <a class="dropdown-item"
                               href="<?php echo site_url('warehouses/index') ?>">Warehouses</a>
                            <a class="dropdown-item"
                               href="<?php echo site_url('configurations/index') ?>">Configuration</a>
                            <a class="dropdown-item" href="<?php echo site_url('users/index') ?>">Users</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?php echo site_url('employees/pickup_notes') ?>">Employee
                                Page</a>
                            <a class="dropdown-item" href="<?php echo site_url('sales/driver_page') ?>">Driver
                                Page</a>
                            <a class="dropdown-item" href="<?php echo site_url('users/choose_fiscal_year') ?>">Change
                                Fiscal Year</a>
                            <?php if ($this->violet_auth->get_user_type() === "Employee") { ?>
                                <a class="dropdown-item" href="<?php echo site_url('employees/pickup_notes') ?>">Pickup
                                    Notes</a>
                                <a class="dropdown-item" href="<?php echo site_url('employees/scan_box') ?>">Scan
                                    Box</a>
                                <a class="dropdown-item" href="<?php echo site_url('reports/receiving_items') ?>">Receiving
                                    Items</a>
                            <?php } ?>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="javascript:void(0);" id="changeUserPassBtn">Change
                                Password</a>
                            <a class="dropdown-item" href="<?php echo site_url('users/logout') ?>">Logout</a>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
        <?php
    } else {
    ?>
    <nav class="navbar navbar-light navbar-glass navbar-top navbar-expand">

        <button class="btn navbar-toggler-humburger-icon navbar-toggler me-1 me-sm-3" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse"
                aria-controls="navbarVerticalCollapse" aria-expanded="false"
                aria-label="Toggle Navigation"><span
                    class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
        <a class="navbar-brand me-1 me-sm-3" href="index.html">
            <div class="d-flex align-items-center"><img class="me-2"
                                                        src="assets/img/icons/spot-illustrations/falcon.png"
                                                        alt=""
                                                        width="40"/><span class="font-sans-serif">falcon</span>
            </div>
        </a>
        <ul class="navbar-nav align-items-center d-none d-lg-block">
            <li class="nav-item">
                <div class="search-box" data-list='{"valueNames":["title"]}'>
                    <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                        <input class="form-control form-control-sm search-input fuzzy-search" type="search"
                               placeholder="Search..."
                               aria-label="Search"/>
                        <span class="fas fa-search search-box-icon"></span>

                    </form>
                    <div class="btn-close-falcon-container position-absolute end-0 top-50 translate-middle shadow-none"
                         data-bs-dismiss="search">
                        <button class="btn btn-link btn-close-falcon p-0" aria-label="Close"></button>
                    </div>
                    <div class="dropdown-menu border font-base start-0 mt-2 py-0 overflow-hidden w-100">
                        <div class="scrollbar list py-3" style="max-height: 24rem;">
                            <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">Recently
                                Browsed</h6><a class="dropdown-item fs--1 px-x1 py-1 hover-primary"
                                               href="app/events/event-detail.html">
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-circle me-2 text-300 fs--2"></span>

                                    <div class="fw-normal title">Pages <span
                                                class="fas fa-chevron-right mx-1 text-500 fs--2"
                                                data-fa-transform="shrink-2"></span> Events
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item fs--1 px-x1 py-1 hover-primary"
                               href="app/e-commerce/customers.html">
                                <div class="d-flex align-items-center">
                                    <span class="fas fa-circle me-2 text-300 fs--2"></span>

                                    <div class="fw-normal title">E-commerce <span
                                                class="fas fa-chevron-right mx-1 text-500 fs--2"
                                                data-fa-transform="shrink-2"></span> Customers
                                    </div>
                                </div>
                            </a>

                            <hr class="text-200 dark__text-900"/>
                            <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">Suggested
                                Filter</h6><a class="dropdown-item px-x1 py-1 fs-0"
                                              href="app/e-commerce/customers.html">
                                <div class="d-flex align-items-center"><span
                                            class="badge fw-medium text-decoration-none me-2 badge-subtle-warning">customers:</span>
                                    <div class="flex-1 fs--1 title">All customers list</div>
                                </div>
                            </a>
                            <a class="dropdown-item px-x1 py-1 fs-0" href="app/events/event-detail.html">
                                <div class="d-flex align-items-center"><span
                                            class="badge fw-medium text-decoration-none me-2 badge-subtle-success">events:</span>
                                    <div class="flex-1 fs--1 title">Latest events in current month</div>
                                </div>
                            </a>
                            <a class="dropdown-item px-x1 py-1 fs-0"
                               href="app/e-commerce/product/product-grid.html">
                                <div class="d-flex align-items-center"><span
                                            class="badge fw-medium text-decoration-none me-2 badge-subtle-info">products:</span>
                                    <div class="flex-1 fs--1 title">Most popular products</div>
                                </div>
                            </a>

                            <hr class="text-200 dark__text-900"/>
                            <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">
                                Files</h6><a
                                    class="dropdown-item px-x1 py-2" href="#!">
                                <div class="d-flex align-items-center">
                                    <div class="file-thumbnail me-2"><img
                                                class="border h-100 w-100 object-fit-cover rounded-3"
                                                src="assets/img/products/3-thumb.png" alt=""/></div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 title">iPhone</h6>
                                        <p class="fs--2 mb-0 d-flex"><span
                                                    class="fw-semi-bold">Antony</span><span
                                                    class="fw-medium text-600 ms-2">27 Sep at 10:30 AM</span>
                                        </p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item px-x1 py-2" href="#!">
                                <div class="d-flex align-items-center">
                                    <div class="file-thumbnail me-2"><img class="img-fluid"
                                                                          src="assets/img/icons/zip.png"
                                                                          alt=""/></div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 title">Falcon v1.8.2</h6>
                                        <p class="fs--2 mb-0 d-flex"><span class="fw-semi-bold">John</span><span
                                                    class="fw-medium text-600 ms-2">30 Sep at 12:30 PM</span>
                                        </p>
                                    </div>
                                </div>
                            </a>

                            <hr class="text-200 dark__text-900"/>
                            <h6 class="dropdown-header fw-medium text-uppercase px-x1 fs--2 pt-0 pb-2">
                                Members</h6><a
                                    class="dropdown-item px-x1 py-2" href="pages/user/profile.html">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-l status-online me-2">
                                        <img class="rounded-circle" src="assets/img/team/1.jpg" alt=""/>

                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 title">Anna Karinina</h6>
                                        <p class="fs--2 mb-0 d-flex">Technext Limited</p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item px-x1 py-2" href="pages/user/profile.html">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-l me-2">
                                        <img class="rounded-circle" src="assets/img/team/2.jpg" alt=""/>

                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 title">Antony Hopkins</h6>
                                        <p class="fs--2 mb-0 d-flex">Brain Trust</p>
                                    </div>
                                </div>
                            </a>
                            <a class="dropdown-item px-x1 py-2" href="pages/user/profile.html">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-l me-2">
                                        <img class="rounded-circle" src="assets/img/team/3.jpg" alt=""/>

                                    </div>
                                    <div class="flex-1">
                                        <h6 class="mb-0 title">Emma Watson</h6>
                                        <p class="fs--2 mb-0 d-flex">Google</p>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <div class="text-center mt-n3">
                            <p class="fallback fw-bold fs-1 d-none">No Result Found.</p>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-icons ms-auto flex-row align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link pe-0 ps-2" id="navbarDropdownUser" role="button"
                   data-bs-toggle="dropdown" aria-haspopup="true"
                   aria-expanded="false">
                    <div class="avatar avatar-xl">
                        <img class="rounded-circle" src="assets/img/team/3-thumb.png" alt=""/>

                    </div>
                </a>
                <div class="dropdown-menu dropdown-caret dropdown-caret dropdown-menu-end py-0"
                     aria-labelledby="navbarDropdownUser">
                    <div class="bg-white dark__bg-1000 rounded-2 py-2">
                        <?php if ($this->violet_auth->get_user_type() === "Employee") { ?>
                            <a class="dropdown-item" href="<?php echo site_url('employees/pickup_notes') ?>">Pickup
                                Notes</a>
                            <a class="dropdown-item" href="<?php echo site_url('employees/scan_box') ?>">Scan Box</a>
                            <a class="dropdown-item" href="<?php echo site_url('reports/receiving_items') ?>">Receiving
                                Items</a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0);" id="changeUserPassBtn">Change
                            Password</a>
                        <a class="dropdown-item" href="<?php echo site_url('users/logout') ?>">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
        <?php
        }
        }
        ?>
        <!-- change password Modal -->
        <div class="modal" id="changeUserPassModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Change Password For User: </b><span
                                    class="text-danger"><?php echo $this->violet_auth->get_user_name() ?></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php echo form_open('users/change_user_password', 'id="changeUserPassForm" name="changeUserPassForm" class="form-horizontal" onsubmit="return changeUserPassFormValidation();" role="form" autocomplete="off" novalidate') ?>
                    <div class="modal-body">
                        <div class="row form-group">
                            <input type="text" id="modalUserPass" hidden>
                            <div class="col-sm-10 col-sm-offset-1">
                                <label class="col-md-4 control-label" style="text-align: left;" for="modal_old_pass">Old
                                    Password</label>
                                <div class="col-md-8">
                                    <?php
                                    echo form_input('modal_old_pass', '', 'id="modal_old_pass" class="form-control form-control-sm"')
                                    ?>
                                    <div id="error_modal_old_pass" style="text-align:center"
                                         onclick="document.getElementById('error_modal_old_pass').style.display = 'none'"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-sm-10 col-sm-offset-1">
                                <label class="col-md-4 control-label" style="text-align: left;" for="modal_new_pass">New
                                    Password</label>
                                <div class="col-md-8">
                                    <?php
                                    echo form_input('modal_new_pass', '', 'id="modal_new_pass" class="form-control form-control-sm"')
                                    ?>
                                    <div id="error_modal_new_pass" style="text-align:center"
                                         onclick="document.getElementById('error_modal_new_pass').style.display = 'none'"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <?php echo form_close() ?>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>