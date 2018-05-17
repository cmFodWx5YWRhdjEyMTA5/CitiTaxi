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
    public function GetDrivingDistance($lat1, $lat2, $long1, $long2)
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
        if(!empty($response_a))  //To handel dealay to get distance or something got error
        {            
            $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
            $distnace = str_replace("km","",$dist);                
            return preg_replace('/\s*/m', '',$distnace);
        }
        else
        {
            return false;
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2,$unit) {               
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $fdist = rad2deg($dist);
      $miles = $fdist * 60 * 1.1515;
      $unit = strtoupper($unit);
      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
          }
    }


    public function searchNearByDriver($fromLat,$fromLong,$date,$time,$service_type_id)  //continue check available drivers
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

    public function oldsearchDriver($fromLat,$fromLong,$date,$time,$service_type_id)   //in booking api
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
        //$res=$this->db->get()->result();
        //echo $res->destination_status;
        //echo json_encode($res);die();
        return $res;
        //echo json_encode($res);
    }

    public function searchDriver($fromLat,$fromLong,$date,$time,$service_type_id,$driver_id)   //in booking api
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
        $this->db->where(array('users.id'=>$driver_id));        
        $this->db->having('distance <= ',$searchRange);  
        $this->db->order_by('distance');
        $res=$this->db->get()->row();
        //print_r($this->db->last_query());die();
        //$res=$this->db->get()->result();
        //echo $res->destination_status;
        //echo json_encode($res);die();
        return $res;
        //echo json_encode($res);
    }

    public function againSearchDriver($fromLat,$fromLong,$date,$time,$service_type_id,$escapeDrivers)  //if need to again search driver
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
        $this->db->where_not_in('driver_live_location.user_id',$escapeDrivers);  //Escape Drivers already searched so we escape
        //print_r($this->db->last_query());
        $res=$this->db->get()->row();
        return $res;
        //echo json_encode($res);
    }

    public function searchDriverDestination($dropLat,$droplong,$driver_id)   //in booking api
    {
        //$searchRange = $this->checkNightTime($date,$time);
        //echo $searchRange;
        $this->db->select("driver_destination.*,(3959 * acos( cos( radians($dropLat) ) * cos( radians(`destination_lat`) ) * cos( radians( `destination_lng` ) - radians($droplong) ) + sin( radians($dropLat) ) * sin( radians( `destination_lat` ) ) ) ) AS distance");
        $this->db->from('driver_destination');              
        $this->db->having('distance <= ',2);    //witin 3km(2mile) range
        $this->db->order_by('distance');
        $this->db->where('driver_id',$driver_id);
        //print_r($this->db->last_query());
        $res=$this->db->get()->row();
        return $res;
        //echo json_encode($res);die();
        //$res=$this->db->get()->result();
        //echo $res->destination_status;
        //echo json_encode($res);die();
    }

    public function checkNightTime($date,$time)
    {
        $starttime  = strtotime($date.' 10:00 pm');  //date night time start
        $endtime    = strtotime("+1 day",strtotime($date.' '.$time));  //next date night end time(morning time)
        $time       = strtotime($date.' '.$time);  //seraching time          
        if($time>$starttime && $time<$endtime)
        {
            return 3.11;  //5km(3.11 mile)
        }
        else
        {
            return 1.3;  //2km(1.3 mile)
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

    public function getTripDriverDetails($customer_id)
    {
        $this->db->select('booking.*,booking_fare.*,vechile_details.*,servicetype.*,users.name,email,mobile,image,image_type,');
        $this->db->from('booking');
        $this->db->join('booking_fare','booking.booking_id=booking_fare.booking_id');
        $this->db->join('users','booking.driver_id=users.id');
        $this->db->join('vechile_details','booking.driver_id=vechile_details.driver_id');
        $this->db->join('servicetype','booking.service_typeid=servicetype.typeid');
        $this->db->where(array('booking.customer_id'=>$customer_id,'booking_status'=>1));
        return $this->db->get()->row();
    }

    public function getTripInvoice($booking_id)
    {
        $this->db->select('booking.*,booking_fare.*,servicetype.*,users.name,email,mobile,image,image_type,vechile_details.*');
        $this->db->from('booking');
        $this->db->join('booking_fare','booking.booking_id=booking_fare.booking_id');
        $this->db->join('users','booking.driver_id=users.id');
        $this->db->join('servicetype','booking.service_typeid=servicetype.typeid');
        $this->db->join('vechile_details','booking.driver_id=vechile_details.driver_id');
        $this->db->where(array('booking.booking_id'=>$booking_id));
        return $this->db->get()->row();
    }

    public function countRecordBetweenDates($fromdate,$todate)
    {
        $this->db->where('order_date >=', $first_date);
        $this->db->where('order_date <=', $second_date);
        return $this->db->get('orders');
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