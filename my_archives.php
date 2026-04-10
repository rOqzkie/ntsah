<style>
    .content.card {
        width: 100%;
        max-width: none;
    }

    .container {
        width: 100%;
        max-width: none;
        padding: 0 20px;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .badge-pill {
        padding: 0.5em 0.75em;
        font-size: 0.8rem;
    }

    /* Ensure full width on smaller screens */
@media (max-width: 768px) {
    .btn-sm, .dropdown-toggle, .dropdown-menu {
        width: 100%;
    }

    .dataTables_length,
    .dataTables_info {
        text-align: left !important;
        float: none !important;
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .dataTables_filter,
    .dataTables_paginate {
        text-align: center !important;
        float: none !important;
        width: 100%;
    }

    .dataTables_wrapper .row {
        display: flex;
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
<!--
<div class="container mt-3">
    <a href="./" class="btn btn-secondary btn-sm mb-2">← Back to Home</a>
    <a href="./?page=profile-adviser" class="btn btn-secondary btn-sm mb-2">← Back to Profile</a>
</div>
-->
<div class="content py-3">
    <div class="container-fluid">
        <div class="card card-outline shadow rounded-0">
            <div class="card-header bg-primary d-flex justify-content-between flex-wrap align-items-center rounded-0">
                <h4 class="card-title text-white mb-2 mb-sm-0"><b>My Archive(s)</b></h4>
            </div>
            <div class="card-body rounded-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <colgroup>
                            <col width="5%">
                            <col width="15%">
                            <col width="15%">
                            <col width="20%">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr class="text-dark">
                                <th>#</th>
                                <th>Date Uploaded</th>
                                <th>Archive Code</th>
                                <th>Title of Study</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                $i = 1;
                                $curriculum = $conn->query("SELECT * FROM curriculum_list where id in (SELECT curriculum_id from `archive_list` where adviser_id = '{$_settings->userdata('id')}' )");
                                $cur_arr = array_column($curriculum->fetch_all(MYSQLI_ASSOC),'name','id');
                                $qry = $conn->query("SELECT * from `archive_list` where adviser_id = '{$_settings->userdata('id')}' order by unix_timestamp(`date_created`) asc ");
                                while($row = $qry->fetch_assoc()):
                            ?>
                                <tr>
                                    <td class="text-center text-dark"><?php echo $i++; ?></td>
                                    <td class="text-center text-dark"><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
                                    <td class="text-center text-dark"><?php echo ($row['archive_code']) ?></td>
                                    <td class="text-left text-dark"><?php echo ucwords($row['title']) ?></td>
                                    <td class="text-center text-dark"><?php echo $cur_arr[$row['curriculum_id']] ?></td>
                                    <td class="text-center">
                                        <?php
                                            switch($row['status']){
                                                case '1':
                                                    echo "<span class='badge badge-success badge-pill'>Archived</span>";
                                                    break;
                                                case '0':
                                                    echo "<span class='badge badge-secondary badge-pill'>For Archiving Approval</span>";
                                                    break;
                                            }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                Action
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="<?= base_url ?>/?page=view_archive&id=<?= $row['id'] ?>" target="_blank"><i class="fa fa-external-link-alt text-gray"></i> View</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?= $row['id'] ?>"><i class="fa fa-trash text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script>
    $(function(){
        $('.delete_data').click(function(){
            _conf("Are you sure to delete this archive permanently?", "delete_archive", [$(this).attr('data-id')])
        });

        $('.table td, .table th').addClass('py-1 px-2 align-middle');

        $('.table').DataTable({
            columnDefs: [
                { orderable: false, targets: 6 }
            ],
            responsive: true,
            language: {
                searchPlaceholder: "Search archive...",
                search: "", // removes default label
                lengthMenu: '<span class="text-dark">Show _MENU_ entries</span>',
                info: '<span class="text-dark">Showing _START_ to _END_ of _TOTAL_ entries</span>'
            },
            initComplete: function () {
                // Align length control to the left
                $('div.dataTables_length').addClass('text-left');
                $('div.dataTables_info').addClass('text-left');
            }
        });
    });

    function delete_archive(id){
        start_loader();
        $.ajax({
            url: _base_url_ + "classes/Master.php?f=delete_archive",
            method: "POST",
            data: { id: id },
            dataType: "json",
            error: err => {
                console.log(err);
                alert_toast("An error occurred.", 'error');
                end_loader();
            },
            success: function(resp){
                if (typeof resp === 'object' && resp.status === 'success') {
                    location.reload();
                } else {
                    alert_toast("An error occurred.", 'error');
                    end_loader();
                }
            }
        });
    }
</script>