<?php $data['page']='coupon'; $data['title']='Passenger List'; $this->load->view('layout/header',$data);?>
    <style>
    label.error, label.valid{
        font-size:13px !important;
        font-weight: 600;
    }
    .sp-pre-con {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;        
        background: url(<?php echo base_url('assest/images/lo2.gif'); ?>) center no-repeat #00000070;
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
                                <!-- Show loader -->                               
                                <div class="sp-pre-con" style="display: none;"></div>
                                 <!-- Show loader -->
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
                                                    <td style="text-align:center"><input data-error="#err" id="chek<?php echo $list->id ?>" type='checkbox' name='users[]' value="<?php echo $list->id; ?>"></td>
                                                    <?php } ?>
                                                    <td style="text-align:center"><?php echo $i++;?></td>
                                                    <td><?php echo $list->id; ?></td>            
                                                    <td><?php echo $list->name; ?></td>                                               
                                                    <td><?php echo $list->email; ?></td>
                                                    <td><?php echo $list->mobile;?></td>
                                                    <?php  if($page=='list'){ ?>
                                                    <td>
                                                        <input type="button" value="Delete" class="btn btn-reset" onclick="remove(<?php echo $list->id.','.$promo_id;?>)">
                                                    </td>                                                    
                                                    <?php } ?>
                                                </tr> 

                                    <!--==================Script to add or remove user id in array===========--> 
                                                <script>
                                                   var favorite=[]; 
                                                   $(document).ready(function(){
                                                        $('#chek<?php echo $list->id; ?>').on('change', function(e){
                                                            if(e.target.checked){
                                                                if(!favorite.includes(<?php echo $list->id; ?>)){
                                                                    favorite.push($(this).val());
                                                                }                
                                                            }
                                                            else
                                                            {
                                                                for(var g=0; g<favorite.length; g++)
                                                                {
                                                                    if(favorite[g]==<?php echo $list->id;?>)
                                                                    {
                                                                        favorite.splice(g, 1);
                                                                        break;
                                                                    }
                                                                } 
                                                            }
                                                          console.log(favorite)
                                                        });
                                                   });
                                                </script>
                                    <!--==================Script to add or remove user id in array End===========--> 
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                     <?php if($page!='list'){?>    
                                    <div class=" col-md-2 pull-right">
                                        <input type="hidden" id="promo_id" name="promo_id" value="<?php echo $promo_id; ?>">
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

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">       
        <h4 class="modal-title" id="myModalLabel">Message</h4>
      </div>
      <div class="modal-body" style="text-align:center !important;">
        <span id="message" ></span>
        <hr>
        <div style="text-align:center;margin-top:5%">
        <button type="button" class="btn btn-back" onclick="reload()" data-dismiss="modal">Close</button>
        </div>
      </div>     
    </div>
  </div>
</div>



  

    <script type="text/javascript">    
    $(document).ready(function() {       
        $("#subm").click(function(){ 
            var promo_id = $('#promo_id').val();
            if(favorite.length>0){
            $(".sp-pre-con").css("display", "block");
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    data:{"users":favorite,"promo_id":promo_id,"submit":'submit'},
                    url:'<?php echo site_url("Home/add_promo_users");?>',
                    success:function(res){
                        //console.log(res.message);
                        if(res.success==1){                            
                            $(".sp-pre-con").css("display", "none");
                            $('#myModal').modal({'show' : true});
                            $('#message').text(res.message);    
                        }
                        else{
                            $(".sp-pre-con").css("display", "none");
                            $('#myModal').modal({'show' : true});
                            $('#message').text(res.message);    
                            
                        }
                        //console.log(res);
                    }
                });
                //console.log(favorite);
            }else{
                alert('Please select atleast 1 Passenger');
            }            
        });  
                //alert("My favourite Passengers are: " + favorite.join(", "));            
        });                      
</script>
<script>
    function remove(user_id,promo_id)
    {
        //console.log(promo_id);
        var r = confirm("Are you realy want to remove this record");
        if(r==true)
        {
            $(".sp-pre-con").css("display", "block");
                $.ajax({
                type:'POST',
                dataType:"json",
                data:{'promo_id':promo_id,'user_id':user_id,'submit':'submit'},
                url:"<?php echo site_url('Home/remove_promo_user');?>",
                success:function(res){   
                console.log(res);                     
                if(res.success==1){
                    //$(".sp-pre-con").css("display", "none");
                    //$('#myModal').modal({'show' : true});
                    $('#message').text(res.message);                                                         
                }
                else{
                    $(".sp-pre-con").css("display", "none");
                    $('#myModal').modal({'show' : true});
                    $('#message').text(res.message);                    
                    /*$(".sp-pre-con").css("display", "none");
                    alert(res.message);*/
                }                        
                },
            });
        }      
    }

    function reload(){
        location.reload(true);  
    }
</script>
