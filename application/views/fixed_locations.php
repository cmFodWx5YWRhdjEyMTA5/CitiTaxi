<?php $data['page']='vehicle'; $data['title']='fixed locations'; $this->load->view('layout/header',$data);?> 

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Fixed locations</strong></h3>
                    <div class="btn-group pull-right">
                         <button type="button" class="btn btn-submit" onclick="addlocation()">
                             Add location
                         </button>   
                     </div>
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated">
                        <div class="panel-body panel-body-table">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th style="min-width:180px;">Pick Up Location</th>
                                                    <th style="min-width:100px;">Pick Up Latitude</th>
                                                    <th style="min-width:100px;">Pick Up Longitude</th>
                                                    <th style="min-width:180px;">Drop off Location</th>
                                                    <th style="min-width:100px;">Drop off Latitude</th>
                                                    <th style="min-width:100px;">Drop off Longitude</th>
                                                    <th style="min-width:80px;">Fix Charge</th>
                                                    <th>Timeing</th>
                                                    <th style="min-width:80px;">Vehicle Type</th>
                                                    <th style="min-width:100px;">Free Waiting Mins</th>
                                                    <th style="min-width:100px;">Waiting (x) Mins</th>
                                                    <th style="min-width:100px;">Waiting (x) Mins charge</th>
                                                    <th style="min-width:100px;">Status</th>
                                                    <th style="min-width:100px;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php $i=1; foreach ($fixlocation as $t =>$v) { ?>
                                                <tr">
                                                    <td class="text-center"><?php echo $i++; ?></td>
                                                    <td><?php echo $v->pickup; ?></td>
                                                    <td><?php echo $v->pickupLat; ?></td>
                                                    <td><?php echo $v->pickupLong; ?></td>
                                                    <td><?php echo $v->dropoff; ?></td>
                                                    <td><?php echo $v->dropoffLat; ?></td>
                                                    <td><?php echo $v->dropoffLong; ?></td>
                                                    <td><?php echo $v->fixcharge; ?></td>
                                                    <td><?php echo $v->startTime.'-'.$v->endTime; ?></td> 
                                                    <td><?php echo $v->service_name; ?></td>
                                                    <td><?php echo $v->free_waitingMin; ?></td>
                                                    <td><?php echo $v->waitingMinUnit; ?></td>
                                                    <td><?php echo $v->waitingMinUnitCharge; ?></td>
                                                    <td style="color:<?php if($v->status=='on'){ echo 'green';} else{echo 'red';} ?>"><strong><?php echo strtoupper($v->status); ?></strong></td>
                                                    <td>
                                                        <?php if($v->status=='on'){ $status ='off';} else{$status='on';}?>

                                                        <input type='button' class="btn btn-danger btn-rounded btn-sm" onClick="changeStatus(<?php echo $v->location_id;?>,<?php echo "'".$status."'";?>);" value="<?php echo $status; ?>">
                                                    </td>
                                                </tr>
                                                <?php } ?>                                           
                                               
                                            </tbody>
                                        </table>
                                    </div>                                

                                </div>               
                    </div>
                </div>
            </div>                    
        </div>

<?php $this->load->view('layout/footer');?> 


<script>
    
    function addlocation()
    {
       window.location.href='<?php echo site_url('Vehicle/add_fixLocation');?>';
    }

    function changeStatus(id,status)
    {      

        var postdata = {
                "data":{'status':status},
                "where":{'location_id':id},
                'table_name':'fixlocations'
                };
        var dataString = JSON.stringify(postdata);
        //alert(dataString);
        $.ajax({
            type:'POST',             
            data:{myData:dataString},
            url:"<?php echo site_url('vehicle/changeStatus');?>",
            success:function(res)
            {
              //console.log(res);
              alert(res);
              location.reload(true);
            }
        });
    }


   
</script>