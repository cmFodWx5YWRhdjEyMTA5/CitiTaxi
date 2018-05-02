
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

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Name</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="name" class="form-control" > 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">E-mail</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="email" id="email" name="email" class="form-control">
                                            <span id="errEmail"></span>
                                        </div>
                                    </div>                                   

                                    <div class="form-group">                                        
                                        <label class="col-md-3 col-xs-12 control-label">Password</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="password" id="password1" name="password" class="form-control" minlength="5" />
                                        </div>
                                    </div>


                                    <div class="form-group">            
                                        <label class="col-md-3 col-xs-12 control-label">Confirm Password</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="password" name="confirm_password" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mobile Number</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10"/>
                                            <span id="errMobile" style="color:red"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Gender</label>
                                        <div class="col-md-6 col-xs-12">                     
                                           <select name="gender" class="form-control">
                                              <option value="">Select</option>
                                              <option value="male">Male</option>
                                              <option value="female">Female</option>
                                            </select>
                                        </div>                                            
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Image</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-submit" name="image" id="filename" />
                                        </div>
                                    </div>

                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <input type="reset" class="btn btn-reset" value="Form Reset" style="margin:5px 0; max-width:300px; width:100%;">
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-submit pull-right" style="max-width:300px; margin:2px 0; width:100%;">
                                    </div>
                                    
                                </div>
                                   
                                </div>
                            </div>
                            </form>                         
                        </div>
                    </div>
                </div>                    
             </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 

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

    





