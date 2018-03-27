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

                        <a href="<?php echo site_url('Home/Dashboard');?>"><span class="fa fa-pencil"></span><span class="xn-text">Dashboard</span><label class="badge" id="rowcount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                    </li> 


                    <li class="xn-openable <?php if(isset($page) && $page=='two'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Home/userlist');?>"><span class="fa fa-user"></span> <span class="xn-text">Manage Customers</span><label class="badge" id="rowcount1" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/add_customer');?>"><span class="fa fa-pencil"></span>Add Customer</a></li>
							<li><a href="<?php echo site_url('HOme/customers');?>"><span class="fa fa-sort-alpha-desc"></span>Customer List</a></li>
                        </ul>
                    </li>

                    <li class="xn-openable <?php if(isset($page) && $page=='driver'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Driver');?>"><span class="fa fa-table"></span> <span class="xn-text">Manage Driver</span></a>
                        <ul>
                           <li><a href="<?php echo site_url('Driver/addDriver');?>"><span class="fa fa-pencil"></span>Add Driver</a></li>
						   <li><a href="<?php echo site_url('Driver');?>"><span class="fa fa-sort-alpha-desc"></span>Driver List</a></li>
                        </ul>
                    </li> 

                    <li class="xn-openable <?php if(isset($page) && $page=='vehicle'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Vehicle');?>"><span class="fa fa-taxi"></span> <span class="xn-text"> Vehicle & Fares</span></a>
                        <ul>
                            <li><a href="<?php echo site_url('Home/servie_type');?>"><span class="fa fa-taxi"></span><span class="xn-text">Service Type</span></a></li>  
                            <li><a href="<?php echo site_url('Vehicle/add_fair');?>"><span class="fa fa-dollar"></span><span class="xn-text">Add Vehicle Fares</span></a></li>
                            <li><a href="<?php echo site_url('Vehicle/fairs');?>"><span class="fa fa-dollar"></span><span class="xn-text">Fare List</span></a></li>
                            <li><a href="<?php echo site_url('Vehicle/fix_location');?>"><span class="fa fa-map-marker"></span>Fixed Locations</a></li>
                            <li><a href="<?php echo site_url('Driver/weeklyRewards');?>"><span class="fa fa-gift"></span>Weekly Reward</a></li>
                        </ul>

                    </li> 

                    

                    <!--li class="xn-envlop <?php if(isset($page) && $page=='five'){ echo 'active';}?>">

                        <a href="<?php echo site_url('PrescriptionControler');?>"><span class="fa fa-pencil"></span><span class="xn-text">Prescription Verification</span><label class="badge" id="rowcount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>

                    </li> 



                    <li class="xn-envlop <?php if(isset($page) && $page=='nine'){ echo 'active';}?>">

                        <a href="<?php echo site_url('PrescriptionControler/renew_prescription');?>"><span class="fa fa-table"></span><span class="xn-text">Refill Request</span><label class="badge" id="rowcount" style="background-color:green !important; margin-left: 10px; border-radius:90% !important"></label></a>
                    </li> 

                    <li class="xn-envlop <?php if(isset($page) && $page=='seven'){ echo 'active';}?>">

                        <a href="<?php echo site_url('User/chat');?>"><span class="fa fa-comments-o" aria-hidden="true"></span><span class="xn-text">Chat</span></a>

                    </li--> 


                    <li class="xn-openable <?php if(isset($page) && $page=='six'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Admin/admin_profile');?>"><span class="fa fa-cog"></span> <span class="xn-text">Setting</span></a>
                        <ul>
                                                     	
                            <li><a href="<?php echo site_url('Home/apptemplate');?>"><span class="fa fa-image"></span><span class="xn-text">App Template Setting</span></a></li>
                           <li><a href="<?php echo site_url('Home/profile');?>"><span class="fa fa-user"></span><span class="xn-text">Admin Profile</span></a></li>

                           <li><a href="<?php echo site_url('Home/changePassword');?>"><span class="fa fa-unlock-alt"></span><span class="xn-text">Change Password</span></a></li>
                        </ul>
                    </li> 
                    <li>
                        <a href="<?php echo site_url('Welcome/websiteContent');?>"><span class="fa fa-pencil"></span><span class="xn-text">Website Content setting</span></a>
                    </li>



                    <!--li class="xn-envlop <!?php if(isset($page) && $page=='six'){ echo 'active';}?>">

                        <a href="<!?php echo site_url('admin/change_password');?>"><span class="fa fa-unlock-alt"></span><span class="xn-text">Change Password</span></a>

                    </li> 

                    <li class="xn-envlop <!?php if(isset($page) && $page=='eight'){ echo 'active';}?>">

                        <a href="<!?php echo site_url('admin/admin_profile');?>"><span class="fa fa-user"></span><span class="xn-text">Admin Profile</span></a>

                    </li-->  



                    <!--li class="xn-envlop <!?php if(isset($page) && $page=='analytics'){ echo 'active';}?>">

                        <a href="<!?php echo site_url('Example');?>"><span class="fa fa-user"></span><span class="xn-text">Analytics</span></a>

                    </li-->                                                                               

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

