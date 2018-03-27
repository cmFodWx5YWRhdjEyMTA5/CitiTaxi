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

    public function add_fair()
    {
        if(isset($_POST['submit']))
        {
            //echo "<pre>";
            //print_r($_POST);die();
            extract($_POST);
            $data = array(
                'serviceType_id'=>$service_type, 'service_name'=>$servicename, 'description'=>$description,
                'maximum_load'=>$maxload,'country_id'=>$country_id, 'country'=>$country, 
                'city_id'=>$city_id,'city'=>$city, 'currency'=>$currency,
                'vehicle_type'=>$vehicletype, 'company_comission_type'=>$commsiontype,
                'company_comission_rate'=>$commissionRate, 'distanceUnit'=>$distanceUnit,
                 //'preEverymin_charge'=>$preEverymin_charge,'afterEverymin_charge'=>$afterEverymin_charge,
                'minbase_fair'=>$minbase_fair,
                'min_distance'=>$minDistance, 'min_distUnit'=>$min_distUnit, 'mini_distancefair'=>$mini_distancefair,
                'regularChargeUpon'=>$regularChargeUponKm,'regularChargeUpon_unit'=>$regularChargeUpon_unit,'uponMinuteCharge'=>$uponMinuteCharge,

                'perMinChargeStatus'=>$perMinChargeStatus,'unitPerMinuteforCharge'=>$unitPerMinuteforCharge,
                'unitPerMinutecharge'=>$unitPerMinutecharge,

                'regFreeWaitingMinute'=>$regFreeWaitingMinute,
                'regWaitingUnitTime'=>$regWaitingUnitTime, 'regWaitingUnitTimePrice'=>$regWaitingUnitTimePrice,
                'morningChargeStatus'=>$morningChargeStatus,
                'morningSurchargeUnit'=>$morningSurchargeUnit, 'morningSurchargePrice'=>$morningSurchargePrice,
                'morningSurchargeTimeStart'=>$morningSurchargeTimeStart, 'morningSurchargeTimeEnd'=>$morningSurchargeTimeEnd,
                'eveningChargeStatus'=>$eveningChargeStatus, 'eveningSurchargeUnit'=>$eveningSurchargeUnit,
                'eveningSurchargePrice'=>$eveningSurchargePrice, 'eveningSurchargeTimeStart'=>$eveningSurchargeTimeStart,
                'eveningSurchargeTimeEnd'=>$eveningSurchargeTimeEnd, 'midNightChargeStatus'=>$midNightChargeStatus,
                'minNightSurchargeUnit'=>$minNightSurchargeUnit, 'minNightSurchargePrice'=>$minNightSurchargePrice,
                'minNightSurchargeTimeStart'=>$minNightSurchargeTimeStart, 'minNightSurchargeTimeEnd'=>$minNightSurchargeTimeEnd, 
                'peaHourkWaitingChargeStatus'=>$peaHourkWaitingChargeStatus, 'peakChargeAfterStart'=>$peakChargeAfterStart,
                'peakUnitTimePriceMin'=>$peakUnitTimePriceMin, 'peakUnitTimePrice'=>$peakUnitTimePrice,
                'cancelChargeUnitDriver'=>$cancelChargeUnitDriver, 'stndCancelChargeDriver'=>$stndCancelChargeDriver,
                'peakHrCancelChargeDriver'=>$peakHrCancelChargeDriver, 'cancelChargeUnitPassenger'=>$cancelChargeUnitPassenger, 
                'stndCancelChargePassenger'=>$stndCancelChargePassenger,'peakHrCancelPassengerStatus'=>$peakHrCancelPassengerStatus,
                'peakHrCancelChargePassenger'=>$peakHrCancelChargePassenger, 
                'peakHourBookingCancelbyPassenger'=>$peakHourBookingCancelbyPassenger,'multiStopCharge'=>$multiStopCharge
                );

            //print_r($data);die();
            if($uid = $this->AuthModel->singleInsert('fair',$data))
            {
                $respose["success"] = 1;
                $respose["message"] = "Vehicle Fair has been successfully saved";
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

    public function cities($countryid)
    {
        $table_name = 'cities';
        $where = array('country_id'=>$countryid); $orderby = "`name`,'ASC'";
        $currency = $this->AuthModel->getSingleRecord('countries',array('id'=>$countryid))->currency_symbol;
        $cities = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        if(!empty($cities)){
            foreach($cities as $key =>$v)
            {
                $data[]='<option value="'.$v->id.'">'.$v->name.'</option>';                
            }
            $d['data']=$data;
            $d['currency'] = $currency;
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
        $checkexist = $this->AuthModel->checkRows('fair',array('city_id'=>$cityid,'serviceType_id'=>$serviceid));
        if($checkexist>0)
        {
            echo 'This service type fair already set for this city';
        }
    }

    public function  checkTargetTrip()  //ajax use
    {
        $targetTrip =$_POST['targetTrip'];        
        $checkexist = $this->AuthModel->checkRows('driverweeklyreward',array('reward_type'=>'min','weeklyTargetTrip'=>$targetTrip));
        if($checkexist>0)
        {
            echo $targetTrip.' target trip already exist for driver reward';
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

    public function fairs()
    {
        $orderby  = "`fair_id` DESC";
        $where    = array();
        $fairlist = $this->AuthModel->getMultipleRecord('fair',$where,$orderby);
        if(!empty($fairlist))
        {
            $data['fairlist']=$fairlist;            
            $this->load->view('vehicle_fairs',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No vehicle fair list found';
            $data["fairlist"]='';
            $this->load->view('vehicle_fairs',$data);
        }
    }

    public function fair_full_details($fairid)
    {
        $data['list'] = $this->AuthModel->getSingleRecord('fair',array('fair_id'=>$fairid));
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
}