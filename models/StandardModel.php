<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
ob_start();
class StandardModel extends CI_Model
{ 
    
    public function __construct()
    {
        parent::__construct();
        //load database library
        $this->load->database(); 
        $this->load->library('email');
        
        /*if($this->session->userdata('ses_time_zone'))
        {
            date_default_timezone_set($this->session->userdata('ses_time_zone'));
        }*/
    }
    
    public function getcategory_all(){
        
        return $this->db->get('category')->result();
    }

//------------- Select all data from table or single data-----------------------
    function select_driver($tbl_name,$where=false)
    {
        $this->db->select("*");
        $this->db->from($tbl_name);
        
        if($where!='')
        {
            $this->db->where($where);
        }
        $query = $this->db->get();// execute
         // $abhi=$query->result_array();
          //print_r($abhi);
        if($query->num_rows()>0)
        {
            return $query->result();// select All value from table
            //return $query->row;// select single row from table
            //return true;
        }
        else
        {
            return false;
        }
    }

    function select_queryfromdatetotwodate($tbl_name,$where=false,$fromdate,$todate)
    {
        $this->db->select("*");
        $this->db->from($tbl_name);     
        if($where!='')
        {
            $this->db->where($where);
            $this->db->where('booking_at_string >=', strtotime($fromdate));
            $this->db->where('booking_at_string <=', strtotime($todate));
        }
        $query = $this->db->get();// execute
      // echo $this->db->last_query();die;
        if($query->num_rows()>0)
        {
            return $query->result();// select All value from table
            //return $query->row;// select single row from table
            //return true;
        }
        else
        {
            return false;
        }
    }

//------------------------Fuction For Insert Data in to Table------------------ 
    function insert_query($tbl_name,$data)
    {
        
        $this->db->insert($tbl_name,$data);
        return $this->db->insert_id();// mysql_insert_id();
    }

    function insert_query_batch($tbl_name,$data)
    {
        
        $this->db->insert_batch($tbl_name,$data);
        return $this->db->insert_id();// mysql_insert_id();
    }

function selectdriverdetail($tbl_name,$driver_id)
    {   
        $this->db->select("*");
        $this->db->from('subcategory');
        
        $where=array('driver_detail.driver_id'=>$driver_id);   
        $this->db->where($where);
        $this->db->join('driver_detail','subcategory.sub_cat_id = driver_detail.subcategory_id');
        $this->db->order_by('driver_detail.driver_det_id','desc');
       $query = $this->db->get();// execute
    //  echo $this->db->last_query();die;
        $driver=$query->result_array();
        return $driver;
        /* $respData= array();
         
         $AllUsers= array();
        foreach($driver as $driverdetail){
        //  print_r($driverdetail);die;
            $subcategory_id=$driverdetail['subcategory_id'];
             $where=array('subcat_id'=>$subcategory_id);;
             $respData['taxesandfees']        = $driverdetail['taxesandfees'];
             $respData['additionalitemtotal'] = $driverdetail['additionalitemtotal'];
             $respData['deliverycharge']      = $driverdetail['deliverycharge'];
             $respData['carsseat']            = $driverdetail['carsseat'];
             $respData['promocode']           = $driverdetail['promocode'];
             $respData['car_price']           = $driverdetail['car_price'];
             $respData['carname']             = $driverdetail['carname'];
             $respData['bookamount']          = $driverdetail['bookamount'];
             $respData['sub_image']           = $driverdetail['sub_image'];
             $respData['driver_phone']        = $driverdetail['driver_phone'];
            $respData['driver_name']          = $driverdetail['driver_name'];
             $respData['pickupaddress']       = $driverdetail['pickupaddress'];
             $respData['deliveraddress']      = $driverdetail['deliveraddress'];
             $respData['deliverylat']         = $driverdetail['deliverylat'];
             $respData['deliverylong']        = $driverdetail['deliverylong'];
             $respData['pickuptime']          = $driverdetail['pickuptime'];
             $respData['delivertime']         = $driverdetail['delivertime'];
             $respData['deliverstatus']       = $driverdetail['deliverstatus'];
              $respData['additionalitem']=$this->select_query('additional_item',$where);
            
            //print_r($additionalitem);die;
                  $AllUsers[]=$respData;
        }
           // print_r($asds);die;
         //echo $this->db->last_query();die;
    //  if($query->num_rows()>0)
///     {
    //      return $query->result();
//      }
        return $respData;*/
    }   

public function getresult($sql) {

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return FALSE;
        }
    }
    function update_data($tbl_name,$up_data,$where)
    {
        $this->db->where($where);
         $this->db->update($tbl_name,$up_data);
        return $this->db->affected_rows() > 0;
    }


//------------- Select all data from table or single data-----------------------
    function select_query($tbl_name,$where=false)
    {
        $this->db->select("*");
        $this->db->from($tbl_name);
        
        if($where!='')
        {
            $this->db->where($where);
        }
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return $query->result();// select All value from table
            //return $query->row;// select single row from table
            //return true;
        }
        else
        {
            return false;
        }
    }
    
    function select_queryfordesc($tbl_name,$where=false)
    {
        $this->db->select("*");
        $this->db->from($tbl_name);
        
        if($where!='')
        {
            $this->db->where($where);
        }
        $this->db->order_by('driver_detail.driver_det_id','desc');
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return $query->result();// select All value from table
            //return $query->row;// select single row from table
            //return true;
        }
        else
        {
            return false;
        }
    }
    //$this->db->order_by('Notification.notification_id','desc');
    function select_additonal_item($tbl_name,$where=false)
    {   $response=array();
        $this->db->select("*");
        $this->db->from($tbl_name);
        
        if($where!='')
        {
            $this->db->where($where);
        }
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return $query->result_array();// select All value from table
            //return $query->row;// select single row from table
            //return true;
        }
        else
        {
            return $response;
        }
    }
        
//-------------- Select single data with where condition from table-------------
    function select_rowwhere_query($tbl_name,$where=false)
    {
        $this->db->select("*");
        $this->db->from($tbl_name);
        
        if($where!='')
        {
            $this->db->where($where);// array('f1'=>$f1,'f2'=>$f2)
        }
    
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return $query->row();// select single row from table
        }
        
        else
        {
            return false;
        }
    }
    function select_rowwhere($tbl_name,$where=false)
    {
        $this->db->select("taxandfees");
        $this->db->from($tbl_name);
        
        if($where!='')
        {
            $this->db->where($where);// array('f1'=>$f1,'f2'=>$f2)
        }
    
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return $query->result_array();// select single row from table
        }
        
        else
        {
            return false;
        }
    }
