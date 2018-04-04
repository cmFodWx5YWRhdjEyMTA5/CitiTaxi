
<?php $data['page']='driver'; $data['title']='Other details'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo site_url('Driver/other_details/'.$userId);?>">Other Details</a></li>
                        <li class="active">Update <?php echo $tag; ?></li>
                    </ul>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Update </strong><?php echo $tag; ?> Details</h3>                            
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
                            <?php }  if(isset($tag) && $tag=='bank'){ ?>
                                <div class="panel-body form-group-separated">
                                <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver's Bank Name</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="bankname" value=<?php echo $bank->bankName ;?> class="form-control"/>
                                        </div>
                                    </div>
                                     <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Bank Code/ Branch Name</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="branchCode_Name" value=<?php echo $bank->branchCode_Name ;?> class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Bank Account Number</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="accountNo" value=<?php echo $bank->accountNo ;?> class="form-control" />
                                        </div>
                                    </div>

                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-12">
                                        <input type="submit" name="submit" value="Update" class="btn btn-success pull-right" style="max-width:300px; margin:2px 0; width:100%;">
                                    </div>
                                    </div>
                                 </form>                         
                                </div>
                                <?php } if($tag=='license'){ ?>
                                <div class="panel-body form-group-separated">
                                <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                     <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver License number </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="licenseno" value="<?php echo $license->licenseNumber; ?>" class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driver License Expiry Date </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="date" name="expiredate" value="<?php echo $license->expireDate; ?>" class="form-control"/>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Driving License Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-primary" name="licenseimages" id="filename" />
                                            <img src="<?php echo base_url('licenseImage/'.$license->licenseImage); ?>" width="80" height="80">
                                            <input type="hidden" name='pre_image' value="<?php echo $license->licenseImage; ?>">
                                        </div>
                                    </div>

                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-12">
                                        <input type="submit" name="submit" value="Update" class="btn btn-success pull-right" style="max-width:300px; margin:2px 0; width:100%;">
                                    </div>
                                    </div>
                                 </form>                         
                                </div>
                                <?php  } ?>
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

    





