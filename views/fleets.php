<?php $data['page']='fleet'; $data['title']='Fleet List'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">

                  <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Fleet List</strong></h3>
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
                                                <th style="min-width:80px;">Fleet ID</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Company name</th>
                                                <th>Address</th>
                                                <th>Country</th>
                                                <th>City</th>
                                                <th style="min-width:90px;">Image</th>  
                                                <th>Creat At</th>                                          
                                                <th style="min-width:50px; text-align:center">Edit</th>
                                                <th style="min-width:50px; text-align:center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($fleetlist as $list) {?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td><?php echo $list->fleet_id;?> </td>
                                                <td><?php echo $list->fleet_name; ?>    </td> 
                                                <td><?php echo $list->fleet_phone;?>   </td>      
                                                <td><?php echo $list->fleet_email; ?>   </td>
                                                <td><?php echo $list->fleet_company; ?>   </td>
                                                <td><?php echo $list->fleet_address; ?>   </td>
                                                <td><?php echo $list->country; ?>   </td>
                                                <td><?php echo $list->city; ?>   </td>
                                                <td>
                                                    <?php echo "<img src=".base_url()."fleetImage/".$list->image." width='60px' height='60px' style='border-radius:33px'>";
                                                    ?>  
                                                </td>                                                
                                                <td><?php $t=$list->create_at;   $s=explode(" ",$t);  $e=implode(" / ",$s);
                                                         echo $e; ?>
                                                </td>                                                
                                                  
                                                <td>
                                                    <a href="<?php echo site_url('Fleet/update_fleet/'.$list->fleet_id);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                                <td>
                                                 <a href="<?php echo site_url('Home/delete_customer/'.$list->fleet_id);?>">
                                                  <i class="fa fa-trash-o fa-fw">
                                                  <strong>Delete</strong></i>
                                                 </a>
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