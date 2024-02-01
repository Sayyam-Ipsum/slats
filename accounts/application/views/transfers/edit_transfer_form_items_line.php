<?php foreach ($transfer_lines as $x => $transfer_line) : ?>
    <tr id="<?php echo 'item-', $x ?>" data-index="<?php echo $x ?>">
        <td>
            <input name="transItems[<?php echo $x ?>][transaction_id]" value="<?php echo $this->Transaction->get_field('id') ?>" id="transaction_id" type="text" class="d-none i-transaction_id" />
            <input name="transItems[<?php echo $x ?>][item_id]" value="<?php echo $transfer_line['item_id'] ?>" id="item_id" type="text" class="d-none i-item_id" />
            <input name="transItems[<?php echo $x ?>][mvt_type]" value="" id="mvt_type" type="text" class="d-none i-mvt_type" />
            <button type="button" class="btn btn-sm btn-danger i-remove">
                <i class="fas fa-trash"></i>
            </button>
        </td>
        <td class="i-EAN"><?php echo $transfer_line['EAN'] ?></td>
        <td class="i-artical_number"><?php echo $transfer_line['artical_number'] ?></td>
        <td><?php
            echo form_dropdown('transItems[' . $x . '][from]', $transfer_line['from_list'], $transfer_line['from'], 'id="from" class="form-select form-select-sm i-from"')
            ?></td>
        <td><?php
            echo form_dropdown('transItems[' . $x . '][to]',  $all_warehouses_shelfs, $transfer_line['to'], 'id="to" class="form-select form-select-sm i-to"')
            ?></td>
        <td class="i-max_qty" style="text-align: center;"></td>
        <td><input name="transItems[<?php echo $x ?>][qty]" value="<?php echo $transfer_line['qty'] ?>" id="qty" type="text" class="form-control form-control-sm i-qty" /></td>
        <td><input name="transItems[<?php echo $x ?>][cost]" value="<?php echo $transfer_line['cost'] ?>" id="cost" type="text" class="form-control form-control-sm i-cost" /></td>
    </tr>
<?php endforeach ?>