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

	public function cronjobForCheckLaterBooking()
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

				if($pickAt==$endSearch) //If pickup time == endsearch time or greater 
				{
					$this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('booking_status'=>9)); //Assign to Admin
				}				
				else
				{
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
	}

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
}