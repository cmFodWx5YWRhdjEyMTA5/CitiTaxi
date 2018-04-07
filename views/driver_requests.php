<?php $data['page']='driver'; $data['title']='Driver Request'; $this->load->view('layout/header',$data);?>
    
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

                                    <h3 class="panel-title"><strong>Driver Request</strong></h3>
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
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>                                            
                                            <th>Driving License</th>
                                            <th>Car Image</th>
                                            <th>Other Document</th>
                                            <th>Request At</th>                                                                
                                            <!-- <th style="min-width:130px;">Action</th>-->
                                            <th>Complete</th>
                                            <th style="min-width:50px; text-align:center">Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($requests as $list) { $status = $list->activeStatus; ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td><?php echo $list->name; ?></td>                                                
                                                <td><?php echo $list->email; ?></td>
                                                <td><?php echo $list->mobile;?></td>                                                
                                                <?php $license = getSingleDetail('driver_license',array('user_id'=>$list->id)); ?>
                                                <td><?php if(!empty($license)){ ?>
                                                    <img src="<?php echo base_url('licenseImage/'.$license->licenseImage); ?>" width="80" height="80">
                                                <?php } ?></td>
                                                <td>
                                                    <a href="#" data-toggle="modal" data-target="#exampleModal">
                                                        <button class="btn btn-submit" onclick="vehicleImages(<?php echo $list->id;?>)">Vechile Image</button></a>
                                                </td>
                                                <?php $otherdoc = getSingleDetail('driver_otherdocument',array('user_id'=>$list->id)); ?>
                                                <td>  

                                                    <?php if(!empty($otherdoc)){ 
                                                        $type = substr($otherdoc->document_type,0,3);
                                                        if($type=='app')
                                                        {$image = 'pdfimage.png';} else {$image=$otherdoc->document;}
                                                     ?>
                                                    <a target="blank" href="<?php echo base_url('otherdocument/'.$otherdoc->document);?>" download>
                                                        <img border="0" src="<?php echo base_url('otherdocument/'.$image);?>"  width="80" height="80">
                                                    </a>                                                        
                                                    <?php } ?>
                                                </td>
                                                
                                                <td><?php $t=$list->created_at;  $s=explode(" ",$t); $e=implode(" / ",$s);
                                                         echo $e; ?>
                                                </td>
                                                
                                                <!--td>
                                                    <div class="form-group">                                         
                                                        <select class="form-control" id="<!?php echo $list->id;?>">
                                                            <option>--Select Action--</option>
                                                            <option value="Trip">Trip History</option>
                                                            <!?php if($status=='Banned' or $status=='Suspended'){?>          
                                                            <option value="Active">Active</option>
                                                            <!?php } else{ ?>
                                                            <option value="Banned">Banned</option>
                                                            <!?php }?>
                                                            <option value="days3">Suspend 3 days</option>
                                                            <option value="days7">Suspend 7 days</option>
                                                            <option value="days30">Suspend 30 days</option>
                                                            <option value="Scrore">Add Scrore</option>
                                                        </select>                                                       
                                                    </div>
                                                </td--> 
                                                <td>
                                                    <button class="btn btn-submit" onclick="completeRequest(<?php echo $list->id;?>)"> Complete Registration</button></a>
                                                </td>                                              
                                               
                                                <td>
                                                 <a href="<?php echo site_url('Driver/delete/'.$list->id);?>">
                                                  <i class="fa fa-trash-o fa-fw">
                                                  <strong>Delete</strong></i>
                                                 </a>
                                                </td>          
                                            </tr>
                                            <script>
                                                $(document).ready(function(){                                               
                                                    $('#<?php echo $list->id;?>').change(function() 
                                                    {
                                                      if ( $('#<?php echo $list->id;?>').val() == 'Trip' )Trip(<?php echo $list->id; ?>)
                                                      else if ( $('#<?php echo $list->id;?>').val() == 'Banned' )
                                                      {
                                                      if (confirm('Are you realy want to banne this user?')) Banned(<?php echo $list->id; ?>,'Banned');return false;
                                                      }  
                                                      else if ( $('#<?php echo $list->id;?>').val() == 'Active' )
                                                      {
                                                      if (confirm('Are you realy want to Active this user? If this user has suspended, It will Active.')) Banned(<?php echo $list->id; ?>,'Active');return false;
                                                      }                                                      
                                                      else if ( $('#<?php echo $list->id;?>').val() == 'days3' ) 
                                                      {
                                                      if (confirm('Are you realy want to Suspend this user for 3days?')) Suspend(<?php echo $list->id; ?>,3);return false;
                                                      }
                                                      else if ( $('#<?php echo $list->id;?>').val() == 'days7' ) 
                                                      {
                                                      if (confirm('Are you realy want to Suspend this user for 7days?')) Suspend(<?php echo $list->id; ?>,7);return false;
                                                      }
                                                      else if ( $('#<?php echo $list->id;?>').val() == 'days30' )
                                                      {
                                                      if (confirm('Are you realy want to Suspend this user for 30 days?')) Suspend(<?php echo $list->id; ?>,30);return false;
                                                      }
                                                      else if ( $('#<?php echo $list->id;?>').val() == 'Score' ) Score(<?php echo $list->id; ?>);
                                                    });
                                                    });                                                
                                                </script>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <!-- Modal content-->
        <div class="modal-content">      
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="col-md-12" id="showImg"></div>       
            <div class="modal-footer">
               <button type="button" class="btn btn-submit" data-dismiss="modal">Close</button>
            </div>
        </div>    
    </div>
</div>


<script>
    function completeRequest(id)
    {
        var location  = site_url+'/Driver/complete_registration/'+id;
        //console.log(location);
        window.location.href= location;
    }
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

    function vehicleImages(user_id)
    {     
        var images = '';
        $('#showImg').html('');
        $.ajax({
            type:'POST',
            data:{'user_id':user_id},
            url:'<?php echo site_url('Driver/vehicleimage');?>',
            dataType:'json',
            success:function(res)
            {
                if(res.error==0)
                {
                    for(var i=0; i<res.images.length; i++)
                    { 
                        images = '<div class="col-md-4"><img src="'+base_url+"/vechicleImage/"+res.images[i].vechile_image+'" width="99%" height="120px" style="padding-bottom:2px;"></div>';
                        $('#showImg').append(images);
                        //console.log(images);
                        images='';
                    }
                }
                else
                {
                    $('#showImg').append(res.message);
                }
            }
        });
        
    }
</script>