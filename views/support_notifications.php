<?php $data['page']='notification'; $data['title']='Notifications'; $this->load->view('layout/header',$data);?>
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
        background: url(<?php echo base_url('assest/images/myloading.gif'); ?>) center no-repeat #00000070;
    }
    </style> 
            <!-- PAGE CONTENT WRAPPER -->
           
                <div class="page-content-wrap">                
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
                                    <h3 class="panel-title"><strong>Notifications</strong></h3>
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
                                                <th style="min-width:20px !important;"></th>                                  
                                                <th style="min-width:20px !important;">Sr.No</th>
                                                <th style="min-width:80px !important;">User ID</th>
                                                <th style="min-width:80px !important;">Booking ID</th>
                                                <th>Name</th>                                                                         
                                                <th style="min-width:80px;">Email</th>                                                
                                                <th>Contact</th>                                                  
                                                <th>Subject</th>                                                  
                                                <th>Message</th>                                                  
                                                <th>issue Image</th>
                                                <th>Message At</th>
                                                <th>Delete</th>                                                       
                                            </tr>
                                        </thead>                                        
                                        <tbody>
                                            <?php
                                              $i=1;  
                                                foreach($messagelist as $list) { ?>
                                                <tr>                                                    
                                                    <td style="text-align:center"><input data-error="#err" id="chek<?php echo $list->support_id; ?>" type='checkbox' name='users[]' value="<?php echo $list->support_id; ?>"></td>                                                    
                                                    <td style="text-align:center"><?php echo $i++;?></td>
                                                    <td><?php echo $list->user_id; ?></td>            
                                                    <td><?php echo $list->booking_id; ?></td>            
                                                    <td><?php echo $list->name; ?></td>                                               
                                                    <td><?php echo $list->email; ?></td>
                                                    <td><?php echo $list->contact;?></td>                    
                                                    <td><?php echo $list->subject;?></td>                    
                                                    <td><?php echo $list->feedback_details;?></td>
                                                    <td><img src="<?php echo base_url('/supportImage/'.$list->issue_image); ?>" width="100" height="100"></td>
                                                    <td><?php $t=$list->support_at;   $s=explode(" ",$t);  $e=implode(" / ",$s);
                                                         echo $e; ?></td>                    
                                                    <td>
                                                        <input type="button" value="Delete" class="btn btn-reset" onclick="remove(<?php echo $list->support_id;?>,1)">
                                                    </td>                                                    
                                                </tr> 

                                    <!--==================Script to add or remove user id in array===========--> 
                                                <script>
                                                   var favorite=[]; 
                                                   $(document).ready(function(){
                                                        $('#chek<?php echo $list->support_id; ?>').on('change', function(e){
                                                            if(e.target.checked){
                                                                if(!favorite.includes(<?php echo $list->support_id; ?>)){
                                                                    favorite.push($(this).val());
                                                                }                
                                                            }
                                                            else
                                                            {
                                                                for(var g=0; g<favorite.length; g++)
                                                                {
                                                                    if(favorite[g]==<?php echo $list->support_id;?>)
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
                                     <div class=" col-md-2 pull-right">                                        
                                        <input type="button" name="submit" value="Delete All" onclick="remove('',3)" class="btn btn-submit"  style="max-width:300px; margin-top:15%; width:100%;">                                        
                                    </div>  
                                    <div class=" col-md-2 pull-right">                                        
                                        <input type="button" name="submit" value="Selected Delete" class="btn btn-submit" id="delete_select" style="max-width:300px; margin-top:15%; width:100%;">                                        
                                    </div>                                    
                                                                      
                                    </form>                                    
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
        <span id="messages" ></span>
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
        $("#delete_select").click(function(){             
            if(favorite.length>0){
            $(".sp-pre-con").css("display", "block");
                $.ajax({
                    type:"POST",
                    dataType:"json",
                    data:{"users":favorite,"submit":'submit'},
                    url:'<?php echo site_url("Home/remove_selected_msg");?>',
                    success:function(res){
                        //console.log(res);
                        if(res.success==1){                            
                            $(".sp-pre-con").css("display", "none");
                            $('#myModal').modal({'show' : true});
                            $('#messages').text(res.message);    
                        }
                        else{
                            $(".sp-pre-con").css("display", "none");
                            $('#myModal').modal({'show' : true});
                            $('#messages').text(res.message);
                        }
                        //console.log(res);
                    },
                    error:function(res) {
                        $(".sp-pre-con").css("display", "none");
                        $('#myModal').modal({'show' : true});
                        $('#messages').text('Internal Error');    
                    }
                });
                //console.log(favorite);
            }else{
                alert('Please select atleast 1 Passenger');
            }            
        });  
                //alert("My favourite Passengers are: " + favorite.join(", "));

            /*$("#checkAl").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            });*/
            
        });                      
</script>
<script>
    function remove(support_id,type)
    {
        //type =>1= userfndly, 2= selected, 3= all;
        //console.log(promo_id);
        var r = confirm("Are you realy want to remove records");
        if(r==true)
        {
            $(".sp-pre-con").css("display", "block");
            if(type==3){var url ='<?php echo site_url('Home/remove_Allsupportmsg');?>';}
            else{ var url ='<?php echo site_url('Home/remove_supportmsg');?>'; }
                $.ajax({
                type:'POST',
                dataType:"json",
                data:{'support_id':support_id,'type':type,'submit':'submit'},
                url:url,
                success:function(res){   
                if(res.success==1){
                console.log(res.message);                     
                    $(".sp-pre-con").css("display", "none");
                    $('#myModal').modal({'show' : true});
                    $('#messages').text(res.message);                                                         
                }
                else{
                    $(".sp-pre-con").css("display", "none");
                    $('#myModal').modal({'show' : true});
                    $('#messages').text(res.message);                    
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
