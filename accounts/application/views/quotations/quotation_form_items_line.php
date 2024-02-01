<tr id="<?php echo 'item-%d' ?>" data-index="<?php echo '%d' ?>">
    <td style="width: 20px;">
        <input name="transItems[%d][transaction_id]" type="text" class="d-none i-transaction_id" />
        <input name="transItems[%d][item_id]" type="text" class="d-none i-item_id" />
        <input name="transItems[%d][mvt_type]" type="text" class="d-none i-mvt_type" />
        <input name="transItems[%d][item_cost]" type="text" class="d-none i-item_cost" />
        <button type="button" class="btn btn-sm btn-danger i-remove">
            <i class="fas fa-trash"></i>
        </button>
    </td> 
    <td class="i-nb"></td>
    <td class="i-name" style="width: 120px;"></td>
    <td class="i-brand"></td>
    <td class="i-artical_number" style="width: 100px;"></td>
    <td style="width: 120px"><?php echo form_dropdown('transItems[%d][warehouses]', $warehouse_list, '', 'id="warehouses" class="form-select form-select-sm form-select form-select-sm-sm  i-warehouse" style="width: 120px;"')
        ?></td>
    <td style="width: 120px"><?php echo form_dropdown('transItems[%d][shelfs]', '', '', 'id="shelfs" class="form-select form-select-sm form-select form-select-sm-sm  i-shelf" style="width: 120px;"')
        ?></td>
    <td><input name="transItems[%d][qty]" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-qty" style="width:40px;" /></td>
    <td><input name="transItems[%d][cost]" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-cost" style="width:100px;" readonly /></td>
    <td><input name="transItems[%d][item_profit]" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-item_profit" style="width:100px;" /></td>
    <td style="width: 60px;"><input name="transItems[%d][price]" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-price" style="width:100px;" /></td>
    <td class="i-subtotal"></td>
    <td><input name="transItems[%d][discount]" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-discount" style="width:80px;" /></td>
    <td class="i-total text-right"></td>
    <td class="i-total_profit text-right"></td>
    <td><input name="transItems[%d][profit_percent]" type="text" class="form-control form-control-sm form-control form-control-sm-sm i-profit_percent" style="width:100px;" /></td>
    <td class="text-right">
        <button type="button" class="btn btn-sm i-order" style="background-color: #404040; color:white;"><i class="fas fa-check-circle"></i></button>
    </td>
</tr>