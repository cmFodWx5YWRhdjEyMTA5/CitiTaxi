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
				$respose= array("success"=>0,"error"=>1,"message"=>"Mobile number has already registered");
				echo json_encode($respose);
			}
			else
			{
				if(isset($_POST['referral_code']) && $_POST['referral_code']!=''){
					$referral_code = $_POST['referral_code'];
					$checkreferral = $this->AuthModel->checkRows($table_name,array('ref_code'=>$referral_code,'user_type'=>0));
					if($checkreferral==0){
						$respose= array("success"=>0,"error"=>1,"message"=>"Invalid Referral Code!");
						echo json_encode($respose);die();
					}						
				}
					
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
			    $currency = '';
			    $cur = $this->AuthModel->getSingleRecord('countries',array('name'=>$country));			    
			    if(!empty($cur)){ $currency = $cur->currency_symbol; }			    
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
					"device_type"	=>$device_type,         //0=android, 1=ios
					"nationality"   =>$country,
					"currency"      =>$currency,
					);
				
				if($uid = $this->AuthModel->singleInsert($table_name,$data))
				{			
				 	//Save referral setting	
					if(isset($_POST['referral_code']) && $_POST['referral_code']!=''){
						$referral_code = $_POST['referral_code'];
						$this->AuthModel->saveReferralDiscount($uid,$name,$referral_code,$user_type);								
					}	
					$this->AuthModel->user_score($uid,0); //save for score
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
                $activeWhere = array("mobile"=>$login,"user_type"=>0);
                if($checkCrediantial==0)
                {
                    $checkWhere =  array("email"=>$login, "password"=>$password,"user_type"=>0); 
                    $activeWhere = array("email"=>$login, "user_type"=>0);
                    $checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
                } 			
 	   			$data = '';	
				$checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
 	   			//print_r($activeWhere);die(); 	   			
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

	public function logout(){
		if(isset($_POST['user_id']) && $_POST['user_id']!=''){
			extract($_POST);
			$upData     = array("device_token"=>'','online_status'=>'offline');
			if($this->AuthModel->updateRecord(array('id'=>$user_id),'users',$upData)){
				$response  = array("success"=>1,"error" => 0,"message"=>"Logout successfull !");
				echo json_encode($response);
			}
			else{
				$response  = array("success"=>0,"error" =>1,"message"=>"Something went wrong! Please try again");
				echo json_encode($response);
			}
		}
		else{
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
  		/*$data = json_decode(file_get_contents("php://input"));
		$method = $_SERVER['REQUEST_METHOD'];
		$id = $data->userid;
  		echo $id;die();*/
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
				$UpdateData       = $this->AuthModel->keychange($ProfileData);
				$rating           =get_rating($userid);
				$UpdateData->rating = $rating;
				$UpdateData->point= getSum('booking','customer_trip_score',array('customer_id'=>$userid));
				$UpdateData->wallet_balance=$this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$userid))->balance;
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
  					$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
  					$UpdateData                 	= $this->AuthModel->keychange($ProfileData);
  					$response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$UpdateData);
  					echo json_encode($response);
  				}
  				else
  				{
  					$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
  					$UpdateData                 	= $this->AuthModel->keychange($ProfileData);
  					$response = array('success'=>0,'error'=>1,'message'=>'Something went wrong','data'=>$UpdateData);
  					echo json_encode($response);
  				}
  			}
  			elseif($link_type==2)
  			{
  				$updata = array('google_id'=>$google_id);
  				if($this->AuthModel->updateRecord($where,'users',$updata))
  				{	
  					$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
  					$UpdateData       = $this->AuthModel->keychange($ProfileData);
  					$response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$UpdateData);
  					echo json_encode($response);
  				}
  				else
  				{
  					$ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
  					$UpdateData       = $this->AuthModel->keychange($ProfileData);
  					$response = array('success'=>0,'error'=>1,'message'=>'Something went wrong','data'=>$UpdateData);
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
  		if(isset($_POST['city']) && $_POST['city']!='' && isset($_POST['device_type']) && $_POST['device_type']!='')
  		{	 
  			extract($_POST);  //'device_type'
  			$paramarray = array('customer_id','country','city','device_token');
            $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$_POST);
            if(isset($vResponse['status']) && $vResponse['status']==0)
            {
                $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                echo json_encode($response);die();
            }
            else
            {
            	$this->AuthModel->checkActiveStatus('users',array('id'=>$customer_id));
            	$resData = $this->AuthModel->getMultipleRecord('servicetype',array('status'=>'active'),'');
            	$this->AuthModel->updateRecord(array('id'=>$customer_id),'users',array("device_token"=>$device_token,'device_type'=>$device_type));
		  		if(!empty($resData))
		  		{
		  			foreach ($resData as $key => $value) {
		  				//echo 'join()';die();
		  				$type_id = $value->typeid;	
		  				//echo $type_id;die();		
				  		$where = array('serviceType_id'=>$type_id,'country'=>$country,'city'=>$city);
			  			$fareData = $this->AuthModel->getSingleRecord('fare',$where);
			  			//print_r($fareData);die();
			  			$service  = $this->AuthModel->keychange($resData[$key]);
			  			//print_r($service);die();
			  			$res['service']=$service;
			  			//print_r($res);die();
			  			if(!empty($fareData))
			  			{
			  				$res['serviceDetail'] = $fareData;
			  			}
			  			$services[]=$res;
		  			}
		  			$response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$services);
		  			echo json_encode($response);
		  		}
		  		else
		  		{
		  			$response = array('success'=>0,'error'=>1,'message'=>'No service type found','data'=>array());
		  			echo json_encode($response);
		  		}
            }	  		
	  	}
	  	else
	  	{
	  		$this->index();
	  	}
  	}

  	public function getServicetypeDetails()
  	{
  		if(isset($_POST['type_id']) && $_POST['type_id']!='' && isset($_POST['city']) && $_POST['city']!='')
  		{
  			extract($_POST);
  			$where = array('serviceType_id'=>$type_id,'country'=>$country,'city'=>$city);
  			$resData = $this->AuthModel->getSingleRecord('fare',$where);
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

  	public function addreview()
    {
    	if(isset($_POST['booking_id']) && $_POST['booking_id']!='')
    	{
    		extract($_POST);
    		$checkReview  = $this->AuthModel->checkRows('review',array('booking_id'=>$booking_id,'giver_id'=>$giver_id,'receiver_id'=>$receiver_id));
    		if($checkReview>0)
    		{
    			$respose = array("success"=>0, "error"=>1, "message"=>"You already give review for this trip");
                echo json_encode($respose); 
    		}
    		else
    		{
    			$data = array(
    			'booking_id'=>$booking_id,
    			'giver_id'=>$giver_id,
    			'receiver_id'=>$receiver_id,
    			'rating'=>$rating,
    			'review'=>$review,
	    		);

	    		if($review_id = $this->AuthModel->singleInsert('review',$data))
	    		{
	    			//$notification_message = 'New review for ride id '.$ride_id.'.  '.$review.' and get '.$rating.' rating';
            		//$this->Communication_model->CheckDeivceForNotification($receiver_id,$notification_message);
            		$reviewData = $this->AuthModel->getSingleRecord('review',array("review_id"=>$review_id));
	    			$respose = array("success"=>1, "error"=>0, "message"=>"Thanks for review.","data"=>$reviewData);
	                echo json_encode($respose); 
	    		}
	    		else
	    		{
	    			$respose = array("success"=>0, "error"=>1, "message"=>"Something went wrong, Please try again","data"=>'');
	                echo json_encode($respose);   
	    		}
    		}    		
    	}
    	else
        {
            $this->index();
        }
    }

    public function checkActiveStatus()   //To check user current Active status
    {
    	if(isset($_POST['user_id']) && $_POST['user_id']!='')
    	{
    		extract($_POST);
    		if($this->AuthModel->checkActiveStatus('users',array('id'=>$user_id)))      //Check, User is Active or not by admin;
    		{
    			$respose = array("success"=>1, "error"=>0, "message"=>"success","data"=>'');
	            echo json_encode($respose);
    		}
    		else
    		{
    			$respose = array("success"=>0, "error"=>1, "message"=>"Something went wrong!","data"=>'');
	            echo json_encode($respose);
    		}
    	}
    	else
        {
            $this->index();
        }
    }

    public function myRating()
    {
    	if(isset($_POST['user_id']) && $_POST['user_id']!='')
    	{
    		extract($_POST);    		
    		$resData = $this->AuthModel->getMultipleRecord('review',array('receiver_id'=>$user_id),'');
	  		if(!empty($resData))
	  		{
	  			foreach ($resData as $rating => $r) {
	  				$giver_id = $r->giver_id;
	  				$giver_data = $this->AuthModel->getSingleRecord('users',array('id'=>$giver_id));
	  				$res['review']=$resData[$rating];
	  				$resData[$rating]->image='';	
	  				$resData[$rating]->name  ='';  			
	  				if(!empty($giver_data))
	  				{
	  					if($giver_data->image_type==0){
	  						$image = base_url('/userimage/'.$giver_data->image);	  					
	  					}
	  					else{
	  							$image = $giver_data->image;
	  						}
	  					$resData[$rating]->image = $image;
	  					$resData[$rating]->name  = $giver_data->name;
	  				}	  				
	  			}
	  			$response = array("success"=>1, "error"=>0, "message"=>"success","data"=>$resData);
	            echo json_encode($response);
	  		}
	  		else
	  		{
	  			$response = array("success"=>0, "error"=>1, "message"=>"No rating","data"=>'');
	            echo json_encode($response);
	  		}
    	}
    }

    public function get_referral_setting(){
    	if(isset($_POST['country']) && $_POST['country']!='' && isset($_POST['user_type']) && $_POST['user_type']!=''){
    		extract($_POST);
    		$setting = $this->AuthModel->getSingleRecord('referral_setting',array('country'=>$country,'user_type'=>$user_type));
    		if(!empty($setting)){
    			$response = array("success"=>1, "error"=>0, "message"=>"success","data"=>$setting);
	            echo json_encode($response);
    		}
    		else{
    			$response = array("success"=>0, "error"=>1, "message"=>"No invitation bouns for your country","data"=>'');
	            echo json_encode($response);
    		}
    	}
    	else{
    		$this->index();
    	}
    }

    public function get_notifications($user_id){
    	if(isset($_GET['user_id']) && $_GET['user_id']!=''){
    		$user_id = $_GET['user_id'];
    		$notification = $this->AuthModel->getMultipleRecord('notifications',array('user_id'=>$user_id),'');
    		if(!empty($notification)){
    			$response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$notification);
	            echo json_encode($response);
    		}else{
    			$response = array("success"=>0,"error"=>1,"message"=>"No notification found","data"=>array());
	            echo json_encode($response);
    		}
    	}
    	else{
    		$this->index();
    	}
    }

    public function remove_notification(){
    	if(isset($_POST['user_id']) && $_POST['user_id']!=''){
    		$paramarray = array('type','notification_id');
            $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$_POST);
            if(isset($vResponse['status']) && $vResponse['status']==0)
            {
                $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                echo json_encode($response);die();
            }
            else{            	
            	extract($_POST);
            	if($type==1){
            		$checkWhere= array('notification_id'=>$notification_id);
            	}else{
            		$checkWhere= array('user_id'=>$user_id);
            	}
    			if($this->AuthModel->delete_record('notifications',$checkWhere))
    			{
    				$notification = $this->AuthModel->getMultipleRecord('notifications',array('user_id'=>$user_id),'');
		    		if(!empty($notification)){
		    			$response = array("success"=>1,"error"=>0,"message"=>"Notification has been removed","data"=>$notification);
			            echo json_encode($response);
		    		}else{
		    			$response = array("success"=>0,"error"=>1,"message"=>"All notification has been removed","data"=>array());
			            echo json_encode($response);
		    		}
    			}
    			else{
    				$notification = $this->AuthModel->getMultipleRecord('notifications',array('user_id'=>$user_id),'');
		    		$response = array("success"=>0,"error"=>1,"message"=>"Oops! Something went wrong, Please try again.","data"=>$notification);
			        echo json_encode($response);
    			}
            }
    	}
    }

    public function get_heatmap_Data(){   
    	if(isset($_POST['country']) && $_POST['country']!='' && isset($_POST['city']) && $_POST['city']!=''){   
    		extract($_POST);
	        //$current  =  strtotime(date('d-m-Y h:i A'));
	        //$current  =  date('d-m-Y h:i A',strtotime('02-06-2018 11:50 AM'));
	        //$start    =  date('d-m-Y h:i A',strtotime('-3 hour',strtotime($current)));
	        //$current  =  strtotime('14-06-2018 05:50 PM');
	        $current  =  strtotime('14-06-2018 05:50 PM');
	        $start    =  strtotime('-3 hour',$current);
	        //echo $start;die();
	        $booking  =  $this->AuthModel->getMultipleRecord('booking',array('booking_at_string>='=>$start,'booking_at_string<='=>$current,'country'=>$country,'city'=>$city),'booking_id DESC');
	        if(!empty($booking)){ 
	            foreach ($booking as $key => $l) {
	                $latlng[] = array('lat'=>$l->pickupLat,'lng'=>$l->pickupLong);              
	            }          
	            $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$latlng);
	            echo json_encode($response);
	        }else{
	            $response = array('success'=>0,'error'=>1,'message'=>'Booking is not found','data'=>$booking);
	            echo json_encode($response);
	        }
	   	}
	   	else{
            $response = array('success'=>0,'error'=>1,'message'=>'You have missed country and city');
            echo json_encode($response);
        }        
    }

    public function get_pagedata(){
    	if(isset($_POST['page_name']) && $_POST['page_name']!=''){
    		extract($_POST);
    		$res = $this->AuthModel->getSingleRecord('wp_posts',array('post_title'=>$page_name,'post_status'=>'publish'));
    		//print_r($this->db->last_query());
    		if(!empty($res)){
    			$res->post_content = str_replace("h4","h3",$res->post_content);
    			$res->post_content = str_replace("h5","h3",$res->post_content);
    			$res->post_content = str_replace("h1","h3",$res->post_content);
    			$res->post_content = str_replace("h2","h3",$res->post_content);
    			$data = array('post_date_gmt'=>$res->post_date_gmt,'post_title'=>$res->post_title,'post_content'=>$res->post_content);
    			//print_r($res->post_content);die();
    			$response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$data);
            	echo json_encode($response);
    		}
    		else{
    			$response = array('success'=>0,'error'=>1,'message'=>'Page is not found','data'=>$res);
            	echo json_encode($response);
    		}
    	}
    }

    	    
}
?>

