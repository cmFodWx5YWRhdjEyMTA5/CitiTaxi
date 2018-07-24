
<?php $data['page']='fleet'; $data['title']='Add dispatcher'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Add dispatcher</strong></h3>                           
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
                            <form method="post" action="<?php echo site_url('Fleet/add_dispatcher');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Name</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="name" class="form-control" id="txtcapital" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mobile Number</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="dismobile" class="form-control" maxlength="10" required />
                                        </div>
                                        <span id="errMobile"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">E-mail</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="email" name="dispatch_email" class="form-control" required>
                                            <span id="errEmail"></span>
                                        </div>
                                    </div>                                    

                                    <div class="form-group">                                        
                                        <label class="col-md-3 col-xs-12 control-label">Password</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="password" id="password1" name="password" class="form-control" minlength="5"/>
                                        </div>
                                    </div> 
                                    <div class="form-group">            
                                        <label class="col-md-3 col-xs-12 control-label">Confirm Password</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="password" name="confirm_password" class="form-control"/>
                                        </div>
                                    </div>                                  

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Address </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="address" class="form-control"/>
                                        </div>
                                    </div>                                    
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-6">              
                                            <select name='country_id' class="form-control select" data-live-search="true" onChange="cities(this)" required>
                                                <option value="">Select Country</option>
                                                <?php foreach(countryies() as $country) { ?>
                                                  <option value="<?php print $country->id; ?>">
                                                    <?php echo $country->name; ?>
                                                  </option>
                                                    <?php } ?> 
                                            </select> 
                                            <input type="hidden" name="country" id="country_name">
                                        </div>
                                    </div>
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">City</label>
                                        <div class="col-md-6">              
                                            <select name='city_id' id="city" class="form-control city" required onChange="citiname(this)">
                                                <option value="">Select City</option>                                               
                                            </select>   
                                            <span id='cityError' style="color:red;"></span>
                                            <input type="hidden" name="city" id="city_name">
                                        </div>
                                    </div>                               

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="image" id="image" data-error="#err" />
                                            <span id="err"></span>
                                        </div>
                                    </div>  
                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-1"></div>
                                    <div class="row">

                                    <div class="col-md-3">
                                        <a href="<?php echo site_url('Fleet'); ?>">
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
            $('#txtcapital').keyup(function() {
                var caps = jQuery('#txtcapital').val(); 
                caps = caps.charAt(0).toUpperCase() + caps.slice(1);
                jQuery('#txtcapital').val(caps);
            });
        })
    </script>

<script>   
    function cities(sel)
    {   //alert(sel.value);
        $(".city option:gt(0)").remove(); 
        var countryid=sel.value;
        var countryname = sel.options[sel.selectedIndex].text;            
        $('.city').find("option:eq(0)").html("Please wait....");
            $.ajax({
            type: "get",
            url: "<?php echo site_url('Vehicle/cities/');?>"+countryid, 
            dataType: "json",  
            success:function(data){
            console.log(data);
            if(data!=null)
            {
                $('#country_name').val(countryname);
                $('.city').find("option:eq(0)").html("Please Select city");
                $('#city').append(data.data);//alert(data);
                //$('#currency').val('');               
                //console.log(data);  
            }
            else
            {
                $('#cityError').text('City is not found. Please select another country');
            }
            }
        });        
    }
    function citiname(city)
    {
        var cityid   = city.value;
        var cityname = city.options[city.selectedIndex].text;             
        $('#city_name').val(cityname);
    }



</script>



