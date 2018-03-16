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

    





    public function other_details($userId)
    {
        $table_name = 'vechile_details';
        $where = array('driver_id'=>$userId);
        $vechile_details = $this->AuthModel->getSingleRecord($table_name,$where);
        //=========================================================================================================//
        $table_name = 'bankdetails';
        $where = array('user_id'=>$userId);
        $bank  = $this->AuthModel->getSingleRecord($table_name,$where);
        //echo '<pre>';
        //print_r($bank);
        //=========================================================================================================//
        $table_name = 'driver_license';
        $where = array('user_id'=>$userId);
        $license = $this->AuthModel->getSingleRecord($table_name,$where);
        //print_r($license);die();
        //=========================================================================================================//
        $data['vechile_details']= $vechile_details;
        $data['bank']           = $bank;
        $data['license']        = $license;

        $this->load->view('Driver_otherDetails',$data);
    }


    

    public function update($id)
    {
        if($id!='')
        {
            $userId  = $id;  $table_name = 'users';
            $where = array('id'=>$userId);
            $profileData = $this->AuthModel->getSingleRecord($table_name,$where);
            if(isset($_POST['submit'])){
                extract($_POST);
                $ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);                
                $checkWhere  = array("mobile"=>$mobiles);
                $checkMobile = $this->AuthModel->checkRows($table_name,$checkWhere);
                if($checkMobile>0 && $mobiles != $ProfileData->mobile)
                {
                    $respose  = array("error"=>1,"message"=>"Mobile number already registered","driver"=>$ProfileData);
                    $this->load->view('updateDriver',$respose);
                }
                else
                {
                    $imagename        = $ProfileData->image;
                    $image_type       = $ProfileData->image_type;
                    if(isset($_FILES['driverimages']) && $_FILES['driverimages']['name']!='')
                    {
                        $folder_name = 'userimage';
                        $imagename   = $this->AuthModel->imageUpload($_FILES['driverimages'],$folder_name);
                        $image_type  = 0;
                    }
                    $updata= array(                 
                            "name"          =>$name,
                            "mobile"        =>$mobiles, 
                            "gender"        =>$gender,
                            "image"         =>$imagename,
                            "image_type"    =>$image_type,          //0=normal, 1=media  
                            "dob"           =>$dob,
                            "nationality"   =>$nationality,   
                            "city"          =>$city,
                            "address"       =>$address     
                            );

                    $UpdateData = $this->AuthModel->updateRecord($where,$table_name,$updata);
                    if($UpdateData)
                    {
                        $ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
                        $response["success"]            = 1;
                        $response["message"]            = "Record has been successfully updated";
                        $response["driver"]           = $ProfileData;
                        $this->load->view('updateDriver',$response);
                    }
                    else
                    {
                        $response["error"]              = 1;    
                        $response["message"]            = "Oops! Error occur. Please Try again";
                        $response["driver"]           = $ProfileData;
                        $this->load->view('updateDriver',$response);
                    }
                } 
            }
            else{
                $data['driver'] = $profileData;
                $this->load->view('updateDriver',$data);
            }   
        }
        else
        {
            redirect(base_url());
        }
    }

    public function delete($id)
    {
        if($id!='')
        { 
            $table_name = "users";
            $checkWhere = array('id'=>$id);
            if($this->AuthModel->delete_record($table_name,$checkWhere))
            {
                echo '<script>alert("Driver record has been successfully removed");
                window.location.href="'.site_url('Driver').'";
                </script>';
            }
            else
            {
                echo '<script>alert("Customer record is not removed, Please try again");
                window.location.href="'.site_url('Driver').'";
                </script>';
            }
        }
        else
        {
            redirect(site_url('Driver'));
        }
    }

    public function updateVechile($vechileId)
    {
        if($vechileId!='')
        {
            $vechileId  = $vechileId;  $table_name = 'vechile_details';
            $where = array('vechileId'=>$vechileId);
            $vechileData = $this->AuthModel->getSingleRecord($table_name,$where);
            $data['vechile'] = $vechileData;
            $data['userId']  = $vechileData->driver_id;
            if(isset($_POST['submit']))
            {   
                extract($_POST);
                $vechileDetails = array(
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
                $UpdateData = $this->AuthModel->updateRecord($where,$table_name,$vechileDetails);
                if($UpdateData)
                {
                    $vechileData      = $this->AuthModel->getSingleRecord($table_name,$where);
                    $response["success"]            = 1;
                    $response["message"]            = "Vechicle record has been successfully updated";
                    $response["vechile"]            = $vechileData;
                    $response['userId']              = $vechileData->driver_id;
                    $this->load->view('updateVechile',$response);
                }
                else
                {
                    $data["error"]              = 1;    
                    $data["message"]            = "Oops! Error occur. Please Try again";
                    $this->load->view('updateVechile',$data);
                }

            }
            else
            {
                $this->load->view('updateVechile',$data);    
            }            
        }
        else
        {
             redirect(base_url());
        }
    }

    public function updateBankDetails($id)
    {
        if($id!='')
        {
            $table_name = 'bankdetails';
            $where = array('bankId'=>$id);
            $bankData = $this->AuthModel->getSingleRecord($table_name,$where);
            $data['tag'] = 'bank';
            $data['bank']= $bankData;
            $data['userId'] = $bankData->user_id;
            if(isset($_POST['submit']))
            {
                extract($_POST);
                $at   = date('Y-m-d H:i');
                $bankDetails = array(
                        "bankName"=>$bankname,
                        "branchCode_Name"=>$branchCode_Name,
                        "accountNo"=>$accountNo,
                        "update_at"=>$at
                        );
                $checkWhere = array('bankId'=>$id);
                if($this->AuthModel->updateRecord($checkWhere,$table_name,$bankDetails)){
                    $bankData = $this->AuthModel->getSingleRecord($table_name,$where);
                    $response['tag'] = 'bank';
                    $response['success'] = 1;
                    $response['message'] = "Bank record has been successfully updated";
                    $response['bank']= $bankData;
                    $response['userId'] = $bankData->user_id;
                    $this->load->view('updateOther_details',$response);
                }
                else
                {
                    $data["error"]             = 1;    
                    $data["message"]           = "Oops! Error occur. Please Try again";
                    $this->load->view('updateOther_details',$data);
                }
            }
            else
            {                
                $this->load->view('updateOther_details',$data);
            }
        }
        else
        {
             redirect(base_url());
        }
    }

    public function updateLicenseDetails($id)
    {
        if($id!='')
        {
            $table_name = 'driver_license';
            $where = array('licenseId'=>$id);
            $licenseData = $this->AuthModel->getSingleRecord($table_name,$where);
            $data['tag'] = 'license';
            $data['license']= $licenseData;
            $data['userId'] = $licenseData->user_id;
            if(isset($_POST['submit'])){
                extract($_POST);
                $licenseimage =$pre_image;
                if(isset($_FILES['licenseimages']) && $_FILES['licenseimages']['name']!='')
                {
                    $folder_name = 'licenseImage';
                    $licenseimage   = $this->AuthModel->imageUpload($_FILES['licenseimages'],$folder_name);
                }
                $at   = date('Y-m-d H:i');
                $licData = array("licenseNumber"=>$licenseno,"expireDate"=>$expiredate,"expireDateString"=>strtotime($expiredate),"licenseImage"=>$licenseimage,'update_at'=>$at);
                $checkWhere = array('licenseId'=>$id);
                if($this->AuthModel->updateRecord($checkWhere,$table_name,$licData)){
                    $licenseData = $this->AuthModel->getSingleRecord($table_name,$where);
                    $response['tag'] = 'license';
                    $response['success'] = 1;
                    $response['message'] = "License record has been successfully updated";
                    $response['license']= $licenseData;
                    $response['userId'] = $licenseData->user_id;
                    $this->load->view('updateOther_details',$response);
                }
                else
                {
                    $data["error"]             = 1;    
                    $data["message"]           = "Oops! Error occur. Please Try again";
                    $this->load->view('updateOther_details',$data);
                }
            }
            else
            {                
                $this->load->view('updateOther_details',$data);
            }

        }
        else
        {
            redirect(base_url());
        }
    }

    public function vechileImage($driverid,$vechileid)
    {
        if($vechileid!=''){
            $table_name = 'vechile_images'; $orderby  = "`image_id` DESC";
            $where = array('vechile_id'=>$vechileid);
            $images = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
            $data['driverid']=$driverid;
            $data['vechileid'] = $vechileid;
            $data['images'] = $images;
            if(!empty($images))
            {                
                $this->load->view('vechicleImage',$data);
            }
            else
            {
                $data['error'] =1;
                $data['message'] = "No vechicle image available, Please add vechicle images";
                $this->load->view('vechicleImage',$data);
            }
        }
        else
        {
            redirect(base_url());
        }
    }



    public function ajaxMulitpleImageupload()
    {
        //$vechile = $_POST['vechile'];
        //$driver  = $_POST['driver'];
        //print_r($_POST['vechile']);die();
        $folder_name    = 'vechicleImage';
        $table_name = "vechile_images";
        $timeline       = $this->AuthModel->ajaxMultipleUpload($_FILES['images'], $folder_name);
        if($timeline['totalupload']!=0)
        {
            foreach ($timeline['saved'] as $key => $value) {
                //echo $timeline['saved'][$key];
                $imagedata = array("vechile_id"=>$_POST['vechile'],"driver_id"=>$_POST['driver'],"vechile_image"=>$timeline['saved'][$key]);
                $this->AuthModel->singleInsert($table_name,$imagedata);
            }
            print_r("Total saved image=".$timeline['totalupload']." unsave image=".$timeline['unupload']);
        }
        else
        {
            print_r("Something went wrong. This files is not upload ".$timeline['unsave']);
        }

    }

    public function imageUpdate()    //Update Vechicle image using ajax
    {
        $image_id   = $_POST['image_id'];
        $folder_name    = 'vechicleImage';        
        $Image_name     = $this->AuthModel->ajaximageUpload($_FILES['file'], $folder_name);
        $update         = date('Y-m-d h:i:s');

        if($Image_name){
            $table_name = 'vechile_images';
            $updata = array('vechile_image'=>$Image_name,'update_at'=>$update);
            $checkWhere = array('image_id'=>$image_id);
            if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
            {
                echo 'Vechicle image has been successfully updated';
            }
            else
            {
                echo 'Oops! something went wrong, Vechicle image is not update';
            }
        }
        else
        {
             echo 'Oops! something went wrong, Vechicle image is not update';
        }
    }

    public function vechileImageDelete()
    {
        if($_POST['imageid']!='')
        { 
            $imageid= $_POST['imageid'];
            $table_name = "vechile_images";
            $checkWhere = array('image_id'=>$imageid);
            if($this->AuthModel->delete_record($table_name,$checkWhere))
            {
                echo "Vechicle Image has been successfully removed";
            }
            else
            {
                echo "Oops! Something went wrong, Please try again";
            }
        }
        else
        {
            echo "Oops! Something went wrong, Please try again";
        }
    }

    public function updateStatus()       //update status Active or Banned
    {
        $table_name = 'users';
        $updata = array('activeStatus'=>$_POST['activeStatus'],'suspend_type'=>'');
        $checkWhere = array('id'=>$_POST['id']);
        if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
        {   
            $checkWhere = array("user_id"=>$_POST['id']);
            $this->AuthModel->delete_record('useraction',$checkWhere);
            echo 'Action has been successfully saved';
        }
        else
        {
            echo 'Oops! something went wrong, Action is not update';
        }
    }

    public function Suspend()
    {
        $table_name = "useraction";
        $byStatus = $this->session->userdata('status');
        $suspend_type = $_POST['type'].' Day';
        $user_id = $_POST['id'];
        $to = date('d-m-Y', strtotime("+".$suspend_type));
        $todate = $to.' 23:59:59';
        $fromstring = strtotime(date('Y-m-d H:i:s'));
        //print_r($todate);
        $where = array("user_id"=>$user_id);
        $upwhere = array('id'=>$user_id);
        $userUpdateData = array('activeStatus'=>'Suspended','suspend_type'=>$suspend_type);
        $checkExist = $this->AuthModel->checkRows($table_name,$where);
        if($checkExist>0)
        {
            $updata = array('suspand_type'=>$suspend_type,'from'=>date('Y-m-d H:i:s'),'fromstring'=>$fromstring,'to'=>$todate,'tostring'=>strtotime($todate),'suspend_by'=>$byStatus);
            if($this->AuthModel->updateRecord($where,$table_name,$updata))
            {
                $this->AuthModel->updateRecord($upwhere,'users',$userUpdateData);   
                echo "Action has successfully saved. User will be suspended to ".$to; 
            }
            else
            {
                echo "Oops something went wrong! Please try again";
            }

        }
        else
        {
            $insertData = array("user_id"=>$user_id,'suspand_type'=>$suspend_type,'from'=>date('Y-m-d H:i:s'),'fromstring'=>$fromstring,'to'=>$todate,'tostring'=>strtotime($todate),'suspend_by'=>$byStatus);
            if($this->AuthModel->singleInsert($table_name,$insertData))
            {
                $this->AuthModel->updateRecord($upwhere,'users',$userUpdateData);
                echo "Action has successfully saved. User will be suspended to ".$to; 
            }
            else
            {
                echo "Oops something went wrong! Please try again";
            }
        }
    }   



    

}