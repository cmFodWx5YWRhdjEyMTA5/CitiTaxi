<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('getUserDetailsById')){
   function getUserDetailsById($id){
       //get main CodeIgniter object
       $ci =& get_instance();       
       //load databse library
       $ci->load->database();       
       //get data from database
       $query = $ci->db->get_where('users',array('id'=>$id));       
       if($query->num_rows() > 0){
           $result = $query->row_array();
           $name=$result['name'];
           return $name;
       }else{
           return false;
       }
   }
}

if ( ! function_exists('getFleetDetailsById')){
   function getFleetDetailsById($id){
       //get main CodeIgniter object
       $ci =& get_instance();       
       //load databse library
       $ci->load->database();       
       //get data from database
       $query = $ci->db->get_where('users',array('id'=>$id));       
       if($query->num_rows() > 0){
           $result = $query->row_array();
           $fleet_id=$result['fleet_id'];
           $query1 = $ci->db->get_where('fleets',array('fleet_id'=>$fleet_id));
           $result1 = $query1->row_array(); 
           $company=$result1['fleet_company'];
           return $company;
       }else{
           return false;
       }
   }
}

if ( ! function_exists('getVechicleDetailsById')){
   function getVechicleDetailsById($id){
       //get main CodeIgniter object
       $ci =& get_instance();       
       //load databse library
       $ci->load->database();       
       //get data from database
       $query = $ci->db->get_where('vechile_details',array('driver_id'=>$id));       
      if($query->num_rows() > 0){
           $result = $query->row_array();
           $name=$result['number_plate'];
           return $name;
       }else{
           return false;
       }
   }
}


function booking_dropoffs($uId){
    $responce=array();
    $responce1=array();
    //get main CodeIgniter object
    $ci =& get_instance();    
    //load databse library
    $ci->load->database();        
    $queryForUsername = $ci->db->query('SELECT dropoff FROM booking_dropoffs WHERE booking_id='.$uId);
    $resultForUsername = $queryForUsername->result();  // this returns an object of all results    
    foreach ($resultForUsername as $value) {
       $responce1['dropoff']= $value->dropoff;
      $responce[]=$responce1;
     }  
    return   $responce;      
}

function booking_drop($uId){
    $responce=array();
    $responce1=array();
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $queryForUsername = $ci->db->query('SELECT dropoff FROM booking_dropoffs WHERE booking_id='.$uId);
    $resultForUsername = $queryForUsername->result();  // this returns an object of all results  
    return $resultForUsername;  
}














