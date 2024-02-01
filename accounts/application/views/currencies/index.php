<div class="card mb-3">
    <div class="bg-holder d-none d-lg-block bg-card"
         style="background-image:url(assets/img/icons/spot-illustrations/corner-4.png);">
    </div>
    <!--/.bg-holder-->

    <div class="card-body position-relative">
        <div class="row">
            <div class="col-lg-12">
                <h3>Currencies</h3>
                <p class="mb-0">
                    <?php if ($this->session->flashdata('message')) { ?>
                <div id="delete_ignore" class="alert alert-danger" style="text-align:center" onclick="document.getElementById('delete_ignore').style.display = 'none'">
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
                    <div class="col-md-6 text-start">
                        <h5 class="mb-0" id="account"><p
                                    class="text-right">
                                <?php echo anchor('currencies/add', 'Add Currency', 'accesskey="a" class="btn btn-falcon-default btn-sm me-1 mb-1" id="bgadd"') ?>
                            </p>
                        </h5>
                    </div>
                </div>
                <hr>
                <table class="table mb-0 data-table fs--1" id="currenciesTbl">
                    <thead class="bg-200 text-900">
                    <tr>
                        <th class="sort"><?php echo $this->lang->line('currency_name') ?></th>
                        <th class="sort"><?php echo $this->lang->line('currency_code') ?></th>
                        <th class="sort"><?php echo $this->lang->line('currency_rate') ?></th>
                        <th class="text-center"><?php echo $this->lang->line('actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($records as $record) { ?>
                        <tr>
                            <td><?php echo $record['currency_name'] ?></td>
                            <td><?php echo $record['currency_code'] ?></td>
                            <td><?php echo $record['currency_rate'] ?></td>
                            <td class="text-center">
                                <a href="currencies/edit/<?php echo $record['id'] ?>"
                                   class="btn bt-link btn-sm"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" data-id="<?php echo $record['id'] ?>" data-action="currencies/delete"
                                   class="btn bt-link btn-sm deleteBtn" title="Delete">
                                    <i class="fas fa-trash"></i>
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
    $('#currenciesTbl').on('click', '.deleteBtn', function (e) {
        e.preventDefault();

        // Get the data-id attribute from the clicked delete button
        var id = $(this).data('id');
        var action = $(this).data('action');

        // Call confirmAndExecuteAction function with appropriate parameters
        confirmAndExecuteAction(action + '/' + id, {id: id});
    });

</script>