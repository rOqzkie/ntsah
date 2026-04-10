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
        <h5 class="mb-0"><i class="fas fa-user-friends me-2"></i>List of Adviser(s)</h5>
    </div>
	<div class="card-body">
		<div class="container-fluid">
        <div class="container-fluid">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th>#</th>
						<th>Avatar</th>
						<th>Name</th>
						<th>Email</th>
						<th>Department</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						$i = 1;
						$user_type = $_SESSION['user_type']; // Assuming user_type is stored in session
                    	$department_id = $_SESSION['department_id']; // Assuming department_id is stored in session
						$department = $conn->query("SELECT * FROM department_list where id in (SELECT department_id from `adviser_list`)");
						$dept_arr = array_column($department->fetch_all(MYSQLI_ASSOC),'name','id');
						//$qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ', middlename) as name from `adviser_list`  order by concat(lastname,', ',firstname,' ', middlename) asc ");
						if ($user_type == 1) {
		                    $qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ', middlename) as name FROM `adviser_list` ORDER BY concat(lastname,', ',firstname,' ', middlename) asc");
	                    } else if ($user_type == 2) {
		                    $qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ', middlename) as name FROM `adviser_list` WHERE department_id = '$department_id' ORDER BY status, concat(lastname,', ',firstname,' ', middlename) asc");
	                    }
						while($row = $qry->fetch_assoc()):
					?>
						<tr>
							<td class="text-center"><?php echo $i++; ?></td>
							<td class="text-center"><img src="<?php echo validate_image($row['avatar']) ?>" class="img-avatar img-thumbnail p-0 border-2" alt="user_avatar"></td>
							<td><?php echo ucwords($row['name']) ?></td>
							<td ><p class="m-0 truncate-1"><?php echo $row['email'] ?></p></td>
							<td><?php echo $dept_arr[$row['department_id']] ?></td>
							<td class="text-center">
								<?php if($row['status'] == 1): ?>
									<span class="badge badge-pill badge-success">Verified</span>
								<?php else: ?>
								<span class="badge badge-pill badge-primary">For Verification</span>
								<?php endif; ?>
							</td>
							<td align="center">
								 <button type="button" class="btn btn-flat btn-default btn-sm dropdown-toggle dropdown-icon" data-toggle="dropdown">
				                  		Action
				                    <span class="sr-only">Toggle Dropdown</span>
				                  </button>
				                  <div class="dropdown-menu" role="menu">
				                    <a class="dropdown-item view_details" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><span class="fa fa-eye text-dark"></span> View</a>
				                    <div class="dropdown-divider"></div>
				                    <?php if($_settings->userdata('type') == 2): ?>
									<?php if($row['status'] != 1): ?>
				                    <a class="dropdown-item verify_user" href="javascript:void(0)" data-id="<?= $row['id'] ?>"  data-name="<?= $row['email'] ?>"><span class="fa fa-check text-primary"></span> Verify</a>
				                    <div class="dropdown-divider"></div>
									<?php endif; ?>
									<?php endif; ?>
				                    <a class="dropdown-item delete_data" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"  data-name="<?= $row['email'] ?>"><span class="fa fa-trash text-danger"></span> Delete</a>
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
		$('.delete_data').click(function(){
			_conf("Are you sure to delete <b>"+$(this).attr('data-name')+"</b> from Adviser List permanently?","delete_user",[$(this).attr('data-id')])
		})
		$('.table td,.table th').addClass('py-1 px-2 align-middle')
		$('.verify_user').click(function(){
			_conf("Are you sure to verify <b>"+$(this).attr('data-name')+"<b/>?","verify_user",[$(this).attr('data-id')])
		})
		$('.view_details').click(function(){
			uni_modal('Adviser Details',"adviser/view_details.php?id="+$(this).attr('data-id'),'mid-large')
		})
		$('.table').dataTable({
		    language: {
			    searchPlaceholder: "Search adviser...",
			    search: ""
		    }
	    });
	})
	function delete_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=delete_adviser",
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
	function verify_user($id){
		start_loader();
		$.ajax({
			url:_base_url_+"classes/Users.php?f=verify_adviser",
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