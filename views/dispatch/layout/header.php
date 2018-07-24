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
                                $image= $this->session->userdata('dis_image');
                                $name = $this->session->userdata('dis_name');
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
                    <ul >
                        <li class="<?php if(isset($page) && $page=='tbooking'){ echo 'active';}?>">
                            <a href="<?php echo site_url('Dispatch');?>"><span class="fa fa-car"></span>Today Requests</a>
                        </li>
                    </ul> 
                    <ul >
                        <li class="<?php if(isset($page) && $page=='newbooking'){ echo 'active';}?>">
                            <a href="<?php echo site_url('Dispatch/new_booking');?>"><span class="fa fa-car"></span>New Booking</a>
                        </li>
                    </ul>  
                    <ul>
                        <li class="<?php if(isset($page) && $page=='heatmap'){ echo 'active';}?>">
                            <a href="<?php echo site_url('Dispatch/heatmap');?>"><span class="fa fa-map-marker"></span>Heat Map</a>
                        </li>
                    </ul>
                    <ul>
                        <li class="<?php if(isset($page) && $page=='booking'){ echo 'active';}?>">
                            <a href="<?php echo site_url('Dispatch/manage_booking');?>"><span class="fa fa-car"></span>Manage Booking</a>
                        </li>
                    </ul>                     


                    <li class="xn-openable <?php if(isset($page) && $page=='setting'){ echo 'active';}?>">
                        <a href="<?php echo site_url('Dispatch/disptach_profile');?>"><span class="fa fa-cog"></span> <span class="xn-text">Setting</span></a>
                        <ul>                            
                           <li><a href="<?php echo site_url('Dispatch/disptach_profile');?>"><span class="fa fa-user"></span><span class="xn-text">Profile</span></a></li>
                           <li><a href="<?php echo site_url('Dispatch/changePassword');?>"><span class="fa fa-unlock-alt"></span><span class="xn-text">Change Password</span></a></li>
                        </ul>
                    </li>                                      
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




