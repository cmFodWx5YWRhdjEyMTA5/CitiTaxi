
<?php $data['page']='coupon'; $data['title']='Add ride coupon'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Add ride coupon</strong></h3>                           
                                </div>
                            <div class="container">                                      
                            <?php if(isset($error)&& $error==1) { ?>
                            <div class="alert alert-danger">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                             <?php echo $message;?>
                            </div>                           
                            <?php }if(isset($success)&& $success==1){?>
                            <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                             <?php echo $message;?>
                            </div>
                            <?php }?>
                            <form method="post" action="<?php echo site_url('Home/add_ride_promocode');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated"> 
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-6">              
                                            <select name='country' id="country" class="form-control select" data-live-search="true" required>
                                                <option value="">Select Country</option>
                                                <?php foreach(countryies() as $country) { ?>
                                                  <option value="<?php print $country->name; ?>">
                                                    <?php echo $country->name; ?>
                                                  </option>
                                                    <?php } ?> 
                                            </select>                                            
                                        </div>
                                    </div>                                   

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Heading</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="heading" id='heading' class="form-control"/>
                                        </div>                                        
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set Promocode</label>
                                        <div class="col-md-5 col-xs-8">                                            
                                            <input type="text" id='promocode' name="promocode" class="form-control" style="color:red">
                                        </div>
                                        <div class="col-md-1 col-xs-4">                                            
                                            <input type="button" id="promo" class="btn btn-submit" value="Generate">
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Promo code Type</label>
                                        <div class="col-md-6">
                                            <select name='rate_type' class="form-control" required >
                                                <option value="">Select</option>
                                                <option value="Percentage">Percentage</option>
                                                <option value="Flat">Flat rate</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo code Rate</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="rate" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set maximum amount</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="max_amount" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set minimum trip amount</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="min_trip_amount" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promocode Start Date</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="stdate" id="stdate" data-provide="datepicker" class="form-control" placeholder="Select Start Date" required>
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promocode End Date</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="endate" id="endate" data-provide="datepicker" class="form-control" placeholder="Select End Date" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set Users Limit</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="user_limit" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Number of times use limit</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="max_time_use" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Code Description</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <textarea class="form-control" rows="3" name="description"></textarea>
                                        </div>                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Select Attention Person</label>
                                        <div class="col-md-6">
                                            <select name='attention' class="form-control" required >
                                                <option value="All">All Passenger</option>
                                                <option value="Selected">Selected Passenger</option>      
                                            </select>
                                        </div>
                                    </div>                                    
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Select Promotion type</label>
                                        <div class="col-md-6">
                                            <select name='promo_type' class="form-control" required >
                                                <option value="0">Immediate</option>
                                                <option value="1">Compete Trip</option>      
                                            </select>
                                        <div style="color:red"><u>Immediate</u>: Bonous deduct from total trip fare.<br>
                                             <u>Complete Trip</u>: Bonous will transferred to CitiPay wallet.
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Photo</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="file" name="image" class="fileinput btn-submit">
                                        </div>
                                    </div> 
                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="col-md-1"></div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="<?php echo site_url('Home/ride_promocode'); ?>">
                                            <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="reset" class="btn btn-reset" value="Reset" style="margin:5px 0; width:100%;">
                                        </div>                                   
                                        <div class="col-md-3">
                                            <input type="submit" name="submit" value="Submit" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                                        </div>                                    
                                    </div>                                   
                                </div>
                            </div>
                            </form>                         
                        </div>
                    </div>
                </div>                    
             </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?>

<script>
 $(document).ready(function(){  
    var date = new Date();
    date.setDate(date.getDate());
    $('#stdate').datepicker({         
        endDate: date                        
    });
    $("#stdate").on("change",function(){
        $('#endate').val('');         
        var selected = $(this).val();        
        $('#endate').datepicker({ 
            startDate: selected        
        });
    });

    $('#promo').on('click',function(){
        var chars = "ABCDEFGHIJK123456LMNOPQRSTUVWXYZ789";
        var string_length = 6;
        var randomstring = '';
        for (var i=0; i<string_length; i++) {
          var rnum = Math.floor(Math.random() * chars.length);
          randomstring += chars.substring(rnum,rnum+1);
        }
        $('#promocode').val(randomstring);
    });

    $('#heading').on('keyup',function(){
        var country = $('#country').val();    
        console.log('ds'+country);
    });
    



  }); 
</script>




