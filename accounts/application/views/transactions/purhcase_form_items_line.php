<tr id="<?php echo 'item-%d' ?>" data-index="<?php echo '%d' ?>">
    <td>
        <input name="transItems[%d][transaction_id]" id="transaction_id" type="text" class="d-none i-transaction_id" />
        <input name="transItems[%d][item_id]" id="item_id" type="text" class="d-none i-item_id" />
        <input name="transItems[%d][mvt_type]" id="mvt_type" type="text" class="d-none i-mvt_type" />
        <input name="transItems[%d][relation_id]" id="relation_id" type="text" class="d-none i-relation_id" />
        <button type="button" class="btn btn-sm btn-danger i-remove">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    <td class="i-nb"></td>
    <td class="i-name"></td>
    <td class="i-brand"></td>
    <td class="i-artical_number"></td>
    <td class="i-EAN"></td>
    <td class="i-alternative"></td>
    <td hidden><?php echo form_dropdown('transItems[%d][warehouses]', $warehouses_list, '',  'id="warehouses" class="form-select form-select-sm i-warehouse" type="input" style="width:min-content;"')
        ?></td>
    <td hidden><?php echo form_dropdown('transItems[%d][shelfs]', '', '', 'id="shelfs" class="form-select form-select-sm i-shelf" style="width:min-content;"')
        ?></td>
    <td><input name="transItems[%d][qty]" id="qty" type="text" class="form-control form-control-sm i-qty" style="width:100px;"/></td>
    <td class="d-none"><input name="transItems[%d][price]" id="price" type="text" class="form-control form-control-sm i-price d-none" style="width:100px;"/></td>
    <td class="d-none"><input name="transItems[%d][cost]" id="cost" type="text" class="form-control form-control-sm i-cost d-none" style="width:100px;"/></td>
    <td class="d-none"><input name="transItems[%d][discount]" id="discount" type="text" class="form-control form-control-sm i-discount" style="width:100px;"/></td>
    <td class="i-net_cost text-center d-none"></td>
    <td class="i-total text-right d-none"></td>
</tr>