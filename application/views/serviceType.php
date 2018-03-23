<?php $data['page']='vehicle'; $data['title']='taxi services'; $this->load->view('layout/header',$data);?> 

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Services Type</strong></h3>
                    <div class="btn-group pull-right">
                         <button type="button" class="btn btn-submit" data-toggle="modal" data-target="#exampleModal">
                             Add Service Type
                         </button>   
                     </div>
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated">
                        <div class="panel-body panel-body-table">

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-actions">
                                            <thead>
                                                <tr>
                                                    <th width="50">#</th>
                                                    <th>Service Name</th>
                                                    <th>Status</th>
                                                    <th width="100">Edit</th>
                                                    <th width="100">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                <?php $i=1; foreach ($types as $t =>$v) { ?>
                                                <tr">
                                                    <td class="text-center"><?php echo $i++; ?></td>
                                                    <td><strong><?php echo $v->servicename; ?></strong></td>
                                                    <td style="color:<?php if($v->status=='active'){ echo 'green';} else{echo 'red';} ?>"><strong><?php echo strtoupper($v->status); ?></strong></td>
                                                    <td>
                                                        <a  href="#" data-toggle="modal" data-target="#exampleModal1">
                                                        <button class="btn btn-default btn-rounded btn-sm" onclick="changeIt(<?php echo "'".$v->servicename."',".$v->typeid; ?>)"><span class="fa fa-pencil"></span>
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <?php if($v->status=='active'){ $status ='deactive';} else{$status='active';}?>

                                                        <input type='button' class="btn btn-danger btn-rounded btn-sm" onClick="changeStatus(<?php echo $v->typeid;?>,<?php echo "'".$status."'";?>);" value="<?php echo $status; ?>">
                                                    </td>
                                                </tr>
                                                <?php } ?>                                           
                                               
                                            </tbody>
                                        </table>
                                    </div>                                

                                </div>               
                    </div>
                </div>
            </div>                    
        </div>

<?php $this->load->view('layout/footer');?> 

<!-- Modal to upload image -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">     
      <div class="modal-body" style="height:100px;">
        <form method="post">          
        <div class="panel-body form-group-separated">
        <div class="form-group"> 
        <label class="col-md-3 col-xs-12 control-label">Enter Service Type</label>
        <div class="col-md-9 col-xs-12">
            <input type="text" name="type" id="type" class="form-control" required />
        </div>            
        </div>
      </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-reset" data-dismiss="modal">Close</button>
        <button type="submit" id="add" class="btn btn-submit">Add Service</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal to update  service type -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">     
      <div class="modal-body" style="height:100px;">
        <form method="post">  
        <div class="panel-body form-group-separated">
        <div class="form-group"> 
        <label class="col-md-3 col-xs-12 control-label">Enter Service Type</label>
        <div class="col-md-9 col-xs-12">
            <input type="text" name="type"   id="types" class="form-control" required />
            <input type="hidden" name="typeid" id="typeid" class="form-control" required />
        </div>            
        </div>
      </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-reset" data-dismiss="modal">Close</button>
        <button type="submit" id="update" class="btn btn-submit">Update</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){
        $('#add').on('click',function(){
            var servicetype = $('#type').val();
            if(servicetype!=''){
                $.ajax({
                 type: 'post',
                 data:{'servicetype':servicetype},
                 url:"<?php echo site_url('Home/addServiceType');?>",
                 success:function(res)
                 {
                    alert(res);
                    location.reload();
                 }
                });
            }
            else
            {
                alert('Please enter Service name');
            }
            
        });

        $('#update').on('click',function(){
            var servicetype = $('#types').val();
            var typeid = $('#typeid').val();
            if(servicetype!=''){
                $.ajax({
                 type: 'post',
                 data:{'servicetype':servicetype,'typeid':typeid},
                 url:"<?php echo site_url('Home/updateServiceType');?>",
                 success:function(res)
                 {
                    alert(res);
                    location.reload();
                 }
                });
            }
            else
            {
                alert('Please enter Service name');
            }
            
        });
    })

    function changeStatus(id,status)
    {
        $.ajax({
            type:'post',
            data:{'typeid':id,'status':status},
            url:"<?php echo site_url('Home/changeTypeStatus');?>",
            success:function(res)
            {
                alert(res);
                location.reload();
            }
        });
    }

    function changeIt(servicename,id)
    {
        $('#types').val(servicename);
        $('#typeid').val(id);
    }
</script>