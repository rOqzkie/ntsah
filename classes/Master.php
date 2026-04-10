<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_college(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `college_list` set {$data} ";
		}else{
			$sql = "UPDATE `college_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `college_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "College Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "College details successfully added.";
				else
					$resp['msg'] = "College details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_college(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `college_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"College has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_discipline(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `discipline_list` set {$data} ";
		}else{
			$sql = "UPDATE `discipline_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `discipline_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Research Discipline Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Research Discipline details successfully added.";
				else
					$resp['msg'] = "Research Discipline details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_discipline(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `discipline_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Research Discipline has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_position(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `position_list` set {$data} ";
		}else{
			$sql = "UPDATE `position_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `position_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Position Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Position details successfully added.";
				else
					$resp['msg'] = "Position details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_position(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `position_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Position has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_department(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `department_list` set {$data} ";
		}else{
			$sql = "UPDATE `department_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `department_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Department Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Department details successfully added.";
				else
					$resp['msg'] = "Department details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_department(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `department_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Department has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_program(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `curriculum_list` set {$data} ";
		}else{
			$sql = "UPDATE `curriculum_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `curriculum_list` where `name`='{$name}' and `department_id` = '{department_id}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Program Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Program details successfully added.";
				else
					$resp['msg'] = "Program details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_program(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `curriculum_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Program has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_archive(){
		if(empty($_POST['id'])){
			$pref= date("Ym");
			$code = sprintf("%'.04d",1);
			while(true){
				$check = $this->conn->query("SELECT * FROM `archive_list` where archive_code = '{$pref}{$code}'")->num_rows;
				if($check > 0){
					$code = sprintf("%'.04d",abs($code)+1);
				}else{
					break;
				}
			}
			$_POST['archive_code'] = $pref.$code;
			$_POST['adviser_id'] = $this->settings->userdata('id');
			$_POST['college_id'] = $this->settings->userdata('college_id');
			$_POST['department_id'] = $this->settings->userdata('department_id');
			$_POST['curriculum_id'] = $this->settings->userdata('curriculum_id');
		}
		if(isset($_POST['abstract']))
		$_POST['abstract'] = htmlentities($_POST['abstract']);
		if(isset($_POST['authors']))
		$_POST['authors'] = htmlentities($_POST['authors']);
		if(isset($_POST['keywords']))
		$_POST['keywords'] = htmlentities($_POST['keywords']);
		extract($_POST);
		$data = "";
		if(isset($_FILES['pdf']) && !empty($_FILES['pdf']['tmp_name'])){
			$type = mime_content_type($_FILES['pdf']['tmp_name']);
			if($type != "application/pdf"){
				$resp['status'] = "failed";
				$resp['msg'] = "Invalid Document File Type.";
				return json_encode($resp);
			} 
		}
        
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id')) && !is_array($_POST[$k])){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `archive_list` set {$data} ";
		}else{
			$sql = "UPDATE `archive_list` set {$data} where id = '{$id}' ";
		}
		//$check_title = $this->conn->query("SELECT * FROM `archive_list` where `title`='{$title}' and `abstract`='{$abstract}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		$check_title = $this->conn->query("SELECT * FROM `archive_list` where `title`='{$title}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check_title > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Title Already Exists.";
		}
		else{
			$save = $this->conn->query($sql);
			if($save){
				$aid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				$resp['id'] = $aid;
				if(empty($id))
					$resp['msg'] = "Archive was successfully submitted";
				else
					$resp['msg'] = "Archive details was updated successfully.";
					if(isset($_FILES['pdf']) && $_FILES['pdf']['tmp_name'] != ''){
						$fname = 'uploads/pdf/archive-'.$aid.'.pdf';
						$dir_path =base_app. $fname;
						$upload = $_FILES['pdf']['tmp_name'];
						$type = mime_content_type($upload);
						$allowed = array('application/pdf');
						if(!in_array($type,$allowed)){
							$resp['msg'].=" But Document File has failed to upload due to invalid file type.";
						}else{
							$uploaded = move_uploaded_file($_FILES['pdf']['tmp_name'],$dir_path);
						}
						if(isset($uploaded)){
							$this->conn->query("UPDATE archive_list set `document_path` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$aid}' ");
						}
					}
				
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_archive(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `archive_list` where id = '{$id}'");
		$del = $this->conn->query("DELETE FROM `archive_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"archive Records has deleted successfully.");
			if($get->num_rows > 0){
				$res = $get->fetch_array();
				$document_path = explode("?",$res['document_path'])[0];
				if(is_file(base_app.$document_path))
					unlink(base_app.$document_path);
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}
	function update_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `archive_list` set status  = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = "Archive status has successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred. Error: " .$this->conn->error;
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_college':
		echo $Master->save_college();
	break;
	case 'delete_college':
		echo $Master->delete_college();
	break;
	case 'save_discipline':
		echo $Master->save_discipline();
	break;
	case 'delete_discipline':
		echo $Master->delete_discipline();
	break;
	case 'save_position':
		echo $Master->save_position();
	break;
	case 'delete_position':
		echo $Master->delete_position();
	break;
	case 'save_department':
		echo $Master->save_department();
	break;
	case 'delete_department':
		echo $Master->delete_department();
	break;
	case 'save_program':
		echo $Master->save_program();
	break;
	case 'delete_program':
		echo $Master->delete_program();
	break;
	case 'save_archive':
		echo $Master->save_archive();
	break;
	case 'delete_archive':
		echo $Master->delete_archive();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	case 'save_payment':
		echo $Master->save_payment();
	break;
	case 'delete_payment':
		echo $Master->delete_payment();
	break;
	default:
		// echo $sysset->index();
		break;
}