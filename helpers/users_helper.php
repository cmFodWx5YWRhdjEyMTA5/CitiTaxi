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
    
    //get data from database  `tbl_users`.`user_fullname`
    $queryForUsername = $ci->db->query('SELECT dropoff FROM booking_dropoffs WHERE booking_id='.$uId);
    $resultForUsername = $queryForUsername->result();  // this returns an object of all results
    //print_r($resultForUsername);die;
    //$row = $resultForUsername->dropoff; 
    foreach ($resultForUsername as $value) {
       $responce1['dropoff']= $value->dropoff;

      $responce[]=$responce1;
     }
     // print_r($responce);
      return   $responce;  

   // if($value->dropoff){
    //    $result = $value->dropoff;
   //     return $result;
  //  }else{
   //     return false;
    //}
    
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
  //print_r($resultForUsername);
  //$row = $resultForUsername[0]->dropoff; 
                            
  //print_r($row);
          return $resultForUsername;  

   // if($value->dropoff){
    //    $result = $value->dropoff;
   //     return $result;
  //  }else{
   //     return false;
    //}
    
}











if ( ! function_exists('getUserDetailsBySocialId')){
   function getUserDetailsBySocialId($social_id){
       //get main CodeIgniter object
       $ci =& get_instance();
       
       //load databse library
       $ci->load->database();
       
       //get data from database
       $query = $ci->db->get_where('tbl_users',array('social_id'=>$social_id));
       
       if($query->num_rows() > 0){
           $result = $query->row_array();
           return $result;
       }else{
           return false;
       }
   }
}

if ( ! function_exists('getUserPicsByUserId')){
   function getUserPicsByUserId($user_id){
      
        $emptyArray=array();
        //get main CodeIgniter object
	    $ci =& get_instance();
	    
	    //load databse library
	    $ci->load->database();
	    
	    //get data from database  `tbl_users`.`user_fullname`
	    $query = $ci->db->query('SELECT picId,pic_url FROM tbl_user_pics WHERE user_id='.$user_id);
	    //$query = $ci->db->query('SELECT user_profile_pic,user_pic_2,user_pic_3,user_pic_4,user_pic_5 FROM tbl_users WHERE user_id='.$user_id);
		$result = $query->result_array();  // this returns an object of all results
		// print_r(array_values($result));die;
		
	    if(!empty($result)){
	        return $result;
	    }else{
	        return $emptyArray;
	    }
   }
}

function getUserNameById($uId){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $queryForUsername = $ci->db->query('SELECT user_fullname FROM tbl_users WHERE user_id='.$uId);
	$resultForUsername = $queryForUsername->result();  // this returns an object of all results
	$row = $resultForUsername[0]; 
    
    if($row->user_fullname){
        $result = $row->user_fullname;
        return $result;
    }else{
        return false;
    }
    
}

