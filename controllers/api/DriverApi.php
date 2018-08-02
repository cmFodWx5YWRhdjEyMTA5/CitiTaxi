<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class DriverApi extends CI_Controller {
	function __construct() {
        parent::__construct();   
    }

    public function index()
    {
        $respose["success"] = 0;
        $respose["error"]=400;
        $respose["message"]="Access Denied";
        echo json_encode($respose);
    }

    public function login()
    {
        //Login type = 0=>simple login, 1= fb_login 2=google login
        //isset param => login_type,device_token,device_type,email,password,mobile,media_id
        $response = array("success" => 0, "error" => 0);
        extract($_REQUEST);
        $table_name = 'users';
        if(isset($_REQUEST['login_type']) && $_REQUEST['login_type']==0)
        {
            if((isset($_REQUEST['login']) && $_REQUEST['login']!=''))
            {
                $checkWhere  = array("mobile"=>$login,"password"=>$password,"user_type"=>1);   
                $checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
                //echo $checkCrediantial;die();
                $activeWhere = array("mobile"=>$login,"activeStatus"=>'Active',"user_type"=>1);
                if($checkCrediantial==0)
                {
                    $checkWhere =  array("email"=>$login,"password"=>$password,"user_type"=>1); 
                    $activeWhere = array("email"=>$login,"activeStatus"=>'Active',"user_type"=>1);
                    $checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
                }           
                $data = ''; 
                //print_r($checkWhere);die();               
                $checkCrediantial = $this->AuthModel->checkRows($table_name,$checkWhere);
                if($checkCrediantial>0)
                {
                    $this->AuthModel->checkActiveStatus($table_name,$activeWhere);      //Check, User is Active or not by admin;
                    $upData     = array("device_token"=>$device_token,"device_type"=>$device_type,'wronglyPassword'=>0);
                    $this->AuthModel->updateRecord($checkWhere,$table_name,$upData);
                    $data       = $this->AuthModel->getSingleRecord($table_name,$checkWhere);   
                    $dataResponse     = $this->AuthModel->keychange($data);
                    $response  = array("success"=>1, "error"=>0, "message"=>"success", "data"=>$dataResponse);
                    echo json_encode($response);
                }
                else
                {
                    $this->AuthModel->passwordAttempt($table_name,$checkWhere);
                    $response  = array("error"=>1,"success"=>0,"message"=>"Invalid Crediantial","data"=>'');
                    echo json_encode($response);
                }
            }
            else{
                $this->index();
            }
        }
        elseif (isset($_REQUEST['login_type']) && ($_REQUEST['login_type']==1 || $_REQUEST['login_type']=2)) {
            if(isset($_REQUEST['media_id']) && $_REQUEST['media_id']!='')
            {     
                $user_type = 1;          
                $data = $this->AuthModel->loginViaMedia($media_id,$login,$login_type,$device_token,$device_type,$user_type);
                $dataResponse     = $this->AuthModel->keychange($data);
                $response  = array("success"=>1,"error" => 0,"message"=>"success","data"=>$dataResponse);
                echo json_encode($response);
            }   
            else{
                $this->index();
            }
        }
        else
        {
            $this->index();
        }
    }

    public function get_dirverProfile()        //driver details
    {
        if(isset($_POST['driver_id']) && (isset($_POST['tag']) && $_POST['tag']=='driver'))
        {
            $driverid = $_POST['driver_id'];
            $table_name = 'users';
            $where = array('id'=>$driverid);
            $drivers = $this->AuthModel->getSingleRecord($table_name,$where);
            if(!empty($drivers))
            {
                $orderby  = "`image_id` DESC";
                $vechileDetails = $this->AuthModel->getSingleRecord('vechile_details',array('driver_id'=>$driverid));
                $licenseDetails = $this->AuthModel->getSingleRecord('driver_license',array('user_id'=>$driverid));
                $Images  = $this->AuthModel->getMultipleRecord('vechile_images',array('driver_id'=>$driverid),$orderby);
                //echo json_encode($Images);die();

                $data['basic']=$this->AuthModel->keychange($drivers);
                $data['vechile']= $vechileDetails;                
                $data['vechileImages']=$Images;
                $data['vehicleImage_url']=base_url('vechicleImage');
                $data['license']=$this->AuthModel->keychange($licenseDetails);

                $respose = array("success"=>1,"error"=>0,"message"=>"success","data"=>$data);
                echo json_encode($respose);
            }
            else
            {
                $respose = array("success"=>0,"error"=>1,"message"=>"Something went wrong","data"=>array());
                echo json_encode($respose);
            }
        }
        else
        {
            $this->index();
        }
    }

    public function driver_signup()
    {
        if(isset($_POST['mobile']) && $_POST['mobile']!='')
        {
            extract($_POST);
            $table_name  = "users";
            $checkEmail =0;
            if($email!='')
            {                
                $checkmail   = array("email"=>$email,"user_type"=>1);
                $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            }
            $checkMobile = array('mobile'=>$mobile,"user_type"=>1);
            $mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);   
            if($checkEmail>0)
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Email already Exist");
                echo json_encode($response);
            }           
            elseif($mobileExist>0)
            {   
                $response= array("success"=>0,"error"=>1,"message"=>"Mobile number has already registered");
                echo json_encode($response);
            }
            else
            {
                if(isset($_POST['referral_code']) && $_POST['referral_code']!=''){
                    $referral_code = $_POST['referral_code'];
                    $checkreferral = $this->AuthModel->checkRows($table_name,array('ref_code'=>$referral_code,'user_type'=>1));
                    if($checkreferral==0){
                        $respose= array("success"=>0,"error"=>1,"message"=>"Invalid Referral Code!");
                        echo json_encode($respose);die();
                    }                       
                }
                $data = array(
                        "google_id"=>$google_id,
                        "ref_code"=>$this->AuthModel->radomno(6),
                        'name'=>$name,
                        'mobile'=>$mobile,
                        'email'=>$email,   
                        'address'=>$address,                 
                        "activeStatus" =>'Inactive',        //Active, Inactive
                        "signup_status"=>'incomplete',
                        "user_type"=>1,
                        "device_type"=>$device_type

                    );                
                if($uid = $this->AuthModel->singleInsert('users',$data))
                {    
                    if(isset($_POST['referral_code']) && $_POST['referral_code']!=''){
                        $referral_code = $_POST['referral_code'];
                        $this->AuthModel->saveReferralDiscount($uid,$name,$referral_code,$user_type);                               
                    }                
                    if(isset($_FILES['licenseimage']) && $_FILES['licenseimage']['name']!='')
                    {
                        $folder_name  = 'licenseImage';
                        $licenseimage = $this->AuthModel->imageUpload($_FILES['licenseimage'],$folder_name);
                        $licenseData  = array(
                            "user_id" =>$uid,                            
                            "licenseImage"=>$licenseimage
                        );
                        $this->AuthModel->singleInsert('driver_license',$licenseData);
                    }
                    if(isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']!='')
                    {                        
                       $vehicleimages =  $this->AuthModel->MultipleUpload($_FILES['vehicle_image'],'vechicleImage');
                       for($i=0; $i<count($vehicleimages); $i++) 
                        {
                            $vdata =array('driver_id'=> $uid,'vechile_image'=> $vehicleimages[$i]);
                            $this->AuthModel->singleInsert('vechile_images',$vdata);
                            $data ='';              
                        } 
                    }
                    if(isset($_FILES['otherdocument']) && $_FILES['otherdocument']['name']!='')
                    {
                        $type = $_FILES['otherdocument']['type'];
                        $otherdocument = $this->AuthModel->imageUpload($_FILES['otherdocument'],'otherdocument');
                        $odata =array('user_id'=> $uid,'document'=> $otherdocument,'document_type'=>$type);
                        $this->AuthModel->singleInsert('driver_otherdocument',$odata);
                    }                    
                    $response["success"] = 1;
                    $response["message"] = "Thanks for connecting us! We will contact you soon on registered mobile number or email";
                    $response["driver_id"] = $uid;
                    echo json_encode($response);
                }
                else
                {
                    $response["error"] = 1;
                    $response["message"] = "Error occur! Please try again";
                    $response["driver_id"] = '';
                    echo json_encode($response);
                }               
            }
        }
    }    

    public function uploadVehicleImage()
    {

        $car_images='';
        foreach($_FILES['vehicle_image']['tmp_name'] as $key => $tmp_name )
        {
            $rand =$this->radomno();
            $type = $_FILES['vehicle_image']['type'][$key];
            $image_name = $rand.$_FILES['vehicle_image']['name'][$key];
            $s =  $_FILES['vehicle_image']['tmp_name'][$key];
            $name = preg_replace('/\s*/m', '',$image_name);
            $d = "vechicleImage/".$name;
            $car_images[]= $name;  
            $types[]=$type;
            move_uploaded_file($s,$d);              
        }
        for($i=0; $i<count($car_images); $i++) 
        {
            $data =array('driver_id'=> $user_id,'vehicle_image'=> $car_images[$i]);
            $this->AuthModel->singleInsert('vehicle_images',$data);
            $data ='';              
        }        
    }

    public function addDriver()
    {
        if(isset($_POST['email']) && $_POST['email']!='')
        {
            extract($_POST);
            $table_name  = "users";
            $checkmail   = array("email"=>$email);
            $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            $checkMobile = array('mobile' =>$mobile,'mobile!='=>'');
            $mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);   
            if($checkEmail>0)
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Email already Exist");
                echo json_encode($response);
            }           
            elseif($mobileExist>0)
            {   
                $response= array("success"=>0,"error"=>1,"message"=>"Mobile number has already registered");
                echo json_encode($response);
            }
            else
            {
                $imagename ='default.jpg';
                if(isset($_FILES['image']))
                {
                    $folder_name = 'userimage';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
                }
                if($image_type==1 && $media_image!='')          //when media image isset
                {
                    $imagename =  $media_image;
                }
                $data= array(
                    "ref_code"      =>$this->AuthModel->radomno(6),
                    "fb_id"         =>$fb_id,
                    "google_id"     =>$google_id,
                    "device_token"  =>$device_token,
                    "user_type"     =>1,           //0=customer, 1= driver
                    "name"          =>$name,
                    "dob"           =>$dob,
                    "gender"        =>$gender,
                    "mobile"        =>$mobile,
                    "email"         =>$email,
                    "password"      =>$password,
                    "image"         =>$imagename,
                    "image_type"    =>$image_type,          //0=normal, 1=media
                    "nationality"   =>$nationality,
                    "city"          =>$city,
                    "address"       =>$address,
                    "activeStatus"  =>'Active',        //Active, Inactive
                    "device_type"   =>$device_type,    //0=android, 1=ios, 2=web                    
                    "signup_status" =>'incomplete'
                    );
                if($uid = $this->AuthModel->singleInsert($table_name,$data))
                {
                    $response["success"] = 1;
                    $response["message"] = "Success";
                    $response["driver_id"] = $uid;
                    echo json_encode($response);
                }
                else
                {
                    $response["error"] = 1;
                    $response["message"] = "Error occur! Please try again";
                    $response["driver_id"] = '';
                     echo json_encode($response);
                }
            }
        }
        else{            
            $this->index();
        }
    }

    public function addDriver_otherDetail()
    {
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='')
        {
            extract($_POST);
            if($service_type=='')
            {
                $response = array('success'=>0,'error'=>1,'message'=>'Please Select atleast 1 service','driver_id'=>$driver_id);
                echo json_encode($response);
            }
            else
            {
                $uid = $_POST['driver_id'];
                $bankDetails = array(
                            "user_id"=>$uid,
                            "bankName"=>$bankname,
                            "branchCode_Name"=>$branchCode_Name,
                            "accountNo"=>$accountNo,
                            );

                $vechileDetails = array(
                    "driver_id"=>$uid,
                    "brand"=>$brand,
                    "sub_brand"=>$subbrand,
                    "number_plate"=>$vehicle_NoPlate,
                    "insurance_company"=>$insuranceCompany,
                    "insurance_no"=>$insuranceNumber,
                    "insurance_expire"=>$insuranceExpiredate,                        
                    "fleet_company"=>$fleet_company,
                    "fleet_country"=>$fleet_country,
                    "fleet_address"=>$fleet_address,
                    "booking_limit"=>$bookingLimit
                    );

                $licenseimage ='defaultLicense.jpg';
                if(isset($_FILES['licenseimage']) && $_FILES['licenseimage']!='')
                {
                    $folder_name = 'licenseImage';
                    $licenseimage   = $this->AuthModel->imageUpload($_FILES['licenseimage'],$folder_name);
                }
                $licenseData = array(
                    "user_id"=>$uid,
                    "licenseNumber"=>$licenseno,
                    "expireDate"=>$expiredate,
                    "expireDateString"=>strtotime($expiredate),
                    "licenseImage"=>$licenseimage
                );

                // ======================================================================================//

                $table_name = "bankdetails";
                $checkbank  = array('user_id' =>$uid,'bankName'=>$bankname);
                $bankExist = $this->AuthModel->checkRows($table_name,$checkbank);   
                $checkSuccess='';
                if($bankExist==0)
                {
                    if($this->AuthModel->singleInsert($table_name,$bankDetails))
                    { $checkSuccess=true;}
                    else{
                        $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                        echo json_encode($response);die();
                    }
                }
                else          //if already exist update bank details
                {   
                    $checkWhere = array('user_id'=>$uid,'bankName'=>$bankname);
                    $updata = array('branchCode_Name'=>$branchCode_Name,'accountNo'=>$accountNo);
                    if($this->AuthModel->updateRecord($checkWhere,'bankdetails',$updata))
                    { $checkSuccess=true;}
                    else{
                        $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                        echo json_encode($response);die();
                    }
                }
                if($checkSuccess==true)           //Bank details successfully saved next save vehicle details
                {                    
                    $checkvechile  = array('driver_id' =>$uid);
                    $vechileExist  = $this->AuthModel->checkRows('vechile_details',$checkvechile);   
                    if($vechileExist==0)
                    {
                        if($vid= $this->AuthModel->singleInsert('vechile_details',$vechileDetails))
                        {
                            $checkSuccess=true;
                            $service_type= explode(',',$service_type);
                            foreach($service_type as $k =>$v)
                            {
                                $c["driver_id"]    = $uid;
                                $c["vehicle_id"]   = $vid;
                                $c["service_type"] =  $v;                
                                $service_types[]=$c;
                            }
                            if(!empty($service_types)){                            
                                $this->AuthModel->batchInsert('vehicle_servicetype',$service_types);
                            }
                        }
                        else{
                            $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                            echo json_encode($response);die();
                        }
                    }
                    else        //if vehicle already exist update vehicle details
                    {   
                        unset($vechileDetails['driver_id']);
                        if($this->AuthModel->updateRecord($checkvechile,'vechile_details',$vechileDetails)){$checkSuccess=true;}
                        else{
                            $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                            echo json_encode($response);die();
                        }
                    }

                    if($checkSuccess==true)  //vehicle details successfully saved next save license details
                    {   
                        $table_name    = "driver_license";
                        $checkLicense  = array('user_id' =>$uid);
                        $LicenseExist  = $this->AuthModel->checkRows($table_name,$checkLicense); 
                        if($LicenseExist==0)
                        {                            
                            if($this->AuthModel->singleInsert($table_name,$licenseData))
                            {
                                $response = array("success"=>1,"error"=>0,"message"=>"success","driver_id"=>$uid);
                                echo json_encode($response);die();
                            }
                            else
                            {
                                $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                                echo json_encode($response);die();
                            }
                        }
                        else{
                            unset($licenseData['user_id']);
                            if($this->AuthModel->updateRecord($checkLicense,$table_name,$licenseData)){
                                $response = array("success"=>1,"error"=>0,"message"=>"success","driver_id"=>$uid);
                                echo json_encode($response);die();
                            }
                            else{
                                $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                                echo json_encode($response);die();
                            }
                        }
                    }
                }
                else{
                    $response = array("success"=>0,"error"=>2,"message"=>"Something went wrong, Please try again","driver_id"=>$uid);
                    echo json_encode($response);die();
                }
            }
        }
        else
        {
            $this->index();
        }
    }


    public function driver_live_location()
    {
        if(isset($_POST['user_id']) && $_POST['user_id']!='')
        {
            extract($_POST);
            $updata= array(
                'user_id'=>$user_id,
                'address'=>$address,
                'latitude'=>$latitude,
                'longitude'=>$longitude
                );
            $checkExist = $this->AuthModel->checkRows('driver_live_location',array('user_id'=>$user_id));
            if($checkExist>0)
            {
                if($this->AuthModel->updateRecord(array('user_id'=>$user_id),'driver_live_location',$updata))
                {
                    $response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$updata);
                    echo json_encode($response);
                }
                else
                {
                    $response = array("success"=>0,"error"=>1,"message"=>"not success","data"=>'');
                    echo json_encode($response);
                }
            }
            else
            {
                if($this->AuthModel->singleInsert('driver_live_location',$updata))
                {
                    $response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$updata);
                    echo json_encode($response);
                }
                else
                {
                    $response = array("success"=>0,"error"=>1,"message"=>"not success","data"=>'');
                    echo json_encode($response);
                }
            }
        }
        else
        {
            $this->index();
        }
    }

    public function changeStatus()  //change online,offline or power off,on
    {
        //status_type  = power,online_status
        //status = power(on,off),online_status('online','offline')
        if(isset($_POST['user_id']) && $_POST['user_id']!='' && isset($_POST['status_type']) && $_POST['status_type']!='')
        {
            extract($_POST);
            $where = array('id'=>$user_id);
            $this->AuthModel->checkActiveStatus('users',array('id'=>$user_id));      //Check, User is Active or not by admin;
            if($status_type=='power')
            {
                $upStatus = $this->AuthModel->updateRecord($where,'users',array('power_status'=>$status));
            }
            elseif($status_type=='online_status')
            {
                $upStatus = $this->AuthModel->updateRecord($where,'users',array('online_status'=>$status));
            }
            elseif($status_type=='destination_status')  //on,off
            {
                $destination = $this->AuthModel->getSingleRecord('driver_destination',array('driver_id'=>$user_id));
                if(!empty($destination))
                {
                    $upStatus = $this->AuthModel->updateRecord($where,'users',array('destination_status'=>$status));               
                }
                else
                {
                    $response = array("success"=>0,"error"=>1,"message"=>"You have not set his destination yet.","data"=>""); 
                    echo json_encode($response);die(); 
                }                
            }
            else
            {
                $response = array('success'=>0,'error'=>2,'message'=>'invalid status_type');
                echo json_encode($response);
            }

            if($upStatus)
            {
                $userdata = $this->AuthModel->getSingleRecord('users',$where);
                $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$userdata);
                echo json_encode($response);
            }
            else
            {
                $response = array('success'=>0,'error'=>1,'message'=>'status is not update, please try again','data'=>'');
                echo json_encode($response);
            }
        }
        else
        {
            $this->index();
        }
    }

    public function tripRequest()    //for home page
    {
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='')
        {
            extract($_POST);
            $tripRequest = $this->BookingModel->getTripRequest($driver_id);
            if(!empty($tripRequest))
            {  
                $booking_id = $tripRequest->booking_id;
                $dropoffs = $this->AuthModel->getMultipleRecord('booking_dropoffs',array("booking_id"=>$booking_id),"");
                $tripdata = $this->AuthModel->keychange($tripRequest);
                $tripData = array(
                    "booking_id"=>$tripdata->booking_id,
                    "booking_id_show"=>$tripdata->booking_id_show,                    
                    "customer_id"=>$tripdata->customer_id,
                    "driver_id"=>$tripdata->driver_id,
                    "booking_address_type"=>$tripdata->booking_address_type,
                    "driver_note"=>$tripdata->booking_note,
                    "pickup"=>$tripdata->pickup,
                    "pickupLat"=>$tripdata->pickupLat,
                    "pickupLong"=>$tripdata->pickupLong,
                    "dropoffLocation"=>$dropoffs,
                    "total_distance"=>$tripdata->total_distance,
                    "distance_unit"=>$tripdata->distance_unit,
                    "total_fare"=>$tripdata->total_fare,
                    "currency"=>$tripdata->currency,
                    "servicename"=>$tripdata->servicename,
                    "customer_name"=>$tripdata->name,
                    "customer_email"=>$tripdata->email,
                    "customer_mobile"=>$tripdata->mobile,
                    "image"=>$tripdata->image
                );
                $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$tripData);
                echo json_encode($response);
            }
            else
            {
                $response = array('success'=>0,'error'=>1,'message'=>'No New request','data'=>'');
                echo json_encode($response);   
            }
        }
        else
        {
            $this->index();
        }
    }

    public function TripRequestStatus()
    {
        if(isset($_POST['booking_id']) && $_POST['booking_id']!='' && isset($_POST['booking_status']) && $_POST['booking_status']!='' )
        {
            $data_val = array('booking_id','driver_id','customer_id','booking_status','date','time');
            $validation = $this->AuthModel->checkRequiredParam($data_val,$_POST);
            if(isset($validation['status']) && $validation['status']=='0')
            {
                echo json_encode(array('response'=>'false','message'=>$validation['message']));die;
            }
            else
            { 
                //params  = booking_id,booking_status,driver_id
                extract($_POST);
                $action_at = $date.' '.$time;
                $bookingData = $this->AuthModel->getSingleRecord('booking',array('booking_id'=>$booking_id));
                if(!empty($bookingData)){
                    $preStatus = $bookingData->booking_status;
                    //booking_status => 0=assigned 1=accept 2=reject by driver 4= done 5=arrived  6=trip start
                    if($booking_status==1)  //accept
                    {                       
                        //Change online status so other request will not get.
                        $this->AuthModel->updateRecord(array("id"=>$driver_id),'users',array("online_status"=>'offline'));
                        $updata = array("booking_status"=>$booking_status);                        
                    }          
                    elseif($booking_status==5){  // Arrived                                            
                        $updata = array("driver_arrived_at"=>$action_at,"booking_status"=>$booking_status);                        
                    }
                    elseif($booking_status==6)  // Trip Start
                    {               
                        if($preStatus==5){
                            $booking_detail = $this->AuthModel->getSingleRecord('booking',array("booking_id"=>$booking_id));
                            $fairDetails  = $this->AuthModel->getSingleRecord('fare',array("serviceType_id"=>$booking_detail->service_typeid,"country"=>$booking_detail->country,"city"=>$booking_detail->city));
                            
                            $commissionData = array("booking_id"=>$booking_id,"driver_id"=>$driver_id,"currency"=>$booking_detail->currency,"commission_type"=>$fairDetails->company_comission_type,"commission_rate"=>$fairDetails->company_comission_rate,"commission_at"=>$date.' '.$time);
                            $this->AuthModel->checkThenInsertorUpdate('booking_earning',$commissionData,array('booking_id'=>$booking_id)); // save company commission
                            $datetime1 = new DateTime($action_at);
                            $datetime2 = new DateTime($booking_detail->driver_arrived_at);
                            $interval = $datetime2->diff($datetime1);
                            $total_waiting= $interval->format('%i');
                            // $total_waiting  = date('i',strtotime($action_at)-strtotime($booking_detail->driver_arrived_at));

                           $updata = array("ride_start_at"=>$action_at,"waiting_time"=>$total_waiting,"booking_status"=>$booking_status);
                        }
                        else{
                            $respose = array("error"=>1,"success"=>0,"message"=>"Now Driver has not clicked on arrived");
                            echo json_encode($respose);die();
                        }
                    }
                    else
                    {
                        $respose = array("error"=>1,"success"=>0,"message"=>"Invalid request");
                        echo json_encode($respose);exit;
                    }
                    if($this->AuthModel->updateRecord(array("booking_id"=>$booking_id),'booking',$updata))
                    {                 
                        $respose = array("success"=>1,"error"=>0,"message"=>"Trip status has been successfully saved");
                        echo json_encode($respose);
                    }   
                    else
                    {
                        $respose = array("error"=>1, "success"=>0,"message"=>"Oops! Something went wrong, Please try again");
                        echo json_encode($respose);
                    }
                }
                else{
                    $respose = array("error"=>1, "success"=>0,"message"=>"Invalid booking request");
                    echo json_encode($respose);
                }
            }            
        }
        else
        {
            $this->index();
        }
    }

    /*public function TripRejectByDriver()
    {
        if(isset($_POST['booking_id']) && $_POST['booking_id'] && isset($_POST['driver_id']) && $_POST['driver_id']!='')
        {            
            $this->AuthModel->getSingleRecord('');
            $updata = array("booking_status"=>2,"cancel_reason"=>$cancel_reason);
            if($this->AuthModel->updateRecord(array("booking_id"=>$booking_id),'booking',$updata))
            {
                $this->AuthModel->updateRecord(array("id"=>$driver_id),'users',array("online_status"=>'online'));
                $record = array("booking_id"=>$booking_id,"cancelby_id"=>$driver_id,"cancel_reason"=>$cancel_reason);
                $this->AuthModel->singleInsert('booking_cancel_record',$record);                
            }
            else
            {
                $respose = array("error"=>1,"success"=>0,"message"=>"Oops! Something went wrong, Please try again");
                echo json_encode($respose);
            }            
        }
    }*/

    public function TripComplete()
    {
        if(isset($_POST['booking_id']) && $_POST['booking_id']!='' && isset($_POST['driver_id']) && $_POST['driver_id']!='')
        {
            //booking_id,driver_id,customer_id 
            extract($_POST);            
            $customerNewScore=0;
            $action_at = $date.' '.$time;
            $finalFair = $this->calculateFair($booking_id,$total_distance,$total_ride_time);   //calculate final fair
            if(!empty($finalFair)){
                $checkWhere = array("booking_id"=>$booking_id);
                $bookingUpdata = array("ride_complete_at"=>$action_at,"total_ride_time"=>$total_ride_time,"total_distance"=>$total_distance,"total_fare"=>$finalFair["total_fair"],"promo_earn"=>$finalFair['bonus'],"booking_status"=>4);
                $fairUpdata = array("total_regular_charge"=>$finalFair['total_regular_charge'],"total_per_minute_charge"=>$finalFair['total_per_minute_charge'],"total_waiting_charge"=>$finalFair['total_waiting_charge'],"total_surcharge"=>$finalFair['total_surcharge']);
                //print_r($fairUpdata);die();
                $companyComm = $this->AuthModel->getSingleRecord('booking_earning',array("booking_id"=>$booking_id));
                if($companyComm->commission_type=='Per')   //For company commission
                {
                    $total_commission = ($finalFair["total_fair"]*$companyComm->commission_rate)/100;
                }
                else{
                    $total_commission = $companyComm->commission_rate;
                }
             //========================================= Driver Score Section ======================================//
                $score = $this->AuthModel->getSingleRecord('users_score',array("user_id"=>$driver_id)); //get previous score
                if($score->total_score<=0){
                    $newScore = $score->total_score+10;                    
                }
                elseif($score->total_score==10){
                    $newScore = $score->total_score;
                }
                else{
                    $newScore = $score->total_score+0.1;
                }
                // echo $newScore;die();   
                //========================================= Customer point Section =================================//
                //Customer will got point if total fare will greater then 100. If total fair 300 then got 300%100=3
                $bookingUpdata['customer_trip_score'] =0;
                $country = $finalFair['country']; $city= $finalFair['city'];
                $pointdata = $this->AuthModel->getSingleRecord('point_system',array('country'=>$country,'city'=>$city));
                if(!empty($pointdata)){
                    $minamount = $pointdata->every_amount_spent;
                    if($finalFair["total_fair"]>=$minamount){
                        $point = (round($finalFair['total_fair']/$minamount,2))%100;
                        $gainpoint = $point*$pointdata->get_point;
                        $bookingUpdata['customer_trip_score'] = $gainpoint;  
                        $this->update_passenger_points($customer_id,$gainpoint); //Update points in user table                        
                    }
                }
                //print_r($bookingUpdata);die();
                //========================================= Customer point Section end =================================//  
                if($this->AuthModel->updateRecord($checkWhere,'booking',$bookingUpdata)){
                    if($this->AuthModel->updateRecord($checkWhere,'booking_fare',$fairUpdata)){   
                       //update score
                        $this->AuthModel->updateRecord(array("user_id"=>$driver_id),'users_score',array('total_score'=>$newScore));

                        //update driver booking commission
                        $driver_earning  = $finalFair["total_fair"]-$total_commission;
                        $commission_data = array('booking_id'=>$booking_id,'driver_id'=>$driver_id,'total_commission'=>$total_commission,"driver_earning"=>$driver_earning,"total_fare"=>$finalFair["total_fair"],"status"=>1,"commission_at"=>$action_at,"commission_at_string"=>strtotime($action_at));

                        $this->AuthModel->checkThenInsertorUpdate('booking_earning',$commission_data,array("booking_id"=>$booking_id));
                        //Update promo history status if promocode has applied
                        if($finalFair['promo_status']=='Yes' && $finalFair['promo_type']=='ride'){
                            $this->updatePromoHistory($booking_id,$customer_id,$finalFair["total_fair"]);                            
                        }

                        //Check for referral bonus if promocode not apply
                        if($finalFair['promo_status']=='No'){
                            $this->checkReferralBonus($booking_id,$customer_id,$finalFair['currency']);   
                        }
                        //Check driver weekly complete trip reward
                        $this->driver_weeklyReward($driver_id,$country,$city);

                        //update driver online_Status
                        //$this->AuthModel->updateRecord(array("id"=>$driver_id),'users',array('online_status'=>'online'));
                        //check user referral bonus

                        $respose = array("success"=>1, "error"=>0,"message"=>"Thanks for using CitiTaxi. Trip has been successfully completed","total_fair"=>$finalFair["total_fair"].' '.$finalFair['currency']);
                        echo json_encode($respose);    
                    }
                    else{
                        $respose = array("success"=>0, "error"=>1,"message"=>"Oops! Something went wrong. Trip details is not saved. Please try again","total_fair"=>$finalFair["total_fare"].' '.$finalFair['currency']);
                        echo json_encode($respose);  
                    }                    
                }
                else{
                    $respose = array("success"=>0, "error"=>1,"message"=>"Oops! something went wrong, Please try again");
                    echo json_encode($respose);
                } 
            }
            else{
                $response = array("success"=>0,"error"=>1,"message"=>"Something wrong in fair details. Please try again");
                echo json_encode($response);
            }                      
        }
        else{
            $this->index();
        }
    }


    public function update_passenger_points($passenger_id,$gainpoint){
        $user_record = $this->AuthModel->getSingleRecord('users',array('id'=>$passenger_id));
        if(!empty($user_record)){
            $pre_point = $user_record->points;
            $new_points = $pre_point+$new_points;
            $this->AuthModel->updateRecord(array('id'=>$passenger_id),'users',array('points'=>$new_points));
        }
    }


    //For Referral bonus
    function checkReferralBonus($booking_id,$customer_id,$currency){
        $today = strtotime(date('d-m-Y'));        
        $bonus = $this->AuthModel->getSingleRecord('user_referral_bonus',array('user_id'=>$customer_id,'last_date_string>='=>$today,'status'=>0));
        //print_r($this->db->last_query());
        if(!empty($bonus)){
            $bonus_id = $bonus->referral_bonus_id;
            $min_ride = $bonus->min_ride;
            $countBooking = $this->AuthModel->checkRows('user_referral_history',array('user_referral_bonus_id'=>$bonus_id));
            if($countBooking<$min_ride){
                $data = array(
                    'user_referral_bonus_id'=>$bonus_id,
                    'booking_id'=>$booking_id,
                    'user_id'=>$customer_id
                    );
                if($this->AuthModel->singleInsert('user_referral_history',$data))
                {
                    $newCount = $countBooking+1;
                    if($newCount==$min_ride){
                        $user_bonus = $bonus->user_bonus;
                        $referral_id = $bonus->referral_user_id;
                        $referral_bonus = $bonus->referral_bonus;
                        $userWallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$customer_id));
                        $referralWallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$referral_id));
                        if(!empty($userWallet)){
                            $preBalance = $userWallet->balance;
                            $newBalance = $preBalance+$user_bonus;
                            $this->AuthModel->updateRecord(array('user_id'=>$customer_id),'wallet_balance',array('balance'=>$newBalance));
                            //send notification to customer get bonus in wallet
                        }
                        if(!empty($referralWallet)){
                            $preBalance = $referralWallet->balance;
                            $newBalance = $preBalance+$referral_bonus;
                            $this->AuthModel->updateRecord(array('user_id'=>$referral_id),'wallet_balance',array('balance'=>$newBalance));
                            //send notification to referral get bonus in wallet
                        }
                        $this->AuthModel->updateRecord(array('referral_bonus_id'=>$bonus_id),'user_referral_bonus',array('status'=>1));
                    }
                }
            }
        }        
    }


    //update ride promo history after complete booking
    public function updatePromoHistory($booking_id,$customer_id,$total_fare){ 
        $promo = $this->AuthModel->getSingleRecord('promocode_history',array('booking_id'=>$booking_id,'user_id'=>$customer_id));
        if(!empty($promo)){            
            $promoRec = $this->AuthModel->getSingleRecord('promocode',array('promo_id'=>$promo->promo_id));
            if(!empty($promoRec)){
                $min_trip_amount = $promoRec->min_trip_amount;                 
                //Check if trip fare greater then set promotional minimum fare then promotion bouns applied
                if($total_fare>$min_trip_amount)
                {
                    $history_id = $promo->history_id;
                    $rate_type  = $promo->rate_type;
                    $rate       = $promo->rate;
                    $promo_type = $promo->promo_type;
                    if($rate_type=='Percentage'){
                        $user_earn = ($total_fare*$rate)/100;
                        if($user_earn>$promoRec->max_amount){  //if promotion earning grater then max bonus amount
                            $user_earn=$promoRec->max_amount;
                        }
                    }else{
                        $user_earn = $rate;
                    }
                    if($this->AuthModel->updateRecord(array('history_id'=>$history_id),'promocode_history',array('promo_earn'=>$user_earn,'status'=>1))){
                        $this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('promo_earn'=>$user_earn));
                        //**************** Update promotion used ********************************//
                        $pre_used = $promoRec->user_used;
                        $new_used = $pre_used+1;
                        if($new_used==$promoRec->user_used){                            
                            $this->AuthModel->updateRecord(array('promo_id'=>$promo->promo_id),'promocode',array('user_used'=>$new_used,'status'=>'Deactive'));
                        }
                        else{
                            $this->AuthModel->updateRecord(array('promo_id'=>$promo->promo_id),'promocode',array('user_used'=>$new_used));
                        }
                        //**************** If promotion type is 1(after complete type) ********************************//
                        if($promo_type==1){  
                            //Update promo bonus amount in CitiPay wallet
                            $receiver_wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$customer_id));
                            if(!empty($receiver_wallet)){
                                $receiver_prebalance = $receiver_wallet->balance;
                                $receiver_newBalance = $receiver_prebalance+$user_earn;                    

                                if($transaction_id = $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$customer_id,'sender_id'=>'','type'=>'cr','amount'=>$user_earn,'description'=>'Promo Code Bonus of Trip id '.$booking_id,'transaction_status'=>'Success','reciver_balance'=>$receiver_newBalance,'sender_balance'=>'','transaction_at'=>date('Y-m-d H:i:s'))))              //store transaction record
                                {
                                    $this->AuthModel->updateRecord(array('user_id'=>$customer_id),'wallet_balance',array('balance'=>$receiver_newBalance,'update_at'=>date('Y-m-d H:i:s')));//update receiver balance 
                                    $message = "Promotional bonus ".$user_earn.' '.$promoRec->currency." of booking_id ".$booking_id." has been credited to your CitiPay Wallet"; //for customer notification.
                                    $this->AuthModel->singleInsert('notifications',array('user_id'=>$customer_id,'subject'=>"Promotional Bonus","message"=>$message,'notification_at'=>date('d-m-Y h:i A')));
                                }  
                                else{
                                    $transaction_id = $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$customer_id,'amount'=>$user_earn,'description'=>'Promo Code Bonus for Trip id '.$booking_id,'transaction_status'=>'Failure','reciver_balance'=>$receiver_prebalance,'transaction_at'=>date('Y-m-d H:i:s')));
                                    $message = "Sorry! Promotional bonus ".$user_earn.' '.$promoRec->currency." of booking_id ".$booking_id." has been failed. Please contact with support";                        
                                }
                            }
                        }
                        else{
                            $message = "Hey! You got ".$user_earn.' '.$promoRec->currency." promotion bonus of booking_id ".$booking_id;
                            //echo $message;
                             //for customer notification.
                        }                        
                    }
                }
                else{
                    $message = "Sorry Promotion is not applied! Your trip fare is less then promotional minimum trip fare.";
                    //echo $message;
                }
            }   
        }
    }

    //Check driver weekly Minimum and Maximum rewards
    public function driver_weeklyReward($driver_id,$country,$city){
        extract($_POST);
        $driver_bonus =0;
        $start    = date('d-m-Y',strtotime('this week monday'));
        $end      = date('d-m-Y',strtotime('this week sunday'));
        //echo $end;
        //echo $start;die();
        $earningDatest    = strtotime($start.' 00:00');            
        $earningDatend    = strtotime($end.' 11:59 PM');
        $completeTrip  = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>4)); // this week total trip
        //print_r($this->db->last_query());
        //echo $completeTrip;die();
        if($completeTrip>0){
            $minReward = $this->AuthModel->getSingleRecord('driverweeklyreward',array('reward_type'=>'min','country'=>$country,'city'=>$city));
            $maxReward = $this->AuthModel->getSingleRecord('driverweeklyreward',array('reward_type'=>'max','country'=>$country,'city'=>$city));
            $driver_wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$driver_id));
            if(!empty($minReward)){                
                $checkPreMinReward = $this->AuthModel->checkRows('driverweeklyreward_history',array('start_string'=>strtotime($start),'end_string'=>strtotime($end),'driver_id'=>$driver_id,'reward_type'=>'min'));
                $checkPreMaxReward = $this->AuthModel->checkRows('driverweeklyreward_history',array('start_string'=>strtotime($start),'end_string'=>strtotime($end),'driver_id'=>$driver_id,'reward_type'=>'min'));
                $targetMinTrip = $minReward->weeklyTargetTrip;
                $targetMaxTrip = $maxReward->weeklyTargetTrip;   
                //if driver not rewarded before
                if(empty($checkPreMinReward)){
                    if($completeTrip>$targetMinTrip && $minReward->reward_status=='on'){
                    //echo 'yes';die();             
                        if($minReward->reward_unit=='Per'){
                            $total_commission = getSum('booking_earning','total_commission',array('driver_id'=>$driver_id));
                            $bonus = ($total_commission*$minReward->reward_rate)/100;
                            $driver_bonus = round($bonus,2);
                        }
                        else{
                            $driver_bonus = $minReward->reward_rate;
                        }
                        //echo $driver_bonus;die();
                        if($driver_bonus>0){
                            $preBalance = $driver_wallet->balance;
                            $newBalance = $preBalance+$driver_bonus;

                            if($this->AuthModel->singleInsert('driverweeklyreward_history',array('driver_id'=>$driver_id,'reward_type'=>'min','reward_unit'=>$minReward->reward_unit,'reward_rate'=>$minReward->reward_rate,'commission_return'=>$driver_bonus,'weekdate_start'=>$start,'start_string'=>strtotime($start),'weekdate_end'=>$end,'end_string'=>strtotime($end))))
                            {
                                if($this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$newBalance,'update_at'=>date('Y-m-d H:i:s'))))//update sender balance
                                {   
                                    //store transaction record
                                    if($this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$driver_id,'type'=>'cr','amount'=>$driver_bonus,'description'=>'Weekly trip complete reward','transaction_status'=>'Success','reciver_balance'=>$newBalance,'transaction_at'=>date('Y-m-d H:i:s')))) 
                                    {
                                        $message = 'Congratulation! You have win weekly mininum trip complete reward '.$driver_bonus.' '.$minReward->currency;                          
                                        $this->AuthModel->singleInsert('notifications', array('user_id'=>$driver_id,'subject'=>'Weekly minimum trip reward','message'=>$message,'type'=>0,'notification_at'=>date('Y-m-d H:i:s')));
                                        //$this->Communication_model->sendToDriver($driver_id,$message);                                    
                                    } 
                                    else{
                                        $this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$preBalance,'update_at'=>date('Y-m-d H:i:s')));
                                        $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$driver_id,'type'=>'','amount'=>$driver_bonus,'description'=>'Weekly trip complete reward fail','transaction_status'=>'Failure','reciver_balance'=>$preBalance,'transaction_at'=>date('Y-m-d H:i:s')));
                                        $message = 'Congratulation! You have win weekly mininum complete trip reward '.$driver_bonus.' '.$minReward->currency.' . Due to technical issue transaction has fail. Please contact with support.';
                                        //$this->Communication_model->sendToDriver($driver_id,$message);
                                    }
                                }
                                else{
                                    $message = 'Congratulation! You have win weekly mininum complete trip reward '.$driver_bonus.' '.$minReward->currency.'. Due to technical issue transaction has fail. Please contact with support.';
                                    //$this->Communication_model->sendToDriver($driver_id,$message);
                                }
                            }                            
                        }
                    }
                } 
                elseif(empty($checkPreMaxReward)){
                    if($completeTrip>$targetMaxTrip && $maxReward->reward_status=='on'){
                        if($maxReward->reward_unit=='Per'){
                            $total_commission = getSum('booking_earning','total_commission',array('driver_id'=>$driver_id));
                            $bonus = ($total_commission*$maxReward->reward_rate)/100;
                            $driver_bonus = round($bonus,2);
                        }
                        else{
                            $driver_bonus = $maxReward->reward_rate;
                        }
                        if($driver_bonus>0){
                            $preBalance = $driver_wallet->balance;
                            $newBalance = $preBalance+$driver_bonus;

                            if($this->AuthModel->singleInsert('driverweeklyreward_history',array('driver_id'=>$driver_id,'reward_type'=>'max','reward_unit'=>$maxReward->reward_unit,'reward_rate'=>$maxReward->reward_rate,'commission_return'=>$driver_bonus,'weekdate_start'=>$start,'start_string'=>strtotime($start),'weekdate_end'=>$end,'end_string'=>strtotime($end))))
                            {
                                if($this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$newBalance,'update_at'=>date('Y-m-d H:i:s'))))//update sender balance
                                {   
                                    //store transaction record
                                    if($this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$driver_id,'type'=>'cr','amount'=>$driver_bonus,'description'=>'Weekly trip complete reward','transaction_status'=>'Success','reciver_balance'=>$newBalance,'transaction_at'=>date('Y-m-d H:i:s')))) 
                                    {
                                        $message = 'Congratulation! You have win weekly maximum trip complete reward '.$driver_bonus.' '.$maxReward->currency;
                                        $this->AuthModel->singleInsert('notifications', array('user_id'=>$driver_id,'subject'=>'Weekly maximum trip reward','message'=>$message,'type'=>0,'notification_at'=>date('Y-m-d H:i:s')));
                                        //$this->Communication_model->sendToDriver($driver_id,$message);                                    
                                    } 
                                    else{
                                        $this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$preBalance,'update_at'=>date('Y-m-d H:i:s')));
                                        $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$driver_id,'type'=>'','amount'=>$driver_bonus,'description'=>'Weekly trip complete reward fail','transaction_status'=>'Failure','reciver_balance'=>$preBalance,'transaction_at'=>date('Y-m-d H:i:s')));
                                        $message = 'Congratulation! You have win weekly maximum complete trip reward '.$driver_bonus.' '.$maxReward->currency.'. Due to technical issue transaction has fail. Please contact with support.';
                                        //$this->Communication_model->sendToDriver($driver_id,$message);
                                    }
                                }
                                else{
                                    $message = 'Congratulation! You have win weekly maximum complete trip reward '.$driver_bonus.' '.$maxReward->currency.'. Due to technical issue transaction has fail. Please contact with support.';
                                    //$this->Communication_model->sendToDriver($driver_id,$message);
                                }
                            }
                        }
                    }
                }
                /*else{
                    echo 'insufficient trip';
                } */               
            }
            elseif(!empty($maxReward)){
                $checkPreMaxReward = $this->AuthModel->checkRows('driverweeklyreward_history',array('start_string'=>$earningDatest,'end_string'=>$earningDatend,'driver_id'=>$driver_id,'reward_type'=>'min'));
                if(empty($checkPreMaxReward)){
                    $targetMaxTrip = $maxReward->weeklyTargetTrip;
                    if($completeTrip>$targetMaxTrip && $maxReward->reward_status=='on'){
                        if($maxReward->reward_unit=='Per'){
                            $total_commission = getSum('booking_earning','total_commission',array('driver_id'=>$driver_id));
                            $bonus = ($total_commission*$maxReward->reward_rate)/100;
                            $driver_bonus = round($bonus,2);
                        }
                        else{
                            $driver_bonus = $maxReward->reward_rate;
                        }
                        if($driver_bonus>0){
                            $preBalance = $driver_wallet->balance;
                            $newBalance = $preBalance+$driver_bonus;
                            if($this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$newBalance,'update_at'=>date('Y-m-d H:i:s'))))//update sender balance
                            {   
                                //store transaction record
                                if($this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$driver_id,'type'=>'cr','amount'=>$driver_bonus,'description'=>'Weekly maximum trip complete reward','transaction_status'=>'Success','reciver_balance'=>$newBalance,'transaction_at'=>date('Y-m-d H:i:s')))) 
                                {
                                    $message = 'Congratulation! You have win weekly maximum trip complete reward '.$driver_bonus.' '.$maxReward->currency;
                                    $this->AuthModel->singleInsert('notifications', array('user_id'=>$driver_id,'subject'=>'Weekly maximum trip reward','message'=>$message,'type'=>0,'notification_at'=>date('Y-m-d H:i:s')));
                                    $this->Communication_model->sendToDriver($driver_id,$message);                                    
                                } 
                                else{
                                    $this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$preBalance,'update_at'=>date('Y-m-d H:i:s')));
                                    $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$driver_id,'type'=>'','amount'=>$driver_bonus,'description'=>'Weekly maximum trip complete reward fail','transaction_status'=>'Failure','reciver_balance'=>$preBalance,'transaction_at'=>date('Y-m-d H:i:s')));
                                    $message = 'Congratulation! You have win weekly maximum complete trip reward '.$driver_bonus.' '.$maxReward->currency'. Due to technical issue transaction has fail. Please contact with support.';
                                    $this->Communication_model->sendToDriver($driver_id,$message);
                                }
                            }
                            else{
                                $message = 'Congratulation! You have win weekly maximum complete trip reward '.$driver_bonus.' '.$maxReward->currency'. Due to technical issue transaction has fail. Please contact with support.';
                                $this->Communication_model->sendToDriver($driver_id,$message);
                            }
                        }
                    }                    
                } 
            }
        }
    }






    public function oldupdatePromoHistory($booking_id,$customer_id,$total_fare){ //update promo history after complete booking
        $promo = $this->AuthModel->getSingleRecord('promocode_history',array('booking_id'=>$booking_id,'user_id'=>$customer_id));
        if(!empty($promo)){
            $history_id = $promo->history_id;
            $rate_type = $promo->rate_type;
            $rate      = $promo->rate;
            if($rate_type=='Percentage'){
                $user_earn = ($total_fare*$rate)/100;
            }else{
                $user_earn = $rate;
            }
            if($this->AuthModel->updateRecord(array('history_id'=>$history_id),'promocode_history',array('promo_earn'=>$user_earn,'status'=>1))){
                $this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('promo_earn'=>$user_earn));
                //Update promo bonus amount in CitiPay wallet
                $receiver_wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$customer_id));
                if(!empty($receiver_wallet)){
                    $receiver_prebalance = $receiver_wallet->balance;
                    $receiver_newBalance = $receiver_prebalance+$user_earn;                    

                    if($transaction_id = $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$customer_id,'sender_id'=>'','type'=>'cr','amount'=>$user_earn,'description'=>'Promo Code Bonus of Trip id '.$booking_id,'transaction_status'=>'Success','reciver_balance'=>$receiver_newBalance,'sender_balance'=>'','transaction_at'=>date('Y-m-d H:i:s'))))              //store transaction record
                    {
                        $this->AuthModel->updateRecord(array('user_id'=>$customer_id),'wallet_balance',array('balance'=>$receiver_newBalance,'update_at'=>date('Y-m-d H:i:s')));//update receiver balance 
                        $message = "Promotional bonus ".$user_earn." MMK of booking_id ".$booking_id." has been credited to your CitiPay Wallet"; //for customer notification.
                        $this->AuthModel->singleInsert('notifications',array('user_id'=>$customer_id,'subject'=>"Promotional Bonus","message"=>$message,'notification_at'=>date('d-m-Y h:i A')));
                    }  
                    else{
                        $transaction_id = $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>$customer_id,'amount'=>$user_earn,'description'=>'Promo Code Bonus for Trip id '.$booking_id,'transaction_status'=>'Failure','reciver_balance'=>$receiver_prebalance,'transaction_at'=>date('Y-m-d H:i:s')));
                        $message = "Sorry! Promotional bonus ".$user_earn." MMK of booking_id ".$booking_id." has been failed. Please contact with support";                        
                    }
                }
            }
        }
    }

    public function calculateFair($booking_id,$total_distance,$total_rideMinute)
    {        
        $booking_detail = $this->AuthModel->getSingleRecord('booking',array("booking_id"=>$booking_id));
        if(!empty($booking_detail))
        {
            $fair_detail = $this->AuthModel->getSingleRecord('booking_fare',array('booking_id'=>$booking_id));
            //echo json_encode($fair_detail);
            $Extra_waitingMinute  = $booking_detail->waiting_time-$fair_detail->free_waiting_minute;  
            $total_waitingCharge  = 0;
            $total_perMinute_charge = 0;
            $total_regularCharge    = 0;
            $bonus =0;
            if($Extra_waitingMinute>0)
            {
                $rightExtraWiting = $this->BookingModel->rightMultiple($Extra_waitingMinute,$fair_detail->paid_every_waiting_minute);
                $total_waitingCharge = ($rightExtraWiting/$fair_detail->paid_every_waiting_minute)*$fair_detail->every_waiting_minute_charge;                
                // $total_waitingCharge = $Extra_waitingMinute*($fair_detail->every_waiting_minute_charge/$fair_detail->paid_every_waiting_minute);
            }   
            if($total_distance>$fair_detail->mini_distance)  //To calculate regularcharge, Total distance must be grater then minimum distance other wise total regular charge will equal to minimum base fare
            {
                $extra_distance = $total_distance-$fair_detail->mini_distance;
                $rightExtra = $this->BookingModel->rightMultiple($extra_distance,$fair_detail->regular_charge_distance);
                $total_regularCharge = ($rightExtra/$fair_detail->regular_charge_distance)*$fair_detail->regular_distance_charge;

                //$total_regularCharge = ($total_distance-$fair_detail->mini_distance/$fair_detail->regular_distance_charge)*$fair_detail->regular_charge_distance;    
                //regularcharge = (total_distance-mini_distance)*distance_charge/everydistanceforcharge;  
            }
            if($fair_detail->per_minute!=0 or $fair_detail->per_minute!=''){  
                $minuteForCharge = $this->BookingModel->rightMultiple($total_rideMinute,$fair_detail->per_minute);
                $total_perMinute_charge = ($minuteForCharge/$fair_detail->per_minute)*$fair_detail->per_minute_charge;

                // $total_perMinute_charge = ($total_rideMinute/$fair_detail->per_minute)*$fair_detail->per_minute_charge;    
            }
            $total_fair = $fair_detail->base_fair+$fair_detail->multi_address_charge+$fair_detail->mini_distance_fair+$total_waitingCharge+ $total_regularCharge+$total_perMinute_charge;
            $total_surcharge = 0;
            if($fair_detail->morning_surcharge_unit!=''){
                if($fair_detail->morning_surcharge_unit=='Per'){
                    $total_surcharge = ($total_fair*$fair_detail->morning_surcharge)/100;
                    $total_fair = $total_fair+$total_surcharge;
                }
                else{
                    $total_surcharge = $fair_detail->morning_surcharge;
                    $total_fair = $total_fair+$fair_detail->morning_surcharge;
                }
            }
            elseif($fair_detail->evening_surcharge_unit!=''){
                if($fair_detail->evening_surcharge_unit=='Per'){
                    $total_surcharge = ($total_fair*$fair_detail->evening_surcharge)/100;
                    $total_fair = $total_fair+$total_surcharge;
                }
                else{
                    $total_surcharge =$fair_detail->evening_surcharge;
                    $total_fair = $total_fair+$fair_detail->evening_surcharge;
                }
            }
            elseif($fair_detail->midnight_surcharge_unit!=''){
                if($fair_detail->midnight_surcharge_unit=='Per')
                {
                    $total_surcharge = ($total_fair*$fair_detail->midnight_surcharge)/100;
                    $total_fair = $total_fair+(($total_fair*$fair_detail->midnight_surcharge)/100);
                }
                else
                {
                    $total_surcharge = $fair_detail->midnight_surcharge;
                    $total_fair = $total_fair+$fair_detail->midnight_surcharge;
                }
            }

            if($booking_detail->promo_type=='point' && $booking_detail->promo_id!=0){  //point type promo calculation
                $promo = $this->AuthModel->getSingleRecord('redeem_history',array('redeem_post_id'=>$booking_detail->promo_id,'user_id'=>$booking_detail->customer_id,'status'=>'No'));
                if(!empty($promo)){
                    $rate_type  = $promo->rate_type;
                    $rate       = $promo->rate;
                    $max_amount = $promo->max_amount;
                    if($rate_type=='Percentage'){
                        $bonus = ($total_fair*$rate)*100;
                        if($bonus>$max_amount){
                            $bonus = $max_amount;                            
                            $total_fair = $total_fair-$max_amount;
                        }else{
                            $total_fair = $total_fair-$bonus;
                        }
                    }
                    elseif($rate_type=='Flat'){
                        $bonus = $rate;
                        $total_fair = $total_fair-$bonus;
                    }
                    $this->AuthModel->updateRecord(array('history_id'=>$promo->history_id),'redeem_history',array('booking_id'=>$booking_id,'bonus_amount'=>$bonus,'status'=>'Yes','redeem_at'=>date('d-m-Y h:i A')));
                    //$this->Communication_model->sendToPassenger($customer_id,$message);
                    //$this->AuthModel->singleInsert('notifications', array('user_id'=>$booking_detail->customer_id,'subject'=>'Exchange point redeem','message'=>$message,'type'=>0,'notification_at'=>date('Y-m-d H:i:s')));
                }
            }
            $data['total_regular_charge']=$total_regularCharge;
            $data['total_per_minute_charge']= $total_perMinute_charge;
            $data['total_waiting_charge']=$total_waitingCharge;
            $data['total_surcharge']= $total_surcharge;
            $data['total_fair'] = $total_fair;
            $data['bonus'] = $bonus;
            $data['currency']= $booking_detail->currency;
            $data['country']=  $booking_detail->country;
            $data['city']=  $booking_detail->city;
            $data['promo_status'] = $booking_detail->promo_status;
            $data['promo_type']   = $booking_detail->promo_type; //ride or point
            return $data;
        }
    }

    public function getDriverAdvanceDetail()
    {
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['device_type']) && $_POST['device_type']!=''){
            extract($_POST);
            $paramarray = array('driver_id','device_token');
            $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$_POST);
            if(isset($vResponse['status']) && $vResponse['status']==0)
            {
                $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                echo json_encode($response);die();
            }
            else
            {
                $this->AuthModel->checkActiveStatus('users',array('id'=>$driver_id));   //To check Driver Active Status
                $this->AuthModel->updateRecord(array('id'=>$driver_id),'users',array("device_token"=>$device_token,'device_type'=>$device_type));
                //===========================================================================================//
                $score = $this->AuthModel->getSingleRecord('users_score',array('user_id'=>$driver_id));
                $total_booking =  $this->AuthModel->checkRows('booking',array('driver_id'=>$driver_id)); //count total booking
                $wallet_balance = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$driver_id));
                if(!empty($wallet_balance)){$balance = $wallet_balance->balance;}else{$balance=0;}  //get wallet balance
                if(!empty($score))
                {
                    $userscore    = $score->total_score;  //total cancellation score
                    $total_cancel = $score->total_cancel_before_accept+$score->total_cancel_after_accept;  //total cancel
                    $where = '(driver_id='.$driver_id.' and (booking_status=1 or booking_status=4))';
                    $acceptance = $this->AuthModel->checkRows('booking',$where);
                    $acceptancePercentage = ($total_booking*$acceptance)/100; //total acceptance
                    $cancellationPercentage = ($total_booking*$total_cancel)/100;
                    $rating = $this->AuthModel->get_rating($driver_id);
                    $response = array("success"=>1,'error'=>0,"userscore"=>$userscore,'acceptance'=>$acceptancePercentage,'cancellation'=>$cancellationPercentage,'total_cancelled'=>$total_cancel,'rating'=>$rating,'bonus'=>0,'citiPay_balance'=>$balance);
                    echo json_encode($response); 
                }
                else
                {
                    $userscore    = 0;
                    $total_cancel = 0;  //total cancel
                    $where = '(driver_id='.$driver_id.' and (booking_status=1 or booking_status=4))';
                    $acceptance = $this->AuthModel->checkRows('booking',$where);
                    $acceptancePercentage = ($total_booking*$acceptance)/100; //total acceptance
                    $cancellationPercentage = ($total_booking*$total_cancel)/100;
                    $rating = $this->AuthModel->get_rating($driver_id);
                    $response = array("success"=>1,'error'=>0,"userscore"=>$userscore,'acceptance'=>$acceptancePercentage,'cancellation'=>$cancellationPercentage,'total_cancelled'=>$total_cancel,'rating'=>$rating,'bonus'=>0,'citiPay_balance'=>$balance);
                    echo json_encode($response);  
                }
            }            
        }
        else{
            $this->index();
        }
    }
    

    public function logout()
    {
        if(isset($_POST['user_id']))
        {
            extract($_POST);
            if($this->AuthModel->updateRecord(array("id"=>$user_id,"user_type"=>1),'users',array('online_status'=>'offline','device_token'=>'')))
            {
                $response = array("success"=>1,"error"=>0,"message"=>"Log out successfull");
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Oops! something went wrong,Please try again");
                echo json_encode($response);
            }
        }
        else
        {
            $this->index();
        }
    }


    public function set_DriverDestination()
    {
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='')
        {
            extract($_POST);
            $paramarray = array('driver_id','destination_address','destination_lat','destination_lng','set_at');
            $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$_POST);
            if(isset($vResponse['status']) && $vResponse['status']==0)
            {
                $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                echo json_encode($response);die();
            }
            else
            {
                $data = array(
                    "driver_id"=>$driver_id,
                    "destination_address"=>$destination_address,
                    "destination_lat"=>$destination_lat,
                    "destination_lng"=>$destination_lng,
                    "update_at"=>$set_at,
                    );
                if($this->AuthModel->checkThenInsertorUpdate('driver_destination',$data,array('driver_id'=>$driver_id)))
                {
                    $destination = $this->AuthModel->getSingleRecord('driver_destination',array('driver_id'=>$driver_id));
                    $response = array("success"=>1,"error"=>0,"message"=>"Destination has been successfully saved","data"=>$destination);
                    echo json_encode($response);
                }
                else
                {
                    $destination = $this->AuthModel->getSingleRecord('driver_destination',array('driver_id'=>$driver_id));
                    $response = array("success"=>0,"error"=>1,"message"=>"Something went wrong. Please try again","data"=>$destination);
                    echo json_encode($response);
                }
            }
        }
        else
        {
            $this->index();
        }
    }

    public function get_myDestination()
    {
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='')
        {
            extract($_POST);
            $destination = $this->AuthModel->getSingleRecord('driver_destination',array('driver_id'=>$driver_id));
            if(!empty($destination))
            {
                $response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$destination); 
                echo json_encode($response);               
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"You have not set his destination yet.","data"=>""); 
                echo json_encode($response); 
            }
        }
        else
        {
            $this->index();
        }
    }

    public function getAssignedJob()
    {      
        $response=array();
        $responsedata=array();                        
        $data_val = array('driver_id','status');
        $validation = $this->AbhiModel->param_validation($data_val,$_POST);
        if(isset($validation['status']) && $validation['status']=='0')
        {
            $response = array('success'=>0,'error'=>1,'message'=>$validation['message']);
            echo json_encode($response);die();            
        }
        else
        {
            extract($_POST);
            $driver_id   = $_POST['driver_id'];             
            //echo strtotime($todayst);
            $todaynd     = strtotime(date('d-m-Y').' 11:59 PM');
            if($status==1){         //1= All data 
                $where=array('driver_id'=>$driver_id,'booking_status!='=>'4','booking_type'=>'later');
            }
            elseif($status==2){      //2=last 30 days 
                $last30 = strtotime(date('d-m-Y',strtotime("-30 days")).' 00:00');                 
                $where = array('booking_at_string>='=>$last30,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status!='=>'4','booking_type'=>'later');
            }
            elseif($status==3){      //3=last 60 days  
                $last60 = strtotime(date('d-m-Y',strtotime("-60 days")).' 00:00'); 
                $where = array('booking_at_string>='=>$last60,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status!='=>'4','booking_type'=>'later');
            }
            elseif($status==4){      //4=last 90 days  
                $last90 = strtotime(date('d-m-Y',strtotime("-90 days")).' 00:00'); 
                $where = array('booking_at_string>='=>$last90,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status!='=>'4','booking_type'=>'later');
            }
            elseif($status==5){      //5=custom  
                $data_val = array('driver_id','status','from_date','to_date');
                $validation = $this->AbhiModel->param_validation($data_val,$_POST);
                if(isset($validation['status']) && $validation['status']=='0'){
                    $response = array('success'=>0,'error'=>1,'message'=>$validation['message']);
                    echo json_encode($response);die();                    
                }
                else{
                    $from =  strtotime($from_date.' 00:00');
                    $to   =  strtotime($to_date.' 11:59 PM');                                       
                    $where = array('booking_at_string>='=>$from,'booking_at_string<='=>$to,'driver_id'=>$driver_id,'booking_status!='=>'4','booking_type'=>'later');    
                }                
            }
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'invalid request');
                echo json_encode($response);die();
            } 

            //$where = '(customer_id='.$customer_id.' AND (booking_status!=2 or booking_status!=3))';            
            $response1=$this->AuthModel->getMultipleRecord('booking',$where,'booking_id DESC');
            //print_r($response1);die();
            if(!empty($response1)){
             
                foreach($response1 as $deliver)
                {
                    $customer_detail = '';   
                    $response ='';
                    
                    $customer = $this->AuthModel->getSingleRecord('users',array('id'=>$deliver->customer_id));
                    if(!empty($customer))
                    {                                            
                        $customer_detail['customer_id'] = $customer->id;
                        $customer_detail['customer_name'] = $customer->name;
                        $customer_detail['customer_mobile'] = $customer->mobile;
                        $customer_detail['customer_rating'] = get_rating($deliver->customer_id);
                        if($customer->image_type==1){
                            $customer_detail['image'] = $customer->image;
                        }else{                            
                            $customer_detail['image'] = base_url().'userimage/'.$customer->image;                            
                        }                                              
                    }                    
                    
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

                    $response['booking_id']=$deliver->booking_id;                
                    $response['booking_id_show']=$deliver->booking_id_show;                
                    $response['favourite_status']= $deliver->favourite_status;
                    $response['booking_status']= $deliver->booking_status;
                    $response['booking_type']= $deliver->booking_type;
                    $response['booking_at']=$deliver->booking_at;                    
                    $response['later_pickup_at'] = $deliver->later_pickup_at;
                    $response['pickup']=$deliver->pickup;
                    $response['booking_note']=$deliver->booking_note; 
                    $response['total_fare']=$deliver->total_fare;     
                    $response['currency']=$deliver->currency;      
                    $response['payment_type']=$deliver->payment_type;     
                    $response['cancel_reason']=$deliver->cancel_reason;            
                    $booking_dropoffs=booking_dropoffs($deliver->booking_id);
                    //$response['dropoff']=$booking_dropoffs[0]->dropoff;
                    $response['dropoff']=$booking_dropoffs;
                    $response['customer_detail'] =$customer_detail; 
                    $booking_status = $deliver->booking_status;
                    //echo $booking_status;
                    if($booking_status=='0' or $booking_status=='1' or $booking_status=='5' or $booking_status=='6' or $booking_status=='8' or $booking_status=='9')
                    {
                        $responsedata['pending'][]=$response;                         
                    }
                    else
                    {
                        $responsedata['cancel'][]=$response;
                    }
                    if(empty($responsedata['pending'])){$responsedata['pending']=array();}
                    if(empty($responsedata['cancel'])){$responsedata['cancel']=array();}                    
                                   
                }
                $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$responsedata);
                echo json_encode($response);                
            }
            else
            {
                $response = array('success'=>0,'error'=>1,'message'=>'No Advance booking found','data'=>$response1);
                echo json_encode($response);
            }     
        }            
    }



    //================================================Develope by Abhisek======================================//

    public function BookingRejectByDriver()  //notification done
    {
        //param => booking_id,driver_id,customer_id,cancel_reason
        $response=array();
        $responsedata=array();
        $driver_cancelCharge=0;       
        $current_time=date('Y-m-d H:i:s',time());
        $data_val = array('booking_id','driver_id','customer_id','cancel_reason');
        $validation = $this->AbhiModel->param_validation($data_val,$_REQUEST);
        if(isset($validation['status']) && $validation['status']=='0')
        {
            echo json_encode(array('response'=>'false','message'=>$validation['message']));die;
        }
        else
        {
            $booking_id= $_REQUEST['booking_id'];
            $driver_id= $_REQUEST['driver_id'];
            $customer_id= $_REQUEST['customer_id'];
            $cancel_reason= $_REQUEST['cancel_reason'];
            $date=strtotime(date("Y-m-d h:i:s"));
        
            $where=array('booking_id'=>$booking_id,'driver_id'=>$driver_id);
            $response1=$this->AbhiModel->select_query('booking',$where);                
            if(!empty($response1))
            {      
                //get fare details to get cancel chrage
                $typeid = $response1[0]->service_typeid;  $country = $response1[0]->country;
                $city   = $response1[0]->city;  
                $limit  = $this->AuthModel->getSingleRecord('fare',array('serviceType_id'=>$typeid,'country'=>$country,'city'=>$city)); 
                //============================================                
                $booking_status=$response1[0]->booking_status;
                //echo $booking_status;die();
                $where=array('user_id'=>$driver_id);
                $response11=$this->AbhiModel->select_query('users_score',$where);
                $trip_score_check=$response11[0]->total_score;
                $banned_count=$response11[0]->banned_count;
                $trip_score=$trip_score_check-0.1;

                if ($booking_status=='0') //cancel before accept
                {
                    $total_cancel_before_accept=$response11[0]->total_cancel_before_accept;
                    $total_cancel_before=$total_cancel_before_accept+1;
                    $update_score=array('total_cancel_before_accept'=>$total_cancel_before);          
                    $wheree=array('user_id'=> $driver_id);
                    $this->AbhiModel->update_query('users_score',$update_score,$wheree);
                }
                else{ //cancel after accept
                    $total_cancel_after_accept=$response11[0]->total_cancel_after_accept;
                    $total_cancel_accept=$total_cancel_after_accept+1;
                    $update_score=array('total_score'=>$trip_score,'total_cancel_after_accept'=>$total_cancel_accept);          
                    $wheree=array('user_id'=> $driver_id);
                    $this->AbhiModel->update_query('users_score',$update_score,$wheree);

                     //Fine cancellation charge to customer if cancel after accept booking                    
                    $driver_cancelUnit      =  $limit->cancelChargeUnitDriver;
                    $driver_cancelCharge    =  $limit->stndCancelChargeDriver;
                    if($driver_cancelUnit=='Per'){
                        $driver_cancelCharge = ($response1[0]->total_fare*$driver_cancelCharge)/100;
                    }                                            
                }
                //echo $driver_cancelCharge;die();
                 //============================Update in Booking table===================================//

                $cancellbooking=array('booking_status'=>'2','cancel_reason'=>$cancel_reason,'cancel_charge'=>$driver_cancelCharge,'cancel_at'=>date('d-m-Y h:i A'));
                $wh=array('booking_id'=>$booking_id,'driver_id'=>$driver_id);

                $bookingCanelled=$this->AbhiModel->update_query('booking',$cancellbooking,$wh);
                if($bookingCanelled)
                {
                    //Cancellation charge record store and deduct from wallet balance
                    if($driver_cancelCharge>0){
                        $wallet = $this->AuthModel->getSingleRecord('wallet_balance',array('user_id'=>$driver_id));
                        if(!empty($wallet)){       
                            $preBalance = $wallet->balance;
                            $newBalance = $preBalance-$driver_cancelCharge; 
                            $currency   = $limit->currency; 
                            $this->AuthModel->updateRecord(array('user_id'=>$driver_id),'wallet_balance',array('balance'=>$newBalance,'update_at'=>date('Y-m-d H:i:s')));//update customer balance                   
                            $dcg= $this->AuthModel->singleInsert('wallet_transaction',array('receiver_id'=>0,'sender_id'=>$driver_id,'type'=>'dr','amount'=>$driver_cancelCharge,'description'=>'Cancel charge of booking id '.$booking_id,'transaction_status'=>'Success','reciver_balance'=>0,'sender_balance'=>$newBalance,'transaction_at'=>date('Y-m-d H:i:s')));   //store transaction record                            
                            $message = $driver_cancelCharge.' '.$currency.' has been Debited from your CitiPay wallet due to cancel booking';
                            //$this->Communication_model->sendToDriver($driver_id,$message);
                        }
                    }
                    /*=========================================================*/
                   
                    if($trip_score == '8.9')
                    {
                        $updatestatus=array('activeStatus'=>'Suspend','suspend_type'=>'7 Day');          
                        $wherestatus=array('id'=> $driver_id);
                        $status=$this->AbhiModel->update_query('users',$updatestatus,$wherestatus);
                        //-----------------useraction------------------------

                        $this->AuthModel->Suspend(7,$driver_id);
                        //=================Notification======================//
                        $drivermessage = "Booking has been cancelled. Your account is suspend for 7 days due to exceeded weekly cancellation limit";                                               
                        //$this->Communication_model->sendToDriver($driver_id,$drivermessage);

                        echo json_encode(array('response'=>'true','Data'=>'Booking has been cancelled. Your account is suspend for 7 days due to exceeded weekly cancellation limit'));                        
                      //---------------------------------------------------                        
                    }
                    elseif($trip_score == '8.5')
                    {            
                        $updatestatus=array('activeStatus'=>'Suspend','suspend_type'=>'14 Day');          
                        $wherestatus=array('id'=> $driver_id);
                        $status=$this->AbhiModel->update_query('users',$updatestatus,$wherestatus);
                        //-------------------
                        $this->AuthModel->Suspend(7,$driver_id);
                        //=================Notification======================//
                        $drivermessage = "Booking has been cancelled. Your account is suspend for 14 days due to exceeded 2nd time weekly cancellation limit";                                              
                        //$this->Communication_model->sendToDriver($driver_id,$drivermessage);

                         echo json_encode(array('response'=>'true','Data'=>'Booking has been cancelled. Your account is suspend for 14 days due to exceeded 2nd time weekly cancellation limit'));die;                        
                    }
                    elseif($trip_score =='8.0'){
                        $updatestatus=array('activeStatus'=>'Banned','suspend_type'=>'','blackList_status'=>'yes');
                        $wherestatus=array('id'=> $driver_id);
                        $status=$this->AbhiModel->update_query('users',$updatestatus,$wherestatus);                    
                        $banned =$banned_count+1;
                        $updatestatus=array('banned_count'=>$banned);  
                        $whereid=array('user_id'=> $driver_id);        
                        $status=$this->AbhiModel->update_query('users_score',$updatestatus,$whereid);
                        //=================Notification======================//
                        $drivermessage = "Booking has been cancelled. Your account is black listed due to exceeded weekly cancellation limit many times";   
                        //$this->Communication_model->sendToDriver($driver_id,$drivermessage);
                        echo json_encode(array('response'=>'true','Data'=>'Booking has been cancelled. Your account is black listed due to exceeded weekly cancellation limit many times'));die;                
                    }
                    else{
                        if ($booking_status=='0') //booking neglect
                        {
                            //$customer_msg = "Sorry! Driver is not responding. Please try again.";
                            $this->Communication_model->sendToPassenger($customer_id,$customer_msg);                            
                            echo json_encode(array('response'=>'true','Data'=>'Your booking has been neglected'));
                            die();
                        }
                        else{
                            //=================Notification======================//
                            $customer_msg = "Sorry! Your Booking has been cancelled by driver";
                            //$this->Communication_model->sendToPassenger($customer_id,$customer_msg);
                            $drivermessage = "Your booking has been successfully cancelled";   
                            //$this->Communication_model->sendToDriver($driver_id,$drivermessage);
                            echo json_encode(array('response'=>'true','Data'=>'Your booking has been successfully cancelled'));
                        }
                    }
                }
                else{
                    $respose = array("response"=>'false',"message"=>"Oops! Something went wrong, Please try again");
                    echo json_encode($respose);
                }
            }
            else{
            echo json_encode(array('response'=>'false','message'=>'Please enter valid booking id!'));die;
            }     
        }
    }    

   public function getDailyEarning(){  //for daily earning and month tab click

        if(isset($_POST['driver_id']) && $_POST['driver_id']!=''){
            $data_val = array('driver_id','earningDate_start','earningDate_end');            
            $validation = $this->AbhiModel->param_validation($data_val,$_POST);
            if(isset($validation['status']) && $validation['status']=='0'){
                $response = array("success"=>0,"error"=>1,"message"=>$validation['message']); 
                echo json_encode($response);die();                 
            }
            else{
                extract($_POST);                
                $earningDatest = strtotime($earningDate_start.' 00:00');            
                $earningDatend = strtotime($earningDate_end.' 11:59 PM');
                $completeTrip = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>4));
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
                            $companyCommssion             = $this->AuthModel->getSingleRecord('booking_earning',array('booking_id'=>$booking_id));

                            $trip_fare = $tripEarning->base_fair+$tripEarning->mini_distance_fair+$tripEarning->total_regular_charge+$tripEarning->total_per_minute_charge;

                            //echo $total_fair;die();
                            //$trip_fare = $tripEarning->base_fair+$tripEarning->total_regular_charge+$tripEarning->total_per_minute_charge;
                            $promotion                    = 0;
                            $res['booking_id']            = $booking_id;
                            $res['booking_id_show']       = $value->booking_id_show;
                            $res['booking_at']            = $tripEarning->booking_at;  
                            $res['booking_status']        = $tripEarning->booking_status;  
                            $res['customer_id']           = $tripEarning->customer_id;
                            $res['driver_id']             = $tripEarning->driver_id;                                   
                            $res['service_id']            = $tripEarning->service_typeid;
                            $res['service_name']          = $tripEarning->servicename;
                            $res['service_image']         = base_url().'/serviceimage/'.$tripEarning->selected_image;
                            $res['db_total_fair']         = $tripEarning->total_fare;
                            $res['currency']              = $tripEarning->currency; 
                            $res['cancel_at']             = $tripEarning->cancel_at;

                            $res['passenger']['payment_type']          = $tripEarning->payment_type;
                            $res['passenger']['transaction_id']        = $tripEarning->transaction_id;
                            $res['passenger']['trip_fare']             = $trip_fare;
                            $res['passenger']['multi_address_charge']  = $tripEarning->multi_address_charge;
                            $res['passenger']['total_surcharge']       = $tripEarning->total_surcharge;
                            $res['passenger']['total_waiting_charge']  = $tripEarning->total_waiting_charge;
                            $res['passenger']['promotion']             = $promotion;
                            $res['passenger']['currency']              = $tripEarning->currency;
                            $passengerTotal               = ($trip_fare+$tripEarning->total_surcharge+$tripEarning->total_waiting_charge+$tripEarning->multi_address_charge)-$promotion;
                            $res['passenger']['total_fare']   = $passengerTotal;
                            $res['tripEarning']['payment_type']  = $tripEarning->payment_type;
                            $res['tripEarning']['earning']       = $passengerTotal;
                            $res['tripEarning']['promotion']     = $promotion;
                            $res['tripEarning']['total_earning'] = $passengerTotal+$promotion;
                            $res['tripEarning']['currency']      = $tripEarning->currency;                            
                            $res['tripEarning']['cancel_charge']      = $tripEarning->cancel_charge;
                            $res['tripEarning']['company_commission']    = '';
                            if(!empty($companyCommssion)) {
                                $res['tripEarning']['company_commission'] = $companyCommssion->total_commission;
                            }                           
                            
                            $earningdata[] = $res;                            
                        }                
                    }
                    // =================================== Get Total Earning ====================================
                    $totalearning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'status'=>1,'commission_at_string>='=>$earningDatest,'commission_at_string<='=>$earningDatend));
                    $totalcancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_status'=>2,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,));
                    $grand_earning = $totalearning-$totalcancelCharge;

                    // =================================== ***************** ====================================
                    $response = array('success'=>1,'error'=>0,'message'=>'Success','total_earning'=>$grand_earning,'bookingCount'=>$bookingCount,'data'=>$earningdata);
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


    public function getweeklyEarning(){    //for weekly earning 
        if(isset($_POST['driver_id']) && $_POST['driver_id']!=''){          
            extract($_POST);
            $today      = date('d-m-Y',strtotime('+1 days'));
            $lastday    = date('d-m-Y',strtotime('-6 days'));
            $earningDatest    = strtotime($lastday.' 00:00');            
            $earningDatend    = strtotime($today.' 11:59 PM');

            $completeTrip  = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>4));
            $passengerWhere = '(booking_at_string>='.$earningDatest.' and booking_at_string<='.$earningDatend.' and driver_id='.$driver_id.' and (booking_status=3 or booking_status=7))'; 
            $passengerCancel = $this->AuthModel->checkRows('booking',$passengerWhere); 
            $driverCancel = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>2));
            $data['bookingCount'] = array("completedTrip"=>$completeTrip,"passengerCancel"=>$passengerCancel,'driverCancel'=>$driverCancel);

                    /*=============================Graph Data Start=========================================*/
            $begin      = new DateTime($lastday);                
            $end        = new DateTime($today);                
            $daterange  = new DatePeriod($begin, new DateInterval('P1D'), $end);
            $graph=array();
            foreach($daterange as $date)
            {
                $st  =  $date->format("d-m-Y")." 00:00";
                //echo $st;
                $end =  $date->format("d-m-Y")." 11:59 PM";
                //echo $end;
                $res['date'] = $date->format("d-m l");
                $total_earning = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'commission_at_string>='=>strtotime($st),'commission_at_string<='=>strtotime($end),'status'=>1));
                $total_CancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_at_string>='=>strtotime($st),'booking_at_string<='=>strtotime($end),'booking_status'=>2));
                $res['earning'] =$total_earning-$total_CancelCharge;
                $graph[]=$res;
                $res='';                   
            }
            $data['graph_Data']=$graph;

                    /*=============================Graph Data End=========================================*/
                    /*=============================Weeklys Data Start=========================================*/
            $thisWeekMonday   = date('d-m-Y',strtotime("previous monday")).' 00:00';
            //echo $thisWeekMonday;die();
            $thisWeekSunday   = date('d-m-Y',strtotime("next sunday")).' 11:59 PM';
            $lastWeekMonday   = date('d-m-Y',strtotime('last monday -7 days')).' 00:00';            
            $lastWeeksunday   = date('d-m-Y',strtotime("last monday -1 days")).' 11:59 PM';
            $last2WeekMonday  = date('d-m-Y',strtotime('last monday -14 days')).' 00:00';            
            $last2Weeksunday  = date('d-m-Y',strtotime("last monday -8 days")).' 11:59 PM';

            // ================================================================================================
            $data['current']['currentweekTrips']  = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>strtotime($thisWeekMonday),'booking_at_string<='=>strtotime($thisWeekSunday),'driver_id'=>$driver_id));
            $data['current']['currentweekstart'] = $thisWeekMonday;
            $data['current']['currentweekend'] = $thisWeekSunday;
            
            $thisweekEarning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'commission_at_string>='=>strtotime($thisWeekMonday),'commission_at_string<='=>strtotime($thisWeekSunday),'status'=>1));
            //print_r($this->db->last_query());die();
            $thisweekCancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_at_string>='=>strtotime($thisWeekMonday),'booking_at_string<='=>strtotime($thisWeekSunday),'booking_status'=>2));
            $data['current']['total_currentweekearning'] = $thisweekEarning-$thisweekCancelCharge;                
             // ================================================================================================

            $data['lastweek']['lastweekTrips'] = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>strtotime($lastWeekMonday),'booking_at_string<='=>strtotime($lastWeeksunday),'driver_id'=>$driver_id));
            $data['lastweek']['lastweekstart'] = $lastWeekMonday;
            $data['lastweek']['lastweekend']   = $lastWeeksunday;
             $lastweekEarning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'commission_at_string>='=>strtotime($lastWeekMonday),'commission_at_string<='=>strtotime($lastWeeksunday),'status'=>1));
            $lastweekCancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_at_string>='=>strtotime($lastWeekMonday),'booking_at_string<='=>strtotime($lastWeeksunday),'booking_status'=>2));
            //print_r($this->db->last_query());die();
            $data['lastweek']['total_lastweekEarning'] = $lastweekEarning-$lastweekCancelCharge;

            // ================================================================================================
            $data['2weekago']['2weekagoTrips'] = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>strtotime($last2WeekMonday),'booking_at_string<='=>strtotime($last2Weeksunday),'driver_id'=>$driver_id));
            $data['2weekago']['2weekagostart'] = $last2WeekMonday;
            $data['2weekago']['2weekagoend'] = $last2Weeksunday;
            $week2AgoEarning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'commission_at_string>='=>strtotime($last2WeekMonday),'commission_at_string<='=>strtotime($last2Weeksunday),'status'=>1));
            $week2AgoCancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_at_string>='=>strtotime($last2WeekMonday),'booking_at_string<='=>strtotime($last2Weeksunday),'booking_status'=>2));
            $data['2weekago']['total_2weekAgoEarning'] = $week2AgoEarning-$week2AgoCancelCharge;                
            //======================================================================================================//
            $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$data);
            echo json_encode($response);
        }
       
        else{
            $this->index();
        }
    }

    public function getWeeklyEarningList(){  //for week earning tab click
        if(isset($_POST['driver_id']) && $_POST['driver_id']!=''){
            $data_val = array('driver_id','earningDate_start','earningDate_end');            
            $validation = $this->AbhiModel->param_validation($data_val,$_POST);
            if(isset($validation['status']) && $validation['status']=='0'){
                $response = array("success"=>0,"error"=>1,"message"=>$validation['message']); 
                echo json_encode($response);die();                 
            }
            else{
                $booking_dates=[];
                extract($_POST);                              
                $earningDatest = strtotime($earningDate_start.' 00:00');            
                $earningDatend = strtotime($earningDate_end.' 11:59 PM');
                $completeTrip = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>4));
                $passengerWhere = '(booking_at_string>='.$earningDatest.' and booking_at_string<='.$earningDatend.' and driver_id='.$driver_id.' and (booking_status=3 or booking_status=7))'; 
                $passengerCancel = $this->AuthModel->checkRows('booking',$passengerWhere); 
                $driverCancel = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id,'booking_status'=>2));
                $bookingCount = array("completedTrip"=>$completeTrip,"passengerCancel"=>$passengerCancel,'driverCancel'=>$driverCancel);
                    //======================================================================================================//
                $where = '(booking_at_string>='.$earningDatest.' and booking_at_string<='.$earningDatend.' and driver_id='.$driver_id.' and (booking_status=2 or booking_status=3 or booking_status=4 or booking_status=7))';                 
                $bookings = $this->AuthModel->getMultipleRecord('booking',$where,'booking_id DESC');

                $earningdata = array(); $dateArray=[];
                if(!empty($bookings)){                    
                    foreach ($bookings as $key => $value){
                        $booking_id = $value->booking_id;    
                        $tripEarning = $this->BookingModel->getEarningInvoice($booking_id);
                       //echo json_encode($tripEarning);die();
                        if(!empty($tripEarning)){
                            $companyCommssion             = $this->AuthModel->getSingleRecord('booking_earning',array('booking_id'=>$booking_id));

                            $trip_fare = $tripEarning->base_fair+$tripEarning->mini_distance_fair+$tripEarning->total_regular_charge+$tripEarning->total_per_minute_charge;

                            //echo $total_fair;die();
                            //$trip_fare = $tripEarning->base_fair+$tripEarning->total_regular_charge+$tripEarning->total_per_minute_charge;
                            $booking_date = date('d-M-Y',strtotime($tripEarning->booking_at));
                            if(!in_array($booking_date, $dateArray)){
                                $dateArray[]=$booking_date;
                            }
                            $promotion                    = 0;
                            $res['booking_id']            = $booking_id;
                            $res['booking_id_show']       = $value->booking_id_show;
                            $res['booking_at']            = $tripEarning->booking_at;  
                            $res['booking_status']        = $tripEarning->booking_status;  
                            $res['customer_id']           = $tripEarning->customer_id;
                            $res['driver_id']             = $tripEarning->driver_id;                                   
                            $res['service_id']            = $tripEarning->service_typeid;
                            $res['service_name']          = $tripEarning->servicename;
                            $res['service_image']         = base_url().'/serviceimage/'.$tripEarning->selected_image;
                            $res['db_total_fair']         = $tripEarning->total_fare;
                            $res['currency']              = $tripEarning->currency; 
                            $res['cancel_at']             = $tripEarning->cancel_at;

                            $res['passenger']['payment_type']          = $tripEarning->payment_type;
                            $res['passenger']['transaction_id']        = $tripEarning->transaction_id;
                            $res['passenger']['trip_fare']             = $trip_fare;
                            $res['passenger']['multi_address_charge']  = $tripEarning->multi_address_charge;
                            $res['passenger']['total_surcharge']       = $tripEarning->total_surcharge;
                            $res['passenger']['total_waiting_charge']  = $tripEarning->total_waiting_charge;
                            $res['passenger']['promotion']             = $promotion;
                            $res['passenger']['currency']              = $tripEarning->currency;
                            //********===========================================************//
                            $passengerTotal               = ($trip_fare+$tripEarning->total_surcharge+$tripEarning->total_waiting_charge+$tripEarning->multi_address_charge)-$promotion;
                            //********===========================================************//
                            $res['passenger']['total_fare']   = $passengerTotal;
                            $res['tripEarning']['payment_type']  = $tripEarning->payment_type;
                            $res['tripEarning']['earning']       = $passengerTotal;
                            $res['tripEarning']['promotion']     = $promotion;
                            $res['tripEarning']['total_earning'] = $passengerTotal+$promotion;
                            $res['tripEarning']['currency']      = $tripEarning->currency;                            
                            $res['tripEarning']['cancel_charge']      = $tripEarning->cancel_charge;
                            $res['tripEarning']['company_commission']    = '';
                            if(!empty($companyCommssion)) {
                                $res['tripEarning']['company_commission'] = $companyCommssion->total_commission;
                            }
                            $earningdata[$booking_date][] = $res;                            
                        }                
                    }
                    // =================================== Get Total Earning ====================================
                    $totalearning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'status'=>1,'commission_at_string>='=>$earningDatest,'commission_at_string<='=>$earningDatend));
                    $totalcancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_status'=>2,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,));
                    $grand_earning = $totalearning-$totalcancelCharge;

                    // =================================== ***************** ====================================
                    $response = array('success'=>1,'error'=>0,'message'=>'Success','total_earning'=>$grand_earning,'bookingCount'=>$bookingCount,'data'=>$earningdata,'booking_date'=>$dateArray);
                    echo json_encode($response); 

                }                   
                else{
                    $response = array('success'=>0,'error'=>1,'message'=>'No booking found','bookingCount'=>$bookingCount,'data'=>$earningdata,'booking_date'=>$dateArray);
                    echo json_encode($response); 
                }                
            }
        }
        else{
            $this->index();
        }
    }

        //================================================================================================================//

    public function getStatment(){
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['status']) && $_POST['status']!=''){
            extract($_POST);
            $driver_cashearning = 0;
            $surcharge =0;
            $waiting_charge=0;
            $multistop_charge=0;
            $driver_otherearning = 0;
            $cancel_charge  = 0;
            $commission=0;
            if($status==1){   //This week week Statment
                $today            = date('d-m-Y',strtotime('monday this week'));
                $lastday          = date('d-m-Y',strtotime('sunday this week'));                
            }
            elseif($status==2){  //MonthlyStatment
                $paramarray = array('month_first_date','month_last_date');
                $vResponse = $this->AuthModel->checkRequiredParam($paramarray,$_POST);
                if(isset($vResponse['status']) && $vResponse['status']==0)
                {
                    $response = array("error"=>1,'success'=>0,'message'=>$vResponse['message']);
                    echo json_encode($response);die();
                }else{
                    $lastday          = $_POST['month_last_date'];
                    $today            = $_POST['month_first_date'];    
                }                
                //$lastday          = date('1-m-Y');
                //$today            = date("d-m-Y",strtotime("+1 month -1 second",strtotime(date("1-m-Y"))));                  
            }
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'Invalid request');
                echo json_encode($response);die();
            }            
            $earningDatest    = strtotime($lastday.' 00:00');            
            $earningDatend    = strtotime($today.' 11:59 PM');

            $where = '(driver_id='.$driver_id.' and booking_at_string>='.$earningDatest.' and booking_at_string<='.$earningDatend.' and (booking_status=4 or booking_status=2))';
            $cashPaymentBooking = $this->AuthModel->getMultipleRecord('booking',$where,'');
            $total_trip = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'driver_id'=>$driver_id));
            foreach ($cashPaymentBooking as $cash => $p) {
                if($p->booking_status==4 && $p->payment_type=="Cash"){
                    $booking_id = $p->booking_id;
                    $booking_earn = $this->AuthModel->getSingleRecord('booking_earning',array('booking_id'=>$booking_id,'driver_id'=>$driver_id));
                    $driver_cashearning =$driver_cashearning+$booking_earn->driver_earning;
                    $commission = $commission+$booking_earn->total_commission;
                    $Bfare = $this->AuthModel->getSingleRecord('booking_fare',array('booking_id'=>$booking_id));
                    if(!empty($Bfare)){
                        $surcharge      = $surcharge+$Bfare->total_surcharge;
                        $waiting_charge = $waiting_charge+$Bfare->total_waiting_charge;
                        $multistop_charge = $multistop_charge+$Bfare->multi_address_charge;
                    }
                }
                if($p->booking_status==4 && $p->payment_type!="Cash"){
                    $booking_id = $p->booking_id;
                    $booking_earn = $this->AuthModel->getSingleRecord('booking_earning',array('booking_id'=>$booking_id,'driver_id'=>$driver_id));
                    $driver_otherearning = $driver_otherearning+$booking_earn;
                    $commission = $commission+$booking_earn->total_commission;                          
                }                
                $cancel_charge = $cancel_charge+$p->cancel_charge;
            }
            $othercharge = $surcharge+$waiting_charge+$multistop_charge;
            $cash_earning = $driver_cashearning-$othercharge;
            $res['tripEarning'] = array('cash_payment'=>$cash_earning,'surcharge'=>$surcharge,'waiting_charge'=>$waiting_charge,'multistop_charge'=>$multistop_charge,'trip_earning'=>$driver_cashearning);
            $res['other_payments'] =array('trip_fare'=>$driver_otherearning,'promotion'=>0,'bonous'=>0,'invite_bonous'=>0);
            $res['deduction'] = array('commission'=>$commission,'cancel_fee'=>$cancel_charge);
            $res['from'] = $lastday;
            $res['to']   = $today;
            $response = array('success'=>1,'error'=>0,'message'=>'success','total_trip'=>$total_trip,'data'=>$res);
            echo json_encode($response);            
        }
        else{
            $this->index();
        }
    }

    public function fare_detailafter_complete(){
        if(isset($_POST['booking_id']) && $_POST['booking_id']!=''){
            extract($_POST);
            $detail = $this->BookingModel->getEarningInvoice($booking_id);
            //$fare_details = $this->AuthModel->getSingleRecord('booking_fare',array('booking_id'=>$booking_id));
            if(!empty($detail)){
                $customer_id = $detail->customer_id;
                $customer = $this->AuthModel->getSingleRecord('users',array('id'=>$customer_id));
                $cdetails = $this->AuthModel->keychange($customer);
                $res['booking_id'] = $detail->booking_id;
                $res['driver_id']  = $detail->driver_id;
                $res['customer_id'] = $detail->customer_id;
                $res['booking_at'] = $detail->booking_at;
                $res['ride_complete_at']= $detail->ride_complete_at;
                $res['booking_status'] = $detail->booking_status;
                $res['payment_type']  = $detail->payment_type;
                $res['total_surcharge']  = $detail->total_surcharge;
                $res['waiting_charge']  = $detail->total_waiting_charge;                
                $res['multi_stop_charge']  = $detail->multi_address_charge;
                $res['total_regular_charge']  = $detail->total_regular_charge;
                $res['total_per_minute_charge']  = $detail->total_per_minute_charge;
                $res['total_fare']    = $detail->total_fare;
                $res['currency']      = $detail->currency;
                $res['promotion']  = 0;
                $res['customer_name']  = $cdetails->name;
                $res['customer_image'] = $cdetails->image;
                $res['customer_rating']  = get_rating($cdetails->id);
                $response = array('success'=>1,'error'=>0,'message'=>'success','data'=>$res);
                echo json_encode($response);
            }
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'Booking record not found');
                echo json_encode($response); 
            }
        }
        else{
            $this->index();
        }
    }

    public function getMonthlyEarning(){
        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['year']) && $_POST['year']!=''){
            extract($_POST); 
            $total_earning=0;     
            $y = $year;  
            $cyear = date('Y');
            if($cyear!=$y){                
                $en        = date("31-12-".$y);
                //echo $en;die();  
            }
            else{
                $en   = date("d-m-Y",strtotime("+1 month -1 second",strtotime(date("1-m-Y"))));  
            }
            $st         = date('01-01-'.$y); 
            //echo $en;die();  
            $start      = strtotime($st);
            $cend       = strtotime($en);
                    // ======================================Get total trips =====================================
            $completeTrip  = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$start,'booking_at_string<='=>$cend,'driver_id'=>$driver_id,'booking_status'=>4));
            $passengerWhere = '(booking_at_string>='.$start.' and booking_at_string<='.$cend.' and driver_id='.$driver_id.' and (booking_status=3 or booking_status=7))'; 
            $passengerCancel = $this->AuthModel->checkRows('booking',$passengerWhere); 
            $driverCancel = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$start,'booking_at_string<='=>$cend,'driver_id'=>$driver_id,'booking_status'=>2));
            $data['bookingCount'] = array("completedTrip"=>$completeTrip,"passengerCancel"=>$passengerCancel,'driverCancel'=>$driverCancel);
                    // ===================================Get Monthly Records ====================================
            $begin      = new DateTime($st); 
            $end        = new DateTime($en);                       
            $daterange  = new DatePeriod($begin, new DateInterval('P1M'), $end);        
            foreach($daterange as $date){
                $mst  =  $date->format("d-m-Y")." 00:00";
                $men  = $date->format("t-m-Y")." 11:59 PM";
                //echo $st.'<br>';
                //echo $en.'<br>';
                $trips = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>strtotime($mst),'booking_at_string<='=>strtotime($men),'driver_id'=>$driver_id));            
                
                $earning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'commission_at_string>='=>strtotime($mst),'commission_at_string<='=>strtotime($men),'status'=>1));                
                $cancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_at_string>='=>strtotime($mst),'booking_at_string<='=>strtotime($men),'booking_status'=>2));
                $r['month']= $date->format('M-Y');
                $thisMonthEarning = $earning-$cancelCharge;                
                $r['thisMonthEarning'] = $thisMonthEarning;
                $r['total_trip']       = $trips;
                $res[]=$r;
            }
            $data['monthlydata']=$res;
                    // =================================== Get Total Earning ====================================
            $totalearning  = $this->AuthModel->getColumnSum('booking_earning','driver_earning',array('driver_id'=>$driver_id,'status'=>1));
            $totalcancelCharge = $this->AuthModel->getColumnSum('booking','cancel_charge',array('driver_id'=>$driver_id,'booking_status'=>2));
            $grand_earning = $totalearning-$totalcancelCharge;
                    // =================================== ***************** ====================================
            $response = array('success'=>1,'error'=>0,'total_earning'=>$grand_earning,'data'=>$data);
            echo json_encode($response);           
        }
        else{
            $this->index();
        }
    }



            /*==========================================================================================*/

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
            $lastWeeksunday  = strtotime(date('d-m-Y',strtotime("last monday -1 days")).' 11:59 PM');
            $firstDate =  strtotime(date('01-m-Y').' 00:00');
            $lastDate  =  strtotime(date('t-m-Y').' 11:59 PM');
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

    public function getCancelBooking()
    { 
        // 1= today, 2=yesterday, 3=this week 4=last week, 5=this month

        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['status']) && $_POST['status']!=''){
            extract($_POST);
            $todayst = strtotime(date('d-m-Y').' 00:00');
            //echo strtotime($todayst);
            $todaynd = strtotime(date('d-m-Y').' 11:59 PM');
            $yesterdayst = strtotime(date('d-m-Y',strtotime("-1 days")).' 00:00'); 
            $yesterdaynd = strtotime(date('d-m-Y',strtotime("-1 days")).' 11:59 PM'); 
            $thisMonday  = strtotime(date('d-m-Y',strtotime("previous monday")).' 00:00');
            $thisSunday  = strtotime(date('d-m-Y',strtotime("next sunday")).' 11:59 PM');
            $lastWeekMonday = strtotime(date('d-m-Y',strtotime('last monday -7 days')).' 00:00');            
            $lastWeeksunday  = strtotime(date('d-m-Y',strtotime("last monday -1 days")).' 11:59 PM');
            $firstDate =  strtotime(date('01-m-Y').' 00:00');
            $lastDate  =  strtotime(date('t-m-Y').' 11:59 PM');
            //echo $firstDate;  
            $or_where = "(booking_status=2 or booking_status=3 or booking_status=7)";
                //========================================================================================
            $todaywhere = array('booking_at_string>='=>$todayst,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id);
            $todayBooking = $this->AuthModel->checkRowsWithOr_where('booking',$todaywhere,$or_where); 
            //print_r($this->db->last_query());die();
                //========================================================================================            
            $yesterdaywhere = array('booking_at_string>='=>$yesterdayst,'booking_at_string<='=>$yesterdaynd,'driver_id'=>$driver_id);
            $yesterdayBooking = $this->AuthModel->checkRowsWithOr_where('booking',$yesterdaywhere,$or_where); 
                    //========================================================================================            
            $thisweekwhere = array('booking_at_string>='=>$thisMonday,'booking_at_string<='=>$thisSunday,'driver_id'=>$driver_id);
            $thisweekBooking =  $this->AuthModel->checkRowsWithOr_where('booking',$thisweekwhere,$or_where); 
                   //========================================================================================        
            $lastweekwhere = array('booking_at_string>='=>$lastWeekMonday,'booking_at_string<='=>$lastWeeksunday,'driver_id'=>$driver_id);
            $lastweekBooking =  $this->AuthModel->checkRowsWithOr_where('booking',$lastweekwhere,$or_where); 
                    //========================================================================================
            $thisMonthkwhere = array('booking_at_string>='=>$firstDate,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id);
            $thisMonthBooking = $this->AuthModel->checkRowsWithOr_where('booking',$thisMonthkwhere,$or_where);
            
            $bookingcount = array('todaycancel'=>$todayBooking,'yesterdaycancel'=>$yesterdayBooking,'thisweekcancel'=>$thisweekBooking,'lastweekcancel'=>$lastweekBooking,'thisMonthcancel'=>$thisMonthBooking);
                //========================================================================================

            $orderby = 'booking_id DESC';
            if($status==1){
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$todaywhere,$or_where,$orderby); 
            }
            elseif($status==2){     //2=yesterday 
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$yesterdaywhere,$or_where,$orderby);             
            }
            elseif($status==3){     //3=this week    
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$thisweekwhere,$or_where,$orderby);
            }
            elseif($status==4){      //4=last week 
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$lastweekwhere,$or_where,$orderby);            
            }
            elseif($status==5){      //5=this month  
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$thisMonthkwhere,$or_where,$orderby);
            }
            elseif($status==6){      //6=last 30 days 
                $last30 = strtotime(date('d-m-Y',strtotime("-30 days")).' 00:00'); 
                $last30Where = array('booking_at_string>='=>$last30,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id);
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$last30Where,$or_where,$orderby);
            }
            elseif($status==7){      //7=last 60 days  
                $last60 = strtotime(date('d-m-Y',strtotime("-60 days")).' 00:00'); 
                $last60where = array('booking_at_string>='=>$last60,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id);
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$last60where,$or_where,$orderby);

            }
            elseif($status==8){      //8=last 90 days  
                $last90 = strtotime(date('d-m-Y',strtotime("-90 days")).' 00:00'); 
                $last90where = array('booking_at_string>='=>$last90,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id);
                $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$last90where,$or_where,$orderby);
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
                    $where = array('booking_at_string>='=>$from,'booking_at_string<='=>$to,'driver_id'=>$driver_id);    
                    $response1 = $this->AuthModel->getOrWhereMultipleRecord('booking',$where,$or_where,$orderby);
                }                
            }
            else{
                $response = array('success'=>0,'error'=>1,'message'=>'invalid request');
                echo json_encode($response);die();
            }
            //$response1=$this->AuthModel->getOrWhereMultipleRecord('booking',$where,$or_where,$orderby);
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

    public function getBookingCancelbyDriver()
    { 
        // 1= today, 2=yesterday, 3=this week 4=last week, 5=this month

        if(isset($_POST['driver_id']) && $_POST['driver_id']!='' && isset($_POST['status']) && $_POST['status']!=''){
            extract($_POST);
            $todayst = strtotime(date('d-m-Y').' 00:00');
            //echo strtotime($todayst);
            $todaynd = strtotime(date('d-m-Y').' 11:59 PM');
            $yesterdayst = strtotime(date('d-m-Y',strtotime("-1 days")).' 00:00'); 
            $yesterdaynd = strtotime(date('d-m-Y',strtotime("-1 days")).' 11:59 PM'); 
            $thisMonday  = strtotime(date('d-m-Y',strtotime("previous monday")).' 00:00');
            $thisSunday  = strtotime(date('d-m-Y',strtotime("next sunday")).' 11:59 PM');
            $lastWeekMonday = strtotime(date('d-m-Y',strtotime('last monday -7 days')).' 00:00');            
            $lastWeeksunday  = strtotime(date('d-m-Y',strtotime("last monday -1 days")).' 11:59 PM');
            $firstDate =  strtotime(date('01-m-Y').' 00:00');
            $lastDate  =  strtotime(date('t-m-Y').' 11:59 PM');
            //echo $firstDate;   
            $todayBooking = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$todayst,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id,'booking_status'=>2));          
            $yesterdayBooking = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$yesterdayst,'booking_at_string<='=>$yesterdaynd,'driver_id'=>$driver_id,'booking_status'=>2)); 
            $thisweekBooking =  $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$thisMonday,'booking_at_string<='=>$thisSunday,'driver_id'=>$driver_id,'booking_status'=>2));        
            $lastweekBooking =  $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$lastWeekMonday,'booking_at_string<='=>$lastWeeksunday,'driver_id'=>$driver_id,'booking_status'=>2));        
            $thisMonthBooking = $this->AuthModel->checkRows('booking',array('booking_at_string>='=>$firstDate,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id,'booking_status'=>2));
            $bookingcount = array('todaycancel'=>$todayBooking,'yesterdaycancel'=>$yesterdayBooking,'thisweekcancel'=>$thisweekBooking,'lastweekcancel'=>$lastweekBooking,'thisMonthcancel'=>$thisMonthBooking);
            if($status==1)
            {
                $where = array('booking_at_string>='=>$todayst,'booking_at_string<='=>$todaynd,'driver_id'=>$driver_id,'booking_status'=>2);
            }
            elseif($status==2){     //2=yesterday 
                $where = array('booking_at_string>='=>$yesterdayst,'booking_at_string<='=>$yesterdaynd,'driver_id'=>$driver_id,'booking_status'=>2);             
            }
            elseif($status==3){     //3=this week    
                $where = array('booking_at_string>='=>$thisMonday,'booking_at_string<='=>$thisSunday,'driver_id'=>$driver_id,'booking_status'=>2);
            }
            elseif($status==4){      //4=last week 
                $where = array('booking_at_string>='=>$lastWeekMonday,'booking_at_string<='=>$lastWeeksunday,'driver_id'=>$driver_id,'booking_status'=>2);            
            }
            elseif($status==5){      //5=this month  
                $where = array('booking_at_string>='=>$firstDate,'booking_at_string<='=>$lastDate,'driver_id'=>$driver_id,'booking_status'=>2);
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
                    $response['booking_id']   =  $deliver->booking_id;
                    $response['booking_id_show']   =  $deliver->booking_id_show;
                    $response['driver_id']    =  $deliver->driver_id;
                    $response['customer_id']  =  $deliver->customer_id;
                    $response['booking_status']= $deliver->booking_status;
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

    //$next_notificationDate = date('d-m-Y', strtotime("+".$notification_frequency.' '.$type));    
    //echo "Next Monday:". date('Y-m-d', strtotime('next monday', strtotime($givenDate)));
    //echo "Previous Monday:". date('Y-m-d', strtotime('previous monday', strtotime($givenDate)));

    /*public function completeBooking()
    {      
        $response=array();
        $responsedata=array();            
        $current_time=date('Y-m-d H:i:s',time());
        $data_val = array('driver_id');
        $validation = $this->AbhiModel->param_validation($data_val,$_REQUEST);
        if(isset($validation['status']) && $validation['status']=='0')
        {echo json_encode(array('response'=>'false','message'=>$validation['message']));die;
        }
        else
        {
            $driver_id= $_REQUEST['driver_id'];
            $fromdate= $_REQUEST['fromdate'];
            $todate= $_REQUEST['todate'];
            $date=strtotime(date("Y-m-d h:i:s"));
            $where=array('driver_id'=>$driver_id,'booking_status'=>'4');
            $response1=$this->AbhiModel->select_queryfromdatetotwodate('booking',$where,$fromdate,$todate);
            if(!empty($response1)){
                foreach($response1 as $deliver){
                    $service_type = $this->AuthModel->getSingleRecord('servicetype',array('typeid'=>$deliver->service_typeid));
                    $response['booking_id']=$deliver->booking_id;
                    $response['booking_status']=$deliver->booking_status;
                    $response['ride_complete_at']=$deliver->ride_complete_at;
                    $response['currency']=$deliver->currency;
                    $response['payment_type']=$deliver->payment_type;
                    $response['total_fare']=$deliver->total_fare;
                    $response['booking_at']=$deliver->booking_at;
                    $response['service_name']=$service_type->servicename;

                    $response['pickup']=$deliver->pickup;
                    $wheree=array('booking_id'=> $response['booking_id']);
                    $booking_dropoffs=$this->AbhiModel->select_query('booking_dropoffs',$wheree);
                    $response['dropoff']=$booking_dropoffs[0]->dropoff;   
                    $responsedata[]=$response;
                }
            echo json_encode(array('response'=>'true','Completed'=>$responsedata));            
            }
            else{echo json_encode(array('response'=>'false','message'=>'No complete ride yet'));
            }     
        }            
    }*/

    

    //==========================================================================================================//
}