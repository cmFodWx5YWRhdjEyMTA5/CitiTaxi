<?php
class BookingModel extends CI_Model {
	function __construct() {
        parent::__construct();
    }

    public function searchDriver($fromLat,$fromLong,$date,$time)
    {
        $searchRange = $this->checkNightTime($date,$time);
        //echo $searchRange;
        $this->db->select("driver_live_location.*,driver_live_location.address as liveaddress,users.address as hoemAddress,users.*,( 3959 * acos( cos( radians($fromLat) ) * cos( radians(`latitude`) ) * cos( radians( `longitude` ) - radians($fromLong) ) + sin( radians($fromLat) ) * sin( radians( `latitude` ) ) ) ) AS distance");
        $this->db->from('driver_live_location');
        $this->db->join('users','driver_live_location.user_id=users.id');
        $this->db->where(array('users.user_type'=>1,'online_status'=>'online','power_status'=>'on','activeStatus'=>'Active'));        
        $this->db->having('distance <= ',$searchRange);  
        $this->db->order_by('distance');
        //print_r($this->db->last_query());
        $res=$this->db->get()->row();
        return $res;
        //echo json_encode($res);
    }

    public function checkNightTime($date,$time)
    {
        $starttime  = strtotime($date.' 10:00 pm');  //date night time start
        $endtime    = strtotime("+1 day",strtotime($date.' '.$time));  //next date night end time(morning time)
        $time       = strtotime($date.' '.$time);  //seraching time          
        if($time>$starttime && $time<$endtime)
        {
            return 5;
        }
        else
        {
            return 2;
        }
    }

    public function getNearbyDriver($current_lat,$current_long)
    {
        $this->db->select("driver_live_location.*,driver_live_location.address as liveaddress,users.address as hoemAddress,users.*,( 3959 * acos( cos( radians($fromLat) ) * cos( radians(`latitude`) ) * cos( radians( `longitude` ) - radians($fromLong) ) + sin( radians($fromLat) ) * sin( radians( `latitude` ) ) ) ) AS distance");
        $this->db->from('driver_live_location');
        $this->db->join('users','driver_live_location.user_id=users.id');
        $this->db->where(array('users.user_type'=>1,'online_status'=>'online','power_status'=>'on','activeStatus'=>'Active'));        
        $this->db->having('distance <= ',2);  
        $this->db->order_by('distance');
        //print_r($this->db->last_query());
        $res=$this->db->get()->result();
        return $res;
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

    


	
}
?>