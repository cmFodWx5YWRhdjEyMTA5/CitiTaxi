<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wallet extends CI_Controller {
	function __construct() {
        parent::__construct();
        //$this->load->helper(form,url);
        $this->load->model("AuthModel");        
    }

    public function index()
	{
		$respose["success"] = 0;
		$respose["error"]=400;
		$respose["message"]="Access Denied";
		echo json_encode($respose);
	}

	public function create_walletPin()
	{
		if(isset($_POST['user_id']) && $_POST['user_id']!='' && isset($_POST['pin']) && $_POST['pin']!='')
		{	
			extract($_POST);
			$wallet_pin = md5($pin);
			if($this->AuthModel->updateRecord(array('id'=>$user_id),'users',array('citipay_pin'=>$wallet_pin)))
			{
				$response  = array("error"=>0,"success"=>1,"message"=>"Wallet pin has been successfully created");
				echo json_encode($response);
			}
			else
			{
				$response  = array("error"=>1,"success"=>0,"message"=>"Oops! something went wrong");
				echo json_encode($response);
			}
		}
		else
		{
			$this->index();
		}
	}

	public function recover_walletPin()
	{
		if(isset($_POST['email']) && $_POST['email']!='' && isset($_POST['user_type']) && $_POST['user_type']!='')
		{	
			extract($_POST);
			$res = $this->AuthModel->recover_walletPin('user',$email,$user_type);
			if($res==0)
			{
				$response["success"]        = 1;
				$response['message']		= "Please Check your Email inbox";
				$response['Email ']			= $email;
				echo json_encode($response);
			}
			elseif($res==1)
			{
				$response["error"]          = 2;    
	            $response["success"]        = 0;
				$response['message']		= "Error occur! Please try again";
				echo json_encode($response);		
			}
			else
			{
				$response['error']=1;
	            $response['success']=0;
	            $response['message']="This email Id is not found! Please enter correct email id";
	            echo json_encode($response);
			}
		}		
		else{
			$this->index();
		}
	}

	public function internal_transfer()
	{
		if(isset($_POST['sender_id']) && $_POST['sender_id']!='')
		{
			extract($_POST);
			$param = array('sender_id','receiver_login','amount','pin');
			$validation = $this->AuthModel->checkRequiredParam($param,$_POST);
			if(isset($validation['status']) && $validation['status']=='0')
			{
				$response  = array("error"=>1,"success"=>0,"message"=>$validation['message']);
				echo json_encode($response);
			}
			else
			{				
				if($user_type!=''){
					$where = "(user_type=".$user_type." And (email="."'$receiver_login'"." or mobile="."'$receiver_login'"."))";
					$receiver_id = $this->checkMultiple_user('users',$where);	
				}
				else{
					$where = "(email="."'$receiver_login'"." or mobile="."'$receiver_login'".")";
					$receiver_id = $this->checkMultiple_user('users',$where);	
				}
				$checkCrediantial = $this->AuthModel->checkRows('users',array("id"=>$sender_id,'citipay_pin'=>md5($pin)));
				if($checkCrediantial)
				{

				}
				else
				{
					$response  = array("error"=>1,"success"=>0,"message"=>"invalid pin");
					echo json_encode($response);
				}
			}
		}
		else{
			$this->index();
		}
	}

	function checkMultiple_user($table_name,$where)
	{		
		$checkUser = $this->AuthModel->checkRows($table_name,$where);
		if($checkUser==1){
			$receiver = $this->AuthModel->getSingleRecord($table_name,$where);
			return $receiver->id;
		}
		elseif ($checkUser>1){
			$response  = array("error"=>0,"success"=>2,"message"=>"Please select receiver user type");
			echo json_encode($response);die();
		}
		else{
			$response  = array("error"=>1,"success"=>0,"message"=>"Receiver account is not found");
			echo json_encode($response);die();
		}
	}

	
	    
}
?>

