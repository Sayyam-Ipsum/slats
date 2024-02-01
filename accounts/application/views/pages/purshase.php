<table border="1">

    <tr>
    <td>purshase from </td>
    <td><input type="text" name="p_r_from" value="<?php echo $this->session->userdata('first_id')?>"></td>
    <td><input type="text" name="p_name_from" ></td>
    <td>  <?php  $from_ac="from";
    echo form_open('/purshase/pursh_select_account/' . $from_ac); ?>
                        <input type="submit" value="get" class="btn btn-danger">
                        </form></td>

    </tr>

    <tr>
    <td>purshase to </td>
    <td><input type="text" name="p_r_to" value="<?php echo $this->session->userdata('second_id')?>"></td>
    <td><input type="text" name="p_name_to" ></td>
    <td>  <?php $to_ac="to"; 
    echo form_open('/purshase/pursh_select_account/'. $to_ac); ?>
                        <input type="submit" value="get" class="btn btn-danger">
                        </form></td>

    </tr>

</table>