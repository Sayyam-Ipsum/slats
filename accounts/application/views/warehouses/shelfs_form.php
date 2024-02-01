<?php if ($this->session->flashdata('message')) { ?>    <div id="delete_ignore" class="alert alert-danger" style="text-align:center" onclick="document.getElementById('delete_ignore').style.display = 'none'">        <strong><?php echo $this->session->flashdata('message') ?></strong>    </div><?php } ?><p class="text-right">    <img src="<?php echo site_url('assets/images/back.png') ?>" id="downloadimg"> <?php echo anchor('warehouses/expand/' . $warehouse, 'Back', 'accesskey="b" class="btn btn-primary"') ?></p><div class="col-sm-12">    <div class="col-sm-3">        <h4><?php echo $title ?></h4>        <hr />    </div></div><input type="text" id="warehouse_name" value="<?php echo $warehouse ?>" hidden><div class="row">    <div class="col-sm-6 col-sm-offset-2 form-group">        <label class="col-sm-5 control-label" for="warehouse">Warehouse</label>        <div class="col-sm-7">            <?php            echo form_dropdown('warehouse', $warehouses_list, $warehouse, 'autofocus id="warehouse" maxlength="255" class="form-control form-control-sm" required'),            $this->Warehouse->get_error('warehouse')            ?>        </div>    </div></div><div class="row">    <div class="col-sm-6 col-sm-offset-2 form-group">        <label class="col-sm-5 control-label" for="tag_shelfs">Multi shelfs</label>        <div class="col-sm-6">            <input type="text" name="tag_shelfs" id="tag_shelfs" class="form-control form-control-sm">            <div id="error_shelfs" style="text-align:center" onclick="document.getElementById('error_shelfs').style.display = 'none'"></div>        </div>    </div></div><div class="form-group">    <div class="col-sm-offset-4 col-sm-4 text-right">        <button type="button" id="save" class="btn btn-primary" accesskey="s">Save</button>    </div></div>