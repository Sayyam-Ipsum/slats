<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <h3><?php echo $title ?></h3>
                <p class="mb-0">
                    <?php if ($this->session->flashdata('message_success')) { ?>
                <div id="save_msg" class="alert alert-success" style="text-align:center"
                     onclick="document.getElementById('save_msg').style.display = 'none'">
                    <strong><?php echo $this->session->flashdata('message_success') ?></strong>
                </div>
                <?php } ?>
                </p>
            </div>
        </div>
    </div>
</div>
<div class="row g-0">
    <div class="col-lg-12 pe-lg-2">
        <div class="card mb-3">
            <div class="card-header bg-light">
                <p class="text-right">
                    <?php echo anchor('items/add_opening_item/' . $this->Item->get_field('id'), 'Opening', 'id="open_product" class="btn btn-falcon-default btn-sm me-1 mb-1"') ?>
                    <?php echo anchor('items/index', 'Back', 'accesskey="b"  class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgback"') ?>
                </p>
            </div>
            <?php echo form_open('', 'id="itemsform" name="itemsform" class="form-horizontal" role="form" onsubmit="return validation();" autocomplete="off" novalidate') ?>
            <div class="card-body">
                <input name="id" id="id" type="hidden" value="<?php echo $this->Item->get_field('id') ?>"/>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="barcode"><?php echo $this->lang->line('barcode') ?></label>
                            <?php
                            echo form_input('barcode', $this->Item->get_field('barcode'), 'id="barcode" maxlength="32" class="form-control form-control-sm" style="width:50%;" required'),
                            $this->Item->get_error('barcode')
                            ?>
                            <div id="error_barcode" style="text-align:center"
                                 onclick="document.getElementById('error_barcode').style.display = 'none'"></div>
                            <hr>
                            <div class="col-sm-1">
                                <p class="text-right">
                                    <button name="generate" id="generate" accesskey="a" class="btn btn-primary">
                                        Generate
                                    </button>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="description"><?php echo $this->lang->line('name') ?></label>
                            <?php
                            echo form_input('description', $this->Item->get_field('description'), 'id="description" maxlength="255" class="form-control form-control-sm" required'),
                            $this->Item->get_error('description')
                            ?>
                            <div id="error_description" style="text-align:center"
                                 onclick="document.getElementById('error_description').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="brand"><?php echo $this->lang->line('brand') ?></label>
                            <?php
                            echo form_input('brand', $this->Item->get_field('brand'), 'id="brand" maxlength="255" class="form-control form-control-sm"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="EAN"><?php echo $this->lang->line('EAN') ?></label>
                            <?php
                            echo form_input('EAN', $this->Item->get_field('EAN'), 'id="EAN" maxlength="255" class="form-control form-control-sm" required'),
                            $this->Item->get_error('EAN')
                            ?>
                            <div id="error_EAN" style="text-align:center"
                                 onclick="document.getElementById('error_EAN').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="artical_number"><?php echo $this->lang->line('artical_number') ?></label>
                            <?php
                            echo form_input('artical_number', $this->Item->get_field('artical_number'), 'id="artical_number" maxlength="255" class="form-control form-control-sm" required'),
                            $this->Item->get_error('artical_number')
                            ?>
                            <div id="error_artical_number" style="text-align:center"
                                 onclick="document.getElementById('error_artical_number').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label"
                                   for="category"><?php echo $this->lang->line('category') ?></label>
                            <?php
                            echo form_input('category', $this->Item->get_field('category'), 'id="category" maxlength="255" class="form-control form-control-sm"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="item_range"><?php echo $this->lang->line('range') ?></label>
                            <?php
                            echo form_input('item_range', $this->Item->get_field('item_range'), 'id="item_range" maxlength="255" class="form-control form-control-sm"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="OE_nb"><?php echo $this->lang->line('OE_nb') ?></label>
                            <?php
                            echo form_input('OE_nb', $this->Item->get_field('OE_nb'), 'id="OE_nb" maxlength="255" class="form-control form-control-sm"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"
                                       for="qty_multiplier"><?php echo $this->lang->line('qty_multiplier') ?></label>
                                <div class="col-sm-9">
                                    <div class="form-check">
                                        <input type="radio" name="qty_multiplier" id="qty_multiplier1"
                                               value="" <?php echo ($this->Item->get_field('qty_multiplier') !== '2') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="qty_multiplier1">
                                            ×1
                                        </label>
                                        &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                        <input type="radio" name="qty_multiplier" id="qty_multiplier2"
                                               value="2" <?php echo ($this->Item->get_field('qty_multiplier') == '2') ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="qty_multiplier2">
                                            ×2
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label class="form-label" for="alternative"><?php echo $this->lang->line('alternative') ?></label>
                                <?php
                                echo form_input('alternative', $this->Item->get_field('alternative'), 'id="alternative" maxlength="255" class="form-control form-control-sm"')
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="weight"><?php echo $this->lang->line('weight_(kg)') ?></label>
                            <?php
                            echo form_input('weight', $this->Item->get_field('weight'), 'id="weight" maxlength="255" class="form-control form-control-sm"')
                            ?>
                            <div id="error_weight" style="text-align:center"
                                 onclick="document.getElementById('error_weight').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="description2">Description</label>
                            <?php echo form_textarea('description2', $this->Item->get_field('description2'), 'id="description2" class="form-control form-control-sm" style="height:100px;"') ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="description2"><?php echo $this->lang->line('open_cost') ?></label>
                            <?php
                            echo form_input('open_cost', $this->Item->get_field('open_cost'), 'id="open_cost" class="form-control form-control-sm" ')
                            ?>
                            <div id="error_open_cost" style="text-align:center"
                                 onclick="document.getElementById('error_open_cost').style.display = 'none'"></div>
                            <div id="warning_open_cost" style="text-align:center"
                                 onclick="document.getElementById('warning_open_cost').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="open_qty"><?php echo $this->lang->line('open_qty') ?></label>
                            <?php
                            echo form_input('open_qty', $this->Item->get_field('open_qty'), 'id="open_qty" class="form-control form-control-sm" readonly="true"')
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="cost"><?php echo $this->lang->line('cost_lc') . " CHF" ?></label>
                            <?php
                            echo form_input('cost', $this->Item->get_field('cost'), 'id="cost" class="form-control form-control-sm" readonly="true"')
                            ?>
                            <div id="error_cost" style="text-align:center"
                                 onclick="document.getElementById('error_cost').style.display = 'none'"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" for="purchase_cost"><<?php echo $this->lang->line('purchase_cost') . " €" ?></label>
                            <?php
                            echo form_input('purchase_cost', $this->Item->get_field('purchase_cost'), 'id="purchase_cost" class="form-control form-control-sm"')
                            ?>
                            <div id="error_purchase_cost" style="text-align:center"
                                 onclick="document.getElementById('error_purchase_cost').style.display = 'none'"></div>
                            <div id="warning_purchase_cost" style="text-align:center"
                                 onclick="document.getElementById('warning_purchase_cost').style.display = 'none'"></div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-title">
                            <b>Extra EAN Of Product</b>
                            <button type="button" class="btn btn-sm pull-right" id="add_ean" style="background-color: #444; color: white;">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <hr>
                        <table class="table table-bordered table-condensed table-striped table-hover">
                            <thead style="background-color: #444; color: white;">
                            <tr>
                                <th>EAN</th>
                                <th>Remove</th>
                            </tr>
                            </thead>
                            <tbody id="eanTblBody">
                            <?php if ($eans) {
                                foreach ($eans as $x => $e) { ?>
                                    <tr id="<?php echo 'ean-', $x ?>" data-index="<?php echo $x ?>">
                                        <td style="width: 90%;"><input type="text" name="ean[]" class="form-control form-control-sm input-sm i-ean"
                                                                       value="<?php echo $e['ean'] ?>"></td>
                                        <td style="width: 10%;">
                                            <button type="button" class="btn btn-sm btn-danger i-remove"><i
                                                        class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr id="row_empty">
                                    <td colspan="2">
                                        <center>No Data Found</center>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card-footer border-top border-200 d-flex flex-between-center">
                <div class="d-flex align-items-center">
                    <?php echo form_submit('submitBtn', $this->lang->line('save'), 'class="btn btn-danger me-1 mb-1" id="bgsave" accesskey="s"') ?>
                </div>
            </div>
            <?php
            echo form_close();
            ?>
        </div>
    </div>
</div>