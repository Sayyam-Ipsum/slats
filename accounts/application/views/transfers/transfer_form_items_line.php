<tr id="<?php echo 'item-%d' ?>" data-index="<?php echo '%d' ?>">
    <td>
        <input name="transItems[%d][transaction_id]" type="text" class="d-none i-transaction_id" />
        <input name="transItems[%d][item_id]" type="text" class="d-none i-item_id" />
        <input name="transItems[%d][mvt_type]" type="text" class="d-none i-mvt_type" />
        <button type="button" class="btn btn-sm btn-danger i-remove">
            <i class="fas fa-trash"></i>
        </button>
    </td>
    <td class="i-EAN"></td>
    <td class="i-artical_number"></td>
    <td><?php
        echo form_dropdown('transItems[%d][from]', '', '', 'id="from" class="form-select form-select-sm i-from"')
        ?></td>
    <td><?php
        echo form_dropdown('transItems[%d][to]', $all_warehouses_shelfs, '', 'id="to" class="form-select form-select-sm i-to"')
        ?></td>
    <td class="i-max_qty" style="text-align: center;"></td>
    <td><input name="transItems[%d][qty]" type="text" class="form-control form-control-sm i-qty" />
        <div id="error_qty" class="form-control form-control-sm i-error_qty" style="text-align:center; display: none;"></div>
    </td>
    <td><input name="transItems[%d][cost]" type="text" class="form-control form-control-sm i-cost" />
        <div id="error_cost" class="form-control form-control-sm i-error_cost" style="text-align:center; display: none;"></div>
    </td>
</tr>