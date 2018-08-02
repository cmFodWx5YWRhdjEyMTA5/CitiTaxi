<?php $data['page']='coupon'; $data['title']='Ride Redeem History'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Ride Redeem History</strong></h3>                                    
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
                                                <th style="min-width:50px;">#</th>                                                
                                                <th style="min-width:80px;">Promo Code</th>
                                                <th style="min-width:80px;">Bonus Amount</th>
                                                <th style="min-width:80px;">Redeem At</th>
                                                <th>Passenger ID</th>
                                                <th>Passenger Name</th>
                                                <th>Passenger Email</th>
                                                <th>Driver ID</th>                                                
                                                <th>Trip ID</th>
                                                <th>Trip From</th>
                                                <th>Trip To</th>
                                                <th>Trip Payment</th>                                                
                                                <th>Promo type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i=1;  
                                            foreach($history as $list) {  $status = $list->status;?>
                                                <tr>
                                                    <td style="text-align:center"><?php echo $i++;?></td>
                                                    <td style="color:red;"><strong><?php echo $list->promocode;?></strong></td>
                                                    <td><?php echo $list->promo_earn;?></td>
                                                    <td><?php echo $list->use_at;?></td>
                                                    <td><?php echo $list->user_id;?></td>
                                                <?php 
                                                    $passenger_name =''; $passenger_email='';$from='';$driver_id='';$payment='';
                                                    $passenger = getSingleDetail('users',array('id'=>$list->user_id));
                                                    if(!empty($passenger)){
                                                        $passenger_name  = $passenger->name;
                                                        $passenger_email = $passenger->email;
                                                    }
                                                    $trip = getSingleDetail('booking',array('booking_id'=>$list->booking_id));
                                                    if(!empty($trip)){
                                                        $from      = $trip->pickup;
                                                        $driver_id = $trip->driver_id;
                                                        $payment   = $trip->total_fare.' '.$trip->currency;
                                                    }
                                                ?>
                                                    <td ><?php echo $passenger_name;?></td>
                                                    <td ><?php echo $passenger_email;?></td>
                                                    <td ><?php echo $list->booking_id;?></td>
                                                    <td ><?php echo $from;?></td>
                                                    <td ><?php $to = getMultipleDetail('booking_dropoffs',array('booking_id'=>$list->booking_id)); 
                                                    if(!empty($to)){
                                                        foreach ($to as $k) {
                                                            echo $k->dropoff.',';
                                                        }
                                                    }

                                                    ?></td>
                                                    <td ><?php echo $driver_id;?></td>
                                                    <td ><?php echo $payment;?></td>
                                                    <td ><strong><?php if($list->promo_type==0){echo 'Immediate';}
                                                    else{ echo 'After Complete';} ?></strong></td>
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
<?php $this->load->view('layout/second_footer');?> 
<script>
   function changeStatus(id,status)
    {
        //alert(status);
        $.ajax({
            type:'post',
            data:{'promo_id':id,'status':status},
            url:"<?php echo site_url('Home/changePromoStatus');?>",
            success:function(res)
            {
                alert(res);
                location.reload();
            }
        });
    }
    function share(id,country){
        var url = '<?php echo site_url('Home/promo_users/'); ?>'+id+'/'+country;        
        var win = window.open(url,'_self');
        // var win = window.open(url, '_blank');
        win.focus();      
    }
</script>