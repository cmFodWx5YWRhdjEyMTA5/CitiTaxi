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

                $data['basic']=$this->AuthModel->keychange($drivers);
                $data['vechile']= $vechileDetails;                
                $data['vechileImages']=$this->AuthModel->keychange($Images);
                $data['license']=$this->AuthModel->keychange($licenseDetails);

                $response = array("success"=>1,"error"=>0,"message"=>"success","data"=>$data);
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Something went wrong","data"=>array());
                echo json_encode($response);
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
                $checkmail   = array("email"=>$email);
                $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            }
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
                $data = array(
                        "google_id"=>$google_id,
                        "ref_code"=>$this->AuthModel->radomno(6),
                        'name'=>$name,
                        'mobile'=>$mobile,
                        'email'=>$email,   
                        'address'=>$address,                 
                        "activeStatus" =>'Inactive',        //Active, Inactive
                        "signup_status"=>'incomplete',
                        "user_type"=>1
                    );
                if($uid = $this->AuthModel->singleInsert('users',$data))
                {                    
                    if(isset($_FILES['licenseimage']) && $_FILES['licenseimage']!='')
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
    }


    public function checkMultipleImage()
    {
        print_r($_FILES['otherdocument']['type']);
        /*$imagename = $this->AuthModel->imageUpload($_FILES['otherdocument'],'otherdocument');
        echo json_encode($imagename);*/
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
            if($status_type=='power')
            {
                $upStatus = $this->AuthModel->updateRecord($where,'users',array('power_status'=>$status));
            }
            elseif($status_type=='online_status')
            {
                $upStatus = $this->AuthModel->updateRecord($where,'users',array('online_status'=>$status));
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
    


//===============================================================================================================================//


   




    

}