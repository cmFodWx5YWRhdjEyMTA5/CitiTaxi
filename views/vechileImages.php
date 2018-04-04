
<?php $data['page']='driver'; $data['title']='Vechile Images'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Vechile</strong> Images</h3>                            
                                </div>
                        <div class="container">  
                                    
                            <?php if(isset($error)&& $error==1) { ?>
                            <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                             <?php echo $message;?>
                            </div>
                            <?php }if(isset($success)&& $success==1){?>
                            <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                             <?php echo $message;?>
                            </div>
                            <?php }?>
                            <form method="post" action="<?php echo site_url('Home/add_customer');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                 <?php if (count($images)<=7){?>
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Add More Image </label>
                                    <div class="col-md-6 col-xs-9">                      
                                        <input type="file" class="fileinput btn-primary" name="image" id="filename" />
                                    </div>
                                    <div class="col-md-3 col-xs-3">
                                        <input class="btn btn-success" type="submit" name="upload" value="Save">
                                    </div>
                                </div>
                                <?php } ?>

                                <?php if(isset($images)){  
                                    $i=1;
                                    foreach ($images as $img) {                                        
                                ?>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Image <?php echo $i++; ?></label>
                                        <div class="col-md-6 col-xs-9">
                                            <img src="<?php echo base_url('vechicleImage/'.$img->vechile_image);?>" width="180">
                                        </div>
                                        <div class="col-md-3 col-xs-3">
                                            <a href="<?php echo site_url('user?id=');?>">
                                                <i class="fa fa-pencil-square-o" style="font-size:28px;"></i>
                                            </a>                                         
                                            <a href="<?php echo site_url('user/delete_user?id=');?>">
                                                <i class="fa fa-trash-o" style="font-size:28px;"></i>
                                            </a>
                                        </div>
                                    </div>

                                <?php }} ?>    

                            </div>
                            </form>                         
                        </div>
                    </div>
                </div>                    
             </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 

<script>

    /*$("#mobile").keyup(function(){
        var mobile = $('#mobile').val();
        $.ajax({
            type:'post',
            data:{'mobile':mobile},
            url:'<?php echo site_url('Driver/checkMobile'); ?>',
            datatype:'json',
            success: function (dd){   
                if(dd=='false')
                {                              
                $("#errMobile").text('Mobile number already exist');
                }             
                console.log(dd);
            }
        });
        
    });*/



    

</script>

    





