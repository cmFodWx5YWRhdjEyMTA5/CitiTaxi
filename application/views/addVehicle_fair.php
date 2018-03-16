
<?php $data['page']='two'; $data['title']='Add Customer'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Add</strong>&nbsp;Customer</h3>                            
                                </div>
                        <div class="container">  
                                    
                            <?php if(isset($error)&& $error==1) { ?>
                            <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                             <?php echo $message;?>
                            </div>
                            <?php }if(isset($success)&& $success==1){?>
                            <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                             <?php echo $message;?>
                            </div>
                            <?php }?>
                            <form method="post" action="<?php echo site_url('Home/add_customer');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">

                                    <!-- START WIZARD WITH VALIDATION -->
                            <div class="block">
                                <h4>Wizard with form validation</h4>                                
                                <form action="javascript:alert('Validated!');" role="form" class="form-horizontal" id="wizard-validation">
                                <div class="wizard show-submit wizard-validation">
                                    <ul>
                                        <li>
                                            <a href="#step-7">
                                                <span class="stepNumber">1</span>
                                                <span class="stepDesc">Login<br /><small>Information</small></span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#step-8">
                                                <span class="stepNumber">2</span>
                                                <span class="stepDesc">User<br /><small>Personal data</small></span>
                                            </a>
                                        </li>                                    
                                    </ul>

                                    <div id="step-7">   

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Login</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" name="login" placeholder="Login"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Password</label>
                                            <div class="col-md-10">
                                                <input type="password" class="form-control" name="password" placeholder="Password" id="password"/>
                                            </div>
                                        </div>             
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Re-Password</label>
                                            <div class="col-md-10">
                                                <input type="password" class="form-control" name="repassword" placeholder="Re-Password"/>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="step-8">

                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Name</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" name="name" placeholder="Name"/>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">E-mail</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" name="email" placeholder="Your email"/>
                                            </div>
                                        </div>                                    
                                        <div class="form-group">
                                            <label class="col-md-2 control-label">Adress</label>
                                            <div class="col-md-10">
                                                <input type="text" class="form-control" name="adress" placeholder="Your adress"/>
                                            </div>                                        
                                        </div>                                                     

                                    </div>                                                                                                            
                                </div>
                                </form>
                            </div>                        
                            <!-- END WIZARD WITH VALIDATION -->
                            </div>
                            </form>                         
                        </div>
                    </div>
                </div>                    
             </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 
 <script type="text/javascript" src="<?php echo base_url('assest/js/plugins/smartwizard/jquery.smartWizard-2.0.min.js');?>"></script>    
<script>

    /*$("#mobile").keyup(function(){
        var mobile = $('#mobile').val();
        $.ajax({
            type:'post',
            data:{'mobile':mobile},
            url:'<?php echo site_url('Driver/checkMobile'); ?>',
            datatype:'json',
            success: function (dd){   
                if(dd=='false')
                {                              
                $("#errMobile").text('Mobile number already exist');
                }             
                console.log(dd);
            }
        });
        
    });*/



    

</script>

    





