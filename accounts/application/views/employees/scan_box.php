<div class="col-sm-12">
	<div class="col-sm-4">
		<h4><strong><?php echo $title ?></strong></h4>
		<hr>
	</div>
</div>
<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="salesemployeeTbl" data-num-rows="<?php echo $this->Transaction->get('paginationTotalRows') ?>">
	<thead>
		<tr>
			<th><?php echo $this->lang->line('invoice_number') ?></th>
			<th><?php echo $this->lang->line('transaction_date') ?></th>
			<th><?php echo $this->lang->line('customer_account') ?></th>
			<th><?php echo $this->lang->line('model') ?></th>
			<th><?php echo $this->lang->line('VIN') ?></th>
			<th><?php echo $this->lang->line('OE') ?></th>
			<th><?php echo $this->lang->line('description') ?></th>
			<th data-no-search="0"><?php echo $this->lang->line('actions') ?></th>
		</tr>
	</thead>
	<tbody style="font-size: small;">
		<?php foreach ($records as $record) { ?>
			<tr>
				<td><?php echo $record['auto_no'] ?></td>
				<td><?php echo $record['trans_date'] ?></td>
				<td><?php echo $record['account1'] ?></td>
				<td><?php echo $record['model'] ?></td>
				<td><?php echo $record['VIN'] ?></td>
				<td><?php echo $record['OE'] ?></td>
				<td><?php echo $record['description'] ?></td>
				<td><?php echo $record['id'] ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>
