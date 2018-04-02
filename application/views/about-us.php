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
        <link rel="stylesheet" href="<?php echo base_url();?>assest/css/bootstrap.css"> <!-- Bootstrap-Core-CSS -->
            
    </head>
    <style>
        .x-navigation-panel{
            padding:5px 0;
            position: fixed;
            height: 20px !important;
        }
        #nextheader{
                background: #FFFFFF;
                padding: 0px 30px;
                box-shadow: 0px 6px 4px 0px rgba(0, 0, 0, 0.14);                                
                transition: all 0.2s ease;
                transform: translateY(0);
                z-index: 100;
                height: 65px;
                width: 100%;
                position: fixed;
                border-top:20px solid;
        }
         
    </style>
    <body>
    <div class="page-content">         
        <div id="nextheader">
            <div class="pull-left">
                <img src="http://localhost/projects/CitiTaxi/assest/cititaxiIcon.png" width="30%" class="logo" style="height:50px !important;">
            </div>
        </div>       
    </div>

    
        
    
         <div class="container-fluid" style="padding:0px">
           <img width="100%" height="100%" src="<?php echo base_url('pageImages/'.$pagedata->banner); ?>" class="img-responsive wow fadeInUp" alt="About" style="max-height:800px;">
           </div>
           <div class="row" style="margin-top:10px">
               <div class="container">
                   <?php print_r($pagedata->content);?>
               </div>
           </div>

    <!--?php print_r($pagedata); ?-->
    	
 
    	


    </body>
</html>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/jquery/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/bootstrap/bootstrap.min.js"></script> 

<script>
    $(document).ready(function(){
        $("img").addClass("img-responsive"); 
        $("table").addClass("table-responsive");
        $("table").css('overflow','scroll');
        $("img").css('display','inline');
    });
</script>