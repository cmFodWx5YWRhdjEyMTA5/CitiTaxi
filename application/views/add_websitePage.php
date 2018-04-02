<?php $data['page']='website'; $data['title']='Add website page'; $this->load->view('layout/header',$data);?> 

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Add website page</strong></h3>
                </div>
                <div class="container-fluid">
                    <span id='imagerr' style="color:red"></span>
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
                    <div class="panel-body form-group-separated">
                        <form enctype="multipart/form-data" method="post" action="<?php echo site_url('Home/add_websitePage'); ?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Enter Page name</label>
                                <div class="col-md-9">
                                    <input type="text" name="page_name" class="form-control" required>
                                </div>
                                <div class="col-md-offset-3"></div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-6 control-label">Banner Image</label>
                                <div class="col-md-6 col-xs-6">
                                    <input type="file" accept="image/*" name="bannerImage" class="fileinput btn-primary" required>
                                </div>                                
                            </div>                           
                            <div class="block">
                                <textarea class="summernote" name='content'>                                
                                
                                </textarea>                               
                                
                            </div>
                            
                            <div class="panel-footer">
                                <input type="submit" name="submit" class=" btn btn-submit pull-right" value="Submit" style="float:; margin-top:7px;">
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-6 control-label">Select Image</label>
                                <div class="col-md-3 col-xs-6">
                                    <input type="file" id="file" name="pageimage" accept="image/*" class="fileinput btn-primary image">
                                    <input type="button" id="but_upload" class="btn btn-submit" value="Upload">

                                </div>
                                <div class="col-md-3">
                                    <input type="text" id='imageurl' class="form-control">
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>                    
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer');?> 

 <!-- THIS PAGE PLUGINS -->        
    <script type='text/javascript' src='<?php echo base_url('assest/js/plugins/icheck/icheck.min.js');?>'></script>
    <script type="text/javascript" src="<?php echo base_url('assest/js/plugins/codemirror/codemirror.js');?>"></script>        
    <script type='text/javascript' src="<?php echo base_url('assest/js/plugins/codemirror/mode/htmlmixed/htmlmixed.js');?>"></script>
    <script type='text/javascript' src="<?php echo base_url('assest/js/plugins/codemirror/mode/xml/xml.js');?>"></script>
    <script type='text/javascript' src="<?php echo base_url('assest/js/plugins/codemirror/mode/javascript/javascript.js');?>"></script>
    <script type='text/javascript' src="<?php echo base_url('assest/js/plugins/codemirror/mode/css/css.js');?>"></script>
    <script type='text/javascript' src="<?php echo base_url('assest/js/plugins/codemirror/mode/clike/clike.js');?>"></script>
    <script type='text/javascript' src="<?php echo base_url('assest/js/plugins/codemirror/mode/php/php.js');?>"></script>    

    <script type="text/javascript" src="<?php echo base_url('assest/js/plugins/summernote/summernote.js');?>"></script>
<!-- END PAGE PLUGINS -->

        <script>
            var editor = CodeMirror.fromTextArea(document.getElementById("codeEditor"), {
                lineNumbers: true,
                matchBrackets: true,
                mode: "application/x-httpd-php",
                indentUnit: 4,
                indentWithTabs: true,
                enterMode: "keep",
                tabMode: "shift"                                                
            });
            editor.setSize('100%','420px');
        </script>   

<script>
$(document).ready(function(){
    $("#but_upload").click(function(){
        var fd = new FormData();
        var files = $('#file')[0].files[0];
        console.log(files);
        fd.append('file',files);
        fd.append('folder','pageImages');
        $.ajax({
            url: '<?php echo site_url('Home/pageImageUpload'); ?>',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            success: function(response){
                //console.log(response);
                if(response != 'not upload'){
                    $('#imageurl').val('');
                    $('#imageurl').val(response);                    
                }else{
                    alert('Oops! something went wrong, Please try again');
                    location.reload(true);
                }
            },
        });
    });    

    /*$('.image').change(function(){
        //alert('hii');
        var file = this.files[0].files;
        var fileName = file.name;
        var ext = fileName.split('.').pop();
        //var files = $(this)[0].files;
        if(ext=='jpg' || ext=='jpeg' || ext=='png'){
          $('#imagerr').text('');          
        }
        else
        {
          $('#imagerr').text('Invalid image type. Only jpg,jpeg or png image allow');          
          $(".image").val("");
          
        }         
    });*/
});
</script>