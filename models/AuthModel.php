<?php
class AuthModel extends CI_Model {
	function __construct() {
        parent::__construct();
    }

    public function imageUpload($image,$folder_name)
    {
        if(isset($image) && !empty($image))
		{
			$len = 8;
			$randno =$this->radomno($len);
            $imageame  = $randno.$image['name'];
			$s  = $image['tmp_name'];
			$nn = preg_replace('/\s*/m', '',$imageame);
            $d  = $folder_name.'/'.$nn;
            move_uploaded_file($s,$d);
            return $nn;
	    }
    }

    public function MultipleUpload($image,$folder_name)
    {
        //print_r($image);die();
        $output = '';  
        if(isset($image) && !empty($image))
        {  
            $count =0; $unsave=0; $saved_images=''; $unsaved='';
            foreach($image['name'] as $name => $value)  
            {  
                $len = 8;
                $randno =$this->radomno($len);
                $imagename =   $randno.$image['name'][$name];
                $new_name = preg_replace('/\s*/m', '',$imagename); 
                $sourcePath = $image["tmp_name"][$name];  
                $targetPath = $folder_name.'/'.$new_name;  
                move_uploaded_file($sourcePath, $targetPath);
                $imagenames[] = $new_name;
               //$file_name = explode(".", $_FILES['images']['name'][$name]);  
               //$allowed_extension = array("jpg", "jpeg", "png", "gif");  
               //if(in_array($file_name[1], $allowed_extension))  
               //{  
                    
                //}  
            }
            return $imagenames;
        } 
    }

    public function ajaximageUpload($image,$folder_name)
    {
        if(isset($image) && !empty($image))
        {
            $len = 8;
            $randno =$this->radomno($len);
            $imageame  = $randno.$image['name'];
            $s  = $image['tmp_name'];
            $nn = preg_replace('/\s*/m', '',$imageame);
            $d  = $folder_name.'/'.$nn;
            if(move_uploaded_file($s,$d))
            {                
                return $imageame;
            }
            else
            {
                return false;
            }
        }
    }

    public function ajaxMultipleUpload($image,$folder_name)
    {
        $output = '';  
        if(isset($image) && !empty($image))
        {  
            $count =0; $unsave=0; $saved_images=''; $unsaved='';
            foreach($_FILES['images']['name'] as $name => $value)  
            {  
               $file_name = explode(".", $_FILES['images']['name'][$name]);  
               $allowed_extension = array("jpg", "jpeg", "png", "gif");  
               if(in_array($file_name[1], $allowed_extension))  
               {  
                    $len = 8;
                    $randno =$this->radomno($len);
                    $new_name =   $randno.$_FILES['images']['name'][$name]; 
                    $sourcePath = $_FILES["images"]["tmp_name"][$name];  
                    $targetPath = $folder_name.'/'.$new_name;  
                    if(move_uploaded_file($sourcePath, $targetPath))
                    {
                        ++$count;
                        $saved_images[]= $new_name;  
                    }
                    else{
                        ++$unsave;
                        $unsaved[] = $_FILES['images']['name'][$name]; 
                    }  
               }  
            }
            $res = array("saved"=>$saved_images,"unsave"=>$unsaved,"totalupload"=>$count,"unupload"=>$unsave);            
            return $res; 
        }
        else{
            echo 'not isset';
        }  
    }



