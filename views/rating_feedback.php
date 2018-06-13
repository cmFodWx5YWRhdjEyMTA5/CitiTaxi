<?php $data['page']=$page; $data['title']='Rating and Feedback'; $this->load->view('layout/header',$data);?>          
            <!-- PAGE CONTENT WRAPPER -->
              <div class="page-content-wrap">
                <ul class="breadcrumb">
                  <?php if($page=='two'){ ?>
                    <li><a href="<?php echo site_url('Home/customers/');?>">Passenger List</a></li>
                    <?php }else{?>
                      <li><a href="<?php echo site_url('Driver/');?>">Driver List</a></li>
                      <?php } ?>
                    <li class="active">Rating and Feedback</li>
                </ul>
                  <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Rating and Feedback</strong></h3>
                                </div>            
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
                                <div class="panel-body">                                  
                									<!-- Posts List -->
                									<div class="table-responsive">
                                     	<div id="list-table" style="overflow:scroll;">  
	                                      <table id="example" class="table display">
	                                        <thead>
	                                            <tr>
		                                            <th>Sr.No</th>
		                                            <th>Trip ID</th>
                                                <th>Receiver Name</th>
                                                <th>Receiver Email</th>
                                                <th>Giver Name</th>
                                                <th>Giver Email</th>                                             
                                                <th>Feedback</th>
                                                <th>Rating</th>
		                                            <th>Feedback Date</th>
                                                <th>Delete</th>                                               
	                                        	</tr>
	                                        </thead>
	                                        <tbody>
	                                        <?php
	                                          $i=1;  
	                                        foreach($list as $list) {?>
	                                            <tr>
	                                                <td style="text-align:center"><?php echo $i++;?></td>
	                                                <td><?php echo $list->booking_id;?></td>
                                                  <?php $rec = getSingleDetail('users',array('id'=>$list->receiver_id)); ?>
	                                                <td><?php echo $rec->name;?></td>
	                                                <td><?php echo $rec->email;?></td>
                                                  <?php $sen = getSingleDetail('users',array('id'=>$list->giver_id)); ?>
                                                  <td><?php echo $sen->name;?></td>
                                                  <td><?php echo $sen->email;?></td>
                                                  <td><?php echo $list->review;?></td>              
                                                  <td style='color:yellow;'><?php echo $list->rating;?></td>              
	                                                <td><?php echo $list->review_at;?></td>             
	                                                <td><a href="<?php echo site_url('Home/remove_reivew/'.$list->review_id); ?>">Delete</a>
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
