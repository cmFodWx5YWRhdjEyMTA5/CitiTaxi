<?php $data['page']='six'; $data['title']='City Settings'; $this->load->view('layout/header',$data);?>
   <style>
    .sp-pre-con {
          position: fixed;
          left: 0px;
          top: 0px;
          width: 100%;
          height: 100%;
          z-index: 9999;        
          background: url(<?php echo base_url('assest/images/myloading.gif'); ?>) center no-repeat #00000070;
        } 
       
   </style>
            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">

                  <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-default">

                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>City Settings</strong></h3>
                                    <!--div class="btn-group pull-right">
                                      <a href="<?php echo site_url('Home/add_referral_setting');?>">
                                         <button type="button" class="btn btn-submit">Add Setting</button>   
                                      </a>
                                    </div-->
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
                                    <div class="sp-pre-con" style="display: none;"></div>
                                    <div class="table-responsive">
                                     <div style="overflow:scroll; height:600px;">
                                     <table id="example" class="table display">
                                        <thead>
                                            <tr>
                                                <th style="min-width:50px;">#</th>                                               
                                                <th style="min-width:80px;">Country</th>
                                                <th style="min-width:80px;"">City</th>
                                                <th>Time Zone</th>
                                                <th>Business Status</th>
                                                <th>Cash Payment</th> 
                                                <th>Wallet Payment</th>                                               
                                                <th>Card Payment</th>                                               
                                                <th>Promo on Cash</th>                                               
                                                <th>Promo On Wallet</th>                                          
                                                <th>Promo On Card</th>                                                                    
                                                <th>Driver Destination</th>
                                                <th>Lst Update At</th>                                                                    
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($setting as $list) { ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>
                                                <td style="color:red;"><?php echo $list->country; ?></td>
                                                <td><?php echo $list->city;?></td>
                                                <td><?php echo $list->time_zone.' '.$list->UTC_offset; ?></td> 
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->business_status=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'business_status','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'business_status','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                                                
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->cash_payment=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'cash_payment','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'cash_payment','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->wallet_payment=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'wallet_payment','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'wallet_payment','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->card_payment=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'card_payment','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'card_payment','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->promo_on_cash=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'promo_on_cash','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'promo_on_cash','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->promo_on_wallet=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'promo_on_wallet','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'promo_on_wallet','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->promo_on_card=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'promo_on_card','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'promo_on_card','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                
                                                <td>
                                                    <label class="switch">
                                                    <?php 
                                                    if($list->destination=='On'){ ?>
                                                        <input type="checkbox" value="Off" checked  onchange="update(<?php echo $list->city_setting_id;?>,'destination','Off')" />
                                                    <?php  } else{?>
                                                        <input type="checkbox" value="On" onchange="update(<?php echo $list->city_setting_id;?>,'destination','On')" />
                                                    <?php }?>                                                    
                                                    <span></span>
                                                    </label>
                                                </td>                
                                                <td><?php echo date('Y-m-d h:i:s',strtotime($list->update_at)); ?>
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
   function update(setting_id,field_name,status){        
        var dateTime = new Date().toLocaleString();
        var r = confirm('Are you realy want to perform this action');
        if(r==true){ 
            $(".sp-pre-con").css("display", "block"); 
                     
            $.ajax({
                type:'post',                
                url:'<?php echo site_url("Vehicle/update_city_setting");?>',
                data:{'city_setting_id':setting_id,'field_name':field_name,'status':status,'update_at':dateTime},
                success:function(res){
                    $(".sp-pre-con").css("display", "none");
                    //console.log(res);
                    alert(res);
                    location.reload(true);
                },
                error:function(res){
                    $(".sp-pre-con").css("display", "none");
                    alert(res);
                    location.reload(true);                    
                }
            });
        }
        else{            
            location.reload(true);                    
        }
   }
</script>