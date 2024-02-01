<div class="row form-group">	<div class="col-sm-4">		<div class="col-sm-4" <?php echo ($total_permission == '0') ? 'hidden' : ''; ?>>			<label for="tot">Total Cost</label>		</div>		<div class="col-sm-8" <?php echo ($total_permission == '0') ? 'hidden' : ''; ?>>			<input type="text" name="tot" value="<?php echo $tot ?>" class="form-control form-control-sm" id="tot" style="text-align: center;" readonly>		</div>	</div>	<div class="col-sm-8">		<p style="text-align: right;">			<?php echo anchor('warehouses/find_alternatives', 'Alternatives', 'accesskey="a" class="btn btn-primary" id="bgsearch"') ?>			<?php echo anchor('opening_items/add', 'Add Opening', 'accesskey="a" class="btn btn-primary" id="bgadd"') ?>		</p>	</div></div><div class="col-sm-12">	<div class="col-sm-4">		<h4><strong><?php echo $title ?></strong></h4>		<hr>	</div></div><div class="row">	<div class="col-sm-12 form-group">	<?php echo form_open('', 'class="form-horizontal" ') ?>		<div class="col-sm-4">			<div class="col-sm-4">				<label for="artical_number_alt">Alternative</label>			</div>			<div class="col-sm-8">				<?php				echo form_input('artical_number_alt', '', 'id="artical_number_alt" class="form-control form-control-sm" placeholder="By Artical number"')				?>			</div>		</div>		<div class="col-sm-2">			<button name="search" type="submit" class="btn bt-link btn-s" title="search"><i class="glyphicon glyphicon-search"></i></button>		</div>	</div>	<?php echo form_close() ?>	<div class="col-sm-12 form-group">		<?php echo form_open('', 'class="form-horizontal" onsubmit="return false" id="dtAdvFltrs"') ?>		<div class="col-sm-3" hidden>			<div class="col-sm-4">				<label for="EAN">Alternative</label>			</div>			<div class="col-sm-8">				<?php				echo form_input('artical_number', '', 'id="artical_number" class="form-control form-control-sm" placeholder="By Artical number"')				?>			</div>		</div>		<div class="col-sm-4">			<div class="col-sm-4">				<label for="warehouse">Warehouse</label>			</div>			<div class="col-sm-8">				<?php				echo form_dropdown('warehouse', $warehouses, '', 'id="filter_warehouse" class="form-control form-control-sm"')				?>			</div>		</div>		<div class="col-sm-4">			<div class="col-sm-4">				<label for="shelf">Shelf</label>			</div>			<div class="col-sm-8">				<input type="text" list="shelfs" id="filter_shelf" name="shelf" value="" class="form-control form-control-sm" />				<datalist id="shelfs">				</datalist>			</div>		</div>		<div class="col-sm-3">			<button name="search" type="submit" id="filter" class="btn bt-link btn-s" title="Filter"><i class="glyphicon glyphicon-filter"></i></button>			<button type="button" name="reset_filters" id="reset_filters" title="Refresh" class="btn bt-link btn-s btn-small-design"><i class="glyphicon glyphicon-refresh"></i></button>			<button name="adjust" type="button" id="adjust" title="Adjust Minus" class="btn bt-link btn-s" style="background-color: #404040; color:white;"><i class="glyphicon glyphicon-minus"></i></button>			<button name="adjust_plus" type="button" id="adjust_plus" title="Adjust Plus" class="btn bt-link btn-s" style="background-color: #404040; color:white;"><i class="glyphicon glyphicon-plus"></i></button>			<button name="adjust_shelf" type="button" id="adjust_shelf" title="Clear Shelf" class="btn bt-link btn-s" style="background-color: #404040; color:white;"><i class="glyphicon glyphicon-remove-circle"></i></button>		</div>	</div></div><?php echo form_close() ?><div class="row form-group">	<table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="warehousesTbl" data-num-rows="<?php echo $this->Warehouse->get('paginationTotalRows') ?>">		<thead>			<tr> 				<th><?php echo $this->lang->line('barcode') ?></th>				<th><?php echo $this->lang->line('EAN') ?></th>				<th><?php echo $this->lang->line('name') ?></th>				<th><?php echo $this->lang->line('artical_number') ?></th>				<th><?php echo $this->lang->line('brand') ?></th>				<th><?php echo $this->lang->line('alternative') ?></th>				<th data-no-search="0"><?php echo $this->lang->line('qty') ?></th>				<th data-no-search="0"><?php echo $this->lang->line('warehouse') ?></th>				<th data-no-search="0"><?php echo $this->lang->line('shelf') ?></th>				<th data-no-search="0"><?php echo $this->lang->line('actions') ?></th>			</tr>		</thead>		<tbody id="tableLines">			<?php foreach ($records as $record) { ?>				<tr>					<td><?php echo $record['barcode'] ?></td>					<td><?php echo $record['EAN'] ?></td>					<td><?php echo $record['description'] ?></td>					<td><?php echo $record['artical_number'] ?></td>					<td><?php echo $record['brand'] ?></td>					<td><?php echo $record['alternative'] ?></td>					<td><?php echo $record['total_qty'] ?></td>					<td><?php echo $record['warehouse'] ?></td>					<td><?php echo $record['shelf'] ?></td>					<td><?php echo $record['item_id'] ?></td>				</tr>			<?php } ?>		</tbody>	</table></div><div class="row form-group">	<div class="col-sm-12">		<div class="col-sm-6">			<h4>Alternatives List</h4>			<hr>		</div>	</div>	<center>		<table style="width: 95%;" class="table table-bordered table-striped table-hover table-condensed table-responsive" id="alternativesTbl">			<thead>				<tr style="background-color:#404040; color:white;">					<th><?php echo $this->lang->line('brand'); ?></th>					<th><?php echo $this->lang->line('artical_number'); ?></th>					<th><?php echo $this->lang->line('warehouse'); ?></th>					<th><?php echo $this->lang->line('shelf'); ?></th>					<th><?php echo $this->lang->line('qty'); ?></th>				</tr>			</thead>			<tbody>				<?php if ($rows) {					foreach ($rows as $row) { ?>						<tr style="<?php echo ($row['artical_number'] != $artical_nb) ? 'background: #FEC5BF;' : ''; ?>">							<td><?php echo $row['brand'] ?></td>							<td><?php echo $row['artical_number'] ?></td>							<td><?php echo $row['warehouse'] ?></td>							<td><?php echo $row['shelf'] ?></td>							<td><?php echo $row['total_qty'] ?></td>						</tr>					<?php }				} else {  ?>					<tr>						<td colspan="5">							<center>No Data Found</center>						</td>					</tr>				<?php }   ?>			</tbody>		</table>	</center></div><!-- Transfer Modal --><div id="itemTransferModalForm" class="modal fade" role="dialog">	<div class="modal-dialog">		<div class="modal-content">			<div class="modal-header">				<button type="button" class="close" data-dismiss="modal">&times;</button>				<h4 class="modal-title">					<legend>Transfer Products</legend>				</h4>			</div>			<div class="modal-body">			</div>			<div class="modal-footer">				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>			</div>		</div>	</div></div><!-- Adjust Modal --><?php$this->load->view('adjust/modal');?><!-- Adjust row Modal --><?php$this->load->view('adjust/row_adjust_modal');?><!-- Confirm modal --><div class="modal" id="confirm_modal" tabindex="-1" role="dialog">	<div class="modal-dialog" role="document">		<div class="modal-content">			<div class="modal-body" style="padding: 80px; color:black; font-weight:bold;">				<center>					<div style="margin-bottom: 20px;">						<img src="assets/images/warning.png" height="50px" width="auto">					</div>					<div style="margin-bottom: 20px;">						<input type="text" id="confirm_warehouse_id" hidden>						<h3><b>Confirm Action Clear Shelf: </b><span id="confirm_shelf_name"></span></h3>						<h3><b>Are You Sure?</b></h3>					</div>					<div>						<button class="btn" data-dismiss="modal" aria-label="Close" style="background-color: #404040; color:white;"><i class="fa fa-close"></i> <?php echo $this->lang->line('close') ?></button>						<button class="btn" id="submit_confirm"><i class="glyphicon glyphicon-ok"></i> Confirm</button>					</div>				</center>			</div>		</div>	</div></div>