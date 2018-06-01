
<?php $data['page']='point'; $data['title']='Add point'; $this->load->view('layout/header',$data);?>
<!-- PAGE CONTENT WRAPPER -->
<style type="text/css">
		#personal_information,
		#company_information{
			display:none;
		}
	</style>

                <div class="page-content-wrap">
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><strong>Add Point</strong></h3>
            </div>           
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
                <form method="POST" action="<?php echo site_url('Home/add_point');?>" class="form-horizontal" enctype="multipart/form-data" id="fairvalidate" name="frm">
                    <div class="panel-body form-group-separated">
                        
                        <div class="form-group">
                            <label class="col-md-3 control-label">Country</label>
                            <div class="col-md-6">
                                <select name='country_id' class="form-control select" data-live-search="true" onChange="cities(this)" required>
                                    <option value="">Select Country</option>
                                    <?php foreach(countryies() as $country) { ?>
                                    <option value="<?php print $country->id; ?>">
                                        <?php echo $country->name; ?>
                                    </option>
                                    <?php } ?> 
                                </select>
                                <input type="hidden" name="country" id="country_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">City</label>
                            <div class="col-md-6">
                                <select name='city_id' id="city" class="form-control city" required onChange="citiname(this)">
                                    <option value="">Select City</option>
                                </select>
                                <span id='cityError' style="color:red;"></span>
                                <input type="hidden" name="city" id="city_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">Currency</label>
                            <div class="col-md-6">   
                                <input type="text" name="currency" id="currency" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Every Trip (X)Amount Spent </label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="amount_spent" class="form-control" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Get Point</label>
                            <div class="col-md-6 col-xs-12">  
                                <input type="text" name="get_point" class="form-control" required />
                                <div>Exm-if amount spent=200 get point=1 then point get=> tripfare%200</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label">Points Expire Date</label>
                            <div class="col-md-6 col-xs-12">
                                <input type="text" id="licensedate" data-provide="datepicker" name="expire_date" class="form-control" placeholder="DD-MM-YYYY" required/>                                
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer" style="margin-top:20px;">
                        <div class="col-md-1"></div>
                            <div class="row">

                            <div class="col-md-3">
                                <a href="<?php echo site_url('Driver'); ?>">
                                <input type="button" class="btn btn-back" value="Back" style="margin:5px 0; width:100%;">
                                </a>
                            </div>
                            <div class="col-md-3">
                                <input type="reset" class="btn btn-reset" value="Reset" style="margin:5px 0; width:100%;">
                            </div>                       
                            <div class="col-md-3">
                                <input type="submit" name="submit" value="Add Point" class="btn btn-submit pull-right" style="margin:5px 0; width:100%;">
                            </div>
                        </div>
                       
                    </div>                   
                </form>
            </div>
        </div>
    </div>
                <!-- END PAGE CONTENT WRAPPER -->  
 
<?php $this->load->view('layout/footer');?> 
<script>
    $(document).ready(function(){
        var date = new Date();
        date.setDate(date.getDate());
        $('#licensedate').datepicker({ 
            startDate: date
        });
    });
</script>
    
<script type="text/javascript">
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
                $('#currency').val(data.currency);
                $('#cityError').text('');
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
        //alert(serviceid);
        $('#city_name').val(cityname);
    }
</script>






