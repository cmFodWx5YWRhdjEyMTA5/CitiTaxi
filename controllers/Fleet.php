<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fleet extends CI_Controller {
	function __construct() {
        parent::__construct();  
        $this->load->library('session');
        if($this->session->userdata('email')=='')
        {
            redirect(base_url());
        }   
    }

    public function index()
    {
        $table_name = 'fleets';
        $orderby    = "";
        $where      = array();
        $fleets    = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($fleets);die();
        if(!empty($fleets))
        {
            $data['fleetlist']=$fleets;            
            $this->load->view('fleets',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No fleet found';
            $data["fleetlist"]='';
            $this->load->view('fleets',$data);
        }

    }
    
    public function add_fleet()
    {
        if(isset($_POST['submit']))
        {
            extract($_POST);
            //print_r($_POST);die();
            $table_name  = "fleets";
            $checkmail   = array("fleet_email"=>$fleet_email);
            $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            $checkMobile = array('fleet_phone' =>$fleet_mobile,'fleet_phone!='=>'');
            $mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);   
            if($checkEmail>0)
            {
                $respose["error"]=1;
                $respose["message"]="Email already Exist";
                $this->load->view('add_fleet',$respose);
            }           
            elseif($mobileExist>0)
            {   
                $respose= array("error"=>1,"message"=>"Mobile number has already registered");
                $this->load->view('add_fleet',$respose);
            }
            else
            {
                $imagename ='default.jpg';
                if(isset($_FILES['fleetimage']))
                {
                    $folder_name = 'fleetimage';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['fleetimage'],$folder_name);
                }
                $data= array(               
                    "fleet_name"    =>$name,
                    "fleet_phone"   =>$fleet_mobile,
                    "fleet_email"   =>$fleet_email,
                    "password"      =>$password,
                    "fleet_address" =>$address,
                    "fleet_company" =>$fleet_company,
                    'country_id'    =>$country_id,
                    'country'       =>$country,
                    'city_id'        =>$city_id,
                    "city"          =>$city,
                    "image"         =>$imagename,                                                             
                    );
                if($uid = $this->AuthModel->singleInsert($table_name,$data))
                {
                    $respose["success"] = 1;
                    $respose["message"] = "Fleet has been successfully saved";
                    $this->load->view('add_fleet',$respose);
                }              
                else
                {
                    $respose["error"] = 1;
                    $respose["message"] = "Error occur! Please try again";
                    $this->load->view('add_fleet',$respose);
                }
            }
        }
        else
        {
            $this->load->view('add_fleet');
        }
    }

    public function update_fleet($fleetid)
    {
        if($fleetid!='')
        {            
            $fleetdata = $this->AuthModel->getSingleRecord('fleets',array('fleet_id'=>$fleetid));
            if(isset($_POST['submit']))
            {
                extract($_POST);
                $checkMobile = $this->AuthModel->checkRows('fleets',array('fleet_phone'=>$fleet_mobiles));
                if($checkMobile>0 && $fleet_mobiles != $fleetdata->fleet_phone)
                {
                    $respose  = array("error"=>1,"message"=>"Mobile number already registered","details"=>$fleetdata);
                    $this->load->view('update_fleet',$respose);
                }
                else
                {
                    $imagename        = $fleetdata->image;                   
                    if(isset($_FILES['fleetimage']) && $_FILES['fleetimage']['name']!='')
                    {
                        $folder_name = 'fleetimage';
                        $imagename   = $this->AuthModel->imageUpload($_FILES['fleetimage'],$folder_name);
                    }
                    $updata = array(
                        "fleet_name"    =>$name,
                        "fleet_phone"   =>$fleet_mobiles,                                          
                        "fleet_address" =>$address,
                        "fleet_company" =>$fleet_company,                    
                        "image"         =>$imagename,  
                    );
                    
                    $UpdateData = $this->AuthModel->updateRecord(array('fleet_id'=>$fleetid),'fleets',$updata);
                    if($UpdateData)
                    {
                        $fleetdata = $this->AuthModel->getSingleRecord('fleets',array('fleet_id'=>$fleetid));
                        $response["success"]          = 1;
                        $response["message"]          = "Record has been successfully updated";
                        $response["details"]           = $fleetdata;
                        $this->load->view('update_fleet',$response);
                    }
                    else
                    {
                        $response["error"]              = 1;    
                        $response["message"]            = "Oops! Error occur. Please Try again";
                        $response["details"]           = $fleetdata;
                        $this->load->view('update_fleet',$response);
                    }
                }                 
            }   
            else
            {
                $data['details'] = $fleetdata; 
                $this->load->view('update_fleet',$data);    
            }
        }
        else
        {
            redirect(site_url('/Fleet'));
        }
    }

    
    public function checkEmail()          //for ajax use
    {
        $table_name  ="fleets";
        $checkmail   = array("fleet_email"=>$_POST['email']);
        $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail); 
        if($checkEmail>0)
        {
            echo json_encode(FALSE);
        }
        else
        {
            echo json_encode(true);
        }
    }

    public function checkMobile()       //for ajax use
    {
        $table_name="fleets";
        $checkmobile = array('fleet_phone' =>$_POST['mobile'],'fleet_phone!='=>'');
        $checkMmail  = $this->AuthModel->checkRows($table_name,$checkmobile); 
        if($checkMmail>0)
        {
            echo json_encode(FALSE);
        }
        else
        {
            echo json_encode(true);
        }
    }

    public function fleet_tracking()
    {       
        $this->load->view('fleetTracking');
    }

    public function search_driver(){   //On fleet Tracking page
        extract($_POST); 
        //echo json_encode($_POST);die();      
        if($driverid!=''){
            //echo '1';die();
            $ress = $this->AuthModel->getSingleRecord('driver_live_location',array('user_id'=>$driverid));
            if(!empty($ress)){
                $driver_data = $this->AuthModel->getSingleRecord('users',array('id'=>$driverid,'user_type'=>1));
                $name = ''; $id='';
                if(!empty($driver_data)){$name=$driver_data->name; $id=$driver_data->id;}
                $address = $ress->address;
                $location[] =array($ress->address,$ress->latitude,$ress->longitude,$name,$id,$email,$mobile); 
                //$locations = array($location);              
                echo json_encode($location);                
            }            
        }

        elseif($servicetype!='' && $country=='Select Country'){  //only service type
            //echo '2';die();
            $drivers = $this->AuthModel->getMultipleRecord('vehicle_servicetype',array('service_type_id'=>$servicetype),'');
            //print_r($this->db->last_query());die();
            if(!empty($drivers)){
                foreach ($drivers as $k => $d) {
                    $driver_ids[] = $d->driver_id;                    
                }

                if(!empty($driver_ids)){
                    $ress = $this->AuthModel->getWhereInRecord('driver_live_location','user_id',$driver_ids);
                    if(!empty($ress)){
                        foreach ($ress as $k => $l) {
                            $driverid = $l->user_id;
                            $driver_data = $this->AuthModel->getSingleRecord('users',array('id'=>$driverid,'user_type'=>1));
                            $name = ''; $id='';$email='';$mobile='';
                            if(!empty($driver_data)){$name=$driver_data->name;$id=$driver_data->id;$email=$driver_data->email;$mobile=$driver_data->mobile;}
                            $location[] =array($l->address,$l->latitude,$l->longitude,$name,$id,$email,$mobile); 
                        }                                
                        echo json_encode($location);                
                    }                    
                }  
            }            
        }

        elseif($servicetype!='' && $country!='Select Country'){  //service type country and city(is or not)
            //echo '3';die();          
            if($city=='Please Select city'){                
                $drivers = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'user_type'=>1),'');
            }else{
               $drivers = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'city'=>$city,'user_type'=>1),'');
            }   
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
                            $name = '';  $id='';$email='';$mobile='';
                            if(!empty($driver_data)){$name=$driver_data->name;$id=$driver_data->id;$email=$driver_data->email;$mobile=$driver_data->mobile;}
                            $location[] =array($l->address,$l->latitude,$l->longitude,$name,$id,$email,$mobile);                           
                        }                                
                        echo json_encode($location);                
                    }
                }
            }
        }

        elseif($servicetype=='' && $country!='Select Country'){  //country and city(is or not)
            //echo '4';die();          
            if($city=='Please Select city'){                
                $drivers = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'user_type'=>1),'');
            }else{
               $drivers = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'city'=>$city,'user_type'=>1),'');
            }               
            //echo json_encode($drivers);die();

            if(!empty($drivers)){
                foreach ($drivers as $k => $d) {                            
                   $driver_ids[] = $d->id;
                }                 
                if(!empty($driver_ids)){                                                                                            
                    $ress = $this->AuthModel->getWhereInRecord('driver_live_location','user_id',$driver_ids);
                    //print_r($this->db->last_query());die();
                    if(!empty($ress)){
                        foreach ($ress as $k => $l) {
                            $driverid = $l->user_id;
                            $driver_data = $this->AuthModel->getSingleRecord('users',array('id'=>$driverid,'user_type'=>1));
                            $name = ''; $id='';$email='';$mobile='';
                           if(!empty($driver_data)){$name=$driver_data->name;$id=$driver_data->id;$email=$driver_data->email;$mobile=$driver_data->mobile;}
                            $location[] =array($l->address,$l->latitude,$l->longitude,$name,$id,$email,$mobile);  
                        }                                
                        echo json_encode($location);                
                    }
                }
                                
            }
        }        
    }

    public function last_hour_booking(){ 
        if(isset($_POST['country']) && $_POST['city']){
            extract($_POST);
            //$current  =  date('d-m-Y h:i A');
            //$current  =  date('d-m-Y h:i A',strtotime('02-06-2018 11:50 AM'));
            //$start    =  date('d-m-Y h:i A',strtotime('-3 hour',strtotime($current)));
            $current  =  strtotime('14-06-2018 05:50 PM');
            $start    =  strtotime('-3 hour',strtotime($current));
            //echo $start;die();
            $booking  =  $this->AuthModel->getMultipleRecord('booking',array('booking_at_string>='=>$start,'booking_at_string<='=>$current,'country'=>$country,'city'=>$city),'booking_id DESC');
            if(!empty($booking)){  
                foreach ($booking as $key => $l) {
                    $latlng[] = array('lat'=>$l->pickupLat,'lng'=>$l->pickupLong);              
                }          
                $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$latlng);            
                echo json_encode($response);
            }else{
                $response = array('success'=>0,'error'=>1,'message'=>'No booking found in this city since last 3 hours','data'=>$booking);
                echo json_encode($response);
           }
        }   
        else{
            $response = array('success'=>0,'error'=>1,'message'=>'You have missed country and city');
            echo json_encode($response);
        }   
                
    }


    public function dispatchers()
    {
        $table_name = 'dispatch';
        $orderby    = "";
        $where      = array();
        $dispatchers    = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($fleets);die();
        if(!empty($dispatchers))
        {
            $data['dispatch']=$dispatchers;            
            $this->load->view('dispatchers',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'Dispatchers is not found';
            $data["dispatch"]= $dispatchers; 
            $this->load->view('dispatchers',$data);
        }

    }
    
    public function add_dispatcher()
    {
        if(isset($_POST['submit']))
        {
            extract($_POST);
            //print_r($_POST);die();
            $table_name  = "dispatch";
            $checkmail   = array("email"=>$dispatch_email);
            $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);                   
            if($checkEmail>0)
            {
                $respose["error"]=1;
                $respose["message"]="Email already Exist";
                $this->load->view('add_dispatcher',$respose);
            }                       
            else
            {
                $imagename ='default.jpg';
                if(isset($_FILES['image']))
                {
                    $folder_name = 'fleetimage';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
                }
                $data= array(               
                    "name"      =>$name,
                    "phone"     =>$dismobile,
                    "email"     =>$dispatch_email,
                    "password"  =>md5($password),
                    "pass"      =>$password,
                    "address"   =>$address,                    
                    'country'   =>$country,                    
                    "city"      =>$city,
                    "image"     =>$imagename,                                                             
                    );
                if($uid = $this->AuthModel->singleInsert($table_name,$data))
                {
                    $respose["success"] = 1;
                    $respose["message"] = "Dispatcher record has been successfully saved";
                    $this->load->view('add_dispatcher',$respose);
                }              
                else
                {
                    $respose["error"] = 1;
                    $respose["message"] = "Error occur! Please try again";
                    $this->load->view('add_dispatcher',$respose);
                }
            }
        }
        else
        {
            $this->load->view('add_dispatcher');
        }
    }

    public function update_dispatcher($dispatcher_id)
    {
        if($dispatcher_id!='')
        {            
            $dispdata = $this->AuthModel->getSingleRecord('dispatch',array('dispatcher_id'=>$dispatcher_id));
            if(isset($_POST['submit']))
            {
                extract($_POST);                
                $checkmail = $this->AuthModel->checkRows('dispatch',array('email'=>$dispatch_email));
                if($checkmail>0 && $dispatch_email != $dispdata->email)
                {
                    $respose  = array("error"=>1,"message"=>"This email-id has already registered","details"=>$dispdata);
                    $this->load->view('update_dispatcher',$respose);
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
                        $fleetdata = $this->AuthModel->getSingleRecord('dispatch',array('dispatcher_id'=>$dispatcher_id));
                        $response["success"]          = 1;
                        $response["message"]          = "Record has been successfully updated";
                        $response["details"]           = $fleetdata;
                        $this->load->view('update_dispatcher',$response);
                    }
                    else
                    {
                        $response["error"]              = 1;    
                        $response["message"]            = "Oops! Error occur. Please Try again";
                        $response["details"]           = $fleetdata;
                        $this->load->view('update_dispatcher',$response);
                    }
                }                 
            }   
            else
            {
                // print_r($dispdata);die();
                $data['details'] = $dispdata; 
                $this->load->view('update_dispatcher',$data);    
            }
        }
        else
        {
            redirect(site_url('/Fleet'));
        }
    }

        

    
}