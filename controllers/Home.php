<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
        parent::__construct();  
        $this->load->model('AuthModel');       

        if($this->session->userdata('email')=='')
        {
            redirect(base_url());
        }   
        $this->load->library('pagination');
        $this->load->model('DummyModel');
    }

    public function index()        //customer details
    {
        $this->load->view('dashboard');
    }

    public function analytics()
    {
        $this->load->view('analytics');
    }

    public function notifications(){
        $message = $this->AuthModel->getMultipleRecord('support',array(),'');
        //print_r($message);die();
        if(!empty($message)){
            $this->AuthModel->updateRecord(array('seen_status'=>0),'support',array('seen_status'=>1));
            $data['messagelist'] = $message;
            $this->load->view('support_notifications',$data);
        }
        else{            
            $data['error'] = 1;
            $data['message'] = 'No support message found';
            $data['messagelist'] = $message;
            $this->load->view('support_notifications',$data);
        }
    }

    public function remove_supportmsg()
    {
        if(isset($_POST['submit']))
        {            
            extract($_POST); 
            if($this->AuthModel->delete_record('support',array('support_id'=>$support_id)))
            { 
                $response = array("success"=>1,"error"=>0,"message"=>"Record has been successfully removed");               
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Record is not removed, Please try again");               
                echo json_encode($response);                
            }
        }
        else{
            $response = array("success"=>0,"error"=>1,"message"=>"access denied");               
            echo json_encode($response);
        }        
    }

    public function remove_Allsupportmsg()
    {
        if(isset($_POST['submit']))
        {
            extract($_POST); 
            if($this->db->truncate('support'))
            { 
                $response = array("success"=>1,"error"=>0,"message"=>"All Record has been successfully removed");               
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Record is not removed, Please try again");               
                echo json_encode($response);                
            }
        }
        else{
            $response = array("success"=>0,"error"=>1,"message"=>"access denied");               
            echo json_encode($response);
        }        
    }

    public function remove_selected_msg(){       
        if(isset($_POST['submit'])){
            extract($_POST);
            //print_r($_POST);
            $dlt=0;
            $data =''; 
            foreach ($users as $v) {
                if($this->AuthModel->delete_record('support',array('support_id'=>$v))){
                    $dlt++;
                }                
            } 
            if($dlt>0 or $dlt<=count($users)){
                $response = array("success"=>1,"error"=>0,"message"=>"Record has been successfully removed");               
                echo json_encode($response);   
            }                            
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Record is not removed, Please try again");               
                echo json_encode($response);                
            }
        }
        else{
            $response = array("success"=>0,"error"=>1,"message"=>"access denied");               
            echo json_encode($response);
        }        
    }

    public function add_customer()
    {
        if(isset($_POST['submit']))
        {    
            $table_name = 'users';
            extract($_POST);
            $checkmail   = array("email"=>$email);
            $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);     
            $checkMobile = array('mobile' =>$mobile,'mobile!='=>'');
            $mobileExist = $this->AuthModel->checkRows($table_name,$checkMobile);   
            if($checkEmail>0)
            {
                $respose["error"]=1;
                $respose["message"]="Email already Exist";
                $this->load->view('add_customer',$respose);
            }           
            elseif($mobileExist>0)
            {   
                $respose= array("error"=>1,"message"=>"Mobile number has already registered");
                $this->load->view('add_customer',$respose);
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
                    $this->load->view('add_customer',$respose);
                }
                else
                {
                    $respose["error"] = 1;
                    $respose["message"] = "Error occur! Please try again";
                    $this->load->view('add_customer',$respose);
                }       
            }            
        }
        else
        {
            $this->load->view('add_customer');
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
                $ProfileData = $this->AuthModel->getSingleRecord($table_name,$where); 
                $checkmail   = array("email"=>$emails,'user_type'=>0);
                $checkEmail  = $this->AuthModel->checkRows($table_name,$checkmail);               
                $checkWhere  = array("mobile"=>$mobiles,'user_type'=>0);
                $checkMobile = $this->AuthModel->checkRows($table_name,$checkWhere);
                if($checkEmail>0 && $emails != $ProfileData->email)
                {
                    $respose  = array("error"=>1,"message"=>"Email already Exist","customer"=>$ProfileData);
                    $this->load->view('updateCustomer',$respose);                    
                }
                elseif($checkMobile>0 && $mobiles != $ProfileData->mobile)
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
                            "email"         =>$emails,
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
        $table_name = 'users';
        $orderby  = "`id` DESC";
        $where = array('user_type'=>0);
        //$orderby ="";
        $customers = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;
            
            $this->load->view('customer_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No customers available';
            $data["userlist"]=$customers;
            $this->load->view('customer_details',$data);
        }
    }

    public function loadRecord($rowno=0)
    {     
        // Search text
        $search_text = "";
        if($this->input->post('submit') != NULL ){
            $search_text = $this->input->post('search');
            $this->session->set_userdata(array("search"=>$search_text));
        }
        //For Table pagaination
        $rowperpage = 10;        
        if($rowno != 0){  $rowno = ($rowno-1) * $rowperpage;  } 
        $allcount = $this->AuthModel->checkRows('users',array());
        $customers = $this->DummyModel->getData($rowno,$rowperpage,$search_text,'users',array());
        $config = $this->AuthModel->tableConfig();
        $config['base_url'] = site_url().'/Home/loadRecord';   
        $config['use_page_numbers'] = TRUE;     
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();        
        $data['row'] = $rowno;
        $data['search'] = $search_text;
        //End Table pagaination
        $orderby  = "`id` DESC";     
       
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;            
            $this->load->view('user_view',$data);
        }
        else
        {
            //$data["error"] =1;
            //$data["message"] = 'No customers available';
            $data["userlist"]=$customers;
            $this->load->view('user_view',$data);
        }
    }

    public function loadRecords($rowno=0){
        
        // Search text
        $search_text = "";
        $search='';
        if($this->input->post('submit') != NULL ){
            $search_text = $this->input->post('search');
            $this->session->set_userdata(array("search"=>$search_text));
            $search = array('name'=>$search_text);
        }/*else{
            if($this->session->userdata('search') != NULL){
                $search_text = $this->session->userdata('search');
            }
        }*/
        // Row per page
        $rowperpage = 5;
        // Row position
        if($rowno != 0){
            $rowno = ($rowno-1) * $rowperpage;
        }       
        // All records count
        $allcount = $this->DummyModel->checkRows('users',array());
        //$allcount = $this->DummyModel->getrecordCount($search_text,'countries',array());
        //echo $allcount;die();
        // Get  records
        $users_record = $this->DummyModel->getData($rowno,$rowperpage,$search,'users',array()); 
        //echo "<pre>";
        //print_r($users_record);die();
        // Pagination Configuration

        $config = $this->tableConfig();
        $config['base_url'] = site_url().'/Home/loadRecord';  
        $config['use_page_numbers'] = TRUE;      
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;

        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $data['userlist'] = $users_record;
        $data['row'] = $rowno;
        $data['search'] = $search_text;
        // Load view
        $this->load->view('user_view',$data);
    }

    

    public function changePassword()
	{
	    $data=new stdClass();
	    if(isset($_POST['submit']))
	    { 
	    	$id =$this->session->userdata('id');
			$old_password=$this->input->post('old_password');
			$password=$this->input->post('password');
        	$rr=$this->AuthModel->change_password($old_password,$password,$id);    
            if($rr)
            {
                $data->error=0;
                $data->success=1;
                $data->message="Password has been updated successful !";
                $data->id =$id;
                $this->load->view('change_password',$data);
            }
            else
            {
                $data->error=1;
                $data->success=0;
                $data->id =$id;
                $data->message="Old Password not found! Please Enter correct old password";
                $this->load->view('change_password',$data);
            }
	    }
	    else
	    {
	    	$data=new stdClass();
            $data->id =$this->session->userdata('id');
        	$this->load->view('change_password',$data);
	    }
	}

    public function profile()
    {
        $table_name ="admin";
        $where  = array('id'=>1);
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
                    'name'=>$name, 
                    'image'=>$picture
                    );
            if($this->AuthModel->updateRecord($where,$table_name,$updata))
            {
                $ProfileData      = $this->AuthModel->getSingleRecord($table_name,$where);

                $user_data        = array('id'=>$ProfileData->id, "email" =>$ProfileData->email,"name" =>$ProfileData->name, 'image'=>$ProfileData->image);
                //print_r($user_data);
                //$this->session->sess_destroy();
                $this->session->set_userdata($user_data);
                $rest['success']=1;
                $rest['message']='Profile updated successfully!';
                $rest['admin']=$ProfileData;
                $this->load->view('admin_profile',$rest);
            }
            else
            {
                $rest['error']=1;
                $rest['message']='Oops! Profile is not updated, Try again';
                $rest['admin']=$this->AuthModel->getSingleRecord($table_name,$where);
                $this->load->view('admin_profile',$rest);
            }            
        }
        else
        {           
            $data['admin']=$this->AuthModel->getSingleRecord($table_name,$where);
            $this->load->view('admin_profile',$data);
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

    public function add_service()
    {
        if(isset($_POST['submit']))
        {
            extract($_POST);
            $service       = array("servicename"=>$service_name);
            $checkservice  = $this->AuthModel->checkRows('servicetype',$service); 
            if($checkservice>0)
            {
                $response = array("pagetype"=>'Add',"success"=>1,"message"=>'Service Type has already existed');
                $this->load->view('add_serviceType',$response);
            }
            else{
                $selectimage = 'select.jpg';
                if(isset($_FILES['selectimage']['name']) && $_FILES['selectimage']['name']!='')
                {
                    $folder_name = 'serviceimage';
                    $selectimage   = $this->AuthModel->imageUpload($_FILES['selectimage'],$folder_name);
                }
                $unselectimage = 'unselect.jpg';
                if(isset($_FILES['unselectimage']['name']) && $_FILES['unselectimage']['name']!='')
                {
                    $folder_name = 'serviceimage';
                    $unselectimage   = $this->AuthModel->imageUpload($_FILES['unselectimage'],$folder_name);
                }
                $data = array('servicename'=>$service_name,'selected_image'=>$selectimage,'unselected_image'=>$unselectimage,'description'=>$description);            
                if($this->AuthModel->singleInsert('servicetype',$data))
                {
                    $response = array("pagetype"=>'Add',"success"=>1,"message"=>'Service Type has been successfully saved');
                    $this->load->view('add_serviceType',$response);
                }
                else
                {
                    $response = array("pagetype"=>'Add',"success"=>1,"message"=>'Oops! Something went wrong, Please try again');
                    $this->load->view('add_serviceType',$response);               
                }
            }                  
        }
        else{
            $response=array("pagetype"=>'Add');
            $this->load->view('add_serviceType',$response);   
        } 
    }
    
    public function updateServiceType($service_id)
    {
        if($service_id=='')
        {
            redirect('Home/servie_type');
        }
        else
        {            
            $service = $this->AuthModel->getSingleRecord('servicetype',array("typeid"=>$service_id));
            if(!empty($service))
            {
                if(isset($_POST['submit']))
                {
                    extract($_POST);                    
                    $services       = array("servicename"=>$service_name);
                    $checkservice  = $this->AuthModel->checkRows('servicetype',$services); 
                    if($checkservice>0 && $service_name!=$service->servicename)
                    {
                        $response = array("pagetype"=>'Update',"error"=>1,"message"=>'Service Type has already existed',"service"=>$service,'service_id'=>$service_id);
                        $this->load->view('add_serviceType',$response);
                    }
                    else
                    {
                        $selectimage = $service->selected_image;
                        if(isset($_FILES['selectimg']['name']) && $_FILES['selectimg']['name']!='')
                        {
                            $folder_name = 'serviceimage';
                            $selectimage   = $this->AuthModel->imageUpload($_FILES['selectimg'],$folder_name);
                        }
                        $unselectimage = $service->unselected_image;
                        if(isset($_FILES['unselectimg']['name']) && $_FILES['unselectimg']['name']!='')
                        {
                            $folder_name = 'serviceimage';
                            $unselectimage   = $this->AuthModel->imageUpload($_FILES['unselectimg'],$folder_name);
                        }
                        $data = array('servicename'=>$service_name,'selected_image'=>$selectimage,'unselected_image'=>$unselectimage,'description'=>$description);
                        if($this->AuthModel->updateRecord(array('typeid'=>$service_id),'servicetype',$data))
                        {
                            $service = $this->AuthModel->getSingleRecord('servicetype',array("typeid"=>$service_id));
                            $response = array("pagetype"=>'Update',"success"=>1,"message"=>'Service has been successfully update',"service"=>$service,'service_id'=>$service_id);
                            $this->load->view('add_serviceType',$response);
                        }
                        else
                        {
                            $response = array("pagetype"=>'Update',"success"=>1,"message"=>'Oops! Something went wrong, Please try again',"service"=>$service,'service_id'=>$service_id);
                            $this->load->view('add_serviceType',$response);               
                        } 
                    }
                                    
                }
                else{
                    $response=array("pagetype"=>'Update',"service"=>$service,'service_id'=>$service_id);
                    $this->load->view('add_serviceType',$response);   
                }
            }
            else
            {
                print_r("Unauthorised request");
            }
        }        
    }

    public function checkServiceName()       //for ajax use
    {
        $table_name="servicetype";
        $checkmobile = array('servicename' =>$_POST['service_type']);
        $checkServiceName  = $this->AuthModel->checkRows($table_name,$checkmobile); 
        if($checkServiceName>0)
        {
            echo json_encode(FALSE);
        }
        else
        {
            echo json_encode(true);
        }
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

    /*public function updateServiceType()
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
    }*/
    public function deleteservice_type()
    {
        if($_POST['serviceid']!='')
        { 
            $serviceid= $_POST['serviceid'];
            $table_name = "servicetype";
            $checkWhere = array('typeid'=>$serviceid);
            if($this->AuthModel->delete_record($table_name,$checkWhere))
            {
                echo "Service type has been successfully removed";
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

    public function websitepages()
    {
        $table_name = 'website_pages'; $orderby  = "`page_id` DESC";
        $where = array();
        $pages = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        if(!empty($pages))
        {
            $res['pages']= $pages; 
            $this->load->view('websitePages',$res); 
        }
        else
        {
            $res['error'] =1;
            $res['message'] = 'No page found!';
            $res['pages']=array(); 
            $this->load->view('websitePages',$res);     
        }
        
    }

    public function add_websitePage()
    {
        if(isset($_POST['submit']))
        {           
            extract($_POST);
            $imagename ='default.jpg';
            if(isset($_FILES['bannerImage']) && $_FILES['bannerImage']!='')
            {
                $folder_name = 'pageImages';
                $imagename   = $this->AuthModel->imageUpload($_FILES['bannerImage'],$folder_name);
            }
            $data = array(                
                'page_name' =>$page_name,
                'banner'    =>$imagename,
                'content'   =>$content
                );
            if($id = $this->AuthModel->singleInsert('website_pages',$data))
            {   
                $res['success'] = 1;
                $res['message'] = 'Page has been successfully added';                
                $this->load->view('add_websitePage',$res);
            }
            else
            {
                $res['error']   = 1;
                $res['message'] = 'Oops something went wrong! Please try again';            
                $this->load->view('add_websitePage',$res);   
            }
        }
        else
        {            
            $this->load->view('add_websitePage');
        }
    }

    public function pageImageUpload()
    {
        $folder_name    = $_POST['folder'];        
        if($imagename   = $this->AuthModel->ajaximageUpload($_FILES['file'], $folder_name))
        {
            echo base_url('pageImages/'.$imagename);
        }
        else
        {
            echo 'not upload';
        }
    }

    public function update_websitePage($id)
    {
        if(isset($id))
        {
            $page_id = $id;
            if(isset($_POST['submit']))
            {           
                extract($_POST);
                if(isset($_FILES['bannerImage']) && $_FILES['bannerImage']['name']!='')
                {
                    $folder_name = 'pageImages';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['bannerImage'],$folder_name);
                }
                $data= array(
                    'page_name'=>$page_name,
                    'banner'    =>$imagename,
                    'content'  =>$content
                    );
                if($this->AuthModel->updateRecord(array('page_id'=>$page_id),'website_pages',$data))                
                {   
                    $res['success'] = 1;
                    $res['message'] = 'Page content has been successfully update'; 
                    $res['pagecontent']  = $this->AuthModel->getSingleRecord('website_pages',array('page_id'=>$page_id));
                    $this->load->view('UpdateWebsitePage',$res);
                }
                else
                {
                    $res['error']   = 1;
                    $res['message'] = 'Oops something went wrong! Please try again'; 
                    $res['pagecontent']  = $this->AuthModel->getSingleRecord('website_pages',array('page_id'=>$page_id));           
                    $this->load->view('UpdateWebsitePage',$res);   
                }
            }
            else
            {  
                $res['pagecontent']  = $this->AuthModel->getSingleRecord('website_pages',array('page_id'=>$page_id));
                $this->load->view('UpdateWebsitePage',$res);
            }
        }
        else
        {
            redirect('Welcome');
        }
    }

    public function booking()
    {
        $table_name = 'booking';
        $orderby  = "`booking_id` DESC";
        $where = array('booking_type'=>'now');
        //$orderby ="";
        $customers = $this->AuthModel->getMultipleRecord($table_name,$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;            
            $this->load->view('booking_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["userdata"]='';
            $this->load->view('booking_details',$data);
        }
    }

    public function get_dropoff_address()
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

    public function pendingbooking()
    {
        $this->AuthModel->updateRecord(array('seen_status'=>0),'booking',array('seen_status'=>1));        
        $orderby    = "`booking_id` DESC";
        $where      = array('booking_status'=>'8','booking_status'=>'9');
        //$where      = "((booking_status!=4 or booking_status!=3 or booking_status!=7) and booking_type='later')";
        $customers  = $this->AuthModel->getMultipleRecord('booking',$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist'] = $customers;            
            $this->load->view('pending_details',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No booking found';
            $data["userdata"]='';
            $this->load->view('pending_details',$data);
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
            echo 'Booking has been successfully assigned';
        }
        else
        {
            echo 'Oops! Something went wrong';           
        }
    }

    public function add_point(){
        if(isset($_POST['submit'])){
            extract($_POST);
            $checkdata     = array("country"=>$country,'city'=>$city);
            $checkCity  = $this->AuthModel->checkRows('point_system',$checkdata); 
            if($checkCity>0)
            {
                $response = array('error'=>1,"message"=>'Point system has already exist for '.$city);
                $this->load->view('addpoint',$response);
            }
            else{
                $data = array(
                    "country"=>$country,
                    "city"   =>$city,
                    "currency"=>$currency,
                    "every_amount_spent"=>$amount_spent,
                    "get_point" =>$get_point,
                    "expire_date"=>$expire_date,
                    "expire_string"=>strtotime($expire_date),
                    "point_at" =>date('d-m-Y h:i A')
                );
                //print_r($data);die();
                if($this->AuthModel->singleInsert('point_system',$data)){
                    $response = array("success"=>1,"message"=>"Point has been successfully saved");
                    $this->load->view('addpoint',$response);
                }
                else{
                    $response = array("error"=>1,"message"=>"Oops! something went wrong, Please try again");
                    $this->load->view('addpoint',$response);
                }
            }          
        }
        else{
            $this->load->view('addpoint');  
        }
    }

    public function getpoints(){
        $points = $this->AuthModel->getMultipleRecord('point_system',array(),'point_id DESC');
        if(!empty($points)){
            $response = array('points'=>$points);
            $this->load->view('getpoints',$response);
        }
        else{
            $response = array('error'=>1,'message'=>'Point record is not available');
            $this->load->view('getpoints',$response);
        }
    }

    public function update_point($point_id){
        if($point_id==''){
            redirect('Home/');
        }
        else{ 
            $pointdata = $this->AuthModel->getSingleRecord('point_system',array('point_id'=>$point_id));
            if(isset($_POST['submit'])){
                extract($_POST);
                $data= array(
                    "every_amount_spent"=>$amount_spent,
                    "get_point" =>$get_point,
                    "expire_date"=>$expire_date,
                    "expire_string"=>strtotime($expire_date),
                    "point_at" =>date('d-m-Y h:i A')
                    );
                if($this->AuthModel->updateRecord(array('point_id'=>$point_id),'point_system',$data)){
                    $pointdata = $this->AuthModel->getSingleRecord('point_system',array('point_id'=>$point_id));
                    $response = array('success'=>1,'message'=>'Record has been successfully updated','point'=>$pointdata);
                    $this->load->view('updatepoint',$response); 
                }
                else{
                    $response = array('error'=>1,'message'=>'Opps! Record is not update. Please try again','point'=>$pointdata);
                    $this->load->view('updatepoint',$response); 
                }
            }
            else{
                $response = array('point'=>$pointdata);
                $this->load->view('updatepoint',$response);           
            }
        }
    }

    public function delete_point($point_id)
    {
        if($point_id!='')
        {             
            if($this->AuthModel->delete_record('point_system',array('point_id'=>$point_id)))
            {
                echo '<script>alert("Record has been successfully removed");
                window.location.href="'.site_url('Home/getpoints').'";
                </script>';
            }
            else
            {
                echo '<script>alert("Record is not removed, Please try again");
                window.location.href="'.site_url('Home/getpoints').'";
                </script>';
            }
        }
        else
        {
            redirect(site_url('Home/getpoints'));
        }
    }

    public function point_history($rowno=0)
    {     
        // Search text
        $search_name = "";
        $search_email = "";
        $search ='';        
        $search2='';
        if(isset($_POST['submit'])){
            $search_name = $this->input->post('name');
            $search_email = $this->input->post('email');
            if($search_name==''){
                $search =array('email'=>$search_email);                
                $search2 = array();
            }else{
                $search = array('name'=>$search_name);
                $search2 =array();                
            }
        }
        //For Table pagaination
        $rowperpage = 10;        
        if($rowno != 0){  $rowno = ($rowno-1) * $rowperpage;  } 
        $allcount  = $this->AuthModel->getrecordCount($search,$search2,'users',array('user_type'=>0));
        $customers = $this->AuthModel->getData($rowno,$rowperpage,$search,$search2,'users',array('user_type'=>0));
        //print_r($this->db->last_query());die();
        $config = $this->AuthModel->tableConfig();
        $config['base_url'] = site_url().'/Home/point_history';   
        //$config['use_page_numbers'] = TRUE;     
        $config['total_rows'] = $allcount;
        $config['per_page'] = $rowperpage;
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();        
        $data['row'] = $rowno;
        $data['search_name'] = $search_name;
        $data['search_email'] = $search_email;
        //End Table pagaination            
        
        if(!empty($customers))
        {
            $data['userlist']=$customers;            
            $this->load->view('booking_point_history',$data);
        }
        else
        {
            $data["error"] =1;
            $data["message"] = 'No record found';
            $data["userlist"]=$customers;
            $this->load->view('booking_point_history',$data);
        }
    }
    public function customer_point_history($customer_id)
    {   
        if($customer_id!=''){
            $bookings = $this->AuthModel->getMultipleRecord('booking',array('customer_id'=>$customer_id,'booking_status'=>4),'');
            //print_r($bookings);die();
            if(!empty($bookings))
            {
                $data['customer_id'] =$customer_id;
                $data['userlist']=$bookings;            
                $this->load->view('customer_point_history',$data);
            }
            else
            {
                $data["error"] =1;
                $data["message"] = 'No record found';
                $data['customer_id'] =$customer_id;
                $data["userlist"]=$bookings;
                $this->load->view('customer_point_history',$data);
            }
        } 
        else{
            redirect('Home');
        }
    }

    public function update_booking_point(){   //ajax use to update booking point of customer
        if(isset($_POST['booking_id'])){            
            extract($_POST);
            //print_r($_POST);die();
            if($this->AuthModel->updateRecord(array('booking_id'=>$booking_id),'booking',array('customer_trip_score'=>$point))){
                echo 'Point has been updated successfully.';                
            }else{
                echo 'Oops! something went wrong, Please try again';
            }
        }
    }

    public function get_feedback($user_id,$page){
        if($user_id!=''){
            $where = '(receiver_id='.$user_id.' or giver_id='.$user_id.')';          
            $resData = $this->AuthModel->getMultipleRecord('review',$where,'review_id DESC');
            if(!empty($resData)){                   
                $response = array("list"=>$resData,'page'=>$page);
                $this->load->view('rating_feedback',$response);
            }
            else{
                $response = array("error"=>1,"message"=>"No Review found",'page'=>$page,"list"=>$resData);
                $this->load->view('rating_feedback',$response);
            }
        }
        else{
            redirect('Home');
        }        
    }

    public function referral_setting(){
        $settings = $this->AuthModel->getMultipleRecord('referral_setting',array(),'');
        if(!empty($settings)){
            $response = array('setting'=>$settings);
            $this->load->view('referral_setting',$response);
        }
        else{
            $response = array("error"=>1,"message"=>"No record found",'setting'=>$settings);
            $this->load->view('referral_setting',$response);
        }
    }

    public function add_referral_setting(){
        if(isset($_POST['submit'])){
            extract($_POST);
            $checkdata     = array("country"=>$country,"user_type"=>$user_type);
            $checkCity  = $this->AuthModel->checkRows('referral_setting',$checkdata); 
            if($checkCity>0)
            {
                $response = array('error'=>1,"message"=>'Setting has already exist for '.$country);
                $this->load->view('add_referral_setting',$response);
            }
            else{
                 //print_r($_POST);die();
                $data = array(
                        "user_type"  =>$user_type,   
                        "country_id" =>$country_id,
                        "country"    =>$country,
                        "currency"   =>$currency,
                        "time_zone"  =>$time_zone,
                        "amount_to_frnd" =>$amount_to_frnd,
                        "bonus_to_referral" =>$bonus_to_referral,
                        "min_ride" =>$min_ride,
                        "within_days" =>$within_days,
                        "description"=>$description

                    );
                if($this->AuthModel->singleInsert('referral_setting',$data)){
                    $response = array("success"=>1,"message"=>"Record has been saved successfully");
                    $this->load->view('add_referral_setting',$response);
                }
                else{
                    $response = array("error"=>1,"message"=>"Oops! Error occur,Record is not saved");
                    $this->load->view('add_referral_setting',$response);
                }
            }           
        }else{            
            $this->load->view('add_referral_setting');
        }
    }

    public function update_referral($id){
        if(!empty($id)){
            $setting = $this->AuthModel->getSingleRecord('referral_setting',array('referral_setting_id'=>$id));
            if(isset($_POST['submit'])){
                extract($_POST);
                //print_r($_POST);die();
                $checkdata     = array("country"=>$country,"user_type"=>$user_type);
                $checkCountry     = $this->AuthModel->checkRows('referral_setting',$checkdata); 
                if($checkCountry>0 && $country!=$setting->country)
                {
                    $response = array('error'=>1,"message"=>'Setting has already exist for '.$country,"setting"=>$setting);
                    $this->load->view('add_referral_setting',$response);
                }
                else{
                    $data = array(
                            "user_type"  =>$user_type,
                            "country_id" =>$country_id,
                            "country"    =>$country,
                            "currency"   =>$currency,
                            "time_zone"  =>$time_zone,
                            "amount_to_frnd" =>$amount_to_frnd,
                            "bonus_to_referral" =>$bonus_to_referral,
                            "min_ride"    =>$min_ride,
                            "within_days" =>$within_days,
                            "description" =>$description
                        );
                    if($this->AuthModel->updateRecord(array('referral_setting_id'=>$id),'referral_setting',$data)){
                        $setting = $this->AuthModel->getSingleRecord('referral_setting',array('referral_setting_id'=>$id));
                        $response = array("success"=>1,"message"=>"Record has been updateed successful","setting"=>$setting);
                        $this->load->view('update_referral_setting',$response);
                    }
                    else{
                        $response = array("error"=>1,"message"=>"Oops! Error occur,Record is not update","setting"=>$setting);
                        $this->load->view('update_referral_setting',$response);
                    }
                }
            }
            else{      
                $response = array("setting"=>$setting);      
                $this->load->view('update_referral_setting',$response);
            }
        }
        else{
            redirect('Home/');
        }
    }
    public function delete_referral($id)
    {
        if($id!='')
        {             
            if($this->AuthModel->delete_record('referral_setting',array('referral_setting_id'=>$id)))
            {
                echo '<script>alert("Record has been successfully removed");
                window.location.href="'.site_url('Home/referral_setting').'";
                </script>';
            }
            else
            {
                echo '<script>alert("Record is not removed, Please try again");
                window.location.href="'.site_url('Home/referral_setting').'";
                </script>';
            }
        }
        else
        {
            redirect(site_url('Home/referral_setting'));
        }
    }

    public function ride_promocode(){
        $promocode = $this->AuthModel->getMultipleRecord('promocode',array(),'promo_id DESC');
        if(!empty($promocode)){
            $response = array('code'=>$promocode);
            $this->load->view('ride_promocode',$response);
        }
        else{
            $response = array("error"=>1,"message"=>"No record found",'code'=>$promocode);
            $this->load->view('ride_promocode',$response);
        }
    }

    public function add_ride_promocode(){
        if(isset($_POST['submit'])){
            extract($_POST);
            //print_r($_POST);die;
            $checkdata  = array("country"=>$country,"promocode"=>$promocode);
            $checkCity  = $this->AuthModel->checkRows('promocode',$checkdata); 
            if($checkCity>0)
            {
                $response = array('error'=>1,"message"=>'This promocode has already in used');
                $this->load->view('add_referral_setting',$response);
            }
            else{
                $imagename        = 'default.png';            
                if(isset($_FILES['image']) && $_FILES['image']['name']!='')
                {
                    $folder_name = 'promo_images';
                    $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);               
                }
                $data = array(
                    "country"       =>$country,
                    "currency"      =>$currency,
                    "heading"       =>$heading,
                    "description"   =>$description,
                    "promocode"     =>$promocode,
                    "rate_type"     =>$rate_type,
                    "rate"          =>$rate,
                    "max_amount"    =>$max_amount,
                    "min_trip_amount"=>$min_trip_amount,
                    "start_date"    =>$stdate,
                    "start_string"  =>strtotime($stdate),
                    "end_date"      =>$endate,
                    "end_string"    =>strtotime($endate),
                    "promo_image"   =>$imagename,
                    "user_limit"    =>$user_limit,
                    "max_time_use"  =>$max_time_use,
                    "attention"     =>$attention,
                    "promo_type"    =>$promo_type,
                    );
                //print_r($data);die();
                if($this->AuthModel->singleInsert('promocode',$data)){
                    if($attention=='All'){
                        $users = $this->AuthModel->getMultipleRecord('users',array('user_type'=>0,'nationality'=>$country),'');
                        if(!empty($users)){
                            foreach ($users as $u => $v) {
                                $data = array('user_id'=>$v->id,'subject'=>'Promotion Offer','message'=>$heading,'notification_at'=>date('d-m-Y h:i:s'));
                                $this->AuthModel->singleInsert('notifications',$data);
                            }
                        }
                    }
                    $response = array('success'=>1,"message"=>"Record has been successfully saved");
                    $this->load->view('add_ride_promocode',$response);
                }else{
                    $response = array('error'=>1,"message"=>"Opps! Record is not saved. Please try again.");
                    $this->load->view('add_ride_promocode',$response);
                }
            }           
        }
        else{            
            $this->load->view('add_ride_promocode');
        }
    }

    public function changePromoStatus()   //Ajax use to Active or Deactive promocode
    {
        $promo_id = $_POST['promo_id'];
        $status   = $_POST['status'];
        if($this->AuthModel->updateRecord(array('promo_id'=>$promo_id),'promocode',array('status'=>$status)))
        {
            echo 'Status has been successfully changed';
        }
        else
        {
            echo 'Oops! Something went wrong, Please try again';
        }
    }

    public function update_promocode($id){
        if(!empty($id)){
            $code = $this->AuthModel->getSingleRecord('promocode',array('promo_id'=>$id));
            if(isset($_POST['submit'])){
                extract($_POST); 
                $checkdata  = array("country"=>$country,"promocode"=>$promocode);
                $checkCity  = $this->AuthModel->checkRows('promocode',$checkdata); 
                if($checkCity>0 && $code->country!=$country && $code->prmocode!=$promocode)
                {
                    $response = array('error'=>1,"message"=>'This promocode has already in used');
                    $this->load->view('add_referral_setting',$response);
                }
                else{
                    $imagename = $code->promo_image;                            
                    if(isset($_FILES['image']) && $_FILES['image']['name']!='')
                    {
                        $folder_name = 'promo_images';
                        $imagename   = $this->AuthModel->imageUpload($_FILES['image'],$folder_name);               
                    }              
                    $data = array(
                        "country"       =>$country,
                        "heading"       =>$heading,
                        "description"   =>$description,
                        "promocode"     =>$promocode,
                        "rate_type"     =>$rate_type,
                        "rate"          =>$rate,
                        "max_amount"    =>$max_amount,
                        "min_trip_amount"=>$min_trip_amount,
                        "start_date"    =>$stdate,
                        "start_string"  =>strtotime($stdate),
                        "end_date"      =>$endate,
                        "end_string"    =>strtotime($endate),
                        "promo_image"   =>$imagename,
                        "user_limit"    =>$user_limit,
                        "max_time_use"  =>$max_time_use,
                        "attention"     =>$attention,
                        "promo_type"    =>$promo_type,
                    );
                    if($endate>date('m/d/Y')){
                        $data['status']='Active';
                    }
                    
                    if($this->AuthModel->updateRecord(array('promo_id'=>$id),'promocode',$data)){
                        if($attention=='All' && $code->attention!=$attention){
                            $users = $this->AuthModel->getMultipleRecord('users',array('user_type'=>0,'nationality'=>$country),'');
                            if(!empty($users)){
                                foreach ($users as $u => $v) {
                                    $data = array('user_id'=>$v->id,'subject'=>'Promotion Offer','message'=>$heading,'notification_at'=>date('d-m-Y h:i:s'));
                                    $this->AuthModel->singleInsert('notifications',$data);
                                }
                            }
                        }
                        $code = $this->AuthModel->getSingleRecord('promocode',array('promo_id'=>$id));
                        $response = array("success"=>1,"message"=>"Record has been updateed successful","code"=>$code);
                        $this->load->view('update_ride_promocode',$response);
                    }
                    else{
                        $response = array("error"=>1,"message"=>"Oops! Error occur, Record is not update","code"=>$code);
                        $this->load->view('update_ride_promocode',$response);
                    }
                }
            }
            else{      
                $response = array("code"=>$code);      
                $this->load->view('update_ride_promocode',$response);
            }
        }
        else{
            redirect('Home/');
        }
    }

    public function delete_promo($id)
    {
        if($id!='')
        {             
            if($this->AuthModel->delete_record('promocode',array('promo_id'=>$id)))
            {
                echo '<script>alert("Record has been successfully removed");
                window.location.href="'.site_url('Home/ride_promocode').'";
                </script>';
            }
            else
            {
                echo '<script>alert("Record is not removed, Please try again");
                window.location.href="'.site_url('Home/ride_promocode').'";
                </script>';
            }
        }
        else
        {
            redirect(site_url('Home/referral_setting'));
        }
    }

    public function promo_users($promo_id,$country){
        if($promo_id!=''){
            $user = $this->AuthModel->getMultipleRecord('promo_users',array('promo_id'=>$promo_id),'');
            if(!empty($user)){
                foreach ($user as $p => $l) {
                    $pre_ids[]=$l->user_id;
                }
                $users = $this->AuthModel->getWhereInRecord('users','id',$pre_ids);
                $response = array('page'=>'list','promo_id'=>$promo_id,'country'=>$country,'userlist'=>$users);
                $this->load->view('promo_users',$response);
            }
            else{
                $response = array('page'=>'list','promo_id'=>$promo_id,'country'=>$country,'error'=>1,'message'=>'You have not share this promocode with any passenger','userlist'=>$user);
                $this->load->view('promo_users',$response);
            }
        }else{
            redirect(site_url('Home/referral_setting'));
        }
    }

    /*public function promo_users_list($promo_id,$country){
        $pre_ids = array(); $users ='';
        if(isset($_POST['submit']))
        {
            extract($_POST); 
            print_r($_POST);die();
            $data =''; $tusers = count($users);
            foreach ($users as $v) {
                $data[] = array(
                    "promo_id"=>$promo_id,
                    "user_id"=>$v,
                    );
            }            
            if($this->AuthModel->batchInsert('promo_users',$data))
            { 
                 $this->save_promo_notification($users,$promo_id);          
                echo '<script>alert("Promotion has been successfully shared with '.$tusers.' passengers.");
                    window.location.href="'.site_url('Home/promo_users_list/'.$promo_id.'/'.$country).'";
                    </script>';                
            }
            else{
                echo '<script>alert("Oops Something went wrong! Please try again");
                    window.location.href="'.site_url('Home/promo_users_list/'.$promo_id.'/'.$country).'";
                    </script>';
            }        
        }
        else{
            $promouser = $this->AuthModel->getMultipleRecord('promo_users',array('promo_id'=>$promo_id),'');
            if(!empty($promouser)){
                foreach ($promouser as $p => $l) {
                    $pre_ids[]=$l->user_id;
                }
                $users = $this->AuthModel->getnotWhereInRecord('users','id',$pre_ids,array('nationality'=>$country,'user_type'=>0,'blackList_status'=>'no'));                  
            }else{
                $users = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'user_type'=>0,'blackList_status'=>'no'),'');
            }
            if(!empty($users)){            
                $response = array('page'=>'add','promo_id'=>$promo_id,'country'=>$country,'userlist'=>$users);
                $this->load->view('promo_users',$response);
            }else{
                $response = array('page'=>'add','promo_id'=>$promo_id,'country'=>$country,'error'=>1,'message'=>'No passengers available or remain to share','userlist'=>$users);
                $this->load->view('promo_users',$response);
            }
        }
    }*/

    public function promo_users_list($promo_id,$country){
        $pre_ids = array(); $users ='';
        $promouser = $this->AuthModel->getMultipleRecord('promo_users',array('promo_id'=>$promo_id),'');
        if(!empty($promouser)){
            foreach ($promouser as $p => $l) {
                $pre_ids[]=$l->user_id;
            }
            $users = $this->AuthModel->getnotWhereInRecord('users','id',$pre_ids,array('nationality'=>$country,'user_type'=>0,'blackList_status'=>'no'));                  
        }else{
            $users = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'user_type'=>0,'blackList_status'=>'no'),'');
        }
        if(!empty($users)){            
            $response = array('page'=>'add','promo_id'=>$promo_id,'country'=>$country,'userlist'=>$users);
            $this->load->view('promo_users',$response);
        }else{
            $response = array('page'=>'add','promo_id'=>$promo_id,'country'=>$country,'error'=>1,'message'=>'No passengers available or remain to share','userlist'=>$users);
            $this->load->view('promo_users',$response);
        }        
    }
    
    public function add_promo_users(){       
        if(isset($_POST['submit'])){
            extract($_POST);
            $data =''; $tusers = count($users);
            foreach ($users as $v) {
                $data[] = array(
                    "promo_id"=>$promo_id,
                    "user_id"=>$v,
                    );
            }                  
            if($this->AuthModel->batchInsert('promo_users',$data))
            {   
                $this->save_promo_notification($users,$promo_id);                
                $response = array("success"=>1,"error"=>0,"message"=>"Promotion has been successfully shared");
                echo json_encode($response);
            }
            else{
                $response = array("success"=>0,"error"=>1,"message"=>"Opps! Something went wrong. Please try again");
                echo json_encode($response);   
            }
        }      
    }

    public function save_promo_notification($users,$promo_id){
        $promo = $this->AuthModel->getSingleRecord('promocode',array('promo_id'=>$promo_id),'');               
        foreach ($users as $v) {            
            $data = array('user_id'=>$v,'subject'=>'Promotion Offer','message'=>$promo->heading,'notification_at'=>date('d-m-Y h:i:s'));
            $this->AuthModel->singleInsert('notifications',$data);                    
        }
    }


    
    public function remove_promo_user()
    {
        if(isset($_POST['submit']))
        {
            extract($_POST);     
            $response = array("success"=>1,"error"=>0,"message"=>"Record has been successfully removed");               
            echo json_encode($response);die();
            //echo json_encode($_POST);
            if($this->AuthModel->delete_record('promo_users',array('promo_id'=>$promo_id,'user_id'=>$user_id)))
            {
                $response = array("success"=>1,"error"=>0,"message"=>"Record has been successfully removed");               
                echo json_encode($response);
            }
            else
            {
                $response = array("success"=>0,"error"=>1,"message"=>"Record is not removed, Please try again");               
                echo json_encode($response);                
            }
        }
        else{
            $response = array("success"=>0,"error"=>1,"message"=>"access denied");               
                echo json_encode($response);
        }        
    }

    public function add_redeem_post(){
        if(isset($_POST['submit'])){ 
            extract($_POST);
            //echo '<pre>';
            //print_r($_POST);die();
            $timeline = 'default.png';
            $preview  = 'default.png';
            if(isset($_FILES['timeline_photo']) && $_FILES['timeline_photo']['name']!=''){
                $folder_name = 'promo_images';
                $timeline    = $this->AuthModel->imageUpload($_FILES['timeline_photo'],$folder_name);
            }
            if(isset($_FILES['preview_photo']) && $_FILES['preview_photo']['name']!=''){
                $folder_name = 'promo_images';
                $preview     = $this->AuthModel->imageUpload($_FILES['preview_photo'],$folder_name);
            }
            $data  = array(
                'country' =>$country,
                'currency'=>$currency,
                'heading'=>$heading,
                'date_display'=>$date_display,
                'promocode'=>$promo_code,
                'rate_type'=>$rate_type,
                'rate' =>$promo_rate,
                'max_amount' =>$max_amount,
                'start_date'=>$promo_start,
                'start_string'=>strtotime($promo_start),
                'end_date'=>$promo_end,
                'end_string'=>strtotime($promo_end),
                'description'=>$description,
                'buttons'=>$buttons,
                'points'=>$points,
                'publish_type'=>$publish_type,
                'later_date'=>$later_date,
                'later_date_string'=>strtotime($later_date),
                'timeline_image'=>$timeline,
                'preview_image'=>$preview,
                'QR_scan'=>'Off',
                );
                if($publish_type!='Now'){
                    $data['status']='Deactive';                
                }
                else{
                    $data['publish_date'] = date('Y-m-d');
                }
            if($this->AuthModel->singleInsert('redeem_post',$data)){
                $this->save_post_notification($country,$heading);
                $response = array('success'=>1,'message'=>'Record has been saved successfully');
                $this->load->view('add_redeem_post',$response);
            }
            else{                
                $response = array('error'=>1,'message'=>'Oops! Error occured. Please try again');
                $this->load->view('add_redeem_post',$response);
            }
        }
        else{
            $this->load->view('add_redeem_post');   
        }
    }

    public function save_post_notification($country,$heading){
        $users = $this->AuthModel->getMultipleRecord('users',array('nationality'=>$country,'user_type'=>0),'');                       
        foreach ($users as $v) {            
            $data = array('user_id'=>$v->id,'subject'=>'Promotion Offer','message'=>$heading,'notification_at'=>date('d-m-Y h:i:s'));
            $this->AuthModel->singleInsert('notifications',$data);                    
        }
    }

    public function update_redeem_post($redeem_post_id){
        if(isset($redeem_post_id) && $redeem_post_id!=''){
            $post = $this->AuthModel->getSingleRecord('redeem_post',array('redeem_post_id'=>$redeem_post_id));
            if(isset($_POST['submit'])){
                extract($_POST);
                $timeline = $post->timeline_image;
                $preview  = $post->preview_image;
                if(isset($_FILES['timeline_photo']) && $_FILES['timeline_photo']['name']!=''){
                    $folder_name = 'promo_images';
                    $timeline    = $this->AuthModel->imageUpload($_FILES['timeline_photo'],$folder_name);
                }
                if(isset($_FILES['preview_photo']) && $_FILES['preview_photo']['name']!=''){
                    $folder_name = 'promo_images';
                    $preview     = $this->AuthModel->imageUpload($_FILES['preview_photo'],$folder_name);
                }
                $data  = array(                   
                    'heading'=>$heading,
                    'date_display'=>$date_display,
                    'promocode'=>$promo_code,
                    'rate_type'=>$rate_type,
                    'rate' =>$promo_rate,
                    'max_amount' =>$max_amount,
                    'start_date'=>$promo_start,
                    'start_string'=>strtotime($promo_start),
                    'end_date'=>$promo_end,
                    'end_string'=>strtotime($promo_end),
                    'description'=>$description,
                    'buttons'=>$buttons,
                    'points'=>$points,
                    'publish_type'=>$publish_type,
                    'later_date'=>$later_date,
                    'later_date_string'=>strtotime($later_date),
                    'timeline_image'=>$timeline,
                    'preview_image'=>$preview,                    
                    );
                    if($publish_type=='Now' && $post->publish_date=='0000-00-00'){                        
                        $data['publish_date'] = date('Y-m-d'); 
                        $this->save_post_notification($post->country,$heading);                         
                    }
                    if($publish_type!='Now'){
                        $data['status']='Deactive';                                                
                    }
                    else{
                        $data['status']='Active';                                                   
                    }
                    //echo '<pre>';
                    //print_r($data);die();                   
                if($this->AuthModel->updateRecord(array('redeem_post_id'=>$redeem_post_id),'redeem_post',$data)){
                    $post = $this->AuthModel->getSingleRecord('redeem_post',array('redeem_post_id'=>$redeem_post_id));
                    $response = array('success'=>1,'message'=>'Record has been udpated successfully','list'=>$post);
                    $this->load->view('update_redeem_post',$response);
                }
                else{
                    $response = array('success'=>1,'message'=>'Record is not udpate. Please try again','list'=>$post);
                    $this->load->view('update_redeem_post',$response);
                }                    
            }           
            else{
                $response = array('list'=>$post);
                $this->load->view('update_redeem_post',$response);
            }
        }
        else{            
            redirect(base_url());
        }
    }

    public function redeem_posts(){
        $redeem_post = $this->AuthModel->getMultipleRecord('redeem_post',array(),'redeem_post_id DESC');
        if(!empty($redeem_post)){             
            $response['code'] = $redeem_post;
            $this->load->view('redeem_posts',$response);
        }
        else{
            $response = array('error'=>1,'message'=>'No post found','code'=>$redeem_post);
            $this->load->view('redeem_posts',$response);
        }
    }


    public function ride_redeem_history(){                  
        $history = $this->AuthModel->getMultipleRecord('promocode_history',array(),'history_id DESC');
        if(!empty($history)){                
            $response = array('history'=>$history);
            $this->load->view('ride_redeem_history',$response);
        }
        else{
            $response = array('error'=>1,'message'=>'No history found','history'=>$history);
            $this->load->view('ride_redeem_history',$response);
        }        
    }

    public function point_redeem_history(){
        $history = $this->AuthModel->getMultipleRecord('redeem_history',array(),'history_id DESC');
        if(!empty($history)){                
            $response = array('history'=>$history);
            $this->load->view('point_redeem_history',$response);
        }
        else{
            $response = array('error'=>1,'message'=>'No history found','history'=>$history);
            $this->load->view('point_redeem_history',$response);
        } 
    }

    public function range_setting()
    {
        $this->load->view('NightSearchSetting');
    }
}