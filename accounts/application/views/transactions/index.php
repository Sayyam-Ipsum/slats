<style>
    table td {
        word-break: break-word;
        vertical-align: top;
        white-space: normal !important;
    }
</style>
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
                    <?php if ($this->session->flashdata('message')) { ?>
                <div id="ignore" class="alert alert-danger" style="text-align:center"
                     onclick="document.getElementById('ignore').style.display = 'none'">
                    <strong><?php echo $this->session->flashdata('message') ?></strong>
                </div>
                <?php } ?>
                <?php if ($this->session->flashdata('message_success')) { ?>
                    <div id="msg_success" class="alert alert-success" style="text-align:center"
                         onclick="document.getElementById('msg_success').style.display = 'none'">
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
            <div class="card-body">
                <div class="row g-0">
                    <div class="col-md-6 text-start">
                        <?php echo anchor('purchases/add', 'Add Receiving', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgadd"') ?>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered mb-0 fs--1" id="purchasesTbl"
                       data-num-rows="<?php echo $this->Transaction->get('paginationTotalRows') ?>">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort"
                            data-sort="receiving_number">#
                        </th>
                        <th class="sort"
                            data-sort="invoice"><?php echo $this->lang->line('invoice') ?></th>
                        <th class="sort"
                            data-sort="transaction_date"><?php echo $this->lang->line('transaction_date') ?></th>
                        <th class="sort"
                            data-sort="value_date"><?php echo $this->lang->line('value_date') ?></th>
                        <th class="sort"
                            data-sort="supplier_account"><?php echo $this->lang->line('supplier_account') ?></th>
                        <th class="sort"
                            data-sort="purchases_account"><?php echo $this->lang->line('purchases_account') ?></th>
                        <th class="sort"
                            data-sort="currency"><?php echo $this->lang->line('currency') ?></th>
                        <th class="sort"
                            data-sort="currency_rate"><?php echo $this->lang->line('currency_rate') ?></th>
                        <th class="sort" data-sort="count"><?php echo $this->lang->line('count') ?></th>
                        <th class="sort" data-sort="total"><?php echo $this->lang->line('total') ?></th>
                        <th class="sort" data-sort="user"><?php echo $this->lang->line('user') ?></th>
                        <th class="sort" data-sort="done"><?php echo $this->lang->line('done') ?></th>
                        <th class="text-center"
                            data-no-search="0"><?php echo $this->lang->line('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody class="list">
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td class="receiving_number"><?php echo $record['auto_no'] ?></td>
                            <td class="invoice"><?php echo $record['invoice_related_nb'] ?></td>
                            <td class="transaction_date"><?php echo $record['trans_date'] ?></td>
                            <td class="value_date"><?php echo $record['value_date'] ?></td>
                            <td class="supplier_account"><?php echo $record['account1'] ?></td>
                            <td class="purchases_account"><?php echo $record['account2'] ?></td>
                            <td class="currency"><?php echo $record['currency_code'] ?></td>
                            <td class="currency_rate"><?php echo $record['currency_rate'] ?></td>
                            <td class="count"><?php echo $record['count'] ?></td>
                            <td class="total"><?php echo $record['total'] ?></td>
                            <td class="user"><?php echo $record['user_name'] ?></td>
                            <td class="done text-center"><?php echo $record['status'] ?></td>
                            <td class="id"><?php echo $record['id'] ?></td>
                            <!-- <td class="text-center">
                                <a href="purchases/edit/<?php /*echo $record['id'] */ ?>"
                                   class="btn bt-link btn-sm"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" data-id="<?php /*echo $record['id'] */ ?>" data-action="purchases/delete"
                                   class="btn bt-link btn-sm deleteBtn" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="purchases/check/<?php /*echo $record['id'] */ ?>"
                                   class="btn bt-link btn-sm"
                                   title="check">
                                    <i class="fas fa-check-circle"></i>
                                </a>
                            </td>-->
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    // Bind click event to the delete button
    $('#purchasesTbl').on('click', '.deleteBtn', function (e) {
        e.preventDefault();

        // Get the data-id attribute from the clicked delete button
        var id = $(this).data('id');
        var action = $(this).data('action');

        // Call confirmAndExecuteAction function with appropriate parameters
        confirmAndExecuteAction(action + '/' + id, {id: id});
    });

</script>