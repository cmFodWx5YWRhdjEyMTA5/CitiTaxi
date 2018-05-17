<?php $data['page']='vehicle'; $data['title']='fair list'; $this->load->view('layout/header',$data);?>
    
    <style>
        table tbody th,td{
            border-left: 1px solid black;
        }
        .panel-body.panel-body-table th{
            padding:8px 25px !important;
        }
        .panel-body.panel-body-table td{
            padding:8px 15px !important;
        }
        
    </style>
            <!-- PAGE CONTENT WRAPPER -->

    <div class="page-content-wrap">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>Vehicle Fair full Details</strong></h3>
                        <center><span style="color:red; font-weight:600">**Per= Percentage</span></center>
                    </div> 

                        <div>
                            <div class="col-md-4 col-xs-6" style="background: #009ae7;color: white; padding: 10px 25px;" >Title</div>
                            <div class="col-md-8 col-xs-6" style="background: #009ae7;color: white; padding: 10px 13px;">Value</div>
                        </div>                          

                    <div class="panel-body form-group-separated">
                        <div class="panel-body panel-body-table">
                            <div class="table-responsive">
                                <div style="overflow:scroll;max-height:580px;">
                                    <table class="table table-bordered table-striped table-actions">
                                        <tbody>                                           
                                            <tr>
                                                <th class="col-md-4 col-xs-6">Service Name</th> 
                                                <th class="col-md-8 col-xs-6" style="color:red"><?php echo $list->service_name; ?></th>
                                            </tr>
                                            <tr>
                                                <th>Description</th> 
                                                <td><?php echo $list->description; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Max load</th> 
                                                <td><?php echo $list->maximum_load; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Country and Currency</th> 
                                                <td><?php echo $list->country.' ( '.$list->currency.' )';  ?></td>
                                            </tr>
                                            <tr>
                                                <th>City</th> 
                                                <td><?php echo $list->city;?></td>
                                            </tr>
                                            <tr>
                                                <th>Vehicle Type</th> 
                                                <td><?php echo $list->vehicle_type;?></td>
                                            </tr>
                                            <?php if($list->company_comission_type=='Per'){$list->company_comission_type='Percentage';} ?>
                                            <tr>
                                                <th>Company Commission</th> 
                                                <td><?php echo $list->company_comission_rate.' ( '.$list->company_comission_type.' )';  ?></td>
                                            </tr>
                                            <tr>
                                                <th>Distance Unit(Km/Mile)</th> 
                                                <td><?php echo $list->distanceUnit;?></td>
                                            </tr>
                                            <tr>
                                                <th>Minimum base fair</th> 
                                                <td><?php echo $list->minbase_fair.' '.$list->currency;?></td>
                                            </tr>
                                            <tr>
                                                <th>Minimum distance</th> 
                                                <td><?php echo $list->min_distance.' '.$list->min_distanceUnit; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Mini distance fair</th> 
                                                <td><?php echo $list->mini_distancefair.' '.$list->currency; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Regular Charge(Min)</th> 
                                                <td><?php echo $list->regularChargeEveryDistance.' '.$list->regularChargeEveryDistance_unit; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Regular x min Charge</th> 
                                                <td><?php echo $list->regularChargeForDistance.' '.$list->currency;?></td>
                                            </tr>
                                             <tr>
                                                <th>Per minutes charge Status</th> 
                                                <td style="color:blue;font-size:14px"><?php echo $list->perMinChargeStatus;?></td>
                                            </tr>
                                             <tr>
                                                <th>Every Minutes for charge (every x mins)</th> 
                                                <td><?php echo $list->unitPerMinuteforCharge.' Minute'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Free Waiting Minute</th> 
                                                <td><?php echo $list->regularFreeWaitingMinute.' Minute'; ?></td>
                                            </tr>

                                            <tr>
                                                <th>Waiting charge every (x) Minutes</th> 
                                                <td><?php echo $list->regularWaitingPeriodForCharge.' Minute'; ?></td>
                                            </tr>
                                            <tr>
                                                <th>Waiting Charge for (x) Minutes</th> 
                                                <td><?php echo $list->regularWaitingPeriodCharge.' '.$list->currency; ?></td>
                                            </tr>

                                            <tr>
                                                <th>Morning Charge Status</th> 
                                                <td style="color:blue;font-size:14px"><?php echo $list->morningChargeStatus;?> </td>
                                            </tr>
                                            <tr>
                                                <th>Morning Surcharge</th> 
                                                <td><?php echo $list->morningSurchargePrice.' ('.$list->morningSurchargeUnit.')';?></td>
                                            </tr>
                                            <tr>
                                                <th>Morning Surcharge Time</th> 
                                                <td><?php echo $list->morningSurchargeTimeStart.' - '.$list->morningSurchargeTimeEnd; ?></td>
                                            </tr>
                                            

                                            <tr>
                                                <th>Evening Charge Status</th> 
                                                <td style="color:blue;font-size:14px"><?php echo $list->eveningChargeStatus;?> </td>
                                            </tr>
                                            <tr>
                                                <th>Evening Surcharge</th> 
                                                <td><?php echo $list->eveningSurchargePrice.' ('.$list->eveningSurchargeUnit.')';?></td>
                                            </tr>
                                            <tr>
                                                <th>Evening Surcharge Time</th> 
                                                <td><?php echo $list->eveningSurchargeTimeStart.' - '.$list->eveningSurchargeTimeEnd; ?></td>
                                            </tr>
                                            
                                            <tr>
                                                <th>MidNight Charge Status</th> 
                                                <td style="color:blue;font-size:14px"><?php echo $list->midNightChargeStatus;?> </td>
                                            </tr>
                                            <tr>
                                                <th>MidNight Surcharge</th> 
                                                <td><?php echo $list->midNightSurchargePrice.' ('.$list->midNightSurchargeUnit.')';?></td>
                                            </tr>
                                            <tr>
                                                <th>MidNight Surcharge Time</th> 
                                                <td><?php echo $list->midNightSurchargeTimeStart.' - '.$list->midNightSurchargeTimeEnd; ?></td>
                                            </tr>
                                            
                                            <tr>
                                            <th colspan="2" style="color:blue; font-size: 14px; font-weight:600"><u>Cancellation Charges</u></th>
                                            </tr>                                            

                                            <tr>
                                                <th>Standar Cancellatioin Charge (Driver)</th> 
                                                <td><?php echo $list->stndCancelChargeDriver.' '.$list->cancelChargeUnitDriver; ?></td>
                                            </tr>                                             

                                            <tr>
                                                <th>Standerd Cancellation Charge (Passenger)</th> 
                                                <td><?php echo $list->stndCancelChargePassenger.' '.$list->cancelChargeUnitPassenger ?></td>
                                            </tr>                                            
                                            
                                            <tr>
                                                <th>Mutiple Stop Booking Surcharge (Flat Rate)</th> 
                                                <td><?php echo $list->multiStopCharge.' '.$list->currency;?></td>
                                            </tr>
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


</script>