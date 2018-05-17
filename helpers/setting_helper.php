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
      $query = $ci->db->get_where('servicetype',array('status'=>'active'))->result();
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

if ( ! function_exists('getCountryName')){
   function getCountryName($countryid){
       //get main CodeIgniter object
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get_where('countries',array('id'=>$countryid))->row()->name;
      return $query;
   }
}

if ( ! function_exists('getCountryName')){
   function getCityName($cityid){
       //get main CodeIgniter object
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get_where('cities',array('id'=>$cityid))->row()->name;
      return $query;
   }
}

if ( ! function_exists('getallfleets')){
   function getallfleets(){
       //get main CodeIgniter object
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get('fleets')->result();
      return $query;
   }
}

if ( ! function_exists('getfleetDetail')){
   function getfleetDetail($fleetid){
       //get main CodeIgniter object
      //return $fleetid;die();
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get_where('fleets',array('fleet_id'=>$fleetid))->row();
      return $query;
   }
}

if ( ! function_exists('getSingleDetail')){
   function getSingleDetail($tablename,$where){
       //get main CodeIgniter object
      //return $fleetid;die();
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get_where($tablename,$where)->row();
      return $query;
   }
}
if ( ! function_exists('getSingleDetail')){
   function getMultipleDetail($tablename,$where){
       //get main CodeIgniter object
      //return $fleetid;die();
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get_where($tablename,$where)->result();
      return $query;
   }
}

if ( ! function_exists('getCount')){
   function getCount($tablename,$where){
       //get main CodeIgniter object
      //return $fleetid;die();
      $ci =& get_instance();       
      $ci->load->database();
      $query = $ci->db->get_where($tablename,$where)->num_rows();
      return $query;
   }
}
if ( ! function_exists('get_rating')){
    function get_rating($user_id)
    {
        $ci =& get_instance();       
        $ci->load->database();
        $rr =$ci->db->get_where('review',array('receiver_id'=>$user_id))->result();       
        $NofRating   = count($rr);        
        $userRating  = 0;
        $countRating = 0;
        if(!empty($rr))
        {
            foreach ($rr as $ratecount) {
                $rate= $ratecount->rating;
                $countRating = $countRating+$rate;
            }            
            $n =$countRating/$NofRating;
            $userRating = round($n,2);
            return $userRating;
        }
        else
        {
            return $userRating;
        }
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