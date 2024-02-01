<?php $count = 0;
foreach ($transItems as $x => $transItem) : $count++; ?>
    <tr id="<?php echo 'item-', $x ?>" data-index="<?php echo $x ?>">
        <td class="i-nb"><?php echo $count ?></td>
        <td class="i-name"><?php echo $transItem['description'] ?></td>
        <td class="i-brand"><?php echo $transItem['brand'] ?></td>
        <td class="i-artical_number"><?php echo $transItem['artical_number'] ?></td>
        <td class="i-EAN"><?php echo $transItem['EAN'] ?></td>
        <td class="i-alternative"><?php echo $transItem['alternative'] ?></td>
        <td hidden><?php echo form_dropdown('transItems[' . $x . '][warehouses]', $warehouses_list, $warehouse[$x], 'id="warehouses" class="form-select form-select-sm i-warehouse" style="width:min-content;"/>')
                    ?></td>
        <td hidden><?php echo form_dropdown('transItems[' . $x . '][shelfs]', $shelf_list[$x], $shelf[$x], 'id="shelfs" class="form-select form-select-sm i-shelf" style="width:min-content;"')
                    ?></td>
        <td><input name="transItems[<?php echo $x ?>][qty]" value="<?php echo $transItem['qty'] ?>" id="qty" type="text" class="form-control form-control-sm i-qty" style="width:100px;" /></td>
        <td class="d-none"><input name="transItems[<?php echo $x ?>][price]" value="<?php echo $transItem['price'] ?>" id="price" type="text" class="form-control form-control-sm i-price d-none" style="width:100px;" /></td>
        <td class="d-none"><input name="transItems[<?php echo $x ?>][cost]" value="<?php echo $transItem['cost'] ?>" id="cost" type="text" class="form-control form-control-sm i-cost d-none" style="width:100px;" /></td>
        <td class="d-none"><input name="transItems[<?php echo $x ?>][discount]" value="<?php echo $transItem['discount'] ?>" id="discount" type="text" class="form-control form-control-sm i-discount" style="width:100px;" /></td>
        <td class="i-net_cost text-center d-none"></td>
        <td class="i-total text-right d-none"></td>
        <td>
            <input name="transItems[<?php echo $x ?>][transaction_id]" value="<?php echo $this->Transaction->get_field('id') ?>" id="transaction_id" type="text" class="d-none i-transaction_id" />
            <input name="transItems[<?php echo $x ?>][item_id]" value="<?php echo $transItem['item_id'] ?>" id="item_id" type="text" class="d-none i-item_id" />
            <input name="transItems[<?php echo $x ?>][mvt_type]" value="<?php echo $transItem['mvt_type'] ?>" id="mvt_type" type="text" class="d-none i-mvt_type" />
            <input name="transItems[<?php echo $x ?>][relation_id]" value="<?php echo $transItem['relation_id'] ?>" id="relation_id" type="text" class="d-none i-relation_id" />
            <button type="button" class="btn btn-sm btn-danger i-remove" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
            <a href="purchases/return_received_item/<?php echo $transItem['id'] ?>" class="btn btn-sm i-return" style="background-color:#444; color:white;" title="Return"><i class="fas fa-redo"></i></a>
        </td>
    </tr>
<?php endforeach ?>