
<?php $data['page']='vehicle'; $data['title']='Update Fare'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->
<style type="text/css">
		#personal_information,
		#company_information{
			display:none;
		}
	</style>

                <div class="page-content-wrap">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><strong>Update Fare</strong></h3>
            </div>           
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
                <form method="POST" action="<?php echo site_url('Vehicle/update_fare/'.$fare->fair_id);?>" class="form-horizontal" enctype="multipart/form-data" id="fairvalidate" name="frm">
                    <div class="panel-body form-group-separated">
                        <div class="form-group">
                            <label class="col-md-3 control-label">Service Type Vehicle</label>
                            <div class="col-md-6">
                                <span style="color:red;font-weight:600;font-size:16px;"><?php echo $fare->service_name; ?></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Country / City</label>
                            <div class="col-md-6">                                
                                <span style="color:red;font-weight:600;font-size:16px;"><?php echo $fare->country.' / '.$fare->city; ?></span>
                            </div>
                        </div>  
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Description</label>
                            <div class="col-md-6 col-xs-12">   
                                <textarea class="form-control" rows="3" name="description">
                                    <?php echo $fare->description; ?>
                                </textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Maximum Load</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="maxload" class="form-control" value="<?php echo $fare->maximum_load; ?>" placeholder="2,3,4.. passengers" />
                            </div>
                        </div>
                                              
                        
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Vehicle Type</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="vehicletype" class="form-control" value="<?php echo $fare->vehicle_type; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Company Commission Type</label>
                            <div class="col-md-6">
                                <select name='commsiontype' class="form-control" >
                                    <option value="<?php echo $fare->company_comission_type; ?>"><?php echo $fare->company_comission_type; ?></option>
                                    <option value="Per">Percentage</option>
                                    <option value="Flat">Flat rate</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Company Commission Rate</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="commissionRate" class="form-control" value="<?php echo $fare->company_comission_rate; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Distance Unit(Km/Mile)</label>
                            <div class="col-md-6">
                                <select name='distanceUnit' class="form-control" >
                                     <option value="<?php echo $fare->distanceUnit; ?>"><?php echo $fare->distanceUnit; ?></option>
                                    <option value="km">Km</option>
                                    <option value="mile">Mile</option>
                                </select>
                            </div>
                        </div>
                        <!--div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Per minutes charge</label>
                            <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                <input type="hidden" name="perMinChargeStatus" value="off"> 
                                <label class="switch">
                                    <input type="checkbox" name="perMinChargeStatus" id="f1" value="on" onChange="divshow(1)"/>
                                    <span></span>
                                </label>
                            </div>
                            </div>
                            
                            <div class="form-group see1" style="display:none">
                            <label class="col-md-3 col-xs-12 control-label">Charge every (x) minutes</label>
                            <div class="col-md-6 col-xs-12">                                              
                                <select name="preEverymin_charge" class="form-control" data-toggle="tooltip" data-placement="top" title="Every couple of minute like: 2min,3min,.."  >
                                    <option value="">Select</option>
                                    <option value="1">1 min</option>
                                    <option value="2">2 min</option>
                                    <option value="5">5 min</option>
                                    <option value="10">10 min</option>
                                    <option value="15">15 min</option>
                                    <option value="20">20 min</option>
                                    <option value="25">25 min</option>
                                    <option value="30">30 min</option>                                                
                                </select>                                              
                            </div>
                            </div>
                            
                            <div class="form-group see1" style="display:none">
                            <label class="col-md-3 col-xs-12 control-label">(x) Minutes Charge</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="afterEverymin_charge" class="form-control" data-toggle="tooltip" data-placement="top" title="Every couple of minute rate like: $2, $3, $.." />
                            </div>
                            </div-->
                        <div class="form-group" style="border-top:1px dashed gray;">
                            <label class="col-md-3 col-xs-12 control-label" ></label>
                            <div class="col-md-6 col-xs-12" style="font-size: 14px;color:blue; border-left:none !important;">
                                <strong><u>Regular Charge</u></strong>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Minimum Base Fare</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="minbase_fair" class="form-control" value="<?php echo $fare->minbase_fair; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Minimum Distance(Km/Mile)</label>
                            <div class="col-md-4 col-xs-9">
                                <input type="text" name="minDistance" class="form-control" value="<?php echo $fare->min_distance; ?>">  
                            </div>
                            <div class="col-md-2 col-xs-3">
                                <select name="min_distUnit" class="form-control">
                                    <option value="<?php echo $fare->min_distanceUnit; ?>"><?php echo $fare->min_distanceUnit; ?></option>
                                    <option value="km">Km</option>
                                    <option value="mile">Mile</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Mini  distance fare</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="mini_distancefair" class="form-control" value="<?php echo $fare->mini_distancefair; ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Charge upon every (x) km/mile</label>
                            <div class="col-md-4 col-xs-9">  
                                <input type="text" name="regularChargeUponKm" class="form-control" data-toggle="tooltip" data-placement="top" title="eg. Every 1km,2km etc charge" value="<?php echo $fare->regularChargeEveryDistance; ?>" />
                            </div>
                            <div class="col-md-2 col-xs-3">
                                <select name="regularChargeUpon_unit" class="form-control">
                                    <option value="<?php  echo $fare->regularChargeEveryDistance_unit ?>"><?php echo $fare->regularChargeEveryDistance_unit; ?></option>
                                    <option value="km">Km</option>
                                    <option value="mile">Mile</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Every (x) Unit Charge</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" name="uponMinuteCharge" class="form-control" data-toggle="tooltip" data-placement="top" title="Above enter every km charge ex:$1 , $5 etc" value="<?php echo $fare->regularChargeForDistance; ?> "/><!--chargePerDistance-->
                                <span style="color:red">Ex. If Every x km/mile = 1km and Unit charge=$0.5 then calculate 1*$0.5  </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Per minutes charge</label>
                            <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                <input type="hidden" name="perMinChargeStatus" value="off">
                                <label class="switch">
                                <input type="checkbox" name="perMinChargeStatus" id="f1" <?php if($fare->perMinChargeStatus=='on'){echo 'checked';}?> value="on" onChange="divshow(1)"/>
                                <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="see1" style="display:<?php if($fare->perMinChargeStatus=='off'){ echo 'none';}else{echo 'block';}?>">
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Every Minutes for charge (every x mins)</label>
                                <div class="col-md-6 col-xs-12">
                                    <select name="unitPerMinuteforCharge" class="form-control" data-toggle="tooltip" data-placement="top" title="Charge will be calculate accroding to select min">
                                        <!--minMinuteforbasefare-->
                                        <option value="<?php echo $fare->unitPerMinuteforCharge;?>"><?php echo $fare->unitPerMinuteforCharge.' min';?></option>
                                        <option value="1">1 min</option>
                                        <option value="2">2 min</option>
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="15">15 min</option>
                                        <option value="20">20 min</option>
                                        <option value="25">25 min</option>
                                        <option value="30">30 min</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Every Select Minute Charge</label>
                                <div class="col-md-6 col-xs-12">
                                    <input type="text" name="unitPerMinutecharge" class="form-control" value="<?php echo $fare->unitPerMinutecharge; ?>" /> <!--afterMinMinutecharge-->
                                    <span style="color:red">Ex. If Every minute=2min and Per minute charge=$1 then calculate by every 2min*$1</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Regular Hours Waiting Charge</strong></label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Free Waiting minutes</label>
                            <div class="col-md-6 col-xs-12">
                                <select name="regFreeWaitingMinute" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge will be charged after Select minute: 2min,3min,.." required >
                                    <option value="<?php echo $fare->regularFreeWaitingMinute;?>"><?php echo $fare->regularFreeWaitingMinute.' min';?></option>
                                    <option value="1">1 min</option>
                                    <option value="2">2 min</option>
                                    <option value="5">5 min</option>
                                    <option value="10">10 min</option>
                                    <option value="15">15 min</option>
                                    <option value="20">20 min</option>
                                    <option value="25">25 min</option>
                                    <option value="30">30 min</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Waiting charge every (x) Minutes </label>
                            <div class="col-md-6 col-xs-12">
                                <select name="regWaitingUnitTime" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge will be charged according to Select minute: 2min,3min,.." required  >
                                    <option value="<?php echo $fare->regularWaitingPeriodForCharge;?>"><?php echo $fare->regularWaitingPeriodForCharge.' min';?></option>
                                    <option value="1">1 min</option>
                                    <option value="2">2 min</option>
                                    <option value="5">5 min</option>
                                    <option value="10">10 min</option>
                                    <option value="15">15 min</option>
                                    <option value="20">20 min</option>
                                    <option value="25">25 min</option>
                                    <option value="30">30 min</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Waiting Charge for (x) Minutes</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="regWaitingUnitTimePrice" class="form-control" value="<?php echo $fare->regularWaitingPeriodCharge; ?>" />
                                <span style="color:red">Ex. If unit time=2min and price=$1 then every 2min*$1</span>
                            </div>
                        </div>
                        
                        <!--div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Peak Hours Waiting Charge</strong></label>
                            <input type="hidden" name="peaHourkWaitingChargeStatus" value="off"/>
                            <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                <label class="switch">
                                <input type="checkbox" name="peaHourkWaitingCharge" value="on" id="f5" onChange="divshow(5)" />
                                <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="see5" style="display:none">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Waiting time start after(X)mins</label>
                                <div class="col-md-6">
                                    <select name="peakChargeAfterStart" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge count after select minutes"  >
                                        <option value="">Select</option>
                                        <option value="1">1 min</option>
                                        <option value="2">2 min</option>
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="15">15 min</option>
                                        <option value="20">20 min</option>
                                        <option value="25">25 min</option>
                                        <option value="30">30 min</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Price for unit time(every  x mins)</label>
                                <div class="col-md-6 col-xs-12">
                                    <select name="peakUnitTimePriceMin" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge for x minute like- every 2min, 5min etc"  >
                                        <option value="">Select</option>
                                        <option value="1">1 min</option>
                                        <option value="2">2 min</option>
                                        <option value="5">5 min</option>
                                        <option value="10">10 min</option>
                                        <option value="15">15 min</option>
                                        <option value="20">20 min</option>
                                        <option value="25">25 min</option>
                                        <option value="30">30 min</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Price for waiting time</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="peakUnitTimePrice" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge for above enters minutes like- $2,$5,$..."/>
                                </div>
                            </div>
                        </div-->
                            <div class="form-group" style="border-top:1px dashed gray;">
                                <label class="col-md-3 col-xs-12 control-label" ></label>
                                <div class="col-md-6 col-xs-12" style="font-size: 14px;color:blue; border-left:none !important;">
                                    <strong><u>Cancellation Charges</u></strong>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Driver Cancellation Charge Unit</label>
                                <div class="col-md-6">
                                    <select name='cancelChargeUnitDriver' class="form-control" required >
                                        <option value="<?php echo $fare->cancelChargeUnitDriver; ?>"><?php echo $fare->cancelChargeUnitDriver; ?></option>
                                        <option value="Per">Percentage</option>
                                        <option value="Flat">Flat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Standard Cancellation Charge (Driver)</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="stndCancelChargeDriver" class="form-control" value="<?php echo $fare->stndCancelChargeDriver;?>" />
                                </div>
                            </div>
                            <!--div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Peak Hour Cancellation Fee(Driver)</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="peakHrCancelChargeDriver" class="form-control" />
                                </div>
                            </div-->
                            <div class="form-group">
                                <label class="col-md-3 control-label">Passenger Cancellation Charge Unit</label>
                                <div class="col-md-6">
                                    <select name='cancelChargeUnitPassenger' class="form-control" required>
                                        <option value="<?php echo $fare->cancelChargeUnitPassenger; ?>"><?php echo $fare->cancelChargeUnitPassenger;?></option>
                                        <option value="Per">Percentage</option>
                                        <option value="Flat">Flat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Standard Cancellation Charge (Passenger)</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="stndCancelChargePassenger" class="form-control" value="<?php echo $fare->stndCancelChargePassenger; ?>"/>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Weekly Cancellation Limit</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="weeklyCancelLimit" data-toggle="tooltip" data-placement="top" title="Enter cancel booking limit like:5 , 10, etc. After week cancel limit, passenger account will suspend" class="form-control" value="<?php echo $fare->WeeklyCancellationLimit;  ?>">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Mutiple Stop Booking  Surcharge (Flat Rate)</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="multiStopCharge" class="form-control" value="<?php echo $fare->multiStopCharge; ?>">
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="panel-footer" style="margin-top:20px;">                        
                        <div class="row">
                            <div class="col-md-3 col-xs-6">
                                <a href="<?php echo site_url('Vehicle/fares');?>">
                                    <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                </a>
                            </div>

                            <div class="col-md-3 col-xs-6 pull-right">
                                <input type="submit" name="submit" value="Update" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                            </div>                                                               
                        </div>                            
                    </div>                            
                </div>                   
            </form>
        </div>
        
    </div>
</div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 

    
<script type="text/javascript">
    function divshow(s)
    {
        //alert('f'+s);
        if($('#f'+s).prop('checked')){
            //$('#f'+s).val('on');
            $('.see'+s).show();
        }
        else
        {
            //$('#f'+s).val('off');
            $('.see'+s).hide();   
        }
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
                console.log(data);
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


        function service(service)
        {      
            var name = service.options[service.selectedIndex].text; 
            $('#servicename').val(name);
        }

        function citiname(city)
        {
            var cityid   = city.value;
            var cityname = city.options[city.selectedIndex].text; 
            var serviceid = $('#service_type').val();
            $.ajax({
                type:'post',
                data:{'city_id':cityid,'serviceid':serviceid},
                url:'<?php echo site_url('Vehicle/fairCityexist');?>',
                success:function(data){
                    if(data!='')
                    {
                        alert(data);
                        location.reload(true);    
                    }
                }
            });
            //alert(serviceid);
            $('#city_name').val(cityname);
        }
</script>






