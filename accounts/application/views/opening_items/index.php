<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <h3>Opening Items</h3>
                <p class="mb-0">
                    <?php if ($this->session->flashdata('message')) { ?>
                <div id="delete_ignore" class="alert alert-danger" style="text-align:center"
                     onclick="document.getElementById('delete_ignore').style.display = 'none'">
                    <strong><?php echo $this->session->flashdata('message') ?></strong>
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
                    <div class="col-md-6 text-end">
                        <h5 class="mb-0" id="account"><p
                                    class="text-start">
                                <?php echo anchor('opening_items/add', 'Add Opening', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgadd"') ?>
                        </h5>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered mb-0 fs--1" id="purchasesTbl" data-num-rows="<?php echo $this->Transaction->get('paginationTotalRows') ?>">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th><?php echo $this->lang->line('auto_number') ?></th>
                        <th><?php echo $this->lang->line('transaction_date') ?></th>
                        <th class="text-center" data-no-search="0"><?php echo $this->lang->line('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td><?php echo $record['auto_no'] ?></td>
                            <td><?php echo $record['trans_date'] ?></td>
                            <td class="text-center">
                                <?php echo $record['id'] ?>
                                <!--<a href="opening_items/edit/<?php /*echo $record['id'] */?>" class="btn bt-link btn-sm"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" data-id="<?php /*echo $record['id'] */?>" data-action="opening_items/delete"
                                   class="btn bt-link btn-sm deleteBtn" title="Delete">
                                    <i class="fas fa-trash"></i>
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