    public function radomno($len)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz0123456789aABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $uuid = '';
        for ($i = 0; $i < $len; $i++) {
            $uuid .= $characters[rand(0, $charactersLength - 1)];
        }
        return $uuid;
    }

    public function singleInsert($table_name,$data)
    {
        if($this->db->insert($table_name,$data))
        {
            return $this->db->insert_id();
        }
    }

    public function batchInsert($table_name,$data)
    {
        return $this->db->insert_batch($table_name, $data);
    }

    public function checkRows($table_name,$where)
    {
        return $this->db->get_where($table_name,$where)->num_rows();
    }   
    
    public function user_score($user_id,$user_type)
    {
        if($this->checkRows('users_score',array('user_id'=>$user_id))==0)
        {            
            $this->db->insert('users_score',array("user_id"=>$user_id,'user_type'=>$user_type));
        }        
    }

    public function passwordAttempt($table_name,$where)
    {
        unset($where['password']);
        //print_r($where);die();
        $checkLogin = $this->db->get_where($table_name,$where);
        if($checkLogin->num_rows()>0)
        {
            $preWrongAttempt = $checkLogin->row()->wronglyPassword;
            $newAttempt =$preWrongAttempt+1;
            $upWhere = array('wronglyPassword'=>$newAttempt);
            $message = 'Wrong password! After '.(5-$newAttempt). '  attempt, Account will be Banned.';
            if($preWrongAttempt>=4)
            {
                $upWhere = array('wronglyPassword'=>$preWrongAttempt+1,'activeStatus'=>'Banned');
                $message = 'Your account is banned. You have type wrong password 5 time. Please contact with help support';
            }
            if($this->updateRecord($where,$table_name,$upWhere))
            {
                $response = array('success'=>0,'error'=>2,'message'=>$message);
                echo json_encode($response);die();
            }
        }
    }

    public function checkActiveStatus($table_name,$where)
    {
        $status = $this->db->get_where($table_name,$where)->row();
        //print_r($status);die();
        if(!empty($status)){
            if($status->activeStatus=='Active')
            {
                return true;
            }
            elseif($status->activeStatus=='Suspended')
            {
                $response  = array("error"=>401, "success"=>0,"message"=>"Your account has been suspended for ".$status->suspend_type.". Please contact with help support","data"=>'');
                echo json_encode($response);
                exit();
            }
            elseif($status->activeStatus=='Banned'){
                $response  = array("error"=>401,"success"=>0,"message"=>"Your account has been Banned. Please contact with help support","data"=>'');
                echo json_encode($response);
                exit();
            }
            else{
                $response  = array("error"=>401,"success"=>0,"message"=>"You are now inactive. Please contact with help support","data"=>'');
                echo json_encode($response);
                exit();            
            }        
        }
    }

    public function getSingleRecord($table_name,$where)
    {
        return $this->db->get_where($table_name,$where)->row();
    }

    public function getColumnSum($table_name,$col_name,$where)
    {
        $this->db->select_sum($col_name);
        $this->db->where($where);
        $sum = $this->db->get($table_name)->row(); 
        //print_r($sum);die();
        if(!empty($sum->$col_name)){            
        return $sum->$col_name;
        }
        else
        {            
            return 0;
        }
    } 

    public function betweenDateData($table_name)
    {
        $this->db->where('`driver_earning` BETWEEN SYSDATE() - INTERVAL 7 DAY AND SYSDATE()', NULL, FALSE);
        $query = $this->db->get($table_name);
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
            }
        }
    }   

    public function getMultipleRecord($table_name,$where,$orderby)
    {
        $this->db->order_by($orderby);
        return $this->db->get_where($table_name,$where)->result();
    }    


    public function getWhereInRecord($table_name,$col_name,$wherinarray)
    {        
        $this->db->where_in($col_name,$wherinarray);
        $this->db->from($table_name);
        return $this->db->get()->result();
        //print_r($this->db->last_query());die();
    }
    public function getnotWhereInRecord($table_name,$col_name,$wherinarray,$where)
    {        
        $this->db->from($table_name);
        $this->db->where_not_in($col_name,$wherinarray);
        $this->db->where($where);        
        return $this->db->get()->result();
        //print_r($this->db->last_query());die();
    }

    public function getOrWhereMultipleRecord($table_name,$where,$or_where,$orderby)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->or_where($or_where);
        $this->db->where($where);
        $this->db->order_by($orderby);
        return $this->db->get()->result();
        //return $this->db->get_where($table_name,$where)->result();
    } 

    public function checkRowsWithOr_where($table_name,$where,$or_where)
    {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->or_where($or_where);
        $this->db->where($where);
        return $this->db->get()->num_rows();       
    }

    public function driverDetails()
    {
        $this->db->select('users.*,vechile_details.*,driver_license.*');
        $this->db->from('users');
        $this->db->join('vechile_details','vechile_details.driver_id=users.id');
        $this->db->join('driver_license','driver_license.user_id=users.id');
        $this->db->where(array('user_type'=>1));
        $this->db->order_by('users.id','DESC');
        return $this->db->get()->result();
    }

    public function driverDetailsfleet($where)
    {
        $this->db->select('users.*,vechile_details.*,driver_license.*');
        $this->db->from('users');
        $this->db->join('vechile_details','vechile_details.driver_id=users.id');
        $this->db->join('driver_license','driver_license.user_id=users.id');
        $this->db->where($where);
        $this->db->order_by('users.id','DESC');
        return $this->db->get()->result();
    }  


    public function keychange($user_detail)
    {
        foreach($user_detail as $key => $val)
        {
            if ($key=='image' && $user_detail->image_type==0)
            //if ($key=='image')
            {
                $user_detail->image = base_url().'userimage/'.preg_replace('/\s*/m', '',$user_detail->image);
            }
            if($key=='vichleimage')
            {
                $user_detail->vichleimage = base_url().'vechileImage/'.$user_detail->vichleimage;
            }
            if($key=='licenseImage')
            {
               $user_detail->licenseImage= base_url().'licenceImage/'.$user_detail->licenseImage;
            }
            if($key=='selected_image')
            {
               $user_detail->selected_image= base_url().'serviceimage/'.$user_detail->selected_image;
               //print_r($user_detail->selected_image);die();
            }
            if($key=='unselected_image')
            {
               $user_detail->unselected_image= base_url().'serviceimage/'.$user_detail->unselected_image;
            }
            if($key=='dropoff')
            {
                $user_detail->dropoff= json_decode($user_detail->dropoff);
            }
            if(isset($user_detail->password))
            {unset($user_detail->password); }
        }
        return $user_detail;
    }

    public function loginViaMedia($media_id,$email,$login_type,$devicetoken,$device_type,$user_type)
    {
        $loginCheck = $this->db->get_where('users', array('email'=>$email,'user_type'=>$user_type));
        $count = $loginCheck->num_rows();
        if($count>0)
        {
            $table_name ="users";
            $activeWhere = array("email"=>$email,"activeStatus"=>'Active','user_type'=>$user_type);
            $this->checkActiveStatus($table_name,$activeWhere);      //Check, User is Active or not by admin;
            $userData    = $loginCheck->row();
            $id          = $userData->id;
            $fb_id       = $userData->fb_id;
            $googleId    = $userData->google_id;
            //Update Device token and type
            $checkWhere = array('id'=>$id);
            $updata = array('device_token'=>$devicetoken,'device_type'=>$device_type);
            $this->updateRecord($checkWhere,$table_name,$updata);
            
            if($login_type==1 && ($fb_id=='' or $fb_id==0))
            {
                $updata = array('fb_id'=>$media_id);
                $this->updateRecord($checkWhere,$table_name,$updata);
                return $this->db->get_where('users', array('email'=>$email,'fb_id'=>$media_id,'user_type'=>$user_type))->row();
            }
            elseif($login_type==2 && ($googleId=='' or $googleId==0))
            {
                $updata = array('google_id'=>$media_id);
                $this->updateRecord($checkWhere,$table_name,$updata);
                return $this->db->get_where('users', array('email'=>$email,'google_id'=>$media_id,'user_type'=>$user_type))->row();
            }
            elseif($login_type==1 && $fb_id==$media_id)
            {
                return $this->db->get_where('users', array('email'=>$email,'fb_id'=>$media_id,'user_type'=>$user_type))->row();
            }
            elseif($login_type==2 && $googleId==$media_id)
            {
                return $this->db->get_where('users', array('email'=>$email,'google_id'=>$media_id,'user_type'=>$user_type))->row();
            }
            else
            {
                $response  = array("success"=>0,"error"=>1,"message"=>"Invalid request","data"=>'');
                echo json_encode($response);
                exit();
            }
        }
        else
        {
            $response  = array("success"=>0,"error"=>1,"message"=>"User does not exist","data"=>'');
            echo json_encode($response);
            exit();
        }
    }

    public function updateRecord($checkWhere,$table_name,$updata)
    {
        $this->db->where($checkWhere);
        return $this->db->update($table_name,$updata);          
    }

    public function delete_record($table_name,$checkWhere)
    {
        $this->db->where($checkWhere);
        return $this->db->delete($table_name); 
    }

    public function forget_password($table_name,$email)
    {
        $check=$this->db->get_where($table_name,array('email'=>$email));
        $count=$check->num_rows();      
        if($count>0)
        {
            $forget_stamp = date('Y-m-d H:i:s');
            $this->updateRecord(array('email'=>$email),'users',array('forget_timestamp'=>$forget_stamp));
            $res= $check->row();
            $data = new stdClass();
            $data->email = $res->email;
            $data->password = $res->password;       
            //----------------------------------------------------------------------------//
            $this->load->library('email');
            $subject = "Forget Password";
            $message = $this->load->view('forget_passwordTemp',$data,true);
            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );

            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('cititaxi@gmail.com','cititaxi'); 
            $this->email->to($res->email);
            $this->email->subject('Forget Passwoord');
            $this->email->message($message);
            if($this->email->send())
            {
                return 0;
            } 
            else
            {
                return 1;  
            }
        }
        else
        {           
            return 2;
            
        }
    }
    public function recover_walletPin($table_name,$email,$user_type)
    {
        $check=$this->db->get_where($table_name,array('email'=>$email,'user_type'=>$user_type));
        $count=$check->num_rows();      
        if($count>0)
        {
            $forget_stamp = date('Y-m-d H:i:s');
            $this->updateRecord(array('email'=>$email,'user_type'=>$user_type),'users',array('recoverpin_timestamp'=>$forget_stamp));
            $res= $check->row();
            $data = new stdClass();
            $data->email = $res->email;
            $data->password = $res->password;       
            //----------------------------------------------------------------------------//
            $this->load->library('email');
            $subject = "Recover Pin";
            $message = $this->load->view('recover_walletPinTemp',$data,true);
            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );
            $this->load->library('email');
            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('cititaxi@gmail.com','CitiTaxi'); 
            $this->email->to($res->email);
            $this->email->subject($subject);
            $this->email->message($message);
            if($this->email->send())
            {
                return 0;
            } 
            else
            {
                return 1;  
            }
        }
        else
        {           
            return 2;
            
        }
    }

    public function change_password($old_password,$password,$id)
    {
        $this->db->where('id',$id);
        $this->db->where('password',$old_password);
        $rr=$this->db->update('admin',array('password'=>$password));
        $result= $this->db->affected_rows($rr); 
        // echo $result;die();
        if($result>0)
        {
            return true;
        }   
        else
        {
            return false;
        }
    }   

    public function Suspend($suspend_days,$user_id)  //when cancel limit exceded
    {
        $table_name = "useraction";
        $byStatus = 'Admin';
        $suspend_type = $suspend_days.' Day';       
        $to = date('d-m-Y', strtotime("+".$suspend_type));
        $todate = $to.' 23:59:59';
        $fromstring = strtotime(date('d-m-Y H:i:s'));
        //print_r($todate);
        $where = array("user_id"=>$user_id);
        $upwhere = array('id'=>$user_id);
        $userUpdateData = array('activeStatus'=>'Suspended','suspend_type'=>$suspend_type);
        $checkExist = $this->AuthModel->checkRows($table_name,$where);
        if($checkExist>0)
        {
            $updata = array('suspand_type'=>$suspend_type,'from'=>date('d-m-Y H:i:s'),'fromstring'=>$fromstring,'to'=>$todate,'tostring'=>strtotime($todate),'suspend_by'=>$byStatus);
            if($this->AuthModel->updateRecord($where,$table_name,$updata))
            {
                $this->AuthModel->updateRecord($upwhere,'users',$userUpdateData);   
                return true; 
            }
            else
            {
                return false;
            }
        }
        else
        {
            $insertData = array("user_id"=>$user_id,'suspand_type'=>$suspend_type,'from'=>date('Y-m-d H:i:s'),'fromstring'=>$fromstring,'to'=>$todate,'tostring'=>strtotime($todate),'suspend_by'=>$byStatus);
            if($this->AuthModel->singleInsert($table_name,$insertData))
            {
                $this->AuthModel->updateRecord($upwhere,'users',$userUpdateData);
                return true; 
            }
            else
            {
                return false;
            }
        }
    } 

    public function checkRequiredParam($paramarray,$type)
    {  
        $returnArr = array();
        $NovalueParam = array();
        foreach ($paramarray as $val)         
        {           
            if (empty($type[$val]))         
            {
                $NovalueParam[] = $val;
            }
        }
        if (is_array($NovalueParam) && count($NovalueParam) > 0)
        {
            $returnArr['status'] = 0;            
            $returnArr['message'] = 'Sorry, You missed ' . implode(', ', $NovalueParam) . ' parameters';
            return $returnArr;
        }
        else
        {
            return $returnArr;
        }    
    }

    public function get_rating($user_id)
    {
        $rr = $this->db->get_where('review',array('receiver_id'=>$user_id))->result();       
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

    public function checkThenInsertorUpdate($table_name,$data,$where)
    {
        $check = $this->checkRows($table_name,$where);
        if($check==0)
        {
            if($this->singleInsert($table_name,$data)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            if($this->updateRecord($where,$table_name,$data)){
                return true;
            }
            else{
                return false;
            }
        }
    }

    public function saveReferralDiscount($uid,$referral_code,$user_type){
        $userdata = $this->db->get_where('users',array('ref_code'=>$referral_code,'user_type'=>$user_type))->row();        
        if(!empty($userdata)){
            $country = $userdata->nationality;
            $setting = $this->db->get_where('referral_setting',array('country'=>$country,'user_type'=>$user_type))->row();
            if(!empty($setting)){
                $last_date = date('d-m-Y',strtotime(date('d-m-Y').'+'.$setting->within_days.' days'));                
                $data = array(
                    'user_id'=>$uid,
                    'referral_user_id'=>$userdata->id,
                    'referral_setting_id'=>$setting->referral_setting_id,
                    'user_bonus'=>$setting->amount_to_frnd,
                    'referral_bonus'=>$setting->bonus_to_referral,
                    'min_ride'=>$setting->min_ride,
                    'last_date'=>$last_date,
                    'last_date_string'=>strtotime($last_date)
                    );
                $this->db->insert('user_referral_bonus',$data);               
            }
        }
    }




    public function tableConfig()
    {
        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li>";
        $config['last_tagl_close'] = "</li>";
        $config['use_page_numbers'] = TRUE; 
        // Initialize
        return $config;       
    }

    // Fetch records
    public function getData($rowno,$rowperpage,$search,$search2,$table_name,$where) {                
        $this->db->select('*');
        $this->db->from($table_name);        
        $this->db->where($where);        
        if(!empty($search)){
            $this->db->like($search);
            if(!empty($search2)){
                $this->db->or_like($search2);
            }
            //$this->db->like($search);
        }
        $this->db->limit($rowperpage, $rowno);  
        $query = $this->db->get();  
        //print_r($this->db->last_query());die();      
        return $query->result();
    }

    // Select total records
    public function getrecordCount($search,$search2,$table_name,$where) {
        $this->db->select('count(*) as allcount');
        $this->db->from($table_name);            
        $this->db->where($where);            
        if(!empty($search)){
            $this->db->like($search);
            if(!empty($search2)){
                $this->db->or_like($search2);
            }
            //$this->db->like($search);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['allcount'];
    }

    
	
}
?>