function getTagNameById($tag_id){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_tags`.`user_fullname`
    $queryForUsername = $ci->db->query('SELECT tag_name FROM tbl_tags WHERE tag_id='.$tag_id);
	$resultForUsername = $queryForUsername->result();  // this returns an object of all results
	$row = $resultForUsername[0]; 
    
    if($row->tag_name){
        $result = $row->tag_name;
        return $result;
    }else{
        return '';
    }
    
}

function getCoinsByUserId($uId){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $queryForUserCoins = $ci->db->query('SELECT `coins` FROM `tbl_users` WHERE `user_id`='.$uId);
	$resultForUserCoins = $queryForUserCoins->result();  // this returns an object of all results
	$row = $resultForUserCoins[0]; 
    
    if($row->coins){
        $result = $row->coins;
        return $result;
    }else{
        return '0';
    }
    
}

function getCheckInApiKey(){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $queryForUsername = $ci->db->query('SELECT `api_key` FROM `tbl_google_api` WHERE `api_type`=1');
	$resultForUsername = $queryForUsername->result();  // this returns an object of all results
	$row = $resultForUsername[0]; 
    
    if($row->api_key){
        $result = $row->api_key;
        return $result;
    }else{
        return false;
    }
    
}


function getRegIdById($uId){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $queryForUsername = $ci->db->query('SELECT user_fullname FROM tbl_users WHERE user_id='.$uId);
	$resultForUsername = $queryForUsername->result();  // this returns an object of all results
	$row = $resultForUsername[0]; 
    
    if($row->user_fullname){
        $result = $row->user_fullname;
        return $result;
    }else{
        return false;
    }
    
}

function isUserVip($user_id){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $query= $ci->db->query('SELECT `is_vip` FROM tbl_users WHERE user_id='.$user_id);
	$result= $query->result();  // this returns an object of all results
	$row = $result[0]; 
    
    if($row->is_vip>0){
    	return true;
    }else{
        return false;
    }
    
}

function getDefaultFilterByUserId($user_id){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    $query= $ci->db->query('SELECT * FROM tbl_users WHERE user_id='.$user_id);
	//$result= $query->result();  // this returns an object of all results
	
    
    if($query->num_rows() > 0){
        $result = $query->row_array();
        return $result;
    }else{
        return false;
    }
    
}


function getSkillNameById($sId){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database
    $query = $ci->db->get_where('skills',array('skillId'=>$sId));
    
    if($query->num_rows() > 0){
        $result = $query->row_array();
        return $result;
    }else{
        return false;
    }
    
}



function getUserGiftsById($user_id,$to_user_id){
    $emptyArray=array();
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_users`.`user_fullname`
    
    //$query= $ci->db->query("SELECT `gift_id` FROM  `tbl_send_gifts` WHERE  `user_id` = $user_id AND  `to_user_id` = $to_user_id ORDER BY `created_on` DESC ;");
    $query= $ci->db->query("SELECT *
							FROM `tbl_gifts`
							JOIN  `tbl_send_gifts`
							ON tbl_gifts.gift_id=tbl_send_gifts.gift_id 
							where `user_id`=$user_id AND `to_user_id`=$to_user_id
							order by `created_on` DESC");
	$input= $query->result();
	//print_r($input);
	if(!empty($input)){
		/*$output = array_map(function ($object) { return $object->gift_id; }, $input);
	    $giftIds= implode(', ', $output);
		//print_r($giftIds);die;
		//echo "SELECT * FROM `tbl_gifts` WHERE `gift_id` IN($giftIds)";die;
		$query1= $ci->db->query("SELECT * FROM `tbl_gifts` WHERE `gift_id` IN($giftIds) ");
		$giftsArray= $query1->result();
		//echo '<pre>';print_r($giftsArray);die;
		$a=array_reverse($giftsArray);*/
	    if(!empty($input)){
	        return $input;
	    }else{
	        return $emptyArray;
	    }
	}else{
		 return $emptyArray;
	}
	
    
}


function getgiftcategoryNameById($gId){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_gift`.`gift_category`
    $queryForGiftCategoryname = $ci->db->query('SELECT gift_category_name FROM  tbl_gift_category WHERE gift_category_id='.$gId);
	$resultForGiftCategoryname = $queryForGiftCategoryname->result();  // this returns an object of all results
	$row = $resultForGiftCategoryname[0]; 
    
    if($row->gift_category_name){
        $result = $row->gift_category_name;
        return $result;
    }else{
        return false;
    }
    
}

function getDateDefaultTimezone(){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_gift`.`gift_category`
    $queryForTime_zone_value = $ci->db->query('SELECT `time_zone_value` FROM `date_default_timezone`;');
	$resultForTime_zone_value = $queryForTime_zone_value->result();  // this returns an object of all results
	$row = $resultForTime_zone_value[0]; 
    
    if($row->time_zone_value){
        $result = $row->time_zone_value;
        return $result;
    }else{
        return false;
    }
    
}

function getUserPicNameByPicId($picId){
    
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_gift`.`gift_category`
    $query= $ci->db->query('SELECT `pic_url` FROM `tbl_user_pics` WHERE `picId`='.$picId);
	$result = $query->result();  // this returns an object of all results
	$row = $result[0]; 
    
    if($row->pic_url || $row->pic_url!=''){
        $result = $row->pic_url;
		$result1=explode('/',$result);
        $picName=$result1[5];
        return $picName;
    }else{
        return false;
    }
    
}

function checkPlaneDetailsByUserId(){
    $current_time=date('Y-m-d H:i:s',time());
    //get main CodeIgniter object
    $ci =& get_instance();
    
    //load databse library
    $ci->load->database();
    
    //get data from database  `tbl_gift`.`gift_category`
    //echo 'SELECT * FROM `tbl_vip_user_details`  WHERE `vip_user_end_date` <='."'$current_time'";die;
    $query= $ci->db->query('SELECT `user_id` FROM `tbl_vip_user_details`  WHERE `vip_user_end_date` <='."'$current_time'");
	$result1 = $query->result();  // this returns an object of all results

	foreach($result1 as $data1){
	
		$userIdArray[]=$data1->user_id;
	    $ci->db->query('DELETE FROM `tbl_vip_user_details` WHERE `user_id`='.$data1->user_id);
		$ci->db->query('UPDATE `tbl_users` SET `is_vip`=0 WHERE `user_id`='.$data1->user_id);
	}
	//$userIds=implode(',',$userIdArray);
	
	//UPDATE `tbl_users` SET `is_vip`='0' WHERE `user_id` IN($userIds) 
	
	//$query2= $ci->db->query('UPDATE `tbl_users` SET `is_vip`=0 WHERE `user_id` IN("'.$userIds.'")');
	//$result2 = $query2->result();  // this returns an object of all results
	//$query3= $ci->db->query('DELETE FROM `tbl_vip_user_details` WHERE `user_id` IN("'.$userIds.'")');
   
	return true;
		
	
    
}


