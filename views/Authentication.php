<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class authentication extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function registernewuser($post) {

        return $query = $this->db->insert('user', $post);
    }

    public function select($table, $wherearr) {

        return $query = $this->db->get_where($table, $wherearr);
        //return $this->db->last_query();
        //return $query->result(); 
    }

    public function select_orders() {

        $res = $this->db->query('SELECT o.*,dr.message,u.firstname,u.lastname,ud.phone,ud.firstname as driver_first,ud.lastname as driver_last FROM `orders` o
                left join user u on o.user_id=u.user_id left join user ud on o.driver_id=ud.user_id 
                left join driver_report dr on o.order_id=dr.order_id AND o.driver_id=dr.driver_id
                order by order_id DESC')->result();
				
        /*$currentDate = date('Y-m-d');
        foreach ($res as $key => $value) {
            $dateArr = explode('/', $value->delivery_date);
            $d = $dateArr[2] . "-" . $dateArr[1] . "-" . $dateArr[0] . " 11:59pm"; 
            $date1 = date_create($d);
            $date2 = date_create($currentDate);
            $diff = date_diff($date2, $date1);
            //$remaining = $diff->format("%R%a days");
$remaining = $diff->format("%R%a days and %H hours and %i minutes");
            $findme = '-';
           $pos=strpos($remaining, $findme);
            if ($pos === false) {
                $r[$key]->remaining_days = str_replace("+", "", $remaining);
            } else {
				
                $r[$key]->remaining_days = 'Expired';
            }
        }
		return $r; */
        
        return $res;
        //return $this->db->last_query();
        //return $query->result(); 
    }

    public function filter_orders() {
        $where = 'where 1=1';

        if ($this->input->post() && $this->input->post('status') != 'undefined' && $this->input->post('status') != '0') {
            $status = $this->input->post('status');
            $where .= ' AND delivery_status="' . $status . '"';
        }
        if ($this->input->post() && $this->input->post('filterDate') != 'undefined' && $this->input->post('filterDate') != '0' && $this->input->post('filterDate') != '') {
            $filterDate = $this->input->post('filterDate');
            $where .= ' AND delivery_date="' . $filterDate . '"';
        }

        $res = $this->db->query('SELECT o.*,u.firstname,u.lastname,ud.phone,ud.firstname as driver_first,ud.lastname as driver_last FROM `orders` o left join user u on o.user_id=u.user_id left join user ud on o.driver_id=ud.user_id '. $where . ' order by order_id desc')->result();
        
        $result = '';
        $result.='<table class="table table-bordered table-striped editable-datatable datatable responsive align-middle bordered" id="order_grid">';
        $result.='<thead><tr><th>Order Id </th><th> Delivery Date </th> <th style="min-width: 120px !important;">Item Desc. </th> ';
        $result.='<th> Delivery Cost </th><th> Remaining Time </th><th>Customer Name </th><th>Pickup address </th><th>Dropoff address </th><th>Delivery status </th><th>Driver Details </th><th>Assign Vehicle </th><th>Edit </th><tbody id="orderBody">';
        $currentDate = date('Y-m-d');
        
        foreach ($res as $key => $value) {
if($value->delivery_type=='2HOUR'){
                $result .=" <tr style='background-color: red;color: #fff;'>";
                 }elseif($value->delivery_type=='4HOUR'){ 
                   $result .=" <tr  style='background-color: blue;color: #fff;'>";
                 }elseif($value->delivery_type=='SAMEDAY'){ 
                   $result .=" <tr style='background-color: green;color: #fff'>";
                 }else{
                   $result .=" <tr>";
                 }
            $result .=" <td>" . $value->order_id . "</td>";
            $result .= "<td>" . $value->delivery_date . "</td>";
            $result .= "<td>" . "Height : " . $value->parcel_height . " cm<br>" . "Width : " . $value->parcel_width . " cm<br>";
            $result .= "Length : " . $value->parcel_lenght . " cm<br>" . "Weight : " . $value->parcel_weight . " gm</td>";
            $result .= "<td>" . $value->delivery_cost . "</td>";

            $dateArr = explode('/', $value->delivery_date);
            $d = $dateArr[2] . "-" . $dateArr[1] . "-" . $dateArr[0];
            $date1 = date_create($d);
            $date2 = date_create($currentDate);
            $diff = date_diff($date2, $date1);
            $remaining = $diff->format("%R%a days");
            $findme = '-';
            $pos = strpos($remaining, $findme);
            if ($pos === false) {
                $remainingDays = str_replace("+", "", $remaining);
            } else {
                $remainingDays = 'Expired';
            }

            $result .= "<td>" . $remainingDays . "</td>";


            $pickupaddr = $value->pickup_house_number;
            $pickupaddr = $value->pickup_street_name ? $pickupaddr . "," . $value->pickup_street_name : $pickupaddr;
            $pickupaddr = $value->pickup_street_suff ? $pickupaddr . "," . $value->pickup_street_suff : $pickupaddr;
            $pickupaddr = $value->pickup_suburb ? $pickupaddr . "," . $value->pickup_suburb : $pickupaddr;
            $pickupaddr = $value->pickup_state ? $pickupaddr . "," . $value->pickup_state : $pickupaddr;
            $pickupaddr = $value->pickup_postcode ? $pickupaddr . "," . $value->pickup_postcode : $pickupaddr;
            $pickupaddr = $value->pickup_country ? $pickupaddr . "," . $value->pickup_country : $pickupaddr;

            $addr = $value->dropoff_house_number;
            $addr = $value->dropoff_stree_name ? $addr . "," . $value->dropoff_stree_name : $addr;
            $addr = $value->dropoff_street_suff ? $addr . "," . $value->dropoff_street_suff : $addr;
            $addr = $value->dropoff_suburb ? $addr . "," . $value->dropoff_suburb : $addr;
            $addr = $value->dropoff_state ? $addr . "," . $value->dropoff_state : $addr;
            $addr = $value->dropoff_postcode ? $addr . "," . $value->dropoff_postcode : $addr;
            $addr = $value->dropoff_country ? $addr . "," . $value->dropoff_country : $addr;


            $result .= "<td>" . $value->firstname . " " . $value->lastname . "</td>";
            $result .= "<td>" . $pickupaddr . "</td>";
            $result .= "<td>" . $addr . "</td>";
            $result .= "<td>";
            if ($value->delivery_status == 4) {
                $result .= "<span class='label label-warning'>Delivered</span>";
            } else if ($value->delivery_status == 3) {
                $result .= "<span class='label label-success'>Canceled</span>";
            } else if ($value->delivery_status == 1) {
                $result .= "<span class='label label-danger'>Pending</span>";
            }else if ($value->delivery_status == 6) {
                $result .= "<span class='label label-danger'>Picked</span>";
            }else if ($value->delivery_status == 5) {
                $result .= "<span class='label label-danger'>Paid</span>";
            }
            $result .= "</td>";
             $result .= "<td>";
            if($value->driver_first!=''){
                       $result .= "Name :". $value->driver_first." ".$value->driver_last."<br>";
                       $result .= "Number :". $value->phone;
                         }else{
                        $result .= '-';
                        }
              $result .= "</td>";          
            $result .= "<td><a href='" . base_url('admin/assignvehicle') . "/" . $value->order_id . "' class=edit id='" . $value->order_id . "'>Assign</a>";
            $result .= $value->vehicle_type ? '(' . $value->vehicle_type . ')' : '';
            $result .= " </td>";
            $result .= " <td><a href='" . base_url('admin/editorder') . "/" . $value->order_id . "' class=edit>Edit</a> </td>";
            $result .= " </tr>";
        }
        $result.="</tbody></table>";
        return $result;

        //return $this->db->last_query();
        //return $query->result(); 
    }

    public function search($name) {

        // echo "SELECT * FROM `user` WHERE `firstname` ='$name' or `lastname`='$name'";
        return $this->db->query("SELECT * FROM `user` WHERE `firstname` ='$name' or `lastname`='$name'")->result();
        //return $this->db->last_query();
        //return $query->result(); 
    }

    public function select_orders_update($where_arr) {

        $user_id = $where_arr['user_id'];
        $order_id = $where_arr['order_id'];
        return $this->db->query("SELECT * FROM `orders` as u inner join order_delivery_status as o on o.order_id=u.order_id inner join user as usr on usr.user_id=o.user_id where u.order_id=$order_id and usr.user_id=$user_id  ")->result();
        //return $this->db->last_query();
        //return $query->result(); 
    }

    public function select_products() {
        return $this->db->query("SELECT * FROM `products` where status = 'Active' ")->result();
        //return $this->db->last_query();
        //return $query->result(); 
    }
    public function select_order_products($id) {
        return $this->db->query("SELECT * FROM `order_products` where order_id='$id' AND status = 'Active' ")->result();
        //return $this->db->query("SELECT orders.shop_name,shop_address,shop_exp_price,shop_item_description,shop_item_quantity,driver_id,order_products.* FROM `order_products` INNER JOIN `orders` on orders.order_id=order_products.order_id WHERE order_products.order_id = '$id' AND order_products.status='ACTIVE' ")->result();
        //return $this->db->last_query();
        //return $query->result(); 
    }
    public function select_order_shop_details($id) {
        return $this->db->query("SELECT * FROM `orders` where order_id='$id'")->result();       
    }

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
    public function select_data($table_name, $where_arr = '', $order_by = '', $limit1 = '', $limit2 = '') {

        $this->db->select('*');
        $this->db->from($table_name);

        if (is_array($where_arr)) {
            $this->db->where($where_arr);
        }
        if (is_array($order_by)) {
            foreach ($order_by as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
        if ($limit1 != '') {
            $this->db->limit($limit2, $limit1);
        }
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $data = $result->result();

            return $data;
        } else {
            return $result->num_rows();
        }
    }

    public function insert($table, $post) {
        $query = $this->db->insert($table, $post);
        return $insert_id = $this->db->insert_id();
    }

    public function update($table, $wherearr, $updatearr) {

        $this->db->where($wherearr);
        return $this->db->update($table, $updatearr);
//        return $this->db->last_query();
    }

    public function delete($table, $id) {

        $this->db->where($id);
        return $this->db->delete($table);
//        return $this->db->last_query();
    }

    public function checkemail($email) {
        $query = $this->db
                ->select('*')
                ->where('email', $email)
                ->get('signup');
        return $query->result();
    }

    public function checkmobile($otp) {
        $query = $this->db
                ->select('*')
                ->where('otp', $otp)
                ->get('signup');
        return $query->row();
    }

    public function mobileverified($status, $id) {
        $data = array(
            'status' => $status
        );
        $this->db->where('id', $id);
        $this->db->update('signup', $data);
    }

    public function resendotp($otp, $newotp) {
        $data = array(
            'otp' => $newotp,
            'reg_date' => date('Y-m-d H:i:s')
        );
        return $this->db->where('otp', $otp)
                        ->update('signup', $data);
    }

    public function logincheck($email, $password) {
        $query = $this->db
                ->select('*')
                ->where('email', $email)
                ->where('password', $password)
                ->get('signup');

        return $query->row();
    }

    public function finddatawithemail($email) {
        $query = $this->db
                ->select('*')
                ->where('email', $email)
                ->get('signup');
        return $query->row();
    }

    public function sendpasswordlink($email, $newotp) {
        $data = array(
            'otp' => $newotp,
            'reg_date' => date('Y-m-d H:i:s')
        );
        return $this->db->where('email', $email)
                        ->update('signup', $data);
    }

    public function changepassword($password, $id) {
        $data = array(
            'password' => $password
        );
        return $this->db->where('id', $id)
                        ->update('signup', $data);
    }
    public function select_minimum()
    {
        $query=$this->db->get('minimum_charges');
        return $query->row();
    }
    public function select_standard()
    {
        $query=$this->db->get('standard_parcel');
        return $query->row();
    }
    public function select_setting()
    {
        $query=$this->db->get('setting');
        return $query->row();
    }
    public function update_minimumcharges($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('minimum_charges', $arr);
    }
    public function update_standardparcel($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('standard_parcel', $arr);
    }
    public function update_motorbike($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_car($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_van($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_truck($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_endtime($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_driverpercentage($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    
    public function update_mparcelsize($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_cparcelsize($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_vparcelsize($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_tparcelsize($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    public function update_pdmin($arr, $id)
    {
        $this->db->where('id',$id);
        return $this->db->update('setting', $arr);
    }
    
    public function select_driver_location1()
    {
       /* $this->db->select('firstname,lastname,user_id,latitude,longitude,lastname,suburb');
        $this->db->from('user');
        $this->db->where('login_status',"yes");
        $this->db->where('user_type',2);
        $query=$this->db->get();
        $data1=$query->result();   */
        $this->db->select('user.*');
	$this->db->from('user');
	$this->db->join('orders', 'user.user_id = orders.driver_id', 'left'); 	 
	$this->db->where('user.user_type', 2);
	$this->db->where('user.login_status', "yes");
	$query = $this->db->get();
	$data1= $query->result(); 
	//print_r($data1);die;
	//$data11='';
  
		foreach($data1 as $row)
		{
		
		$data[]=array("lat"=>$row->latitude,"lng"=>$row->longitude,"description"=>$row->sub."<div class='shub'><img src='http://freebizoffer.com/apptech/pick&drop/courierapp/api/upload/$row->profile_image' style='float:left;margin-right:10px;    border-radius: 100%;
    border: 1px solid #fff;
    margin-left: 5px;
    margin-top: 5px;
    margin-bottom: 0px;' width='50' height='50'></td><td>Driver-". $row->firstname ."". $row->lastname ."<br>Driver Id-". $row->user_id ."<br>Type -". $row->vehicle_type,"image"=>"https://cdn4.iconfinder.com/data/icons/car-silhouettes/1000/city-car-512.png","vehicle_type"=>$row->vehicle_type);
		}
		//print_r($data);die;
        return $data;
    }
    public function select_driver_vtype1()
    {
       $this->db->select('vehicle_type');
        $this->db->from('user');
        $this->db->where('login_status',"yes");
        $this->db->where('user_type',2);
        $query=$this->db->get();
        $data1=$query->result();
        $data=array();
		foreach($data1 as $row)
		{

		 if($row->vehicle_type =='Car'){$s='http://freebizoffer.com/apptech/pick&drop/courierapp/courierapp/driver.png';}
		 if($row->vehicle_type =='Van'){$s='http://freebizoffer.com/apptech/pick&drop/courierapp/courierapp/driver.png';}
		 if($row->vehicle_type =='Bike'){$s='http://freebizoffer.com/apptech/pick&drop/courierapp/courierapp/driver.png';}
		 if($row->vehicle_type =='Truck') {$s='http://freebizoffer.com/apptech/pick&drop/courierapp/courierapp/driver.png';}		 	
		}
		//print_r($s);die;
        return $s;
    }
    
    public function select_delivery_location1()
    {       
        $this->db->select('*');
    	$this->db->from('orders');
    	//$this->db->join('orders', 'user.user_id = orders.driver_id', 'left'); 	 
    	//$this->db->where('user.user_type', 2);
    	//$this->db->where('user.login_status', "yes");
    	$query = $this->db->get();
    	$data1= $query->result(); 
    	//print_r($data1);die;	
		foreach($data1 as $row)
		{		
		  $data[]=array("lat"=>$row->pickup_lat,"lng"=>$row->pickup_long,"description"=>$row->sub."<div class='shub'></td><td>Delivery Id-". $row->order_id."<br>Type -". $row->delivery_type."<br>Pickup-". $row->pickup_suburb."<br>Drop off-".$row->dropoff_suburb ,"delivery_type"=>$row->delivery_type,"vehicle_type"=>$row->vehicle_type);
		}
		//print_r($data);die;
        return $data;
    }
    
    
    
    /*******************new test*****************************/
    
    
     public function select_driver_location()
    {
        $this->db->select('user.*');
    	$this->db->from('user');
    	$this->db->join('orders', 'user.user_id = orders.driver_id', 'left'); 	 
    	$this->db->where('user.user_type', 2);
    	$this->db->where('user.login_status', "yes");
    	$query = $this->db->get();
    	$data1= $query->result(); 
    	//print_r($data1);die;
    	//$data11='';
      
		/*foreach($data1 as $row)
		{		
		$data[]="'<div>Driver Id-".$row->user_id."<br>Driver -".$row->firstname ."". $row->lastname ."<br>Type -".$row->vehicle_select_delivery_locationtype."</div>'".','.$row->latitude.','.$row->longitude;
			
		}*/
        return $data1;
    }
    
    public function select_driver_vtype()
    {
       $this->db->select('vehicle_type');
        $this->db->from('user');
        $this->db->where('login_status',"yes");
        $this->db->where('user_type',2);
        $query=$this->db->get();
        $data1=$query->result();
        $data=array();
		foreach($data1 as $row)
		{
			$type[]=array("type"=>$row->vehicle_type);
			//echo $row->vehicle_type;
		}
        return $s;
    }
    
    public function select_delivery_location()
    {       
        $this->db->select('*');
    	$this->db->from('orders');
    	//$this->db->join('orders', 'user.user_id = orders.driver_id', 'left'); 	 
    	//$this->db->where('user.user_type', 2);
    	//$this->db->where('user.login_status', "yes");
    	$query = $this->db->get();
    	$data1= $query->result(); 
    	//print_r($data1);die;	
		/*foreach($data1 as $row)
		{
		
		$data[]=array("lat"=>$row->pickup_lat,"lng"=>$row->pickup_long,"description"=>$row->sub."<div class='shub'></td><td>Delivery Id-". $row->order_id."<br>Type -". $row->delivery_type."<br>Pickup-". $row->pickup_suburb."<br>Drop off-".$row->dropoff_suburb ,"delivery_type"=>$row->delivery_type,"vehicle_type"=>$row->vehicle_type);
		}
		//print_r($data);die;*/
        return $data1;
    }
    
    
    
    
    /******************end *********************************/

}

?>
