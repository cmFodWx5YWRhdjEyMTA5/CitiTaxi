<?php $data['page']='two'; $data['title']='Passenger List'; $this->load->view('Sub_admin/layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Registered Passenger</strong></h3>
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
                                            <th>Name</th>
                                            <th>Photo</th>
                                            <th style="min-width:50px;">Rating</th>
                                            <th style="min-width:80px;">Email</th>
                                            <th>Phone</th>
                                            <th>trip Requested</th>
                                            <th>Completed</th>
                                            <th>Cancelled</th>
                                            <th>Referral Code</th>
                                            <th>Wallet Amount</th>
                                            <th>Registerd</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                          <!--   <th style="min-width:50px; text-align:center">Edit</th>
                                            <th style="min-width:50px; text-align:center">Delete</th> -->
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                          if(!empty($userlist)){
                                        foreach($userlist as $list) { $status = $list->activeStatus; ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td><?php echo $list->name; ?></td>                                                
                                                <td>
                                                <?php if($list->image_type==0){
                                                 echo "<img src=".base_url()."userimage/".$list->image." width='60px' height='60px' style='border-radius:33px'>";
                                                }
                                                else{
                                                echo "<img src=".$list->image." width='60px' height='60px' style='border-radius:33px'>"; } ?>  
                                                </td>
                                                <td><?php echo get_rating($list->id); ?></td>
                                                <td><?php echo $list->email; ?></td>
                                                <td><?php echo $list->mobile;?></td>
                                                <td><!--trip Requested--></td>
                                                <td><!--trip Completed--></td>
                                                <td><!--trip Cancelled--></td>
                                                <td><?php echo $list->ref_code;?></td>
                                                <td><!--Wallet Amount--></td>
                                                <td><?php $t=$list->created_at;   $s=explode(" ",$t);  $e=implode(" / ",$s);
                                                         echo $e; ?>
                                                </td>                                                
                                                <td style="font-size:14px;<?php if($status=='Active'){?> color:blue;<?php }else{?>color:red;<?php }?>"><strong><?php echo  $list->activeStatus; ?></strong>
                                                    <br><?php echo $list->suspend_type; ?>
                                                </td>
                                                <td>
                                                    <div class="form-group">                                         
                                                        <select class="form-control" id="<?php echo $list->id;?>">
                                                            <option>--Select Action--</option>
                                                            <?php if($status=='Banned' or $status=='Suspended'){?>          
                                                            <option value="Active">Active</option>
                                                            <?php } else{ ?>
                                                            <option value="Banned">Banned</option>
                                                            <?php }?>
                                                           <option value="Trip">Trip History</option> 
                                                            <option value="days3">Suspend 3 days</option>
                                                            <option value="days7">Suspend 7 days</option>
                                                            <option value="days30">Suspend 30 days</option>                 
                                                        </select>                                                       
                                                    </div>
                                                </td>  
                                               <!--  <td>
                                                    <a href="<?php //echo site_url('Dispatcher/update_customer/'.$list->id);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                                <td>
                                                 <a href="<?php //echo site_url('Dispatcher/delete_customer/'.$list->id);?>">
                                                  <i class="fa fa-trash-o fa-fw">
                                                  <strong>Delete</strong></i>
                                                 </a>
                                                </td> -->
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
        <?php $this->load->view('Sub_admin/layout/second_footer');?> 
<script>
    function Trip(id)
    {
        var url = '<?php echo site_url('Dispatcher/tripHistroy/'); ?>'+id;        
        var win = window.open(url, '_blank');
        win.focus();
        //window.open.href =
        //alert('Called function Trip');
    }
    function Banned(id,Status)
    {
        //alert(Status);
        $.ajax({
            method:'POST',
            url:'<?php echo site_url('Dispatcher/updateStatus'); ?>',
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
        //alert(id);
        $.ajax({
            method:'POST',
            url:'<?php echo site_url('Dispatcher/Suspend'); ?>',
            data:{'id':id,'type':type},
            success:function(data)
            {
                alert(data);
                location.reload();
            }
        });
    }
</script>
