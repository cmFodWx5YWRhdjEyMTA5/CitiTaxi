<?php
class Communication_model extends CI_Model {
	function __construct() {
        parent::__construct();
    }

    public function sendToPassenger($passenger_id,$message)
    {    
        $customer    = $this->db->get_where('users',array('id'=>$passenger_id))->row();                        
        if(!empty($customer))
        {            
            $device_token   = $customer->device_token;
            $device_type    = $customer->device_type;
            $user_type      = $customer->user_type;
            if($device_type=='1')
            {         
                $this->ios($customerToken,$message); 
            }
            if($device_type=='0')
            {
                $this->androidNotification($device_token,$user_type,$message); 
            }
        }
    }
    
    public function sendToDriver($driver_id,$message)
    {    
        $driver    = $this->db->get_where('users',array('id'=>$driver_id))->row();                          
        if(!empty($driver))
        {         
            $device_token   = $driver->device_token;
            $device_type    = $driver->device_type;
            $user_type      = $driver->user_type;
            if($device_type=='1')
            {         
                $this->ios($device_token,$user_type,$message); 
            }
            if($device_type=='0')
            {
                $this->androidNotification($device_token,$user_type,$message); 
            }
        }
    }

    public function ios($deviceToken,$message,$user_type)
    {
        // $deviceToken = $customerToken;//'0329955742ccbfdb084327f535d3102939eff60b83d90d12b307ed12ed6a0740';
        $deviceToken = "7dec86150a5f1be1e01a5de87a2eb7bea154d9b3d668a873855e585d12d47b23";
        $ctx = stream_context_create();
        if($user_type==1)
        {
            stream_context_set_option($ctx, 'ssl', 'local_cert','pemfile/certificatesdriver.pem');            
        }
        else
        {
            stream_context_set_option($ctx, 'ssl', 'local_cert','pemfile/certificatespassenger.pem');   
        }
        stream_context_set_option($ctx, 'ssl', 'passphrase', '123');
        $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
        {
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        }
        else
        {
            $body['aps'] = array(
            'alert' => array('title'=>'Citi Taxi','body' => $message),
            'sound' => 'default'
            );
            $payload = json_encode($body);
            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
            // Send it to the server
            $result = fwrite($fp, $msg, strlen($msg));
            // Close the connection to the server
            fclose($fp);  
            //echo json_encode($result);    
        }        
    }

    public function androidNotification($device_token,$user_type,$message)
    {
        #API access key from Google API's Console       
        if($user_type==0)   //passenger
        {
            $access_key ="AAAAtPWdT20:APA91bHT9c8NyVV4_WhQwuAtKIapUHFdMm2PUia8TqjOhi01cjjh97y72O80GNy54tPemdTIT9V39ts9fw1ml8NACfWLh4J1MEHCffZDO1oIBXuxY5OQ3qlQ6U0zAxZ2ioohsDuWU73-";
        }
        else   //driver
        {
            $access_key = "AAAAg58pWl4:APA91bFxMCpvd9YwAeZ6D7DS0lyQKhsc0bue3I402uXOUT5aIZEYVj0irkhPehUUz9KZZmnT-c94VLWTaL6Gz5MEyAPLMczPgiI_N-2NSuiNb9crAZupDmiS0qS4LvaMr6og4Qyi8mU5";
        }
        #prep the bundle
        $icon = base_url('/assest/cititaxiIcon.png');
        $msg = array('body'  => $message,'title' => 'Citi Taxi','icon'=>$icon,'sound'=>'mySound');
        $fields = array('to'=>$device_token,'notification'=>$msg);
        $headers = array(
                    'Authorization: key=' .$access_key,
                    'Content-Type: application/json'
                );
        #Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
        $result = curl_exec($ch );
        curl_close( $ch );
        #Echo Result Of FireBase Server
        //echo $result;
    }

    public function mailconfig()
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
    }

    public function SentSignupDetailsToDriver($userdata)
    {
        $data['result'] = $userdata;
        $technician_email = $userdata->email;
        //echo $technician_email;die();
        $config=array(
        'charset'=>'utf-8',
        'wordwrap'=> TRUE,
        'mailtype' => 'html'
        );
        $this->email->set_newline("\r\n");
        $this->email->initialize($config);
        $message  = 'Hi,'."\r\n";
        $message .= 'Congratulations for being a part of our team. Our whole team welcome to you. We are looking forward for the company’s success with you.'."\r\n\r\n";
        //$message .= 'Olá, parabéns por fazer parte da nossa equipe. Desejamos as boas-vindas, muitas realizações e sucesso nesta nova empreitada.'."\r\n\r\n";
        $message .= $this->load->view('signupTemp',$data,true);
        $this->load->library('email');
        $this->email->from('cititaxi@gmail.com','CitiTaxi');
        $this->email->to($technician_email);
        $this->email->subject('Account details');  //New Registration
        $this->email->message($message);
        $this->email->send();        
    }

}