//-----------------------------select function with count,where,join,limit,offset,orderby,like----------------------------------------------------------    
           
    function select($tbl_name_enc,$select='*',$record_type,$count_status,$where=false,$offset=false,$limit=false,$orderby=false,$order=false,$like=false,$join=false)
    {
                
        $this->db->select($select); //seslect all record
        $this->db->from($tbl_name_enc); //define record from which table
        
        if($join)
        {
            foreach($join as $key_j=>$value_j)
            {
                $this->db->join($key_j,$value_j);  //condition
            }
            
        }
        
        if($where)
        {
            $this->db->where($where); //condition
        }
        if($like)
        {
            foreach($like as $key=>$value)
            {
                $this->db->like($key,$value);  //condition
            }
        }
            
        if($order&&$orderby)
        {
            $this->db->order_by($orderby,$order);
        }
        
        if($count_status=='f'&&$limit)
        {
            $this->db->limit($limit,$offset);// define limit for redord on page
        }
            
        
        $query = $this->db->get();
        
        $num_rows=$query->num_rows();
        
        if($count_status=='f')
        {
            
            if($num_rows>0)
            {
                if($record_type=='s')
                {
                    
                    if(!empty($like_name)&&!empty($like_value))
                    {
                        $data=$query->row();
                        $data=$this->filter_search($like_name,$like_value,$data,$record_type);
                    }
                    else
                    {
                        return $query->row();
                    }
                    
                }
                else
                {
                    $data=$query->result();
                    return $data; 
                    
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            if(!empty($like_name)&&!empty($like_value))
            {
                $data=$query->result();
                return count($this->filter_search($like_name,$like_value,$data,$record_type));
            }
            else
            {
                return $num_rows;
            }
            
        }
        
        
    } 


//----------------------------------------------------------------------------------------------------------------------------------------------------
           
    
//---------------Funtion for Update Data from Table-----------------------------    
    function update_query($tbl_name,$up_data,$where)
    {
        $this->db->where($where);
        return $this->db->update($tbl_name,$up_data);
    }
//---------------Funtion for Delete Data from Table-----------------------------    
    function delete_query($tbl_name,$where)
    {
        $this->db->where($where);
        $this->db->delete($tbl_name);
        return true;
    }
//---------------Funtion for Count Number of Data in Table--------------------------    
    function count_data($tabl_name,$where=false)
    {
        $this->db->select("*");
        $this->db->from($tabl_name);
        if($where!='')
        {
            $this->db->where($where);
        }
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return count($query->result());
        }
    }
    //--------------------------get blocked Users list---------------------------   
    function select_blocked_users($offset,$limit)
    {   
        $this->db->select('DISTINCT(tbl_user_like.user_to_id),tbl_users.user_id,user_fullname,user_email,user_country,is_vip,coins,user_profile_pic');
        $this->db->from('tbl_users');
        
        $where=array('tbl_user_like.like_status'=>2);   
        $this->db->where($where);
        $this->db->join('tbl_user_like', 'tbl_user_like.user_to_id = tbl_users.user_id');
        $this->db->order_by('tbl_users.user_id','DESC');
        $this->db->limit($limit,$offset);
        $query = $this->db->get();// execute
        //echo $this->db->last_query();die;
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }
//--------------------------Function For Pagingation--------------------------- 
    function paging_data($tabl_name,$where=false,$orderby=false,$order=false,$offset=0,$limit=3)
    {
        $this->db->select("*");
        $this->db->from($tabl_name);
        if($where!='')
        {
            $this->db->where($where);
        }
        if($order&&$orderby)
        {
            $this->db->order_by($orderby,$order);
        }
        
        
        $this->db->limit($limit,$offset);
        $query = $this->db->get();// execute
        if($query->num_rows()>0)
        {
            return $query->result();
        }
    }
//-------------------------Function For Mysql Injection------------------------ 
    function sql_injection($string)
    {
        return mysql_real_escape_string($string);   
    }
//----------------------------------------------------------------------------------     
     
     function cdf($date)//current date from time stamptodate
     {
        return date("d-M-Y",strtotime($date));
     }
     
    
    // prevent from sql injection 
    function go_safe($string,$string1=false)
    {
        if($string1==true)
        {
            return mysql_real_escape_string(trim($string));
        }
        else
        {
            return mysql_real_escape_string(trim($string));
        }
    }
    
    
    function insert($tbl_name,$data)
    {
        $this->db->insert($tbl_name,$data);
        return $this->db->insert_id();
    }
    
    
    function c_to_md5($string,$array=false)
    {
        return  md5($string);
    }
    


    function current_getRouteDetails($lat1,$lon1,$current_lat,$current_lon)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$lon1."&destinations=".$current_lat.",".$current_lon."&mode=driving&language=pl-PL";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        //echo '<pre>';print_r($response_a);die;
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $dist1=explode(',', $dist);
        //print_r($dist);die;
        $miles=$dist1[0]*0.621371;
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    //  $results=array('distance' => $miles, 'time' => $time);
        //print_r($results);die;
        //if($miles>5){
         //     $miles1=$miles-5; 
         //     $amounttravel=$miles1*0.50;select_rowwhere
        $results=array('distance' => $miles, 'time' => $time);
       // }else{

             $results=array('distance' => $miles, 'time' => $time);
        

        //}

        return $results;
    }
    

    
    function getRouteDetails($lat1,$lon1,$lat2,$lon2,$current_lat,$current_lon,$subcategory_id)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$lon1."&destinations=".$lat2.",".$lon2."&mode=driving&language=pl-PL";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        //echo '<pre>';print_r($response_a);die;
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        //$dist1=explode(',', $dist);
        $dist1=substr($dist, 0, strrpos($dist, ' '));
        $dist12=explode(',', $dist1);
        //print_r($dist1);die;
        $miles1=$dist12[0]*0.621371;
        $temp = explode('.',$miles1);
        $miles= implode('.', $temp);
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
    //  $results=array('distance' => $miles, 'time' => $time);
        //print_r($results);die;
    //  $currentposition=$this->current_getRouteDetails($lat1,$lon1,$current_lat,$current_lon);
