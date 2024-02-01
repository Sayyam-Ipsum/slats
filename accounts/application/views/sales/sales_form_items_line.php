<tr id="<?php echo 'item-%d' ?>" data-index="<?php echo '%d' ?>">
    <td>
        <input name="transItems[%d][transaction_id]" type="text" class="d-none i-transaction_id" />
        <input name="transItems[%d][item_id]" type="text" class="d-none i-item_id" />
        <input name="transItems[%d][mvt_type]" type="text" class="d-none i-mvt_type" />
        <input name="transItems[%d][item_cost]" type="text" class="d-none i-item_cost" />
        <button type="button" class="btn btn-sm btn-danger i-remove">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    <td class="i-nb"></td>
    <td class="i-name"></td>
    <td class="i-brand"></td>
    <td class="i-artical_number"></td>
    <td><?php echo form_dropdown('transItems[%d][warehouses]', $warehouse_list, '', 'id="warehouses" class="form-select form-select-sm i-warehouse" style="width: 100px;"')
        ?></td>
    <td><?php echo form_dropdown('transItems[%d][shelfs]', '', '', 'id="shelfs" class="form-select form-select-sm i-shelf" style="width: 100px;"')
        ?></td>
    <td><input name="transItems[%d][qty]" type="text" class="form-control form-control-sm i-qty" style="width: 70px;"/>
        <div id="error_qty" class="form-control form-control-sm i-error_qty" style="text-align:center; display: none; height: auto;"></div>
    </td>
    <td><input name="transItems[%d][cost]" type="text" class="form-control form-control-sm i-cost" style="width:100px;" readonly/></td>
    <td><input name="transItems[%d][price]" type="text" class="form-control form-control-sm i-price" style="width: 100px;"/></td>
    <td class="i-subtotal"></td>
    <td><input name="transItems[%d][discount]" type="text" class="form-control form-control-sm i-discount" style="width: 100px;"/></td>    
    <td><input name="transItems[%d][profit]" type="text" class="form-control form-control-sm i-profit" style="width: 100px;" readonly/></td>
    <td><input name="transItems[%d][item_profit]" type="text" class="form-control form-control-sm i-item_profit" style="width:100px;"/></td>
    <td class="i-total text-right"></td>
</tr>