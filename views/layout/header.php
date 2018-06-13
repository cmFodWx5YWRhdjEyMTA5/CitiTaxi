<!DOCTYPE html>
<html lang="en">
    <head>      
        <!-- META SECTION -->
        <title><?php if(isset($title)){ echo $title;} else {echo 'CitiTaxi';} ?></title> 
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />        
        <link rel="SHORTCUT ICON" href="<?php echo base_url('assest/favicon.png');?>" type="image/png" />
        <script>var site_url = '<?php echo site_url(); ?>';</script>
        <script>var base_url = '<?php echo base_url(); ?>';</script>
        <!-- END META SECTION -->
        <!-- CSS INCLUDE -->     
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assest/css/theme-default.css">

        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">     
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

                        <a href="<?php echo base_url();?>">Dashboard</a>

                        <a href="#" class="x-navigation-control"></a>

                    </li>

                    <li class="xn-profile">

                        <a href="#" class="profile-mini">

                            <?php 

                                $image= $this->session->userdata('image');

                                $name = $this->session->userdata('name');

                            ?>

                            <img src="<?php echo base_url('userimage/'.$image);?>" alt="<?php echo $name; ?>"/>

                        </a>

                        <div class="profile">

                            <div class="profile-image">

                                <img src="<?php echo base_url('userimage/'.$image); ?>" alt="<?php echo $name; ?>"/>

                          </div>

                            <div class="profile-data">

                                <div class="profile-data-name"><?php echo $name; ?></div>

                            </div>

                         </div>                                                                     

                    </li>

                    <li class="xn-envlop <?php if(isset($page) && $page=='one'){ echo 'active';}?>">

                        <a href="<?php echo site_url('Home/Dashboard');?>"><span class="fa fa-dashboard"></span><span class="xn-text">Dashboard</span></a>
                    </li> 

                    <li class="xn-envlop <?php if(isset($page) && $page=='analytic'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/analytics');?>"><span class="fa fa-paw"></span><span class="xn-text">Analytics</span></a>
                    </li>


                    <li class="xn-openable <?php if(isset($page) && $page=='two'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/userlist');?>"><span class="fa fa-user"></span> <span class="xn-text">Manage Customers</span><label class="badge" id="rowcount1" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/add_customer');?>"><span class="fa fa-pencil"></span>Add Customer</a></li>
							<li><a href="<?php echo site_url('Home/customers');?>"><span class="fa fa-sort-alpha-desc"></span>Customer List</a></li>
                            <li><a href="<?php echo site_url('Home/loadRecord');?>"><span class="fa fa-sort-alpha-desc"></span>Customers</a></li>
                        </ul>
                    </li>

                    <li class="xn-openable <?php if(isset($page) && $page=='driver'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Driver');?>"><span class="fa fa-table"></span> <span class="xn-text">Manage Driver</span></span><label class="badge" id="drivercount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                        <ul>
                           <li><a href="<?php echo site_url('Driver/addDriver');?>"><span class="fa fa-pencil"></span>Add Driver</a></li>
						   <li><a href="<?php echo site_url('Driver');?>"><span class="fa fa-sort-alpha-desc"></span>Driver List</a></li>
                           <li><a href="<?php echo site_url('Driver/requests');?>"><span class="fa fa-sort-alpha-desc"></span>Driver Request</a></li>
                        </ul>
                    </li> 

                    <li class="xn-openable <?php if(isset($page) && $page=='fleet'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Fleet');?>"><span class="fa fa-table"></span> <span class="xn-text">Manage Fleet</span></a>
                        <ul>
                           <li><a href="<?php echo site_url('Fleet/add_fleet');?>"><span class="fa fa-pencil"></span>Add fleet</a></li>
                           <li><a href="<?php echo site_url('Fleet');?>"><span class="fa fa-sort-alpha-desc"></span>Fleet List</a></li>
                            <li>
                                <a href="<?php echo site_url('Fleet/fleet_tracking');?>"><span class="fa fa-taxi"></span><span class="xn-text">Fleet Tracking</span></a>
                            </li>
                        </ul>
                    </li> 

                    <li class="xn-openable <?php if(isset($page) && $page=='vehicle'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Vehicle');?>"><span class="fa fa-taxi"></span> <span class="xn-text"> Vehicle & Fares</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/servie_type');?>"><span class="fa fa-taxi"></span><span class="xn-text">Service Type</span></a></li>  
                            <li><a href="<?php echo site_url('Vehicle/add_fare');?>"><span class="fa fa-dollar"></span><span class="xn-text">Add Vehicle Fares</span></a></li>
                            <li><a href="<?php echo site_url('Vehicle/fares');?>"><span class="fa fa-dollar"></span><span class="xn-text">Fare List</span></a></li>
                            <li><a href="<?php echo site_url('Vehicle/fix_location');?>"><span class="fa fa-map-marker"></span>Fixed Locations</a></li>
                            <li><a href="<?php echo site_url('Driver/weeklyRewards');?>"><span class="fa fa-gift"></span>Weekly Reward</a></li>
                        </ul>
                    </li> 
                    <li class="xn-openable <?php if(isset($page) && $page=='point'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/points');?>"><span class="fa fa-dollar"></span> <span class="xn-text">Point System</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/add_point');?>"><span class="fa fa-pencil"></span>Add Point</a></li>
                            <li><a href="<?php echo site_url('Home/getpoints');?>"><span class="fa fa-sort-alpha-desc"></span>Points</a></li>
                            <li><a href="<?php echo site_url('Home/point_history');?>"><span class="fa fa-sort-alpha-desc"></span>Point History</a></li>
                        </ul>
                    </li> 
                    <li class="xn-openable <?php if(isset($page) && $page=='coupon'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/points');?>"><span class="fa fa-gift"></span> <span class="xn-text">Manage Coupon</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/ride_promocode');?>"><span class="fa fa-pencil"></span>Ride Promo Code</a></li>
                            <li><a href="<?php echo site_url('Home/getpoints');?>"><span class="fa fa-sort-alpha-desc"></span>Redeem Post</a></li>
                            <li><a href="<?php echo site_url('Home/point_history');?>"><span class="fa fa-sort-alpha-desc"></span>Redeem History</a></li>
                        </ul>
                    </li> 

                    <li class="xn-openable <?php if(isset($page) && $page=='booking'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/userlist');?>"><span class="fa fa-car"></span> <span class="xn-text">Manage Booking</span><label class="badge" id="bookingcount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/booking');?>"><span class="fa fa-sort-alpha-desc"></span>Booking List</a></li>
                            <li><a href="<?php echo site_url('Home/pendingbooking');?>"><span class="fa fa-sort-alpha-desc"></span>Pending Booking</a></li>
                        </ul>
                    </li>
                    <li class="xn-openable <?php if(isset($page) && $page=='revenue'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Vehicle/trip_earning');?>"><span class="fa fa-bar-chart"></span> <span class="xn-text">Revenue</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Vehicle/trip_earning');?>"><span class="fa fa-sort-alpha-desc"></span>Trip Earning</a></li>
                            <li><a href="<?php echo site_url('Vehicle/daily_earning');?>"><span class="fa fa-sort-alpha-desc"></span>Daily Earning</a></li>
                            <li><a href="<?php echo site_url('Vehicle/weekly_earning');?>"><span class="fa fa-sort-alpha-desc"></span>Weekly Earning</a></li>
                            <li><a href="<?php echo site_url('Vehicle/monthly_earning');?>"><span class="fa fa-sort-alpha-desc"></span>Monthly Earning</a></li>
                        </ul>
                    </li>   

                    

                    <li class="xn-openable <?php if(isset($page) && $page=='wallet'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Driver');?>"><span class="fa fa-table"></span> 
                        <span class="xn-text">Manage Wallet User</span>
                        <label class="badge" id="managercount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                        <ul>
                            <li><a href="<?php echo site_url('Driver/addWalletuser');?>">
                                <span class="fa fa-pencil"></span>Add User</a>
                            </li>
                            <li><a href="<?php echo site_url('Driver/walletUsers');?>">
                                <span class="fa fa-sort-alpha-desc"></span>Wallet User List</a>
                            </li>                       
                        </ul>
                    </li>





                    <li class="xn-openable <?php if(isset($page) && $page=='six'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Admin/admin_profile');?>"><span class="fa fa-cog"></span> <span class="xn-text">Setting</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/referral_setting');?>"><span class="fa fa-gift"></span><span class="xn-text">Referral Setting</span></a></li> 
                        
                             <li><a href="<?php echo site_url('Home/range_setting');?>"><span class="fa fa-image"></span><span class="xn-text">Range Setting</span></a></li>                        	
                            <li><a href="<?php echo site_url('Home/apptemplate');?>"><span class="fa fa-image"></span><span class="xn-text">App Template Setting</span></a></li>
                           <li><a href="<?php echo site_url('Home/profile');?>"><span class="fa fa-user"></span><span class="xn-text">Admin Profile</span></a></li>

                           <li><a href="<?php echo site_url('Home/changePassword');?>"><span class="fa fa-unlock-alt"></span><span class="xn-text">Change Password</span></a></li>
                        </ul>
                    </li> 
                    <!--li class="<?php if(isset($page) && $page=='website'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/websitepages');?>"><span class="fa fa-pencil"></span><span class="xn-text">Website Content setting</span></a>
                    </li-->                   
                </ul>
                <!-- END X-NAVIGATION -->
            </div>
            <!-- END PAGE SIDEBAR -->      
            

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


