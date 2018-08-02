<?php  $data['page']='six'; $data['title']='Admin Profile'; $this->load->view('Sub_admin/layout/header',$data);?>

<div class="page-content-wrap">

                    <div class="row">

                        <div class="col-md-12">

                            <div class="panel panel-default">

                                <div class="panel-heading">

                                    <h3 class="panel-title"><strong>Profile </strong></h3>                            

                                </div>

                                <div class="container-fluid">

                                <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate">

                                <div class="panel-body form-group-separated">

                                    

                            <?php if(isset($error)&& $error==1) { ?>

                            <div class="alert alert-danger">

                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                             <?php echo $message;?>

                            </div>

                            <?php } if(isset($success)&& $success==1){?>

                            <div class="alert alert-success">

                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>

                             <?php echo $message;?>

                            </div>

                            <?php }?>



                                <div class="form-group">                                   

                                        <label class="col-md-3 col-xs-12 control-label">Name</label>

                                        <div class="col-md-6 col-xs-12">                                            

                                                <!--input type="hidden" name="status" value="<?php echo $admin->status;?>"-->

                                                <input type="hidden" name="fleet_id" value="<?php echo $admin->fleet_id;?>">

                                                <input type="text" name="fleet_name" value="<?php echo $admin->fleet_name;?>" class="form-control"/>

                                                                                  

                                        </div>

                                </div>





                                    <div class="form-group">                                   

                                        <label class="col-md-3 col-xs-12 control-label">Email</label>

                                        <div class="col-md-6 col-xs-12">

                                            <input type="email" name="fleet_email" value="<?php echo $admin->fleet_email; ?>" style="color:black;" class="form-control"/>

                                        </div>

                                    </div>

                                    

                                    <div class="form-group">

                                        <label class="col-md-3 col-xs-12 control-label">Profile Image</label>

                                        <div class="col-md-6 col-xs-12">                      

                                            <input type="file" class="fileinput btn-submit" name="admin_img"/>

                                            <img src="<?php echo base_url('userimage/'.$admin->image);?>" style="width:80px; height: 80px;">

                                            <input type="hidden" name="admin_img" value="<?php echo $admin->image; ?>">

                                        </div>

                                    </div>

                                <div class="panel-footer">

                                    <div class="row">

                                    <input type="reset" class="btn btn-reset" value="Form Reset" style="width:100%; max-width:300px;">                                   

                                    <input type="submit" name="submit" value="Update" class="btn btn-submit pull-right" style="width:100%; max-width:300px; margin-top:5px">

                                    </div>

                                </div>

                            </div>

                            </div>

                        </form>                         

                    </div>

                </div>                    

            </div>



<?php $this->load->view('dispatch/layout/footer');?> 