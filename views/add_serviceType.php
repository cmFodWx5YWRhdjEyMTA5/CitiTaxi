
<?php $data['page']='vehicle'; $data['title']=$pagetype.' Service Type'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">

                <!-- Update View Start -->
                <?php if($pagetype=='Update'){ ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Update Service Type</strong></h3>                           
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
                            <form method="post" action="<?php echo site_url('Home/updateServiceType/'.$service_id);?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Enter Service Type</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="service_name" value="<?php echo $service->servicename;?>" class="form-control" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Selected Image<span style="color:red"> (200*200px)</span></label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="selectimg" id="selectimage" data-error="#err" value="<?php echo $service->selected_image; ?>" />
                                            <img src="<?php echo base_url('/serviceimage/'.$service->selected_image); ?>" width="100" height="100">
                                            <span id="err"></span>
                                            <div>(This image will show when passenger click on icon)</div>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Un-Selected Image<span style="color:red"> (200*200px)</span></label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="unselectimg" id="unselectimage" data-error="#err1" />
                                            <img src="<?php echo base_url('/serviceimage/'.$service->unselected_image); ?>" width="100" height="100">
                                            <span id="err1"></span>
                                            <div>(This image will show when icon is un-selected)</div>
                                        </div>
                                    </div>
                                </div> 
                                <div class="form-group">
                                    <label class="col-md-3 col-xs-12 control-label">Description</label>
                                    <div class="col-md-6 col-xs-12">   
                                        <textarea class="form-control" rows="3" name="description"><?php echo  $service->description; ?></textarea>
                                    </div>
                                </div> 
                                    
                                <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-1"></div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Home/servie_type'); ?>">
                                            <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            
                                        </div>                                   
                                        <div class="col-md-3">
                                            <input type="submit" name="submit" value="Update" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                                        </div>                                    
                                    </div>                                   
                                </div>
                            </div>
                        </form>                         
                    </div>
                
                  <!--================================ Add new service start ======================-->
                  <?php } else{ ?> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Add Service Type</strong></h3>                           
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
                            <form method="post" action="<?php echo site_url('Home/add_service');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Enter Service Type</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="service_name" id="service_name" class="form-control" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Selected Image<span style="color:red"> (200*200px)</span></label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="selectimage" id="selectimage" data-error="#err" />
                                            <span id="err"></span>
                                            <div>(This image will show when passenger click on icon)</div>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Un-Selected Image<span style="color:red"> (200*200px)</span></label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="unselectimage" id="unselectimage" data-error="#err1" />
                                            <span id="err1"></span>
                                            <div>(This image will show when icon is un-selected)</div>
                                        </div>
                                    </div>
                                   <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Description</label>
                                        <div class="col-md-6 col-xs-12">   
                                            <textarea class="form-control" rows="3" name="description"></textarea>
                                        </div>
                                    </div>
                                </div>  
                                    
                                <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-1"></div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Home/servie_type'); ?>">
                                            <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="reset" class="btn btn-reset" value="Reset" style="margin:5px 0; width:100%;">
                                        </div>                                   
                                        <div class="col-md-3">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                                        </div>                                    
                                    </div>                                   
                                </div>
                            </div>
                        </form>                         
                    </div>
                    <?php } ?> 
                </div>
            </div>                    
        </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 
<script>
$(document).ready(function(){
    var _URL = window.URL || window.webkitURL;
    $("#selectimage").change(function(e) {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {
                if(this.width>200 && this.height>200)
                {
                    alert('Image dimension exceeded. Image size should be 200*200 px');
                    location.reload(true);
                }
                //alert(this.width + "*" + this.height);
            };            
            img.src = _URL.createObjectURL(file);
        }

    });
    
    $("#unselectimage").on('change',function()
    {
        var file, img;
        if ((file = this.files[0])) {
            img = new Image();
            img.onload = function() {
                if(this.width>200 && this.height>200)
                {
                    alert('Image dimension exceeded. Image size should be 200*200 px');
                    location.reload(true);
                }
                //alert(this.width + "*" + this.height);
            };            
            img.src = _URL.createObjectURL(file);
        }       
    });
});    
</script>



