<?php $data['page']='coupon'; $data['title']='Passenger List'; $this->load->view('layout/header',$data);?>
    <style>
    label.error, label.valid{
        font-size:13px !important;
        font-weight: 600;
    }
    </style> 
            <!-- PAGE CONTENT WRAPPER -->
           
                <div class="page-content-wrap">
                <ul class="breadcrumb">
                <?php if($page=='list'){?>
                    <li><a href="<?php echo site_url('Home/ride_promocode');?>">Promocode</a></li>
                    <li class="active">Passengers</li>
                    <?php }else{?>
                    <li><a href="<?php echo site_url('Home/promo_users/'.$promo_id.'/'.$country);?>">Shared Passengers</a></li>
                    <li class="active">Passengers</li>
                    <?php }?>
                </ul>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
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
                                <div class="panel-heading">
                                    <?php if($page=='list'){?>
                                    <h3 class="panel-title"><strong>Shared With Passengers</strong></h3>
                                    <div class="btn-group pull-right">
                                      <a href="<?php echo site_url('Home/promo_users_list/'.$promo_id.'/'.$country);?>">
                                         <button type="button" class="btn btn-submit">Add More Users</button>   
                                      </a>
                                    </div>
                                    <?php } else{?>
                                    <h3 class="panel-title"><strong>Passengers</strong></h3>
                                     <span id="err" style="text-align:center;"></span>

                                    <?php } ?>    
                                </div>   
            <!--  ============SAME PAGE IS USED TO SHOW SHARED USER AND TO SHOW PASSENGERS TO ASSIGN THIS PROMOTION=======  -->                  
                                <div class="panel-body">
                                    <div class="table-responsive">
                                     <div id="list_table" style="overflow:scroll;">
                                    
                                     <form method="POST" id="share" name="share" action="<?php echo current_url(); ?>">
                                     <table id="example" class="table display">
                                        <thead>
                                            <tr>
                                                <?php if($page!='list'){?>                                            
                                                <th></th>                                                
                                                <?php } ?>
                                                <th>Sr.No</th>
                                                <th>User ID</th>
                                                <th>Name</th>                                                                         
                                                <th style="min-width:80px;">Email</th>
                                                <th>Phone</th>  
                                                <?php if($page=='list'){?>                                            
                                                <th>Delete</th>                                                
                                                <?php } ?>                                                        
                                            </tr>
                                        </thead>                                        
                                        <tbody>
                                            <?php
                                              $i=1;  
                                                foreach($userlist as $list) { ?>
                                                <tr>
                                                    <?php if($page!='list'){?>
                                                    <td style="text-align:center"><input data-error="#err" class="ids" type='checkbox' name='users[]' value="<?php echo $list->id; ?>"></td>
                                                    <?php } ?>
                                                    <td style="text-align:center"><?php echo $i++;?></td>
                                                    <td><?php echo $list->id; ?></td>            
                                                    <td><?php echo $list->name; ?></td>                                               
                                                    <td><?php echo $list->email; ?></td>
                                                    <td><?php echo $list->mobile;?></td>
                                                    <?php  if($page=='list'){ ?> 
                                                    <td><a href="<?php echo site_url('Home/delete_promo_user/'.$promo_id.'/'.$country.'/'.$list->id);?>">
                                                          <i class="fa fa-trash-o fa-fw">
                                                          <strong>Delete</strong></i>
                                                         </a>
                                                    </td>
                                                    <?php } ?>
                                                </tr>                                           
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                     <?php if($page!='list'){?>    
                                    <div class=" col-md-2 pull-right">
                                        <input type="hidden" name="promo_id" value="<?php echo $promo_id; ?>">
                                        <input type="hidden" name="country" value="<?php echo $country; ?>">
                                        <input type="button" name="submit" value="Share" class="btn btn-submit" id="subm" style="max-width:300px; margin-top:15%; width:100%;">                                        
                                    </div>                                    
                                    </form>
                                    <?php } ?>
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
<script type='text/javascript' src='<?php echo base_url('assest/js/plugins/jquery-validation/jquery.validate.js');?>'></script> 
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script type='text/javascript' src='<?php echo base_url('assest/js/formValidationScript.js');?>'></script> 
    <script>
    var share = $("#share").validate({
        rules: {
            "users[]": {
                required: true,                        
                }
            },
            messages: {                
             "users[]": {
                required: "*Please select atleast 1 checkbox"
                },                        
            },
            errorPlacement: function(error, element) 
            {
              var placement = $(element).data('error');
              if (placement) 
              {
                $(placement).append(error)
              }
              else 
              {
                error.insertAfter(element);
              }
            }
        });    
    </script>

    <script type="text/javascript">
    $(document).ready(function() {
        $("#subm").click(function(){
            var favorite = [];
            $.each($("input[name='users[]']:checked"), function(){            
                favorite.push($(this).val());
            });
            alert("My favourite Passengers are: " + favorite.join(", "));
        });
    });
</script>
