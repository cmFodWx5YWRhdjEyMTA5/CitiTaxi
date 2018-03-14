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
        if(isset($_POST['add']))
        {

        }
        else
        {
            $this->load->view('addVehicle_fair');
        }
    }
    


   

}