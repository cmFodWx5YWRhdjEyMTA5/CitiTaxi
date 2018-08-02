<?php $data['page']='vehicle'; $data['title']='fair list'; $this->load->view('layout/header',$data);?>
    
    <style>
        table tbody th,td{
            border-left: 1px solid black;
        }
        .sp-pre-con {
          position: fixed;
          left: 0px;
          top: 0px;
          width: 100%;
          height: 100%;
          z-index: 9999;        
          background: url(<?php echo base_url('assest/images/myloading.gif'); ?>) center no-repeat #00000070;
        } 
    </style>
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">

                  <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-default">
                                <div class="sp-pre-con" style="display: none;"></div>
                                <div class="panel-heading">

                                    <h3 class="panel-title"><strong>Vehicle Fair Details</strong></h3>
                                    <div class="btn-group pull-right">
                                      <a href="<?php echo site_url('Vehicle/add_fare');?>">
                                         <button type="button" class="btn btn-submit">Add Fare</button>   
                                      </a>
                                    </div>                                    
                                    <?php if(isset($sucess)==1){ ?>

                                    <div class="alert alert-success">

                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                    <?php echo $message;?>

                                    </div>        

                                    <?php } else if(isset($error)==1) { ?>

                                    <div class="alert alert-danger">

                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                                    <?php echo $message;?>

                                    </div>

                                    <?php }?>     

                                </div>                               


                                <div class="panel-body">
                                    <div class="table-responsive">
                                     <div id="list_table" style="overflow:scroll;">
                                     <table id="example" class="table display">                                        
                                        <thead>
                                            <tr>
                                            <th>Sr.No</th>                                           
                                            <th style="min-width:100px;">Service Name</th>                                         
                                            <th>Country</th>
                                            <th>City</th>
                                            <th style="min-width:60px;">Currency</th>
                                            <th style="min-width:100px;">Company Commission</th>
                                            <th style="min-width:100px;">Minimum base fair</th>
                                            <th style="min-width:100px;">Minimum distance</th>
                                            <th style="min-width:100px;">Mini distance fair</th>
                                            <th style="min-width:100px;">Regular Charge(Min)</th>
                                            <th style="min-width:100px;">Regular x min Charge</th>
                                            <th style="min-width:100px;">Stnd Cancel charge(Driver)</th>
                                            <th style="min-width:100px;">Stnderd Cancel charge(Customer)</th>
                                            <th style="min-width:100px;">Peak Max Cancel booking(customer)</th>
                                            <th style="min-width:100px;">Ride Later Status</th>
                                            <th style="min-width:80px;"> Full Details</th>
                                            <th style="min-width:80px;">Edit Fare</th>
                                            <th style="min-width:80px;">Edit Surcharge</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($fairlist as $list) {  ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td><?php echo $list->service_name; ?></td>     
                                                <td><?php echo $list->country; ?></td>
                                                <td><?php echo $list->city;?></td>
                                                <td><?php echo $list->currency;?></td>
                                                <?php if($list->company_comission_type=='Per'){$list->company_comission_type='Percentage';} ?>
                                                <td><?php echo $list->company_comission_rate.' '.$list->company_comission_type;?></td>
                                                <td><?php echo $list->minbase_fair.' '.$list->currency;;?></td>
                                                <td><?php echo $list->min_distance.' '.$list->min_distanceUnit;?></td>
                                                <td><?php echo $list->mini_distancefair.' '.$list->currency;;?></td>
                                                <td><?php echo $list->regularChargeEveryDistance.' '.$list->regularChargeEveryDistance_unit;?></td>
                                                <td><?php echo $list->regularChargeForDistance;?></td>
                                                <td><?php echo $list->stndCancelChargeDriver.' '.$list->cancelChargeUnitDriver;?></td>
                                                <td><?php echo $list->stndCancelChargePassenger.' '.$list->cancelChargeUnitPassenger;?></td>
                                                <td><?php echo $list->WeeklyCancellationLimit.' Rides';?></td>
                                                <td>                                                    
                                                    <label class="switch">
                                                    <?php $pre_later = $list->ride_later_status;
                                                    if($pre_later=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->fair_id;?>,'Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->fair_id;?>,'On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>
                                                <td><a href="<?php echo site_url('Vehicle/fare_full_details/'.$list->fair_id);?>">Full Details</a></td>
                                                <td><a href="<?php echo site_url('Vehicle/update_fare/'.$list->fair_id);?>">Edit</a>
                                                </td>
                                                <td><a href="<?php echo site_url('Vehicle/update_surcharge/'.$list->fair_id);?>">Edit</a>
                                                </td>
                                        <?php } ?>
                                        </tbody>
                                    </table> 
                                    </div>                                   
                                    </div>
                                </div>

                            </div>
                            <!-- END DATATABLE EXPORT -->
                        </div>
                    </div>
                </div>         



                <!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('layout/second_footer');?> 

<script>   
   function update(fare_id,status){
        var r = confirm('Are you realy want to perform this action');
        if(r==true){ 
            $(".sp-pre-con").css("display", "block");           
            $.ajax({
                type:'post',                
                url:'<?php echo site_url("Vehicle/update_laterBooking_status");?>',
                data:{'fare_id':fare_id,'status':status},
                success:function(res){
                    $(".sp-pre-con").css("display", "none");
                    //console.log(res);
                    alert(res);
                    location.reload(true);
                },
                error:function(res){
                    $(".sp-pre-con").css("display", "none");
                    console.log(res);
                }
            });
        }
        else{
            alert('heyy');
        }
   }
</script>