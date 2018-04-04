
<?php $data['page']='two'; $data['title']='Add Customer'; $this->load->view('layout/header',$data);?>
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
                                    <h3 class="panel-title"><strong>Add</strong>&nbsp;Customer</h3>                            
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
                            <form method="post" action="<?php echo site_url('Home/add_customer');?>" class="form-horizontal" enctype="multipart/form-data" id="jvalidate" name="frm">
                                <div class="panel-body form-group-separated">
                                	<div class="form-group">                                       
                                        <label class="col-md-3 control-label">Service Type Vehicle</label>
                                        <div class="col-md-6">              
                                            <select name='service_type[]' id="myDropdown" multiple class="form-control select" required>
                                            <?php foreach(servicetypes() as $t) { ?>
                                              <option value="<?php print $t->typeid; ?>">
                                                <?php echo $t->servicename; ?>
                                              </option>
                                            <?php } ?>
                                                <!--option value="Eco">Eco</option>
                                                <option value="Biz">Biz</option>
                                                <option value="Star">Star</option-->                                               
                                            </select>                                               
                                        </div>
                                    </div> 
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Description</label>
                                        <div class="col-md-6 col-xs-12">   
                                        	<textarea class="form-control" rows="3" name="description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Maximum Load</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="maxload" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Country</label>
                                        <div class="col-md-6">              
                                            <select name='country' id="country" multiple class="form-control select" required>
                                            	<option>---Select Country---</option>
                                            </select>                                              
                                        </div>
                                    </div> 

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">City</label>
                                        <div class="col-md-6">              
                                            <select name='city' id="city" multiple class="form-control select" required>
                                            	<option>---Select City---</option>
                                            </select>   
                                            <span id='typeError' style="color:red;"></span>
                                        </div>
                                    </div> 

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Currency Unit </label>
                                        <div class="col-md-6">              
                                            <select name='currencyUnit ' id="currency" multiple class="form-control select" required>
                                            	<option>---Select Currency---</option>
                                            </select>   
                                            <span id='typeError' style="color:red;"></span>
                                        </div>
                                    </div> 

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Vehicle Type</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="vehicletype" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Company Comission Type</label>
                                        <div class="col-md-6">              
                                            <select name='commsiontype' id="currency" multiple class="form-control select" required>
                                            	<option value="percentage">Percentage</option>
                                            	<option value="flat">Flat rate</option>
                                            </select>                                              
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Company Comission Rate</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="commissionRate" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">                                       
                                        <label class="col-md-3 control-label">Distance Unit(Km/Mile)</label>
                                        <div class="col-md-6">              
                                            <select name='distUnit' id="currency" multiple class="form-control select" required>
                                            	<option value="Km">Km</option>
                                            	<option value="mile">Mile</option>
                                            </select>                                              
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Per minutes charge</label>
                                        <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                            <label class="switch">
                                                <input type="checkbox" value="0"/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Charge every minutes</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group" style="border-top:1px dashed gray;">
                                        <label class="col-md-3 col-xs-12 control-label" ></label>
                                        <div class="col-md-6 col-xs-12" style="font-size: 14px;color:blue; border-left:none !important;">
                                            <strong><u>Regular Charge</u></strong>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Charge every minutes</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Minimum Base Fare</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Minimum Distance(Km/Mile)</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mini  distance fare</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Charge upon every  km/mile</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Price Per Unit Distance(Km/Mile)</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Per Minute Charge</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                    	<label class="col-md-3 col-xs-12 control-label"><strong>Regular Hours Waiting Charge</strong></label>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Waiting time start after(X)mins</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Price for unit time(every  x mins)</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Price for waiting time</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
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
<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/additional-methods.js"></script>
<script type="text/javascript">
		$(document).ready(function(){

			// Custom method to validate username
			$.validator.addMethod("usernameRegex", function(value, element) {
				return this.optional(element) || /^[a-zA-Z0-9]*$/i.test(value);
			}, "Username must contain only letters, numbers");

			$(".next").click(function(){
				var form = $("#myform");
				form.validate({
					errorElement: 'span',
					errorClass: 'help-block',
					highlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').addClass("has-error");
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).closest('.form-group').removeClass("has-error");
					},
					rules: {
						username: {
							required: true,
							usernameRegex: true,
							minlength: 6,
						},
						password : {
							required: true,
						},
						conf_password : {
							required: true,
							equalTo: '#password',
						},
						company:{
							required: true,
						},
						url:{
							required: true,
						},
						name: {
							required: true,
							minlength: 3,
						},
						email: {
							required: true,
							minlength: 3,
						},
						
					},
					messages: {
						username: {
							required: "Username required",
						},
						password : {
							required: "Password required",
						},
						conf_password : {
							required: "Password required",
							equalTo: "Password don't match",
						},
						name: {
							required: "Name required",
						},
						email: {
							required: "Email required",
						},
					}
				});
				if (form.valid() === true){
					if ($('#account_information').is(":visible")){
						current_fs = $('#account_information');
						next_fs = $('#company_information');
					}else if($('#company_information').is(":visible")){
						current_fs = $('#company_information');
						next_fs = $('#personal_information');
					}
					
					next_fs.show(); 
					current_fs.hide();
				}
			});

			$('#previous').click(function(){
				if($('#company_information').is(":visible")){
					current_fs = $('#company_information');
					next_fs = $('#account_information');
				}else if ($('#personal_information').is(":visible")){
					current_fs = $('#personal_information');
					next_fs = $('#company_information');
				}
				next_fs.show(); 
				current_fs.hide();
			});
			
		});
	</script>

    





