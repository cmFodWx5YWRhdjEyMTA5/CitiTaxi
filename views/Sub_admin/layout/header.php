<!DOCTYPE html>
<html lang="en">
    <head>      
        <!-- META SECTION -->
        <title><?php if(isset($title)){ echo $title;} else {echo 'CitiTaxi';} ?></title> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />        
        <link rel="SHORTCUT ICON" href="<?php echo base_url('assest/favicon.png');?>" type="image/png" />
        <script>var site_url = '<?php echo site_url();?>Sub_admin';</script>
        <script>var base_url = '<?php echo base_url(); ?>Sub_admin';</script>
        <!-- END META SECTION -->
        <!-- CSS INCLUDE -->     
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assest/css/theme-default.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">
        <!-- EOF CSS INCLUDE -->  
        <script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/jquery/jquery.min.js"></script>       
    </head>
        <style>
            .dt-button{          /* Css for excel button */
            font-size: 13px !important;
            background: brown !important;
            color: white !important;
            }
        </style>
    <body>
        <!-- START PAGE CONTAINER -->
        <div class="page-container">
            <!-- START PAGE SIDEBAR -->
             <!--?php if($this->session->userdata('status')=='admin'){ ?-->
            <div class="page-sidebar">
                <!-- START X-NAVIGATION -->
                <ul class="x-navigation">
                    <li class="xn-logo">
                        <a href="<?php echo site_url();?>/Sub_admin/customers">Dashboard</a>
                        <a href="#" class="x-navigation-control"></a>
                    </li>
                    <li class="xn-profile">
                        <a href="#" class="profile-mini">
                            <?php 
                              
                                $image= $this->session->userdata('fleet_image');
                                
                                $name = $this->session->userdata('name');
                            ?>
                            <img src="<?php echo base_url('fleetimage/'.$image);?>" alt="<?php echo $name; ?>"/>
                        </a>
                        <div class="profile">
                            <div class="profile-image">
                                <img src="<?php echo base_url('fleetimage/'.$image); ?>" alt="<?php echo $name; ?>"/>
                          </div>
                            <div class="profile-data">
                                <div class="profile-data-name"><?php echo $name; ?></div>
                            </div>
                         </div>                                                                     
                    </li>
                    <li class="xn-envlop <?php if(isset($page) && $page=='analytic'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Sub_admin');?>"><span class="fa fa-dashboard"></span><span class="xn-text">Analytics</span></a>
                    </li> 
                    <li class="xn-openable <?php if(isset($page) && $page=='two'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Sub_admin/userlist');?>"><span class="fa fa-user"></span> <span class="xn-text">Manage Passenger</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Sub_admin/add_customer');?>"><span class="fa fa-pencil"></span>Add Passenger</a></li>
							<li><a href="<?php echo site_url('Sub_admin/customers');?>"><span class="fa fa-sort-alpha-desc"></span>Passenger List</a></li>
                        </ul>
                    </li>
                    <li class="xn-openable <?php if(isset($page) && $page=='driver'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Sub_admin/drivers');?>"><span class="fa fa-table"></span> <span class="xn-text">Manage Driver</span></a>
                        <ul>
                        <li><a href="<?php echo site_url('Sub_admin/addDriver');?>"><span class="fa fa-pencil"></span>Add Driver</a></li>
						<li><a href="<?php echo site_url('Sub_admin/drivers');?>"><span class="fa fa-sort-alpha-desc"></span>Driver List</a></li>
                        <!-- <li><a href="<?php //echo site_url('Driver/requests');?>"><span class="fa fa-sort-alpha-desc"></span>Driver Request</a></li> -->
                        </ul>
                    </li> 
                    
                    <li class="xn-openable <?php if(isset($page) && $page=='six'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Sub_admin/admin_profile');?>"><span class="fa fa-cog"></span> <span class="xn-text">Settings</span></a>
                        <ul>                         	
                            <!--li><a href="<?php echo site_url('Home/apptemplate');?>"><span class="fa fa-image"></span><span class="xn-text">App Template Setting</span></a></li-->
                           <li><a href="<?php echo site_url('Sub_admin/profile');?>"><span class="fa fa-user"></span><span class="xn-text">Admin Profile</span></a></li>
                           <li><a href="<?php echo site_url('Sub_admin/changePassword');?>"><span class="fa fa-unlock-alt"></span><span class="xn-text">Change Password</span></a></li>
                        </ul>
                    </li> 
                           <li class="xn-openable <?php if(isset($page) && $page=='booking'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/userlist');?>"><span class="fa fa-car"></span> <span class="xn-text">Manage Booking</span><label class="badge" id="bookingcount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                        <ul>
                              <li><a href="<?php echo site_url('Sub_admin/booking');?>"><span class="fa fa-sort-alpha-desc"></span>Booking List</a></li>
                              
                              <li><a href="<?php echo site_url('Sub_admin/pendingbooking');?>"><span class="fa fa-sort-alpha-desc"></span>Pending List</a></li>
                        </ul>
                    </li>
                                                                            
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->
             <!--?php }  ?-->
            
            <!-- PAGE CONTENT -->
            <div class="page-content">                
                <!-- START X-NAVIGATION VERTICAL -->
                <ul class="x-navigation x-navigation-horizontal x-navigation-panel">
                    <!-- TOGGLE NAVIGATION -->
                    <li class="xn-icon-button">
                        <a href="#" class="x-navigation-minimize"><span class="fa fa-dedent"></span></a>
                    </li>
                    <!-- END TOGGLE NAVIGATION -->                 
                    <!-- SIGN OUT -->
                    <li class="xn-icon-button pull-right">
                        <a href="#" class="mb-control" data-box="#mb-signout">
                        <span style="position: relative;  right: 32px;" class="fa fa-sign-out">LogOut</span></span></a>
                    </li>  
                    <!-- END SIGN OUT -->     
                </ul>
                <!-- END X-NAVIGATION VERTICAL -->                     
                <!-- START BREADCRUMB -->
                <!--ul class="breadcrumb">
                    <li><a href="#">Home</a></li>                    
                    <li class="active">Dashboard</li>
                </ul-->
                <!-- END BREADCRUMB -->                       
        <!-- PAGE CONTENT WRAPPER -->
<script>
    setInterval(function() {
        checkpendingBooking();// Do something every 5 seconds
        checkuser();        
    }, 4000);
function checkpendingBooking()
{
    $.ajax({
        url: "<?php echo site_url('Welcome/checkpendingBooking');?>",
        success: function (data) {
            //console.log(data);
            if(data!=0)
            {
                //var x = document.getElementById("audio-alert");
                //x.play();  
                $("#bookingcount").html(data);
            }
        }
    });
};
function checkuser() {
    $.ajax({
        url: "<?php echo site_url('Welcome/checkdriverRequest');?>",
        success: function (list) {
            //console.log(list);
            if(list!=0){
                $("#drivercount").html(list);
                }
            }
        });
    };
    
</script>