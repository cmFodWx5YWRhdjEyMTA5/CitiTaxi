<?php
class AbhiModel extends CI_Model {
	function __construct() {
        parent::__construct();
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
        $driver=$query->result_array();
        return $driver;        
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

        $this->load->library('email');
        $this->email->set_newline("\r\n");
        $this->email->set_mailtype("html");

        $this->email->from('Host01.net.in', 'Vartsu'); 
        $this->email->to($customer_email);

        $this->email->subject('Confirmation Code');
        $this->email->message('Hello '.$customer_email.',<br><br>Please get confirmation code.<br> Confirmation code: '.$code.'<br><br>Thank you!');   

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


	
}
?>