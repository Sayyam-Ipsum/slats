<?php $count = 1;
foreach ($transItems as $x => $transItem) : ?>
    <tr id="<?php echo 'item-', $x ?>" data-index="<?php echo $x ?>">
        <td style="width: 20px;">
            <input name="transItems[<?php echo $x ?>][transaction_id]"
                   value="<?php echo $this->Transaction->get_field('id') ?>" id="transaction_id" type="text"
                   class="d-none i-transaction_id"/>
            <input name="transItems[<?php echo $x ?>][item_id]" value="<?php echo $transItem['item_id'] ?>" id="item_id"
                   type="text" class="d-none i-item_id"/>
            <input name="transItems[<?php echo $x ?>][mvt_type]" value="<?php echo $transItem['mvt_type'] ?>"
                   id="mvt_type" type="text" class="d-none i-mvt_type"/>
            <input name="transItems[<?php echo $x ?>][item_cost]" value="<?php echo $transItem['item_cost'] ?>"
                   id="item_cost" type="text" class="d-none i-item_cost"/>
            <button type="button" class="btn btn-sm btn-danger i-remove">
                <i class="fas fa-trash"></i>
            </button>
        </td>
        <td class="i-nb"><?php echo $count ?></td>
        <td class="i-name" style="width: 120px;">
            <small class="mb-1"><?php echo $transItem['description'] ?></small>
        </td>
        <td class="i-brand">
            <small class="mb-1"><?php echo $transItem['brand'] ?></small>
        </td>
        <td class="i-artical_number" style="width: 100px;">
            <small class="mb-1">
            <?php echo $transItem['artical_number'] ?>
            </small>
        </td>
        <td style="width: 120px"><?php echo form_dropdown('transItems[' . $x . '][warehouses]', $warehouse_list, $warehouse[$x], 'id="warehouses" class="form-select form-select-sm form-select form-select-sm-sm  i-warehouse" style="width: 120px;"')
            ?></td>
        <td style="width: 120px"><?php echo form_dropdown('transItems[' . $x . '][shelfs]', $shelf_list[$x], $shelf[$x], 'id="shelfs" class="form-select form-select-sm form-select form-select-sm-sm  i-shelf" style="width: 120px;"')
            ?></td>
        <td><input name="transItems[<?php echo $x ?>][qty]" value="<?php echo $transItem['qty'] ?>" id="qty" type="text"
                   class="form-control form-control-sm form-control form-control-sm-sm i-qty" style="width: 40px;"/></td>
        <td><input name="transItems[<?php echo $x ?>][cost]" value="<?php echo $transItem['cost'] ?>" id="cost"
                   type="text" class="form-control form-control-sm form-control form-control-sm-sm i-cost" style="width: 100px;" readonly/></td>
        <td><input name="transItems[<?php echo $x ?>][item_profit]" value="<?php echo $transItem['item_profit'] ?>"
                   id="item_profit" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-item_profit" style="width: 100px;"/></td>
        <td style="width: 60px;"><input name="transItems[<?php echo $x ?>][price]"
                                         value="<?php echo $transItem['price'] ?>" id="price" type="text"
                                         class="form-control form-control-sm form-control form-control-sm-sm i-price" style="width: 100px;"/></td>
        <td class="i-subtotal"></td>
        <td><input name="transItems[<?php echo $x ?>][discount]" value="<?php echo $transItem['discount'] ?>"
                   id="discount" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-discount" style="width: 80px;"/></td>
        <td class="i-total text-right"></td>
        <td class="i-total_profit text-right"></td>
        <td><input name="transItems[<?php echo $x ?>][profit_percent]" value="<?php echo $transItem['profit_percent'] ?>"
                   id="profit_percent" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-profit_percent" style="width: 100px;"/></td>

        <td class="text-right">
            <button type="button" class="btn btn-sm i-order" style="background-color: #404040; color:white;"><i
                        class="fas fa-check-circle"></i></button>
        </td>
    </tr>
    <?php $count++;
endforeach ?>