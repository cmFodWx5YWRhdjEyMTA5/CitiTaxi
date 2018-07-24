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

    public function calculateFair()
    {        
        //$total_distance,$total_rideMinute        
        extract($_POST);
        $total_distance= str_replace(" km","",$_POST['distance']);
        $total_rideMinute= str_replace(" mins","",$_POST['time']);   
        $now = strtotime(date('h:i A')); 
        //echo $total_distance;
        // $fair_detail = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$service_id,'country'=>$this->country,'city'=>$this->city));
        $fair_detail = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$service_id,'country'=>$_POST['country'],'city'=>$_POST['city']));
        //print_r($fair_detail);die();       
        
        if(!empty($fair_detail))
        {
            //$fair_detail = $this->AuthModel->getSingleRecord('booking_fare',array('booking_id'=>$booking_id));
            //echo json_encode($fair_detail);
            $Extra_waitingMinute  = 0;  
            $total_waitingCharge  = 0;
            $total_perMinute_charge = 0;
            $total_regularCharge    = 0;
            if($Extra_waitingMinute>0)
            {
                $total_waitingCharge = $Extra_waitingMinute*($fair_detail->every_waiting_minute_charge/$fair_detail->paid_every_waiting_minute);
            }   
            if($total_distance>$fair_detail->min_distance)  //To calculate regularchage, Total distance must be grater then minimum distance other wise total regular charge will equal to minimum base fare
            {
                $t =  intval($total_distance);
                if($t==$total_distance){
                    $total_distance =$t;
                }
                $extra_distance = $total_distance-$fair_detail->min_distance;                               
                $rightExtra = $this->BookingModel->rightMultiple($extra_distance,$fair_detail->regularChargeEveryDistance); 
                //echo $rightExtra;die();               
                $total_regularCharge = ($rightExtra/$fair_detail->regularChargeEveryDistance)*$fair_detail->regularChargeForDistance;

                // $ntotal_regularCharge = ($total_distance-$fair_detail->min_distance/$fair_detail->regularChargeEveryDistance)*$fair_detail->regularChargeForDistance;            
            }                     
            if($fair_detail->perMinChargeStatus=='on')
            {                
                $minuteForCharge = $this->BookingModel->rightMultiple($total_rideMinute,$fair_detail->unitPerMinuteforCharge);
                //echo $minuteForCharge;die();
                $total_perMinute_charge = ($minuteForCharge/$fair_detail->unitPerMinuteforCharge)*$fair_detail->unitPerMinutecharge;    
                //echo $total_perMinute_charge;die();    
            }
            $total_fair = $fair_detail->minbase_fair+$fair_detail->mini_distancefair+$total_waitingCharge+ $total_regularCharge+$total_perMinute_charge;
            $total_surcharge = 0;
            if($fair_detail->morningChargeStatus=='on' && $now>strtotime($fair_detail->morningSurchargeTimeStart) && $now<strtotime($fair_detail->morningSurchargeTimeEnd))
            {
                if($fair_detail->morningSurchargeUnit=='Per')
                {
                    $total_surcharge = ($total_fair*$fair_detail->morning_surcharge)/100;
                    $total_fair = $total_fair+$total_surcharge;
                }
                else
                {
                    $total_surcharge = $fair_detail->morning_surcharge;
                    $total_fair = $total_fair+$fair_detail->morning_surcharge;
                }
            }
            elseif($fair_detail->eveningChargeStatus=='on' && $now>strtotime($fair_detail->eveningSurchargeTimeStart) && $now<strtotime($fair_detail->eveningSurchargeTimeEnd))
            {
                if($fair_detail->eveningSurchargeUnit=='Per')
                {
                    $total_surcharge = ($total_fair*$fair_detail->evening_surcharge)/100;
                    $total_fair = $total_fair+$total_surcharge;
                }
                else
                {
                    $total_surcharge =$fair_detail->evening_surcharge;
                    $total_fair = $total_fair+$fair_detail->evening_surcharge;
                }
            }
            elseif($fair_detail->midNightChargeStatus=='on' && $now>strtotime($fair_detail->midNightSurchargeTimeStart) && $now<strtotime($fair_detail->midNightSurchargeTimeEnd))
            {
                if($fair_detail->midNightSurchargeUnit=='Per')
                {
                    $total_surcharge = ($total_fair*$fair_detail->midNightSurchargePrice)/100;
                    $total_fair = $total_fair+(($total_fair*$fair_detail->midNightSurchargePrice)/100);
                }
                else
                {
                    $total_surcharge = $fair_detail->midNightSurchargePrice;
                    $total_fair = $total_fair+$fair_detail->midNightSurchargePrice;
                }
            }
            $data['total_regular_charge']=$total_regularCharge;
            $data['total_per_minute_charge']= $total_perMinute_charge;
            $data['total_waiting_charge']=$total_waitingCharge;
            $data['total_surcharge']= $total_surcharge;
            $data['total_fair'] =  ceil($total_fair);
            $data['currency']   =  $fair_detail->currency;    
            echo json_encode($data);                   
            //print_r($data);
        }
    }



    public function rightFactor(){
        $minDistance = 2;
        $minDistanceCharge = 50;
        $total_distance = 45;
        $everyDistance = 7;
        $everyDistanceCharge = 10;
        $extra_distance =  $total_distance-$minDistance;  //48-5 = 43km;  laana h 48
        $extra = $extra_distance/$everyDistance;
        if(is_float($extra_distance/$everyDistance)){
           $x = floor($extra_distance/$everyDistance);              
           $new = ($x+1)*$everyDistance;
           //echo $new;
        }
        else{
            $new = $extra_distance;
        }
        echo $new;
    }


    public function genratePassword()
    {
        $password = $this->encrypt->encode($this->input->post('password'));
        echo $password;
    }

    public function getweeklyEarning(){    //for weekly earning 
        if(isset($_POST['driver_id']) && $_POST['driver_id']!=''){
            $data_val = array('driver_id','earningDate_start','earningDate_end');            
            $validation = $this->AbhiModel->param_validation($data_val,$_POST);
            if(isset($validation['status']) && $validation['status']=='0'){
                $response = array("success"=>0,"error"=>1,"message"=>$validation['message']); 
                echo json_encode($response);die();                 
            }
            else{
                extract($_POST);
                $thisWeekMonday   = date('d-m-Y',strtotime("previous monday").' 00:00');
                $thisWeekSunday   = date('d-m-Y',strtotime("next sunday").' 11:59 PM');
                $lastWeekMonday   = date('d-m-Y',strtotime('last monday -7 days').' 00:00');            
                $lastWeeksunday   = date('d-m-Y',strtotime("last monday -1 days").' 11:59 PM');
                $last2WeekMonday  = date('d-m-Y',strtotime('last monday -7 days').' 00:00');            
                $last2Weeksunday  = date('d-m-Y',strtotime("last monday -1 days").' 11:59 PM');

                $earningDatest = strtotime($earningDate_start.' 00:00');            
                $earningDatend = strtotime($earningDate_end.' 11:59 PM');

                $completeTrip  = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>4));
                $passengerWhere = '(booking_at_string>='.$earningDatest.' and booking_at_string<='.$earningDatend.' and driver_id='.$driver_id.' and (booking_status=3 or booking_status=7))'; 
                $passengerCancel = $this->AuthModel->checkRows('booking',$passengerWhere); 
                $driverCancel = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>2));
                $bookingCount = array("completedTrip"=>$completeTrip,"passengerCancel"=>$passengerCancel,'driverCancel'=>$driverCancel);
                    //======================================================================================================//
               
                $where = '(booking_at_string>='.$earningDatest.' and booking_at_string<='.$earningDatend.' and driver_id='.$driver_id.' and (booking_status=2 or booking_status=3 or booking_status=4 or booking_status=7))';                 
                $bookings = $this->AuthModel->getMultipleRecord('booking',$where,'booking_id DESC');

                $earningdata = array();
                if(!empty($bookings)){
                    foreach ($bookings as $key => $value){
                        $booking_id = $value->booking_id;    
                        $tripEarning = $this->BookingModel->getEarningInvoice($booking_id);
                       //echo json_encode($tripEarning);die();
                        if(!empty($tripEarning)){
                            $companyCommssion             = $this->AuthModel->getSingleRecord('company_booking_commission',array('booking_id'=>$booking_id));

                            $trip_fare = $tripEarning->base_fair+$tripEarning->mini_distance_fair+$tripEarning->total_regular_charge+$tripEarning->total_per_minute_charge;

                            //echo $total_fair;die();
                            //$trip_fare = $tripEarning->base_fair+$tripEarning->total_regular_charge+$tripEarning->total_per_minute_charge;
                            $res['booking_id']            = $booking_id;
                            $res['booking_at']            = $tripEarning->booking_at;  
                            $res['customer_id']           = $tripEarning->customer_id;
                            $res['driver_id']             = $tripEarning->driver_id;                                   
                            $res['service_id']            = $tripEarning->service_typeid;
                            $res['service_name']          = $tripEarning->servicename;
                            $res['service_image']         = base_url().'/serviceimage/'.$tripEarning->selected_image;
                            $res['payment_type']          = $tripEarning->payment_type;
                            $res['transaction_id']        = $tripEarning->transaction_id;
                            $res['trip_fare']             = $trip_fare;
                            $res['multi_address_charge']  = $tripEarning->multi_address_charge;
                            $res['total_surcharge']       = $tripEarning->total_surcharge;
                            $res['total_waiting_charge']  = $tripEarning->total_waiting_charge;
                            $res['total_fair']            = $tripEarning->total_fare;
                            $res['currency']              = $tripEarning->currency; 
                            $res['company_commission']    = '';
                            if(!empty($companyCommssion)) {
                                $res['company_commission'] = $companyCommssion->total_commission;
                            }
                            $earningdata[] = $res;
                        }                
                    }
                    $response = array('success'=>1,'error'=>0,'message'=>'Success','bookingCount'=>$bookingCount,'data'=>$earningdata);
                    echo json_encode($response); 
                }                   
                else{
                    $response = array('success'=>0,'error'=>1,'message'=>'No booking found','bookingCount'=>$bookingCount,'data'=>$earningdata);
                    echo json_encode($response); 
                }                
            }
        }
        else{
            $this->index();
        }
    }

    public function getCompleteBooking()
    { 
        // 1= today, 2=yesterday, 3=this week 4=last week, 5=this month
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['status']) && $_POST['status']!=''){
            extract($_POST);
            $todayst = strtotime(date('d-m-Y').' 00:00');
            //echo strtotime($todayst);
            $todaynd     = strtotime(date('d-m-Y').' 11:59 PM');
            $yesterdayst = strtotime(date('d-m-Y',strtotime("-1 days")).' 00:00'); 
            $yesterdaynd = strtotime(date('d-m-Y',strtotime("-1 days")).' 11:59 PM'); 
            $thisMonday  = strtotime(date('d-m-Y',strtotime("previous monday")).' 00:00');
            $thisSunday  = strtotime(date('d-m-Y',strtotime("next sunday")).' 11:59 PM');
            $lastWeekMonday = strtotime(date('d-m-Y',strtotime('last monday -7 days')).' 00:00');            
            $lastWeeksunday = strtotime(date('d-m-Y',strtotime("last monday -1 days")).' 11:59 PM');
            $firstDate      =  strtotime(date('01-m-Y').' 00:00');
            $lastDate       =  strtotime(date('t-m-Y').' 11:59 PM');
            //echo $firstDate;   
            $todayBooking = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$todayst,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id,'booking_status'=>4));          
            $yesterdayBooking = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$yesterdayst,'booking_at_string<='=>$yesterdaynd,'driver_id'=>$driver_id,'booking_status'=>4)); 
            $thisweekBooking =  $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$thisMonday,'booking_at_string<='=>$thisSunday,'driver_id'=>$driver_id,'booking_status'=>4));        
            $lastweekBooking =  $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$lastWeekMonday,'booking_at_string<='=>$lastWeeksunday,'driver_id'=>$driver_id,'booking_status'=>4));        
            $thisMonthBooking = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$firstDate,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id,'booking_status'=>4));
            $bookingcount = array('todaybooking'=>$todayBooking,'yesterdayBooking'=>$yesterdayBooking,'thisweekBooking'=>$thisweekBooking,'lastweekBooking'=>$lastweekBooking,'thisMonthBooking'=>$thisMonthBooking);
            if($status==1){
                $where = array('booking_at_string>='=>$todayst,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status'=>4);
            }
            elseif($status==2){     //2=yesterday 
                $where = array('booking_at_string>='=>$yesterdayst,'booking_at_string<='=>$yesterdaynd,'driver_id'=>$driver_id,'booking_status'=>4);             
            }
            elseif($status==3){     //3=this week    
                $where = array('booking_at_string>='=>$thisMonday,'booking_at_string<='=>$thisSunday,'driver_id'=>$driver_id,'booking_status'=>4);
            }
            elseif($status==4){      //4=last week 
                $where = array('booking_at_string>='=>$lastWeekMonday,'booking_at_string<='=>$lastWeeksunday,'driver_id'=>$driver_id,'booking_status'=>4);            
            }
            elseif($status==5){      //5=this month  
                $where = array('booking_at_string>='=>$firstDate,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id,'booking_status'=>4);
            }
            elseif($status==6){      //6=last 30 days 
                $last30 = strtotime(date('d-m-Y',strtotime("-30 days")).' 00:00');                 
                $where = array('booking_at_string>='=>$last30,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status'=>4);
            }
            elseif($status==7){      //7=last 60 days  
                $last60 = strtotime(date('d-m-Y',strtotime("-60 days")).' 00:00'); 
                $where = array('booking_at_string>='=>$last60,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status'=>4);
            }
            elseif($status==8){      //8=last 90 days  
                $last90 = strtotime(date('d-m-Y',strtotime("-90 days")).' 00:00'); 
                $where = array('booking_at_string>='=>$last90,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status'=>4);
            }
            elseif($status==9){      //9=custom  
                $data_val = array('driver_id','status','from_date','to_date');
                $validation = $this->AbhiModel->param_validation($data_val,$_POST);
                if(isset($validation['status']) && $validation['status']=='0'){
                    $response = array('success'=>0,'error'=>1,'message'=>$validation['message']);
                    echo json_encode($response);die();                    
                }
                else{
                    $from =  strtotime($from_date.' 00:00');
                    $to   =  strtotime($to_date.' 11:59 PM');                                       
                    $where = array('booking_at_string>='=>$from,'booking_at_string<='=>$to,'driver_id'=>$driver_id,'booking_status'=>4);    
                }                
            }
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'invalid request');
                echo json_encode($response);die();
            }            
            $orderby = 'booking_id DESC';
            $response1=$this->AuthModel->getMultipleRecord('booking',$where,$orderby);
            //print($this->db->last_query());die();
            if(!empty($response1)){
                foreach($response1 as $deliver){
                    $service_type = $this->AuthModel->getSingleRecord('servicetype',array('typeid'=>$deliver->service_typeid));
                    $response['booking_id']=$deliver->booking_id;
                    $response['driver_id']  = $deliver->driver_id;
                    $response['customer_id']  = $deliver->customer_id;
                    $response['booking_status']=$deliver->booking_status;
                    $response['ride_complete_at']=$deliver->ride_complete_at;
                    $response['payment_type']=$deliver->payment_type;
                    $response['total_fare']=$deliver->total_fare;
                    $response['currency']=$deliver->currency;
                    $response['booking_at']=$deliver->booking_at;
                    $service = $this->AuthModel->getSingleRecord('servicetype',array('typeid'=>$deliver->service_typeid));
                    if(!empty($service)){
                        $response['service_typeid']  = $service->typeid;
                        $response['servicename']     = $service->servicename;
                        $response['selected_image']  = base_url().'serviceimage/'.$service->selected_image;                    
                    }
                    else{
                        $response['service_typeid']  = '';
                        $response['servicename']     = '';
                        $response['selected_image']  = ''; 
                    }                    
                    $response['pickup']=$deliver->pickup;
                    $dropoffs = $this->AuthModel->getMultipleRecord('booking_dropoffs',array("booking_id"=>$deliver->booking_id),"");
                    $response['dropoff'] = $dropoffs;
                    $responsedata[]=$response;
                }
                $response = array('error'=>0,'success'=>1,'message'=>'success','bookingcount'=>$bookingcount,'data'=>$responsedata);
                echo json_encode($response);
            }
            else
            {
                $response = array('error'=>1,'success'=>0,'message'=>'No booking found','bookingcount'=>$bookingcount,'data'=>$response1);
                echo json_encode($response);
            }
        }   
        else{
            $this->index();
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

    public function checkEmailTemplate(){
        $res = $this->AuthModel->getSingleRecord('users',array('email'=>'shubhamj@gmail.com','user_type'=>0));
        $data = array('email'=>$res->email,'name'=>$res->name,'user_type'=>$res->user_type);        
        $this->load->view('forget_passwordTemp',$data);
    }
}


