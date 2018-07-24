<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dispatch extends CI_Controller {
    var $country; var $city;
	function __construct() {
        parent::__construct();  
        $this->load->library('session');
        if($this->session->userdata('dis_email')=='')
        {
            redirect(site_url('Welcome/dispatch_login'));
        }else{
            $this->country = $this->session->userdata('dis_country');
            $this->city = $this->session->userdata('dis_city');
            $this->dis_id = $this->session->userdata('dis_id');      
        }
        
    }

    public function index()
    {         
        $today= date('d-m-Y');
        $earningDatest = strtotime($today.' 00:00');            
        $earningDatend = strtotime($today.' 11:59 PM');
        $todaybooking = $this->AuthModel->getMultipleRecord('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'country'=>$this->country,'city'=>$this->city),"`booking_id` DESC");       
        
        if(!empty($todaybooking))
        {
            $data = array("list"=>$todaybooking);                
            $this->load->view('dispatch/todaybooking',$data);
        }
        else
        {
            $data = array("error"=>1,"message"=>"Today, We have not found trip request in your city.","list"=>array());             
            $this->load->view('dispatch/todaybooking',$data);
        }
    }

    public function new_booking(){
        $this->load->view('dispatch/newbooking');
    }

    public function search_driver(){   //On New booking page
        extract($_POST); 
        //echo json_encode($_POST);die();
        $driver_ids=array();
        $datest = strtotime(date('d-m-Y').' 00:00');            
        $datend = strtotime(date('d-m-Y').' 11:59 PM');
        $userWhere=array();
        if($city=='Please Select city'){  
            $userWhere = array('nationality'=>$country,'user_type'=>1,'activeStatus'=>'Active');            
        }
        else{
            $userWhere = array('nationality'=>$country,'city'=>$city,'user_type'=>1);
        }
        //======================================================================================================//
        if($servicetype!='' && $status!=''){  //According to service type,status and country and city(is or not)
            //echo '4';die();
            if($status=='free'){         //get free driver means online drivers
                $userWhere['online_status']='online';                
                $drivers = $this->AuthModel->getMultipleRecord('users',$userWhere,'');            
            }
            else if($status='busy'){   // get busy driver with servicetype means driver booking is in progress.                
                if($city!='Please Select city'){                      
                    $where = 'country="'.$country.'" and city="'.$city.'" and service_typeid='.$servicetype.' and (booking_status=0 or booking_status=1 or booking_status=5 or booking_status=6)';
                }
                else{
                    $where = 'country="'.$country.'" and service_typeid='.$servicetype.' and  (booking_status=0 or booking_status=1 or booking_status=5 or booking_status=6)';
                }                
                $drivers = $this->AuthModel->getMultipleRecord('booking',$where,'');                                
            }           

            if(!empty($drivers)){
                foreach ($drivers as $k => $d) {
                    if($status=='free'){
                        $driverid = $d->id;
                        $exist = $this->AuthModel->checkRows('vehicle_servicetype',array('driver_id'=>$driverid,'service_type_id'=>$servicetype));
                        if($exist>0){
                            $driver_ids[] = $driverid;
                        }                        
                    }else{
                        $driver_ids[] = $d->driver_id;
                    }                    
                }     
                //print_r($driver_ids);die();            
                if(!empty($driver_ids)){                                                                                            
                    $ress = $this->AuthModel->getWhereInRecord('driver_live_location','user_id',$driver_ids);
                    //print_r($this->db->last_query());die();
                    if(!empty($ress)){
                        foreach ($ress as $k => $l) {
                            $driverid = $l->user_id;
                            $driver_data = $this->AuthModel->getSingleRecord('users',array('id'=>$driverid,'user_type'=>1));
                            $fare_data = $this->AuthModel->getSingleRecord('fare',array('country'=>$country,'city'=>$city,'serviceType_id'=>$servicetype));
                            $max_person=1;
                            if(!empty($fare_data)){
                                $max_person=$fare_data->maximum_load;
                            }
                            $name = ''; $id='';$email='';$mobile='';
                            if(!empty($driver_data)){$name=$driver_data->name;$id=$driver_data->id;$email=$driver_data->email;$mobile=$driver_data->mobile;}
                            $location['marker'][] =array($l->address,$l->latitude,$l->longitude,$name,$id,$email,$mobile);  
                            $location['max_person']=$max_person;
                            $loc[]=$location;
                        }                                
                        echo json_encode($location);                
                    }
                }                                
            }
        }

        //======================================================================================================//
        elseif($status!=''){                                                 //According to driver status   (free,busy)
            //echo '2';die();
            if($status=='free'){         //get free driver means online drivers
                $userWhere['online_status']='online';                
                $drivers = $this->AuthModel->getMultipleRecord('users',$userWhere,'');   
                //print_r($this->db->last_query());die();         
             }
             else if($status='busy'){   // get busy driver means driver booking is in progress.
                //$tbook = 'booking_at_string>='.$datest.' and booking_at_string<='.$datend;
                if($city!='Please Select city'){                      
                    $where = 'country="'.$country.'" and city="'.$city.'" and (booking_status=0 or booking_status=1 or booking_status=5 or booking_status=6)';
                }
                else{
                    $where = 'country="'.$country.'" and (booking_status=0 or booking_status=1 or booking_status=5 or booking_status=6)';
                }                
                $drivers = $this->AuthModel->getMultipleRecord('booking',$where,'');                                
             }
             //print_r($this->db->last_query());die();
            // print_r($drivers);die();
            if(!empty($drivers)){
                foreach ($drivers as $k => $d) {
                    if($status=='free'){$driverid = $d->id;}else{$driverid = $d->driver_id;}
                    $check = $this->AuthModel->getSingleRecord('users',array('id'=>$driverid,'activeStatus'=>'Active'));; 
                    if(!empty($check)){
                        $ress = $this->AuthModel->getSingleRecord('driver_live_location',array('user_id'=>$driverid));
                        $email='';$mobile='';
                        if(!empty($ress)){
                            $name=$check->name; 
                            $id=$driverid; $email = $check->email; $mobile = $check->mobile;
                            $location['marker'][] =array($ress->address,$ress->latitude,$ress->longitude,$name,$id,$email,$mobile);
                        }                                                                                               
                    }                        
                }
                echo json_encode($location);                
            }           
        }
        //======================================================================================================//

        elseif($servicetype!=''){                       //According to service type, country and city(is or not)
            //echo '3';die();          
            $drivers = $this->AuthModel->getMultipleRecord('users',$userWhere,'');  
            if(!empty($drivers)){
                foreach ($drivers as $k => $d) {
                    $driverid= $d->id;
                    $check = $this->AuthModel->checkRows('vehicle_servicetype',array('driver_id'=>$driverid,'service_type_id'=>$servicetype)); 
                    if($check>0){
                        $driver_ids[] = $driverid;
                    }                        
                }
                //print_r($driver_ids);die();
                if(!empty($driver_ids)){ 
                    $ress = $this->AuthModel->getWhereInRecord('driver_live_location','user_id',$driver_ids);
                    if(!empty($ress)){
                        foreach ($ress as $k => $l) {
                            $driverid = $l->user_id;
                            $driver_data = $this->AuthModel->getSingleRecord('users',array('id'=>$driverid,'user_type'=>1));
                            $name = '';  $id=''; $email='';$mobile='';
                            if(!empty($driver_data)){$name=$driver_data->name;$id=$driver_data->id;$email=$driver_data->email;$mobile=$driver_data->mobile;}
                            $location['marker'][] =array($l->address,$l->latitude,$l->longitude,$name,$id,$email,$mobile);                           
                        }                                
                        echo json_encode($location);                
                    }
                }
            }
        }                      
    }


    public function calculateFair()
    {        
        //$total_distance,$total_rideMinute        
        extract($_POST);
        $total_distance= str_replace(" km","",$_POST['distance']);
        $total_rideMinute= str_replace(" mins","",$_POST['time']);   
        $now = strtotime(date('h:i A')); 

        $fair_detail = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$service_id,'country'=>$this->country,'city'=>$this->city));
        
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
                $extract_distance = $total_distance-$fair_detail->min_distance;
                $rightExtra = $this->BookingModel->rightMultiple($extract_distance,$fair_detail->regularChargeEveryDistance);
                $total_regularCharge = ($rightExtra/$fair_detail->regularChargeEveryDistance)*$fair_detail->regularChargeForDistance;  
                //echo $total_regularCharge;die();                
                //$total_regularCharge = ($total_distance-$fair_detail->min_distance/$fair_detail->regularChargeEveryDistance)*$fair_detail->regularChargeForDistance;                   
            }                     
            if($fair_detail->perMinChargeStatus=='on')
            {              
                $minuteForCharge = $this->BookingModel->rightMultiple($total_rideMinute,$fair_detail->unitPerMinuteforCharge);
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

    public function get_userDetails(){
        if(isset($_POST['email']) && $_POST['email']!=''){
            extract($_POST);
            $record = $this->AuthModel->getSingleRecord('users',array('email'=>$email,'user_type'=>0,'activeStatus'=>'Active'));
            if(!empty($record)){
                $res = array('id'=>$record->id,'name'=>$record->name,'mobile'=>$record->mobile,'email'=>$record->email);
                $response = array('error'=>0,'data'=>$res);
                echo json_encode($response);
            }
            else{
                $response = array('error'=>1,'message'=>'This email id is not found');
                echo json_encode($response);   
            }
        }
        else{
            $response  = array('error'=>1,'message'=>'Please enter passenger email id.');
            echo json_encode($response);
        }
    }

    public function BookDriver()
    {
        $cc = json_decode('{"success":1,"error":0,"message":"Booking successfull","data":{"booking_id":8,"driver_id":"22","driver_image":"http:\/\/localhost\/projects\/CitiTaxi\/userimage\/9G8anm1Rm1.jpg","driver_name":"test app","driver_mobile":"9889521018","service_typeid":"1","service_name":"Eco","vehicle_id":"9","vehicle_name":"Tata Tiago","vehicle_no":"MP 09 MN 4521","liveaddress":"South Tukoganj, Indore","livelatitude":"22.715512","livelongitude":"75.875426","pickup":"Madhumilan Chauraha, Chhoti Gwaltoli, Indore, Madhya Pradesh","pickupLat":"22.7141686","pickupLong":"75.87436179999997","free_waiting_minute":"5","waiting_period":"2","waiting_period_charge":"2","currency":"Rs"},"booking_id":8}');
        echo json_encode($cc);
            die();
        $rawPostData    = file_get_contents('php://input');
        $jsonData       = json_decode($rawPostData,true);
        //print_r($jsonData['dropoff']);die();
        if(!empty($jsonData) && !empty($jsonData['dropoff']))
        {   
            //customer_id,address_type(single,multiple),fromaddress,fromLat,fromLong,toaddress,toLat,toLong,service_type_id, date,time,booking_type(now,later),country,city
            if($jsonData['booking_type']=='now')
            {
                $paramarray = array('customer_id','booking_address_type','pickup','pickupLat','pickupLong','service_type_id','date','time','country','city_name','payment_type','total_ride_distance','total_fair','promocode_status');
                $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$jsonData);
                if(isset($vResponse['status']) && $vResponse['status']==0)
                {
                    $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                    echo json_encode($response);die();
                }
            }
            if($jsonData['booking_type']=='later')
            {
                $paramarray = array('later_pickup_date','later_pickup_time');
                $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$jsonData);
                if(isset($vResponse['status']) && $vResponse['status']==0)
                {
                    $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                    echo json_encode($response);die();
                }
            }   
            if($jsonData['promocode_status']=='Yes'){
                $paramarray = array('promo_id');
                $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$jsonData);
                if(isset($vResponse['status']) && $vResponse['status']==0)
                {
                    $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                    echo json_encode($response);die();
                }
            }                   
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
            $promo_status           = $jsonData['promocode_status'];  //(Yes, No)
            $promo_id               = $jsonData['promo_id'];
            $later_pickup_date      = $jsonData['later_pickup_date']; 
            $later_pickup_time      = $jsonData['later_pickup_time']; 
            $booking_note           = $jsonData['booking_note'];
            $passenger              = $jsonData['passenger'];
            $payment_type           = $jsonData['payment_type'];    //cash,paypal,citipay           
           
            //=================================================================================================//
            /*$dropoffs=[];
            foreach($dropoff as $k =>$v)
            {
                $c["booking_id"]    =  1;
                $c["dropoff"]       =  $v['dropoff'];
                $c["dropoffLat"]    =  $v['dropoffLat'];
                $c["dropoffLong"]   =  $v['dropoffLong'];
                $dropoffs[]=$c;
            } 
            print_r($dropoffs);die();*/


            
            $fairDetails            = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$service_type_id,"country"=>$country,"city"=>$city_name));  
            //check service available in this city or not
            if(!empty($fairDetails))
            {
                if($jsonData['booking_type']=='later'){
                    $nearbyDriver = true;
                    $driver_id    = 0;
                    $booking_status=8; // will assign later
                    $later_pickup = $later_pickup_date.' '.$later_pickup_time;
                    $later_pickup_string = strtotime($later_pickup);
                    $later_pickup_at = date('d-m-Y h:i A',strtotime($later_pickup));
                }
                else{ 
                    $nearbyDriver = '';
                    $allDriverNearBy  = $this->BookingModel->searchNearByDriver($pickupLat,$pickupLong,$date,$time,$service_type_id);
                    //print_r($this->db->last_query());
                    //print_r($allDriverNearBy);die();
                    if(!empty($allDriverNearBy))
                    {
                        foreach ($allDriverNearBy as $near) {
                            $driver = $near->user_id;        
                        //To check driver wallet balance if balnce greater then cancel charge then assign request                    
                            $driver_cancelUnit = $fairDetails->cancelChargeUnitDriver;
                            $driver_cancelCharge =  $fairDetails->stndCancelChargeDriver;
                            if($driver_cancelUnit=='Per'){
                                $driver_cancelCharge = ($total_fair*$driver_cancelCharge)/100;
                            }
                            //echo $driver_cancelCharge;die();
                            $nearly  = $this->BookingModel->searchDriver($pickupLat,$pickupLong,$date,$time,$service_type_id,$driver,$driver_cancelCharge);
                            //echo json_encode($nearbyDriver);die();
                            if(!empty($nearly)){                                                        
                                $driver_id = $nearly->id;                                
                                $booking_status=0; //assign driver
                                $later_pickup_at ='';
                                $later_pickup_string = '';
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
                    }
                }
                getfinaldriver:
                //echo json_encode($nearbyDriver);die();
                if(!empty($nearbyDriver))
                {                                         
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
                            "passenger"=>$passenger,
                            "booking_at" =>$booking_at,
                            "booking_at_string"=>strtotime($booking_at),
                            "booking_type"=>$booking_type,
                            "later_pickup_at"=>$later_pickup_at,
                            "later_pickup_string"=>$later_pickup_string,
                            "total_ride_time"=>$total_ride_time,
                            "total_distance"=>$total_ride_distance,
                            "distance_unit"=>$fairDetails->distanceUnit,
                            "total_fare"=>$total_fair,
                            "currency"=>$fairDetails->currency,
                            "promo_status"=>$promo_status,
                            "promo_id"=>$promo_id,
                            "payment_type"=>$payment_type, 
                            "booking_status"=>$booking_status,                          
                        );                   
                    //print_r($bookingData);die();                    
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

                            /*********************** Save promo details if apply ************************/
                            if($promo_status=='Yes'){                                
                                $this->savePromoHistory($customer_id,$booking_id,$promo_id); 
                            }

                            if($jsonData['booking_type']=='now') // when current booking
                            {                                 
                                /*$commissionData = array("booking_id"=>$booking_id,"driver_id"=>$driver_id,"commission_type"=>$fairDetails->company_comission_type,"commission_rate"=>$fairDetails->company_comission_rate,"commission_at"=>$date.' '.$time);
                                $this->AuthModel->singleInsert('company_booking_commission',$commissionData); */

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
                                                    "free_waiting_minute"=>$fairDetails->regularFreeWaitingMinute,
                                                    "waiting_period"=>$fairDetails->regularWaitingPeriodForCharge,
                                                    "waiting_period_charge"=>$fairDetails->regularWaitingPeriodCharge,
                                                    "currency"=>$fairDetails->currency,                                             
                                                );

                                $response = array('success'=>1,'error'=>0,'message'=>'Booking successfull',"data"=>$tripData,'booking_id'=>$booking_id);
                                echo json_encode($response);
                            }
                            else
                            {
                                $response = array('success'=>1,'error'=>0,'message'=>'Booking successfull',"data"=>'','booking_id'=>$booking_id);
                                echo json_encode($response);
                            }
                        }
                        else{
                            $this->AuthModel->delete_record('booking',array('booking_id'=>$booking_id));
                            $response = array('success'=>0,'error'=>1,'message'=>'Oops! something went wrong, please try again',"data"=>'');
                            echo json_encode($response);                            
                        }
                    }
                    else{
                        $response = array("success"=>0,"error"=>1,"message"=>"Oops! something went wrong, please try again");
                        echo json_encode($response);
                    }
                }
                else{
                    $response = array("success"=>0,"error"=>1,"message"=>"Sorry! No driver found at your pickup location");
                    echo json_encode($response);
                }
            } 
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'Please change location.');
                echo json_encode($response);
            } 
        }
        else{
            $this->index();
        }
    }
    public function savePromoHistory($customer_id,$booking_id,$promo_id){
        $promo = $this->AuthModel->getSingleRecord('promocode',array('promo_id'=>$promo_id));
        //print_r($promo);
        
        if(!empty($promo)){
            $data = array(
                "booking_id"=>$booking_id,
                "user_id"=>$customer_id,
                "promo_id"=>$promo_id,
                "promocode"=>$promo->promocode,
                "rate_type"=>$promo->rate_type,
                "rate"     =>$promo->rate,  
                "promo_type"=>$promo->promo_type,              
                );    
                //echo json_encode($data);die();        
            $this->AuthModel->singleInsert('promocode_history',$data,array('user_id'=>$customer_id,'promocode'=>$promo->promocode));
        }
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
    
    public function saveFairDetails($fairDetails,$booking_id,$bookdate,$booktime,$booking_address_type,$total_regular_charge,$total_perminute_charge)  //Book driver part
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


    public function trackTrip($booking_id,$driverid){
        if($booking_id!='' && $driverid!=''){  
            $book_data = $this->AuthModel->getSingleRecord('booking',array('booking_id'=>$booking_id));          
            $dropoffs  = $this->AuthModel->getMultipleRecord('booking_dropoffs',array('booking_id'=>$booking_id),'');
            if(!empty($book_data) && !empty($dropoffs))
            { 
                $marker[] = array($book_data->pickup,$book_data->pickupLat,$book_data->pickupLong);
                foreach ($dropoffs as $key => $v) {
                    $marker[] = array($v->dropoff,$v->dropoffLat,$v->dropoffLong);
                }
                //$location['marker'][] =array($book->pickup,$book->pickupLat,$book->pickLong);
                $data['marker'] = $marker;
                $data['book'] = $book_data;
                $data['drop'] = $dropoffs;
                $this->load->view('Dispatch/tripTracking',$data);
            }
            else{
                $response = array('response'=>'false','message'=>'Invalid booking');
                echo json_encode($response);
            }            
        }
        else{
            redirect(site_url("Dispatch/unknow"));
        }
    }

    public function tripMarkers(){
        if(isset($_POST['booking_id']) && $_POST['booking_id']!=''){
            extract($_POST);
            $book_data = $this->AuthModel->getSingleRecord('booking',array('booking_id'=>$booking_id));          
            $dropoffs  = $this->AuthModel->getMultipleRecord('booking_dropoffs',array('booking_id'=>$booking_id),'');
            if(!empty($book_data) && !empty($dropoffs))
            { 
                $marker[] = array($book_data->pickup,$book_data->pickupLat,$book_data->pickupLong,'p');
                foreach ($dropoffs as $key => $v) {
                    $marker[] = array($v->dropoff,$v->dropoffLat,$v->dropoffLong,'d');
                }                
                echo json_encode($marker);                
            }
        }
        else{
            $response = array('error'=>1,'message'=>'access denied');
            echo json_encode($response);
        }
    }
    public function driverLiveLocation(){
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['booking_id']) && $_POST['booking_id']!=''){
            extract($_POST);
            $live = $this->AuthModel->getSingleRecord('driver_live_location',array('user_id'=>$driver_id));                      
            if(!empty($live))
            { 
                $booking =  $this->AuthModel->getSingleRecord('booking',array('booking_id'=>$booking_id));  
                $status  =   $booking->booking_status;
                $marker[] = array($live->address,$live->latitude,$live->longitude,'driver',$status);                               
                echo json_encode($marker);                
            }
        }
        else{
            $response = array('error'=>1,'message'=>'access denied');
            echo json_encode($response);
        }
    }



    public function heatmap(){
        $this->load->view('dispatch/heatmap');
    }

    public function manage_booking(){
        $table_name = 'booking';
        $orderby  = "`booking_id` DESC";
        $where = array('booking_type'=>'now','country'=>$this->country,'city'=>$this->city);
        //$orderby ="";
        $bookings = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);

        //print_r($this->db->last_query());die();
        if(!empty($bookings))
        {
            $data['list']=$bookings;            
            $this->load->view('dispatch/booking_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["list"]='';
            $this->load->view('dispatch/booking_details',$data);
        }
    }

    public function disptach_profile()
    {
        if($this->dis_id==''){
            redirect('Welcome/dispatch_login');
        }
        else{
            $dispatcher_id= $this->dis_id;
            $dispdata = $this->AuthModel->getSingleRecord('dispatch',array('dispatcher_id'=>$dispatcher_id));
            if(isset($_POST['submit']))
            {
                extract($_POST);                
                $checkmail = $this->AuthModel->checkRows('dispatch',array('email'=>$dispatch_email));
                if($checkmail>0 && $dispatch_email != $dispdata->email)
                {
                    $respose  = array("error"=>1,"message"=>"This email-id has already registered","details"=>$dispdata);
                    $this->load->view('dispatch/profile_dispatcher',$respose);
                }
                else
                {
                    $imagename        = $dispdata->image;                   
                    if(isset($_FILES['image']) && $_FILES['image']['name']!='')
                    {
                        $folder_name = 'fleetimage';
                        $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
                    }
                    $updata= array(               
                        "name"      =>$name,
                        "phone"     =>$dismobile,
                        "email"     =>$dispatch_email,                        
                        "address"   =>$address,                                            
                        "image"     =>$imagename,                                                             
                    );                    
                    $UpdateData = $this->AuthModel->updateRecord(array('dispatcher_id'=>$dispatcher_id),'dispatch',$updata);
                    if($UpdateData)
                    {
                        $dispdata = $this->AuthModel->getSingleRecord('dispatch',array('dispatcher_id'=>$dispatcher_id));
                        $user_data = array("dis_email"=>$dispdata->email,"dis_name"=>$dispdata->name,'dis_image'=>$dispdata->image);
                        $this->session->set_userdata($user_data);
                        $response["success"]          = 1;
                        $response["message"]          = "Record has been successfully updated";
                        $response["details"]           = $dispdata;
                        $this->load->view('dispatch/profile_dispatcher',$response);
                    }
                    else
                    {
                        $response["error"]              = 1;    
                        $response["message"]            = "Oops! Error occur. Please Try again";
                        $response["details"]           = $dispdata;
                        $this->load->view('dispatch/profile_dispatcher',$response);
                    }
                }                 
            }   
            else
            {
                // print_r($dispdata);die();
                $data['details'] = $dispdata; 
                $this->load->view('dispatch/profile_dispatcher',$data);    
            }
        }
    }

    public function changePassword()
    {
        if($this->dis_id==''){
            redirect('Welcome/dispatch_login');
        }
        else{
            if(isset($_POST['submit']))
            { 
                extract($_POST);
                $id = $this->dis_id;
                //echo $password;                
                $encPasswrod = md5($old_password);
                $rr = $this->AuthModel->checkRows('dispatch',array('password'=>$encPasswrod,'dispatcher_id'=>$id));
                //print_r($this->db->last_query()); die();
                if($rr>0)
                {
                    if($this->AuthModel->updateRecord(array('dispatcher_id'=>$id),'dispatch',array('password'=>md5($password),'pass'=>$password))){
                        $response = array('success'=>1,'message'=>'Password has been successfully changed');
                        $this->load->view('dispatch/change_password',$response);
                    }
                    else
                    {
                        $response = array('error'=>1,'message'=>'Sorry! Password is not changed. Please try again');
                        $this->load->view('dispatch/change_password',$response);
                    }                    
                }
                else
                {
                    $response = array('error'=>1,'message'=>'Old password is not match. Please enter correct old password');
                    $this->load->view('dispatch/change_password',$response);                    
                }
            }
            else
            {                
                $this->load->view('dispatch/change_password');
            }
        }        
    }
         

    //======================================================================================================// 

        

    
}