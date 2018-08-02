
<?php $data['page']='coupon'; $data['title']='Add Reedem Post'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->

                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><strong>Add Reedem Post</strong></h3>                            
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
                            <form method="post" action="<?php echo site_url('Home/add_redeem_post');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-6">              
                                            <select name='country_id' class="form-control select" data-live-search="true" onChange="get_currency(this)" required>
                                                <option value="">Select Country</option>
                                                <?php foreach(countryies() as $country) { ?>
                                                  <option value="<?php print $country->id; ?>">
                                                    <?php echo $country->name; ?>
                                                  </option>
                                                    <?php } ?> 
                                            </select> 
                                            <input type="hidden" name="country" id="country_name">
                                            <input type="hidden" name="currency" id="currency" class="form-control">
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Post Timeline Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-submit" name="timeline_photo" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Profile preview Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-submit" name="preview_photo" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Subject Heading</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="heading" class="form-control" > 
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Post Date Display</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="hidden" name="date_display" value="Off"/>
                                            <label class="switch">
                                            <input type="checkbox" name="date_display" value="On"/>
                                            <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set Promo Code</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="promo_code" class="form-control" required title="Please set Promo Code" > 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Set Promo Type</label>
                                        <div class="col-md-6">
                                            <select name='rate_type' class="form-control" required title="Please set Promo Type" >
                                                <option value="">Select Unit</option>
                                                <option value="Per">Percentage</option>
                                                <option value="Flat">Flat</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set Promo Rate</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="promo_rate" class="form-control" required title="Please set Promo rate" > 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Set maxiumum Amount</label>
                                        <div class="col-md-6 col-xs-12">
                                                <input type="text" name="max_amount" class="form-control" > 
                                        </div>
                                    </div>
                                    <!--div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">QR Code Scan</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="hidden" name="QR_scan" value="Off" />
                                            <label class="switch">
                                            <input type="checkbox" name="QR_scan" value="On"/>
                                            <span></span>
                                            </label>
                                        </div>
                                    </div-->
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Start Date</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="promo_start"  id="stdate" data-provide="datepicker" class="form-control" placeholder="Select Start Date" required title="Select Promotion Start date" >
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo End Date</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="promo_end" id="endate" data-provide="datepicker" class="form-control" placeholder="Select End Date" required title="Select Promotion End date">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Promo Code Description</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <textarea class="form-control" rows="3" name="description"></textarea>
                                        </div>                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Set Buttons</label>
                                        <div class="col-md-6">
                                            <select name='buttons' id='buttons' class="form-control" required>
                                                <option value="">Select button</option>
                                                <option value="Exchange Points">Exchange Points</option>
                                                <option value="Buy Now">Buy Now</option>
                                                <option value="Buy With UNpay">Buy With UNpay</option>
                                                <option value="View Details">View Details</option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="form-group" id='points' style="display:none;">
                                        <label class="col-md-3 col-xs-12 control-label">Exchange Points</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="points" id="points" class="form-control" required >
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 control-label">Set Publish Type</label>
                                        <div class="col-md-6">
                                            <select name='publish_type' id='publish_type' class="form-control" required >
                                                <option value="">Select type</option>
                                                <option value="Now">Publish Now</option>
                                                <option value="Later">Publish Later</option>
                                                <option value="Draft">Save As Draft</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group" id='laterdate' style="display:none;">
                                        <label class="col-md-3 col-xs-12 control-label">Publish Later Date</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="text" name="later_date"  id="later" data-provide="datepicker" class="form-control" placeholder="Select publish Date" required >
                                        </div>
                                    </div> 

                                    <div class="panel-footer" style="margin-top:20px;">
                                    <div class="row">
                                    <div class="col-md-6">
                                        <input type="reset" class="btn btn-reset" value="Form Reset" style="margin:5px 0; max-width:300px; width:100%;">
                                    </div>
                                   
                                    <div class="col-md-6">
                                        <input type="submit" name="submit" value="Submit" class="btn btn-submit pull-right" style="max-width:300px; margin:2px 0; width:100%;">
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
            startDate: date                        
        });
        $("#stdate").on("change",function(){
            $('#endate').val('');         
            var selected = $(this).val();        
            $('#endate').datepicker({ 
                startDate: selected        
            });
        });
        $('#later').datepicker({         
            startDate: date                        
        });
        
        $('#publish_type').on('change',function(){
            // if($("#buttons :selected").length>0){
                var type = $('#publish_type').val();
                // var count = $("#buttons :selected").length;
                // console.log(count);
                if(type=='Later'){
                    $('#laterdate').css('display','block');
                }
                else{
                    $('#laterdate').css('display','none');   
                } 
            /*}
            else{
                alert('Please select buttons');
                var type = $('#publish_type').val('');
                return false;
            }*/            
        });

        $('#buttons').on('change',function(){
            // var type = $('#publish_type').val('');
            var buttons = $('#buttons').val();
            if(buttons=='Exchange Points'){
                $('#points').css('display','block');                
            }
            else{
                $('#points').css('display','none');  
            }
        });
    });

     function get_currency(sel){
        var countryid=sel.value;
        //alert(countryid);
        var countryname = sel.options[sel.selectedIndex].text;
        $.ajax({
            type: "get",
            url: "<?php echo site_url('Vehicle/cities/');?>"+countryid, 
            dataType: "json",  
            success:function(data){
                //console.log(data);
                if(data!=null)
                {
                    $('#country_name').val(countryname);                
                    $('#currency').val(data.currency);                
                    //console.log(data);  
                }           
            }
        });
    }
    
       

</script>

    





