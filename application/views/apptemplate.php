<?php  $data['page']='six'; $data['title']='Admin Profile'; $this->load->view('layout/header',$data);?>

<style>
    .bootstrap-select.btn-group .dropdown-menu{
        max-height:200px !important;
    }
</style>
<div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>App Template</strong></h3>                            
                                </div>
                                <div class="container-fluid">
                                <form method="post" action="<?php echo current_url(); ?>" class="form-horizontal" enctype="multipart/form-data" id="apptimeline">
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
                                <label class="col-md-3 col-xs-12 control-label">Time Zone</label>
                                <div class="col-md-6 col-xs-12">  
                                    
                                      <select class="form-control select" data-live-search="true" style="display: none;">
                                        <option value="0">Please, select timezone</option>
                                        <?php foreach(tz_list() as $t) { ?>
                                          <option value="<?php print $t['zone'] ?>">
                                            <?php print $t['diff_from_GMT'] . ' - ' . $t['zone'] ?>
                                          </option>
                                        <?php } ?>
                                      </select>
                                    
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="button" class="btn btn-success" onclick="updateInfos('Copy_right')" value="update">
                                </div> 
                            </div>


                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Timeline Image</label>

                                <div class="col-md-6 col-xs-12">                      
                                    <input type="file" id="file" class="fileinput btn-primary" name="timeline" data-error="#err"/>
                                    <img src="<?php echo base_url('appTemplate/'.$timeline);?>" style="width:80px; height: 80px; margin-left:100px;">
                                    <span id="err"></span>
                                </div>

                                <div class="col-md-2 col-xs-12">
                                    <input type="button" class="btn btn-success" name="imagecheck" id="but_upload" value="update">
                                </div>                                
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 col-xs-12 control-label">Copy right info</label>
                                <div class="col-md-6 col-xs-12">  
                                    <input type="text" name="copyright" id="Copy_right" value="<?php echo $Copy_right;?>" class="form-control">
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="button" class="btn btn-success" onclick="updateInfos('Copy_right')" value="update">
                                </div> 
                            </div>
                           
                            </div>
                            </div>
                        </form>                         
                    </div>
                </div>                    
            </div>

<?php $this->load->view('layout/footer');?> 

<script>
$(document).ready(function(){

    $("#but_upload").click(function(){
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file',files);
        fd.append('image_status','timeline');
        $.ajax({
            url: '<?php echo site_url('Home/imageUpload'); ?>',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                console.log(response);
                if(response != 'Not update'){
                    alert(response);   
                    location.reload();                 
                }else{
                    alert(response);
                }
            },
        });
    });
});
    function updateInfos(status)
    {
        var d= document.getElementById(status).value;
        $.ajax({
            url: '<?php echo site_url('Home/ajaxDataUpdate'); ?>',
            type: 'post',
            data: {'message':d,'status':status},
            success: function(response){
                console.log(response);
                if(response != 'Not update'){
                    alert(response);   
                    location.reload();                 
                }else{
                    alert(response);
                }
            },
        });
    }
</script>

