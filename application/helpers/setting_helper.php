<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('tz_list')){
   function tz_list($pres_id){
       //get main CodeIgniter object
      $ci =& get_instance();       
      $zones_array = array();
      $timestamp = time();
      foreach(timezone_identifiers_list() as $key => $zone) {
        date_default_timezone_set($zone);
        $zones_array[$key]['zone'] = $zone;
        $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
      }
      return $zones_array;
   }
}

if ( ! function_exists('servicetypes')){
   function servicetypes(){
       //get main CodeIgniter object
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get('servicetype')->result();
      return $query;
   }
}

if ( ! function_exists('countryies')){
   function countryies(){
       //get main CodeIgniter object
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get('countries')->result();
      return $query;
   }
}


/*if ( ! function_exists('get_userPharmacy')){
   function get_userPharmacy($pharmacy_id){
       //get main CodeIgniter object
       $ci =& get_instance();
       
       //load databse library
       $ci->load->database();
       
       //get data from database
       $query = $ci->db->get_where('user_pharmacy',array('id'=>$pharmacy_id));
       return $query->row();
   }
}*/