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
					$receiver_id = $this->checkMultiple_user('users',$where);	//to check valid receiver
				}
				else{
					$where = "(email="."'$receiver_login'"." or mobile="."'$receiver_login'".")";
					$receiver_id = $this->checkMultiple_user('users',$where);	//to check valid receiver
				}
				$checkCrediantial = $this->AuthModel->checkRows('users',array("id"=>$sender_id,'citipay_pin'=>md5($pin)));
				if($checkCrediantial)
				{
					$sender_wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$sender_id));
					$receiver_wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$receiver_id));
					if(!empty($sender_wallet))
					{
						$sender_balance = $sender_wallet->balance;
						$receiver_balance = $receiver_wallet->balance;
						if($sender_balance>$amount)
						{
							$sender_newBalance = $sender_balance-$amount;
							$receiver_newBalance = $receiver_balance+$amount;
							$this->AuthModel->updateRecord(array('user_id'=>$sender_id),'wallet_balance',array('balance'=>$sender_newBalance,'update_at'=>date('Y-m-d H:i:s'))); //update sender balance
							$this->AuthModel->updateRecord(array('user_id'=>$receiver_id),'wallet_balance',array('balance'=>$receiver_newBalance,'update_at'=>date('Y-m-d H:i:s')));//update receiver balance					
							if($transaction_id = $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$receiver_id,'sender_id'=>$sender_id,'type'=>'cr','amount'=>$amount,'description'=>'internal transfer','transaction_status'=>'Success','reciver_balance'=>$receiver_newBalance,'sender_balance'=>$sender_newBalance,'transaction_at'=>date('Y-m-d H:i:s'))))              //store transaction record
							{
								$response  = array("error"=>0,"success"=>1,"message"=>"CityPay ".$amount. " has been transfer to ".$receiver_login);
								echo json_encode($response);
							} 
							else{  //when transaction failed
								$this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$receiver_id,'sender_id'=>$sender_id,'type'=>'','amount'=>$amount,'description'=>'Transaction is unsuccessful due to technical issue','transaction_status'=>'Failure','reciver_balance'=>$receiver_balance,'sender_balance'=>$sender_balance,'transaction_at'=>date('Y-m-d H:i:s')));

								$this->AuthModel->updateRecord(array('user_id'=>$sender_id),'wallet_balance',array('balance'=>$sender_balance,'update_at'=>date('Y-m-d H:i:s'))); //Re-update sender balance

								$this->AuthModel->updateRecord(array('user_id'=>$receiver_id),'wallet_balance',array('balance'=>$receiver_balance,'update_at'=>date('Y-m-d H:i:s')));//Re-update receiver balance	

								$response  = array("error"=>0,"success"=>1,"message"=>"Sorry! Transaction is unsuccessful. Please try again after few minutes");
								echo json_encode($response);
							}
						}
						else
						{
							$this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$receiver_id,'sender_id'=>$sender_id,'type'=>'','amount'=>$amount,'description'=>'Insufficient balance','transaction_status'=>'Failure','reciver_balance'=>$receiver_balance,'sender_balance'=>$sender_balance,'transaction_at'=>date('Y-m-d H:i:s')));
							$response  = array("error"=>1,"success"=>0,"message"=>"Transaction error. Check your wallet balance and try again");
							echo json_encode($response);
						}
					}
					else{
						$response  = array("error"=>1,"success"=>0,"message"=>"Oops! Something went wrong");
						echo json_encode($response);
					}
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

	public function my_wallet()
	{
		if(isset($_POST['user_id']) && $_POST['user_id']!='')
		{
			extract($_POST);
			$wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$user_id));
			if(!empty($wallet))
			{
				$wallet_balance = $wallet->balance;
				$where = '(sender_id='.$user_id.' or receiver_id='.$user_id.')';
				$transactions = $this->AuthModel->getMultipleRecord('wallet_transaction',$where,"");
				if(!empty($transactions))
				{
					foreach ($transactions as $key => $t) {
						if($t->sender_id==$user_id && $t->type!='')
						{
							$transactions[$key]->type ='dr';							
						}
						if($t->sender_id==$user_id)
						{							
							$transactions[$key]->remain_balance = $t->sender_balance;
							unset($t->reciver_balance);
							unset($t->sender_balance);
						}
						if($t->receiver_id==$user_id)
						{							
							$transactions[$key]->remain_balance = $t->reciver_balance;
							unset($t->reciver_balance);
							unset($t->sender_balance);
						}
					}
					$response  = array("error"=>0,"success"=>1,"message"=>"success","wallet_balance"=>$wallet_balance,"transaction_data"=>$transactions);
					echo json_encode($response);
				}
				else
				{
					$response  = array("error"=>0,"success"=>2,"message"=>"No transactions","wallet_balance"=>$wallet_balance,"transaction_data"=>$transactions);
					echo json_encode($response);
				}
			}
			else
			{
				$response  = array("error"=>1,"success"=>0,"message"=>"Wallet record does not exist");
				echo json_encode($response);
			}
		}
		else
		{
			$this->index();
		}
	}

	

	
	    
}
?>

