                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Zone Fare</strong></label>
                                        <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                            <label class="switch">
                                                <input type="checkbox" value="0" id="f6" onChange="divshow(6)"/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="see6" style="display:none">
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
                                        <label class="col-md-3 col-xs-12 control-label">Price Per Unit Distance(Km/Mile)</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label" style="color:blue;"><strong>Add Fixed Location Names</strong></label>
                                        <div class="col-md-6 col-xs-12" style="padding: 6px 0px 0px 12px !important;">  
                                            <label class="switch">
                                                <input type="checkbox" value="0" id="f7" onChange="divshow(7)"/>
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="see7" style="display:none">
                                    <div class="form-group">
                                      <label class="col-md-3 col-xs-12 control-label">Add Location</label>
                                      <div class="col-md-6 col-xs-12">   
                                         <input type="button" name="time" class="btn btn-submit" onclick="addElement();" value="Add more location" required>
                                      </div>
                                   </div>  
                                   <div class="form-group">
                                      <div id="content"></div>
                                   </div>
                                   </div>
                                   <div class="form-group">
                                        <label class="col-md-3 col-xs-12 control-label">Mutiple Stop Booking  Surcharge (Flat Rate)</label>
                                        <div class="col-md-6 col-xs-12">  
                                            <input type="text" name="everymin_charge" class="form-control" />
                                        </div>
                                    </div>


<script>
    var intTextBox = 0; 
    function addElement() 
    {
        intTextBox++;
        var objNewDiv = document.createElement('div');
        document.getElementById('content').appendChild(objNewDiv);
        objNewDiv.setAttribute('id', 'div_' + intTextBox);
        objNewDiv.innerHTML ='<div class="form-group"><label class="col-md-3 col-xs-12 control-label" style="margin-left:7px;">Zone'+intTextBox+'</label><div class="col-md-6 col-xs-10"><input type="text" name="everymin_charge" class="form-control" /></div><input type="button" class="btn btn-danger" onclick="removeElement();" value="X" style="margin-top: 12px;line-height: 7px !important;"></div>';
    }

    function removeElement()
    {
        if(1 < intTextBox) {
            document.getElementById('content').removeChild(document.getElementById('div_' + intTextBox));
            intTextBox--;
        } 
        else{
            $('#f7').prop('checked',false);
            $('.see7').hide();
            
        }
    }
</script>