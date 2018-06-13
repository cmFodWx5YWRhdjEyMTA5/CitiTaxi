<?php $data['page']='revenue'; $data['title']='Trip Earning'; $this->load->view('layout/header',$data);?>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.3.8/angular.min.js"></script>
<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.25/angular-route.js"></script>        

            <!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap" ng-app="myApp" ng-controller="myController">
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Trip Earning</strong></h3>
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
                                    <div class="table-responsive">
                                     <div id="list_table" style="overflow:scroll;">
                                     <table id="example" class="table display">
                                        <thead>
                                            <tr>
                                            <th>Sr.No</th>
                                            <th>TripID</th>
                                            <th>Status</th>
                                            <th>Passenger ID</th>
                                            <th>Passenger Name</th>
                                            <th>Passenger Email</th>
                                            <th>Passenger Phone</th>
                                            <th>Pick Up Location</th>
                                            <th>Dropoff Location</th>
                                            <th>Driver ID</th>
                                            <th>Driver Name</th>
                                            <th>Driver Email</th>
                                            <th>Driver Phone</th>
                                            <th>Driver Trip Earnign</th>
                                            <th>Promocode Apply</th>
                                            <th>Company Commission</th>
                                            <th>Pay To Driver</th>                                            
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <!--?php                                        
                                          $i=1;  
                                        foreach($userlist as $list) {
                                        $dropof=array();
                                         //$status = $list->activeStatus; ?>
                                            <tr>
                                              <td style="text-align:center"><?php echo $i++;?></td>
                                              <?php if($list->booking_status=='0'){ ?>
                                              <td style="color:blue">Assigned</td>
                                              <?php }elseif($list->booking_status=='1'){ ?>
                                              <td style="color:orange">Accept</td>                                             
                                              <?php }elseif($list->booking_status=='2'){ ?>
                                              <td style="color:red">Reject by driver</td>
                                              <?php }elseif($list->booking_status=='3'){?>
                                              <td style="color:red">Reject by Passenger after accept</td>
                                              <?php }elseif($list->booking_status=='4'){ ?>
                                              <td style="color:green">Complete</td>
                                              <?php } elseif($list->booking_status=='5'){ ?>
                                              <td style="color:orange">Arrived</td>                                            
                                              <?php }elseif($list->booking_status=='6'){ ?>
                                              <td style="color:blue">Trip start</td>                                            
                                              <?php } elseif($list->booking_status=='7'){ ?>
                                              <td style="color:red">Reject by Passenger before accept</td>
                                              <?php } ?>


                                              <td><?php echo $list->booking_at; ?></td>
                                              <td><?php echo $list->ride_start_at;?></td>
                                              <td><?php echo $list->driver_arrived_at; ?></td>
                                              <td><?php echo 'Ride '.$list->booking_type;?></td>
                                              <td><?php echo $list->booking_id;?></td>
                                              <td><?php echo getUserDetailsById($list->customer_id);?></td>
                                              <td><?php echo getFleetDetailsById($list->driver_id);?></td>
                                              <td><?php echo getUserDetailsById($list->driver_id);?></td>
                                              <td><?php echo getVechicleDetailsById($list->driver_id);?></td>
                                              <td><?php echo $list->pickup;?></td>
                                              <td>
                                                <a  href="#" data-toggle="modal" data-target="#dropoffs">
                                                  <button type="button" class="btn btn-submit" onclick="dropoff(<?php echo $list->booking_id;?>)">Address</button>
                                                </a>
                                              </td> 
                                              <td><?php echo $list->total_distance." ".$list->distance_unit;?></td>
                                              <td><?php echo $list->total_fare." ".$list->currency;?></td>
                                            </tr>
                                        
                                        </tbody>-->
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


<div class="modal fade" id="dropoffs" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="title">Drop off locations </h4>
      </div>
      <div class="modal-body" id='details'>
        <div class="locations" style="font-size: 13px;"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>  

<script>
  function dropoff(booking_id)
  {   
    $(".locations").html('');
    $.ajax({
      type:'post',
      url:'<?php echo site_url("Home/get_dropoff_address"); ?>',
      data:{'booking_id':booking_id},
      dataType: "json",

      success:function(res)
      {
        console.log(res);                   
        if(res.success==1)
        { 
          var len = res.data.length;
          var j=1;
          var txt = "";
          for(i=0; i<len; i++){          
            txt +='<li>'+res.data[i].dropoff +'</li>';
          }           
          $(".locations").append(txt);
          console.log(txt);                   
          //console.log(res.data.length);                    
        }
        else{
           $(".locations").html('No dropoff location found');          
        }
      }
    });
  }
</script>

<script>
  var dbPost = angular.module('myApp',[]);
  dbPost.controller('myController', function($scope,$http){
    $http.post(site_url+'/api/Auth/get_profile',{"userid":1})
    .success(function(data){        
      console.log(data);     
      });
  });
</script>




