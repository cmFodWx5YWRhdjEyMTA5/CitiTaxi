<?php $data['page']='point'; $data['title']='Point history'; $this->load->view('layout/header',$data);?>          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Point History</strong></h3>
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
                                </div>            
                                <div class="panel-body">
									<!-- Posts List -->
									<div class="table-responsive">
                                     	<div style="overflow:scroll; max-height:600px;">
                                     	<!-- Search form (start) -->
                                	<div class="col-md-3 col-xs-12 pull-right" style="margin-bottom:6px; margin-right:30px;">
									<form method='post' action="<?= site_url('/Home/point_history') ?>" >
										<div class="col-md-5 col-xs-5">
										<input class="form-control" type='text' name='name' value='<?= $search_name ?>' data-toggle="tooltip" data-placement="top" title="Search by Name" placeholder='name'>
										</div>
										<div class="col-md-5 col-xs-5">
										<input class="form-control" type='text' name='email' value='<?= $search_email ?>' data-toggle="tooltip" data-placement="top" title="Search by email" placeholder='email'>
										</div>
										<div class="col-md-2 col-xs-2">
										<input class="btn" type='submit' name='submit' value='Search' style="padding: 6px 20px;">
										</div>
									</form>
									</div>
									<br/>
	                                    <table id="example1" class="table display">
	                                        <thead>
	                                            <tr>
	                                            <th>Sr.No</th>
	                                            <th>Passenger ID</th>
	                                            <th>Passenger Name</th>
	                                            <th>Passenger Email</th>	                                            
	                                            <th>Phone Number</th>
	                                            <th>Total Trip</th>
	                                            <th>Complete Trip</th>
	                                            <th>Total Points</th>
	                                            <th>Total Spent</th>
	                                            <th>Status</th>	                                            
	                                            <th>View Details</th>	                                            
	                                        </tr>
	                                        </thead>
	                                        <tbody>
	                                        <?php
	                                          $i=1;  
	                                        foreach($userlist as $list) { $status = $list->activeStatus; ?>
	                                            <tr>
	                                                <td style="text-align:center"><?php echo $i++;?></td>
	                                                <td><?php echo $list->id;?></td>
	                                                <td><?php echo $list->name; ?></td>                                               
	                                                <td><?php echo $list->email; ?></td>
	                                                <td><?php echo $list->mobile;?></td>
	                                                <td><?php echo getCount('booking',array('customer_id'=>$list->id)); ?></td>
	                                                <td><?php echo getCount('booking',array('customer_id'=>$list->id,'booking_status'=>4)); ?></td>
	                                                <td style="color:blue;" ><strong>
	                                                	<?php echo getSum('booking','customer_trip_score',array('customer_id'=>$list->id));?>
	                                                </strong></td>
	                                                <td><?php echo getSum('booking','total_fare',array('booking_status'=>4,'customer_id'=>$list->id));?></td>
	                                                <td style="font-size:14px;<?php if($status=='Active'){?> color:blue;<?php }else{?>color:red;<?php }?>"><strong><?php echo  $list->activeStatus; ?></strong>
                                                    <br><?php echo $list->suspend_type; ?>
                                                	</td>                                              
	                                                <td>
	                                                 <a href="<?php echo site_url('Home/customer_point_history/'.$list->id);?>">
	                                                  <strong>View</strong>
	                                                 </a>
	                                                </td>
	                                            </tr>	                                            
	                                        <?php } ?>
	                                        </tbody>
	                                    </table> 
									<!-- Paginate -->
									<div class="pull-right">
										<div  style='margin:10px;text-align:right;'>
											<?= $pagination; ?>
										</div>
									</div>
								</div>                                   
                            </div>
                        </div>
                    </div>
                    <!-- END DATATABLE EXPORT -->
                </div>
    <?php $this->load->view('layout/second_footer');?> 
                       
