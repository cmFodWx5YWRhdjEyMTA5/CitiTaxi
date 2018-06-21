
<?php $data['page']='six'; $data['title']='refferal_setting'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Add Refferal setting</strong></h3>                           
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
                            <form method="post" action="<?php echo site_url('Home/add_referral_setting');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Select User Type</label>
                                        <div class="col-md-6">              
                                            <select name='user_type' class="form-control select" required>
                                                <option value="">Select user type</option>      
                                                <option value="0">Customer</option>
                                                <option value="1">Driver</option>                                                
                                            </select>                                            
                                        </div>
                                    </div>

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-6">              
                                            <select name='country_id' class="form-control select" data-live-search="true" onChange="get_currency(this)" required>
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
                                        <label class="col-md-3 col-xs-12 control-label">Currency</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="currency" id="currency" class="form-control" readonly> 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Time Zone</label>
                                        <div class="col-md-6 col-xs-12">                                      
                                            <select class="form-control select" data-live-search="true" name="time_zone">
                                                <option value="">Please, select timezone</option>
                                                <?php foreach(tz_list() as $t) { ?>
                                                  <option value="<?php print $t['zone'] ?>">
                                                    <?php print $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                                                  </option>
                                                <?php } ?>
                                            </select>                                    
                                        </div>                               
                                    </div>                                    

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Referral Amount to Friend</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="amount_to_frnd" class="form-control"/>
                                        </div>                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Referral Bonus Amount</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="bonus_to_referral" class="form-control">
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Get Bonus after (x) ride</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="min_ride" class="form-control">
                                            <div><strong>(Eg. 3, 3 Rides will complete after registration, Bonus will automatically credit in CitiPay wallet. )</strong></div>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Ride complete within (x) days</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="within_days" class="form-control">
                                            <div><strong>(Eg. 15 days, Trip must be completed within 15 days after registration. )</strong></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Description for Referral</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <textarea class="form-control" rows="3" name="description"></textarea>
                                        </div>                                        
                                    </div>
                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-1"></div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Home/referral_setting'); ?>">
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
    function get_currency(sel){
        var countryid=sel.value;
        var countryname = sel.options[sel.selectedIndex].text;
        $.ajax({
            type: "get",
            url: "<?php echo site_url('Vehicle/cities/');?>"+countryid, 
            dataType: "json",  
            success:function(data){
                //console.log(data);
                if(data!=null)
                {
                    $('#country_name').val(countryname);                
                    $('#currency').val(data.currency);                
                    //console.log(data);  
                }           
            }
        });
    }
    


    /*function cities(sel)
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
    }*/



</script>



