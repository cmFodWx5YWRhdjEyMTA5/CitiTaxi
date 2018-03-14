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
		if(isset($_POST['email']) && $_POST['email']!='')
		{
			extract($_POST);
			$checkmail   = array("email"=>$email);
			$checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);		
			$checkMobile = array('mobile' =>$mobile,'mobile!='=>'');
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
		extract($_POST);
		$table_name = 'users';
    	if(isset($_POST['login_type']) && $_POST['login_type']==0)
    	{
    		if((isset($_POST['email']) && $_POST['email']!='') || (isset($_POST['mobile']) && $_POST['mobile']!=''))
    		{
    			if($mobile!=''){
    				$checkWhere = array("mobile"=>$mobile,"password"=>$password);	
    				$activeWhere = array("mobile"=>$mobile,"activeStatus"=>'Active');
    			}
    			else{
    				$checkWhere = array("email"=>$email,"password"=>$password);	
    				$activeWhere = array("email"=>$email,"activeStatus"=>'Active');
    			}    			
 	   			$data = '';		
 	   			//print_r($checkWhere);die();
				$checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
				if($checkCrediantial>0)
				{
					$this->AuthModel->checkActiveStatus($table_name,$activeWhere);      //Check, User is Active or not by admin;
					$upData     = array("device_token"=>$device_token,"device_type"=>$device_type);
					$this->AuthModel->updateRecord($checkWhere,$table_name,$upData);
					$data 		= $this->AuthModel->getSingleRecord($table_name,$checkWhere);	
					$dataResponse     = $this->AuthModel->keychange($data);
					$response  = array("success"=>1,"message"=>"success","data"=>$dataResponse);
					echo json_encode($response);
				}
				else
				{
					$response  = array("error"=>1,"message"=>"Invalid Crediantial","data"=>'');
					echo json_encode($response);
				}
    		}
    		else{
    			$this->index();
    		}
    	}
    	elseif (isset($_POST['login_type']) && ($_POST['login_type']==1 || $_POST['login_type']=2)) {
    		if(isset($_POST['media_id']) && $_POST['media_id']!='')
    		{    			
 	   			$data = $this->AuthModel->loginViaMedia($media_id,$email,$login_type,$device_token,$device_type);
 	   			$dataResponse     = $this->AuthModel->keychange($data);
				$response  = array("success"=>1,"message"=>"success","data"=>$dataResponse);
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

  	public function addvechile()
    {
    	$respose = array("success"=>0,"error"=>0,"message"=>'');
		$table_name = 'vehicle';
    	if(isset($_POST['userid']) && $_POST['userid']!='')
    	{
    		extract($_POST);
    		$vechicleimage ='default.jpg';
			if(isset($_FILES['vechicleImage']))
			{
				$folder_name 	= 'vechileImage';
				$vechicleimage   = $this->AuthModel->imageUpload($_FILES['vechicleImage'],$folder_name);
			}
			$licenceImage='default.jpg';
			if(isset($_FILES['licenceImage']))
			{
				$folder_name 	= 'licenceImage';
				$licenceImage   = $this->AuthModel->imageUpload($_FILES['licenceImage'],$folder_name);
			}
    		$data = array(
    			"user_id"=>$userid,
    			"brand"=>$brand,
    			"model"=>$model,
    			"year"=>$year,
    			"color"=>$color,
    			"interior_color"=>$interior_color,
    			"licence_number"=>$licence_number,
    			"type"=>$type,            //four wheel or six wheel 
    			"issue_on"=>$issue_on,
    			"expire"=>$expire,
    			"vichleimage"=>$vechicleimage,
    			"licence_image"=>$licenceImage
    			);
    		if($uid = $this->AuthModel->singleInsert($table_name,$data))
			{
				$respose["success"] = 1;
				$respose["message"] = "success";
				echo json_encode($respose);
			}	
			else
			{
				$respose["error"] = 1;
				$respose["message"] = "Error occur! Please try again";
				echo json_encode($respose);
			}
    	}
    	else
    	{
    		$this->index();
    	}
    }

    public function get_vechileDetails()
    {
    	$respose = array("success"=>0,"error"=>0,"message"=>'');
		$table_name = 'vehicle';
    	if(isset($_POST['userid']) && $_POST['userid']!='')
    	{
    		extract($_POST);
    		$where= array('user_id'=>$userid);
    		$orderby ="";
    		$vehicleDetails = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
    		if(!empty($vehicleDetails))
			{
				$respose["success"] = 1;
				$respose["message"] = "success";
				$respose["data"] = $vehicleDetails;
				echo json_encode($respose);
			}	
			else
			{
				$respose["error"] = 1;
				$respose["message"] = "No vehicle added by you";
				$respose["data"] = array();
				echo json_encode($respose);
			}
    	}
    	else
    	{
    		$this->index();
    	}
    }
    
    public function add_ride()
    {
    	$respose = array("success"=>0,"error"=>0,"message"=>'');
		$table_name = 'ride';
    	if(isset($_POST['userid']) && $_POST['userid']!='')
    	{
    		extract($_POST);
    		$data = array(
    			"user_id" 	=>$userid,
    			"fromAddress" =>$fromAddress,
    			"fromLat" 	=>$fromAddressLat,
    			"fromLng" 	=>$fromAddressLng,
    			"toAddress"	=>$toAddress,
    			"toLat" 	=>$toAddressLat,
    			"toLng" 	=>$toAddressLng,
    			"date"  	=>$rideDate,
    			"time"  	=>$rideTime,
    			"Vechicleseats" =>$Vechicleseats,
    			"luggagesize"=>$luggagesize,
    			"luggagequantity"=>$luggagequantity,
    			"pickupFlexibility"=>$pickupFlexibility,
    			"per_seat_price"=>$per_seat_price,
    			"pet"=>$pet,
    			"payment"=>$payment,
    			"vehicleid"=>$vehicleid,
    			);
    		if($rideId = $this->AuthModel->singleInsert($table_name,$data))
			{
				$where         = array('ride_id'=>$rideId);
				$rideData      = $this->AuthModel->getSingleRecord($table_name,$where);
				$respose["success"] = 1;
				$respose["message"] = "success";
				$respose["data"]= $rideData;
				echo json_encode($respose);
			}	
			else
			{
				$respose["error"] = 1;
				$respose["message"] = "Error occur! Please try again";
				$respose["data"]='';
				echo json_encode($respose);
			}
    	}
    	else
    	{
    		$this->index();
    	}
    }

    public function requestRide()
    {
        $respose = array("success"=>0,"error"=>0,"message"=>'');
        $table_name = 'ride';
        if(isset($_POST['userid']) && $_POST['userid']!='')
        {
            extract($_POST);
            $data = array(
                "user_id"   =>$userid,
                "toAddress" =>$toAddress,
                "toLat"     =>$toAddressLat,
                "toLng"     =>$toAddressLng,
                "fromAddress" =>$fromAddress,
                "fromLat"   =>$fromAddressLat,
                "fromLng"   =>$fromAddressLng,               
                "date"      =>$rideDate,
                "time"      =>$rideTime,
                "ridetype"  =>1
                );
            if($rideId = $this->AuthModel->singleInsert($table_name,$data))
            {
                $where         = array('ride_id'=>$rideId);
                $rideData      = $this->AuthModel->getSingleRecord($table_name,$where);
                $respose["success"] = 1;
                $respose["message"] = "Ride request has been successfully saved";
                $respose["data"]= $rideData;
                echo json_encode($respose);
            }   
            else
            {
                $respose["error"] = 1;
                $respose["message"] = "Error occur! Please try again";
                $respose["data"]='';
                echo json_encode($respose);
            }
        }
        else
        {
            $this->index();
        }
    }

    public function searchUsrs()
    {
        $respose = array("success"=>0,"error"=>0,"message"=>'');
        $table_name = 'ride';          
        //searchtype  =====>  0= find cars for ride, 1=find user for travelling
        if(isset($_POST['searchtype']) && $_POST['searchtype']!='')
        {
            extract($_POST);
            $FromMatchData = $this->AuthModel->searchFromAddress($searchtype,$fromAddressLat,$fromAddressLng,$date,$time);
            //echo json_encode($FromMatchData);die();
            $search = array();
            if(!empty($FromMatchData))
            {                
                foreach ($FromMatchData as $from => $f) 
                {
                    $rideId = $f->ride_id;
                    if($searchtype==0)
                    {
                        $finalSearch = $this->AuthModel->searchToAddressWithCar($rideId,$toAddressLat,$toAddressLng);
                    }
                    else
                    {
                        $finalSearch = $this->AuthModel->searchToAddress($rideId,$toAddressLat,$toAddressLng);
                    }
                    if(!empty($finalSearch))
                    {
                        $dataResponse   = $this->AuthModel->keychange($finalSearch);
                        $search[] = $dataResponse;
                    }                   
                }
                $respose = array("success"=>1, "message"=>"success", "data"=>$search);
                echo json_encode($respose);

            }
            else
            {
                $respose = array("error"=>1, "message"=>"No matching ride available", "data"=>array());
                echo json_encode($respose);
            }
            
        }
        else
        {
            $this->index();
        }
    }

    public function sendRideRequest()
    {
        $respose = array("success"=>0,"error"=>0,"message"=>'');
        $table_name = 'Riderequest';          
        //Ride type  =====>  0=find ride request user 1= findcreat ride user
        if(isset($_POST['ride_id']) && $_POST['ride_id']!='')
        {
            extract($_POST);
            if($request_type=='byCustomer' OR $request_type=='byRideCreatUser')
            {
                $data = array(
                "request_type"          =>$request_type,                   //byCustomer , byRideCreatUser
                "ride_id"               =>$ride_id,
                "offer_rideuser_id"     =>$offer_rideuser_id,
                "request_rideuser_id"   =>$request_rideuser_id,
                );
                $rowCount = $this->AuthModel->checkRows($table_name,$data);
                if($rowCount==0)
                {
                    if($uid = $this->AuthModel->singleInsert($table_name,$data))
                    {
                        $respose = array("success"=>1,"message"=>"Request has been send successfully");
                        echo json_encode($respose);
                    }   
                    else
                    {
                        $respose = array("error"=>1,"message"=>"Oops! Something went wrong, Please try again");
                        echo json_encode($respose);
                    }
                }
                else
                {
                    $respose = array("error"=>2,"message"=>"You have already send request for this ride");
                    echo json_encode($respose);
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

    public function rideRequestStatus()
    {
        $respose = array("success"=>0,"error"=>0,"message"=>'');
        $table_name = 'Riderequest';          
        if(isset($_POST['ride_request_id']) && $_POST['ride_request_id']!='')
        {
            extract($_POST);
            $checkWhere = array('request_id'=>$ride_request_id);
            if($ride_status==3)
            {                
                $updata = array("ride_status"=>$ride_status,"cancel_reason"=>$cancel_reason);
            }
            else
            {
                $updata = array("ride_status"=>$ride_status);   
            }
            if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
            {
                 $respose = array("success"=>1,"message"=>"Request has been successfully saved");
                    echo json_encode($respose);
            }   
            else
            {
                $respose = array("error"=>1,"message"=>"Oops! Something went wrong, Please try again");
                echo json_encode($respose);
            }
        }
        else
        {
            $this->index();
        }
    }

    public function getRequestAndCreatRides()
    {
        $table_name = 'ride';          
        if(isset($_POST['user_id']) && $_POST['user_id']!='' && isset($_POST['getType']) && $_POST['getType']!='')
        {
            //getType:  0= find his creat rides 1= Find his request rides.
            extract($_POST);
            $orderby    = "`ride_id` DESC";
            $where      = array('user_id'=>$user_id,'ridetype'=>$getType);
            $res        = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
            $respose = array("success"=>1, "error"=>0, "message"=>"success","data"=>$res);
            echo json_encode($respose);
        }
        else
        {
            $this->index();
        }
    }

    
}
?>

