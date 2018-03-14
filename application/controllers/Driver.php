<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Driver extends CI_Controller {
	function __construct() {
        parent::__construct();  
        $this->load->library('session');  
        if($this->session->userdata('email')=='')
        {
            redirect(base_url());
        }   
    }

    public function index()        //driver details
    {
        $table_name = 'users';
        $orderby  = "`id` DESC";
        $where = array('user_type'=>1);
        $drivers = $this->AuthModel->driverDetails();
        if(!empty($drivers))
        {
            $data['userlist']=$drivers;            
            $this->load->view('driver_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No Driver found';
            $data["userdata"]='';
            $this->load->view('driver_details',$data);
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

    public function checkEmail()          //for ajax use
    {
        $table_name="users";
        $checkmail   = array("email"=>$_POST['email']);
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
        $table_name="users";
        $checkmobile = array('mobile' =>$_POST['mobile'],'mobile!='=>'');
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

    


    public function addDriver()
    {
        if(isset($_POST['submit'])){
            extract($_POST);
            $table_name  = "users";
            $checkmail   = array("email"=>$email);
            $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            $checkMobile = array('mobile' =>$mobile,'mobile!='=>'');
            $mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);   
            if($checkEmail>0)
            {
                $respose["error"]=1;
                $respose["message"]="Email already Exist";
                $this->load->view('add_driver',$respose);
            }           
            elseif($mobileExist>0)
            {   
                $respose= array("error"=>1,"message"=>"Mobile number has already registered");
                $this->load->view('add_driver',$respose);
            }
            else
            {
                $imagename ='default.jpg';
                if(isset($_FILES['driverimage']))
                {
                    $folder_name = 'userimage';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['driverimage'],$folder_name);
                }
                $data= array(
                    "ref_code"      =>$this->AuthModel->radomno(6),
                    "user_type"     =>1,           //0=customer, 1= driver
                    "name"          =>$name,
                    "dob"           =>$dob,
                    "gender"        =>$gender,
                    "mobile"        =>$mobile,
                    "email"         =>$email,
                    "password"      =>$password,
                    "image"         =>$imagename,
                    "image_type"    =>0,          //0=normal, 1=media
                    "nationality"   =>$nationality,
                    "city"          =>$city,
                    "address"       =>$address,
                    "activeStatus"  =>'Active',        //Active, Inactive
                    "device_type"   =>2         //0=android, 1=ios, 2=web
                    );
                if($uid = $this->AuthModel->singleInsert($table_name,$data))
                {
                    $bankDetails = array(
                        "user_id"=>$uid,
                        "bankName"=>$bankname,
                        "branchCode_Name"=>$branchCode_Name,
                        "accountNo"=>$accountNo,
                        );
                    $table_name = "bankdetails";
                    $this->AuthModel->singleInsert($table_name,$bankDetails);
                    $vechileDetails = array(
                        "driver_id"=>$uid,
                        "brand"=>$brand,
                        "sub_brand"=>$subbrand,
                        "insurance_company"=>$insuranceCompany,
                        "insurance_no"=>$insuranceNumber,                        
                        "fleet_company"=>$fleet_company,
                        "fleet_country"=>$fleet_country,
                        "fleet_address"=>$fleet_address,
                        "booking_limit"=>$bookingLimit
                        );
                    $table_name = "vechile_details";
                    $this->AuthModel->singleInsert($table_name,$vechileDetails);
                    $licenseimage ='defaultLicense.jpg';
                    if(isset($_FILES['licenseimage']))
                    {
                        $folder_name = 'licenseImage';
                        $licenseimage   = $this->AuthModel->imageUpload($_FILES['licenseimage'],$folder_name);
                    }
                    $licenseData = array("user_id"=>$uid,"licenseNumber"=>$licenseno,"expireDate"=>$expiredate,
                        "expireDateString"=>strtotime($expiredate),"licenseImage"=>$licenseimage);
                    $table_name = "driver_license";
                    $this->AuthModel->singleInsert($table_name,$licenseData);

                    $respose["success"] = 1;
                    $respose["message"] = "Driver recored has been successfully saved";
                    $this->load->view('add_driver',$respose);
                }
                else
                {
                    $respose["error"] = 1;
                    $respose["message"] = "Error occur! Please try again";
                    $this->load->view('add_driver',$respose);
                }
            }
        }
        else{            
            $this->load->view('add_driver');
        }
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
                        "insurance_company"=>$insuranceCompany,
                        "insurance_no"=>$insuranceNumber,                        
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