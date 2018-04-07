
<?php $data['page']='driver'; $data['title']='Add driver'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Add driver</strong></h3>                           
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
                                            <input type="text" name="dob" class="form-control datepicker" placeholder="DD-MM-YYYY" />
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
                                            <input type="text" name="expiredate" class="form-control datepicker" placeholder="DD-MM-YYYY"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driving License Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="licenseimage" id="filename" data-error="#drivingErr" />
                                            <span id="drivingErr"></span>
                                        </div>
                                    </div>

                                    

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="driverimage" id="driverimage" data-error="#err" />
                                            <span id="err"></span>
                                        </div>
                                    </div>

                                    

                                    <div class="form-group" style="border-top:1px dashed gray;">
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
                                        <label class="col-md-3 col-xs-12 control-label">Vehicle Number Plate </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="vehicle_NoPlate" class="form-control" />
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
                                        <label class="col-md-3 col-xs-12 control-label">Insurance Expiry Date </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="insuranceExpiredate" class="form-control datepicker" placeholder="DD-MM-YYYY"/>
                                        </div>
                                    </div>

                                    <!--div class="form-group" style="border-top:1px dashed gray;">
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
                                    </div-->
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Select Fleet Company</label>
                                        <div class="col-md-6">              
                                            <select name='fleet_id' class="form-control" required>
                                             <option value="">Select</option>   
                                            <?php foreach(getallfleets() as $t) { ?>
                                              <option value="<?php print $t->fleet_id; ?>">
                                                <?php echo $t->fleet_company.' ('.$t->fleet_id.')'; ?>
                                              </option>
                                            <?php } ?>                                                                           
                                            </select>                                              
                                        </div>
                                    </div> 

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Service Type Vehicle</label>
                                        <div class="col-md-6">              
                                            <select name='service_type[]' id="myDropdown" multiple class="form-control select" required>

                                            <?php foreach(servicetypes() as $t) { ?>
                                              <option value="<?php print $t->typeid; ?>">
                                                <?php echo $t->servicename; ?>
                                              </option>
                                            <?php } ?>                                                                           
                                            </select>   
                                            <span id='typeError' style="color:red;"></span>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Booking Limit Per Day </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" id='bookingLimit' name="bookingLimit" class="form-control"/>
                                        </div>
                                    </div>

                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-1"></div>
                                    <div class="row">

                                    <div class="col-md-3">
                                        <a href="<?php echo site_url('Driver'); ?>">
                                        <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                        </a>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="reset" class="btn btn-reset" value="Reset" style="margin:5px 0; width:100%;">
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
    // For Technician Password
    var chars = "0123456789";
    var string_length = 6;
    var randomstring = '';
    for (var i=0; i<string_length; i++) {
      var rnum = Math.floor(Math.random() * chars.length);
      randomstring += chars.substring(rnum,rnum+1);
    }
    document.getElementById("password").value = randomstring;

    $('#bookingLimit').on('keyup', function() {
        var responseId = $('#myDropdown').val();
        if(responseId==null){
            $('#bookingLimit').val('');
            $('.typeErr').css('height','20px !important');
            $('#typeError').text("Please select atlest 1 service type");
        }           
        
    });

    $('#myDropdown').on('change', function() {
        var responseId = $('#myDropdown').val();
        if(responseId==null){
            $('#bookingLimit').val('');
            $('.typeErr').css('height','20px !important');
            $('#typeError').text("Please select service type");
        }
        else{
            $('#typeError').text(" ");
        } 
    });

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



