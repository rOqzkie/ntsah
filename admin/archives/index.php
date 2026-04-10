<?php
    if (session_status() === PHP_SESSION_NONE) session_start();
?>
<style>
    .img-avatar{
        width:45px;
        height:45px;
        object-fit:cover;
        object-position:center center;
        border-radius:100%;
    }
</style>
<div class="card card-outline">
	<div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-archive me-2"></i>List of Archive(s)</h5>
    </div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
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
					<tr>
						<th>#</th>
						<th>Date Uploaded</th>
						<th>Archive Code</th>
						<th>Title of Study</th>
						<th>Department</th>
						<th>Program</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
                    	$department_id = isset($_SESSION['department_id']) ? intval($_SESSION['department_id']) : 0;
						$department = $conn->query("SELECT * FROM department_list where id in (SELECT department_id from `curriculum_list` where id in (SELECT curriculum_id from `archive_list`))");
						$dept_arr = array_column($department->fetch_all(MYSQLI_ASSOC),'name','id');
						$curriculum = $conn->query("SELECT * FROM curriculum_list where id in (SELECT curriculum_id from `archive_list`)");
						$cur_arr = array_column($curriculum->fetch_all(MYSQLI_ASSOC),'name','id');
						//$qry = $conn->query("SELECT * from `archive_list` order by `year` desc, `title` desc ");
						if ($user_type == 1) {
		                    $qry = $conn->query("SELECT al.*, cl.department_id FROM `archive_list` al LEFT JOIN `curriculum_list` cl ON al.curriculum_id = cl.id ORDER BY al.`title` ASC");
	                    } else if ($user_type == 2) {
		                    $qry = $conn->query("SELECT al.*, cl.department_id FROM `archive_list` al LEFT JOIN `curriculum_list` cl ON al.curriculum_id = cl.id WHERE cl.department_id = $department_id ORDER BY al.`status` ASC");
	                    }
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class=""><?php echo date("Y-m-d H:i",strtotime($row['date_created'])) ?></td>
							<td><?php echo ($row['archive_code']) ?></td>
							<td><?php echo ucwords($row['title']) ?></td>
							<td><?php echo $dept_arr[$row['department_id']] ?></td>
							<td><?php echo $cur_arr[$row['curriculum_id']] ?></td>
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
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item" href="<?= base_url ?>/?page=view_archive&id=<?php echo $row['id'] ?>" target="_blank"><span class="fa fa-external-link-alt text-gray"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <?php if($_settings->userdata('type') == 2): ?>
				                    <a class="dropdown-item update_status" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-status="<?php echo $row['status'] ?>"><span class="fa fa-check text-dark"></span> Update Status</a>
									<div class="dropdown-divider"></div>
									<?php endif; ?>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
<script>
	$(document).ready(function(){
		$('.verified').click(function(){
			_conf("Are you sure to verify this student|author Request?","verified",[$(this).attr('data-id')])
		})
		$('.delete_data').click(function(){
			_conf("Are you sure to delete this Thesis|FS permanently?","delete_archive",[$(this).attr('data-id')])
		})
		$('.update_status').click(function(){
            uni_modal("Update Details","archives/update_status.php?id="+$(this).attr('data-id')+"&status="+$(this).attr('data-status'))
        })
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.table').dataTable({
		    columnDefs: [
			    { orderable: false, targets: 5 }
		    ],
		    language: {
			    searchPlaceholder: "Search archives...",
			    search: ""
		    }
	    });
	})
	function delete_archive($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Master.php?f=delete_archive",
			method:"POST",
			data:{id: $id},
			dataType:"json",
			error:err=>{
				console.log(err)
				alert_toast("An error occured.",'error');
				end_loader();
			},
			success:function(resp){
				if(typeof resp== 'object' && resp.status == 'success'){
					location.reload();
				}else{
					alert_toast("An error occured.",'error');
					end_loader();
				}
			}
		})
	}
</script>