<?php $data['page']='analytic'; $data['title']='Analytic Dashboard'; $this->load->view('Sub_admin/layout/header',$data);?> 

<style>
       #map {
        height: 500px;
        width: 100%;
       }
    </style>
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Analytic Dashboard</strong></h3>
              
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated" style="min-height: 50px;">
                        <div class="panel-body panel-body-table">
                          <div class="col-md-4">
                            <div class="widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                <span><img src="<?php echo base_url('assest/images/faicon/driver.png'); ?>" width='50%'></span>
                                    
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Drivers</div>                                   
                                    <div class="widget-int num-count"><?php $t = getCount('users',array('user_type'=>1)); echo $t; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/active_driver.png'); ?>" width='50%'></span>                                    
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Active Drivers</div>                                  
                                  <div class="widget-int num-count"><?php $t = getCount('users',array('user_type'=>1,'activeStatus'=>'Active')); echo $t; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                   <span><img src="<?php echo base_url('assest/images/faicon/inactive_driver.png'); ?>" width='50%'></span> 
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">In-Active Drivers</div>                                  
                                  <div class="widget-int num-count"><?php $t = getCount('users',array('user_type'=>1,'activeStatus!='=>'Active')); echo $t; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                            <div class="widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/passenger.png'); ?>" width='50%'></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Passengers</div>                                    
                                    <div class="widget-int num-count"><?php $tp = getCount('users',array('user_type'=>0)); echo $tp; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/active_passenger.png'); ?>" width='50%'></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Active Passengers</div>                                  
                                  <div class="widget-int num-count"><?php $ap = getCount('users',array('user_type'=>0,'activeStatus'=>'Active')); echo $ap; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                   <span><img src="<?php echo base_url('assest/images/faicon/inactive_passenger.png'); ?>" width='30%' ></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">In-Active Passenger</div>                                  
                                  <div class="widget-int num-count"><?php $iap = getCount('users',array('user_type'=>0,'activeStatus!='=>'Active')); echo $iap; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Bookings</div>                                  
                                  <div class="widget-int num-count"><?php $tb = getCount('booking',array()); echo $tb; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/cancel.png'); ?>" width='50%' ></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Cancelled Trip</div>                   
                                  <?php $where = '(booking_status=2 or booking_status=3 or booking_status=7)'; ?>               
                                  <div class="widget-int num-count"><?php $tct = getCount('booking',$where); echo $tct; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/complete.png'); ?>" width='50%' ></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Completed Booking</div>                                  
                                  <div class="widget-int num-count"><?php $tCb = getCount('booking',array('booking_status'=>4)); echo $tCb; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                   <span><img src="<?php echo base_url('assest/images/faicon/pending.png'); ?>" width='50%' ></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Pending Booking</div>
                                  <?php $pwhere = 'booking_type="later" and (booking_status=8 or booking_status=9)'; ?>
                                  <div class="widget-int num-count"><?php $pending = getCount('booking',$pwhere); echo $tCb; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/driver_cancel.png'); ?>" width='55%' ></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Driver Cancelled Booking</div>                                  
                                  <div class="widget-int num-count"><?php $dCb = getCount('booking',array('booking_status'=>2)); echo $dCb; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/customer_cancel.png'); ?>" width='55%' ></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Passenger Cancelled Booking</div>              
                                  <?php $pswhere = '(booking_status=3 or booking_status=7)'; ?>                    
                                  <div class="widget-int num-count"><?php $pCb = getCount('booking',$pswhere); echo $pCb; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <!--div class="col-md-4">Total Fleet</div>
                          <div class="col-md-4">Total Assign Fleet</div>
                          <div class="col-md-4">Total Unassign Fleet</div>  
                          <div class="col-md-4">Total Available Vehicles</div>
                          <div class="col-md-4">Total Unavailable Vehicles </div>
                          <div class="col-md-4">Total In-Active Vehicles</div-->                         
                        </div>
                    </div>
                </div>                
            </div>                    
        </div>

<?php $this->load->view('Sub_admin/layout/footer');?> 

