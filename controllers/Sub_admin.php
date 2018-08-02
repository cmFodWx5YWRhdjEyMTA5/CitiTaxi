<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sub_admin extends CI_Controller {
	function __construct() {
        parent::__construct();  
        $this->load->library('session');
       if($this->session->userdata('fleet_email')=='')
       {
            redirect(site_url('Welcome/sub_admin_login'));
       }   
    }

    public function index(){
        $this->load->view('Sub_admin/analytics');
    }
    
    public function drivers(){
        $fleet_id=$this->session->userdata('fleet_id');
        $table_name = 'users';
            $orderby  = "`id` DESC";
            $where = array('user_type'=>1,'fleet_id'=>$fleet_id);
            $drivers = $this->AuthModel->driverDetailsfleet($where);
            if(!empty($drivers))
            {
                $data['userlist']=$drivers;            
                $this->load->view('Sub_admin/driver_details',$data);
            }
            else
            {
                $data["error"] =1;
                $data["message"] = 'No Driver found';
                $data["userdata"]='';
                $this->load->view('Sub_admin/driver_details',$data);
            }
    }
    public function other_details($userId)
    {
        //$userId  = $_GET['id'];
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
        $this->load->view('Sub_admin/Driver_otherDetails',$data);
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
                $this->load->view('Sub_admin/vechicleImage',$data);
            }
            else
            {
                $data['error'] =1;
                $data['message'] = "No vechicle image available, Please add vechicle images";
                $this->load->view('Sub_admin/vechicleImage',$data);
            }
        }
        else
        {
            redirect(site_url().'Sub_adminer');
        }
    }
    public function updateStatus()       //update status Active or Banned
    {
        $table_name = 'users';
        $updata = array('activeStatus'=>$_POST['activeStatus'],'suspend_type'=>'');
        $checkWhere = array('id'=>$_POST['id']);
        if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
        {   
            //$checkWhere = array("user_id"=>$_POST['id']);
            //$this->AuthModel->delete_record('useraction',$checkWhere);
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
        $fromstring = strtotime(date('d-m-Y H:i:s'));
        //print_r($todate);
        $where = array("user_id"=>$user_id);
        $upwhere = array('id'=>$user_id);
        $userUpdateData = array('activeStatus'=>'Suspended','suspend_type'=>$suspend_type);
        $checkExist = $this->AuthModel->checkRows($table_name,$where);
        if($checkExist>0)
        {
            $updata = array('suspand_type'=>$suspend_type,'from'=>date('d-m-Y H:i:s'),'fromstring'=>$fromstring,'to'=>$todate,'tostring'=>strtotime($todate),'suspend_by'=>$byStatus);
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
            //print_r($insertData);die();
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
     /*public function vehicleimage()
    {
        $userid = $_POST['user_id'];
        $table_name = 'vechile_images'; $orderby  = "";
        $where = array('driver_id'=>$userid);
        $images = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);        
        if(!empty($images))
        {          
            $data['error']=0;
            $data['images']=$images;      
            echo json_encode($data);
        }
        else
        {
            $data['error'] =1;
            $data['message'] = "No vechicle image";
            echo json_encode($data);
        }
    }*/
    public function add_customer()
    {
        if(isset($_POST['submit']))
        {    
            $table_name = 'users';
            extract($_POST);
            $checkmail   = array("email"=>$email);
            $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            $checkMobile = array('mobile' =>$mobile);
            $mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);   
            if($checkEmail>0)
            {
                $respose["error"]=1;
                $respose["message"]="Email already Exist";
                $this->load->view('Sub_admin/add_customer',$respose);
            }           
            elseif($mobileExist>0)
            {   
                $respose= array("error"=>1,"message"=>"Mobile number has already registered");
                $this->load->view('Sub_admin/add_customer',$respose);
            }
            else
            {
                $imagename ='default.jpg';
                if(isset($_FILES['image']) && $_FILES['image']['name']!='')
                {
                    $folder_name = 'userimage';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
                }
                $data= array(
                    "ref_code"      =>$this->AuthModel->radomno(6),
                    "name"          =>$name,
                    "gender"        =>$gender,
                    "mobile"        =>$mobile,
                    "email"         =>$email,
                    "password"      =>$password,
                    "image"         =>$imagename,
                    "activeStatus"  =>'Active',        //Active, Inactive
                    "device_type"   =>2         //0=android, 1=ios
                    );
                
                if($uid = $this->AuthModel->singleInsert($table_name,$data))
                {
                    $this->AuthModel->user_score($uid,0);  //add score
                    $respose["success"] = 1;
                    $respose["message"] = "Customer Record has been successfully saved";
                    $this->load->view('Sub_admin/add_customer',$respose);
                }
                else
                {
                    $respose["error"] = 1;
                    $respose["message"] = "Error occur! Please try again";
                    $this->load->view('Sub_admin/add_customer',$respose);
                }       
            }            
        }
        else
        {
            $this->load->view('Sub_admin/add_customer');
        }
    }
    public function update_customer($id)
    {
        if($id!='')
        {            
            $userId  = $id;  $table_name = 'users';
            $where = array('id'=>$userId);
            if(isset($_POST['submit']))
            {   
                extract($_POST);
                $ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);                
                $checkWhere  = array("mobile"=>$mobiles);
                $checkMobile = $this->AuthModel->checkRows($table_name,$checkWhere);
                if($checkMobile>0 && $mobiles != $ProfileData->mobile)
                {
                    $respose  = array("error"=>1,"message"=>"Mobile number already registered","customer"=>$ProfileData);
                    $this->load->view('updateCustomer',$respose);
                }
                else
                {
                    $imagename        = $ProfileData->image;
                    $image_type       = $ProfileData->image_type;
                    if(isset($_FILES['image']) && $_FILES['image']['name']!='')
                    {
                        $folder_name = 'userimage';
                        $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);
                        $image_type  = 0;
                    }
                    $updata= array(                 
                            "name"          =>$name,
                            "mobile"        =>$mobiles, 
                            "gender"        =>$gender,
                            "image"         =>$imagename,
                            "image_type"    =>$image_type,          //0=normal, 1=media                 
                            );
                    $UpdateData = $this->AuthModel->updateRecord($where,$table_name,$updata);
                    if($UpdateData)
                    {
                        $ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
                        $response["success"]            = 1;
                        $response["message"]            = "Record has been successfully updated";
                        $response["customer"]           = $ProfileData;
                        $this->load->view('updateCustomer',$response);
                    }
                    else
                    {
                        $response["error"]              = 1;    
                        $response["message"]            = "Oops! Error occur. Please Try again";
                        $response["customer"]           = $ProfileData;
                        $this->load->view('updateCustomer',$response);
                    }
                }               
            }
            else
            {
                $data['customer'] = $this->AuthModel->getSingleRecord($table_name,$where);
                $this->load->view('updateCustomer',$data);
            }
        }
        else
        {
            redirect(base_url());
        }
    }
    public function delete_customer($id)
    {
        if($id!='')
        { 
            $table_name = "users";
            $checkWhere = array('id'=>$id);
            if($this->AuthModel->delete_record($table_name,$checkWhere))
            {
                echo '<script>alert("Customer record has been successfully removed");
                window.location.href="'.site_url('Home/customers').'";
                </script>';
            }
            else
            {
                echo '<script>alert("Customer record is not removed, Please try again");
                window.location.href="'.site_url('Home/customers').'";
                </script>';
            }
        }
        else
        {
            redirect(site_url('Home/customers'));
        }
    }
    public function customers()
    {  
        $fleet_id=$this->session->userdata('fleet_id');
        $table_name = 'users';
        $orderby  = "`id` DESC";
        $where = array('user_type'=>0,'fleet_id'=>$fleet_id);
        //$orderby ="";
        $customers = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;
            
            $this->load->view('Sub_admin/customer_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No customers found';
            $data["userdata"]='';
            $this->load->view('Sub_admin/customer_details',$data);
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
                $this->load->view('Sub_admin/add_driver_Sub_adminer',$respose);
            }           
            elseif($mobileExist>0)
            {   
                $respose= array("error"=>1,"message"=>"Mobile number has already registered");
                $this->load->view('Sub_admin/add_driver_Sub_adminer',$respose);
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
                    "device_type"   =>2,         //0=android, 1=ios, 2=web
                    "fleet_id"      =>$fleet_id
                    );
                if($uid = $this->AuthModel->singleInsert($table_name,$data))
                {
                    $this->AuthModel->user_score($uid,0);  //add score
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
                        "number_plate"=>$vehicle_NoPlate,
                        "insurance_company"=>$insuranceCompany,
                        "insurance_no"=>$insuranceNumber,
                        "insurance_expire"=>$insuranceExpiredate,                        
                        /*"fleet_company"=>$fleet_company,
                        "fleet_country"=>$fleet_country,
                        "fleet_address"=>$fleet_address,*/
                        "booking_limit"=>$bookingLimit
                        );
                    $table_name = "vechile_details";
                    if($vid= $this->AuthModel->singleInsert($table_name,$vechileDetails))
                    {
                        foreach($service_type as $k =>$v)
                        {
                            $c["driver_id"]    = $uid;
                            $c["vehicle_id"]   = $vid;
                            $c["service_type_id"] =  $v;                
                            $service_types[]=$c;
                        }
                        if(!empty($service_types)){                            
                            $this->AuthModel->batchInsert('vehicle_servicetype',$service_types);
                        }
                    }
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
                    $this->load->view('Sub_admin/add_driver_Sub_adminer',$respose);
                }
                else
                {
                    $respose["error"] = 1;
                    $respose["message"] = "Error occur! Please try again";
                    $this->load->view('Sub_admin/add_driver_Sub_adminer',$respose);
                }
            }
        }
        else{            
            $this->load->view('Sub_admin/add_driver_Sub_adminer');
        }
    }
    public function tripHistroy()
    {
        $id=$this->uri->segment(3);
        $table_name = 'booking';
        $orderby  = "`booking_id` DESC";
        $where = array('customer_id'=>$id);
        //$orderby ="";
        $customers = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;            
            $data['drivercheck']="2";  
            $this->load->view('Sub_admin/trip_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["userdata"]='';
              $data['drivercheck']="2";  
            $this->load->view('Sub_admin/trip_details',$data);
        }
    }
 public function tripHistroydriver()
    {
        $id=$this->uri->segment(3);
        $table_name = 'booking';
        $orderby  = "`booking_id` DESC";
        $where = array('driver_id'=>$id);
        //$orderby ="";
        $customers = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;
            $data['drivercheck']="1";            
            $this->load->view('Sub_admin/trip_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["userdata"]='';
             $data['drivercheck']="1"; 
            $this->load->view('Sub_admin/trip_details',$data);
        }
    } 
 public function get_dropoff_address()  //ajax use
    {
        extract($_POST);
        $address = $this->AuthModel->getMultipleRecord('booking_dropoffs',array('booking_id'=>$booking_id),'');
        if(!empty($address))
        {
            $res = array("success"=>1,"data"=>$address);
            echo json_encode($res);
        }
        else
        {
            $res=array("success"=>0);
            echo json_encode($res);
        }
    }
