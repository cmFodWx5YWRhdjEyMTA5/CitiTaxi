<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {
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

	public function signup()             //signup
	{
		$respose = array("success"=>0,"error"=>0,"message"=>'');
		$table_name = 'users';
		if(isset($_POST['email']) && $_POST['email']!='' && isset($_POST['mobile']) && $_POST['mobile']!='')
		{
			extract($_POST);
			$checkmail   = array("email"=>$email,"user_type"=>0);
			$checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);		
			$checkMobile = array('mobile' =>$mobile,"user_type"=>0);
			$mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);	
			if($checkEmail>0)
			{
				$respose["error"]=1;
				$respose["message"]="Email already Exist";
				echo json_encode($respose);
			}			
			elseif($mobileExist>0)
			{	
				$respose= array("error"=>1,"message"=>"Mobile number has already registered");
				echo json_encode($respose);
			}
			else
			{
				$imagename ='default.jpg';
				if(isset($_FILES['image']))
				{
					$folder_name = 'userimage';
					$imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
				}
				if($image_type==1 && $media_image!='')          //when media image isset
			    {
			    	$imagename =  $media_image;
			    }
				$data= array(
					"ref_code"      =>$this->AuthModel->radomno(6),
					"fb_id"         =>$fb_id,
					"google_id"     =>$google_id,
					"device_token"  =>$device_token,
					"user_type"		=>$user_type,           //0=customer, 1= driver
					"name"			=>$name,
					"dob"			=>$dob,
					"gender"        =>$gender,
					"mobile"		=>$mobile,
					"email"			=>$email,
					"password"		=>$password,
					"image"			=>$imagename,
					"image_type"	=>$image_type, 			//0=normal, 1=media
					"activeStatus"  =>$activeStatus,        //Active, Inactive
					"device_type"	=>$device_type         //0=android, 1=ios
					);
				
				if($uid = $this->AuthModel->singleInsert($table_name,$data))
				{
					$where 			= array("id"=>$uid);
					$record 		= $this->AuthModel->getSingleRecord($table_name,$where);
					$dataResponse   = $this->AuthModel->keychange($record);
					$respose["success"] = 1;
					$respose["message"] = "success";
					$respose["data"]    = $dataResponse;
					echo json_encode($respose);
				}
				else
				{
					$respose["error"] = 1;
					$respose["message"] = "Error occur! Please try again";
					$respose["data"]    = '';
					echo json_encode($respose);
				}		
			}
		}
		else
		{
			$this->index();
		}
	}


	public function login()
	{
		//Login type = 0=>simple login, 1= fb_login 2=google login
		//isset param => login_type,device_token,device_type,email,password,mobile,media_id
		$response = array("success" => 0, "error" => 0);
		extract($_REQUEST);
		$table_name = 'users';
    	if(isset($_REQUEST['login_type']) && $_REQUEST['login_type']==0)
    	{
    		if((isset($_REQUEST['login']) && $_REQUEST['login']!=''))
            {

                $checkWhere  = array("mobile"=>$login,"password"=>$password,"user_type"=>0);   
                $checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
                //echo $checkCrediantial;die();
                $activeWhere = array("mobile"=>$login,"activeStatus"=>'Active',"user_type"=>0);
                if($checkCrediantial==0)
                {
                    $checkWhere =  array("email"=>$login,"password"=>$password,"user_type"=>0); 
                    $activeWhere = array("email"=>$login,"activeStatus"=>'Active',"user_type"=>0);
                    $checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
                } 			
 	   			$data = '';	
 	   			//print_r($checkWhere);die(); 	   			
				$checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
				if($checkCrediantial>0)
				{
					$this->AuthModel->checkActiveStatus($table_name,$activeWhere);      //Check, User is Active or not by admin;
					$upData     = array("device_token"=>$device_token,"device_type"=>$device_type,'wronglyPassword'=>0);
					$this->AuthModel->updateRecord($checkWhere,$table_name,$upData);
					$data 		= $this->AuthModel->getSingleRecord($table_name,$checkWhere);	
					$dataResponse     = $this->AuthModel->keychange($data);
					$response  = array("success"=>1, "error" => 0,"message"=>"success","data"=>$dataResponse);
					echo json_encode($response);
				}
				else
				{
					$this->AuthModel->passwordAttempt($table_name,$checkWhere);
					$response  = array("error"=>1,"success"=>0,"message"=>"Invalid Crediantial","data"=>'');
					echo json_encode($response);
				}
    		}
    		else{
    			$this->index();
    		}
    	}
    	elseif (isset($_REQUEST['login_type']) && ($_REQUEST['login_type']==1 || $_REQUEST['login_type']=2)) {
    		if(isset($_REQUEST['media_id']) && $_REQUEST['media_id']!='')
    		{    			
    			$user_type	=	0;
 	   			$data = $this->AuthModel->loginViaMedia($media_id,$login,$login_type,$device_token,$device_type,$user_type);
 	   			$dataResponse     = $this->AuthModel->keychange($data);
				$response  = array("success"=>1,"error" => 0,"message"=>"success","data"=>$dataResponse);
				echo json_encode($response);
    		}	
    		else{
    			$this->index();
    		}
    	}
    	else
    	{
    		$this->index();
    	}
	}


    public function ForgetPassword()
	{
		if(isset($_POST['email']) && $_POST['email']!='')
	    {
			$email=$_POST['email'];
			$table_name = "users";
			$res=$this->AuthModel->forget_password($table_name,$email);
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
	    else
	    {	    	
			$this->index();
	    }
	}

	public function change_password()
  	{
  		$response = array("success" => 0, "error" => 0);
  		//print_r($_POST);die();
  		if(isset($_POST['userid']) && $_POST['userid']!='')
  		{
			$id           = $this->input->post('userid');
			$old_password = $this->input->post('old_password');
			$password     = $this->input->post('new_password');

			$table_name   = 'users';
			$checkWhere   = array("id"=>$id,"password"=>$old_password);
			$checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
			if($checkCrediantial>0)
			{
				$updata   = array('password'=>$password);
				if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
				{
					$response["error"]				= 0;	
	    			$response["success"]			= 1;
	    			$response["message"]			= "Password has been changed successfull";
	    			echo json_encode($response);
				}
				else
				{
					$response["error"]				= 1;	
	    			$response["success"]			= 0;
	    			$response["message"]			= "Error Occur! Password is not change";
	    			echo json_encode($response);
				}		
			}
			else
		    {
		    	$response["error"]				= 2;	
				$response["success"]			= 0;
				$response["message"]			= "Old password does not match.Please Enter correct details";
				echo json_encode($response);
		    }				
  		}
  		else
  		{
  			$this->index();
  		}
  	}

  	public function get_profile()
  	{
  		if(isset($_POST['userid']) && $_POST['userid']!='')
  		{
  			extract($_POST);
  			$table_name       = "users";
  			$where            = array('id'=>$userid);
  			$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
  			if($ProfileData)
			{
				$UpdateData                 	= $this->AuthModel->keychange($ProfileData);
				$response["error"]				= 0;	
				$response["success"]			= 1;
				$response["message"]			= "Success";
				$response["data"]			    = $ProfileData;
				echo json_encode($response);
			}
			else
		    {
		    	$response["error"]				= 2;	
				$response["success"]			= 0;
				$response["message"]			= "User does not exist. Please Enter correct details";
				echo json_encode($response);
		    }
  		}
  		else
  		{
  			$response["error"]		= 1;
  			$response["success"]	= 0;
	    	$response["message"]	= "Access denied";
	    	echo json_encode($response);
  		}
  	}

  	public function profile_update()
  	{
  		if(isset($_POST['userid']) && $_POST['userid']!='')
  		{
  			extract($_POST);
			$update_at		  = date('Y-m-d h:i:s');
	        $table_name       = "users";
  			$where            = array('id'=>$userid);
  			$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
  			
  			$checkWhere = array("mobile"=>$mobile);
	        $checkMobile = $this->AuthModel->checkRows($table_name,$checkWhere);
	        if($checkMobile>0 && $mobile != $ProfileData->mobile)
        	{
        		$response= array("error"=>1,"success"=>0,"message"=>"Mobile number already registered");
        		echo json_encode($response);
        		exit();
        	}
	        $imagename 		  = $ProfileData->image;
	        if(isset($_FILES['image']))
			{
				$folder_name = 'userimage';
				$imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
			}

		    $updata= array(					
					"name"			=>$name,
					"mobile"        =>$mobile, 
					"gender"        =>$gender,
					"image"			=>$imagename,
					"image_type"	=>$image_type, 			//0=normal, 1=media					
					);

			$UpdateData = $this->AuthModel->updateRecord($where,$table_name,$updata);
			if($UpdateData)
			{
				$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
				$UpdateData                 	= $this->AuthModel->keychange($ProfileData);
				$response["error"]				= 0;	
				$response["success"]			= 1;
				$response["message"]			= "Success";
				$response["data"]			    = $UpdateData;
				echo json_encode($response);
			}
			else
		    {
		    	$response["error"]				= 2;	
				$response["success"]			= 0;
				$response["message"]			= "Oops! Error occur. Please Try again";
				echo json_encode($response);
		    }
		}
		else
  		{
  			$response["error"]		= 1;
	    	$response["message"]	= "Access denied";
	    	echo json_encode($response);
  		}
  	}

  	public function socialAccountConnectivity()
  	{
  		if(isset($_POST['link_type']) && $_POST['link_type']!='' && isset($_POST['user_id']) && $_POST['user_id']!='')
  		{  			
  			//link_type = 1=>fb 2=google+
  			extract($_POST);
  			$where = array('id'=>$user_id);
			if($link_type==1)
  			{
  				$updata = array('fb_id'=>$fb_id);
  				if($this->AuthModel->updateRecord($where,'users',$updata))
  				{	
  					$response = array('success'=>1,'error'=>0,'message'=>'success');
  					echo json_encode($response);
  				}
  				else
  				{
  					$response = array('success'=>0,'error'=>1,'message'=>'Something went wrong');
  					echo json_encode($response);
  				}
  			}
  			elseif($link_type==2)
  			{
  				$updata = array('google_id'=>$google_id);
  				if($this->AuthModel->updateRecord($where,'users',$updata))
  				{	
  					$response = array('success'=>1,'error'=>0,'message'=>'success');
  					echo json_encode($response);
  				}
  				else
  				{
  					$response = array('success'=>0,'error'=>1,'message'=>'Something went wrong');
  					echo json_encode($response);
  				}	
  			}
  			else
  			{
  				$this->index();
  			}			
  		}
  		else
  		{
  			$this->index();
  		}
  	}

  	public function support()
  	{
  		if(isset($_POST['booking_id']) && $_POST['booking_id']!='' && isset($_POST['user_id']) && $_POST['user_id']!='')
  		{
  			extract($_POST);
  			$imagename 		  ='default.jpg';
	        if(isset($_FILES['issue_image']))
			{
				$folder_name = 'supportImage';
				$imagename   = $this->AuthModel->imageUpload($_FILES['issue_image'],$folder_name);
			}
			$data = array(
				'user_id'		=>$user_id,
				'name'			=>$name,
				'contact'		=>$contact_no,
				'email'  		=>$email,
				'subject'		=>$subject,
				'date_time'		=>$date_time,
				'booking_id'	=>$booking_id,
				'issue_image'	=>$imagename,
				'feedback_details'=>$feedback_details
				);
			if($this->AuthModel->singleInsert('support',$data))
			{
				$response = array('success'=>1,'error'=>0,'message'=>'success');
  				echo json_encode($response);
			}
			else
			{
				$response = array('success'=>0,'error'=>1,'message'=>'Something went wrong');
  				echo json_encode($response);
			}
  		}
  		else
  		{
  			$this->index();
  		}
  	} 

  	public function getServiceType()
  	{
  		$where = array('status'=>'active');  		
  		$resData = $this->AuthModel->getMultipleRecord('servicetype',$where,'');
  		if(!empty($resData))
  		{
  			$response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$resData);
  			echo json_encode($response);
  		}
  		else
  		{
  			$response = array('success'=>0,'error'=>1,'message'=>'No service type found','data'=>array());
  			echo json_encode($response);
  		}
  	}

  	public function getServicetypeDetails()
  	{
  		if(isset($_POST['type_id']) && $_POST['type_id']!='' && isset($_POST['city']) && $_POST['city']!='')
  		{
  			extract($_POST);
  			$where = array('serviceType_id'=>$type_id,'country'=>$country,'city'=>$city);
  			$resData = $this->AuthModel->getSingleRecord('fair',$where);
  			if(!empty($resData))
  			{
  				$response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$resData);
	  			echo json_encode($response);
  			}
  			else
  			{
  				$response = array('success'=>0,'error'=>1,'message'=>'Service details is not found for this city','data'=>array());
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

