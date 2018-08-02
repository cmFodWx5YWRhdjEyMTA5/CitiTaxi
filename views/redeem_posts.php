<?php $data['page']='coupon'; $data['title']='Redeem Post'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Redeem Post</strong></h3>
                                    <div class="btn-group pull-right">
                                      <a href="<?php echo site_url('Home/add_redeem_post');?>">
                                         <button type="button" class="btn btn-submit">Add More Post</button>   
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
                                                <th style="min-width:80px;">Promo Code</th>
                                                <th style="min-width:80px;">Heading</th>
                                                <th>Description</th>
                                                <th style="min-width:100px;">Rate</th> 
                                                <th>Max Discount</th>                                                 
                                                <th>Start Date</th>                                               
                                                <th>End Date</th> 
                                                <th style="min-width:100px;">Date Display</th>                                             
                                                <th style="min-width:100px;">QR scan</th>                                              
                                                <th>Preview Image</th>
                                                <th>Timeline Image</th>
                                                <th>Button</th>                                               
                                                <th>Publish Type</th>
                                                <th>Later Date</th>
                                                <th>Status</th>
                                                <th>Post Date</th>
                                                <th>Created At</th>                                          
                                                <th style="min-width:50px; text-align:center">Edit</th>
                                                <th style="min-width:50px; text-align:center">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i=1;  
                                            foreach($code as $list) {  $status = $list->status;?>
                                                <tr>
                                                    <td style="text-align:center"><?php echo $i++;?></td>
                                                    <td><?php echo $list->country;?></td>
                                                    <td style="color:red;"><strong><?php echo $list->promocode;?></strong></td>
                                                    <td style="min-width:150px;"><?php echo $list->heading; ?></td> 
                                                    <td style="min-width:200px;"><?php echo $list->description; ?></td>
                                                    <td><?php echo $list->rate.' '.$list->rate_type;?></td>      
                                                    <td><?php echo $list->max_amount.' '.$list->currency;?></td>
                                                    <td><?php echo date('d-m-Y',strtotime($list->start_date)); ?></td>
                                                    <td><?php echo date('d-m-Y',strtotime($list->end_date)); ?></td>                
                                                    <td><?php echo $list->date_display;?></td>      
                                                    <td><?php echo $list->QR_scan;?></td>      
                                                    <td><img src="<?php echo base_url('promo_images/'.$list->preview_image); ?>" width="80" height="80"></td>     
                                                    <td><img src="<?php echo base_url('promo_images/'.$list->timeline_image); ?>" width="80" height="80"></td>
                                                    <td><?php echo $list->buttons;?></td>  
                                                    <td><?php echo $list->publish_type;?></td>  
                                                    <td><?php echo $list->later_date;?></td> 
                                                    
                                                    <?php if($list->status=='Expired'){?>
                                                    <td style="color:red;font-size:16px;font-weight:600;"><?php echo $list->status; ?></td>     
                                                    <?php  } else{ ?> 
                                                    <td style="color:blue;font-size:16px;font-weight:600;"><?php echo $list->status; ?></td>     
                                                    <?php } ?>  
                                                    <td><?php echo $list->publish_date; ?></td>                                            
                                                    <td><?php $t=$list->promo_at;   $s=explode(" ",$t);  $e=implode(" / ",$s);
                                                        echo $e; ?>
                                                    </td>      
                                                    <td>
                                                        <a href="<?php echo site_url('Home/update_redeem_post/'.$list->redeem_post_id);?>">
                                                        <i class="fa fa-pencil fa-fw">
                                                        <strong>Edit</strong>
                                                        </i></a>
                                                    </td>
                                                    <td>
                                                     <a href="<?php echo site_url('Home/delete_redeem_post/'.$list->redeem_post_id);?>">
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