public function logout()
    {      
        $this->load->library('session');
        $this->session->sess_destroy();
        $this->load->view('Sub_admin/login');     
    }
    public function changePassword()
    {
        $data=new stdClass();
        if(isset($_POST['submit']))
        { 
            $id =$this->session->userdata('fleet_id');
            $old_password=$this->input->post('old_password');
            $password=$this->input->post('password');
            $rr=$this->AuthModel->change_password_fleet($old_password,$password,$id);    
            if($rr)
            {
                $data->success=1;
                $data->message="Password has been updated successful";
                $data->id =$id;
                $this->load->view('Sub_admin/change_password',$data);
            }
            else
            {
                $data->error=1;
                $data->id =$id;
                $data->message="Old Password not found! Please Enter correct old password";
                $this->load->view('Sub_admin/change_password',$data);
            }
        }
        else
        {
            $data=new stdClass();
            $data->id =$this->session->userdata('id');
            $this->load->view('Sub_admin/change_password',$data);
        }
    }
    public function profile()
    {
        $table_name ="fleets";
        $fleet_id=$this->session->userdata('fleet_id');
        $where  = array('fleet_id'=>$fleet_id);
        if(isset($_POST['submit']))
        {
            extract($_POST);
            $picture=$_POST['admin_img'];
            if(isset($_FILES['admin_img']['name']) && $_FILES['admin_img']['name']!='')
            {
                $folder_name = 'userimage';
                $picture   = $this->AuthModel->imageUpload($_FILES['admin_img'],$folder_name);
            }
            $updata=array(
                    'fleet_name'=>$fleet_name, 
                    'image'=>$picture
                    );
            if($this->AuthModel->updateRecord($where,$table_name,$updata))
            {
                $ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);
                $user_data        = array('fleet_id'=>$ProfileData->fleet_id, "fleet_email" =>$ProfileData->fleet_email,"fleet_name" =>$ProfileData->fleet_name, 'image'=>$ProfileData->image);
                //print_r($user_data);
                //$this->session->sess_destroy();
                $this->session->set_userdata($user_data);
                $rest['success']=1;
                $rest['message']='Profile updated successfully!';
                $rest['admin']=$ProfileData;
                $this->load->view('Sub_admin/admin_profile',$rest);
            }
            else
            {
                $rest['error']=1;
                $rest['message']='Oops! Profile is not updated, Try again';
                $rest['admin']=$this->AuthModel->getSingleRecord($table_name,$where);
                $this->load->view('Sub_admin/admin_profile',$rest);
            }            
        }
        else
        {           
            $data['admin']=$this->AuthModel->getSingleRecord($table_name,$where);
            $this->load->view('Sub_admin/admin_profile',$data);
        }        
    }
