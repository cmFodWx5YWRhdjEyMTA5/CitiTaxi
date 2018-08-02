<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
    <style>
        span{
            color:red;
            font-size: 16px;
        }
        #login_heading{
            color:white;
            letter-spacing: 5px;
            font-weight: 600;
            font-size:25px;
        }
    </style>
        <!-- META SECTION -->
        <title>Sub_admin Login</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="SHORTCUT ICON" href="<?php echo base_url('assest/favicon.png');?>" type="image/png" />
        <script>var site_url = '<?php echo site_url(); ?>';</script>
        <!-- END META SECTION -->      
        <!-- CSS INCLUDE --> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>    
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url('assest/css/theme-default.css'); ?>"/>
        <!-- EOF CSS INCLUDE -->                                    
    </head>
    <body>
        
        <div class="login-container">
        
            <div class="login-box animated fadeInDown">
            <?php if(isset($success) && $success==1) { ?>
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $message;?>
            </div>
            <?php } else if(isset($error) && $error==1) { ?>
                <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?php echo $message;?>
            </div>
            <?php } ?>
                <center><div id="login_heading"><u>SUB-ADMIN</u></div></center>
                <div class="login-logo" style="margin-left:25% !important;"><img src="<?php echo base_url('assest/cititaxiIcon.png'); ?>" style="border-radius:15%; width:200px; height:80px"></div>
                <div class="login-body">
                    <div class="login-title"><strong>Welcome</strong>, Please login</div>
                    <form class="form-horizontal" method="post" action="<?php echo Site_url();?>/Sub_admin">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="text" name="loginemail" class="form-control" placeholder="email"/>   
                     
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="password" name="loginpassword" class="form-control" placeholder="Password"/> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <a href="<?php echo site_url('Sub_admin/ForgetPassword');?>" class="btn btn-link btn-block">Forgot your password?</a>
                        </div>
                        <div class="col-md-6">
                            <input type="submit" name="login" value="LogIn" class="btn btn-info btn-block">
                        </div>
                    </div>
                    </form>
                </div>
                <div class="login-footer">
                    <div class="pull-right">
                        <!-- <a target="blank" href="http://repillrx.com/about-us" >About</a> |
                        <a target="blank" href="http://repillrx.com/privacy-policy">Privacy</a> |
                        <a target="blank" href="http://repillrx.com/support/contact">Contact Us</a> -->
                    </div>
                </div>
            </div>
            
        </div>
        
    </body>
</html>
<script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/jquery/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo base_url('assest/js/plugins/jquery-validation/jquery.validate.js');?>'></script> 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type='text/javascript' src='<?php echo base_url('assest/js/plugins/jquery-validation/formValidationScript.js');?>'></script>
