<?php $data['page']='booking'; $data['title']='Near by Driver'; $this->load->view('layout/header',$data);?>
    
    <style>
        table tbody th,td{
            border-left: 1px solid black;
        }
    </style>
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Driver Details</strong></h3>
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
                                     <div style="overflow:scroll;max-height:600px;">
                                     <table id="example" class="table display">
                                        <thead>
                                            <tr>
                                            <th style="min-width:50px;">Sr.No</th>
                                            <th style="min-width:80px; text-align:center">Driver ID</th>
                                            <th style="min-width:50px !important; text-align:center">Rating</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Fleet Company Name</th>
                                            <th>Driver Live Address</th>
                                            <th style="min-width:100px;">Driver Latitude</th>
                                            <th style="min-width:100px;">Driver Longitude</th>
                                            <th style="min-width:100px;">Image</th>                               
                                            <th style="min-width:100px;text-align:center">Status (online/offline)</th>
                                            <th style="min-width:130px;">Action</th>                                         
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($userlist as $list) { $status = $list->activeStatus; ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                               <!--  <td style="font-size:14px;<?php if($status=='Active'){?> color:blue;<?php }else{?>color:red;<?php }?>"><strong><?php echo  $list->activeStatus; ?></strong>
                                                    <br><?php echo $list->suspend_type; ?>
                                                </td> -->
                                                <td><?php echo $list->id; ?></td>
                                                <td><?php echo get_rating($list->id);?></td>
                                                <td><?php echo $list->name; ?></td>                                                
                                                <td><?php echo $list->email; ?></td>
                                                <td><?php echo $list->mobile;?></td>
                                                <?php $fleet_company = getfleetDetail($list->fleet_id)->fleet_company; ?>
                                                <td><?php echo $fleet_company;?></td>
                                                 <?php $live = getSingleDetail('driver_live_location',array('user_id'=>$list->id));?>
                                                 <td>
                                                   <?php if(!empty($live)){echo $live->address;} else{ echo '';} ?>
                                                 </td>
                                                 <td><?php if(!empty($live)){echo $live->latitude;} else{ echo '';} ?></td>
                                                 <td><?php if(!empty($live)){echo $live->longitude;} else{ echo '';} ?></td>
                                                <td>
                                                <?php if($list->image_type==0){
                                                    echo "<img src=".base_url()."userimage/".$list->image." width='60px' height='60px' style='border-radius:33px'>";    
                                                }
                                                else{
                                                    echo "<img src=".$list->image." width='60px' height='60px' style='border-radius:33px'>"; 
                                                }
                                                ?>                                                    
                                                </td>                                
                                                <?php if($list->online_status=='online')
                                                { ?>
                                                <td style="text-align:center; color:#33b733;font-weight:600;font-size:13px"><?php echo $list->online_status; ?></td>
                                                <?php } else{ ?>
                                                <td style="text-align:center; color:red;font-weight:600;font-size:13px"><?php echo $list->online_status; ?>                                                   
                                                </td>   
                                                <?php }?>
                                                <td>                                               
                                                  <button type="button" class="btn btn-submit" onclick="assign(<?php echo $booking_id.','.$list->id;?>)">Assign Driver</button>          
                                                </td>
                                            </tr>
                                            
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
<!-- Modal to upload image -->


<?php $this->load->view('layout/second_footer');?> 

<script type="text/javascript">
  function assign(booking_id,driver_id)
  {
    //alert(booking_id);
    $.ajax({
      type:'POST',
      url:'<?php echo site_url("Home/assignbooking"); ?>',
      data:{'booking_id':booking_id,'driver_id':driver_id},
      success:function(res){
        alert(res);
        location.reload(true);
      },
    });
  }
</script>