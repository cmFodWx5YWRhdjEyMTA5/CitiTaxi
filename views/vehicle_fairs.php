<?php $data['page']='vehicle'; $data['title']='fair list'; $this->load->view('layout/header',$data);?>
    
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

                                    <h3 class="panel-title"><strong>Vehicle Fair Details</strong></h3>
                                    <center><span style="color:red; font-weight:600">**Per= Percentage</span></center>
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
                                     <div style="overflow:scroll; ,max-height:600px;">
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
                                            <th style="min-width:100px;">Peak Cancel charge(Driver)</th>
                                            <th style="min-width:100px;">Stnd Cancel charge(Customer)</th>                                            
                                            <th style="min-width:100px;">Peak Max Cancel booking(customer)</th>
                                            <th style="min-width:80px;"> Full Details</th>
                                            <th style="min-width:80px;">Edit</th>
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
                                                <td><?php echo $list->company_comission_rate.' '.$list->company_comission_type;?></td>
                                                <td><?php echo $list->minbase_fair.' '.$list->currency;;?></td>
                                                <td><?php echo $list->min_distance.' '.$list->min_distUnit;?></td>
                                                <td><?php echo $list->mini_distancefair.' '.$list->currency;;?></td>
                                                <td><?php echo $list->regularChargeUpon.' '.$list->regularChargeUpon_unit;?></td>
                                                <td><?php echo $list->uponMinuteCharge;?></td>
                                                <td><?php echo $list->stndCancelChargeDriver.' '.$list->cancelChargeUnitDriver;?></td>
                                                <td><?php echo $list->peakHrCancelChargeDriver.' '.$list->cancelChargeUnitDriver;?></td>
                                                <td><?php echo $list->stndCancelChargePassenger.' '.$list->cancelChargeUnitPassenger;?></td>
                                                <td><?php echo $list->peakHourBookingCancelbyPassenger.' Rides';?></td>
                                                <td><a href="<?php echo site_url('Vehicle/fair_full_details/'.$list->fair_id);?>">Full Details</a></td>
                                                <td><a href="">Edit</a></td>
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
    function Trip(id)
    {
        alert('Called function Trip');
    }
    function Banned(id,Status)
    {
        //alert(Status);
        $.ajax({
            method:'POST',
            url:'<?php echo site_url('Driver/updateStatus'); ?>',
            data:{'id':id,'activeStatus':Status},
            success:function(data)
            {
                alert(data);
                location.reload();
            }
        });
    }
    function Suspend(id,type)
    {
        $.ajax({
            method:'POST',
            url:'<?php echo site_url('Driver/Suspend'); ?>',
            data:{'id':id,'type':type},
            success:function(data)
            {
                alert(data);
                location.reload();
            }
        });
    }
</script>