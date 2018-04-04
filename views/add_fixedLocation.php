
<?php $data['page']='vehicle'; $data['title']='add fixed location'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Add fixed location</strong></h3>                            
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
                            <form method="post" action="<?php echo site_url('Vehicle/add_fixLocation');?>" class="form-horizontal" enctype="multipart/form-data" id="fairvalidate" name="frm">
                                <div class="panel-body form-group-separated">

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Pick Up Location</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="pickup" class="form-control" > 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Pick Up Latitude</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="pickupLat" class="form-control">
                                            <span id="errEmail"></span>
                                        </div>
                                    </div>                                   

                                    <div class="form-group">                                        
                                        <label class="col-md-3 col-xs-12 control-label">Pick Up Longitude</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text"  name="pickupLong" class="form-control" minlength="5" />
                                        </div>
                                    </div>


                                    <div class="form-group">            
                                        <label class="col-md-3 col-xs-12 control-label">Drop off Location</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="dropoff" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Drop off Latitude</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="dropofLat" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Drop off Longitutde</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="dropofLong" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Area Fixed Charge</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="fixCharge" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Time from</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="startTime" class="form-control timepicker" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Time to </label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="endTime" class="form-control timepicker" />
                                        </div>
                                    </div>

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Vehicle Type</label>
                                        <div class="col-md-6">              
                                            <select name='service_type' id="service_type" class="form-control" onChange="service(this)">
                                            <option value="">Select</option>
                                            <?php foreach(servicetypes() as $t) { ?>
                                              <option value="<?php print $t->typeid; ?>">
                                                <?php echo $t->servicename; ?>
                                              </option>
                                            <?php } ?>                                                                                             
                                            </select>    
                                            <input type="hidden" name="servicename" id="servicename">                                           
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Free Waiting minutes</label>
                                        <div class="col-md-6 col-xs-12">
                                            <select name="freeWaitingMinute" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge will be charged after Select minute: 2min,3min,.." required >
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
                                        <label class="col-md-3 col-xs-12 control-label">Waiting charge every (x) Minutes </label>
                                        <div class="col-md-6 col-xs-12">  
                                            <select name="waitingUnitTime" class="form-control" data-toggle="tooltip" data-placement="top" title="Waiting charge will be charged according to Select minute: 2min,3min,.." required  >
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
                                        <label class="col-md-3 col-xs-12 control-label">Waiting Charge for (x) Minutes</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="waitingMinUnitCharge" class="form-control" />
                                            <span style="color:red">Ex. If unit time=2min and price=$1 then every 2min*$1</span>
                                        </div>
                                    </div>                                    

                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="row">
                                    <div class="col-md-4">
                                        <a href="<?php echo site_url('Vehicle/fix_location'); ?>">
                                        <input type="button"  value="back" class="btn btn-back" style="max-width:300px; margin:2px 0; width:100%;">
                                        </a>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="reset" class="btn btn-reset" value="Form Reset" style="margin:5px 0; max-width:300px; width:100%;">
                                    </div>                                    
                                   
                                    <div class="col-md-4">
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

    function service(service)
    {      
        var name = service.options[service.selectedIndex].text; 
        $('#servicename').val(name);
    }
    

</script>

    





