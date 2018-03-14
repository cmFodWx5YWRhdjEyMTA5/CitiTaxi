<?php $data['page']='driver'; $data['title']='Vechicle Image'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                 <ul class="breadcrumb">
                        <li><a href="<?php echo site_url('Driver/other_details/'.$driverid);?>">Other Details</a></li>
                        <li class="active">Vechile Images</li>
                    </ul>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">

                                    <?php if(isset($success)==1){ ?>
                                    <div class="alert alert-success">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo $message;?>
                                    </div>        
                                    <?php } else if(isset($error)==1) { ?>
                                    <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    <?php echo $message;?>
                                    </div>
                                    <?php }?> 
                                <div class="panel-heading">
                                      <h3 class="panel-title"><strong>Vechicle </strong> Image <?php echo count($images); ?></h3> 
                                      <div class="btn-group pull-right">
                                     <?php if(count($images)<7){ ?> 
                                     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                                         Add More image
                                     </button>
                                     <?php } ?>
                                     <input type="hidden" id="driverid" value="<?php echo $driverid; ?>">
                                     <input type="hidden" id="vechileid" value="<?php echo $vechileid; ?>">
                                     <input type="hidden" id="maxImage" value="<?php echo 7-count($images); ?>">
                                                                           </div>
                                </div>                                

                                <div class="panel-body">
                                    <div class="table-responsive">
                                     <div style="overflow:scroll; height:550px;">
                                     <table id="example1" class="table display">
                                        <thead>
                                            <tr>
                                                <th>Sr.No</th>
                                                <th style="min-width:500px;">Image</th>
                                                <th style="max-width:50px;">Update</th>                                            
                                                <th style="max-width:50px;">Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                          $i=1;  
                                        foreach($images as $img) { ?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $i++;?></td>                             
                                                <td>  
                                                 <a  href="#" id="link1" data-toggle="modal" data-target="#qbimageModal">
                                                    <img onclick="changeIt(this)" src="<?php echo base_url('vechicleImage/'.$img->vechile_image);?>" width="250" height="150">
                                                </a>
                                                </td>                                                
                                                <td>                                                    
                                                 <div><input type="file" id="file" value="Select"></div>
                                                 <div style="margin-top:2%">
                                                    <input type="button" onclick="imageUpdate(<?php echo $img->image_id; ?>)" class="btn btn-success" value="Update">
                                                    <input type="hidden" id="imgId" value="<?php echo $img->image_id; ?>">
                                                 </div>
                                                   
                                                </td>
                                                <td>
                                                  <input type="button" value="X Delete" class="btn btn-primary" onclick="deleteimage(<?php echo $img->image_id;?>)">
                                                </td>
                                            </tr>
                                        <?php } ?>

                                        </tbody>
                                    </table> 
                                    </div>                                   
                                    </div>
                                </div>

                            </div>
                            <!-- END DATATABLE EXPORT -->
                        </div>
                    </div>
                </div>         



                <!-- END PAGE CONTENT WRAPPER -->

<?php $this->load->view('layout/second_footer');?> 

<!-- Modal to upload image -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">     
      <div class="modal-body" style="height:100px;">
        <form method="post" id="upload_form">  
            <label class="col-md-3 col-xs-12 control-label" style="margin-top:5% !important;">Add More Image </label>
        <div class="col-md-6 col-xs-9">     
            <input type="file" name="images[]" id="select_image" multiple style="margin-top:10% !important;"  />                 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="upload" class="btn btn-success">Upload</button>
      </div>
    </div>
  </div>
</div>



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
  function changeIt(img)
  {
    var name = img.src;  
    //alert(name);
    document.getElementById("showImg").innerHTML="<center><button type='button' class='close' data-dismiss='modal'aria-hidden='true' style='color:white;opacity:1;'>&times;</button><img class=img-responsive src='"+name+"'/></center>";
  }
  function imageUpdate(id)
  {
    var fd = new FormData();
        var files = $('#file')[0].files[0];
        fd.append('file',files);
        fd.append('image_id',id);
        $.ajax({
            url: '<?php echo site_url('Driver/imageUpdate'); ?>',
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
  }

  function deleteimage(imageid)
  {
    $.ajax({
      method:'POST',
      url:"<?php echo site_url('Driver/vechileImageDelete');?>",
      data:{'imageid':imageid},
      success:function(data){
        alert(data);
        location.reload();
      },
    });
  }
  </script>
  <!-- Using javascript -->

<script type="text/javascript">

$(document).ready(function(){
  $('#upload_form').on('submit', function(e){  
           e.preventDefault();  
           var fd = new FormData(this);
           var vechileid = document.getElementById('vechileid').value;
           var driverid = document.getElementById('driverid').value;
           fd.append('vechile',vechileid);
           fd.append('driver',driverid);
           //alert(fd);
           $.ajax({  
                url :"<?php echo site_url('Driver/ajaxMulitpleImageupload'); ?>",  
                method:"POST",  
                data:fd,  
                contentType:false,  
                processData:false,  
                success:function(data){  
                    alert(data);
                    location.reload();
                }  
           });  
      }); 

    $('input#select_image').change(function(){
      var maxSelect = document.getElementById('maxImage').value;
      var files = $(this)[0].files;
      if(files.length >maxSelect){
          $('#upload').addClass('disabled',true);
          alert("you can select max "+ maxSelect+" files.");
          location.reload();          
      }
    });   

  });
  </script>

