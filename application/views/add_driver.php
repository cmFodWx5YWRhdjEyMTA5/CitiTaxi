
<?php $data['page']='driver'; $data['title']='Add driver'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Add</strong>&nbsp;driver</h3>                           
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
                            <form method="post" action="<?php echo site_url('Driver/addDriver');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Name</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="name" class="form-control" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mobile Number</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="mobile" id="mobile" class="form-control" maxlength="10"/>
                                        </div>
                                        <span id="errMobile"></span>
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
                                            <input type="text" id="password" name="password" class="form-control" minlength="5" style="font-weight:bold; color:red;" />
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
                                        <label class="col-md-3 col-xs-12 control-label">DOB   </label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="date" name="dob" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Nationality </label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="nationality" class="form-control"/>
                                        </div>
                                    </div>

                                     <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">City </label>
                                        <div class="col-md-6 col-xs-12"> 
                                            <input type="text" name="city" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Address </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="address" class="form-control"/>
                                        </div>
                                    </div>


    				                <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver's Bank Name</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="bankname" class="form-control"/>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Bank Code/ Branch Name</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="branchCode_Name" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Bank Account Number</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="accountNo" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver License number </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="licenseno" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver License Expiry Date </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="date" name="expiredate" class="form-control"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driving License Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-primary" name="licenseimage" id="filename" />
                                        </div>
                                    </div>

                                    

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-primary" name="driverimage" id="driverimage" />
                                        </div>
                                    </div>

                                    

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" ></label>
                                        <div class="col-md-6 col-xs-12" style="font-size: 14px;color:blue; border-left:none !important;">                      
                                            <strong><u>Vechile Information</u></strong>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Vehicle Brand </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="brand" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Sub brand name </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="subbrand" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Insurance Company </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="insuranceCompany" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Insurance number </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="insuranceNumber" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" ></label>
                                        <div class="col-md-6 col-xs-12" style="font-size: 14px;color:blue; border-left:none !important;">                      
                                            <strong><u>Fleet Company Information</u></strong>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Company name </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="fleet_company" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Fleet Company Country </label>
                                        <div class="col-md-6 col-xs-12">                                
                                            <input type="text" name="fleet_country" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Fleet Company Address </label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="fleet_address" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Booking Limit Per Day </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="bookingLimit" class="form-control"/>
                                        </div>
                                    </div>

                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <input type="reset" class="btn btn-success" value="Form Reset" style="margin:5px 0; max-width:300px; width:100%;">
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-success pull-right" style="max-width:300px; margin:2px 0; width:100%;">
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
    // For Technician Password
    var chars = "0123456789";
    var string_length = 6;
    var randomstring = '';
    for (var i=0; i<string_length; i++) {
      var rnum = Math.floor(Math.random() * chars.length);
      randomstring += chars.substring(rnum,rnum+1);
    }
    document.getElementById("password").value = randomstring;

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



