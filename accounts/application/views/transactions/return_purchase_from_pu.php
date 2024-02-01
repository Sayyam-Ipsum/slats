<p class="text-right">
	<button accesskey="b" class="btn btn-primary" id="bgback">Back</button>
	<?php echo form_open('', 'id="transactionForm" name="transactionForm" class="form-horizontal" role="form" onsubmit="return validation();" autocomplete="off" novalidate') ?>
	<input name="id" id="id" type="hidden" value="" />
<div class="row">
	<div class="col-sm-12">
		<h4><?php echo $transTypeText ?></h4>
		<hr />
	</div>
	<div class="clearfix"></div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-4 control-label" for="auto_no">Return Purchase #</label>
			<div class="col-sm-8">
				<?php
				echo form_input('trans[auto_no]', $trans['auto_no'], 'id="auto_no" class="form-control form-control-sm" readonly="true" required '),
				$this->Transaction->get_error('auto_no')
				?>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-6 control-label" for="currency_id"><?php echo $this->lang->line('currency') ?></label>
			<div class="col-sm-6">
				<?php
				echo form_dropdown('currency_id_list', $currenciesList, $trans['currency_id'], 'id="currency_id_list" class="form-control form-control-sm"'),
				$this->Transaction->get_error('currency_id');
				?>
				<input name="trans[currency_id]" id="currency_id" type="hidden" value="<?php echo $trans['currency_id'] ?>" />
				<div id="error_currency_id" style="text-align:center" onclick="document.getElementById('error_currency_id').style.display = 'none'"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-6 control-label" for="currency_rate">Currency Rate</label>
			<div class="col-sm-6">
				<?php
				echo form_input('trans[currency_rate]', $trans['currency_rate'], 'id="currency_rate" class="form-control form-control-sm" required'),
				$this->Transaction->get_error('currency_rate')
				?>
				<div id="error_currency_rate" style="text-align:center" onclick="document.getElementById('error_currency_rate').style.display = 'none'"></div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-4 control-label" for="acctLkupTo">
				Supplier Account
			</label>
			<div class="col-sm-8">
				<?php echo form_input('', $account, 'id="acctLkupTo" maxlength="10" class="form-control form-control-sm"') ?>
				<div class="input-group-addon">
					<button type="button" id="add_acc_modal"><i class="glyphicon glyphicon-plus text-danger"></i></button>
					<button type="button" id="edit_acc_modal"><i class="glyphicon glyphicon-pencil text-danger"></i></button>
				</div>
				<input name="trans[account_id]" value="<?php echo $trans['account_id'] ?>" id="account_id" type="hidden" />
				<div id="error_acctLkupTo" style="text-align:center" onclick="document.getElementById('error_acctLkupTo').style.display = 'none'"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label" for="acctLkupFrom">
				Purchases Account
			</label>
			<div class="col-sm-8">
				<?php echo form_input('', $account2, 'id="acctLkupFrom" maxlength="10" class="form-control form-control-sm" readonly="true"') ?>
				<input name="trans[account2_id]" value="<?php echo $trans['account2_id'] ?>" id="account2_id" type="hidden" />
				<div id="error_acctLkupFrom" style="text-align:center" onclick="document.getElementById('error_acctLkupFrom').style.display = 'none'"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-6 control-label" for="trans_date">Date</label>
			<div class="col-sm-6">
				<?php echo form_input('trans[trans_date]', $trans_date, 'id="trans_date" class="form-control form-control-sm" required'), $this->Transaction->get_error('trans_date')
				?>
				<div id="error_trans_date" style="text-align:center" onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-6 control-label" for="value_date">Due Date</label>
			<div class="col-sm-6">
				<?php echo form_input('trans[value_date]', $value_date, 'id="value_date" class="form-control form-control-sm"'), $this->Transaction->get_error('value_date')
				?>
				<div id="error_value_date" style="text-align:center" onclick="document.getElementById('error_trans_date').style.display = 'none'"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-8">
		<div class="form-group">
			<label class="col-sm-3 control-label" for="itemLookup">Select Item</label>
			<div class="col-sm-9">
				<?php echo form_input('', '', 'id="itemLookup" class="form-control form-control-sm" required') ?>
				<div class="input-group-addon">
					<button type="button" id="add_item_modal"><i class="glyphicon glyphicon-plus text-danger"></i></button>
					<!-- <button type="button" id="edit_item_modal"><i class="glyphicon glyphicon-pencil text-danger"></i></button> -->
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="col-sm-4">
		<div class="form-group">
			<label class="col-sm-4 control-label" for="description">Description</label>
			<div class="col-sm-8">
				<?php echo form_textarea('trans[description]', $trans['description'], 'id="description" class="form-control form-control-sm" style="height:100px;"') ?>
			</div>
		</div>
	</div>
	<div class="col-sm-12 table-responsive">
		<table class="table table-bordered table-condensed table-striped table-hover">
			<thead>
				<tr class="danger">
					<th class="col-sm-1">Delete</th>
					<th class="col-sm-2">EAN</th>
					<th class="col-sm-2">Artical Nb</th>
					<th class="col-sm-2">Warehouse</th>
					<th class="col-sm-2">Shelf</th>
					<th class="col-sm-1">Quantity</th>
					<th class="col-sm-2">Price</th>
					<th class="col-sm-1">Discount %</th>
					<th class="col-sm-2 text-right">Total</th>
				</tr>
			</thead>
			<tbody id="transactionLines">
				<?php
				$this->load->view('return_purchases/edit_return_purhcase_form_items_line', ['transItems' => isset($trans_items) ? $trans_items : []]);
				?>
			</tbody>
		</table>
		<div id="error_transactionLines" style="text-align:center" onclick="document.getElementById('error_transactionLines').style.display = 'none'"></div>
	</div>
	<div class="col-sm-12 text-right">
		<div class="col-sm-3">
			<div class="form-group">
				<label class="col-sm-6 control-label" for="sub_total">Subtotal</label>
				<div class="col-sm-6">
					<?php
					echo form_input('trans[sub_total]', '', 'id="sub_total" class="form-control form-control-sm" readonly="true"')
					?>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label class="col-sm-6 control-label" for="discount">Discount</label>
				<div class="col-sm-6">
					<?php
					echo form_input('trans[discount]', $trans['discount'], 'id="disc" class="form-control form-control-sm" required'),
					$this->Transaction->get_error('discount')
					?>
					<div id="error_disc" style="text-align:center" onclick="document.getElementById('error_disc').style.display = 'none'"></div>
				</div>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<label class="col-sm-6 control-label" for="final_total">Total</label>
				<div class="col-sm-6">
					<?php
					echo form_input('trans[final_total]', '', 'id="final_total" class="form-control form-control-sm" readonly="true"')
					?>
				</div>
			</div>
		</div>
		<img src="<?php echo site_url('assets/images/tick.png') ?>" id="saveimg"> <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-primary" accesskey="s"'), ' ', form_reset('reset', $this->lang->line('reset'), 'class="btn btn-link"') ?>
	</div>
</div>
<?php echo form_close() ?>
<script type="text/javascript">
	const ItemLineTpl = <?php echo json_encode($this->load->view('return_purchases/return_purhcase_form_items_line', [], true)) ?>;
</script>
<!-- Account Form Modal -->
<?php
$this->load->view('accounts/modal_form');
?>
<!-- Item Form Modal -->
<?php
$this->load->view('items/modal_form');
?>