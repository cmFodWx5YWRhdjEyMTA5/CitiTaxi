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
                    $booking_at = $date.' '.$time;                   
                    $bookingData =  array(
                            "customer_id"=>$customer_id,
                            "driver_id"=>$driver_id,
                            "service_typeid"=>$service_type_id,
                            "country"=>$country,
                            "city"=>$city_name,
                            "booking_address_type"=>$booking_address_type,
                            "pickup"=>$pickup,
                            "pickupLat"=>$pickupLat,
                            "pickupLong"=>$pickupLong,
                            "booking_note"=>$booking_note,
                            "booking_at" =>$booking_at,
                            "booking_at_string"=>strtotime($booking_at),
                            "booking_type"=>$booking_type,
                            "total_ride_time"=>$total_ride_time,
                            "total_distance"=>$total_ride_distance,
                            "distance_unit"=>$fairDetails->distanceUnit,
                            "total_fare"=>$total_fair,
                            "currency"=>$fairDetails->currency,
                            "payment_type"=>$payment_type,                           
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
                            $commissionData = array("booking_id"=>$booking_id,"driver_id"=>$driver_id,"commission_type"=>$fairDetails->company_comission_type,"commission_rate"=>$fairDetails->company_comission_rate,"commission_at"=>$date.' '.$time);
                            $this->AuthModel->singleInsert('company_booking_commission',$commissionData);                       

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
            "mini_distance"             =>$fairDetails->min_distance,
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

    public function getTripInvoice()
    {
        if(isset($_POST['booking_id']) && $_POST['booking_id']!='')
        {
            extract($_POST);
            $tripReceipt = $this->BookingModel->getTripInvoice($booking_id);
            //echo json_encode($tripReceipt);die();
            if(!empty($tripReceipt))
            {
                $dropoffs = $this->AuthModel->getMultipleRecord('booking_dropoffs',array("booking_id"=>$booking_id),"");
                $tripdata = $this->AuthModel->keychange($tripReceipt);
                $response = array("success"=>1,"error"=>0,"message"=>"success","tripReceipt"=>$tripdata,"dropoffs"=>$dropoffs);
                echo json_encode($response); 
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Booking details is not found");
                echo json_encode($response);
            }
        }
        else
        {
            $this->index();
        }
    }

    public function countWeekCanceBooking($user_id,$booking_status)  //this week cancelled
    {      
        $weekStart  = date('d-m-Y',strtotime( "previous sunday"));
        $weekEnd    = date('d-m-Y',strtotime( "next saturday" ));
        $where      = array('booking_at>='=>$weekStart,'booking_at<='=>$weekEnd,'booking_status'=>$booking_status,'customer_id'=>$user_id);
        $weeklyCancel = $this->AuthModel->checkRows('booking',$where);
        return  $weeklyCancel;
    }

    public function BookingRejectByCustomer()
    {
        if(isset($_POST['booking_id']) && $_POST['booking_id'] && isset($_POST['customer_id']) && $_POST['customer_id']!='')
        {            
            extract($_POST);  //booking_id,customer_id
            $booking = $this->AuthModel->getSingleRecord('booking',array("booking_id"=>$booking_id,'customer_id'=>$customer_id));  //get booking data            
            if(!empty($booking))
            {
                $typeid = $booking->service_typeid;  $country = $booking->country;
                $city   = $booking->city;
                //check cencelation limit
                $limit = $this->AuthModel->getSingleRecord('fare',array('serviceType_id'=>$typeid,'country'=>$country,'city'=>$city));
                $cancel_limit = $limit->WeeklyCancellationLimit;
                //$cancel_limit = 2;

                $score = $this->AuthModel->getSingleRecord('users_score',array("user_id"=>$customer_id)); //get previous score

                if($booking->booking_status==0)   //cancel before accept
                {  
                    $updata = array("booking_status"=>7,"cancel_reason"=>$cancel_reason); 
                    $thisWeekCancels = $this->countWeekCanceBooking($customer_id,7);
                    //$thisWeekCancels = 1;
                    $newCancelCount  = $thisWeekCancels+1; //New total canel this week
                    $preCancel       = $score->total_cancel_before_accept;
                    $newCancel       = $preCancel+1;                    
                    $upscoreData     = array('total_cancel_before_accept'=>$newCancel);                 
                }
                else{                    
                    $updata          = array("booking_status"=>3,"cancel_reason"=>$cancel_reason);
                    $thisWeekCancels = $this->countWeekCanceBooking($customer_id,3);
                    //$thisWeekCancels = 1;
                    $newCancelCount  = $thisWeekCancels+1;  //New total canel this week
                    $preCancel       = $score->total_cancel_after_accept;
                    $newCancel       = $preCancel+1; 
                    $upscoreData     = array('total_cancel_after_accept'=>$newCancel); 
                }
                
                if($this->AuthModel->updateRecord(array("booking_id"=>$booking_id),'booking',$updata))
                {  
                    $message="Your booking has been successfully cancelled";
                    if($score->banned_count==0 && $cancel_limit==$newCancelCount)  //first time suspend 7days
                    {
                        if($msg = $this->AuthModel->Suspend(7,$customer_id))  //store in useraction and user table
                        { $message = 'Booking has been cancelled. Your account is suspend for 7 days due to exceeded weekly cancellation limit'; } 
                        $upscoreData['banned_count']=1;
                        $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',$upscoreData);
                    }
                    elseif($score->banned_count==1 && $cancel_limit==$newCancelCount)//2nd time suspend 14days
                    {
                        if($this->AuthModel->Suspend(14,$customer_id))  //store in useraction and user table
                        { $message = 'Booking has been cancelled. Your account is suspend for 14 days due to exceeded 2nd time weekly cancellation limit'; }
                        $upscoreData['banned_count']=2;
                        $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',$upscoreData);
                    }
                    elseif($score->banned_count==2 && $cancel_limit==$newCancelCount) // balck listed
                    {
                        if($this->AuthModel->updateRecord(array('id'=>$customer_id),'users',array('active_status'=>'Banned','blackList_status'=>'yes')));
                        {
                            $message = 'Booking has been cancelled. Your account is black listed due to exceeded weekly cancellation limit many times';
                        }
                        $upscoreData['banned_count']=3;
                        $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',$upscoreData);
                    }
                    else
                    {
                        if($this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',$upscoreData))
                        { $message="Your booking has been successfully cancelled"; }
                    }
                    $respose = array("error"=>0,"success"=>1,"message"=>$message);
                    echo json_encode($respose);             
                }
                else
                {
                    $respose = array("error"=>1,"success"=>0,"message"=>"Oops! Something went wrong, Please try again");
                    echo json_encode($respose);
                }            
            }
            else
            {
                $respose = array("error"=>1,"success"=>0,"message"=>"Invalid booking");
                echo json_encode($respose);
            }            
        }
        else
        {
            $this->index();
        }
    } 

    /*public function OldTripRejectByCustomer()
    {
        if(isset($_POST['booking_id']) && $_POST['booking_id'] && isset($_POST['customer_id']) && $_POST['customer_id']!='')
        {            
            $checkBookingStatus = $this->AuthModel->getSingleRecord('booking',array("booking_id"=>$booking_id));
            if(!empty($checkBookingStatus))
            {                
                $updata = array("booking_status"=>3,"cancel_reason"=>$cancel_reason);
                if($this->AuthModel->updateRecord(array("booking_id"=>$booking_id),'booking',$updata))
                {       
                    $score = $this->AuthModel->getSingleRecord('users_score',array("user_id"=>$customer_id));
                    if($checkBookingStatus->booking_status==1)   //cancel after accept
                    {
                        $preAcceptCancel = $score->total_cancel_after_accept;
                        $newAcceptCancel = $preAcceptCancel+1;  
                        if($score->banned_count==0 && $newAcceptCancel<3)
                        {
                            $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',array("total_cancel_after_accept"=>$newAcceptCancel));                            
                        }                      
                        elseif($score->banned_count==0 && $newAcceptCancel==3)
                        {
                            $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',array("total_cancel_after_accept"=>$newAcceptCancel,'banned_count'=>1));
                            $this->AuthModel->updateRecord(array('id'=>$customer_id),'users',array('active_status'=>'banned'));
                        }
                        elseif($score->banned_count==1 && $newAcceptCancel==3)
                        {
                            $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',array("total_cancel_after_accept"=>$newAcceptCancel,'banned_count'=>1));
                            $this->AuthModel->updateRecord(array('id'=>$customer_id),'users',array('active_status'=>'banned'));
                        }
                    }
                    elseif($checkBookingStatus->booking_status==0)   //cancel before accept
                    {
                        $preCancel       = $score->total_cancel_before_accept;
                        $newPreAcceptCancel = $preCancel+1;                        
                        if($score->banned_count==0 && $newPreAcceptCancel==3)
                        {
                            $this->AuthModel->updateRecord(array("user_id"=>$customer_id),'users_score',array("total_cancel_before_accept"=>$newPreAcceptCancel,'banned_count'=>1));
                            $this->AuthModel->updateRecord(array('id'=>$customer_id),'users',array('active_status'=>'banned'));
                        }
                    }
                    $record = array("booking_id"=>$booking_id,"cancelby_id"=>$driver_id,"cancel_reason"=>$cancel_reason);
                    $this->AuthModel->singleInsert('booking_cancel_record',$record);                
                }
                else
                {
                    $respose = array("error"=>1,"success"=>0,"message"=>"Oops! Something went wrong, Please try again");
                    echo json_encode($respose);
                }            
            }
            else
            {
                $respose = array("error"=>1,"success"=>0,"message"=>"Invalid booking");
                echo json_encode($respose);

            }            
        }
        else
        {
            $this->index();
        }
    } */ 

            // =================================Develope by Abhishek=====================================================//
    public function completeBooking()
    {      
        $response=array();
        $responsedata=array();                
        $current_time=date('Y-m-d H:i:s',time());
        $data_val = array('customer_id');
        $validation = $this->StandardModel->param_validation($data_val,$_REQUEST);
        if(isset($validation['status']) && $validation['status']=='0')
        {
            echo json_encode(array('response'=>'false','message'=>$validation['message']));die;
        }
        else
        {
            $customer_id= $_REQUEST['customer_id'];
            $date=strtotime(date("Y-m-d h:i:s"));
            $where = '(customer_id='.$customer_id.' AND (booking_status!=2 or booking_status!=3))';
            //$where=array('customer_id'=>$customer_id,'booking_status'=>'4');
            $response1=$this->StandardModel->select_query('booking',$where);
            if(!empty($response1)){
            foreach($response1 as $deliver)
            {
                $response['booking_id']=$deliver->booking_id;
                $response['booking_status']=$deliver->booking_status;
                $response['ride_complete_at']=$deliver->ride_complete_at;
                $response['booking_at']=$deliver->booking_at;
                $response['pickup']=$deliver->pickup;
                //$wheree=array('booking_id'=> $response['booking_id']);
                //$booking_dropoffs=$this->StandardModel->select_query('booking_dropoffs',$wheree);
                $booking_dropoffs=booking_dropoffs($response['booking_id']);
                //$response['dropoff']=$booking_dropoffs[0]->dropoff;
                $response['dropoff']=$booking_dropoffs;
                $responsedata[]=$response;
                }
                echo json_encode(array('response'=>'true','Completed'=>$responsedata));
            }
            else
            {
                echo json_encode(array('response'=>'false','message'=>'Please enter valid customerid !'));die;
            }     
        }            
    }

    public function CancelBooking()
    {      
        $response=array();
        $responsedata=array();            
        $current_time=date('Y-m-d H:i:s',time());
        $data_val = array('customer_id');
        $validation = $this->StandardModel->param_validation($data_val,$_REQUEST);
        if(isset($validation['status']) && $validation['status']=='0')
        {
            echo json_encode(array('response'=>'false','message'=>$validation['message']));die;
        }
        else
        {
            $customer_id= $_REQUEST['customer_id'];
            $date=strtotime(date("Y-m-d h:i:s"));
            $where= '(customer_id='.$customer_id.' and (booking_status=2 or booking_status=3))';
            //$where=array('customer_id'=>$customer_id,'booking_status'=>'3');
            $response1=$this->StandardModel->select_query('booking',$where);
            if(!empty($response1))
            {
                foreach($response1 as $deliver)
                {
                    $response['booking_id']=$deliver->booking_id;
                    $response['booking_status']=$deliver->booking_status;
                    $response['booking_at']=$deliver->booking_at;
                    $response['cancel_reason']=$deliver->cancel_reason;
                    $response['pickup']=$deliver->pickup;         
                    $wheree=array('booking_id'=> $response['booking_id']);
                    $booking_dropoffs=$this->StandardModel->select_query('booking_dropoffs',$wheree);
                    $response['dropoff']=$booking_dropoffs[0]->dropoff;
                    $responsedata[]=$response;                   
                }                
                echo json_encode(array('response'=>'true','Cancelled'=>$responsedata));                
            }
            else{
                echo json_encode(array('response'=>'false','message'=>'No Cancel Booking Records!'));
            }     
        }            
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