//      print_r($currentposition);die;
        if($miles>5){
              $miles1=$miles-5; 
            $amounttravel1=$miles1*0.50;
            $temp = explode('.',$amounttravel1);
          //  unset($temp[count($temp) + 2]);
            $amounttravel= implode('.', $temp);
            $results=array('distance'=>$miles,'time'=>$time,'amount'=>$amounttravel);
            
        }else{

         $results=array('distance'=>$miles,'time'=>$time,'amount'=>0);

        }

        return $results;
    }
    
    
    function fetchCountryByLatLong($lat,$lng)
    {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
    $json = @file_get_contents($url);
    $output=json_decode($json);
    //echo "<pre>";print_r($output);die;
    $status = $output->status;
    
    for($j=0;$j<count($output->results[0]->address_components);$j++){
        $cn=array($output->results[0]->address_components[$j]->types[0]);
            if(in_array("country", $cn)){
                $country= $output->results[0]->address_components[$j]->long_name;
                
            }
            
    }
    if($status=="OK")
    //return $data->results[0]->formatted_address;
    return $country;
    else
    return '';
    
    }
    
    function fetchCityByLatLong($lat,$lng)
    {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
    $json = @file_get_contents($url);
    $output=json_decode($json);
    //echo "<pre>";print_r($output);die;
    $status = $output->status;
    
    for($j=0;$j<count($output->results[0]->address_components);$j++){
        $cn=array($output->results[0]->address_components[$j]->types[0]);
            if(in_array("locality", $cn)){
                $city= $output->results[0]->address_components[$j]->long_name;
            }
            
    }
    if($status=="OK")
    //return $data->results[0]->formatted_address;
    return $city;
    else
    return '';
    
    }
    
    
    
    //------------------------driver payments------------------ 
    function driverPayment($driverId,$x_amount,$transaction_Id,$approval_Code)
    {
        $created_on  =date('Y-m-d H:i:s');
        $data=array('driverId'=>$driverId,'x_amount'=>$x_amount,'transaction_Id'=>$transaction_Id,'approval_Code'=>$approval_Code,'createdOn'=>$created_on);
        
        $subEndDate=date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s", strtotime(date('Y-m-d H:i:s'))) . " + 365 day"));
        $dataX=array('driverId'=>$driverId,'subStartDate'=>$created_on,'subEndDate'=>$subEndDate,'createdOn'=>$created_on);
        if($this->db->insert('driverPayments',$data))
        {
            $where=array('driverId'=>$driverId);
            $isSub=$this->select_rowwhere_query('driverSubcription',$where);
            
            if($isSub){
                
                  $this->db->where($where);
                $this->db->update('driverSubcription',$dataX); 
                  return  "update";//$this->db->affected_rows() mysql_insert_id();  
                
            }else{
                
                 $this->db->insert('driverSubcription',$dataX);
                
                 return  "insert";//$this->db->insert_id(); mysql_insert_id();
            }
          
        }else{
            
            return FALSE;
        }
    }
    
    // check if email is unique
    public function isEmailUnique($table,$where){
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function isPhoneUnique($table,$where){
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();
        return $query->num_rows();
    }
    // check if email is unique
    public function isSocialIdUnique($table,$where){
        $this->db->where($where);
        $this->db->from($table);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    //get Rang
    public function getRangByAdmin()
    {
        $this->db->select('rang_value');
        $this->db->from('`tbl_user_rang');
        $query = $this->db->get();
        return $query->row()->rang_value;
    }
    
    //Param validation
    function param_validation($paramarray, $data)
    {
        $returnArr = array();
        $NovalueParam = array();

        foreach ($paramarray as $val)
         
        {
           //if (!$data[$val])
            if (empty($data[$val]))
         // if(isset($data[$val]) & !empty($val))
            {
                $NovalueParam[] = $val;
            }
        }
        if (is_array($NovalueParam) && count($NovalueParam) > 0)
        {
            $returnArr['status'] = 0;
            
            $returnArr['message'] = 'Sorry, that is not valid input. You missed ' . implode(',', $NovalueParam) . ' parameters';
            return $returnArr;
        }
        else
        {
            return $returnArr;
        }
    }
    
    function validateEmail($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL);
   }
   
      /*-------------------my mutual friends list--------------------------------------------------------------------------*/
    public function getMyFriendsList($user_id)
    {
        
    $where=array('user_status'=>1,'user_id <>'=>$user_id);
        //--------------if token  0-------------------------
            $this->db->select('*');
            
            $this->db->where($where);
            //$this->db->order_by("coins", "desc"); 
            $this->db->from('tbl_users');
            $datas = $this->db->get();
            $data= $datas->result_array(); 
            //echo $this->db->last_query();
            //echo '<pre>'; print_r($data);die;
            $arraycount=count($data);
            $respData= array();
            $AllUsers= array();
            if(!empty($data))
            {
                
                foreach($data as $userDatas)
                {                       
                    
                             $like_user_id= $userDatas['user_id'];
                            
                             $likeStatus=$this->getUserLikeStatus($user_id,$like_user_id); 
                             //echo $likeStatus; echo '<br>';
                             //if($likeStatus ==1 || !$likeStatus){
                                 $mutualStatus=$this->checkMutualStatus($user_id,$like_user_id);
                                 if($mutualStatus=='1'){
                                    $respData['isMutual']=$mutualStatus;
                                 $userDetails=getUserDetailsById($like_user_id);
                                 $respData['user_list_id']=$like_user_id;
                                 $respData['user_fullname']=$userDetails['user_fullname'];
                                 $respData['user_age']=$userDetails['user_age'];
                                 $respData['user_city']=$userDetails['user_city'];
                                 $respData['is_online']=$userDetails['is_online'];
                                 $respData['miles']=ceil($miles);
                                 $respData['likeStatus']=$likeStatus;
                                
                                 $respData['status']=$userDetails['status'];
                                 $respData['user_profile_pic']=$userDetails['user_profile_pic'];
                                 
                                 /*--*/
                                             $UserPics=array();
                                            $UserPics=getUserPicsByUserId($like_user_id);
                                            //print_r($UserPics);
                                            if(!empty($UserPics) && ($respData['user_profile_pic']!="")){
                                                
                                                $a=count($UserPics);
                                                $res=array();
                                                $result=array();
                                                array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$UserPics;
                                            }else if(empty($UserPics) && ($respData['user_profile_pic']!="")){
                                                
                                                $a=count($UserPics);
                                                $res=array();
                                                $result=array();
                                                array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$UserPics;
                                            }else if(!empty($UserPics) && ($respData['user_profile_pic']==" ")){
                                                
                                                $a=count($UserPics);
                                                $res=array();
                                                $result=array();
                                                //array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$UserPics;
                                            }else{
                                                
                                                $emptyArray=array();
                                                //array_unshift($emptyArray,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$emptyArray;
                                            }
                                            $AllUsers[]= $respData;
                                            /*--*/
                                 } 
                                 
                                 
                                 
                             //}
                        
                            
                  }
                  //die;
                
            }else{
                $emptyArray=array();
                $AllUsers['list_token']=$emptyArray;
            }
            return $AllUsers;
            //--------------------------end of token  0------------------------------------------------------
        
    }
    public function getTenNearByUsers($rang,$user_lat,$user_long,$user_id)
    {
        
        $defaultArray=getDefaultFilterByUserId($user_id);
        $is_default_filter=$defaultArray['is_default_filter'];
        
                //print_r($defaultArray);die; 
        //*for default filter*/
        if($is_default_filter>0){
            $user_lat=$defaultArray['user_lat'];
            $user_long=$defaultArray['user_long'];
            $intrested_in=$defaultArray['filter_gender'];
            $filter_age_from=$defaultArray['filter_age_from'];
            $filter_age_to=$defaultArray['filter_age_to'];
            $only_online=$defaultArray['only_online'];
            $filter_distance=$defaultArray['filter_distance'];
            
            if($intrested_in!=3 && $only_online!=3){
                //echo 'test1';
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in,'is_online'=>$only_online);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }elseif($intrested_in==3 && $only_online!=3){
                //echo 'test2'; 
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'is_online'=>$only_online);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }elseif($intrested_in!=3 && $only_online==3){
                //echo 'test3'; 
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
                
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }
            else{
                //echo 'test4';
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to);
            }
            
        }else{
            $where=array('user_status'=>1,'user_id <>'=>$user_id);
            $filter_distance=$rang;
            //$where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id);
        }
        /*for default filter*/
        
        
        $token=1;
        $i=0;
        $j=0;
        //--------------if token  0-------------------------
            $this->db->select('*');
            
            $this->db->where($where);
            $this->db->order_by("spend_coins", "desc"); 
            $this->db->from('tbl_users');
            $datas = $this->db->get();
            $data= $datas->result_array(); 
            //echo $this->db->last_query();
            //echo '<pre>'; print_r($data);die;
            $arraycount=count($data);
            $respData= array();
            $AllUsers= array();
            if(!empty($data))
            {
                
                foreach($data as $userDatas)
                {                       
                     $theta = $user_long - $userDatas['user_long'];
                     $dist = sin(deg2rad($user_lat)) * sin(deg2rad($userDatas['user_lat'])) +  cos(deg2rad($user_lat)) * cos(deg2rad($userDatas['user_lat'])) * cos(deg2rad($theta));
                     $dist = acos($dist);
                     $dist = rad2deg($dist);
                     $miles = $dist * 60 * 1.1515;
                    //echo $filter_distance.'<br>';
                    //echo $miles.'<br>';
                        if($miles<=$filter_distance)
                        {
                             $like_user_id= $userDatas['user_id'];
                            
                             $likeStatus=$this->getUserLikeStatus($user_id,$like_user_id); 
                             //echo $likeStatus; echo '<br>';
                             if($likeStatus ==1 || !$likeStatus){
                                 $mutualStatus=$this->checkMutualStatus($user_id,$like_user_id); 
                                 $respData['isMutual']=$mutualStatus;
                                 $userDetails=getUserDetailsById($like_user_id);
                                 $respData['user_list_id']=$like_user_id;
                                 //$respData['quickbloxId']=$userDetails['quickbloxId'];
                                 $respData['user_lat']=$userDetails['user_lat'];
                                 $respData['user_long']=$userDetails['user_long']; 
                                 $AllUsers[]= $respData;
                             }
                        }
                            
                  }
                  //die;
                
            }else{
                $emptyArray=array();
                $AllUsers=$emptyArray;
            }
            return $AllUsers;
            //--------------------------end of token  0------------------------------------------------------
        
    }
   /*-------------------star near by user for vip user--------------------------------------------------------------------------*/
    public function getUsersByRangForVip($rang,$user_lat,$user_long,$user_id)
    {
        
        $defaultArray=getDefaultFilterByUserId($user_id);
        $is_default_filter=$defaultArray['is_default_filter'];
        
                //print_r($defaultArray);die; 
        //*for default filter*/
        if($is_default_filter>0){
            $user_lat=$defaultArray['user_lat'];
            $user_long=$defaultArray['user_long'];
            $intrested_in=$defaultArray['filter_gender'];
            $filter_age_from=$defaultArray['filter_age_from'];
            $filter_age_to=$defaultArray['filter_age_to'];
            $only_online=$defaultArray['only_online'];
            $filter_distance=$defaultArray['filter_distance'];
            
            if($intrested_in!=3 && $only_online!=3){
                //echo 'test1';
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in,'is_online'=>$only_online);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }elseif($intrested_in==3 && $only_online!=3){
                //echo 'test2'; 
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'is_online'=>$only_online);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }elseif($intrested_in!=3 && $only_online==3){
                //echo 'test3'; 
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
                
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }
            else{
                //echo 'test4';
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to);
            }
            
        }else{
            $where=array('user_status'=>1,'user_id <>'=>$user_id);
            $filter_distance=$rang;
            //$where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id);
        }
        /*for default filter*/
        
        
        $token=1;
        $i=0;
        $j=0;
        //--------------if token  0-------------------------
            $this->db->select('*');
            
            $this->db->where($where);
            $this->db->order_by("spend_coins", "desc"); 
            $this->db->order_by("user_id", "desc"); 
            $this->db->from('tbl_users');
            $datas = $this->db->get();
            $data= $datas->result_array(); 
            //echo $this->db->last_query();
            //echo '<pre>'; print_r($data);die;
            $arraycount=count($data);
            $respData= array();
            $AllUsers= array();
            if(!empty($data))
            {
                
                foreach($data as $userDatas)
                {                       
                     $theta = $user_long - $userDatas['user_long'];
                     $dist = sin(deg2rad($user_lat)) * sin(deg2rad($userDatas['user_lat'])) +  cos(deg2rad($user_lat)) * cos(deg2rad($userDatas['user_lat'])) * cos(deg2rad($theta));
                     $dist = acos($dist);
                     $dist = rad2deg($dist);
                     $miles = $dist * 60 * 1.1515;
                    //echo $filter_distance.'<br>';
                    //echo $miles.'<br>';
                        if($miles<=$filter_distance)
                        {
                             $like_user_id= $userDatas['user_id'];
                            
                             $likeStatus=$this->getUserLikeStatus($user_id,$like_user_id); 
                             //echo $likeStatus; echo '<br>';
                             if($likeStatus ==1 || !$likeStatus){
                                 $mutualStatus=$this->checkMutualStatus($user_id,$like_user_id); 
                                 $respData['isMutual']=$mutualStatus;
                                 $userDetails=getUserDetailsById($like_user_id);
                                 $respData['user_list_id']=$like_user_id;
                                 //$respData['quickbloxId']=$userDetails['quickbloxId'];
                                 $respData['user_fullname']=$userDetails['user_fullname'];
                                 $respData['user_age']=$userDetails['user_age'];
                                 $respData['user_city']=$userDetails['user_city'];
                                 $respData['is_online']=$userDetails['is_online'];
                                 $respData['miles']=ceil($miles);
                                // $respData['friends_count']=0;
                                 $respData['likeStatus']=$likeStatus;
                                
                                 $respData['status']=$userDetails['status'];
                                 $respData['user_profile_pic']=$userDetails['user_profile_pic'];
                                 
                                 /*--*/
                                             $UserPics=array();
                                            $UserPics=getUserPicsByUserId($like_user_id);
                                            //print_r($UserPics);
                                            if(!empty($UserPics) && ($respData['user_profile_pic']!="")){
                                                
                                                $a=count($UserPics);
                                                $res=array();
                                                $result=array();
                                                array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$UserPics;
                                            }else if(empty($UserPics) && ($respData['user_profile_pic']!="")){
                                                
                                                $a=count($UserPics);
                                                $res=array();
                                                $result=array();
                                                array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$UserPics;
                                            }else if(!empty($UserPics) && ($respData['user_profile_pic']==" ")){
                                                
                                                $a=count($UserPics);
                                                $res=array();
                                                $result=array();
                                                //array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$UserPics;
                                            }else{
                                                
                                                $emptyArray=array();
                                                //array_unshift($emptyArray,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                                $respData['UserPics']=$emptyArray;
                                            }
                                            
                                            /*--*/
                                 
                                 $AllUsers[]= $respData;
                             
                             
                             
                             }
                        }
                            
                  }
                  //die;
                
            }else{
                $emptyArray=array();
                $AllUsers['list_token']=$emptyArray;
            }
            return $AllUsers;
            //--------------------------end of token  0------------------------------------------------------
        
    }
   /*end near by user for vip user*/
    
    /*public function getUsersByRang($rang,$user_lat,$user_long,$list_token,$user_id)
    {
        $token=1;
        $i=0;
        $j=0;
        if($list_token=='not_token')
        {
            //--------------if token  0-----------------------------------------------------------------------------------------------  
            $this->db->select('*');
            $where=array('user_status'=>1,'user_id <>'=>$user_id);
            $this->db->where($where);
            //$this->db->order_by("coins", "desc"); 
            $this->db->from('tbl_users');
            $datas = $this->db->get();
            $data= $datas->result_array(); 
            //echo '<pre>'; print_r($data);die;
            $arraycount=count($data);
            $respData= array();
            $AllUsers= array();
            if(!empty($data))
            {
                
                foreach($data as $userDatas)
                {                       
                     $theta = $user_long - $userDatas['user_long'];
                     $dist = sin(deg2rad($user_lat)) * sin(deg2rad($userDatas['user_lat'])) +  cos(deg2rad($user_lat)) * cos(deg2rad($userDatas['user_lat'])) * cos(deg2rad($theta));
                     $dist = acos($dist);
                     $dist = rad2deg($dist);
                     $miles = $dist * 60 * 1.1515;
                    //echo $miles.'<br>';
                        if($miles<=$rang)
                        {
                             $like_user_id= $userDatas['user_id'];
                            
                             $likeStatus=$this->getUserLikeStatus($user_id,$like_user_id); 
                             //echo $likeStatus; echo '<br>';
                             if($likeStatus ==1 || !$likeStatus){
                                 $mutualStatus=$this->checkMutualStatus($user_id,$like_user_id); 
                                 $respData['isMutual']=$mutualStatus;
                                 $userDetails=getUserDetailsById($like_user_id);
                                 $respData['user_list_id']=$like_user_id;
                                 $respData['quickbloxId']=$userDetails['quickbloxId'];
                                 $respData['user_fullname']=$userDetails['user_fullname'];
                                 $respData['user_age']=$userDetails['user_age'];
                                 $respData['user_city']=$userDetails['user_city'];
                                 $respData['is_online']=$userDetails['is_online'];
                                 $respData['miles']=ceil($miles);
                                // $respData['friends_count']=0;
                                 $respData['likeStatus']=$likeStatus;
                                
                                 $respData['status']=$userDetails['status'];
                                 $respData['user_profile_pic']=$userDetails['user_profile_pic'];
                                 $AllUsers[]= $respData;
                             
                             
                             
                             }
                        }
                        if($token===10){break;}else{ $token++;}
                        $i++;   
                  }
                  //die;
                
                    if($arraycount>=10){
                            $AllUsers['list_token']=$data[$i]['createdOn'];
                         }else{
                            $AllUsers['list_token']=$data[$arraycount-1]['createdOn']; 
                         }
        
            }else{
                $AllUsers['list_token']=date('Y-m-d H:i:s',time());
            }
            return $AllUsers;
            //--------------------------end of token  0------------------------------------------------------
        }
        else {
                //--------------if token is not 0-----------------------------------------------------------------------------------------------  
                $this->db->select('*');
                $where=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id);
                $this->db->where($where);
                $this->db->from('tbl_users');
                $datas = $this->db->get();
                $data= $datas->result_array(); 
                //echo '<pre>'; print_r($data);die;
                $arraycount=count($data);
                $respData= array();
                $AllUsers= array();
                if(!empty($data)){
                    foreach($data as $userDatas)
                    {                       
                        $theta = $user_long - $userDatas['user_long'];
                        $dist = sin(deg2rad($user_lat)) * sin(deg2rad($userDatas['user_lat'])) +  cos(deg2rad($user_lat)) * cos(deg2rad($userDatas['user_lat'])) * cos(deg2rad($theta));
                        $dist = acos($dist);
                        $dist = rad2deg($dist);
                        $miles = $dist * 60 * 1.1515;
                        //echo $miles.'<br>';
                        if($miles<=$rang)
                        {
                             $like_user_id= $userDatas['user_id'];
                             $likeStatus=$this->getUserLikeStatus($user_id,$like_user_id);
                             if($likeStatus==1 || !$likeStatus){
                                 $mutualStatus=$this->checkMutualStatus($user_id,$like_user_id); 
                                 $respData['isMutual']=$mutualStatus;
                                 $userDetails=getUserDetailsById($like_user_id);
                                 $respData['user_list_id']=$like_user_id;
                                 $respData['quickbloxId']=$userDetails['quickbloxId'];
                                 $respData['user_fullname']=$userDetails['user_fullname'];
                                 $respData['user_age']=$userDetails['user_age'];
                                 $respData['user_city']=$userDetails['user_city'];
                                 $respData['is_online']=$userDetails['is_online'];
                                 $respData['miles']=ceil($miles);
                                // $respData['friends_count']=0;
                                 $respData['likeStatus']=$likeStatus;
                                 $respData['status']=$userDetails['status'];
                                 $respData['user_profile_pic']=$userDetails['user_profile_pic'];
                                 $AllUsers[]= $respData;
                            }
                        }
                    if($token===10){break;}else{ $token++;} 
                    $j++;
                  }
                    if($arraycount>=10){
                            $AllUsers['list_token']=$data[$j]['createdOn'];
                         }else{
                            $AllUsers['list_token']=$data[$arraycount-1]['createdOn']; 
                         }
                            
                    
                }else{
                    //$AllUsers['list_token']=date('Y-m-d h:i:s',time());
                }
                return $AllUsers;
             //--------------------------end of token not 0------------------------------------------------------
            
        }
    }
*/
    public function getUsersByRang($rang,$user_lat,$user_long,$user_id)
    {
        $defaultArray=getDefaultFilterByUserId($user_id);
        $is_default_filter=$defaultArray['is_default_filter'];
        
        //print_r($defaultArray);die; 
        //*for default filter*/
        if($is_default_filter>0){
            $user_lat=$defaultArray['user_lat'];
            $user_long=$defaultArray['user_long'];
            $intrested_in=$defaultArray['filter_gender'];
            $filter_age_from=$defaultArray['filter_age_from'];
            $filter_age_to=$defaultArray['filter_age_to'];
            $only_online=$defaultArray['only_online'];
            $UserRang=$defaultArray['filter_distance'];
            
            if($intrested_in!=3 && $only_online!=3){
                //echo 'test1';
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in,'is_online'=>$only_online);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }elseif($intrested_in==3 && $only_online!=3){
                //echo 'test2'; 
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'is_online'=>$only_online);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }elseif($intrested_in!=3 && $only_online==3){
                //echo 'test3'; 
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
                
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to,'user_gender'=>$intrested_in);
            }
            else{
                //echo 'test4';
                $where=array('user_status'=>1,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to);
                $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id,'user_age >='=>$filter_age_from,'user_age <='=>$filter_age_to);
            }
            
        }else{
            $UserRang=$rang;
            $where=array('user_status'=>1,'user_id <>'=>$user_id);
            $where1=array('user_status'=>1,'createdOn >'=>$list_token,'user_id <>'=>$user_id);
        }
        /*for default filter*/
        
        $token=1;
        $i=0;
        $j=0;
        
            //--------------if token  0-----------------------------------------------------------------------------------------------  
            $this->db->select('*');
            //$where=array('user_status'=>1,'user_id <>'=>$user_id);
            $this->db->where($where);
            $this->db->order_by("spend_coins", "desc"); 
            $this->db->order_by("user_id", "desc"); 
            $this->db->from('tbl_users');
            $datas = $this->db->get();
            $data= $datas->result_array(); 
            //echo $this->db->last_query();
            //echo '<pre>'; print_r($data);
            $arraycount=count($data);
            $respData= array();
            $AllUsers= array();
            if(!empty($data))
            {   
                foreach($data as $userDatas)
                {
                                            
                     $theta = $user_long - $userDatas['user_long'];
                     $dist = sin(deg2rad($user_lat)) * sin(deg2rad($userDatas['user_lat'])) +  cos(deg2rad($user_lat)) * cos(deg2rad($userDatas['user_lat'])) * cos(deg2rad($theta));
                     $dist = acos($dist);
                     $dist = rad2deg($dist);
                     $miles = $dist * 60 * 1.1515;
                    /*echo 'my-lat-'.$user_lat.'<br>';
                    echo 'my-long-'.$user_long.'<br>';
                    echo 'userId-'.$userDatas['user_id'].'<br>';
                    echo 'user-lat-'.$userDatas['user_lat'].'<br>';
                    echo 'user-long-'.$userDatas['user_long'].'<br>';
                
                    echo 'userId-'.$userDatas['user_id'].'<br>';
                    echo $miles.'<br>';*/
                        if($miles<=$UserRang)
                        {
                             $like_user_id= $userDatas['user_id'];
                            
                             $likeStatus=$this->getUserLikeStatus($user_id,$like_user_id); 
                             //echo $likeStatus; echo '<br>';
                             if($likeStatus ==1 || !$likeStatus){
                                 $mutualStatus=$this->checkMutualStatus($user_id,$like_user_id); 
                                 $respData['isMutual']=$mutualStatus;
                                 $userDetails=getUserDetailsById($like_user_id);
                                 $respData['user_list_id']=$like_user_id;
                                 //$respData['quickbloxId']=$userDetails['quickbloxId'];
                                 $respData['user_fullname']=$userDetails['user_fullname'];
                                 $respData['user_age']=$userDetails['user_age'];
                                 $respData['user_city']=$userDetails['user_city'];
                                 $respData['is_online']=$userDetails['is_online'];
                                 $respData['miles']=ceil($miles);
                                // $respData['friends_count']=0;
                                 $respData['likeStatus']=$likeStatus;
                                
                                 $respData['status']=$userDetails['status'];
                                 $respData['user_profile_pic']=$userDetails['user_profile_pic'];
                                 
                                 /*--*/
                                 $UserPics=array();
                                $UserPics=getUserPicsByUserId($like_user_id);
                                //print_r($UserPics);
                                if(!empty($UserPics) && ($respData['user_profile_pic']!="")){
                                    
                                    $a=count($UserPics);
                                    $res=array();
                                    $result=array();
                                    array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                    $respData['UserPics']=$UserPics;
                                }else if(empty($UserPics) && ($respData['user_profile_pic']!="")){
                                    
                                    $a=count($UserPics);
                                    $res=array();
                                    $result=array();
                                    array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                    $respData['UserPics']=$UserPics;
                                }else if(!empty($UserPics) && ($respData['user_profile_pic']==" ")){
                                    
                                    $a=count($UserPics);
                                    $res=array();
                                    $result=array();
                                    //array_unshift($UserPics,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                    $respData['UserPics']=$UserPics;
                                }else{
                                    
                                    $emptyArray=array();
                                    //array_unshift($emptyArray,array("picid"=>"default",'pic_url'=>$respData['user_profile_pic']));
                                    $respData['UserPics']=$emptyArray;
                                }
                                
                                /*--*/
                                 
                                 
                                 $AllUsers[]= $respData;
                             
                             
                             
                             }
                        }
                  }
                  //die;
                
                    /*if($arraycount>=10){
                            $AllUsers['list_token']=$data[$i]['createdOn'];
                         }else{
                            $AllUsers['list_token']=$data[$arraycount-1]['createdOn']; 
                         }*/
        
            }else{
                $emptyArray=array();
                //$AllUsers['list_token']=$emptyArray;
            }
            return $AllUsers;
            //--------------------------end of token  0------------------------------------------------------
            
    }


    
 
   
  
   
 
     //--------------------------show near by rang hangout----------------------------------------------
  
   public function getHangoutByRang($rang,$user_lat,$user_long,$user_id)
   {
   
        $token=1;
        $i=0;
        $j=0;
        
 
        $select=array('tbl_hangout.user_id','hangout_id','hangout_title','hangout_description','media_url','media_type','check_in'
                      ,'tbl_hangout.created_on','user_fullname','user_email','user_lat','user_long','tbl_hangout.tag_id');
        $this->db->select($select);
        $where=array('user_status'=>1);
        $this->db->where($where);
        $this->db->from('tbl_hangout');
        $this->db->order_by("tbl_hangout.created_on","desc");
        $this->db->join('tbl_users','tbl_users.user_id=tbl_hangout.user_id');
        $datas = $this->db->get();
        $data= $datas->result_array(); 
        //echo $this->db->last_query();
        //echo '<pre>'; print_r($data);die;
        $arraycount=count($data);
        $respData= array();
        $AllUsers= array();
        if(!empty($data))
        {
            
            foreach($data as $userDatas)
            {                       
                 $theta = $user_long - $userDatas['user_long'];
                 $dist = sin(deg2rad($user_lat)) * sin(deg2rad($userDatas['user_lat'])) +  cos(deg2rad($user_lat)) * cos(deg2rad($userDatas['user_lat'])) * cos(deg2rad($theta));
                 $dist = acos($dist);
                 $dist = rad2deg($dist);
                 $miles = $dist * 60 * 1.1515;
                  if($miles<=$rang)
                    {
                        $user_to_id= $userDatas['user_id'];
                        $hangout_id= $userDatas['hangout_id'];
                        
                        $whereReport=array('user_id'=>$user_id,'hangout_id'=>$hangout_id);
                        $reportStatus=$this->select('tbl_hangout_report','*','s','c',$whereReport,false,false,false,false,false);
                        
                        if($reportStatus<=0 || $reportStatus==""){
                            
                            $hangoutdetail=$this->getHangOutDetailByID($user_to_id,$hangout_id);
                      //  print_r($hangoutdetail);
                        if($hangoutdetail){
                            
                                ///show hangout of last 4 days
                                $respData['hangout_id']=$hangout_id;
                                $respData['hangout_title']=$hangoutdetail[0]['hangout_title'];
                                $respData['hangout_i_liked']=$this->checkILikedHangout($user_id,$hangout_id);
                                $respData['hangout_description']=$hangoutdetail[0]['hangout_description'];
                                $respData['user_to_id']=$user_to_id;
                                $userDetails=getUserDetailsById($user_to_id);
                                $respData['user_fullname']=$userDetails['user_fullname'];
                                $respData['user_city']=$userDetails['user_city'];
                                $respData['user_profile_pic']=$userDetails['user_profile_pic'];
                                $respData['hangout_status']=$hangoutdetail[0]['hangout_status'];
                                if($hangoutdetail[0]['media_url']){
                                $respData['media_url']=$hangoutdetail[0]['media_url'];
                                }else{
                                $respData['media_url']="";//http://shapingtechnology.com/demo/skout/api_assets/images/avtar.png 
                                }
                                $respData['media_type']=$hangoutdetail[0]['media_type'];
                                $respData['check_in']=$hangoutdetail[0]['check_in'];                            
                                $respData['hangout_likes']=$this->getTotalHangoutLikesById($hangout_id);
                                $respData['hangout_comments']=$this->getTotalHangoutCommentsById($hangout_id);
                                $respData['created_on']=$hangoutdetail[0]['created_on'];
                                
                                if($hangoutdetail[0]['tag_id']!=0){
                                    $tagId = $hangoutdetail[0]['tag_id'];
                                    }else{
                                         $tagId='';
                                    }
                                $respData['tag_id']=$tagId;
                                $respData['tag_name']=getTagNameById($hangoutdetail[0]['tag_id']);
                                
                                
                                
                                //if(1<=$interval->d  &&   '0' !=$interval->d ){
                                
                                //$respData['created_hangout_time']=    $interval->d."d";
                                //}
                                //else if(23>=$interval->h && '0' !=$interval->h  )
                                //{
                                    
                                    //$respData['created_hangout_time']=    $interval->h."h";
                                //}
                                //else if(59>=$interval->i){
                                    
                                    //$respData['created_hangout_time']=    $interval->i."min";
                                //}
                                date_default_timezone_set("Asia/Calcutta");
                                 $current_date=date('Y-m-d H:i:s',time()); 
                                 $next_date=$respData['created_on'];
                                
                                $date_a = new DateTime($next_date);
                                $date_b = new DateTime($current_date);
                                $diff34 = date_diff($date_a,$date_b);
                                
                                
                                //accesing days
                                 $days = $diff34->d; 
                                //accesing months
                                 $months = $diff34->m; 
                                //accesing years
                                 $years = $diff34->y; 
                                //accesing hours
                                 $hours=$diff34->h; 
                                //accesing minutes
                                 $minutes=$diff34->i; 
                                //accesing seconds
                                 $seconds=$diff34->s;
                                
                                //echo $interval_time=$days.':'.$months.':'.$years.':'.$hours.':'.$minutes.':'.$seconds;
                                //die; 
                                if($minutes>1 && $hours<1 && $days<1){
                                
                                $respData['created_hangout_time']=$minutes .' min';
                                }
                                else if($seconds>0 && $minutes<1 && $hours<1)
                                {
                                    $respData['created_hangout_time']=$seconds .' sec';
                                }
                                else if($hours>1 && $seconds>=0 && $minutes>=0){
                                    
                                    $respData['created_hangout_time']=  $hours .' hr';
                                }
                                else if($days>0 && $hours>=0 && $seconds>=0 && $minutes>=0){
                                    
                                    $respData['created_hangout_time']=  $days .' days';
                                }else{
                                    
                                    $respData['created_hangout_time']=  0 .' day';
                                }
                                
                                
                                //--------------------------------------------
                                
                                
                                
                                $AllUsers[]= $respData; 
                         
                         
                         }
                        
                        
                        
                        
                            
                        }
                    }
                    
            }
        }
        else{
            //echo "Randome date";
            return FALSE;
        }
        //print_r($AllUsers);die;
           return $AllUsers;
        //--------------------------end of token  0------------------------------------------------------
        
   }


   
   //--------------------------end near by rang hangout-----------------------------------------------
   

   
   
    
  
   
  
   
   
    
  
     /*** Forget user password ***/
    function forgetuserpassword($email_value)
    {
        //echo $email_value;die;
        
        $this->db->where('user_email',$email_value);
        $query = $this->db->get('tbl_users');
        //print_r($query);
        //echo $query->num_rows;
        //die;
        if($query->num_rows()>0)
        {
            //echo "text";die;
             $pass= random_string('alnum',8);
            //print_r($pass);die;
            $pwd=md5($pass);
            //print_r($pwd);die;
            $user_data = array(
                    'user_password'=>   $pwd
                );
                //print_r($user_data);die;
         
            $this->db->where('user_email',$email_value);
            $this->db->update('tbl_users',$user_data); 
            
            $query1 = $query->result();
            //print_r($query1);die;
    
            $data = array(
                    'id'        => $query1[0]->id,
                    'user_email'        => $query1[0]->user_email,
                    'user_fullname'         => $query1[0]->user_fullname
                    
                );
                //print_r($data);die;
            $password = $this->generateRandomPassword(); 
            //print_r($password);die;   
            $this->send_new_password_email($data['id'], $data['user_email'],$data['user_fullname'],$pass);
            return true;
        }
        else
        {   
            return false;
        }
    }
     //*** send_password_to user***/
    
    function send_new_password_email($user_id,$user_email,$user_fullname,$pass)
    {
        //echo $admin_email;die;
        $this->load->library('parser');
        $data = array('user_fullname' =>$user_fullname,'user_password' =>$pass);
        $html=$this->parser->parse('forgotPasswordTemplate', $data);
        
        $result = '';
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");

        $this->email->from('noreply@getzing.co', 'ZiNG'); 
        $this->email->to($user_email);

        $this->email->subject(' password update');

        $this->email->message($html);

        if ($this->email->send())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
        
    /*** send forgetpassword  for admin***/
    
    function send_forgetpassword_email($admin_id, $admin_email,$admin_password)
    {
        //echo $admin_email;die;
        $result = '';
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");

        $this->email->from('noreply@getzing.co', 'ZiNG'); 
        $this->email->to($admin_email);

        $this->email->subject('ZiNG Forget password');

        $this->email->message('Hello '.$admin_email.',<br><br>  Thank you for reset your account password. <br> Please Login http://52.33.54.193/superadmin and update your password for more Security.<br> Password: '.$admin_password.'<br><br>Thank you!');

        if ($this->email->send())
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /*** for generate Random Password ***/
    function generateRandomPassword()
    {
      $password = '';
    
      $desired_length = rand(8, 20);
      //print_r($desired_length);die;
      for($length = 0; $length < $desired_length; $length++)
      {
        $password .= chr(rand(32, 126));
      }
      return $password;
    }
    
    /*** Forget password ***/
    function forgetpassword($email_value)
    {
        //echo $email_value;die;
        
        $this->db->where('admin_email',$email_value);
        $query = $this->db->get('tbl_admin');
        //print_r($query);
        //echo $query->num_rows;
        //die;
        if($query->num_rows==1)
        {
            //echo "text";die;
             $pass= random_string('alnum',8);
            //print_r($pass);die;
            $pwd=md5($pass);
            //print_r($pwd);die;
            $user_data = array(
                    'admin_password'=>  $pwd
                );
                //print_r($user_data);die;
         
            $this->db->where('admin_email',$email_value);
            $this->db->update('tbl_admin',$user_data); 
            
            $query1 = $query->result();
            //print_r($query1);die;
    
            $data = array(
                    'admin_id'      => $query1[0]->admin_id,
                    'admin_email'       => $query1[0]->admin_email
                    
                );
                //print_r($data);die;
            $password = $this->generateRandomPassword(); 
            //print_r($password);die;   
            $this->send_forgetpassword_email($data['admin_id'], $data['admin_email'],$pass);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    function sendvartsu($customer_email,$code)
    {
        

/*   $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");

        $this->email->from('Host01.net.in', 'Vartsu'); 
        $this->email->to($customer_mail);

        $this->email->subject('Vartsu Car Details');

        $this->email->message('Hello '.$customer_name.',<br><br>  Thank you for submit car details <br> <br>Thank you!');

        $this->email->send();
*/

        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");

        $this->email->from('Host01.net.in', 'Vartsu'); 
        $this->email->to($customer_email);

        $this->email->subject('Confirmation Code');
        $this->email->message('Hello '.$customer_email.',<br><br>Please get confirmation code.<br> Confirmation code: '.$code.'<br><br>Thank you!');

    //  $this->email->message('testing Thank you!');

        if ($this->email->send())
        {
            return 1;
            //echo 'mail send';
        }
        else
        {
            return 0;
            //echo 'not send';
        }
    }
    
    function unlinkFileByFolderNameAndFileName($folderPath,$fileName)
    {
        //$folderPath,$fileName
        //$folderPath='/api_assets/images/';
        //$fileName='6548.jpg';
        $m_img_real=$_SERVER['DOCUMENT_ROOT'].$folderPath.$fileName;
        
        
        if (file_exists($m_img_real)) 
        {
             unlink($m_img_real);
             return 'deleted';
        }else{
             return 'file not exists';
        }

    }
    function getLikedUsersDetails($res_arr)
    {
        $this->db->select("*");
        $this->db->from("tbl_users");
        $this->db->where_in('user_id',$res_arr);
        $query =  $this->db->get();
        return $query->result_array();
        
        
    }
    function getLikedHangoutsDetails($res_arr)
    {
        $this->db->select("*");
        $this->db->from("tbl_hangout");
        $this->db->where_in('hangout_id',$res_arr);
        $query =  $this->db->get();
        //print_r($query);die;
        return $query->result_array();
    }
   
   /* public function mailconfig()
    {
        $config = array();
        $config['useragent']           = "CodeIgniter";
        $config['mailpath']            = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail 25"
        $config['protocol']            = "smtp";
        $config['smtp_host']           = "localhost";
        $config['smtp_port']           = "25";
        $config['mailtype']            = 'mail';
        $config['charset']             = 'utf-8';
        $config['newline']             = "\r\n";
        $config['wordwrap']            = TRUE;
        return $config;
    }*/

     public function SentOrderBillByEmail1($customer_email)
    {
      //----------------------------------------------------------------------------//
        $data = new stdClass();
      //  $data->details  = $order;
       // $data->taxdetails= $taxdetails;
        //----------------------------------------------------------------------------//
        $config= $this->mailconfig();
        $this->load->library('email');
        $this->email->initialize($config);
        $toemail  =$customer_email;
        $subject  = "Order Bill";
     //   $message  = $this->load->view('newOrder',$data,true);
           $message  = 'tesing in inbox';
       
        $config=array(
        'charset'=>'utf-8',
        'wordwrap'=> TRUE,
        'mailtype' => 'html'
        );
        $this->email->initialize($config);
        $this->email->to($toemail);
        $this->email->set_newline("\r\n");
        $this->email->from('shubhamapptech6@gmail.com','ConnectMart'); 
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
        show_error($this->email->print_debugger());
       if($this->email->send()){
         echo "mail send";
       }
       else{

           echo 'email not send'; 
       }
    }


    function sendmanojr($customer_email)
    {
         //$data = new stdClass();
        //echo $admin_email;die;
        $result = '';
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");

        $this->email->from('shubhamapptech6@gmail.com', 'ConnectMart'); 
        $this->email->to($customer_email);

        $this->email->subject('Order Bill');
        $this->email->message('Hello '.$customer_email.',<br><br>  Thank you for reset your account password. <br> Please Login http://52.33.54.193/superadmin and update your password for more Security.<br> Password: '.$customer_email.'<br><br>Thank you!');

    //  $this->email->message('testing Thank you!');

        if ($this->email->send())
        {
            echo 'mail send';
        }
        else
        {
            echo 'not send';
        }
    }

   public function mailconfig()
   {$config = array();
   $config['useragent'] = "CodeIgniter";
   $config['mailpath'] = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail 25"
   $config['protocol'] = "smtp";
   $config['smtp_host'] = "localhost";
   $config['smtp_port'] = "25";
   $config['mailtype'] = 'html';
   $config['charset'] = 'utf-8';
   //config['newline'] = "\r\n";
   $config['wordwrap'] = TRUE;
   return $config;
}

public function sendmanojs($user_email)
{
$data = new stdClass();
$from = $user_email;
//$data->result = $userdata;
$data->user_fullname="govind";
$data->pass="123456";
//$data = array('user_fullname' =>$user_fullname,'user_password' =>$pass);
$this->load->library('email');

$config = $this->mailconfig();
$this->email->initialize($config);
$subject = 'New Order';
$message = $this->load->view('forgotPasswordTemplate',$data,true);
//$config=array('charset'=>'utf-8','wordwrap'=> TRUE,'mailtype' => 'html');
$this->email->initialize($config);//$this->email->to('shubhamapptech6@gmail.com');
$this->email->to('abhiapptech@gmail.com');
$this->email->set_newline("\r\n");
$this->email->from('shubhamapptech6@gmail.com','ConnectMart');
$this->email->subject($subject);
$this->email->message($message);
if ($this->email->send())
        {
            echo 'email send';
        }
        else
        {
            show_error($this->email->print_debugger());
            die;
            echo 'email not send';
        }
}


    function sendmanoja($user_email)
    {
        //echo $admin_email;die;
        $user_fullname='abhishek';
        $pass='123456';
        $this->load->library('parser');
        $data = array('user_fullname' =>$user_fullname,'user_password' =>$pass);
        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype('html');
        //$headers = "X-Mailer: PHP". phpversion() ."\r\n";
    //  $headers ='X-Mailer: PHP/' . phpversion();
    //    $headers = "MIME-Version: 1.0\r\n";
    //    $headers = "Content-type: text/html; charset=iso-8859-1\r\n";
      //  $this->email->set_header($headers,'value1');
        $this->email->from('shubhamapptech6@gmail.com','ConnectMart'); 
        $this->email->to($user_email);
        $html=$this->parser->parse('forgotPasswordTemplate',$data,true);
        $this->email->subject('Order confirmation');
       // $html=$this->parser->parse('forgotPasswordTemplate',$data,true);
       //$this->email->message('Hello '.$user_email.',<br><br> Your order successfully created. Thank you!');
        $this->email->message($html);

        if ($this->email->send())
        {
            echo 'email send';
        }
        else
        {
            echo 'email not send';
        }
    }
    //}
    function sendmanojc($user_email) {
        //echo $admin_email;die;
        $this ->load->library('parser');
        $user_fullname='abhishek';
        $pass='123456';
    
        $data = array('user_fullname' => $user_fullname, 'user_password' => $pass);
        $html = $this ->parser->parse('forgotPasswordTemplate', $data,true);

        $result = '';
        $this ->load->library('email');

        //---------------------------
    $this ->email-> initialize(array('protocol' => 'smtp', 'smtp_host' => 'smtp.sendgrid.net', 'smtp_user'=>'abhishek98277','smtp_pass' =>'abhishek8', 'smtp_port' =>587, 'mailtype' => 'html', 'crlf' => "\r\n", 'newline' => "\r\n"));

        $this ->email->from('shubhamapptech6@gmail.com', 'ZiNG');
        //noreply@getzing.co
        $this->email->to($user_email);

        $this->email->subject('ZiNG password update');
        $this->email->message($html);

        if ($this->email->send()) {
            echo 'send';
        } else {
            //  show_error($this->email->print_debugger());
            //die;
            echo 'not send';
        }
    }
public function sendmanoj($customer_email)    {    
    $data['result'] = 'a';     
     //  $technician_email = $user_email;       
       $config=array( 'charset'=>'utf-8',
        'wordwrap'=> TRUE,        
        'mailtype' => 'html');        
       $this->email->set_newline("\r\n");        
       $this->email->initialize($config);       
        $message  = 'Hi,'."\r\n";        
        $message .= 'Congratulations for being a part of our team. Our whole team welcome to you. We are looking forward for the company’s success with you.'."\r\n\r\n";       
         $message .= $this->load->view('newOrder',$data,true);        
         $this->load->library('email');        
         $this->email->from('business@ckarmainc.com','ConnectMart');        
         $this->email->to($customer_email);        
         $this->email->subject('New registration');        
         $this->email->message($message);        
        
        if ($this->email->send()) {
            echo 'send';
        } else {
            //  show_error($this->email->print_debugger());
            //die;
            echo 'not send';
        }            
     }

    
}

?>




