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

                </p>
            </div>
        </div>
    </div>
</div>
<div class="row g-0">
    <div class="col-lg-12 pe-lg-2">
        <div class="card mb-3">

            <div class="card-body">
                <div class="row g-0">
                    <div class="col-md-6 text-end">
                        <h5 class="mb-0" id="account"><p
                                    class="text-start">
                                <?php echo anchor('transfers/add', 'Add Transfer', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgadd" ') ?>

                        </h5>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered mb-0 fs--1" id="transfersTbl" <table class="table table-bordered table-striped table-hover table-condensed table-responsive" id="transfersTbl" data-num-rows="<?php echo $this->Transaction->get('paginationTotalRows') ?>">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th><?php echo $this->lang->line('auto_number') ?></th>
                        <th><?php echo $this->lang->line('transaction_date') ?></th>
                        <th class="text-center" data-no-search="0"><?php echo $this->lang->line('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody class="list">
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td><?php echo $record['auto_no'] ?></td>
                            <td><?php echo $record['trans_date'] ?></td>
                            <td class="text-center">
                                <?php echo $record['id'] ?>
                              <!--  <a href="transfers/view/<?php /*echo $record['id'] */?>" class="btn bt-link btn-sm"
                                   title="Edit">
                                    <i class="fas fa-info"></i>
                                </a>-->
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>
