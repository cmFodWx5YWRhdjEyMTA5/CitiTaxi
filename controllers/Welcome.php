<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct() 
    {
		parent::__construct();	
        $this->load->model('AuthModel');	
	
	}

	public function Index()
	{
		//$data = new stdclass();
		if(isset($_POST['login']))
		{
			extract($_POST);
			$table_name ="admin";
			$where=array('email'=>$loginemail,'password'=>$loginpassword);
			$check=$this->AuthModel->checkRows($table_name,$where);
			if($check>0)
			{
				$res  = $this->AuthModel->getSingleRecord($table_name,$where);
				//print_r($res);die();
				$user_data = array('id'=>$res->id, "email" =>$res->email,"name" =>$res->name, 'image'=>$res->image, 'status'=>$res->status );
	            $this->session->set_userdata($user_data);
	            
	            redirect('Home/analytics');
			}
			else
			{
				$data['error']='1';
				$data['message']='Please enter correct Email and Password';
				$this->load->view('login',$data);
			}
		}	
		else
		{
			if($this->session->userdata('email') != '')
			{
				redirect('Home/analytics');
			}
			else
			{				
				$this->load->view('login');
			}
		}
	}

	public function ForgetPassword()
	{
		if(isset($_POST['email']) && $_POST['email']!='')
	    {
			$email=$_POST['email'];
			$table_name = "admin";
			$res=$this->AuthModel->forget_password($table_name,$email);
			if($res==0)
			{
				$response["success"]        = 1;
				$response['message']		= "Please Check your Email inbox";
				$response['Email ']			= $email;
				$this->load->view('forgetPassword',$response);		
			}
			elseif($res==1)
			{
				$response["error"]          = 2;    
	            $response["success"]        = 0;
				$response['message']		= "Error occur! Please try again";
				$this->load->view('forgetPassword',$response);			
			}
			else
			{
				$response['error']=1;
	            $response['success']=0;
	            $response['message']="This email Id is not found! Please enter correct email id";
	            $this->load->view('forgetPassword',$response);
			}
	    }
	    else
	    {	    	
			$this->load->view('forgetPassword');
	    }
	}

	public function recover_password()         //for  admin
	{
		$data=new stdClass();
		$email=$_GET['email'];
		if($this->input->post('change'))
		{
			extract($_POST);
			$enpass= md5($new_password);
			$res=$this->Admin_model->recover_password($enpass,$email);
			if($res!=false)
			{
				$data->error=0;
				$data->success=1;
				$data->email=$email;
				$data->message="Password update successfull";
				$this->load->view('recover_password',$data);
			}
			else
			{
				$data->error=0;
				$data->success=1;
				$data->email=$email;
				$data->message="Password not update, Please Try again";
				$this->load->view('recover_password',$data);
			}
		}
		else
		{
			$data->email=$email;
			$this->load->view('recover_password',$data);
		}
	}

	public function reset_password()     //for user
	{
		$data=new stdClass();
		$email=$_GET['email'];
		if($this->input->post('recover'))
		{
			extract($_POST);
			$enpass= md5($new_password);
			$res= $this->Admin_model->userdetail($email);
			$upass= $new_password;  
			$cp =$res->upass;  
			$user_qbid =$res->qb_id;      
			$res=$this->Admin_model->recover_userpassword($enpass,$upass,$email);
			if($res!=false)
			{	
				$data->success=1;
				$data->email=$email;
				$data->message="Password Reset successfull";
				$this->load->view('recover_userpassword',$data);
			}
			else
			{
				$data->error=1;
				$data->email=$email;
				$data->message="Password not Reset, Please Try again";
				$this->load->view('recover_userpassword',$data);
			}
		}
		else
		{
			$data->email=$email;
			$this->load->view('recover_userpassword',$data);
		}
	}

	public function EmailTemplate()
	{
		$data['email'] = 'shubhamj@gmail.com';
		$this->load->view('forget_passwordTemp',$data);
	}


	public function logout()
	{		
		$this->session->sess_destroy();
		$this->load->view('login');		
	}

	public function websitepage($pageid)
	{		
		if($pageid!='')
		{
			$pagecontent  = $this->AuthModel->getSingleRecord('website_pages',array('page_id'=>$pageid));
			if(!empty($pagecontent))
			{
				$data['pagedata'] =$pagecontent;
				$this->load->view('about-us',$data);		
			}
			else
			{
				redirect('Welcome/unauthrised');				
			}			
		}
		else
		{
			redirect('Welcome/unauthrised');
		}
	}

	public function checkdriverRequest()  //to check new driver request
    {
        $newdrivercount = $this->AuthModel->checkRows('users',array('signup_status'=>'incomplete','seen_status'=>0));
        print_r($newdrivercount);

    }

    public function checkpendingBooking()  //to check later booking request
    {
        $where  = "((booking_status=9 or booking_status=2) and booking_type='later' and seen_status=0)";
        $newdrivercount = $this->AuthModel->checkRows('booking',$where);
        print_r($newdrivercount);

    }

    public function checkNewMessage()  //to check later booking request
    {        
        $newdrivercount = $this->AuthModel->checkRows('support',array('seen_status'=>0));
        print_r($newdrivercount);

    }

	public function unauthrised()
	{
		$data['heading']='404 Page Not Found';
		$data['message']='The page you requested was not found.';
		$this->load->view('errors/html/error_404',$data);
	}

	/*$("#btn").click(function(){
        var geocoder =  new google.maps.Geocoder();
        geocoder.geocode( { 'address': 'miami, us'}, function(results, status) {
          if (status == google.maps.GeocoderStatus.OK) {
            alert("location : " + results[0].geometry.location.lat() + " " +results[0].geometry.location.lng()); 
          } else {
            alert("Something got wrong " + status);
          }
        });
    });*/

    public function dispatch_login()
	{
		//$data = new stdclass();
		if(isset($_POST['login']))
		{
			extract($_POST);
			$table_name ="dispatch";
			$where=array('email'=>$loginemail,'password'=>md5($loginpassword));
			//echo $loginpassword;
			$check=$this->AuthModel->checkRows($table_name,$where);
			//print_r($this->db->last_query());die();
			if($check>0)
			{
				$res  = $this->AuthModel->getSingleRecord($table_name,$where);
				//print_r($res);die();
				$user_data = array('dis_id'=>$res->dispatcher_id,"dis_email"=>$res->email,"dis_name"=>$res->name,'dis_image'=>$res->image,'dis_country'=>$res->country,'dis_city'=>$res->city);
	            $this->session->set_userdata($user_data);	            
	            redirect('Dispatch');
			}
			else
			{
				$data['error']='1';
				$data['message']='Please enter correct Email and Password';
				$this->load->view('dispatch/login',$data);
			}
		}	
		else
		{
			if($this->session->userdata('dis_email') != '')
			{
				redirect('Dispatch');
			}
			else
			{				
				$this->load->view('dispatch/login');
			}
		}
	}
	public function dispatch_logout()
	{		
		$keys = array('dis_id','dis_email','dis_name','dis_image','dis_country','dis_city');
		$this->session->unset_userdata($keys);
		/*$this->session->unset_userdata('dis_id');
        $this->session->unset_userdata('dis_email');
        $this->session->unset_userdata('dis_name');*/

        // Set Message
        $this->session->set_flashdata('logged_out','You have been Logged Out');        
		redirect('Welcome/dispatch_login');	
	}

	public function sub_admin_login()        //customer details
    {       
        if(isset($_POST['login']))
        {
            extract($_POST);
            $table_name ="fleets";
            $where=array('fleet_email'=>$loginemail,'password'=>$loginpassword);
            $check=$this->AuthModel->checkRows($table_name,$where);
            if($check>0)
            {
                $res  = $this->AuthModel->getSingleRecord($table_name,$where);
                //print_r($res);die();
                $user_data = array('fleet_id'=>$res->fleet_id, "fleet_email" =>$res->fleet_email,"fleet_name" =>$res->fleet_name, 'fleet_image'=>$res->image,'status'=>$res->status);
                $this->session->set_userdata($user_data);
                
                redirect('Sub_admin/drivers');
            }
            else
            {
                $data['error']='1';
                $data['message']='Please enter correct Email and Password';
                $this->load->view('Sub_admin/login',$data);
            }
        }   
        else
        {
            if($this->session->userdata('fleet_email') != '')
            {
                redirect('Sub_admin');
            }
            else
            {
                $this->load->view('Sub_admin/login');
            }
        }
    }

    public function sub_admin_logout()
	{		
		$keys = array('fleet_id','fleet_email','fleet_name','fleet_image','status');
		$this->session->unset_userdata($keys);		
        $this->session->set_flashdata('logged_out','You have been Logged Out');        
		redirect('Welcome/sub_admin_login');	
	}
}