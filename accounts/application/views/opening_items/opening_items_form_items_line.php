<tr id="<?php echo 'item-%d' ?>" data-index="<?php echo '%d' ?>">
    <td>
        <input name="transItems[%d][transaction_id]" id="transaction_id" type="text" class="form-control form-control-sm d-none i-transaction_id" />
        <input name="transItems[%d][item_id]" id="item_id" type="text" class="form-control form-control-sm d-none i-item_id" />
        <input name="transItems[%d][mvt_type]" id="mvt_type" type="text" class="form-control form-control-sm d-none i-mvt_type" />
        <button type="button" class="btn btn-sm btn-danger i-remove">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    <td class="i-EAN"></td>
    <td class="i-artical_number"></td>
    <td><?php echo form_dropdown('transItems[%d][warehouses]', $warehouses_list, '',  'id="warehouses" class="form-select form-select-sm i-warehouse" type="input"')
        ?></td>
    <td><?php echo form_dropdown('transItems[%d][shelfs]', '', '', 'id="shelfs" class="form-select form-select-sm i-shelf"')
        ?></td>
    <td><input name="transItems[%d][qty]" id="qty" type="text" class="form-control form-control-sm i-qty" /></td>
</tr>