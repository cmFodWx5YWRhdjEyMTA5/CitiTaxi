
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
                                        <label class="col-md-3 col-xs-12 control-label">Hit Minimum traget trips  per week</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="weeklyTargetTrip" id="targetTrip" class="form-control" data-toggle="tooltip" data-placement="top" title="Eg. 50 Trips  per week" value="<?php echo $rewardData->weeklyTargetTrip; ?>"  />
                                        </div>
                                    </div>
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Reward  As Percentage/Flat Rate</label>
                                        <div class="col-md-6">              
                                            <select name='reward_unit' class="form-control" onChange="checkTargetTrip()" required>
                                                <option value="<?php echo $rewardData->reward_unit;?>"><?php echo $rewardData->reward_unit;?></option>
                                                <option value="Per">Percentage</option>
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
                                      <input type="reset" value="Reset" class="btn btn-reset">  
                                      <input type="submit" name="update" value="Update Reward" class="btn btn-submit pull-right">
                                   </div>
                                </div>
                            </form> 
                            <?php } else { ?>

                            <form method="POST" action="<?php echo site_url('Driver/addDriver_reward');?>" class="form-horizontal"  id="apptimeline" name="frm">
                                <div class="panel-body form-group-separated">                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Hit Minimum traget trips  per week</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="weeklyTargetTrip" id="targetTrip" class="form-control" data-toggle="tooltip" data-placement="top" title="Eg. 50 Trips  per week"  />
                                        </div>
                                    </div>
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Reward  As Percentage/Flat Rate</label>
                                        <div class="col-md-6">              
                                            <select name='reward_unit' class="form-control" onChange="checkTargetTrip()" required>
                                                <option value="">Select</option>
                                                <option value="Per">Percentage</option>
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
                                      <input type="reset" value="Reset" class="btn btn-reset">  
                                      <input type="submit" name="submit" value="Add Reward" class="btn btn-submit pull-right">
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
        var targetTrip = $('#targetTrip').val();
        //alert(targetTrip);
        $.ajax({
        type: "post",
        data:{'targetTrip':targetTrip},
        url: "<?php echo site_url('Vehicle/checkTargetTrip');?>",         
        success: 
        function(data){
            alert(data);
            location.reload(true);
            //console.log(data);       
        }
        });
    
    }

        
</script>






