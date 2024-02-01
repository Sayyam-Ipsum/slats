<?php $count = 0;
foreach ($transItems as $x => $transItem) : $count++; ?>
    <tr id="<?php echo 'item-', $x ?>" data-index="<?php echo $x ?>">
        <td>
            <input name="transItems[<?php echo $x ?>][transaction_id]" value="<?php echo $this->Transaction->get_field('id') ?>" id="transaction_id" type="text" class="d-none i-transaction_id" />
            <input name="transItems[<?php echo $x ?>][item_id]" value="<?php echo $transItem['item_id'] ?>" id="item_id" type="text" class="d-none i-item_id" />
            <input name="transItems[<?php echo $x ?>][mvt_type]" value="<?php echo $transItem['mvt_type'] ?>" id="mvt_type" type="text" class="d-none i-mvt_type" />
            <input name="transItems[<?php echo $x ?>][item_cost]" value="<?php echo $transItem['item_cost'] ?>" id="item_cost" type="text" class="d-none i-item_cost" />
            <br>
            <button type="button" class="btn btn-sm btn-danger i-remove" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
            <br>
            <a href="sales/return_invoice_item/<?php echo $transItem['id'] ?>" class="btn btn-sm i-return" style="background-color:#444; color:white;" title="Return"><i class="fas fa-redo"></i></a>
        </td>
        <td class="i-nb"><?php echo $count ?></td>
        <td class="i-name"><?php echo $transItem['description'] ?></td>
        <td class="i-brand"><?php echo $transItem['brand'] ?></td>
        <td class="i-artical_number"><?php echo $transItem['artical_number'] ?></td>
        <td><?php echo form_dropdown('transItems[' . $x . '][warehouses]', $warehouse_list, $warehouse[$x],  'id="warehouses" class="form-select form-select-sm i-warehouse" style="width: 100px;"')
            ?></td>
        <td><?php echo form_dropdown('transItems[' . $x . '][shelfs]', $shelf_list[$x], $shelf[$x], 'id="shelfs" class="form-select form-select-sm i-shelf" style="width: 100px;"')
            ?></td>
        <td><input name="transItems[<?php echo $x ?>][qty]" value="<?php echo $transItem['qty'] ?>" id="qty" type="text" class="form-control form-control-sm i-qty" style="width: 70px;" />
            <div id="error_qty" class="form-control form-control-sm i-error_qty" style="height:auto; text-align:center; display: none;"></div>
        </td>
        <td><input name="transItems[<?php echo $x ?>][cost]" value="<?php echo $transItem['cost'] ?>" id="cost" type="text" class="form-control form-control-sm i-cost" style="width: 100px;" readonly /></td>
        <td><input name="transItems[<?php echo $x ?>][price]" value="<?php echo $transItem['price'] ?>" id="price" type="text" class="form-control form-control-sm i-price" style="width: 100px;" /></td>
        <td class="i-subtotal"></td>
        <td><input name="transItems[<?php echo $x ?>][discount]" value="<?php echo $transItem['discount'] ?>" id="discount" type="text" class="form-control form-control-sm i-discount" style="width: 100px;" /></td>
        <td><input name="transItems[<?php echo $x ?>][profit]" value="<?php echo $transItem['profit'] ?>" id="profit" type="text" class="form-control form-control-sm i-profit" style="width: 100px;" readonly /></td>
        <td><input name="transItems[<?php echo $x ?>][item_profit]" value="<?php echo $transItem['item_profit'] ?>" id="item_profit" type="text" class="form-control form-control-sm i-item_profit" style="width: 100px;" /></td>

        <td class="i-total text-right"></td>
    </tr>
<?php endforeach ?>