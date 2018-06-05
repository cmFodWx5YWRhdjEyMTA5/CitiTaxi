<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advance extends CI_Controller {
	function __construct() {
        parent::__construct();
        //$this->load->helper(form,url);
        $this->load->model("AuthModel");         
        date_default_timezone_set('Asia/kolkata');
    }

    public function index()
	{
		$respose["success"] = 0;
		$respose["error"]=400;
		$respose["message"]="Access Denied";
		echo json_encode($respose);
	}

	public function cronjobToCheckLaterBooking()
	{
		//echo strtotime('+55 minutes', strtotime(date('d-m-Y h:i A')));
		//echo date('d-m-Y h:i A',strtotime('+65 minutes', strtotime(date('d-m-Y h:i A'))));

		//Important note =Search near by driver When advance booking time is less than 1hr and grater then 45 min otherwise send information to Major admin		
		$startSearch         = strtotime('+60 minutes', strtotime(date('d-m-Y h:i A')));   //add 60 minute 
		$endSearch           = strtotime('+25 minutes', strtotime(date('d-m-Y h:i A')));   //add 25 minute 
		$endDriverSearch     = strtotime('+45 minutes', strtotime(date('d-m-Y h:i A')));   //add 45 minute 
		$rejectTime          = strtotime('+30 minutes', strtotime(date('d-m-Y h:i A')));   //add 30 minute 

		//echo strtotime('+40 minutes', strtotime(date('d-m-Y h:i A')));   //add 30 minute 
		//echo date('d-m-Y h:i A',strtotime('+40 minutes', strtotime(date('d-m-Y h:i A'))));   //add 30 minute 
		//die();
		//$startSearch   = strtotime('+60 minutes', strtotime(date('d-m-Y h:i A')));   //add 60 minute 
		//$endSearch     = strtotime('+40 minutes', strtotime(date('d-m-Y h:i A')));   //add 45 minute
		//echo $startSearch.'<br>'; 
		//echo $endSearch.'<br>';
		$where ='(booking_type="later" and later_pickup_string<='.$startSearch.' and later_pickup_string>='.$endSearch.' and (booking_status=0 or booking_status=8 or booking_status=9))';
		$pendingBooking = $this->AuthModel->getMultipleRecord('booking',$where,'');
		//print_r($this->db->last_query());
		//echo '<pre>';
		//print_r($pendingBooking);die();
		if(!empty($pendingBooking))
		{
			foreach ($pendingBooking as $later){
				$pickupLat    = $later->pickupLat;
				$pickupLong   = $later->pickupLong;				
				$country      = $later->country;				
				$city         = $later->city;								
				$service_type_id = $later->service_typeid;
				$pickAt 	  = $later->later_pickup_at;
				$getdate = explode(' ',$pickAt);				
				$date    = $getdate[0];
				$time    = $getdate[1].' '.$getdate[2];
				//echo $time;die();
				$pickstring   = $later->later_pickup_string;							
				$booking_id   = $later->booking_id;
				$total_fair   =	$later->total_fare;
				$customer_id  = $later->customer_id;
				//echo $pickstring.'<br>';
				//echo $endDriverSearch.'<br>';
				//echo $startSearch;die();
				//if later booking time is between 1hr to 45 min
					//1527923700>=1527924720           1527923700<=1527925620  
				if($pickstring>=$endDriverSearch && $pickstring<=$startSearch){  //search driver to assign booking
					//echo '1st';die();
					$nearbyDriver = '';
                    $allDriverNearBy  = $this->BookingModel->searchNearByDriver($pickupLat,$pickupLong,$date,$time,$service_type_id);
                    //print_r($this->db->last_query());
                    //print_r($allDriverNearBy);die();
                    if(!empty($allDriverNearBy))
                    {
                    	$fairDetails            = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$service_type_id,"country"=>$country,"city"=>$city));  

                        foreach ($allDriverNearBy as $near) {
                            $driver = $near->user_id;        
                        	//To check driver wallet balance if balnce greater then cancel charge then assign request                    
                            $driver_cancelUnit   =  $fairDetails->cancelChargeUnitDriver;
                            $driver_cancelCharge =  $fairDetails->stndCancelChargeDriver;

                            if($driver_cancelUnit=='Per'){
                                $driver_cancelCharge = ($total_fair*$driver_cancelCharge)/100;
                            }
                            //echo $driver_cancelCharge;die();
                            $nearly  = $this->BookingModel->searchDriver($pickupLat,$pickupLong,$date,$time,$service_type_id,$driver,$driver_cancelCharge);
                            //echo json_encode($nearbyDriver);die();
                            if(!empty($nearly)){                                                        
                                $driver_id = $nearly->id;                                                                      
                                //print_r($this->db->last_query());die();
                                if($nearly->destination_status=='on'){   
                                    //echo $driver_id;
                                    if($this->checkDriverDestinations($driver_id,$dropoff)){ 
                                        $nearbyDriver = $nearly;
                                        goto getfinaldriver;
                                        break;
                                        //$nearbyDriver = $nearbyDriver;//echo $driver_id;
                                        //exit();
                                    }                                    
                                }
                                else{
                                    $nearbyDriver = $nearly;
                                }
                            }                            
                        }//echo 'mil gya'.$driver_id;
                        getfinaldriver: 
                        $driver_id = $nearbyDriver->id;		
						$this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('booking_status'=>0,'driver_id'=>$driver_id)); //Assign to driver

						if($this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('driver_id'=>$driver_id,'booking_status'=>0))){
				            //$this->CustomerApi->sendNotificationForLaterbooking($booking_id,$driver_id);				            
				        }
                    }
                }
                //if later booking time is less then 45 min and driver is not found then assign to admin                
				elseif($pickstring>=$rejectTime && $pickstring<=$endDriverSearch){ 
					//echo '2nd';die();
					$this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('booking_status'=>9)); //Assign to Admin
				}	
				//if admin will not assigned booking to driver and booking time less then or equal to 30 min then booking will cancel
				elseif($pickstring>=$endSearch && $pickstring<=$rejectTime){ 
					//echo '3rd';die();
					$this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('booking_status'=>2,'cancel_reason'=>'driver is not found at advance pickup time','cancel_at'=>date('d-m-Y h:i A')));
					$message = 'Sorry! We are unable to assign driver for you at your pickup time. Please try Ride now before your trip time.';
					//$this->Communication_model->sendToPassenger($customer_id,$message);
				}													
			}			
		}
		else{
			echo 'No booking within time intervel';
		}		
		//print_r($pendingBooking);die();
	}

	public function checkDriverDestinations($driver_id,$dropoff)
    {
        $des = $this->AuthModel->getSingleRecord('driver_destination',array('driver_id'=>$driver_id));
        if(!empty($des))
        {
            $destinationLat = $des->destination_lat;
            $destinationLng = $des->destination_lng;    
            //print_r($dropoff);die();  
            if(count($dropoff>0)) //if multiple address then check far distance address
            {
                foreach($dropoff as $k =>$v)
                {  
                    $dropoffLat    = $v['dropoffLat'];
                    $dropoffLong   = $v['dropoffLong'];
                    $dist   = $this->BookingModel->distance($destinationLat,$destinationLng,$dropoffLat,$dropoffLong,'k');
                    //$dist   = $this->BookingModel->searchDriverDestination($dropoffLat,$dropoffLong,$driver_id);
                    //echo json_encode($dist);die();
                    //$dist         = $this->BookingModel->GetDrivingDistance($destinationLat,$dropoffLat,$destinationLng,$dropoffLong);
                    //if(!empty($dist)){
                        $r['dist']     = $dist;
                        $r['positon']  = $k; 
                        $r['droplat']      = $dropoffLat; 
                        $r['droplng']      = $dropoffLong; 
                        $r['destLat']      =  $destinationLat;       
                        $r['destLng']      =  $destinationLng;       
                        $dropoffs[]    = $r;
                    //}
                }
                //print_r($dropoffs);die();            
                //print_r($dropoffs);die();
                if(!empty($dropoffs)){
                    $maxDropIndex    = array_search(max($dropoffs),$dropoffs); //get longest address index
                    //echo $maxDropIndex;die();
                    $dropoffLat      = $dropoff[$maxDropIndex]['dropoffLat'];
                    $dropoffLng      = $dropoff[$maxDropIndex]['dropoffLong'];                               
                }
                else{
                    $dropoffLat      = $dropoff[0]['dropoffLat'];
                    $dropoffLng      = $dropoff[0]['dropoffLong'];
                }
            }
            else{
                $dropoffLat      = $dropoff[0]['dropoffLat'];
                $dropoffLng      = $dropoff[0]['dropoffLong'];            
            }
            if($this->BookingModel->searchDriverDestination($dropoffLat,$dropoffLng,$driver_id)){            
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function sendNotificationForLaterbooking($booking_id,$driver_id)
    {
        $bookingdata = $this->AuthModel->getSingleRecord('booking',array('booking_id'=>$booking_id));
        if(!empty($bookingdata))
        {
            $driver_id    = $bookingdata->driver_id;
            $customer_id  = $bookingdata->customer_id;
            $customer_msg = "Your Booking id ".$booking_id." has been assgned to driver";
            $driver_msg   = "New Later booking request for you";
            $this->Communication_model->sendToDriver($driver_id,$driver_msg);  
            $this->Communication_model->sendToPassenger($customer_id,$customer_msg);                   
        }        
    }




	/*public function cronjobForCheckLaterBooking()
	{
		//Important note =Search near by driver When advance booking time is less than 1hr and grater then 45 min otherwise send information to Major admin

		$startSearch   = date('d-m-Y h:i A',strtotime('+60 minutes', strtotime(date('d-m-Y h:i A'))));   //add 60 minute 
		$endSearch     = date('d-m-Y h:i A',strtotime('+40 minutes', strtotime(date('d-m-Y h:i A'))));   //add 45 minute 	
		//$startSearch   = strtotime('+60 minutes', strtotime(date('d-m-Y h:i A')));   //add 60 minute 
		//$endSearch     = strtotime('+40 minutes', strtotime(date('d-m-Y h:i A')));   //add 45 minute
		echo $startSearch.'<br>'; 
		echo $endSearch.'<br>';	

		$pendingBooking = $this->AuthModel->getMultipleRecord('booking',array('booking_status'=>8,'later_pickup_at<='=>$startSearch,'later_pickup_at>='=>$endSearch),'');
		//print_r($this->db->last_query());
		//echo '<pre>';
		//print_r($pendingBooking);die();
		if(!empty($pendingBooking))
		{
			foreach ($pendingBooking as $later){
				$pickupLat    = $later->pickupLat;
				$pickupLong   = $later->pickupLong;				
				$service_type_id = $later->service_typeid;
				$pickAt 	  = $later->later_pickup_at;
				$pick     	  =	explode(' ',$pickAt);				
				$date 	      =	$pick[0];
				$time         = $pick[1].' '.$pick[2];								
				$booking_id   = $later->booking_id;			

				if($pickAt==$endSearch){ //If pickup time == endsearch time or greater 				
					$this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('booking_status'=>9)); //Assign to Admin
				}				
				else{
					$nearbyDriver = $this->BookingModel->searchDriver($pickupLat,$pickupLong,$date,$time,$service_type_id);
					if(!empty($nearbyDriver)){
						$driver_id = $nearbyDriver->id;		
						$this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('booking_status'=>0,'driver_id'=>$driver_id)); //Assign to driver								
					}									
				}
			}			
		}
		else{
			echo 'No booking withing time intervel';
		}
		echo "<pre>";
		//print_r($pendingBooking);die();
	}*/

	

	public function checkTime()
	{		
		//echo strtotime('04-05-2018 06:10 PM');
		$pickAt  = '05-05-2018 00:11 AM';
		$sStart  = '04-05-2018 11:50 PM';
		$sEnd    = '05-05-2018 00:10 AM'; 

		if($pickAt==$sEnd OR $sEnd<$pickAt)
		{
			echo 'For admin';
		}
		else
		{
			echo 'For pickup';
		}

		$startSearch   = strtotime('+60 minutes', strtotime(date('d-m-Y h:i A')));   //add 60 minute 
		$endSearch     = strtotime('+40 minutes', strtotime(date('d-m-Y h:i A')));   //add 45 minute 	
		//echo date('d-m-Y h:i',$startSearch).' '.$startSearch.'<br>';
		//echo date('d-m-Y h:i',$endSearch).' '.$endSearch.'<br>';
	}

	public function suspendToactive_users()
	{
		$today = date('d-m-Y');
		echo $today;
		$list = $this->AuthModel->getMultipleRecord('useraction',array('to'=>$today),'');
		if(!empty($list)){
			foreach ($list as $k => $l) {
				$user_id = $l->user_id;				
				$this->AuthModel->updateRecord(array('id'=>$user_id),'users',array('activeStatus'=>'Active','suspend_type'=>''));
				$this->AuthModel->delete_record('useraction',array('user_id'=>$user_id));
			}
		}
	}

	public function suspendToactive_managers()
	{
		$today = date('d-m-Y');
		$list = $this->AuthModel->getMultipleRecord('walletmanageraction',array('to'=>$today),'');
		if(!empty($list)){
			foreach ($list as $k => $l) {
				$manager_id = $l->manager_id;				
				$this->AuthModel->updateRecord(array('manager_id'=>$manager_id),'wallet_manager',array('activeStatus'=>'Active','suspend_type'=>''));
				$this->AuthModel->delete_record('walletmanageraction',array('manager_id'=>$manager_id));
			}
		}
	}
}