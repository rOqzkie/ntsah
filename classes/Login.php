<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_errors', 0);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	/*
	public function login(){
		extract($_POST);

		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password')");
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] != 1){
				return json_encode(array('status'=>'notverified'));
			}
			foreach($res as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}
			}
			$this->settings->set_userdata('login_type',1);
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}
	*/
	public function login(){
        extract($_POST);
    
        // Query to check user credentials
        $qry = $this->conn->query("SELECT * FROM users WHERE username = '$username' AND password = md5('$password')");
    
        if($qry->num_rows > 0){
            $res = $qry->fetch_array();
        
            // Check if the user is verified
            if($res['status'] != 1){
                return json_encode(array('status' => 'notverified'));
            }

            // Store user data in the session
            $_SESSION['user_id'] = $res['id'];
            $_SESSION['user_type'] = $res['type']; // 1 = See all archives, 2 = Restricted by department
            $_SESSION['department_id'] = $res['department_id'];

            // Store user data using the `set_userdata` method
            foreach($res as $k => $v){
                if(!is_numeric($k) && $k != 'password'){
                    $this->settings->set_userdata($k, $v);
                }
            }

            // Set login type in session
            $_SESSION['login_type'] = 1; 
            $this->settings->set_userdata('login_type', 1);

            return json_encode(array('status' => 'success'));
        } else {
            return json_encode(array(
                'status' => 'incorrect',
                'last_qry' => "SELECT * FROM users WHERE username = '$username' AND password = md5('$password')"
            ));
        }
    }
    /*
	public function logout(){
		if($this->settings->sess_des()){
			redirect('admin/login.php');
		}
	}
	*/
	public function logout(){
        $_SESSION = array(); // Clear all session variables
        session_unset(); // Unset all session variables
        session_destroy(); // Destroy the session

        redirect('admin/login.php'); // Redirect to login page
    }
	function student_login(){
		extract($_POST);
		$qry = $this->conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from student_list where email = '$email' and `password` = md5('$password') ");
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
		}else{
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			$_SESSION['user_id'] = $res['id'];
			if($res['status'] == 1 ){
				foreach($res as $k => $v){
					$this->settings->set_userdata($k,$v);
				}
				$this->settings->set_userdata('login_type',2);
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "Wait for Admin Verification.";
			}
			
		}else{
		$resp['status'] = 'failed';
		$resp['msg'] = "Invalid email or password.";
		}
		}
		return json_encode($resp);
	}
	function adviser_login(){
		extract($_POST);
		$qry = $this->conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from adviser_list where email = '$email' and `password` = md5('$password') ");
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
		}else{
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			$_SESSION['user_id'] = $res['id'];
			if($res['status'] == 1){
				foreach($res as $k => $v){
					$this->settings->set_userdata($k,$v);
				}
				$this->settings->set_userdata('login_type',3);
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "Your Account is not verified yet.";
			}
			
		}else{
		$resp['status'] = 'failed';
		$resp['msg'] = "Invalid email or password.";
		}
		}
		return json_encode($resp);
	}
	/*
	public function student_logout(){
		if($this->settings->sess_des()){
			redirect('./login.php');
		}
	}
	*/
	public function student_logout(){
        $_SESSION = array();
        session_unset();
        session_destroy();
    
        redirect('./login.php');
    }
    /*
	public function adviser_logout(){
		if($this->settings->sess_des()){
			redirect('./login-adviser.php');
		}
	}
	*/
	public function adviser_logout(){
        $_SESSION = array();
        session_unset();
        session_destroy();
    
        redirect('./login-adviser.php');
    }
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'student_login':
		echo $auth->student_login();
		break;
	case 'student_logout':
		echo $auth->student_logout();
		break;
	case 'adviser_login':
		echo $auth->adviser_login();
		break;
	case 'adviser_logout':
		echo $auth->adviser_logout();
		break;
	default:
		echo $auth->index();
		break;
}