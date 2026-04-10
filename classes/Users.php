<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json');

require_once('../config.php');
Class Users extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function save_users(){
		if(!isset($_POST['status']) && $this->settings->userdata('login_type') == 1){
			$_POST['status'] = 1;
			$_POST['type'] = 2;
		}
		extract($_POST);
		$oid = $id;
		$data = '';
		if(isset($oldpassword)){
			if(md5($oldpassword) != $this->settings->userdata('password')){
				return 4;
			}
		}
		$chk = $this->conn->query("SELECT * FROM `users` where username ='{$username}' ".($id>0? " and id!= '{$id}' " : ""))->num_rows;
		if($chk > 0){
			return 3;
			exit;
		}
		foreach($_POST as $k => $v){
			if(in_array($k,array('firstname','middlename','lastname','username','college_id','department_id','type'))){
				if(!empty($data)) $data .=" , ";
				$data .= " {$k} = '{$v}' ";
			}
		}
		if(!empty($password)){
			$password = md5($password);
			if(!empty($data)) $data .=" , ";
			$data .= " `password` = '{$password}' ";
		}

		if(empty($id)){
			$qry = $this->conn->query("INSERT INTO users set {$data}");
			if($qry){
				$id = $this->conn->insert_id;
				$this->settings->set_flashdata('success','User Details successfully saved.');
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}

		}else{
			$qry = $this->conn->query("UPDATE users set $data where id = {$id}");
			if($qry){
				$this->settings->set_flashdata('success','User Details successfully updated.');
				if($id == $this->settings->userdata('id')){
					foreach($_POST as $k => $v){
						if($k != 'id'){
							if(!empty($data)) $data .=" , ";
							$this->settings->set_userdata($k,$v);
						}
					}
					
				}
				$resp['status'] = 1;
			}else{
				$resp['status'] = 2;
			}
			
		}
		
		if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = 'uploads/avatar-'.$id.'.png';
			$dir_path =base_app. $fname;
			$upload = $_FILES['img']['tmp_name'];
			$type = mime_content_type($upload);
			$allowed = array('image/png','image/jpeg');
			if(!in_array($type,$allowed)){
				$resp['msg'].=" But Image failed to upload due to invalid file type.";
			}else{
				$new_height = 200; 
				$new_width = 200; 
		
				list($width, $height) = getimagesize($upload);
				$t_image = imagecreatetruecolor($new_width, $new_height);
				imagealphablending( $t_image, false );
				imagesavealpha( $t_image, true );
				$gdImg = ($type == 'image/png')? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
				imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				if($gdImg){
						if(is_file($dir_path))
						unlink($dir_path);
						$uploaded_img = imagepng($t_image,$dir_path);
						imagedestroy($gdImg);
						imagedestroy($t_image);
				}else{
				$resp['msg'].=" But Image failed to upload due to unkown reason.";
				}
			}
			if(isset($uploaded_img)){
				$this->conn->query("UPDATE users set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
				if($id == $this->settings->userdata('id')){
						$this->settings->set_userdata('avatar',$fname);
				}
			}
		}
		if(isset($resp['msg']))
		$this->settings->set_flashdata('success',$resp['msg']);
		return  $resp['status'];
	}
	public function delete_users(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM users where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM users where id = $id");
		if($qry){
			$avatar = explode("?",$avatar)[0];
			$this->settings->set_flashdata('success','User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	private function handle_student_image_upload($id) {
        if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != '') {
            $fname = 'uploads/student-'.$id.'.png';
            $dir_path = base_app . $fname;
            $upload = $_FILES['img']['tmp_name'];
            $type = mime_content_type($upload);
            $allowed = array('image/png','image/jpeg');
            if(!in_array($type, $allowed)) {
                return "Image failed to upload due to invalid file type.";
            } else {
                $new_height = 200; 
                $new_width = 200; 

                list($width, $height) = getimagesize($upload);
                $t_image = imagecreatetruecolor($new_width, $new_height);
                imagealphablending($t_image, false);
                imagesavealpha($t_image, true);
                $gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
                imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                if($gdImg) {
                    if(is_file($dir_path)) unlink($dir_path);
                    $uploaded_img = imagepng($t_image, $dir_path);
                    imagedestroy($gdImg);
                    imagedestroy($t_image);
                    if($uploaded_img) {
                        $this->conn->query("UPDATE student_list set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}'");
                        if($id == $this->settings->userdata('id')){
                            $this->settings->set_userdata('avatar', $fname);
                        }
                    }
                } else {
                    return "Image failed to upload due to unknown reason.";
                }
            }
        }
        return null;
    }
	public function save_student() {
        extract($_POST);
        $data = '';

        // Password verification for existing account
        if (!empty($oldpassword) && !empty($id)) {
            $res = $this->conn->query("SELECT password FROM student_list WHERE id = '{$id}'");
            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                if (md5($oldpassword) !== $row['password']) {
                    echo json_encode(["status" => 'failed', "msg" => 'Old Password is Incorrect']);
                    exit;
                }
            } else {
                echo json_encode(["status" => 'failed', "msg" => 'User not found.']);
                exit;
            }
        }

        // Email check
        $email_check = $this->conn->query("SELECT * FROM `student_list` WHERE email = '{$email}' " . ($id > 0 ? " AND id!= '{$id}' " : ""))->num_rows;
        if ($email_check > 0) {
            echo json_encode(["status" => 'failed', "msg" => 'Email already exists.']);
            exit;
        }

        // Student ID check
        $chk = $this->conn->query("SELECT * FROM `student_list` WHERE studid ='{$studid}' " . ($id > 0 ? " AND id!= '{$id}' " : ""))->num_rows;
        if ($chk > 0) {
            echo json_encode(["status" => 'failed', "msg" => 'Student ID already exists.']);
            exit;
        }

        foreach ($_POST as $k => $v) {
            if (!in_array($k, ['id', 'oldpassword', 'cpassword', 'password'])) {
                if (!empty($data)) $data .= " , ";
                    $data .= " {$k} = '{$v}' ";
            }   
        }

        if (!empty($password)) {
            $password = md5($password);
            if (!empty($data)) $data .= " , ";
                $data .= " `password` = '{$password}' ";
        }

        //if (!empty($data)) $data .= " , ";
            //$data .= " `is_verified` = 1 ";

        if (empty($id)) {
            $qry = $this->conn->query("INSERT INTO student_list SET {$data}");
            if ($qry) {
                $id = $this->conn->insert_id;

                // Generate and save OTP
                $otp = rand(100000, 999999);
                $this->conn->query("INSERT INTO otp_codes (email, otp, created_at) VALUES ('{$email}', '{$otp}', NOW())");

                // PHPMailer send OTP
                require '../PHPMailer/src/PHPMailer.php';
                require '../PHPMailer/src/SMTP.php';
                require '../PHPMailer/src/Exception.php';

                $mail = new PHPMailer\PHPMailer\PHPMailer();
                try {
                    $mail->isSMTP();
                    $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
                    $mail->SMTPAuth = true;
                    $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site');
                    $mail->Password = env('MAIL_PASSWORD', '');
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                    $mail->Port = 465;

                    $mail->setFrom('ntsah.site@ntsah.site', 'NEMSU Archiving Hub');
                    $mail->addAddress($email);

                    $mail->Subject = 'Verify Your Student Account - OTP Code';
                    $mail->isHTML(true);
                    $mail->Body = "
                        <h3>Email Verification OTP</h3>
                        <p>Hi, thank you for registering. Your OTP code is:</p>
                        <h2>{$otp}</h2>
                        <p>This OTP will expire in 5 minutes.</p>
                        <p>If you didn't request this, please ignore the email.</p>
                    ";

                    if (!$mail->send()) {
                        echo json_encode(["status" => "error", "msg" => "Failed to send OTP email."]);
                        exit;
                    }

                    //$this->settings->set_flashdata('success', 'Student registered. Please verify your email.');
                    echo json_encode(["status" => "success", "email" => $email]);
                    exit;

                } catch (Exception $e) {
                    echo json_encode(["status" => "error", "msg" => "Mailer Error: " . $mail->ErrorInfo]);
                    exit;
                }

            } else {
                echo json_encode(["status" => "failed", "msg" => "Error saving student: " . $this->conn->error]);
                exit;
            }

        } else {
            // Update student
            $qry = $this->conn->query("UPDATE student_list SET $data WHERE id = {$id}");
            if ($qry) {
                $this->settings->set_flashdata('success', 'Student Details successfully updated.');
                if ($id == $this->settings->userdata('id')) {
                    foreach ($_POST as $k => $v) {
                        if ($k != 'id') {
                            $this->settings->set_userdata($k, $v);
                        }
                    }
                }
                $this->handle_student_image_upload($id);
                echo json_encode(["status" => "success"]);
            } else {
                echo json_encode([
                    "status" => "failed",
                    "msg" => "Error saving student: " . $this->conn->error,
                    "sql" => $data
                ]);
            }
            exit;
        }

        //return json_encode($resp);
        echo json_encode(["status" => "failed", "msg" => "Unexpected error occurred."]);
        exit;
    }

	public function delete_student(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM student_list where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM student_list where id = $id");
		if($qry){
			$avatar = explode("?",$avatar)[0];
			$this->settings->set_flashdata('success','Student User Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function verify_student() {
    extract($_POST);
    $update = $this->conn->query("UPDATE student_list SET status = 1 WHERE id = '{$id}'");

        if ($update) {
            // Fetch student's email
            $qry = $this->conn->query("SELECT email FROM student_list WHERE id = '{$id}'");
            if ($qry->num_rows > 0) {
                $row = $qry->fetch_assoc();
                $email = $row['email'];

                // Load PHPMailer
                //require 'vendor/autoload.php'; // Ensure PHPMailer is installed
                require '../PHPMailer/src/PHPMailer.php';
                require '../PHPMailer/src/SMTP.php';
                require '../PHPMailer/src/Exception.php';
            
                $mail = new PHPMailer\PHPMailer\PHPMailer();

                try {
                    // SMTP Configuration for Hostinger
                    $mail->isSMTP();
                    $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
                    $mail->SMTPAuth = true;
                    $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site'); // Replace with your Hostinger email
                    $mail->Password = env('MAIL_PASSWORD', ''); // Use your email password
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // SSL
                    $mail->Port = 465;

                    // Email settings
                    $mail->setFrom('ntsah.site@ntsah.site', 'NEMSU Archiving Hub');
                    $mail->addAddress($email); // Recipient

                    $mail->Subject = 'Account Verified';
                    $mail->isHTML(true);
                    $mail->Body = "<h3>Your student account has been successfully verified!</h3>
                               <p>You can now log in and access all features.</p>
                               <p><a href='https://ntsah.site/login.php' style='display:inline-block; padding:10px 20px; background-color:#28a745; color:white; text-decoration:none; border-radius:5px;'>Login Now</a></p>";

                    if ($mail->send()) {
                        return json_encode(['status' => 'success']);
                    } else {
                        return json_encode(['status' => 'error', 'msg' => 'Email not sent']);
                    }
                } catch (Exception $e) {
                    return json_encode(['status' => 'error', 'msg' => 'Mailer Error: ' . $mail->ErrorInfo]);
                }
            }
        }

        return json_encode(['status' => 'error']);
    }
	public function save_adviser(){
	    extract($_POST);
	    $data = '';
	    $resp = [];

	    if(isset($oldpassword)){
		    if(md5($oldpassword) != $this->settings->userdata('password')){
			    return json_encode(array("status"=>'failed', "msg"=>'Old Password is Incorrect'));
		    }
	    }

	    // Check if the email already exists
	    $qry = $this->conn->query("SELECT * FROM `adviser_list` WHERE email = '{$email}' " . ($id > 0 ? " AND id!= '{$id}' " : ""));
	    if ($qry->num_rows > 0) {
		    return json_encode(array("status" => 'failed', "msg" => 'Email already exists.'));
	    }

	    // Check if the adviser ID already exists
	    $adviser_id = isset($_POST['adviser_id']) ? $this->conn->real_escape_string($_POST['adviser_id']) : '';
        $chk = $this->conn->query("SELECT * FROM `adviser_list` WHERE adviser_id = '{$adviser_id}' " . ($id > 0 ? " AND id!= '{$id}' " : ""))->num_rows;
	    //$chk = $this->conn->query("SELECT * FROM `adviser_list` WHERE adviser_id ='{$adviser_id}' " . ($id > 0 ? " AND id!= '{$id}' " : ""))->num_rows;
	    if ($chk > 0) {
		    return json_encode(array("status" => 'failed', "msg" => 'Adviser ID already exists.'));
	    }

	    // Check if both email and adviser ID already exist
	    $email_id = $this->conn->query("SELECT * FROM `adviser_list` WHERE email = '{$email}' and adviser_id = '{$adviser_id}' " . ($id > 0 ? " AND id!= '{$id}' " : ""))->num_rows;
	    if ($email_id > 0) {
		    return json_encode(array("status" => 'failed', "msg" => 'Email and Adviser ID already exist.'));
	    }

	    foreach($_POST as $k => $v){
		    if(!in_array($k, array('id','oldpassword','cpassword','password','type'))){
			    if(!empty($data)) $data .=" , ";
			        $data .= " {$k} = '{$v}' ";
		    }
	    }

	    if(!empty($password)){
		    $password = md5($password);
		    if(!empty($data)) $data .=" , ";
		        $data .= " `password` = '{$password}' ";
	    }

	    if(empty($id)){
		    $qry = $this->conn->query("INSERT INTO adviser_list set {$data}");
		    if($qry){
			    $id = $this->conn->insert_id;
			    $this->settings->set_flashdata('success','Adviser Details successfully saved.');
			    $resp['status'] = "success";
		    }else{
			    return json_encode(array("status" => "failed", "msg" => "An error occurred while saving the data. Error: ". $this->conn->error));
		    }
	    }else{
		    $qry = $this->conn->query("UPDATE adviser_list set $data where id = {$id}");
		    if($qry){
			    $this->settings->set_flashdata('success','Adviser Details successfully updated.');
			    if($id == $this->settings->userdata('id')){
				    foreach($_POST as $k => $v){
					    if($k != 'id'){
						    $this->settings->set_userdata($k,$v);
					    }
				    }
			    }
			    $resp['status'] = "success";
		    }else{
			    return json_encode(array("status" => "failed", "msg" => "An error occurred while saving the data. Error: ". $this->conn->error));
		    }
	    }

	    // Handle image upload
	    if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
		    $fname = 'uploads/adviser-'.$id.'.png';
		    $dir_path = base_app . $fname;
		    $upload = $_FILES['img']['tmp_name'];
		    $type = mime_content_type($upload);
		    $allowed = array('image/png','image/jpeg');

		    if(!in_array($type, $allowed)){
			    $resp['msg'] = ($resp['msg'] ?? '') . " But image failed to upload due to invalid file type.";
		    }else{
			    $new_height = 200; 
			    $new_width = 200; 

			    list($width, $height) = getimagesize($upload);
			    $t_image = imagecreatetruecolor($new_width, $new_height);
			    imagealphablending($t_image, false);
			    imagesavealpha($t_image, true);

			    $gdImg = ($type == 'image/png') ? imagecreatefrompng($upload) : imagecreatefromjpeg($upload);
			    imagecopyresampled($t_image, $gdImg, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

			    if($gdImg){
				    if(is_file($dir_path)) unlink($dir_path);
				    $uploaded_img = imagepng($t_image, $dir_path);
				    imagedestroy($gdImg);
				    imagedestroy($t_image);
			    }else{
				    $resp['msg'] = ($resp['msg'] ?? '') . " But image failed to upload due to unknown reason.";
			    }
		    }

		    if(isset($uploaded_img)){
			    $this->conn->query("UPDATE adviser_list set `avatar` = CONCAT('{$fname}','?v=',unix_timestamp(CURRENT_TIMESTAMP)) where id = '{$id}' ");
			    if($id == $this->settings->userdata('id')){
				    $this->settings->set_userdata('avatar', $fname);
			    }
		    }
	    }

	    if(!isset($resp['status'])){
		    $resp['status'] = 'failed';
		    $resp['msg'] = 'Unknown error occurred.';
	    }

	    return json_encode($resp);
    }
	public function delete_adviser(){
		extract($_POST);
		$avatar = $this->conn->query("SELECT avatar FROM adviser_list where id = '{$id}'")->fetch_array()['avatar'];
		$qry = $this->conn->query("DELETE FROM adviser_list where id = $id");
		if($qry){
			$avatar = explode("?",$avatar)[0];
			$this->settings->set_flashdata('success','Adviser Details successfully deleted.');
			if(is_file(base_app.$avatar))
				unlink(base_app.$avatar);
			$resp['status'] = 'success';
		}else{
			$resp['status'] = 'failed';
		}
		return json_encode($resp);
	}
	public function verify_adviser() {
    extract($_POST);
    $update = $this->conn->query("UPDATE adviser_list SET status = 1 WHERE id = '{$id}'");

        if ($update) {
            // Fetch student's email
            $qry = $this->conn->query("SELECT email FROM adviser_list WHERE id = '{$id}'");
            if ($qry->num_rows > 0) {
                $row = $qry->fetch_assoc();
                $email = $row['email'];

                // Load PHPMailer
                //require 'vendor/autoload.php'; // Ensure PHPMailer is installed
                require '../PHPMailer/src/PHPMailer.php';
                require '../PHPMailer/src/SMTP.php';
                require '../PHPMailer/src/Exception.php';
            
                $mail = new PHPMailer\PHPMailer\PHPMailer();

                try {
                    // SMTP Configuration for Hostinger
                    $mail->isSMTP();
                    $mail->Host = env('MAIL_HOST', 'smtp.hostinger.com');
                    $mail->SMTPAuth = true;
                    $mail->Username = env('MAIL_USERNAME', 'ntsah.site@ntsah.site'); // Replace with your Hostinger email
                    $mail->Password = env('MAIL_PASSWORD', ''); // Use your email password
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; // SSL
                    $mail->Port = 465;

                    // Email settings
                    $mail->setFrom('ntsah.site@ntsah.site', 'NEMSU Archiving Hub');
                    $mail->addAddress($email); // Recipient

                    $mail->Subject = 'Account Verified';
                    $mail->isHTML(true);
                    $mail->Body = "<h3>Your account has been successfully verified!</h3>
                               <p>You can now log in and access all features.</p>
                               <p><a href='https://ntsah.site/login-adviser.php' style='display:inline-block; padding:10px 20px; background-color:#28a745; color:white; text-decoration:none; border-radius:5px;'>Login Now</a></p>";

                    if ($mail->send()) {
                        return json_encode(['status' => 'success']);
                    } else {
                        return json_encode(['status' => 'error', 'msg' => 'Email not sent']);
                    }
                } catch (Exception $e) {
                    return json_encode(['status' => 'error', 'msg' => 'Mailer Error: ' . $mail->ErrorInfo]);
                }
            }
        }

        return json_encode(['status' => 'error']);
    }
	
}

$users = new users();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
switch ($action) {
	case 'save':
		echo $users->save_users();
	break;
	case 'delete':
		echo $users->delete_users();
	break;
	case 'save_student':
		echo $users->save_student();
	break;
	case 'delete_student':
		echo $users->delete_student();
	break;
	case 'verify_student':
		echo $users->verify_student();
	break;
	case 'save_adviser':
		echo $users->save_adviser();
	break;
	case 'delete_adviser':
		echo $users->delete_adviser();
	break;
	case 'verify_adviser':
		echo $users->verify_adviser();
	break;
	default:
		// echo $sysset->index();
		break;
}