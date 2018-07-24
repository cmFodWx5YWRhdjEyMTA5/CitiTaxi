<?php if(isset($rewardData)){ $title = 'Update reward';} else { $title='Add Reward';}
$data['page']='vehicle'; $data['title']=$title; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Driver Rewards Commission Return</strong></h3>
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
                            <?php } if(isset($rewardData)){ ?>
                            <form method="POST" action="<?php echo site_url('Driver/updateDriver_reward/'.$rewardData->reward_id);?>" class="form-horizontal"  id="apptimeline" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Reward Type</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <span style="color:red"><?php echo $rewardData->reward_type.' ('.$rewardData->city.', '.$rewardData->country.')'; ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Hit traget trips  per week</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="weeklyTargetTrip" class="form-control" data-toggle="tooltip" data-placement="top" title="Eg. 50 Trips  per week" value="<?php echo $rewardData->weeklyTargetTrip; ?>"  />
                                        </div>
                                    </div>
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Reward  As Percentage/Flat Rate</label>
                                        <div class="col-md-6">              
                                            <select name='reward_unit' class="form-control" onChange="checkTargetTrip()" required>
                                                <option value="<?php echo $rewardData->reward_unit;?>"><?php echo $rewardData->reward_unit;?></option>
                                                <option value="Percentage">Percentage</option>
                                                <option value="Flat">Flat rate</option>
                                            </select>                                              
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Reward Rate</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="rewardRate" class="form-control" value=<?php echo $rewardData->reward_rate; ?> />
                                            <span style="color:gray">(Eg. 5%  , It means 5 % return  back from his comission. if Flat rate, 10 MMK will be giving back to driver as reward for 50 trips(10MMK x 50 trips = 500 MMK ))</span>
                                        </div>
                                    </div>

                                    <div class="panel-footer"> 
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Driver/weeklyRewards'); ?>">
                                            <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                            </a>
                                        </div> 
                                        <div class="col-md-3">
                                            <input type="reset" class="btn btn-reset" value="Reset" style="margin:5px 0; width:100%;">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="submit" name="update" value="Update Reward" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                                        </div>                                                                          
                                   </div>
                                </div>
                            </form> 
                            <?php } else { ?>

                            <form method="POST" action="<?php echo site_url('Driver/addDriver_reward');?>" class="form-horizontal"  id="apptimeline" name="frm">
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
                                            <input type="hidden" name="currency" id="currency">
                                        </div>
                                    </div>                                    
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Reward Type</label>
                                        <div class="col-md-6">              
                                            <select name='reward_type' id='reward_type' class="form-control" onChange="checkTargetTrip()" required>
                                                <option value="">Select</option>
                                                <option value="Minimum">Minimum</option>
                                                <option value="Maximum">Maximum</option>
                                            </select>                                              
                                        </div>
                                    </div> 
                                                                       
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Hit traget trips  per week</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="weeklyTargetTrip" class="form-control" data-toggle="tooltip" data-placement="top" title="Eg. 50 Trips  per week"  />
                                        </div>
                                    </div>
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Reward  As Percentage/Flat Rate</label>
                                        <div class="col-md-6">              
                                            <select name='reward_unit' class="form-control" onChange="checkTargetTrip()" required>
                                                <option value="">Select</option>
                                                <option value="Percentage">Percentage</option>
                                                <option value="Flat">Flat rate</option>
                                            </select>                                              
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Reward Rate</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="rewardRate" class="form-control" />
                                            <span style="color:gray">(Eg. 5%  , It means 5 % return  back from his comission. if Flat rate, 10 MMK will be giving back to driver as reward for 50 trips(10MMK x 50 trips = 500 MMK ))</span>
                                        </div>
                                    </div>
                                    
                                    <div class="panel-footer"> 
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Driver/weeklyRewards'); ?>">
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
                            </form> 
                            <?php } ?>

                        </div>
                    </div>
                </div>                    
             </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 

    
<script type="text/javascript">
    function checkTargetTrip()
    {  
        var reward_type = $('#reward_type').val();
        var country = $('#country_name').val();        
        var city = $('#city_name').val();        
        //console.log(reward_type+' '+country);
        $.ajax({
        type: "post",
        data:{'reward_type':reward_type,'country':country,'city':city},
        dataType:'json',
        url: "<?php echo site_url('Vehicle/checkTargetTrip');?>",         
        success:function(data){
            if(data.error==1){
                $('#reward_type').prop('selectedIndex',0);                
                alert(data.message);return false;
            }            
            //location.reload(true);
            //console.log(data);       
        },
        error:function(data){
            console.log(data);
        }
        });
    }    
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
            //console.log(data);
            if(data!=null)
            {
                $('#country_name').val(countryname);
                $('.city').find("option:eq(0)").html("Please Select city");
                $('#city').append(data.data);//alert(data);
                //$('#currency').val('');
                $('#currency').val(data.currency);
                $('#cityError').text('');
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

        







