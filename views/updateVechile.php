
<?php $data['page']='driver'; $data['title']='Update Vechile'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo site_url('Driver/other_details/'.$userId);?>">Other Details</a></li>
                        <li class="active">Update Vechile</li>
                    </ul>
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Update</strong> Vechile</h3>                            
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
                            <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Vehicle Brand </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="brand" class="form-control" value="<?php echo $vechile->brand; ?>" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Sub brand name </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="subbrand" value="<?php echo $vechile->sub_brand; ?>" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Vehicle Number Plate </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="vehicle_NoPlate" class="form-control" value="<?php echo $vechile->number_plate; ?>" />
                                        </div>
                                    </div>



                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Insurance Company </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="insuranceCompany" value="<?php echo $vechile->insurance_company; ?>" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Insurance number </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="insuranceNumber" value="<?php echo $vechile->insurance_no; ?>" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Insurance Expiry Date </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="insuranceExpiredate" class="form-control datepicker" value="<?php echo $vechile->insurance_expire; ?>" placeholder="DD-MM-YYYY"/>
                                        </div>
                                    </div>



                                    <!--div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" ></label>
                                        <div class="col-md-6 col-xs-12" style="font-size: 14px;color:blue; border-left:none !important;">                      
                                            <strong><u>Fleet Company Information</u></strong>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Company name </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="fleet_company" value="<?php echo $vechile->fleet_company; ?>" class="form-control"/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Fleet Company Country </label>
                                        <div class="col-md-6 col-xs-12">                                
                                            <input type="text" name="fleet_country" value="<?php echo $vechile->fleet_country; ?>" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Fleet Company Address </label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="fleet_address" value="<?php echo $vechile->fleet_address; ?>" class="form-control" />
                                        </div>
                                    </div-->                                    
                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-2"></div>
                                    <div class="row">

                                    <div class="col-md-3">
                                        <a href="<?php echo site_url('Driver/other_details/'.$userId);?>">
                                        <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                        </a>
                                    </div>

                                    <div class="col-md-3">
                                        <input type="submit" name="submit" value="Update" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                                    </div>                                   
                                    
                                </div>
                                <div class="col-md-2"></div>
                                   
                                </div>
                                    
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

    





