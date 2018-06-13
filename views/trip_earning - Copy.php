<?php $data['page']='revenue'; $data['title']='Trip Earning'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Trip Earning</strong></h3>
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
                                  <div class="panel-body" style="background:#960707;color:white;box-shadow:0px 9px 2px #dad7d7fa;">
                                    <div class="col-md-3 col-xs-3">
                                      <label  class="col-md-8 col-xs-8 control-label">Total Trips</label>
                                      <div class="col-md-3 col-xs-3">
                                          <?php $tb = getCount('booking',array()); echo $tb; ?>
                                      </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Completed Trips</label>
                                      <div class="col-md-3 col-xs-3">
                                          <?php $cb = getCount('booking',array('booking_status'=>4)); echo $cb; ?>
                                      </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Trip Earning</label>
                                      <div class="col-md-3 col-xs-3">
                                       <?php $te = getSum('booking','total_fare',array('booking_status'=>4)); echo $te; ?>
                                      </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Card Payment</label>
                                      <div class="col-md-3 col-xs-3">
                                          <?php $te = getSum('booking','total_fare',array('booking_status'=>4,'payment_type'=>'Card')); echo $te; ?>
                                      </div>
                                    </div>                                  

                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Cash Payment</label>
                                      <div class="col-md-3 col-xs-3">
                                          <?php $te = getSum('booking','total_fare',array('booking_status'=>4,'payment_type'=>'Cash')); echo $te; ?>
                                      </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Wallet Payment</label>
                                      <div class="col-md-3 col-xs-3">
                                          <?php $te = getSum('booking','total_fare',array('booking_status'=>4,'payment_type'=>'citipay')); echo $te; ?>
                                      </div>
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Total Driver Earnign</label>
                                      <div class="col-md-3 col-xs-3">
                                      <?php  $total_earning    = getSum('booking_earning','driver_earning',array()); 
                                              echo $total_earning;
                                      ?>
                                      </div>
                                    </div> 
                                    <div class="col-md-3 col-xs-3">
                                      <label class="col-md-8 col-xs-8 control-label">Total Company Earnign</label>
                                      <div class="col-md-3 col-xs-3">
                                          <?php  $total_commission = getSum('booking_earning','total_commission',array()); 
                                                  echo $total_commission;
                                          ?>
                                      </div>
                                    </div>                                                                      
                                </div>                               
                                  
                      <!-- ======================================================================================= -->
                                
                                <div class="panel-body">
                                
                                  <div class="table-responsive">
                                    <div id="list_table" style="overflow:scroll;">
                                      <table id="example" class="table display">
                                        <thead>
                                            <tr>
                                            <th>Sr.No</th>
                                            <th>TripID</th>
                                            <th>Status</th>
                                            <th>Passenger ID</th>
                                            <th>Passenger Name</th>
                                            <th>Passenger Email</th>
                                            <th>Passenger Phone</th>
                                            <th>Pick Up Location</th>
                                            <th>Dropoff Location</th>
                                            <th>Driver ID</th>
                                            <th>Driver Name</th>
                                            <th>Driver Email</th>
                                            <th>Driver Phone</th>
                                            <th>Driver Trip Earnign</th>
                                            <th>Promocode Apply</th>
                                            <th>Company Commission</th>
                                            <!--th>Pay To Driver</th-->                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        if(isset($datalist)){  $i=1;                                          
                                        foreach($datalist as $list) {
                                        $dropof=array();
                                         //$status = $list->activeStatus; ?>
                                            <tr>
                                              <td style="text-align:center"><?php echo $i++;?></td>
                                              <td><?php echo $list->booking_id; ?></td>
                                              <?php if($list->booking_status=='0'){ ?>
                                              <td style="color:blue">Assigned</td>
                                              <?php }elseif($list->booking_status=='1'){ ?>
                                              <td style="color:orange">Accept</td>                                             
                                              <?php }elseif($list->booking_status=='2'){ ?>
                                              <td style="color:red">Reject by driver</td>
                                              <?php }elseif($list->booking_status=='3'){?>
                                              <td style="color:red">Reject by Passenger after accept</td>
                                              <?php }elseif($list->booking_status=='4'){ ?>
                                              <td style="color:green">Complete</td>
                                              <?php } elseif($list->booking_status=='5'){ ?>
                                              <td style="color:orange">Arrived</td>                                            
                                              <?php }elseif($list->booking_status=='6'){ ?>
                                              <td style="color:blue">Trip start</td>                                            
                                              <?php } elseif($list->booking_status=='7'){ ?>
                                              <td style="color:red">Reject by Passenger before accept</td>
                                              <?php } ?>
                                              <td><?php echo $list->customer_id;?></td>
                                              <?php $customer = getSingleDetail('users',array('id'=>$list->customer_id));?>
                                              <td><?php if(!empty($customer)){echo $customer->name;}?></td>
                                              <td><?php if(!empty($customer)){echo $customer->email;}?></td>
                                              <td><?php if(!empty($customer)){echo $customer->mobile;}?></td>
                                              <td><?php echo $list->pickup;?></td>
                                              <td>
                                                <a  href="#" data-toggle="modal" data-target="#dropoffs">
                                                  <button type="button" class="btn btn-submit" onclick="dropoff(<?php echo $list->booking_id;?>)">Address</button>
                                                </a>
                                              </td>
                                              <td><?php echo $list->driver_id;?></td>

                                              <?php $driver = getSingleDetail('users',array('id'=>$list->driver_id));?>
                                              <td><?php if(!empty($driver)){echo $driver->name;}?></td>
                                              <td><?php if(!empty($driver)){echo $driver->email;}?></td>
                                              <td><?php if(!empty($driver)){echo $driver->mobile;}?></td>

                                              <?php $earning = getSingleDetail('booking_earning',array('booking_id'=>$list->booking_id));?>
                                              <td>
                                              <?php if(!empty($earning)){echo $earning->driver_earning.' '.$earning->currency;}?></td>
                                              <td><!--Promotion Apply--></td>
                                              <td>

                                              <?php if(!empty($earning))
                                              {echo $earning->total_commission.' '.$earning->currency;}?></td>
                                            </tr>
                                        <?php }} ?>
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




