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
        $this->load->view('Dashboard');
    }

    public function analytics()
    {
        $this->load->view('analytics');
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
        if($this->input->post('submit') != NULL ){
            $search_text = $this->input->post('search');
            $this->session->set_userdata(array("search"=>$search_text));
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
        $users_record = $this->DummyModel->getData($rowno,$rowperpage,$search_text,'users',array()); 
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
                $data = array('servicename'=>$service_name,'selected_image'=>$selectimage,'unselected_image'=>$unselectimage);
            
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
                        $data = array('servicename'=>$service_name,'selected_image'=>$selectimage,'unselected_image'=>$unselectimage);
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
        $where      = "((booking_status!=4 or booking_status!=3 or booking_status!=7) and booking_type='later')";
        $customers = $this->AuthModel->getMultipleRecord('booking',$where,$orderby);
        //print_r($customers);die();
        if(!empty($customers))
        {
            $data['userlist']=$customers;            
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

   

    public function check()
    {
        echo 'hii';
    }

    public function range_setting()
    {
        $this->load->view('NightSearchSetting');
    }
}