<?php $data['page']='booking'; $data['title']='Pending Booking'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Pending Booking</strong></h3>
                                    <?php if(isset($success)==1){ ?>
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
                                     <div style="overflow:scroll; height:600px;">
                                     <table id="example" class="table display">
                                        <thead>
                                            <tr>
                                            <th>Sr.No</th>
                                            <th style="min-width:80px;">TripID</th>
                                            <th style="min-width:80px;">Status</th>
                                            <th>Assigned Driver</th>
                                            <th>Driver Contact</th>
                                            <th>Booking At</th>
                                            <th>Actual Pickup Time</th>                                            
                                            <th>Passenger</th>                                            
                                            <th>Pick Up Location</th>
                                            <th>Dropoff Location</th>
                                            <th style="min-width:100px;">Distance</th>
                                            <th style="min-width:100px;">Fare</th>
                                            <th>Action</th> <!--Assign Driver-->                                         
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                         if(!empty($userlist)){ 
                                        foreach($userlist as $list) {
                                        $dropof=array();
                                         //$status = $list->activeStatus; ?>
                                            <tr>
                                              <td style="text-align:center"><?php echo $i++;?></td>  
                                              <td style="text-align:center"><?php echo $list->booking_id;?></td>


                                              <?php if($list->booking_status=='0'){ ?>
                                              <td style="color:blue">Assigned</td>
                                              <?php }elseif($list->booking_status=='1'){ ?>
                                              <td style="color:orange">Accept</td>                                             
                                              <?php }elseif($list->booking_status=='2'){ ?>
                                              <td style="color:red">Reject by driver</td>
                                              <?php }elseif($list->booking_status=='3'){?>
                                              <td style="color:red">Reject by customer after accept</td>
                                              <?php }elseif($list->booking_status=='4'){ ?>
                                              <td style="color:green">Complete</td>
                                              <?php } elseif($list->booking_status=='5'){ ?>
                                              <td style="color:orange">Arrived</td>                                            
                                              <?php }elseif($list->booking_status=='6'){ ?>
                                              <td style="color:blue">Trip start</td>                                            
                                              <?php } elseif($list->booking_status=='7'){ ?>
                                              <td style="color:red">Reject by custmer before accept</td>
                                              <?php } elseif($list->booking_status=='8'){ ?>
                                              <td style="color:red">Pending</td>
                                              <?php } elseif($list->booking_status=='9'){ ?>
                                              <td style="color:red">For Admin</td>
                                              <?php } 

                                              if($list->driver_id!=0){$driver=getSingleDetail('users',array('id'=>$list->driver_id));
                                              $name = $driver->name; $mobile=$driver->mobile;} 
                                              else{ $mobile=''; $name='';}
                                              ?>                                             


                                              <td style="color:blue;font-weight:600;"><?php echo $name; ?></td>
                                              <td style="color:blue;font-weight:600;"><?php echo $mobile; ?></td>

                                              <td><?php echo $list->booking_at; ?></td>
                                              <td><strong><?php echo $list->later_pickup_at;?></strong></td>
                                              
                                              <td><?php echo getUserDetailsById($list->customer_id);?></td>
                                              <td><?php echo $list->pickup;?></td>
                                              <td>
                                                <a  href="#" data-toggle="modal" data-target="#dropoffs">
                                                  <button type="button" class="btn btn-submit" onclick="dropoff(<?php echo $list->booking_id;?>)">Address</button>
                                                </a>
                                              </td> 
                                              <td><?php echo $list->total_distance." ".$list->distance_unit;?></td>
                                              <td><?php echo $list->total_fare." ".$list->currency;?></td>
                                             <td>
                                              <?php if($list->booking_status=='9' or $list->booking_status=='2'){ ?>
                                                <a target="_blank" href="<?php echo site_url('Home/nearbydriver/'.$list->booking_id.'/'.$list->city);?>">
                                                  <button type="button" class="btn btn-submit">Show Nearby Driver</button>
                                                </a>
                                                <?php } else{?>
                                                  <button type="button" class="btn btn-reset">Show Nearby Driver</button>
                                                  <?php } ?>
                                              </td> 
                                             
                                            </tr>
                                        <?php } } ?>
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


<div class="modal fade" id="dropoffs" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title">Drop off locations </h4>
      </div>
      <div class="modal-body" id='details'>
        <div class="locations" style="font-size: 13px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>  

<script>
  function dropoff(booking_id)
  {   
    $(".locations").html('');
    $.ajax({
      type:'post',
      url:'<?php echo site_url("Home/get_dropoff_address"); ?>',
      data:{'booking_id':booking_id},
      dataType: "json",

      success:function(res)
      {
        console.log(res);                   
        if(res.success==1)
        { 
          var len = res.data.length;
          var j=1;
          var txt = "";
          for(i=0; i<len; i++){          
            txt +='<li>'+res.data[i].dropoff +'</li>';
          }           
          $(".locations").append(txt);
          console.log(txt);                   
          //console.log(res.data.length);                    
        }
        else{
           $(".locations").html('No dropoff location found');          
        }
      }
    });
  }
</script>




