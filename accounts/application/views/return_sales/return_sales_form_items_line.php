<tr id="<?php echo 'item-%d' ?>" data-index="<?php echo '%d' ?>">
    <td>
        <input name="transItems[%d][transaction_id]" type="text" class="d-none i-transaction_id" />
        <input name="transItems[%d][item_id]" type="text" class="d-none i-item_id" />
        <input name="transItems[%d][mvt_type]" type="text" class="d-none i-mvt_type" />
        <input name="transItems[%d][item_price]" type="text" class="d-none i-item_price" />
        <button type="button" class="btn btn-sm btn-danger i-remove">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    <td class="i-EAN    "></td>
    <td class="i-artical_number"></td>
    <td><?php echo form_dropdown('transItems[%d][warehouses]', $warehouses_list, '',  'id="warehouses" class="form-control form-control-sm i-warehouse" type="input"')
        ?></td>
    <td><?php echo form_dropdown('transItems[%d][shelfs]', '', '', 'id="shelfs" class="form-control form-control-sm i-shelf"')
        ?></td>
    <td><input name="transItems[%d][qty]" type="text" class="form-control form-control-sm i-qty" /></td>
    <td><input name="transItems[%d][price]" type="text" class="form-control form-control-sm i-price" /></td>
    <td class="i-subtotal"></td>
    <td><input name="transItems[%d][discount]" type="text" class="form-control form-control-sm i-discount" /></td>
    <td class="i-total text-right"></td>
</tr>