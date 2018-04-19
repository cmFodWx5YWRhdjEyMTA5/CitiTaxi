<?php $data['page']='six'; $data['title']='Search Setting'; $this->load->view('layout/header',$data);?> 
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Night Search Setting</h3>
                </div>
                <div class="panel-body form-group-separated">
                    <form method="post" action="<?php echo current_url(); ?>" id="jvalidate" class="form-horizontal">
                        <div class="panel-body form-group-separated">
                            <?php if(isset($error)&& $error==1) { ?>
                            <div class="alert alert-danger">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php echo $message;?>
                            </div>
                            <?php }  if(isset($success)&& $success==1){?>
                            <div class="alert alert-success">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                <?php echo $message;?>
                            </div>
                            <?php }?>
                            <div class="form-group">                                
                                <label class="col-md-3 col-xs-12 control-label">From</label>
                                <div class="col-md-3 col-xs-6">
                                    <select name='fromHr' class="form-control" required >
                                        <option value="">Select from Hour</option>
                                        <?php for($i=0; $i<24; $i++){ if($i<10){$a = '0'.''.$i;}else{$a=$i;} ?>
                                        <option value="<?php echo $a ?>"><?php echo $a; ?></option>
                                        <?php } ?>
                                    </select>                                    
                                </div>                         
                                <div class="col-md-3 col-xs-6">
                                    <select name='fromMin' class="form-control" required >
                                        <option value="">Select from Hour</option>
                                        <?php for($i=0; $i<24; $i++){ if($i<10){$a = '0'.''.$i;}else{$a=$i;} ?>
                                        <option value="<?php echo $a ?>"><?php echo $a; ?></option>
                                        <?php } ?>
                                    </select>                                    
                                </div>       
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">To</label>
                            <div class="col-md-6 col-xs-12">
                                <select name='fromtime' class="form-control" required >
                                    <option value="">Select To Hour</option>
                                    <?php for($i=0; $i<24; $i++){ if($i<10){$a = '0'.''.$i;}else{$a=$i;} ?>
                                    <option value="<?php echo $a ?>"><?php echo $a; ?></option>
                                    <?php } ?>
                                </select>                                    
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Search Range</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" name="range" class="form-control"/>
                            </div>
                        </div>
                </div>
                <div class="panel-footer">
                <div class="row">
                <input type="reset" class="btn btn-reset" value="Form Reset" style="width:100%; max-width:300px;">
                <input type="submit" name="submit" value="Update" class="btn btn-submit pull-right" style="width:100%; max-width:300px; margin-top:5px">
                </div>
                </div>
            </div>
            </form>                         
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer');?>