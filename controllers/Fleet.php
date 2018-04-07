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

    public function fleet_tracking()
    {       
        $this->load->view('fleetTracking');
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
        

    
}