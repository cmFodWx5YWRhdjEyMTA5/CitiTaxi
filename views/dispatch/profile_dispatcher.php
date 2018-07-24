<?php $data['page']='setting'; $data['title']='Profile'; $this->load->view('dispatch/layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                   <h3 class="panel-title"><strong>Profile</strong></h3>                           
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
                            <form method="post" action="<?php echo site_url('Dispatch/disptach_profile');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Name</label>
                                        <div class="col-md-6 col-xs-12">
                                            <input type="text" name="name" value="<?php echo $details->name; ?>" class="form-control" > 
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mobile Number</label>
                                        <div class="col-md-6 col-xs-12">                                               
                                            <input type="text" name="dismobile" value="<?php echo $details->phone; ?>" id="mobile" class="form-control" maxlength="10" required/>
                                        </div>
                                        <span id="errMobile"></span>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">E-mail</label>
                                        <div class="col-md-6 col-xs-12">                                            
                                            <input type="email" name="dispatch_email" class="form-control" value="<?php echo $details->email; ?>" required>
                                        </div>
                                    </div>  
                                   
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Address </label>
                                        <div class="col-md-6 col-xs-12">                                            
                                          <input type="text" name="address" value="<?php echo $details->address; ?>" class="form-control"/>
                                        </div>
                                    </div>   

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-6">    
                                        <span><?php echo $details->country; ?></span>
                                        </div>
                                    </div>

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">City</label>
                                        <div class="col-md-6">              
                                            <span><?php echo $details->city; ?></span>
                                        </div>
                                    </div>                               

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Photo</label>
                                        <div class="col-md-6 col-xs-12">                      
                                            <input type="file" class="fileinput btn-fileinput" name="image" id="image" data-error="#err" />
                                             <img src="<?php echo base_url('fleetimage/'.$details->image); ?>" width="80px" height="80px">
                                            <span id="err"></span>
                                        </div>
                                    </div>  
                                    
                                    <div class="panel-footer" style="margin-top:20px;">
                                        <div class="col-md-1"></div>
                                        <div class="row">
                                            <div class="col-md-3 pull-right">
                                                <input type="submit" name="submit" value="Submit" class="btn btn-submit" style="margin:5px 0; width:100%;">
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
 
<?php $this->load->view('dispatch/layout/footer');?> 
    

<script> 
    function cities(sel)
    {   //alert(sel.value);
        $(".city option:gt(0)").remove(); 
        var countryid=sel.value;
        var countryname = sel.options[sel.selectedIndex].text;            
        $('.city').find("option:eq(0)").html("Please wait....");
            $.ajax({
            type: "get",
            url: "<?php echo site_url('Vehicle/cities/');?>"+countryid, 
            dataType: "json",  
            success:function(data){
            console.log(data);
            if(data!=null)
            {
                $('#country_name').val(countryname);
                $('.city').find("option:eq(0)").html("Please Select city");
                $('#city').append(data.data);//alert(data);
                //$('#currency').val('');               
                //console.log(data);  
            }
            else
            {
                $('#cityError').text('City is not found. Please select another country');
            }
            }
        });        
    }
     function citiname(city)
        {
            var cityid   = city.value;
            var cityname = city.options[city.selectedIndex].text;             
            $('#city_name').val(cityname);
        }



</script>



