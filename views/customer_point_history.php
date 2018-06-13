<?php $data['page']='point'; $data['title']='Customer Point history'; $this->load->view('layout/header',$data);?>          
            <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                <ul class="breadcrumb">
                    <li><a href="<?php echo site_url('Home/point_history/');?>">Point History</a></li>
                    <li class="active">Customer point history</li>
                </ul>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Customer Point History</strong></h3>
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
									
	                                    <table id="example" class="table display">
	                                        <thead>
	                                            <tr>
		                                            <th>Sr.No</th>
		                                            <th>Booking ID</th>
		                                            <th>Customer ID</th>
		                                            <th>Driver ID</th>
		                                            <th>Pick UP</th>
		                                            <th>Drop Off</th>	                                            
		                                            <th>Booking At</th>
		                                            <th>Total Fare</th>
		                                            <th>Earn Point</th>
		                                            <th>Booking Status</th>	                                            
		                                            <th>Edit Point</th>	                                            
	                                        	</tr>
	                                        </thead>
	                                        <tbody>
	                                        <?php
	                                          $i=1;  
	                                        foreach($userlist as $list) {?>
	                                            <tr>
	                                                <td style="text-align:center"><?php echo $i++;?></td>
	                                                <td><?php echo $list->booking_id;?></td>
	                                                <td><?php echo $list->customer_id;?></td>
	                                                <td><?php echo $list->driver_id;?></td>
	                                                <td><?php echo $list->pickup;?></td>                                              
	                                                <td>
		                                                <a  href="#" data-toggle="modal" data-target="#dropoffs">
		                                                  <button type="button" class="btn btn-submit" onclick="dropoff(<?php echo $list->booking_id;?>)">Address</button>
		                                                </a>
		                                             </td> 
	                                                <td><?php echo $list->booking_at;?></td>
	                                                <td><?php echo $list->total_fare." ".$list->currency;?></td>
	                                                <td><?php echo $list->customer_trip_score; ?></td>

	                                                <!--==============Booking Status ===========================-->
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
		                                            <!--==============Booking Status End=========================-->  
	                                                <td>
                                                        <a  href="#" data-toggle="modal" data-target="#exampleModal1">
                                                        <button class="btn btn-default btn-rounded btn-sm" onclick="changeIt(<?php echo $list->customer_trip_score.','.$list->booking_id; ?>)"><span class="fa fa-pencil"></span>
                                                        </button>
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

<!-- Modal to Edit point type -->
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">     
      <div class="modal-body" style="height:100px;">
        <form method="post">  
        <div class="panel-body form-group-separated">
        <div class="form-group"> 
        <label class="col-md-3 col-xs-12 control-label">Point</label>
        <div class="col-md-9 col-xs-12">
            <input type="text" name="point" id="point" class="form-control" required />
            <input type="hidden" name="prepoint" id="prepoint" class="form-control"/>
            <input type="hidden" name="customer_id" id="customer_id" class="form-control"/>
            <input type="hidden" name="booking_id" id="booking_id" class="form-control"/>
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

  	function changeIt(point,booking_id)
    {
        $('#point').val(point);
        $('#prepoint').val(point);        
        $('#booking_id').val(booking_id);
    }
</script>

<script>
$(document).ready(function(){
	/*var date = new Date();
    date.setDate(date.getDate());
    $('#date').datepicker({ 
        endDate: date
    });*/


	$('#update').on('click',function(){		
        var point 		= $('#point').val();
        var prepoint 	= $('#prepoint').val();        
        var booking_id 	= $('#booking_id').val();
        //alert(prepoint);
        if(point!='' && booking_id!=''){
            $.ajax({
             type: 'POST',
             data:{'point':point,'prepoint':prepoint,'booking_id':booking_id},
             url:"<?php echo site_url('Home/update_booking_point');?>",
             success:function(res){
                alert(res);
                location.reload();
             }});
        }
        else{
            alert('Please enter point');
        }          
    });
});	 

</script>
