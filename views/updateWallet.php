
<?php $data['page']='wallet'; $data['title']='Update wallet'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Update Wallet manager</strong></h3>                           
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


                            <form method="post" action="<?php echo site_url('Driver/update_manager/'.$driver->manager_id);?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Shop Name</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="shopname" value="<?php echo $driver->shop_name;?>" class="form-control" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Name</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="name" value="<?php echo $driver->name;?>" class="form-control" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mobile Number</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="mobiles" required id="mobiles" value="<?php echo $driver->phone;?>" class="form-control" maxlength="10"/>
                                        </div>
                                        <span id="errMobile"></span>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">E-mail</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="email" id="email" value="<?php echo $driver->email;?>" name="emails" class="form-control" style="color:black">
                                            <span id="errEmail"></span>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Gender</label>
                                        <div class="col-md-6 col-xs-12">                     
                                           <select name="gender" class="form-control">
                                           <option value="<?php echo $driver->gender;?>"><?php echo $driver->gender; ?></option>
                                              <option value="">Select</option>
                                              <option value="male">Male</option>
                                              <option value="female">Female</option>
                                            </select>
                                        </div>                                            
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">DOB</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="dob" class="form-control datepicker" value="<?php echo $driver->dob;?>" />
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Nationality </label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="nationality" class="form-control" value="<?php echo $driver->nationality;?>" />
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">City / Country </label>
                                        <div class="col-md-6 col-xs-12"> 
                                            <?php echo $driver->city.' / '.$driver->country;?>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Address </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="address" class="form-control" value="<?php echo $driver->address;?>"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">User Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="driverimages"/>
                                            <img src="<?php echo base_url('managers/'.$driver->manager_photo); ?>" width="80px" height="80px">
                                        </div>
                                    </div>
                                </div>                                 
                                <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-2"></div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Driver/walletUsers'); ?>">
                                            <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                            </a>
                                        </div> 
                                        <div class="col-md-3">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
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
   $(document).ready(function(){
    
    
    /*$("#email").keyup(function(){
        var email = $('#email').val();
        $.ajax({
            type:'post',
            data:{'email':email},
            url:'<?php echo site_url('Driver/checkEmail'); ?>',
            datatype:'json',
            success: function (response){   
                if(response==false)
                {                    
                    $("#errEmail").text('Email id already exist');
                }             
                console.log(response);
            }
        });        
    });

    $("#mobile11").keyup(function(){
        var mobile = $('#mobile').val();
        $.ajax({
            type:'post',
            data:{'mobile':mobile},
            url:'<?php echo site_url('Driver/checkMobile'); ?>',
            datatype:'json',
            success: function (dd){   
                if(dd==false)
                {                    
                    $("#errMobile").text('Mobile number already exist');
                }             
                console.log(dd);
            }
        });
        
    });*/

    
});
</script>



