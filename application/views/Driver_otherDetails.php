<?php $data['page']='driver'; $data['title']='Driver other details'; $this->load->view('layout/header',$data);?>
          
            <!-- PAGE CONTENT WRAPPER -->
    <style>
        table tbody th,td{
            border-left: 1px solid black;
            border-bottom: 1px solid black;
        }
        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color:white !important;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }       
    </style>

                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Vechile</strong>Details</h3>        
                                    <div class="btn-group pull-right">
                                        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
                                        <ul class="dropdown-menu">
                                            <li class="divider"></li>
                                            <li><a href="#" onClick ="$('#customers2').tableExport({type:'excel',escape:'false'});"><img src='<?php echo base_url();?>/assest/img/icons/xls.png' width="24"/> XLS</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onClick ="$('#customers2').tableExport({type:'png',escape:'false'});"><img src='<?php echo base_url();?>/assest/img/icons/png.png' width="24"/> PNG</a></li>
                                            <li><a href="#" onClick ="$('#customers2').tableExport({type:'pdf',escape:'false'});"><img src='<?php echo base_url();?>/assest/img/icons/pdf.png' width="24"/> PDF</a></li>
                                        </ul>
                                    </div> 

                                    <?php if(isset($sucess)==1){ ?>
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
                                </div>                                

                                <div class="panel-body">
                                    <div class="table-responsive">
                                     <div style="overflow:scroll; max-height:200px;">
                                     <table id="customers2" class="table">
                                        <thead>
                                            <tr>
                                            <th>Vechile id</th>
                                            <th>Driver id</th>
                                            <th>Brand</th>
                                            <th>Sub Brand</th>
                                            <th>Insurance No</th>
                                            <th>Insurance Company</th>
                                            <th>Booking Limit</th>
                                            <th>Images</th>
                                            <th style="min-width:50px; text-align:center">Edit</th>
                                            <!--th style="min-width:50px; text-align:center">Delete</th-->
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(isset($vechile_details) && $vechile_details!='') {?>
                                            <tr>
                                                <td style="text-align:center"><?php echo $vechile_details->vechileId;?></td>
                                                <td><?php echo $vechile_details->driver_id; ?></td>           
                                                <td><?php echo $vechile_details->brand;?></td>
                                                <td><?php echo $vechile_details->sub_brand; ?></td>
                                                <td><?php echo $vechile_details->insurance_no;?></td>
                                                <td><?php echo $vechile_details->insurance_company;?></td>
                                                <td><?php echo $vechile_details->booking_limit;?></td>
                                                 <td>
                                                    <a href="<?php echo site_url('Driver/vechileImage/'.$vechile_details->driver_id.'/'.$vechile_details->vechileId);?>"><button class="btn btn-success">Vechile Image</button></a>
                                                </td>
                                                <td>
                                                    <a href="<?php echo site_url('Driver/updateVechile/'.$vechile_details->vechileId);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                                <!--td>
                                                 <a href="<!?php echo site_url('user/delete_user?id='.$vechile_details->vechileId);?>">
                                                  <i class="fa fa-trash-o fa-fw">
                                                  <strong>Delete</strong></i>
                                                 </a>
                                                </td-->
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Bank and Driving License</strong>Details </h3>        
                                    <div class="btn-group pull-right">
                                        <button class="btn btn-danger dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> Export Data</button>
                                        <ul class="dropdown-menu">
                                            <li class="divider"></li>
                                            <li><a href="#" onClick ="$('#customers2').tableExport({type:'excel',escape:'false'});"><img src='<?php echo base_url();?>/assest/img/icons/xls.png' width="24"/> XLS</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#" onClick ="$('#customers2').tableExport({type:'png',escape:'false'});"><img src='<?php echo base_url();?>/assest/img/icons/png.png' width="24"/> PNG</a></li>
                                            <li><a href="#" onClick ="$('#customers2').tableExport({type:'pdf',escape:'false'});"><img src='<?php echo base_url();?>/assest/img/icons/pdf.png' width="24"/> PDF</a></li>
                                        </ul>
                                    </div> 

                                    <?php if(isset($sucess)==1){ ?>
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
                                </div>                                

                                <div class="panel-body">
                                    <div class="table-responsive">
                                     <div style="overflow:scroll; height:200px;">
                                     <table id="customers2" class="table">
                                        <thead>
                                            <tr style="font-size:16px !important; ">
                                            <th colspan="4" style="text-align:center; border-right:1px solid white; background:#5b5d5f !important;">Bank Detials</th>
                                            <th colspan="4" style="text-align:center; background:#5b5d5f !important;">License Details</th>
                                            </tr>
                                            <tr>

                                                <th style="min-width:150px;">Bank Name</th>
                                                <th style="min-width:180px;">Branch Code-Name</th>
                                                <th style="min-width:100px;">AccountNo</th>
                                                <th style="min-width:50px; border-right:1px solid white;">Edit</th>
                                                <!--th style="min-width:50px;">Delete</th-->

                                                <th style="min-width:150px;">License Number</th>
                                                <th style="min-width:180px;">Expire Date</th>
                                                <th style="min-width:100px;">License Image</th>
                                                <th style="min-width:50px;">Edit</th>
                                                <!--th style="min-width:50px;">Delete</th-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(isset($bank) && $bank!=''){?>
                                            <tr>
                                                <td><?php echo $bank->bankName; ?></td>                                               
                                                <td><?php echo $bank->branchCode_Name;?></td>
                                                <td><?php echo $bank->accountNo; ?></td>
                                                <td>
                                                    <a href="<?php echo site_url('Driver/updateBankDetails/'.$bank->bankId);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                            <?php } if(isset($license) && $license!=''){ ?>

                                                <td style="text-align:center"><?php echo $license->licenseNumber;?></td>
                                                <td><?php echo $license->expireDate; ?></td>       
                                                <td>
                                                <a  href="#" id="link1" data-toggle="modal" data-target="#qbimageModal">
                                                <img onclick="changeIt(this)" src="<?php echo base_url('licenseImage/'.$license->licenseImage);?>" width='60px' height='60px' style="cursor:pointer; padding:5px; border:1px solid;">
                                                </a>
                                                </td>                                               
                                                <td>
                                                    <a href="<?php echo site_url('Driver/updateLicenseDetails/'.$license->licenseId);?>">
                                                    <i class="fa fa-pencil fa-fw">
                                                    <strong>Edit</strong>
                                                    </i></a>
                                                </td>
                                            <?php } ?>
                                            </tr>
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

<!-- Image in large view  -->
<div class="modal fade" id="qbimageModal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="background-color:rgba(0, 0, 0, 0.78);">
    <div class="modal-dialog-md">
    <div class="modal-header" style="background:black !important; color:white;border-bottom:0px !important; cursor:pointer;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>    
      </div>
      <div class="modal-content">
        <div class="modal-body" id='' style="max-width:100%;height:auto; background-color:black;">
         <div class='col-sm-12' id="showImg">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
    document.getElementById("showImg").innerHTML="<center><img class=img-responsive src='"+name+"'/><button type='button' class='close' data-dismiss='modal'aria-hidden='true'>&times;</button></center>";
  }
  </script>
  <!-- Using javascript -->