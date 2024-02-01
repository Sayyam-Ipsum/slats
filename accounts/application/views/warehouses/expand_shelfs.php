<?php if ($this->session->flashdata('message')) { ?>
	<div id="delete_ignore" class="alert alert-danger" style="text-align:center" onclick="document.getElementById('delete_ignore').style.display = 'none'">
		<strong><?php echo $this->session->flashdata('message') ?></strong>
	</div>
<?php } ?>
<div class="hide">
	<input type="text" id="warehouse_name" value="<?php echo $warehouse_name ?>" hidden>
</div>
<p class="text-right">
	<?php echo anchor('warehouses/add_shelfs/'.$warehouse_name, 'Add Shelf', 'accesskey="a" class="btn btn-primary" id="bgadd" style="width:180px;"') ?>
	<?php echo anchor('warehouses/index', 'Back', 'accesskey="b" class="btn btn-primary" id="bgback" style="width:180px;"') ?>
</p>
<div class="col-sm-12">
	<div class="col-sm-3">
		<h4><?php echo $title ?></h4>
		<hr />
	</div>
</div>
<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="warehousesTbl" data-num-rows="<?php echo $this->Warehouse->get('paginationTotalRows') ?>">
	<thead>
		<tr>
			<th><?php echo $this->lang->line('warehouse') ?></th>
			<th><?php echo $this->lang->line('shelf') ?></th>
			<th><?php echo $this->lang->line('actions') ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($records as $record) { ?>
			<tr>
				<td><?php echo $record['warehouse'] ?></td>
				<td><?php echo $record['shelf'] ?></td>
				<td><?php echo $record['id'] ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>