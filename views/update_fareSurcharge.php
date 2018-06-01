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
                <form method="POST" action="<?php echo site_url('Vehicle/update_surcharge/'.$fare->fair_id);?>" class="form-horizontal" enctype="multipart/form-data" id="fairvalidate" name="frm">
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
                            <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Morning Charge</strong></label>
                            <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                <input type="hidden" name="morningChargeStatus" value="off"/>
                                <label class="switch">
                                <input type="checkbox" name="morningChargeStatus" <?php if($fare->morningChargeStatus=='on'){echo 'checked';}?> value="on" id="f2" onChange="divshow(2)"/>
                                <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="see2" style="display:<?php if($fare->morningChargeStatus=='off'){ echo 'none';}else{echo 'block';}?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Surcharge Unit</label>
                                <div class="col-md-6">
                                    <select name='morningSurchargeUnit' class="form-control" >
                                        <option value="<?php echo $fare->morningSurchargeUnit; ?>"><?php echo $fare->morningSurchargeUnit; ?></option>
                                        <option value="Per">Percentage</option>
                                        <option value="Flat">Flat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Price</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="morningSurchargePrice" class="form-control" data-toggle="tooltip" data-placement="top" value="<?php echo $fare->morningSurchargePrice; ?>" title="Enter Morning Surcharge Price link $50, $80 etc" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Time Start</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="morningSurchargeTimeStart" value="<?php echo $fare->morningSurchargeTimeStart; ?>" class="form-control timepicker" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Time End</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="morningSurchargeTimeEnd" value="<?php echo $fare->morningSurchargeTimeEnd; ?>" class="form-control timepicker" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Evening Charge</strong></label>
                            <input type="hidden" name="eveningChargeStatus" value="off">
                            <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                <label class="switch">
                                <input type="checkbox" name="eveningChargeStatus" <?php if($fare->eveningChargeStatus=='on'){echo 'checked';}?> value="on" id="f3" onChange="divshow(3)"/>
                                <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="see3" style="display:<?php if($fare->eveningChargeStatus=='off'){ echo 'none';}else{echo 'block';}?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Surcharge Unit</label>
                                <div class="col-md-6">
                                    <select name='eveningSurchargeUnit' class="form-control">
                                        <option value="<?php echo $fare->eveningSurchargeUnit; ?>"><?php echo $fare->eveningSurchargeUnit; ?></option>
                                        <option value="Per">Percentage</option>
                                        <option value="Flat">Flat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Price</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="eveningSurchargePrice" class="form-control" data-toggle="tooltip" data-placement="top" value="<?php echo $fare->eveningSurchargePrice; ?>" title="Enter Evening Surcharge Price link $50, $80 etc" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Time Start</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="eveningSurchargeTimeStart" value="<?php echo $fare->eveningSurchargeTimeStart; ?>" class="form-control timepicker" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Time End</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="eveningSurchargeTimeEnd" value="<?php echo $fare->eveningSurchargeTimeEnd; ?>" class="form-control timepicker"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Mid Night Charge</strong></label><input type="hidden" name="midNightChargeStatus" value="off"/>
                            <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                <label class="switch">
                                <input type="checkbox" name="midNightChargeStatus" <?php if($fare->midNightChargeStatus=='on'){echo 'checked';}?> value="on" id="f4" onChange="divshow(4)"/>
                                <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="see4" style="display:<?php if($fare->midNightChargeStatus=='off'){ echo 'none';}else{echo 'block';}?>"">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Surcharge Unit</label>
                                <div class="col-md-6">
                                    <select name='midNightSurchargeUnit' class="form-control">
                                        <option value="<?php echo $fare->midNightSurchargeUnit;?>"><?php echo $fare->midNightSurchargeUnit;?></option>
                                        <option value="Per">Percentage</option>
                                        <option value="Flat">Flat</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Price</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="midNightSurchargePrice" data-toggle="tooltip" data-placement="top" title="Enter Mid night Surcharge Price link $50, $80 etc" value="<?php echo $fare->midNightSurchargePrice; ?>" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Time Start</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="midNightSurchargeTimeStart" value="<?php echo $fare->midNightSurchargeTimeStart; ?>" class="form-control timepicker" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Surcharge Time End</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="midNightSurchargeTimeEnd" value="<?php echo $fare->midNightSurchargeTimeEnd; ?>" class="form-control timepicker" />
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