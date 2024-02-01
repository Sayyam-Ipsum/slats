<h3 align="center"><b>all account</b></h3>

<div class="table-responsive">
    <table class="table table-bordered" align="center" width="60%" border="1">
        <tr>
            <th>id</th>
            <th>name</th>
            <th>address</th>
            <th>type</th>
            <th>phone_a</th>
            <th>phone_b</th>
            <th>debit</th>
            <th>credit</th>
            <th>balance</th>
            <th>open balance</th>
            <th>select</th>
      
        </tr>
        <?php
        $array_account =  $this->account_model->get_all_acount();
        echo $from_to ;


        // $key =$id and $val = $quantiy

        if (!empty($array_account)) {

            foreach ($array_account as $ar_ac) {


        ?>

                <tr>
                    <td><?php echo $ar_ac['A_id'];  ?></td>
                    <td><?php echo $ar_ac['A_name'];  ?></td>
                    <td><?php echo $ar_ac['address'];  ?></td>
                    <td><?php echo $ar_ac['A_type']; ?></td>
                    <td><?php echo $ar_ac['phone_a']; ?></td>
                    <td><?php echo $ar_ac['phone_b']; ?></td>
                    <td><?php echo $ar_ac['debit']; ?></td>
                    <td><?php echo $ar_ac['credit']; ?></td>
                    <td><?php echo $ar_ac['balance']; ?></td>
                    <td><?php echo $ar_ac['open_balance']; ?></td>
                 

<td> <?php echo form_open(base_url().'purchase/select_account/' . $ar_ac['A_id'].'/'.$from_to); ?>
                        <input type="submit" value="select" class="btn btn-primary">
                    </form>
                    </td>


          
                </tr>



        <?php }
        } else {
            echo "<p style=\"color:red;\">no items added for now !! </p>";
        }            ?>



    </table>
</div>