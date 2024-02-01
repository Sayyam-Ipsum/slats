<nav class="navbar navbar-default" id="navbar" style="background-color:#404040;">
	<div class="container-fluid" id="navbardiv">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php
			if ($this->violet_auth->get_user_type() === "Admin" || $this->violet_auth->get_user_type() === "Master Admin") {
			?>
				<a href="<?php echo site_url('') ?>"> <img id="logo" src="<?php echo site_url('assets/img/logos/logo-white-2.png') ?>"></a>
				<?php echo $this->violet_auth->is_logged_in() ? anchor('', $this->violet_auth->get_fiscal_year(), ' class="navbar-brand" style="margin-left:10px;"') : '' ?>
			<?php
			} else {
			?>
				<img id="logo" src="<?php echo site_url('assets/img/logos/logo-white-2.png') ?>">
			<?php
			}
			?>
		</div>
		<?php
		if ($this->session->userdata('vauth_fiscal_year_id')) {
			if ($this->violet_auth->get_user_type() === "Admin" || $this->violet_auth->get_user_type() === "Master Admin") {
		?>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<li><a href="<?php echo site_url('accounts/index') ?>">Contacts</a></li>
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Products<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo site_url('items/index') ?>">All Products</a></li>
								<li><a href="<?php echo site_url('opening_items/index') ?>">Opening Products</a></li>
								<li><a href="<?php echo site_url('transfers/index') ?>">Transfer Products</a></li>
							</ul>
						</li>
						<li><a href="<?php echo site_url('warehouses/inventory') ?>">Inventory</a></li>
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sales<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo site_url('quotations/index') ?>">Quotations</a></li>
								<li><a href="<?php echo site_url('orders/index') ?>">Orders</a></li>
								<li><a href="<?php echo site_url('sales/index') ?>">Invoices</a></li>
								<li><a href="<?php echo site_url('reports/pickup_items') ?>">Pickup</a></li>
								<li><a href="<?php echo site_url('delivery_notes/index') ?>">Delivery Notes</a></li>
								<li><a href="<?php echo site_url('return_sales/index') ?>">Return Invoices</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Purchases<span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo site_url('order_purchases/index') ?>">Order Purchases</a></li>
								<li><a href="<?php echo site_url('purchases/index') ?>">Receiving</a></li>
								<li><a href="<?php echo site_url('return_purchases/index') ?>">Return</a></li>
							</ul>
						</li>
						<li><a href="<?php echo site_url('payments/index') ?>">Payments</a></li>
						<li><a href="<?php echo site_url('receipts/index') ?>">Receipts</a></li>
						<li class="dropdown">
							<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo site_url('journal_accounts/index') ?>">Journals Report</a></li>
								<li><a href="<?php echo site_url('warehouses/reports') ?>">Warehouse Report</a></li>
								<li><a href="<?php echo site_url('reports/orders') ?>">Orders Report</a></li>
								<li><a href="<?php echo site_url('reports/employees') ?>">Employee Report</a></li>
								<li><a href="<?php echo site_url('reports/activity') ?>">Activity Report</a></li>
								<!-- <li><a href="<?php // echo site_url('reports/receiving_items') ?>">Pickup Report</a></li> -->
								<li><a href="<?php echo site_url('reports/customer_receiving_items') ?>">Customer Receiving Items Report</a></li>
								<li><a href="<?php echo site_url('reports/purchase_orders') ?>">Order Purchase Report</a></li>
								<!-- <li><a href="<?php // echo site_url('reports/pickup') ?>">Pickup Report</a></li> -->
								<li><a href="<?php echo site_url('reports/pfand') ?>">Pfand Report</a></li>
								<li><a href="<?php echo site_url('reports/monthly_accounts') ?>">Monthly Accounts Report</a></li>
								<li><a href="<?php echo site_url('reports/receiving_missing_items') ?>">Receiving Missing Items Report</a></li>
							</ul>
						</li>
						<li><a href="<?php echo site_url('suppliers/autopartner_search') ?>">Amigo</a></li>
					</ul>
					<form class="navbar-form navbar-left hide">
						<div class="form-group">
							<input type="text" class="form-control form-control-sm" placeholder="Search">
						</div>
						<button type="submit" class="btn btn-default">Submit</button>
					</form>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								Settings <span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo site_url('fiscal_years/index') ?>">Fiscal Years</a></li>
								<li><a href="<?php echo site_url('currencies/index') ?>">Currencies</a></li>
								<li><a href="<?php echo site_url('warehouses/index') ?>">Warehouses</a></li>
								<li><a href="<?php echo site_url('configurations/index') ?>">Configuration</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="<?php echo site_url('users/index') ?>">Users</a></li>
								<li><a href="<?php echo site_url('employees/pickup_notes') ?>">Employee Page</a></li>
								<li><a href="<?php echo site_url('sales/driver_page') ?>">Driver Page</a></li>
								<li><a href="<?php echo site_url('users/choose_fiscal_year') ?>">Change Fiscal Year</a></li>
								<li><a href="javascript:void(0);" id="changeUserPassBtn">Change Password</a></li>
								<li><a href="<?php echo site_url('users/logout') ?>">log out</a></li>
							</ul>
						</li>
					</ul>
				</div><!-- /.navbar-collapse -->
			<?php
			} else {
			?>
				<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					<ul class="nav navbar-nav">
						<?php if ($this->violet_auth->get_user_type() === "Employee") { ?>
							<li><a href="<?php echo site_url('employees/pickup_notes') ?>">Pickup Notes</a></li>
							<li><a href="<?php echo site_url('employees/scan_box') ?>">Scan Box</a></li>
							<li><a href="<?php echo site_url('reports/receiving_items') ?>">Receiving Items</a></li>
						<?php } ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="<?php echo site_url('users/logout') ?>">log out</a></li>
					</ul>
				</div>
		<?php
			}
		}
		?>
	</div><!-- /.container-fluid -->
</nav>
<!-- change password Modal -->
<div class="modal" id="changeUserPassModal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><b>Change Password For User: </b><span class="text-danger"><?php echo $this->violet_auth->get_user_name() ?></span></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<?php echo form_open('users/change_user_password', 'id="changeUserPassForm" name="changeUserPassForm" class="form-horizontal" onsubmit="return changeUserPassFormValidation();" role="form" autocomplete="off" novalidate') ?>
			<div class="modal-body">
				<div class="row form-group">
					<input type="text" id="modalUserPass" hidden>
					<div class="col-sm-10 col-sm-offset-1">
						<label class="col-md-4 control-label" style="text-align: left;" for="modal_old_pass">Old Password</label>
						<div class="col-md-8">
							<?php
							echo form_input('modal_old_pass', '', 'id="modal_old_pass" class="form-control form-control-sm"')
							?>
							<div id="error_modal_old_pass" style="text-align:center" onclick="document.getElementById('error_modal_old_pass').style.display = 'none'"></div>
						</div>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-sm-10 col-sm-offset-1">
						<label class="col-md-4 control-label" style="text-align: left;" for="modal_new_pass">New Password</label>
						<div class="col-md-8">
							<?php
							echo form_input('modal_new_pass', '', 'id="modal_new_pass" class="form-control form-control-sm"')
							?>
							<div id="error_modal_new_pass" style="text-align:center" onclick="document.getElementById('error_modal_new_pass').style.display = 'none'"></div>
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