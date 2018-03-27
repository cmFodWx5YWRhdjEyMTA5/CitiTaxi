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
                        <form method="post" action="<?php echo site_url('Welcome/websiteContent'); ?>">
                            <div class="block">
                                <textarea class="summernote" name='content'>                                
                                <?php print_r($pagecontent->content); ?>
                                </textarea>                               
                                
                            </div>
                            <div class="box-body pad text-center">
                                    <input type="submit" name="submit" class=" btn btn-submit" value="Submit" style="float:; margin-top:7px;">
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