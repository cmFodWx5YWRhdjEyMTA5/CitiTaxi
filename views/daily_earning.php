<?php $data['page']='revenue'; $data['title']='Daily Earning'; $this->load->view('layout/header',$data);?>
<style type="text/css">
  .widget.widget-item-icon .widget-data{
    padding-left: 0px !important;
  }
  .widget.widget-default{
    background:#0095e8 !important;
    color:white !important;
  }
  .widget .widget-int{
    color:white !important;
    font-size:16px !important;
    line-height: 16px !important;
  }
</style>  
          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Daily Earning</strong></h3>
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

                                <?php 

                                  $tbooking=0; $completebooking=0; $triperning=0; $cardpay=0; $cashpay=0; $walletpay=0; $driver_earning=0; $company_earnign=0;$paypal=0;
                                  if(isset($datalist)){                                    
                                    $tbooking = getCount('booking',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend));
                                    $completebooking = getCount('booking',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4));
                                    $triperning = getSum('booking','total_fare',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4));
                                    $cardpay = getSum('booking','total_fare',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4,'payment_type'=>'Card'));
                                    $cashpay = getSum('booking','total_fare',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4,'payment_type'=>'Cash'));
                                    $walletpay = getSum('booking','total_fare',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4,'payment_type'=>'citipay'));
                                    $paypal = getSum('booking','total_fare',array("driver_id"=>$driver_id,'booking_at_string>='=>$earningDatest,'booking_at_string<='=>$earningDatend,'booking_status'=>4,'payment_type'=>'paypal'));
                                  }


                                ?> 
                                </div>

                                <div class="panel-body form-group-separated" style="min-height: 50px;">
                                  <div class="panel-body panel-body-table">
                                      <div class="col-md-3">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Cash Payment</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php echo $cashpay; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Wallet Payment</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php  echo $walletpay; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Paypal Payment</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php  echo $paypal; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Card Payment</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php echo $cardpay; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div>

                                      <!-- ================================================ -->

                                      <div class="col-md-4">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Total Trips</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php echo $tbooking; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Completed Trips</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php echo $completebooking; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div> 
                                      <div class="col-md-4">
                                        <div class="widget widget-default widget-item-icon">                 
                                            <div class="widget-data">
                                              <div class="widget-title">Trip Earning</div>                                   
                                              <div class="widget-int num-count">
                                                  <?php echo $triperning; ?>
                                              </div>
                                            </div>                                
                                        </div>
                                      </div>                                                                      
                                  </div>
                                </div>
                                                               
                                  
                      <!-- ======================================================================================= -->
                      <div class="panel-body">
                        <form action="<?php echo site_url('Vehicle/daily_earning'); ?>" method="POST">
                          <div class="col-md-3 col-xs-3" style="margin-top:.5%;">
                              <input type="text" name="driver_id" class="form-control" placeholder="Enter Driver id" required>
                          </div>
                          <div class="col-md-3 col-xs-3" style="margin-top:.5%;">
                                  <input type="text" name="date" id="date" data-provide="datepicker" class="form-control" placeholder="Enter Date" required>
                          </div>
                          <div class="col-md-2 col-xs-3">
                            <input type="submit" name="search" value="Search" class="btn btn-submit" style="max-width:200px; margin:2px 0; width:100%;">
                          </div>
                          <div class="col-md-2 col-xs-3">
                            <input type="reset" value="Reset" class="btn btn-reset" style="max-width:200px; margin:2px 0; width:100%;"> 
                          </div>                        
                        </form>
                      </div>                   

                      <div class="panel-body">                               
                        <div class="table-responsive">
                          <div id="list_tab" style="overflow:scroll;">
                            <table id="example" class="table display">
                              <thead>
                                <tr>   
                                  <th>Sr. No.</th>                                         
                                  <th>TripID</th>
                                  <th>Driver ID</th>
                                  <th>Driver Name</th>
                                  <th>Driver Email</th>
                                  <th>Driver Phone</th>
                                  <th>Date</th>
                                  <th>Promo Applied</th>
                                  <th>Trip Earning</th>
                                  <th>Driver Earning</th>                                  
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
                                      <td><?php echo $list->driver_id;?></td>
                                      <?php $driver = getSingleDetail('users',array('id'=>$list->driver_id));?>
                                      <td><?php if(!empty($driver)){echo $driver->name;}?></td>
                                      <td><?php if(!empty($driver)){echo $driver->email;}?></td>
                                      <td><?php if(!empty($driver)){echo $driver->mobile;}?></td>
                                      <td><?php echo $list->booking_at; ?></td>
                                      <td>No</td>
                                      <?php $earning = getSingleDetail('booking_earning',array('booking_id'=>$list->booking_id));?>
                                      <td>                                      
                                      <?php if(!empty($earning)){echo $earning->total_fare.' '.$earning->currency;}?></td>
                                      <td>                                      
                                      <?php if(!empty($earning)){echo $earning->driver_earning.' '.$earning->currency;}?></td>
                                      <td>
                                      <?php if(!empty($earning)){echo $earning->total_commission.' '.$earning->currency;}?></td>
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

<script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/bootstrap/bootstrap-datepicker.js"></script>
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
<script>
 $(document).ready(function(){
  $('#list_tab').height($(window).height() -350);
    var date = new Date();
    date.setDate(date.getDate());
    $('#date').datepicker({ 
        endDate: date
    });
  });
 
</script>
 





