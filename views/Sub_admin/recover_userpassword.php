<!DOCTYPE html>
<html lang="en" class="body-full-height">
    <head>        
    <style>
        span{
            color:red;
            font-size: 16px;
        }
    </style>
        <!-- META SECTION -->
        <title>Recover Password</title>            
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        
        <link rel="SHORTCUT ICON" href="<?php echo base_url('assest/favicon.png');?>" type="image/png" />
        <!-- END META SECTION -->      
        <!-- CSS INCLUDE --> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
       <script> var site_url = '<?php echo site_url(); ?>';</script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>     
        <link rel="stylesheet" type="text/css" id="theme" href="<?php echo base_url();?>assest/css/theme-default.css"/>
        <!-- EOF CSS INCLUDE -->                                    
    </head>
    <style>
        .alert{
            width:99% !important;
            margin-left: 0px!important;
        }
    </style>
    
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
            <div class="login-logo" style="margin-left:25% !important;"><img src="<?php echo base_url('assest/cititaxiIcon.png'); ?>" style="border-radius:15%; width:200px; height:80px"></div>
               
                <div class="login-body">
                    <div class="login-title"><strong>Reset</strong> Password </div>
                    <form class="form-horizontal" id="jvalidate" method="post" action="<?php echo site_url('Dispatcher/reset_password?email='.$email); ?>">
                    <div class="form-group">
                        <div class="col-md-12">
                            <input type="hidden" name="email" value="<?php echo $email; ?>">  
                            <input type="text" id="password1" name="password" class="form-control" placeholder="Enter New Password" minlength="8" required /> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">  
                            <input type="text" name="confirm_password" class="form-control" placeholder="Enter Confirm Password" minlength="8" required /> 
                        </div>
                    </div>
                    <div class="col-md-6 pull-left">
                         <a href="<?php echo site_url();?>/Dispatcher" class="btn btn-link btn-block" >Back to LogIn</a>
                        </div>

                        <div class="col-md-6 pull-right">
                            <?php if(isset($error) && $error!=1) { ?><input type="submit" name="recover" value="Reset" class="btn btn-success btn-block"> <?php }?>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    </body>
</html>
<script type='text/javascript' src='<?php echo base_url('assest/js/plugins/jquery-validation/jquery.validate.js');?>'></script> 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type='text/javascript' src='<?php echo base_url('assest/js/plugins/jquery-validation/formValidationScript.js');?>'></script>






