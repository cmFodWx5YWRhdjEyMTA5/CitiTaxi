<?php $data['page']='driver'; $data['title']='Driver list'; $this->load->view('layout/header',$data);?>
    
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

                                    <h3 class="panel-title"><strong>Driver</strong>  Details</h3>
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
                                            <th style="min-width:80px;">Status</th>
                                            <th style="min-width:80px; text-align:center">Driver ID</th>
                                            <th style="width:50px !important; text-align:center">Rating</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Company Name</th>
                                            <th>License ID</th>
                                            <th>Image</th>
                                            <th>Address</th>
                                            <th>Creat_At</th>
                                            <th>Driver Wallet</th>
                                            <th style="text-align:center">Status (online/offline)</th>
                                            <th>Other Details</th>                                           
                                            <th style="min-width:130px;">Action</th>
                                            <th style="min-width:50px; text-align:center">Edit</th>
                                            <th style="min-width:50px; text-align:center">Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($userlist as $list) { $status = $list->activeStatus; ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td style="font-size:14px;<?php if($status=='Active'){?> color:blue;<?php }else{?>color:red;<?php }?>"><strong><?php echo  $list->activeStatus; ?></strong>
                                                    <br><?php echo $list->suspend_type; ?>
                                                </td>
                                                <td><?php echo $list->id; ?></td>
                                                <td><!--Rating--></td>
                                                <td><?php echo $list->name; ?></td>                                                
                                                <td><?php echo $list->email; ?></td>
                                                <td><?php echo $list->mobile;?></td>
                                                <td><?php echo $list->fleet_company;?></td>
                                                <td><?php echo $list->licenseNumber;?></td>
                                                <td>
                                                <?php if($list->image_type==0)
                                                {
                                                    echo "<img src=".base_url()."userimage/".$list->image." width='60px' height='60px'>";    
                                                }
                                                else
                                                {
                                                    echo "<img src=".$list->image." width='60px' height='60px'>"; 
                                                }
                                                ?>
                                                    
                                                </td>                                
                                                <td>
                                                    <?php echo $list->address.' /'.'<br>';
                                                          echo $list->city.' , '.$list->nationality;
                                                    ?>
                                                </td>
                                                <td><?php $t=$list->created_at;  $s=explode(" ",$t); $e=implode(" / ",$s);
                                                         echo $e; ?>
                                                </td>
                                                <td><!-- wallet A/c--></td>
                                                <td style="text-align:center; color:red;"><?php echo $list->online_status; ?></td>
                                                <td><a href="<?php echo site_url('Driver/other_details/'.$list->id);?>"><button class="btn btn-success">Other Details</button></a>
                                                </td>
                                                <td>
                                                    <div class="form-group">                                         
                                                        <select class="form-control" id="<?php echo $list->id;?>">
                                                            <option>--Select Action--</option>
                                                            <option value="Trip">Trip History</option>
                                                            <?php if($status=='Banned' or $status=='Suspended'){?>          
                                                            <option value="Active">Active</option>
                                                            <?php } else{ ?>
                                                            <option value="Banned">Banned</option>
                                                            <?php }?>
                                                            <option value="days3">Suspend 3 days</option>
                                                            <option value="days7">Suspend 7 days</option>
                                                            <option value="days30">Suspend 30 days</option>
                                                            <option value="Scrore">Add Scrore</option>
                                                        </select>                                                       
                                                    </div>
                                                </td>                                               

                                                <td>
                                                    <a href="<?php echo site_url('Driver/update/'.$list->id);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                                <td>
                                                 <a href="<?php echo site_url('Driver/delete'.$list->id);?>">
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