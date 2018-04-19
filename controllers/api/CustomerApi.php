<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CustomerApi extends CI_Controller {
	function __construct() {
        parent::__construct();   
        $this->load->model('BookingModel');
    }

    public function index()
    {
        $respose["success"] = 0;
        $respose["error"]=400;
        $respose["message"]="Access Denied";
        echo json_encode($respose);
    }

    public function nearbyDriver()
    {
        if(isset($_POST['current_lat']) && $_POST['current_lat']!='' && isset($_POST['current_long']) && $_POST['current_long']!='' )
        {
            extract($_POST);
            $this->AuthModel->getNearbyDriver($current_lat,$current_long);
        }
        else
        {
            $this->index();
        }
    }

    public function BookDriver()
    {
        $rawPostData    = file_get_contents('php://input');
        $jsonData       = json_decode($rawPostData,true);
        //print_r($jsonData);die();
        if(!empty($jsonData) && !empty($jsonData['dropoff']))
        {
            //customer_id,address_type(single,multiple),fromaddress,fromLat,fromLong,toaddress,toLat,toLong,service_type_id, date,time,booking_type(now,later),country,city
            $customer_id        = $jsonData['customer_id'];
            $country            = $jsonData['country'];
            $city_name          = $jsonData['city_name'];
            $service_type_id    = $jsonData['service_type_id'];
            $booking_address_type = $jsonData['booking_address_type'];  //Single Multiple
            $pickup             = $jsonData['pickup'];
            $pickupLat          = $jsonData['pickupLat'];
            $pickupLong         = $jsonData['pickupLong'];
            $dropoff            = $jsonData['dropoff'];
            $date               = $jsonData['date'];
            $time               = $jsonData['time'];
            $booking_type       = $jsonData['booking_type'];     //(now,later) 
            $booking_note       = $jsonData['booking_note'];
            $payment_type       = $jsonData['payment_type'];    //cash,paypal,citipay
            $fairDetails        = $this->AuthModel->getSingleRecord('fair',array("country"=>$country,"city"=>$city_name));  //check service available in this city or not
            if(!empty($fairDetails))
            {                               
                $nearbyDriver = $this->BookingModel->searchDriver($pickupLat,$pickupLong,$date,$time);  
                if(!empty($nearbyDriver))
                {
                    $driver_id = $nearbyDriver->id;                     
                    $bookingData =  array(
                            "customer_id"=>$customer_id,
                            "driver_id"=>$driver_id,
                            "service_typeid"=>$service_type_id,
                            "booking_address_type"=>$booking_address_type,
                            "pickup"=>$pickup,
                            "pickupLat"=>$pickupLat,
                            "pickupLong"=>$pickupLong,
                            "booking_note"=>$booking_note,
                            "booking_at" =>$date.' '.$time,
                            "booking_type"=>$booking_type,
                            "payment_type"=>$payment_type
                        );
                    if($booking_id = $this->AuthModel->singleInsert('booking',$bookingData))
                    {
                        $dropoffs=[];
                        foreach($dropoff as $k =>$v)
                        {
                            $c["booking_id"]    =  $booking_id;
                            $c["dropoff"]       =  $v['dropoff'];
                            $c["dropoffLat"]    =  $v['dropoffLat'];
                            $c["dropoffLong"]   =  $v['dropoffLong'];
                            $dropoffs[]=$c;
                        } 
                        if($this->AuthModel->batchInsert('booking_dropoffs',$dropoffs))
                        {
                            $this->saveFairDetails($fairDetails,$booking_id,$date,$time,$booking_address_type);   //save fair details
                            // $this->AuthModel->updateRecord(array("id"=>$driver_id),'users',array("online_status"=>'offline'));
                            $response = array('success'=>1,'error'=>0,'message'=>'Booking successfull','booking_id'=>$booking_id);
                            echo json_encode($response);
                        }
                        else{
                            $this->AuthModel->delete_record('booking',array('booking_id'=>$booking_id));
                            $response = array('success'=>0,'error'=>1,'message'=>'Oops! something went wrong, please try again');
                            echo json_encode($response);                            
                        }
                    }
                    else
                    {
                        $response = array("success"=>0,"error"=>1,"message"=>"Oops! something went wrong, please try again");
                        echo json_encode($response);
                    }
                }
                else
                {
                    $response = array("success"=>0,"error"=>1,"message"=>"Sorry! No driver found at your pickup location");
                    echo json_encode($response);
                }
            } 
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'Please change location.');
                echo json_encode($response);
            } 
        }
        else
        {
            $this->index();
        }
    }

    public function saveFairDetails($fairDetails,$booking_id,$bookdate,$booktime,$booking_address_type)
    {
        $time       = strtotime($bookdate.' '.$booktime);
        $fairdata = array(
            "booking_id"                =>$booking_id,
            "currency"                  =>$fairDetails->currency,            
            "base_fair"                 =>$fairDetails->minbase_fair,
            "mini_distance_fair"        =>$fairDetails->mini_distancefair,
            "regular_charge_distance"   =>$fairDetails->regularChargeEveryDistance,
            "regular_distance_charge"   =>$fairDetails->regularChargeForDistance,
            "per_minute"                =>$fairDetails->unitPerMinuteforCharge,
            "per_minute_charge"         =>$fairDetails->unitPerMinutecharge,
            "free_waiting_minute"       =>$fairDetails->regularFreeWaitingMinute,
            "paid_every_waiting_minute" =>$fairDetails->regularWaitingPeriodForCharge,
            "every_waiting_minute_charge"=>$fairDetails->regularWaitingPeriodCharge,
            );
            if($booking_address_type=='Multiple')
            {
                $fairdata["multi_address_charge"]=$fairDetails->multiStopCharge;
            }
            if($fairDetails->morningChargeStatus=='on')
            {   
                $starttime  = strtotime($bookdate.' '.$fairDetails->morningSurchargeTimeStart);
                $endtime    = strtotime($bookdate.' '.$fairDetails->morningSurchargeTimeEnd);
                if($time>$starttime && $time<$endtime)
                {
                    $fairdata["morning_surcharge_unit"] =$fairDetails->morningSurchargeUnit;
                    $fairdata["morning_surcharge"]=$morningSurchargePrice;                    
                }                
            }
            if($fairDetails->eveningChargeStatus=='on')
            {
                $starttime  = strtotime($bookdate.' '.$fairDetails->eveningSurchargeTimeStart);
                $endtime    = strtotime($bookdate.' '.$fairDetails->eveningSurchargeTimeEnd);
                if($time>$starttime && $time<$endtime)
                {                   
                    $fairdata["evening_surcharge_unit"]=$fairDetails->eveningSurchargeUnit;
                    $fairdata["evening_surcharge"]=$fairDetails->eveningSurchargePrice;                    
                }
            }
            if($fairDetails->midNightChargeStatus=='on')
            {
                $starttime  = strtotime($bookdate.' '.$fairDetails->midNightSurchargeTimeStart);
                $endtime    = strtotime("+1 day",strtotime($bookdate.' '.$fairDetails->midNightSurchargeTimeEnd));
                if($time>$starttime && $time<$endtime)
                {
                    $fairdata["midnight_surcharge_unit"]=$fairDetails->midNightSurchargeUnit;
                    $fairdata["midnight_surcharge"]=$fairDetails->midNightSurchargeUnit;                   
                }
            }
        $this->AuthModel->singleInsert('booking_fair',$fairdata);
    }




   

    public function get_location($lat,$long)
    {
        //echo $lat;die();
        $geocode=file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBzXwf4iAm2V2oFxm81vczA__A0XXq53O8&latlng='.$lat.','.$long.'&sensor=true');
        $output= json_decode($geocode);
        echo '<pre>';
        print_r($output);
        $country_name = $output->results[4]->address_components[3]->long_name;
        $city_name = $output->results[4]->address_components[0]->long_name;   
        $fairlocation = array('country_name'=>$country_name,'city_name'=>$city_name);
        print_r($fairlocation);
        if($city_name!='')
        {     

            //$this->AuthModel->getSingleRecord('fair',())
            //return  $city_name;
        }     
        else
        {
            $respose = array('success'=>0,'error'=>1,'message'=>'Oops Somethign went wrong.City name does not found');
            exit;
        }
    }


    
    


//===============================================================================================================================//


   




    

}