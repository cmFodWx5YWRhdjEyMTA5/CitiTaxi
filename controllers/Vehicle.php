<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vehicle extends CI_Controller {
	function __construct() {
        parent::__construct();  
        if($this->session->userdata('email')=='')
        {
            redirect(base_url());
        }   
    }

    public function index()        //vechicle types
    {
        $table_name = 'vehicle_types';
        $where = array(); $orderby  = "`vtype_id` DESC";
        $Vtypes = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        if(!empty($Vtypes)){   
            $response = array('vtypes'=>$Vtypes);         
            $this->load->view('vehicle_types',$response);
        }
        else
        {
            $response = array('error'=>1,'message'=>'No vehicle types available','vtypes'=>$Vtypes);
            $this->load->view('vehicle_types',$response);   
        }
    }

    public function add_fare()
    {
        if(isset($_POST['submit']))
        {
            //echo "<pre>";
            //print_r($_POST);
            // `serviceType_id`, `service_name`, `description`, `maximum_load`, `country_id`, `country`, `city_id`, `city`, `currency`, `vehicle_type`, `company_comission_type`, `company_comission_rate`, `distanceUnit`, `minbase_fair`, `min_distance`, `min_distanceUnit`, `mini_distancefair`, `regularChargeEveryDistance`, `regularChargeEveryDistance_unit`, `regularChargeForDistance`, `perMinChargeStatus`, `unitPerMinuteforCharge`, `unitPerMinutecharge`, `regularFreeWaitingMinute`, `regularWaitingPeriodForCharge`, `regularWaitingPeriodCharge`, `morningChargeStatus`, `morningSurchargeUnit`, `morningSurchargePrice`, `morningSurchargeTimeStart`, `morningSurchargeTimeEnd`, `eveningChargeStatus`, `eveningSurchargeUnit`, `eveningSurchargePrice`, `eveningSurchargeTimeStart`, `eveningSurchargeTimeEnd`, `midNightChargeStatus`, `midNightSurchargeUnit`, `midNightSurchargePrice`, `midNightSurchargeTimeStart`, `midNightSurchargeTimeEnd`, `cancelChargeUnitDriver`, `stndCancelChargeDriver`, `cancelChargeUnitPassenger`, `stndCancelChargePassenger`, `WeeklyCancellationLimit`, `multiStopCharge`,
            extract($_POST);
            $data = array(
                'serviceType_id'=>$service_type, 'service_name'=>$servicename, 'description'=>$description,
                'maximum_load'=>$maxload,'country_id'=>$country_id, 'country'=>$country, 
                'city_id'=>$city_id,'city'=>$city, 'currency'=>$currency,
                'vehicle_type'=>$vehicletype, 'company_comission_type'=>$commsiontype,
                'company_comission_rate'=>$commissionRate, 'distanceUnit'=>$distanceUnit,                
                'minbase_fair'=>$minbase_fair,
                'min_distance'=>$minDistance, 'min_distanceUnit'=>$min_distUnit, 'mini_distancefair'=>$mini_distancefair,
                'regularChargeEveryDistance'=>$regularChargeUponKm,'regularChargeEveryDistance_unit'=>$regularChargeUpon_unit,
                'regularChargeForDistance'=>$uponMinuteCharge,
                'perMinChargeStatus'=>$perMinChargeStatus,'unitPerMinuteforCharge'=>$unitPerMinuteforCharge,
                'unitPerMinutecharge'=>$unitPerMinutecharge,
                'regularFreeWaitingMinute'=>$regFreeWaitingMinute,
                'regularWaitingPeriodForCharge'=>$regWaitingUnitTime, 'regularWaitingPeriodCharge'=>$regWaitingUnitTimePrice,
                'morningChargeStatus'=>$morningChargeStatus,
                'morningSurchargeUnit'=>$morningSurchargeUnit, 'morningSurchargePrice'=>$morningSurchargePrice,
                'morningSurchargeTimeStart'=>$morningSurchargeTimeStart, 'morningSurchargeTimeEnd'=>$morningSurchargeTimeEnd,
                'eveningChargeStatus'=>$eveningChargeStatus, 'eveningSurchargeUnit'=>$eveningSurchargeUnit,
                'eveningSurchargePrice'=>$eveningSurchargePrice, 'eveningSurchargeTimeStart'=>$eveningSurchargeTimeStart,
                'eveningSurchargeTimeEnd'=>$eveningSurchargeTimeEnd, 'midNightChargeStatus'=>$midNightChargeStatus,

                'midNightSurchargeUnit'=>$midNightSurchargeUnit, 'midNightSurchargePrice'=>$midNightSurchargePrice,
                'midNightSurchargeTimeStart'=>$midNightSurchargeTimeStart, 'midNightSurchargeTimeEnd'=>$midNightSurchargeTimeEnd, 

                
                'cancelChargeUnitDriver'=>$cancelChargeUnitDriver, 'stndCancelChargeDriver'=>$stndCancelChargeDriver,
                'cancelChargeUnitPassenger'=>$cancelChargeUnitPassenger,'stndCancelChargePassenger'=>$stndCancelChargePassenger,
                'WeeklyCancellationLimit'=>$weeklyCancelLimit,'multiStopCharge'=>$multiStopCharge
                );

            //print_r($data);die();
            if($uid = $this->AuthModel->singleInsert('fare',$data))
            {
                $this->checkCitySetting($country,$city,$timezone,$offset);
                $respose["success"] = 1;
                $respose["message"] = "Vehicle Fare has been successfully saved";
                $this->load->view('addVehicle_fair',$respose);
            }
            else
            {
                $respose["error"] = 1;
                $respose["message"] = "Error occur! Please try again";
                $this->load->view('addVehicle_fair',$respose);
            }           
        }
        else
        {
            $this->load->view('addVehicle_fair');
        }
    }

    public function checkCitySetting($country,$city,$timezone,$offset){
        $date  = date('m/d/Y h:i:s A');                
        $data  = array('country'=>$country,'city'=>$city,'time_zone'=>$timezone,'UTC_offset'=>$offset,'update_at'=>$date);
        $where = array('country'=>$country,'city'=>$city);
        $this->AuthModel->checkThenInsertorUpdate('fare_city_setting',$data,$where);
    }

    public function update_fare($fare_id)
    {
        if($fare_id!='')
        {
            $fare_details = $this->AuthModel->getSingleRecord('fare',array('fair_id'=>$fare_id));

            if(isset($_POST['submit']))
            {
                //echo "<pre>";
                //print_r($_POST);die();
                // `serviceType_id`, `service_name`, `description`, `maximum_load`, `country_id`, `country`, `city_id`, `city`, `currency`, `vehicle_type`, `company_comission_type`, `company_comission_rate`, `distanceUnit`, `minbase_fair`, `min_distance`, `min_distanceUnit`, `mini_distancefair`, `regularChargeEveryDistance`, `regularChargeEveryDistance_unit`, `regularChargeForDistance`, `perMinChargeStatus`, `unitPerMinuteforCharge`, `unitPerMinutecharge`, `regularFreeWaitingMinute`, `regularWaitingPeriodForCharge`, `regularWaitingPeriodCharge`, `morningChargeStatus`, `morningSurchargeUnit`, `morningSurchargePrice`, `morningSurchargeTimeStart`, `morningSurchargeTimeEnd`, `eveningChargeStatus`, `eveningSurchargeUnit`, `eveningSurchargePrice`, `eveningSurchargeTimeStart`, `eveningSurchargeTimeEnd`, `midNightChargeStatus`, `midNightSurchargeUnit`, `midNightSurchargePrice`, `midNightSurchargeTimeStart`, `midNightSurchargeTimeEnd`, `cancelChargeUnitDriver`, `stndCancelChargeDriver`, `cancelChargeUnitPassenger`, `stndCancelChargePassenger`, `WeeklyCancellationLimit`, `multiStopCharge`,
                extract($_POST);
                $data = array(
                    'description'=>$description,
                    'maximum_load'=>$maxload,
                    'vehicle_type'=>$vehicletype,
                    'company_comission_type'=>$commsiontype,
                    'company_comission_rate'=>$commissionRate,
                    'distanceUnit'=>$distanceUnit,                
                    'minbase_fair'=>$minbase_fair,
                    'min_distance'=>$minDistance,
                    'min_distanceUnit'=>$min_distUnit, 
                    'mini_distancefair'=>$mini_distancefair,                    
                    'regularChargeEveryDistance'=>$regularChargeUponKm,
                    'regularChargeEveryDistance_unit'=>$regularChargeUpon_unit,
                    'regularChargeForDistance'=>$uponMinuteCharge,                    
                    'perMinChargeStatus'=>$perMinChargeStatus,
                    'unitPerMinuteforCharge'=>$unitPerMinuteforCharge,
                    'unitPerMinutecharge'=>$unitPerMinutecharge,
                    'regularFreeWaitingMinute'=>$regFreeWaitingMinute,
                    'regularWaitingPeriodForCharge'=>$regWaitingUnitTime,
                    'regularWaitingPeriodCharge'=>$regWaitingUnitTimePrice,
                    'cancelChargeUnitDriver'=>$cancelChargeUnitDriver,
                    'stndCancelChargeDriver'=>$stndCancelChargeDriver,
                    'cancelChargeUnitPassenger'=>$cancelChargeUnitPassenger,
                    'stndCancelChargePassenger'=>$stndCancelChargePassenger,
                    'WeeklyCancellationLimit'=>$weeklyCancelLimit,
                    'multiStopCharge'=>$multiStopCharge
                    );

                //print_r($data);die();
                if($uid = $this->AuthModel->updateRecord(array('fair_id'=>$fare_id),'fare',$data))
                {
                    $fare_details = $this->AuthModel->getSingleRecord('fare',array('fair_id'=>$fare_id));
                    $response["success"] = 1;
                    $response["message"] = "Vehicle Fare has been successfully update";
                    $response['fare']    = $fare_details;
                    $this->load->view('updateVehicle_fair',$response);
                }
                else
                {                    
                    $response["error"] = 1;
                    $response["message"] = "Error occur! Please try again";
                    $response['fare']    = $fare_details;
                    $this->load->view('updateVehicle_fair',$response);
                }           
            }
            else
            {
                $response = array('fare'=>$fare_details);
                $this->load->view('updateVehicle_fair',$response);
            }
        }
        else
        {
            redirect('Home');
        }
    }

    public function update_surcharge($fare_id)
    {
        if($fare_id!='')
        {
            $fare_details = $this->AuthModel->getSingleRecord('fare',array('fair_id'=>$fare_id));

            if(isset($_POST['submit']))
            {
                //echo '<pre>';
                //print_r($_POST);die();
                extract($_POST);
                $data = array(
                    'morningChargeStatus'=>$morningChargeStatus,
                    'morningSurchargeUnit'=>$morningSurchargeUnit,
                    'morningSurchargePrice'=>$morningSurchargePrice,
                    'morningSurchargeTimeStart'=>$morningSurchargeTimeStart,
                    'morningSurchargeTimeEnd'=>$morningSurchargeTimeEnd,
                    'eveningChargeStatus'=>$eveningChargeStatus,
                    'eveningSurchargeUnit'=>$eveningSurchargeUnit,
                    'eveningSurchargePrice'=>$eveningSurchargePrice,
                    'eveningSurchargeTimeStart'=>$eveningSurchargeTimeStart,
                    'eveningSurchargeTimeEnd'=>$eveningSurchargeTimeEnd,
                    'midNightChargeStatus'=>$midNightChargeStatus,
                    'midNightSurchargeUnit'=>$midNightSurchargeUnit,
                    'midNightSurchargePrice'=>$midNightSurchargePrice,
                    'midNightSurchargeTimeStart'=>$midNightSurchargeTimeStart,
                    'midNightSurchargeTimeEnd'=>$midNightSurchargeTimeEnd, 
                );
                if($uid = $this->AuthModel->updateRecord(array('fair_id'=>$fare_id),'fare',$data))
                {
                    $fare_details = $this->AuthModel->getSingleRecord('fare',array('fair_id'=>$fare_id));
                    $response["success"] = 1;
                    $response["message"] = "Surcharge has been successfully update";
                    $response['fare']    = $fare_details;
                    $this->load->view('update_fareSurcharge',$response);
                }
                else
                {                    
                    $response["error"] = 1;
                    $response["message"] = "Error occur! Please try again";
                    $response['fare']    = $fare_details;
                    $this->load->view('update_fareSurcharge',$response);
                } 
                //print_r($data);die();
            }
            else
            {
                $response = array('fare'=>$fare_details);
                $this->load->view('update_fareSurcharge',$response);
            }

        }
        else
        {
            redirect('Home');
        }
    }



    public function cities($countryid)
    {
        $table_name = 'cities';
        $where = array('country_id'=>$countryid); $orderby = "`name`,'ASC'";
        $country_data = $this->AuthModel->getSingleRecord('countries',array('id'=>$countryid));
        $cities = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        if(!empty($cities)){
            foreach($cities as $key =>$v)
            {
                $data[]='<option value="'.$v->id.'">'.$v->name.'</option>';                
            }
            $d['data']=$data;
            $d['currency'] = $country_data->currency_symbol;
            $d['timezone'] = $country_data->time_zone;
            $d['offset']   = $country_data->offset;
            echo json_encode($d);
        }
        else
        {
            echo json_encode(array());
        }
    }

    public function updateCountryId()
    {      
        for ($i=201; $i <=246 ; $i++) { 
            $where      = array('id_countries'=>$i);
            $states = $this->AuthModel->getSingleRecord('countries',$where);
            //print_r($states->iso_alpha2);
            //print_r($states->currency_name);
            //print_r($states->currrency_symbol);
            if(!empty($states))
            {
                $checkwhere = array('sortname'=>$states->iso_alpha2);
                $updata     = array('currency_name'=>$states->currency_name,'currency_symbol'=>$states->currrency_symbol);
                $UpdateData = $this->AuthModel->updateRecord($checkwhere,'newcountries',$updata);
            }

        }
        
        //echo "<pre>";
        //print_r($states);
    }

    public function fairCityexist()  //ajax use
    {
        $cityid     =   $_POST['city_id'];
        $serviceid  =   $_POST['serviceid'];
        $checkexist = $this->AuthModel->checkRows('fare',array('city_id'=>$cityid,'serviceType_id'=>$serviceid));
        if($checkexist>0)
        {
            echo 'This service type fare already set for this city';
        }
    }

    public function  checkTargetTrip()  //ajax use
    {
        if(isset($_POST['reward_type']) && isset($_POST['country'])){
            $reward_type = $_POST['reward_type'];        
            $country     = $_POST['country'];   
            $city        = $_POST['city'];
            //$response = array('error'=>1,'reward'=>$reward_type,'country'=>$country);
            //echo json_encode($response);die(); 
            $checkexist = $this->AuthModel->checkRows('driverweeklyreward',array('reward_type'=>$reward_type,'country'=>$country,'city'=>$city));
            if($checkexist>0)
            {
                $response = array('error'=>1,'message'=>'Target trip already exist for this county');
                echo json_encode($response);                
            }
            else{
                $response = array('error'=>0,'message'=>'success');
                echo json_encode($response); 
            }
        }
        else{
            $response = array('error'=>1,'message'=>'Something went wrong, Please try again');
            echo json_encode($response);                            
        }        
    }

    public function changeRewardStatus()  //ajax use
    {
        $rewardid = $_POST['rewardid'];
        $status   = $_POST['status'];
        if($this->AuthModel->updateRecord(array('reward_id'=>$rewardid),'driverweeklyreward',array('reward_status'=>$status)))
        {
            echo 'Status has been successfully updated';
        }
        else
        {
            echo 'Oops! Something went wrong, Please try again';
        }
    }

    public function fares()
    {
        $orderby  = "`fair_id` DESC";
        $where    = array();
        $fairlist = $this->AuthModel->getMultipleRecord('fare',$where,$orderby);
        if(!empty($fairlist))
        {
            $data['fairlist']=$fairlist;            
            $this->load->view('vehicle_fairs',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No vehicle fare list found';
            $data["fairlist"]=$fairlist;
            $this->load->view('vehicle_fairs',$data);
        }
    }
    public function update_laterBooking_status(){
        if(isset($_POST['fare_id']) && $_POST['fare_id']!=''){
            extract($_POST);
            if($this->AuthModel->updateRecord(array('fair_id'=>$fare_id),'fare',array('ride_later_status'=>$status))){
                echo 'Status has been successfully changed';
            }
            else{
                echo 'Oops! Error occured. Please try again.';
            }
        }
        else{
            echo 'Access denied';
        }
    }
    public function fare_full_details($fairid)
    {      
        $data['list'] = $this->AuthModel->getSingleRecord('fare',array('fair_id'=>$fairid));
        $this->load->view('Vehiclefair_fulldetails',$data);
    }

    public function fix_location()
    {
        $orderby  = "`location_id` DESC";
        $where    = array();
        $fixlocation = $this->AuthModel->getMultipleRecord('fixlocations',$where,$orderby);
        if(!empty($fixlocation))
        {
            $data['fixlocation']=$fixlocation;            
            $this->load->view('fixed_locations',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No fix location found';
            $data["fixlocation"]='';
            $this->load->view('fixed_locations',$data);
        }
    }

    public function add_fixLocation()
    {
        if(isset($_POST['submit']))
        {
            //echo "<pre>";
            extract($_POST);
            //print_r($_POST);die();
            $data = array(
                'pickup'=>$pickup,
                'pickupLat'=>$pickupLat,
                'pickupLong'=>$pickupLong,
                'dropoff'=>$dropoff,
                'dropoffLat'=>$dropofLat,
                'dropoffLong'=>$dropofLong,
                'fixcharge'=>$fixCharge,
                'startTime'=>$startTime,
                'endTime'=>$endTime,
                'service_id'=>$service_type,
                'service_name'=>$servicename,
                'free_waitingMin'=>$freeWaitingMinute,
                'waitingMinUnit'=>$waitingUnitTime,
                'waitingMinUnitCharge'=>$waitingMinUnitCharge
                );
            if($uid = $this->AuthModel->singleInsert('fixlocations',$data))
            {
                $respose["success"] = 1;
                $respose["message"] = "Fix loaction has been successfully saved";
                $this->load->view('add_fixedLocation',$respose);
            }
            else
            {
                $respose["error"] = 1;
                $respose["message"] = "Error occur! Please try again";
                $this->load->view('add_fixedLocation',$respose);
            } 
        }
        else
        {
            $this->load->view('add_fixedLocation');
        }        
    }


    public function changeStatus()
    {
        $obj   = $_POST['myData'];
        $array = json_decode($obj, true);
        if($this->AuthModel->updateRecord($array['where'],$array['table_name'],$array['data']))
        {
            echo 'Status has been successfully updated';
        }
        else
        {
            echo 'Oops! Something went wrong, Please try again';
        }
    }

    public function trip_earning(){
        $bookings = $this->AuthModel->getMultipleRecord('booking',array('booking_status'=>4),'booking_id DESC');
        if(!empty($bookings)){  
            $response = array('datalist'=>$bookings);          
            $this->load->view('trip_earning',$response);
        }else{
            $response = array('error'=>1,'message'=>'No record found','datalist'=>$bookings); 
            $this->load->view('trip_earning',$response);
        }        
    }

    public function daily_earning(){
        if(isset($_POST['search'])){
            extract($_POST);
            $earningDate_start = $_POST['date'];
            $earningDate_end   = $_POST['date'];
            $earningDatest = strtotime($earningDate_start.' 00:00');            
            $earningDatend = strtotime($earningDate_end.' 11:59 PM');
            $where = array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4);
            $bookings = $this->AuthModel->getMultipleRecord('booking',$where,'booking_id DESC');
            //print_r($this->db->last_query());die();
            if(!empty($bookings)){
                $response = array('datalist'=>$bookings,"driver_id"=>$driver_id,'earningDatest'=>$earningDatest,'earningDatend'=>$earningDatend);
                //print_r($response);die();
                $this->load->view('daily_earning',$response); 
            }
            else{      
                $response = array('error'=>1,'message'=>'No record found');          
                $this->load->view('daily_earning',$response); 
            }
        }                   
        else{                         
            $this->load->view('daily_earning'); 
        }
        
    }
    public function weekly_earning(){
        if(isset($_POST['search'])){
            extract($_POST);           
            $earningDate_start = date('d-m-Y',strtotime($date));
            $earningDate_end   = date('d-m-Y',strtotime('+7 days',strtotime($date)));
            //echo $earningDate_start;
            //echo $earningDate_end;die();
            
            $earningDatest = strtotime($earningDate_start.' 00:00');            
            $earningDatend = strtotime($earningDate_end.' 11:59 PM');
            $where = array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4);
            $bookings = $this->AuthModel->getMultipleRecord('booking',$where,'booking_id DESC');
            //print_r($this->db->last_query());die();
            if(!empty($bookings)){
                $response = array('datalist'=>$bookings,"driver_id"=>$driver_id,'earningDatest'=>$earningDatest,'earningDatend'=>$earningDatend);
                //print_r($response);die();
                $this->load->view('weekly_earning',$response); 
            }
            else{      
                $response = array('error'=>1,'message'=>'No record found');          
                $this->load->view('weekly_earning',$response); 
            }
        }                   
        else{                         
            $this->load->view('weekly_earning'); 
        }        
    }

    public function monthly_earning(){
        if(isset($_POST['search'])){
            extract($_POST);           
            $earningDate_start = date('d-m-Y',strtotime($date));
            $first_date        = date('1-m-Y',strtotime($date));
            $earningDate_end   = date("d-m-Y",strtotime("+1 month -1 second",strtotime($first_date)));
            //$earningDate_end   = date('d-m-Y',strtotime('+7 days',strtotime($date)));
            //echo $earningDate_start;
            //echo $earningDate_end;die();            
            $earningDatest = strtotime($earningDate_start.' 00:00');            
            $earningDatend = strtotime($earningDate_end.' 11:59 PM');
            $where = array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4);
            $bookings = $this->AuthModel->getMultipleRecord('booking',$where,'booking_id DESC');
            //print_r($this->db->last_query());die();
            if(!empty($bookings)){
                $response = array('datalist'=>$bookings,"driver_id"=>$driver_id,'earningDatest'=>$earningDatest,'earningDatend'=>$earningDatend);
                //print_r($response);die();
                $this->load->view('monthly_earning',$response); 
            }
            else{      
                $response = array('error'=>1,'message'=>'No record found');          
                $this->load->view('monthly_earning',$response); 
            }
        }                   
        else{                         
            $this->load->view('monthly_earning'); 
        }        
    }

    public function city_setting(){
        $setting = $this->AuthModel->getMultipleRecord('fare_city_setting',array(),'');
        if(!empty($setting)){
            $response = array('setting'=>$setting);
            $this->load->view('fare_city_setting',$response);
        }
        else{
            $response = array('error'=>1,'message'=>'No setting found','setting'=>$setting);
            $this->load->view('fare_city_setting',$response);   
        }
    }

    public function update_city_setting(){
        if(isset($_POST['city_setting_id']) && $_POST['city_setting_id']!=''){
            extract($_POST);
            //print_r($_POST);
            if($this->AuthModel->updateRecord(array('city_setting_id'=>$city_setting_id),'fare_city_setting',array("".$field_name.""=>$status,'update_at'=>$update_at)))
            {
                echo $field_name.' status has been successfully changed';
            }
            else{
                echo 'Opps! Something went wrong. Please try again';
            }
        }
        else{
            echo 'Something went wrong';
        }
    }
}