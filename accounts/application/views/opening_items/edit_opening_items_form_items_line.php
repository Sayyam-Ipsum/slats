<?php foreach ($transItems as $x => $transItem) : ?>
<tr id="<?php echo 'item-', $x ?>" data-index="<?php echo $x ?>">
<td>
<input name="transItems[<?php echo $x ?>][transaction_id]" value="<?php echo $this->Transaction->get_field('id') ?>" id="transaction_id" type="text" class="form-control form-control-sm d-none i-transaction_id" />
<input name="transItems[<?php echo $x ?>][item_id]" value="<?php echo $transItem['item_id'] ?>" id="item_id" type="text" class="form-control form-control-sm d-none i-item_id" />
<input name="transItems[<?php echo $x ?>][mvt_type]" value="<?php echo $transItem['mvt_type'] ?>" id="mvt_type" type="text" class="form-control form-control-sm d-none i-mvt_type" />
    <br>
    <button type="button" class="btn btn-sm btn-danger i-remove">
<i class="fas fa-trash"></i>
</button>
</td>
<td class="i-EAN"><?php echo $transItem['EAN'] ?></td>
<td class="i-artical_number"><?php echo $transItem['artical_number'] ?></td>
<td><?php echo form_dropdown('transItems['.$x.'][warehouses]',$warehouses_list, $warehouse[$x], 'id="warehouses" class="form-select form-select-sm i-warehouse"')
?></td>
<td><?php echo form_dropdown('transItems['.$x.'][shelfs]', $shelf_list[$x] ,$shelf[$x], 'id="shelfs" class="form-select form-select-sm i-shelf"')
?></td>
<td><input name="transItems[<?php echo $x ?>][qty]" value="<?php echo $transItem['qty'] ?>" id="qty" type="text" class="form-control form-control-sm i-qty" /></td>
</tr>
<?php endforeach ?>