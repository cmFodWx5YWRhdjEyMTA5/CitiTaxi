<?php $data['page']='six'; $data['title']='Referral Settings'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">

                  <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Referral Settings List</strong></h3>
                                    <div class="btn-group pull-right">
                                      <a href="<?php echo site_url('Home/add_referral_setting');?>">
                                         <button type="button" class="btn btn-submit">Add Setting</button>   
                                      </a>
                                    </div>
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
                                                <th style="min-width:80px;">Country</th>
                                                <th style="min-width:80px;"">Currency</th>
                                                <th>Time Zone</th>
                                                <th>Amount to Friend</th>
                                                <th>Referral Bonous</th> 
                                                <th>Minimum Ride</th>                                               
                                                <th>Minimum Days</th>                                               
                                                <th>Description</th>                                               
                                                <th>Creat At</th>                                          
                                                <th style="min-width:50px; text-align:center">Edit</th>
                                                <th style="min-width:50px; text-align:center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($setting as $list) {?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td><?php echo $list->country;?></td>
                                                <td><?php echo $list->currency; ?></td> 
                                                <td><?php echo $list->time_zone;?></td>      
                                                <td><?php echo $list->amount_to_frnd; ?></td>
                                                <td><?php echo $list->bonus_to_referral; ?></td>                
                                                <td><?php echo $list->min_ride; ?></td>                
                                                <td><?php echo $list->within_days; ?></td>                
                                                <td><?php echo $list->description; ?></td>                
                                                <td><?php $t=$list->setting_at;   $s=explode(" ",$t);  $e=implode(" / ",$s);
                                                         echo $e; ?>
                                                </td>                                                
                                                  
                                                <td>
                                                    <a href="<?php echo site_url('Home/update_referral/'.$list->referral_setting_id);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                                <td>
                                                 <a href="<?php echo site_url('Home/delete_referral/'.$list->referral_setting_id);?>">
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
   
</script>