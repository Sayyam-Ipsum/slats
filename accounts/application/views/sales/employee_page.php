<?php if ($this->session->flashdata('message')) { ?>
	<div id="msg" class="alert alert-success" style="text-align:center" onclick="document.getElementById('msg').style.display = 'none'">
		<strong><?php echo $this->session->flashdata('message') ?></strong>
	</div>
<?php } ?>
<h3>Employee pickup Notes List</h3>
<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="salesTbl" data-num-rows="<?php echo $this->Transaction->get('paginationTotalRows') ?>">
	<thead>
		<tr>
			<th><?php echo $this->lang->line('invoice_number') ?></th>
			<th><?php echo $this->lang->line('order_number') ?></th>
			<th><?php echo $this->lang->line('transaction_date') ?></th>
			<th><?php echo $this->lang->line('value_date') ?></th>
			<th><?php echo $this->lang->line('customer_account') ?></th>
			<th><?php echo $this->lang->line('customer_number') ?></th>
			<th><?php echo $this->lang->line('sales_account') ?></th>
			<th><?php echo $this->lang->line('pickup') ?></th>
			<th><?php echo $this->lang->line('actions') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($records as $record) { ?>
			<tr>
				<td><?php echo $record['auto_no'] ?></td>
				<td><?php echo $record['relation_id'] ?></td>
				<td><?php echo $record['trans_date'] ?></td>
				<td><?php echo $record['value_date'] ?></td>
				<td><?php echo $record['account1'] ?></td>
				<td><?php echo $record['account_number1'] ?></td>
				<td><?php echo $record['account2'] ?></td>
				<td><?php echo $record['pickup'] ?></td>
				<td><?php echo $record['id'] ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