public function ForgetPassword()
    {
        if(isset($_POST['email']) && $_POST['email']!='')
        {
            $email=$_POST['email'];
            $table_name = "fleets";
            $res=$this->AuthModel->forget_password_Sub_adminer($table_name,$email);
            if($res==0)
            {
                $response["success"]        = 1;
                $response['message']        = "Please Check your Email inbox";
                $response['Email ']         = $email;
                $this->load->view('Sub_admin/forgetPassword',$response);      
            }
            elseif($res==1)
            {
                $response["error"]          = 2;    
                $response["success"]        = 0;
                $response['message']        = "Error occur! Please try again";
                $this->load->view('Sub_admin/forgetPassword',$response);          
            }
            else
            {
                $response['error']=1;
                $response['success']=0;
                $response['message']="This email Id is not found! Please enter correct email id";
                $this->load->view('Sub_admin/forgetPassword',$response);
            }
        }
        else
        {           
            $this->load->view('Sub_admin/forgetPassword');
        }
    }
    public function reset_password()     //for user
    {
        $data=new stdClass();
        $email=$_GET['email'];
        $check = $this->checkForgetTime($email);
        if($check=='not find')
        {
            $data->email=$email;
            $data->error=1;
            $data->message="something went wrong. Please try again password reset request";
            $this->load->view('Sub_admin/recover_userpassword',$data);
        }
        elseif($check=='correct')
        {
            if($this->input->post('recover'))
            {
                extract($_POST);
                //$enpass= md5($new_password);
                $table_name = 'fleets';
                if($this->AuthModel->updateRecord(array('fleet_email'=>$email),$table_name,array('password'=>$password)))
                {
                    $data->success=1;
                    $data->email=$email;
                    $data->message="Password has been successfull reset";
                    $this->load->view('Sub_admin/recover_userpassword',$data);
                }
                else
                {
                    $data->error=1;
                    $data->email=$email;
                    $data->message="Password not Reset, Please Try again";
                    $this->load->view('Sub_admin/recover_userpassword',$data);
                }
            }
            else
            {
                $data->email=$email;
                $data->error=0;
                $this->load->view('Sub_admin/recover_userpassword',$data);
            }
        }
        else
        {
            $data->email=$email;
            $data->error=1;
            $data->message = "This link has been expired. Please resend password request";
            $this->load->view('recover_userpassword',$data);
        }
        
    }
    public function checkForgetTime($email)
    {
        $where = array('fleet_email'=>$email);
        if($data = $this->AuthModel->getSingleRecord('fleets',$where))
        {
            $formtime = $data->forget_timestamp;           // 60
            $endTime = strtotime('+2 hours',strtotime($formtime));  //end time   80
            $ctime   = strtotime(date('Y-m-d H:i:s'));  //current time   75
            //$ctime = strtotime('2018-03-07 04:28:02');
            if($ctime<=$endTime && $ctime>=$formtime)
            {
                return 'correct';
            }
            else
            {
                return false;
            }
        }
        else
        {
            return 'not find';
                    
        }
    }
    public function apptemplate()
    {
        $id = $this->session->userdata('id');
        $admin_status = $this->session->userdata('status');
        $update = date('Y-m-d h:i:s');
        $table_name ="apptemplates";
        // $templates = $this->AuthModel->getMultipleRecord($table_name,array(),array());
        //print_r($templates);die();*/
        if(isset($_POST['submit']))
        {  
            extract($_POST);
            $timeline = $timeline_img;
            if(isset($_FILES['timeline']['name']) && $_FILES['timeline']['name']!='')
            {
                $folder_name = 'appTemplate';
                $timeline   = $this->AuthModel->imageUpload($_FILES['timeline'],$folder_name);
            }
            $data = array('image'=>$timeline,'image_status'=>'timeline','adminStatus'=>$admin_status,'creatbyId'=>$id,'update_at'=>$update);
            if($uid = $this->AuthModel->singleInsert($table_name,$data))
            {
                $where   = array('image_status'=>'timeline');
                $resData = $this->AuthModel->getSingleRecord($table_name,$where);
                $respose= array('success'=>1,'message'=>'image successfully saved','template'=>$resData);
                $this->load->view('apptemplate',$respose);                
            }      
            else
            {
                $where   = array('image_status'=>'timeline');
                $resData = $this->AuthModel->getSingleRecord($table_name,$where);
                $respose = array('success'=>1,'message'=>'image is not saved, please try again!','template'=>$resData);
                $this->load->view('apptemplate',$respose);  
            }
        }
        else
        {
            $response = $this->templateData();
            $this->load->view('apptemplate',$response);
        }
    }
    function templateData()
    {        
        //echo "<pre>";
        $tampdata = [];
        $table_name ="apptemplates";
        $tempaltes = $this->AuthModel->getMultipleRecord($table_name,array(),array());
        foreach ($tempaltes as $temp) {
            if($temp->image_status=='Copy_right')
            {
                $tampdata[$temp->image_status] = $temp->message;
            }
            else
            {
                $tampdata[$temp->image_status] = $temp->image;
            }
            
        }
        return $tampdata;
        //print_r($tampdata);
    }
    public function imageUpload()
    {
        $id             = $this->session->userdata('id');
        $admin_status   = $this->session->userdata('status');
        $image_status   = $_POST['image_status'];
        $folder_name    = 'appTemplate';
        $timeline       = $this->AuthModel->ajaximageUpload($_FILES['file'], $folder_name);
        $update         = date('Y-m-d h:i:s');
        if($timeline){
            $table_name = 'apptemplates';
            $updata = array('image'=>$timeline,'image_status'=>$image_status,'adminStatus'=>$admin_status,'creatbyId'=>$id,'update_at'=>$update);
            $checkWhere = array('image_status'=>$image_status);
            if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
            {
                echo $image_status.' has been successfully updated';
            }
            else
            {
                echo 'Not update';
            }
        }
        else
        {
             echo 'Oops! something went wrong,'.$image_status.' not update';
        }
    }
    public function ajaxDataUpdate()
    {
        $id             = $this->session->userdata('id');
        $admin_status   = $this->session->userdata('status');
        $update         = date('Y-m-d h:i:s');
        $image_status         = $_POST['status'];
        $message        = $_POST['message'];
        $table_name = 'apptemplates';
        $updata = array('message'=>$message,'image_status'=>$image_status,'adminStatus'=>$admin_status,'creatbyId'=>$id,'update_at'=>$update);
        $checkWhere = array('image_status'=>$image_status);
        if($this->AuthModel->updateRecord($checkWhere,$table_name,$updata))
        {
            echo $image_status.' has been successfully updated';
        }
        else
        {
            echo 'Not update';
        }
        //print_r($_POST);
    }
    public function servie_type()
    { 
        $types = $this->AuthModel->getMultipleRecord('servicetype',array(),array());
        $data['types'] = $types;
        $this->load->view('serviceType',$data);
    }
    public function addServiceType()
    {
        $serviceType = $_POST['servicetype'];
        if($this->AuthModel->singleInsert('servicetype',array("servicename"=>$serviceType)))
        {
            echo 'Service Type has been successfully saved';
        }
        else
        {
            echo 'Oops! Something went wrong, Please try again';
        }
    }
    public function updateServiceType()
    {
        $serviceType = $_POST['servicetype'];
        $typeid      = $_POST['typeid'];
        if($this->AuthModel->updateRecord(array('typeid'=>$typeid),'servicetype',array('servicename'=>$serviceType)))
        {
            echo 'Service Type has been successfully updated';
        }
        else
        {
            echo 'Oops! Something went wrong, Please try again';
        }
    }
    public function changeTypeStatus()
    {
        $typeid = $_POST['typeid'];
        $status = $_POST['status'];
        if($this->AuthModel->updateRecord(array('typeid'=>$typeid),'servicetype',array('status'=>$status)))
        {
            echo 'Service status has been successfully changed';
        }
        else
        {
            echo 'Oops! Something went wrong, Please try again';
        }
    }
    public function booking()
    {  $response=array();
        $fleet_id=$this->session->userdata('fleet_id');
          
        $table_name = 'booking';
        $orderby  = "`booking_id` DESC";
        $where = array('fleet_id'=>$fleet_id,'user_type'=>'1');
        $customerss = $this->AuthModel->getMultipleRecord('users',$where,'fleet_id');
        //echo '<pre>';print_r($customerss);die;
        foreach($customerss as $customer){
          $driver_id=$customer->id; 
         // $where1 = array('driver_id'=>$driver_id);
          $customers = $this->AuthModel->getMultipleRecord($table_name,array('driver_id'=>$driver_id),false);
          $response[]=$customers;
         }
        //echo '<pre>';print_r($response);die;
        if(!empty($response))
        {
            $data['userlist']=$response;            
            $this->load->view('Sub_admin/booking_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["userdata"]='';
            $this->load->view('Sub_admin/booking_details',$data);
        }
    }
     public function pendingbooking()
    {
         $fleet_id=$this->session->userdata('fleet_id'); 
         $where = array('fleet_id'=>$fleet_id,'user_type'=>'1');
         $customerss = $this->AuthModel->getMultipleRecord('users',$where,'fleet_id');
         //print_r($customerss);die;
         $driver_id =$customerss[0]->id;
         //$where1 = array('driver_id'=>$driver_id);
        
        $this->AuthModel->updateRecord(array('seen_status'=>0),'booking',array('seen_status'=>1));
        $table_name = 'booking';
        $orderby    = "`booking_id` DESC";
        $where1      = "((booking_status!=4 or booking_status!=3 or booking_status!=7) and booking_type='later' and driver_id=".$driver_id.")";
           
       // $where = array('booking_status'=>'8','booking_status'=>'9');
        //$orderby ="";
        $customers = $this->AuthModel->getMultipleRecord($table_name,$where1,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;            
            $this->load->view('Sub_admin/pending_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["userdata"]='';
            $this->load->view('Sub_admin/pending_details',$data);
        }
    }   
    public function nearbydriver($booking_id,$city)        //driver details
    {  
        if($booking_id!='' && $city!='')
        {
            $data['booking_id'] = $booking_id;
            $table_name = 'users';
            $orderby  = "`id` DESC";
            $where = array('city'=>$city,'user_type'=>'1','power_status'=>'on','activeStatus'=>'Active');       
            $drivers = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
            if(!empty($drivers))
            {               
                $data['userlist']=$drivers;            
                $this->load->view('nearbydriver',$data);
            }
            else
            {
                $data["error"] =1;
                $data["message"] = 'No Driver found';
                $data["userdata"]='';
                $this->load->view('nearbydriver',$data);
            }
        }
        else
        {
            redirect(base_url());
        }
        
    } 
    public function assignbooking()  //ajax use
    {
        extract($_POST);
        if($this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('driver_id'=>$driver_id,'booking_status'=>0)))
        {
            $this->sendNotificationForLaterbooking($booking_id,$driver_id);
            echo 'Booking has been successfully assigned';
        }
        else
        {
            echo 'Oops! Something went wrong';           
        }
    } 
    public function sendNotificationForLaterbooking($booking_id,$driver_id)
    {
        $bookingdata = $this->AuthModel->getSingleRecord('booking',array('booking_id'=>$booking_id));
        if(!empty($bookingdata))
        {
            $driver_id    = $bookingdata->driver_id;
            $customer_id  = $bookingdata->customer_id;
            $customer_msg = "Your Booking id ".$booking_id." has been assgned to driver";
            $driver_msg   = "New Later booking request for you";
            $this->Communication_model->sendToDriver($driver_id,$driver_msg);  
            $this->Communication_model->sendToPassenger($customer_id,$customer_msg);        
        }        
    }
    
    
    
}