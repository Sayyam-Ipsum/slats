<!-- <div class="col-sm-12">
	<div class="col-sm-2">
	<p class="text-right"><?php // echo anchor('accounts/index', 'All', 'accesskey="a" class="btn btn-primary" id="bglist" ') ?></p>
	</div>
	<div class="col-sm-2">
	<p class="text-right"><?php // echo anchor('accounts/filter/Supplier', 'Suppliers', 'accesskey="a" class="btn btn-primary" id="supplier" ') ?></p>
	</div>
	<div class="col-sm-2">
	<p class="text-right"><?php // echo anchor('accounts/filter/Customer', 'Customers', 'accesskey="a" class="btn btn-primary" id="customer" ') ?></p>
	</div>
</div> -->

<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <h3><?php echo $title ?></h3>
                <p class="mb-0"><?php echo $this->lang->line('accounts_list') ?></p>
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
                    <div class="col-md-6 text-start">
                        <h5 class="mb-0" id="account"><p
                                    class="text-right"><?php echo anchor('accounts/add', 'Add Account', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgadd" ') ?></p>
                        </h5>
                    </div>
                </div>
                <hr>
                <table class="table mb-0 data-table fs--1" id="accountsTbl">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort"><?php echo $this->lang->line('account_number') ?></th>
                        <th class="sort"><?php echo $this->lang->line('account_name') ?></th>
                        <th class="sort"><?php echo $this->lang->line('account_type') ?></th>
                        <th class="sort"><?php echo $this->lang->line('currency') ?></th>
                        <th class="sort"><?php echo $this->lang->line('phone') ?></th>
                        <th class="sort"><?php echo $this->lang->line('debit') ?></th>
                        <th class="sort"><?php echo $this->lang->line('credit') ?></th>
                        <th class="sort"><?php echo $this->lang->line('balance') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td><?php echo $record['account_number'] ?></td>
                            <td><?php echo $record['account_name'] ?></td>
                            <td><?php echo $record['account_type'] ?></td>
                            <td><?php echo $record['currency'] ?></td>
                            <td><?php echo $record['phone'] ?></td>
                            <td><?php echo $record['debit'] ?></td>
                            <td><?php echo $record['credit'] ?></td>
                            <td><?php echo $record['balance'] ?></td>
                            <td class="text-center">
                                <a href="accounts/edit/<?php echo $record['id'] ?>"
                                   class="btn bt-link btn-sm"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" data-id="<?php echo $record['id'] ?>" data-action="accounts/delete"
                                   class="btn bt-link btn-sm deleteBtn" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="accounts/activity/<?php echo $record['id'] ?>"
                                   class="btn bt-link btn-sm" title="Activity">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
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
    $('#accountsTbl').on('click', '.deleteBtn', function (e) {
        e.preventDefault();

        // Get the data-id attribute from the clicked delete button
        var id = $(this).data('id');
        var action = $(this).data('action');

        // Call confirmAndExecuteAction function with appropriate parameters
        confirmAndExecuteAction(action + '/' + id, {id: id});
    });

</script>