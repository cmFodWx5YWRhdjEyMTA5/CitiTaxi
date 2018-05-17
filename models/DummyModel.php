<?php
class DummyModel extends CI_Model {
	function __construct() {
        parent::__construct();
    }

    public function imageUpload($image,$folder_name)
    {
        //print_r($image['tmp_name']);die();
    	if(isset($image) && !empty($image))
		{
			$len = 8;
			$randno =$this->radomno($len);
            $imageame  = $randno.$image['name'];
			$s  = $image['tmp_name'];
			$nn = preg_replace('/\s*/m', '',$imageame);
            $d  = $folder_name.'/'.$nn;
            move_uploaded_file($s,$d);
            return $imageame;
	    }
    }

    public function radomno($len)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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

    public function checkRows($table_name,$where)
    {
        return $this->db->get_where($table_name,$where)->num_rows();
    }

    public function getSingleRecord($table_name,$where)
    {
        return $this->db->get_where($table_name,$where)->row();
    }

    public function getMultipleRecord($table_name,$where,$orderby)
    {
    	$this->db->order_by($orderby);
        return $this->db->get_where($table_name,$where)->result();
        //print_r($this->db->last_query());die();
    }

     

    public function getRequestAndCreatRides($table_name,$user_id,$getType)
    {
        $this->db->select('ride.*,Riderequest.request_type,ride_status,request_id');
        $this->db->from('ride');
        $this->db->join('Riderequest','ride.ride_id=Riderequest.ride_id');
        $this->db->where(array('ride.user_id'=>$user_id,'ridetype'=>$getType));
        $this->db->order_by('ride_id','DESC');
        return $this->db->get()->result();
        //print_r($this->db->last_query());
    }    

    public function keychange($user_detail)
    {
        $image = base_url().'images/'.$user_detail->image;
        foreach($user_detail as $key => $val)
        {
            if ($key=='image' && $user_detail->image_type==0)
            //if ($key=='image')
            {
                $user_detail->image = $image;
            }
            if($key=='vichleimage')
            {
                $user_detail->vichleimage = base_url().'vechileImage/'.$user_detail->vichleimage;
            }
            if($key=='licence_image')
            {
               $user_detail->licence_image = base_url().'licenceImage/'.$user_detail->licence_image;
            }
            if(isset($user_detail->password))
            {unset($user_detail->password); }
        }
        return $user_detail;
    }

    public function loginViaMedia($media_id,$email,$login_type,$devicetoken,$device_type)
    {
        $where       = array('email'=>$email);
        $table_name  = 'users';
        $loginCheck  = $this->checkRows($table_name,$where);
        if($loginCheck>0)
        {
            $userData    = $this->getSingleRecord($table_name,$where);
            $id          = $userData->id;
            $fb_id       = $userData->fb_id;
            //$googleId    = $userData->google_id;
            //Update Login Device_token and Device_type
            $checkWhere  = array('id'=>$id);
            $updata      = array('device_token'=>$devicetoken,'device_type'=>$device_type);
            $this->updateRecord($checkWhere,$table_name,$updata);
            
            if($login_type==1 && ($fb_id=='' or $fb_id==0))
            {
                $updata = array('fb_id'=>$media_id);
                $this->updateRecord($checkWhere,$table_name,$updata);
                return $this->getSingleRecord($table_name,$checkWhere);
            }
            /*elseif($login_type==2 && ($googleId=='' or $googleId==0))
            {
                $updata = array('google_id'=>$media_id);
                $this->updateRecord($checkWhere,$table_name,$updata);
                return $this->getSingleRecord($table_name,$checkWhere);
            }*/
            elseif($login_type==1 && $fb_id==$media_id)
            {
                return $this->getSingleRecord($table_name,$checkWhere);
            }
            /*elseif($login_type==2 && $googleId==$media_id)
            {
                return $this->getSingleRecord($table_name,$checkWhere);
            }*/
            else
            {
                $respons["success"] = 0;
                $respons["error"]   = 4;
                $respons["message"] = "Invalid request";
                $response["data"]   = '';
                echo json_encode($respons);
                exit();
            }
        }
        else
        {
            $respons["success"] = 0;
            $respons["error"]   = 3;
            $respons["message"] = "User does not exist";
            $response["data"]   = '';
            echo json_encode($respons);
            exit();
        }
    }

    public function updateRecord($checkWhere,$table_name,$updata)
    {
        $this->db->where($checkWhere);
        return $this->db->update($table_name,$updata);          
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
            $message = $this->load->view('AdminForgetPass',$data,true);
            $config=array(
            'charset'=>'utf-8',
            'wordwrap'=> TRUE,
            'mailtype' => 'html'
            );
            $this->load->library('email');
            $this->email->set_newline("\r\n");
            $this->email->initialize($config);
            $this->email->from('kotchi@gmail.com','kotchi'); 
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

    // Fetch records    
    public function getDatas($rowno,$rowperpage,$search="",$table_name,$where) {
        //echo $table_name;die();
        $this->db->limit($rowperpage,$rowno);
        $response = $this->db->get_where($table_name,$where)->result_array();
        return $response;
        //print_r($this->db->last_query());die();
    }



    // Fetch records
    public function getData($rowno,$rowperpage,$search="",$table_name,$where) {
                
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where);
        if($search != ''){
            $this->db->like('name', $search);
            //$this->db->or_like('email', $search);
        }
        $this->db->limit($rowperpage, $rowno);  
        $query = $this->db->get();        
        return $query->result();
    }

    // Select total records
    public function getrecordCount($search = '',$table_name,$where) {        

        $this->db->select('count(*) as allcount');
        $this->db->from($table_name);
        $this->db->where($where);
      
        if($search != ''){
            $this->db->like('name', $search);
            //$this->db->or_like('email', $search);
        }
        $query = $this->db->get();
        $result = $query->result_array();
        return $result[0]['allcount'];
    }


}
?>