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

    public function getTravellingTime()
    {
        $res = $this->BookingModel->GetDrivingDistanceAndTime($lat1, $lat2, $long1, $long2);
        print_r($res);
    }

    public function GetNearByDriver()
    {
        if(isset($_POST['service_type_id']) && $_POST['service_type_id']!='')
        {
            extract($_POST);            
            foreach ($_POST as $key => $value) {
                if($value=='')
                {
                    $response = array("success"=>0,"error"=>1,"message"=>"All field must be filled");
                    echo json_encode($response);exit();
                }
            }            
            $drivers = $this->BookingModel->searchNearByDriver($current_lat,$current_long,$date,$time,$service_type_id);
            if(!empty($drivers))
            {
                $response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$drivers);
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"No driver available","data"=>$drivers);
                echo json_encode($response);
            }
        }
        else
        {
            $this->index();
        }
    }

    public function getServiceFairDetails()
    {
        if(isset($_POST['service_type_id']) && $_POST['service_type_id']!='' && isset($_POST['city']) && $_POST['city']!='')
        {
            extract($_POST);
            $where = array("serviceType_id"=>$service_type_id,"country"=>$country,"city"=>$city);
            $fairDetail = $this->AuthModel->getSingleRecord('fare',$where);
            if(!empty($fairDetail))
            {
                $response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$fairDetail);
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Fair details is not found for this city","data"=>'');
                echo json_encode($response);
            }
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
            $customer_id            = $jsonData['customer_id'];
            $country                = $jsonData['country'];
            $city_name              = $jsonData['city_name'];
            $service_type_id        = $jsonData['service_type_id'];
            $booking_address_type   = $jsonData['booking_address_type'];  //Single Multiple
            $pickup                 = $jsonData['pickup'];
            $pickupLat              = $jsonData['pickupLat'];
            $pickupLong             = $jsonData['pickupLong'];
            $dropoff                = $jsonData['dropoff'];
            $date                   = $jsonData['date'];
            $time                   = $jsonData['time'];
            $total_ride_time        = $jsonData['total_ride_time'];
            $total_ride_distance    = $jsonData['total_ride_distance'];
            $total_regular_charge   = $jsonData['total_regular_charge'];
            $total_perminute_charge = $jsonData['total_perminute_charge'];
            $total_fair             = $jsonData['total_fair'];
            $booking_type           = $jsonData['booking_type'];     //(now,later) 
            $booking_note           = $jsonData['booking_note'];
            $payment_type           = $jsonData['payment_type'];    //cash,paypal,citipay

            $fairDetails            = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$service_type_id,"country"=>$country,"city"=>$city_name));  
            //check service available in this city or not
            if(!empty($fairDetails))
            {                               
                $nearbyDriver = $this->BookingModel->searchDriver($pickupLat,$pickupLong,$date,$time,$service_type_id);  
                //echo json_encode($nearbyDriver);die();
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
                            "total_ride_time"=>$total_ride_time,
                            "total_distance"=>$total_ride_distance,
                            "distance_unit"=>$fairDetails->distanceUnit,
                            "total_fare"=>$total_fair,
                            "currency"=>$fairDetails->currency,
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
                            $this->saveFairDetails($fairDetails,$booking_id,$date,$time,$booking_address_type,$total_regular_charge,$total_perminute_charge);   //save fair details

                            $this->AuthModel->updateRecord(array("id"=>$driver_id),'users',array("online_status"=>'offline'));
                            //Change online status so other request will not get.

                            $vehicle = $this->AuthModel->getSingleRecord('vechile_details',array("driver_id"=>$driver_id));//get vehicle details
                            $resdata = $this->AuthModel->keychange($nearbyDriver);
                            $tripData = array(  
                                                "booking_id"=>$booking_id,
                                                "driver_id"=>$driver_id,
                                                "driver_image"=>$resdata->image,
                                                "driver_name"=>$resdata->name,
                                                "driver_mobile"=>$resdata->mobile,
                                                "service_typeid"=>$resdata->typeid,
                                                "service_name"=>$resdata->servicename,
                                                "vehicle_id"=>$vehicle->vechileId,
                                                "vehicle_name"=>$vehicle->brand.' '.$vehicle->sub_brand,
                                                "vehicle_no"=>$vehicle->number_plate,                                                
                                                "liveaddress"=>$resdata->liveaddress,                                                
                                                "livelatitude"=>$resdata->latitude,                                                
                                                "livelongitude"=>$resdata->longitude,
                                                "pickup"=>$pickup,
                                                "pickupLat"=>$pickupLat,
                                                "pickupLong"=>$pickupLong,                                              
                                            );
                            $response = array('success'=>1,'error'=>0,'message'=>'Booking successfull',"data"=>$tripData);
                            echo json_encode($response);
                        }
                        else{
                            $this->AuthModel->delete_record('booking',array('booking_id'=>$booking_id));
                            $response = array('success'=>0,'error'=>1,'message'=>'Oops! something went wrong, please try again',"data"=>'');
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
    
    public function saveFairDetails($fairDetails,$booking_id,$bookdate,$booktime,$booking_address_type,$total_regular_charge,$total_perminute_charge)
    {
        $time       = strtotime($bookdate.' '.$booktime);
        $fairdata = array(
            "booking_id"                =>$booking_id,
            "currency"                  =>$fairDetails->currency,            
            "base_fair"                 =>$fairDetails->minbase_fair,
            "mini_distance_fair"        =>$fairDetails->mini_distancefair,
            "regular_charge_distance"   =>$fairDetails->regularChargeEveryDistance,
            "regular_distance_charge"   =>$fairDetails->regularChargeForDistance,
            "total_regular_charge"      =>$total_regular_charge,            
            "free_waiting_minute"       =>$fairDetails->regularFreeWaitingMinute,
            "paid_every_waiting_minute" =>$fairDetails->regularWaitingPeriodForCharge,
            "every_waiting_minute_charge"=>$fairDetails->regularWaitingPeriodCharge,
            );
            if($fairDetails->perMinChargeStatus=='on')
            {
                $fairdata["per_minute"]  =$fairDetails->unitPerMinuteforCharge;
                $fairdata["per_minute_charge"] = $fairDetails->unitPerMinutecharge;
                $fairdata["total_per_minute_charge"]=$total_perminute_charge;
            }
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
        $this->AuthModel->singleInsert('booking_fare',$fairdata);
    }   

//===============================================================================================================================//
    /*public function get_location($lat,$long)
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

            //$this->AuthModel->getSingleRecord('fare',())
            //return  $city_name;
        }     
        else
        {
            $respose = array('success'=>0,'error'=>1,'message'=>'Oops Somethign went wrong.City name does not found');
            exit;
        }
    }*/


    
    




   




    

}