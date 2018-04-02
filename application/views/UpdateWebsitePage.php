<?php $data['page']='six'; $data['title']='About us'; $this->load->view('layout/header',$data);?> 

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>About Us</strong></h3>
                </div>
                <div class="container-fluid">
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
                        <form enctype="multipart/form-data" method="post" action="<?php echo site_url('Home/update_websitePage/'.$pagecontent->page_id); ?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Page name</label>
                                <div class="col-md-9 ">
                                    <input type="text" name="page_name" class="form-control" value="<?php if(isset($pagecontent)){print_r($pagecontent->page_name);} ?>">
                                </div>                                
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 col-xs-6 control-label">Banner Image</label>
                                <div class="col-md-4 col-xs-6">
                                    <input type="file" accept="image/*" name="bannerImage" class="fileinput btn-primary">
                                </div>  
                                <div class="col-md-3">
                                <a  href="#" id="link1" data-toggle="modal" data-target="#qbimageModal">
                                    <img onclick="changeIt(this)" src="<?php echo base_url('pageImages/'.$pagecontent->banner); ?>" style='height:100px; width: 100px'>
                                    <input type="hidden" name="imagename" value="<?php echo $pagecontent->banner; ?>">
                                </a>
                                </div>
                            </div>
                            <div class="block">
                                <textarea class="summernote" name='content'>                                
                                <?php if(isset($pagecontent)){print_r($pagecontent->content);} ?>
                                </textarea>                               
                                
                            </div>
                            
                            <div class="panel-footer">
                                <input type="button" class=" btn btn-back" value="Back" style="margin-top:7px;">
                                <input type="submit" name="submit" class=" btn btn-submit pull-right" value="Update" style="margin-top:7px;">
                            </div> 
                        </form>
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
            function changeIt(img)
            {
                var name = img.src;  
                document.getElementById("showImg").innerHTML="<center><button type='button' class='close' data-dismiss='modal'aria-hidden='true' style='color:white;opacity:1;'>&times;</button><img class=img-responsive src='"+name+"'/></center>";
            }
        </script>   


<!-- Images in large view  -->
<div class="modal fade" id="qbimageModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background-color:rgba(0, 0, 0, 0.78);">
    <div class="modal-dialog-md">
    <div class="modal-header" style="background:black !important; color:white;border-bottom:0px !important; cursor:pointer;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>    
    </div>
    <div class="modal-content">
        <div class="modal-body" id='' style="max-width:100%;height:auto; background-color:black;">
         <div class='col-sm-12' id="showImg">           
        </div>
    </div>
        
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialo-->

<!-- Image in large view End  -->

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