<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dummy extends CI_Controller {
	function __construct() {
        parent::__construct();
        //$this->load->helper(form,url);
        $this->load->model("AuthModel");      
        $this->load->model("DummyModel");
        $this->load->library('encrypt');        
    }

    public function index()
	{
		$respose["success"] = 0;
		$respose["error"]=400;
		$respose["message"]="Access Denied";
		echo json_encode($respose);
	}

    public function genratePassword()
    {
        $password = $this->encrypt->encode($this->input->post('password'));
        echo $password;
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
    		$vehicleDetails = $this->AuthModel->getMultipleRecord($table_name,$where);
    		if(!empty($vehicleDetails))
			{
				$respose["success"] = 1;
				$respose["message"] = "success";
				$respose["vehicleDetails"] = $vehicleDetails;
				echo json_encode($respose);
			}	
			else
			{
				$respose["error"] = 1;
				$respose["message"] = "No vehicle added by you";
				$respose["vehicleDetails"] = array();
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
				$respose["rideData"]= $rideData;
				echo json_encode($respose);
			}	
			else
			{
				$respose["error"] = 1;
				$respose["message"] = "Error occur! Please try again";
				$respose["rideData"]='';
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
                $respose["requestRideData"]= $rideData;
                echo json_encode($respose);
            }   
            else
            {
                $respose["error"] = 1;
                $respose["message"] = "Error occur! Please try again";
                $respose["requestRideData"]='';
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
        //Ride type  =====>  0=find ride request user 1= findcreat ride user
        if(isset($_POST['searchtype']) && $_POST['searchtype']!='')
        {
            extract($_POST);
            $FromMatchData = $this->DummyModel->searchFromAddress($searchtype,$fromAddressLat,$fromAddressLng,$date,$time);
            //echo json_encode($FromMatchData);die();
            $search = array();
            if(!empty($FromMatchData))
            {                
                foreach ($FromMatchData as $from => $f) 
                {
                    $rideId = $f->ride_id;
                    if($searchtype==0)
                    {
                        $finalSearch = $this->DummyModel->searchToAddressWithCar($rideId,$toAddressLat,$toAddressLng);
                    }
                    else
                    {
                        $finalSearch = $this->DummyModel->searchToAddress($rideId,$toAddressLat,$toAddressLng);
                    }
                    if(!empty($finalSearch))
                    {
                        $dataResponse   = $this->DummyModel->keychange($finalSearch);
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
                $rowCount = $this->DummyModel->checkRows($table_name,$data);
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
            if($this->DummyModel->updateRecord($checkWhere,$table_name,$updata))
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
            //ridetype:  0= find his creat rides 1= Find his request rides.
            extract($_POST);
            $orderby    = "`ride_id` DESC";
            $where      = array('user_id'=>$user_id,'ridetype'=>$getType);
            $res        = $this->DummyModel->getMultipleRecord($table_name,$where,$orderby);
            $respose = array("success"=>1, "error"=>0, "message"=>"success","data"=>$res);
            echo json_encode($respose);
        }
        else
        {
            $this->index();
        }
    }

    public function insertRecords()  //for insert record in new table
    {
        $users = $this->DummyModel->getMultipleRecord('users',array(),'');
        foreach ($users as $key => $value) {
            $user_id = $value->id;
            $this->DummyModel->singleInsert('wallet_balance',array('user_id'=>$user_id,'update_at'=>date('Y-m-d H:i:s')));
        }
    }
}


