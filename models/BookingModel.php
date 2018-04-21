<?php
class BookingModel extends CI_Model {
	function __construct() {
        parent::__construct();
    }

    public function GetDrivingDistanceAndTime($lat1, $lat2, $long1, $long2)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=en-EN";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        //echo json_encode($response_a);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];
        return array('distance' => $dist, 'travel_time' => $time);
    }

    public function searchNearByDriver($fromLat,$fromLong,$date,$time,$service_type_id)
    {
        $searchRange = $this->checkNightTime($date,$time);
        //echo $searchRange;
        $this->db->select("driver_live_location.*,( 3959 * acos( cos( radians($fromLat) ) * cos( radians(`latitude`) ) * cos( radians( `longitude` ) - radians($fromLong) ) + sin( radians($fromLat) ) * sin( radians( `latitude` ) ) ) ) AS distance");
        $this->db->from('driver_live_location');
        $this->db->join('users','driver_live_location.user_id=users.id');
        $this->db->join('vehicle_servicetype','users.id=vehicle_servicetype.driver_id');
        $this->db->where('vehicle_servicetype.service_type_id',$service_type_id);
        $this->db->where(array('users.user_type'=>1,'online_status'=>'online','power_status'=>'on','activeStatus'=>'Active'));        
        $this->db->having('distance <= ',$searchRange);  
        $this->db->order_by('distance');
        //print_r($this->db->last_query());
        $res=$this->db->get()->result();
        return $res;
        //echo json_encode($res);
    }

    public function searchDriver($fromLat,$fromLong,$date,$time,$service_type_id)
    {
        $searchRange = $this->checkNightTime($date,$time);
        //echo $searchRange;
        $this->db->select("driver_live_location.*,servicetype.*,driver_live_location.address as liveaddress,users.address as hoemAddress,users.*,( 3959 * acos( cos( radians($fromLat) ) * cos( radians(`latitude`) ) * cos( radians( `longitude` ) - radians($fromLong) ) + sin( radians($fromLat) ) * sin( radians( `latitude` ) ) ) ) AS distance");
        $this->db->from('driver_live_location');
        $this->db->join('users','driver_live_location.user_id=users.id');
        $this->db->join('vehicle_servicetype','users.id=vehicle_servicetype.driver_id');
        $this->db->join('servicetype','vehicle_servicetype.service_type_id=servicetype.typeid');
        $this->db->where(array('vehicle_servicetype.service_type_id'=>$service_type_id,"servicetype.typeid"=>$service_type_id));
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

    public function getTripRequest($driver_id)
    {
        $this->db->select('booking.*,booking_fare.*,servicetype.*,users.name,email,mobile,image,image_type');
        $this->db->from('booking');
        $this->db->join('booking_fare','booking.booking_id=booking_fare.booking_id');
        $this->db->join('users','booking.customer_id=users.id');
        $this->db->join('servicetype','booking.service_typeid=servicetype.typeid');
        $this->db->where(array('booking.driver_id'=>$driver_id,'booking_status'=>0));
        return $this->db->get()->row();
    }

    /*public function searchToAddress($rideId,$toAddLat,$toAddLng)
    {
        $this->db->select("ride.*,users.*,( 3959 * acos( cos( radians($toAddLat) ) * cos( radians(`toLat`) ) * cos( radians( `toLng` ) - radians($toAddLng) ) + sin( radians($toAddLat) ) * sin( radians( `toLat` ) ) ) ) AS distance");
        $this->db->from('ride');
        $this->db->join('users','ride.user_id=users.id');
        //$this->db->join('workarea','workarea.user_id=registration.id');
        $this->db->where(array('ride_id'=>$rideId));
        $this->db->having('distance <= ',10);  
        $this->db->order_by('distance');
        return $this->db->get()->row();
    }*/

    

    


	
}
?>