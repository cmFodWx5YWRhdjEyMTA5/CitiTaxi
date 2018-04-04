<?php $data['page']='website'; $data['title']='Website content'; $this->load->view('layout/header',$data);?> 

<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Services Type</strong></h3>
                    <div class="btn-group pull-right">
                        <a href="<?php echo site_url('Home/add_websitePage');?>">
                           <button type="button" class="btn btn-submit">Add More Page </button>   
                        </a>
                     </div>
                </div>
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
                <div class="container-fluid">
                    <div class="panel-body form-group-separated">
                        <div class="panel-body panel-body-table">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-actions">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Page name</th> 
                                            <th>Privew</th>                                                   
                                            <th width="100">Edit</th>
                                            <th width="100">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php $i=1; foreach ($pages as $p =>$v) { ?>
                                        <tr">
                                            <td class="text-center"><?php echo $i++; ?></td>
                                            <td><strong><?php echo $v->page_name; ?></strong></td>
                                            <td><a target="blank" href="<?php echo site_url('Welcome/websitepage/'.urldecode($v->page_id.'/'.$v->page_name));?>">Privew</a></td>
                                            <td>
                                                <a  href="<?php echo site_url('Home/update_websitePage/'.$v->page_id); ?>">
                                                <button class="btn btn-default btn-rounded btn-sm"><span class="fa fa-pencil"></span>
                                                </button>
                                            </td>
                                            <td>
                                              <a href="">
                                                <input type='button' class="btn btn-danger btn-rounded btn-sm" value="Delete">
                                              </a>
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

