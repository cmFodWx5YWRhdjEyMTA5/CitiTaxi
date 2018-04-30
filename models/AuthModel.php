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
        $status = $this->db->get_where($table_name,$where)->num_rows();
        if($status!=0){
            return true;
        }
        else{
           $response  = array("error"=>401,"message"=>"You are now inactive. Please contact with help support","data"=>'');
            echo json_encode($response);
            exit();
        }
    }

    public function getSingleRecord($table_name,$where)
    {
        return $this->db->get_where($table_name,$where)->row();
    }

    public function getMultipleRecord($table_name,$where,$orderby)
    {
    	$this->db->order_by($orderby);
        return $this->db->get_where($table_name,$where)->result();
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
            $this->load->library('email');
            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('cititaxi@gmail.com','kotchi'); 
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

    public function searchFromAddress($searchtype,$fromAddLat,$fromAddLng,$date,$time)
    {
        $this->db->select("ride.*,users.*,( 3959 * acos( cos( radians($fromAddLat) ) * cos( radians(`fromLat`) ) * cos( radians( `fromLng` ) - radians($fromAddLng) ) + sin( radians($fromAddLat) ) * sin( radians( `fromLat` ) ) ) ) AS distance");
        $this->db->from('ride');
        $this->db->join('users','ride.user_id=users.id');
        $this->db->where(array('ridetype'=>$searchtype,'date'=>$date,'ride.status'=>0));
        $this->db->having('distance <= ',10);  
        $this->db->order_by('distance');
        //print_r($this->db->last_query());die();
        return $res=$this->db->get()->result();
    }

    public function searchToAddress($rideId,$toAddLat,$toAddLng)
    {
        $this->db->select("ride.*,users.*,( 3959 * acos( cos( radians($toAddLat) ) * cos( radians(`toLat`) ) * cos( radians( `toLng` ) - radians($toAddLng) ) + sin( radians($toAddLat) ) * sin( radians( `toLat` ) ) ) ) AS distance");
        $this->db->from('ride');
        $this->db->join('users','ride.user_id=users.id');
        //$this->db->join('workarea','workarea.user_id=registration.id');
        $this->db->where(array('ride_id'=>$rideId));
        $this->db->having('distance <= ',10);  
        $this->db->order_by('distance');
        return $this->db->get()->row();
    }

    public function searchToAddressWithCar($rideId,$toAddLat,$toAddLng)
    {
        $this->db->select("ride.*,users.*,vehicle.*,( 3959 * acos( cos( radians($toAddLat) ) * cos( radians(`toLat`) ) * cos( radians( `toLng` ) - radians($toAddLng) ) + sin( radians($toAddLat) ) * sin( radians( `toLat` ) ) ) ) AS distance");
        $this->db->from('ride');
        $this->db->join('users','ride.user_id=users.id');
        $this->db->join('vehicle','ride.vehicleid=vehicle.vehicle_id');
        //$this->db->join('workarea','workarea.user_id=registration.id');
        $this->db->where(array('ride_id'=>$rideId));
        $this->db->having('distance <= ',10);  
        $this->db->order_by('distance');
        return $this->db->get()->row();
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

    


	
}
?>