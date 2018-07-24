<?php $data['page']='newbooking'; $data['title']='New booking'; $this->load->view('dispatch/layout/header',$data);?> 

<style>
       #map {
        height: 500px;
        width: 100%;
       }
       #mymap {
        height: 500px;
        width: 100%;   
       }
       #heatmap {
        height: 500px;
        width: 100%;   
       }
       .sp-pre-con {
          position: fixed;
          left: 0px;
          top: 0px;
          width: 100%;
          height: 100%;
          z-index: 9999;        
          background: url(<?php echo base_url('assest/images/myloading.gif'); ?>) center no-repeat #00000070;
        } 
        
          
        
    </style>
<div class="page-content-wrap">
  <div class="row">  
    <!-- ======================================================================= -->
    <div class="col-md-12">
      <div class="panel panel-default">
       <!-- Show loader -->                               
       <div class="sp-pre-con" style="display: none;"></div>
       <!-- Show loader -->
       <div class="panel-heading">
          <h3 class="panel-title"><strong>Searching</strong></h3>
       </div>
       <div class="container-fluid">
          <div class="panel-body form-group-separated" style="min-height: 75px;">
             <div class="panel-body panel-body-table">
                <div class="col-md-3">
                   <div class="col-md-12" style="margin-bottom: 2%;">Service type</div>
                   <div class="col-md-12">
                      <select name='service_type' id="service_type" class="form-control">
                         <option value="">Select</option>
                         <?php foreach(servicetypes() as $t) { ?>
                         <option value="<?php print $t->typeid; ?>">
                            <?php echo $t->servicename; ?>
                         </option>
                         <?php } ?>                                                                                             
                      </select>
                   </div>
                </div>
                <div class="col-md-2">
                   <div class="col-md-12" style="margin-bottom: 2%;">Select Status</div>
                   <select name="status" id="status" class="form-control">
                      <option value="">Please Select Status</option>
                      <option value="free">Free</option>
                      <option value="busy">Busy</option>
                   </select>
                </div>
                <input type="hidden" id="country" value="<?php echo $this->country; ?>">
                <input type="hidden" id="city" value="<?php echo $this->city; ?>">
                <div class="col-md-1" style="margin-top: 1.3%;">
                   <div class="col-md-12">                              
                      <input type="button" onclick="search_driver()" name="" id='search' value="search" class="btn btn-submit">
                   </div>
                </div>
             </div>
          </div>
       </div>
      </div>
    </div>
    <!-- ======================================================================= -->
    <div class="col-md-12">
      <div class="panel panel-default">
       <div class="panel-heading">
          <h3 class="panel-title"><strong>New Booking</strong></h3>
       </div>
        <div class="col-md-4">
           <div class="panel-body form-group-separated">
              <form action="#" id="my-form" method="post">
                <div class="panel panel-default panel-body panel-body-table">
                  <div class="col-md-12" style="padding-top:3% !important;">
                     <label class="col-md-3 col-xs-12 control-label">Service</label>
                     <div class="col-lg-9 col-md-9 col-xs-12">
                        <select name='service_type' id='sel_service' onchange="nearByDriver(this);" class="form-control" required="Select service">
                           <option value="">Select</option>
                           <?php 
                              $country = $this->country; $city = $this->city;
                              //echo $country;
                              
                              $where = array("country"=>$country,"city"=>$city); 
                              $details = getMultipleDetail('fare',$where);
                              foreach($details as $t) { ?>
                           <option value="<?php echo $t->serviceType_id; ?>">
                              <?php echo $t->service_name; ?>
                           </option>
                           <?php } ?>                                                                                             
                        </select>
                     </div>
                  </div>
                  <div class="col-md-12" style="padding-top:3% !important;">
                     <label class="col-lg-3 col-md-3 col-xs-12 control-label">Pickup</label>
                     <div class="col-md-9 col-xs-12">
                        <input type="text" name="name" id="source" class="form-control" required>
                        <input type="hidden" id="source_lat"> 
                        <input type="hidden" id="source_lng"> 
                     </div>
                  </div>
                  <div class="col-md-12" style="padding-top:3% !important;">
                     <label class="col-md-3 col-xs-12 control-label">Drop Off</label>
                     <div class="col-md-9 col-xs-12">
                        <input type="text" name="name" id="destination" class="form-control" required>                  
                        <div id="mapdist" style=" background: yellow; color: red;"></div>
                        <input type="hidden" id="dest_lat"> 
                        <input type="hidden" id="dest_lng">  
                        <input type="hidden" id='distance'>
                        <input type="hidden" id='time'>
                        <input type="hidden" id="est_fare">
                        <input type="hidden" id="est_fareUnit">
                     </div>
                  </div>
                  <div class="col-md-12" style="padding-top:3% !important;">
                     <label class="col-md-3 col-xs-12 control-label">Driver Note</label>
                     <div class="col-md-9 col-xs-12">
                        <input type="text" id="note" class="form-control" > 
                     </div>
                  </div>
                  <div class="col-md-12" style="padding-top:3% !important;">
                     <label class="col-md-3 col-xs-12 control-label">Persons</label>
                     <div class="col-md-9 col-xs-12">
                        <select name='person' id="person" class="form-control person" required>
                           <option value="">Select Person</option>
                        </select>
                     </div>
                  </div>                  

                  <div class="col-md-12" style="padding-top:3% !important;">
                     <label class="col-md-3 col-xs-12 control-label">Passenger ID</label>
                     <div class="col-md-9 col-xs-12">
                        <input type="text" id='passenger_id' class="form-control" required> 
                        <span><a href="#" data-toggle="modal" data-target="#passengerID"><id>Find Passenger ID?</id></a></span>
                     </div>
                  </div>
                  <div class="col-md-12" style="padding-top:3% !important; padding-bottom:3% !important;">
                     <label class="col-md-3 col-xs-12 control-label">Booking type</label>
                     <div class="col-md-9 col-xs-12">
                        <select id="book_type" class="form-control" onchange="divshow(this)" required>
                           <option value="now">Now</option>
                           <option value="later">Later</option>
                        </select>
                     </div>
                  </div>
                  <div class="see" style="display:none">
                     <div class="col-md-12" style="padding-top:3% !important;">
                        <label class="col-md-3 col-xs-12 control-label">Pickup Date</label>
                        <div class="col-md-9 col-xs-12">  
                           <input type="text" id="later_pickup_date" data-provide="datepicker" class="form-control" placeholder="DD-MM-YYYY" />                
                        </div>
                     </div>
                     <div class="col-md-12" style="padding-top:3% !important; padding-bottom:3% !important;">
                        <label class="col-md-3 col-xs-12 control-label">Time</label>
                        <div class="col-md-9 col-xs-12">  
                           <input type="text" id='later_pickup_time'  class="form-control timepicker"/>
                        </div>
                     </div>
                  </div>
                </div>
                <div class="panel-footer">          
                  <div class="row">
                    <div class="col-md-5">
                      <input type="button" class="btn btn-back" onclick="calculatefare()" value="Check" style="margin:5px 0; max-width:300px; width:100%;">
                    </div>
                    <div class="col-md-5 pull-right">
                      <input type="reset" class="btn btn-reset" value="Reset" style="margin:5px 0; max-width:300px; width:100%;">
                    </div>
                    </div>
                    <div class="row">          
                      <div class="col-md-8 col-md-offset-2">
                        <input type="submit" name="submit" onsubmit="booked()" value="Confirm Booking" class="btn btn-submit" style="margin:5px 0; max-width:300px; width:100%;">
                      </div> 
                      <div class="col-md-2">
                        <input type="button" onclick="checkDriver()" value="check">
                      </div>         
                  </div>
                </div> 
              </form>
           </div>
        </div>
        <div class="col-md-8">
           <div class="panel panel-default">
              <div class="container-fluid">
                 <div class="panel-body form-group-separated" style="padding:5px !important">
                    <div class="panel-body panel-body-table">
                       <div id="map"></div>
                       <div id="mymap"></div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ======================================================================================== -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
         <div class="modal-body" style="text-align:center !important;">
            <span id="message" ></span>
            <hr>
            <div style="text-align:center;margin-top:5%">
               <button type="button" class="btn btn-submit" data-dismiss="modal" style="width: 75%;">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>


<div class="modal fade" id="passengerID" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
   <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
         <div class="modal-body" style="text-align:center !important;">
            <div class="col-md-12" style="padding-top:3% !important;">
               <label class="col-md-3 col-xs-12 control-label">Passenger Email</label>
               <div class="col-md-8 col-xs-10" style="padding-right:0px !important">
                  <input type="text" id='passenger_email' class="form-control" placeholder="Please enter email to find passenger id" required>                                               
               </div>
               <div class="col-md-1 col-xs-2">
                  <button onclick="getDetails()" class="btn btn-submit" style="padding:6px;margin-left:-5px;border-radius:0px !important">Search</button>
               </div>
            </div>        
            <span> *** </span>
            <span id="passmessage" ></span>                
            
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-submit pull-right" data-dismiss="modal">Done</button>
        </div>

      </div>
   </div>
</div>

<?php $this->load->view('dispatch/layout/footer');?> 
<script>
  function getDetails(){    
    var email = document.getElementById('passenger_email').value;
    if(email!=''){
      $(".sp-pre-con").css("display", "block"); 
      $.ajax({
        type:'POST',
        dataType:'json',
        data:{'email':email},
        url: site_url+'/Dispatch/get_userDetails',
        success:function(res){
          console.log(res);
          if(res.error==0){
            $(".sp-pre-con").css("display", "none"); 
            var msgData = '<div style="font-size:14px;font-weight:600; color:green;">Passenger ID : '+res.data.id+' Name : '+res.data.name+'</div>';
            $('#passenger_id').val(res.data.id);            
            $('#passmessage').html(msgData);
            console.log(res.data.id);
          }
          else{
            $(".sp-pre-con").css("display", "none"); 
            var msg = '<div style="color:red;">'+res.message+'</div>';
            $('#passmessage').html(msg);            
            //console.log(res.message);
          }
        },
        error:function(res){
          var msg = '<div style="color:red;">Something went wrong</div>';
          $('#passmessage').html(msg);
          //console.log('something went wrong');
        }
      })
    }
    else{
      alert('Please enter passenger email id');
      return false;
    }
  }
  function checkDriver(){
    $(".sp-pre-con").css("display", "block");
    $.ajax({
        type:'POST',
        dataType:'json',                
        url:"<?php echo site_url('Dispatch/BookDriver'); ?>",
        success:function(res) {
            console.log(res);            
          if(res.success==1){
            $(".sp-pre-con").css("display", "none");
            $('#myModal').modal({'show' : true});

            var msgData = '<span style="font-size:14px;font-weight:600px">'+res.message+'</span><div style="font-size:14px;font-weight:600;">Booking ID : CT'+res.booking_id+'</div><div><img src="'+res.data.driver_image+'" style="width:80px;height:80px; border-radius:60%"></div><div style="font-size:14px;">'+res.data.driver_name+'</div><div>CitiTaxi- '+res.data.service_name+'</div><div style="font-size:14px;"><strong>'+res.data.vehicle_no+'</strong></div><div>'+res.data.vehicle_name+'</div><div><a href="#" onClick="openTripTrackWindow('+res.booking_id+','+res.data.driver_id+');"  style="font-size: 14px !important;color: blue !important;font-weight: 600;">Track Trip</a></div>';

            $('#message').html(msgData);
          }
          else{
            $(".sp-pre-con").css("display", "none");
            $('#myModal').modal({'show' : true});
            $('#message').text(res.message);
          }
        }
      });

  }


   $(document).ready(function(){     

    $('#later_pickup_time').val(''); 
    var date = new Date();          
    $(document).on('submit', '#my-form', function() {
      var fare = function () {
        var tmp = null;
        var vtype = document.getElementById('sel_service');
        var servicetype  = vtype[vtype.selectedIndex].value;
        var ride_distance = document.getElementById('distance').value;
        var time = document.getElementById('time').value;
        
        $.ajax({
          async: false,
          type:'POST',
          data:{'service_id':servicetype,'distance':ride_distance,'time':time},
          url:'<?php echo site_url('Dispatch/calculateFair'); ?>',
          dataType:"json",            
          success:function(fare){
            tmp = fare;
          }
        });
        return tmp;
      }(); 

      var customer_id = document.getElementById('passenger_id').value;
      var country     = document.getElementById('country').value;
      var city        = document.getElementById('city').value;
      var service_type_id = document.getElementById('sel_service').value;
      var pickup      = document.getElementById('source').value;
      var pickupLat   = document.getElementById('source_lat').value;
      var pickupLong  = document.getElementById('source_lng').value;
      var dropoff     = document.getElementById('destination').value;
      var dropoffLat  = document.getElementById('dest_lat').value;
      var dropoffLong   = document.getElementById('dest_lng').value;
      var distance    = document.getElementById('distance').value;     
      var ride_distance = distance.split(" km").join("");
      var time        = document.getElementById('time').value;
      var ride_time   = time.split(" mins").join("");
      var month       = ((date.getMonth().length+1) === 1)? (date.getMonth()+1) : '0' + (date.getMonth()+1);
      var book_date   = date.getDate()+'-'+month+'-'+date.getFullYear();
      var book_time   = date.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });      
      var total_regular_charge    = fare.total_regular_charge;
      var total_perminute_charge  = fare.total_per_minute_charge;
      var total_fare              = fare.total_fair;     
      var booking_type            = document.getElementById('book_type').value;
      var later_pickup_date       = document.getElementById('later_pickup_date').value;
      var later_pickup_time       = document.getElementById('later_pickup_time').value;
      var booking_note            = document.getElementById('note').value;
      var passenger               = document.getElementById('person').value;  
      
      var dropoffs = [{'dropoff':dropoff,'dropoffLat':dropoffLat,'dropoffLong':dropoffLong}];
      //var dropoffs = JSON.stringify(drop);
      //dropoffs = '['+dropoffs+']';
      //console.log(dropoffs);
      var full_data = {'customer_id':customer_id,'country':country,'city_name':city,'service_type_id':service_type_id,"booking_address_type":"Single",'pickup':pickup,'pickupLat':pickupLat,'pickupLong':pickupLong,'dropoff':dropoffs,'date':book_date,'time':book_time,'total_ride_time':ride_time,'total_ride_distance':ride_distance,'total_regular_charge':total_regular_charge,'total_perminute_charge':total_perminute_charge,'total_fair':total_fare,'booking_type':booking_type,'later_pickup_date':later_pickup_date,'later_pickup_time':later_pickup_time,'booking_note':booking_note,'passenger':passenger,'payment_type':'cash','promocode_status':'No','promo_id':''};
      //console.log(full_data);

      $.ajax({
        type:'POST',
        data:JSON.stringify(full_data),
        //dataType:'json',
        contentType: "application/json",        
        url:"<?php echo site_url('Dispatch/BookDriver'); ?>",
        success:function(res) {
          console.log(res);
          if(res.success==1){
            $(".sp-pre-con").css("display", "none");
            $('#myModal').modal({'show' : true});

            var msgData = '<span style="font-size:14px;font-weight:600px">'+res.message+'</span><div style="font-size:14px;font-weight:600;">Booking ID : CT'+res.booking_id+'</div><div><img src="'+res.data.driver_image+'" style="width:80px;height:80px; border-radius:60%"></div><div style="font-size:14px;">'+res.data.driver_name+'</div><div>CitiTaxi- '+res.data.service_name+'</div><div style="font-size:14px;"><strong>'+res.data.vehicle_no+'</strong></div><div>'+res.data.vehicle_name+'</div><div><a href="#" onClick="openTripTrackWindow('+res.booking_id+','+res.data.driver_id+');"  style="font-size: 14px !important;color: blue !important;font-weight: 600;">Track Trip</a></div>';
            $('#message').html(msgData);
          }
          else{
            $(".sp-pre-con").css("display", "none");
            $('#myModal').modal({'show' : true});
            $('#message').text(res.message);
          }
        }
      });   
      //alert(service_type_id);
      //var service_type_id = cty[cty.selectedIndex].text;
      return false;
    });
       
    date.setDate(date.getDate());
    var end = new Date();
    end.setDate(end.getDate() +2); //number  of days to add, e.x. 15 days  
    $('#later_pickup_date').datepicker({ 
        startDate: date,
        endDate:end        
    });   
    
  });
</script>



<script>  
    function openTripTrackWindow(booking_id,driver_id)
    {      
      //alert(booking_id+' '+driver_id);
        var top = window.screen.height -800;
        top = top > 0 ? top/2 : 0;            
        var left = window.screen.width - 950;
        left = left > 0 ? left/2 : 0;
        var uploadWin = window.open("<?php echo site_url('Dispatch/trackTrip/') ?>"+booking_id+'/'+driver_id,"Trip Tracking","width=950,height=800,top="+top+",left="+left);
        uploadWin.moveTo(left, top);
        uploadWin.focus();
    }

  function divshow(s)
  {
    if(s.value=='later'){
      $('.see').show();
      $('#later_pickup_date').prop('required',true);      
      $('#later_pickup_time').prop('required',true); 
      

    }
    else{      
      $('.see').hide();
      $('#later_pickup_date').prop('required',false);
      $('#later_pickup_date').val('');
      $('#later_pickup_time').prop('required',false);      
      $('#later_pickup_time').val(''); 
    }      
  }
  function calculatefare(){
    var vtype = document.getElementById('sel_service');
    var servicetype  = vtype[vtype.selectedIndex].value;
    alert(vtype);
    var distance = document.getElementById('distance').value;
    var time = document.getElementById('time').value;
    if(servicetype!='' && distance!='' && time!=''){
        $.ajax({
        type:'POST',
        data:{'service_id':servicetype,'distance':distance,'time':time},
        url:'<?php echo site_url('Dispatch/calculateFair'); ?>',
        dataType:"json",
        success:function(fare){
          console.log(fare);
          document.getElementById('est_fare').value=fare.total_fair;
          document.getElementById('est_fareUnit').value=fare.currency;
          var message = "Estimate Distance : "+distance        
              message += "\n";
              message += "Estimate ride time : "+time;
              message += "\n";
              message += "Estimate fare : "+fare.total_fair+' '+fare.currency;  
              message += "\n";
              message += "**Final fare will be calculate after complete trip";  
              alert(message);                             
            console.log(fare.total_fair);
          },
          error:function(fare){
            console.log(fare);
          }
        });
      }
      else{
        alert('Please select servicetype, enter pickup and dropoff location');

      }
    
  }

//AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw
//AIzaSyAmEjk44-ZHG_nlWbkBmmhc_y4dbcY-dJc
//key=AIzaSyDy6Pol7SiacA3CfIBmkgZjlozY-VToAeI&
</script>
  <script src="https://maps.google.com/maps/api/js?key=AIzaSyCAaSsKL0bekeYUkT3GyP-od5YdJzANQO0&libraries=visualization,places,geometry" type="text/javascript"></script>
  <script src="<?php echo base_url('assest/routeBoxer/public/routeBoxer.js');?>" type="text/javascript"></script>
  <script type="text/javascript">

      var dispatch_city = '<?php echo $city; ?>';
      var dispatch_country = '<?php echo $country; ?>';
      
      var trafficLayer; var heatmap;
      document.getElementById('mymap').style.display="none";
      //var locations =fleetlocations();
      var locations =[];     
      var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: new google.maps.LatLng(22.718991,75.855698),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        gestureHandling: 'greedy'
      }); 


      function nearByDriver(sel){
        //var vtype = document.getElementById('book_service_type');
        var per='';
        var servicetype  = sel.value;
        var status = 'free';
        var country = document.getElementById('country').value;
        var city = document.getElementById('city').value;  
        $('.person').html('');
        $('.person').find("option:eq(0)").html("Please wait....");      
          $.ajax({
              type: "POST",
              url: "<?php echo site_url('Dispatch/search_driver');?>", 
              data:{'servicetype':servicetype,'status':status,'country':country,'city':city}, 
              dataType:'json',         
              success:function(locationdata){                
                console.log(locationdata);
                if(locationdata[0]!=''){ 
                  $('.person').find("option:eq(0)").html("Select Persons");
                  for(var i=1; i<=locationdata.max_person; i++){
                  $('.person').append('<option value='+i+'>'+i+'</option>');                    
                  }
                //console.log(per);
                  initialize(locationdata.marker); 
                }                                         
              },
            error: function(){
              alert('Search result is not found');
              location.reload(true);
            }
        });
    } 
      

    
    //=========================================== Initial Map finish====================================

    function search_driver()
    {
        var vtype = document.getElementById('service_type');
        var servicetype  = vtype[vtype.selectedIndex].value;
        var status = document.getElementById('status');
        var driverstatus = status[status.selectedIndex].value;        
        /*var ctry = document.getElementById('country_id');
        var country = ctry[ctry.selectedIndex].text;
        var cty = document.getElementById('city');
        var city = cty[cty.selectedIndex].text;*/         
        var country = document.getElementById('country').value;
        var city = document.getElementById('city').value;         
        if (servicetype=='' && driverstatus==''){
          alert('Please select altest 1 field.');
        }           
        else{
         $(".sp-pre-con").css("display", "block");          
            $.ajax({
              type: "POST",
              url: "<?php echo site_url('Dispatch/search_driver');?>", 
              data:{'servicetype':servicetype,'status':driverstatus,'country':country,'city':city}, 
              dataType:'json',         
              success:function(locationdata){
                console.log(locationdata);                
                if(locationdata[0]!=''){
                  $(".sp-pre-con").css("display", "none"); 
                  search_result(locationdata.marker);  
                }              
            },
            error: function(){
              alert('Search result is not found');
              location.reload(true);
            }
          });         
        }       
    }

    function search_result(locations){      
      document.getElementById('map').style.display="block";
      document.getElementById('mymap').style.display="none";
      var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 13,
      center: new google.maps.LatLng(locations[0][1],locations[0][2]),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'greedy'
      });      

      var infowindow = new google.maps.InfoWindow();
      var marker, i;
      for (i=0; i<locations.length; i++) {  
          marker     = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][1], locations[i][2]),        
          map: map,
          icon: base_url+'/mapmarker/car.png'
        });

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            var content = '<div> ID: '+locations[i][4]+'</div><div> Name: '+locations[i][3]+'</div><div> Email: '+locations[i][5]+'</div><div> Mobile: '+locations[i][6]+'</div>';
            infowindow.setContent(content);
            //infowindow.setContent('ID : '+locations[i][4]+'\n Name: '+locations[i][3]);
            infowindow.open(map, marker);
          }
        })(marker, i));
    }
  }

  //==============================Search Result Map finish=======================================================

    var sourceLat, sourceLng;
    var destinationLat, destinationLng;
    function initialize(locations='') {
      var rendererOptions = {
          map: map,
          suppressMarkers: false,
          polylineOptions: {
            strokeColor: 'blue'
          }
        };
      var directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
      directionsDisplay.setOptions({map: map,suppressMarkers: true });
      var directionsService = new google.maps.DirectionsService();      
      var map;
      var routeBoxer = new RouteBoxer();
      var distance = 1;
      var cascadiaFault;
      var routeBounds = [];

      var mapOptions = {
        center: new google.maps.LatLng(locations[0][1], locations[0][2]),
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.ROADMAP
      };

      var map = new google.maps.Map(document.getElementById('map'), mapOptions);
      directionsDisplay.setMap(map);  

      if(locations!=''){
        var infowindow = new google.maps.InfoWindow();
          var marker, i;
          for (i=0; i<locations.length; i++) {  
              marker     = new google.maps.Marker({
              position: new google.maps.LatLng(locations[i][1], locations[i][2]),        
              map: map,
              icon: base_url+'/mapmarker/car.png'
            });

            google.maps.event.addListener(marker, 'click', (function(marker, i) {
              return function() {
                var content = '<div> ID: '+locations[i][4]+'</div><div> Name: '+locations[i][3]+'</div><div> Email: '+locations[i][5]+'</div><div> Mobile: '+locations[i][6]+'</div>';
                infowindow.setContent(content);
                //infowindow.setContent('ID : '+locations[i][4]+'\n Name: '+locations[i][3]);
                infowindow.open(map, marker);
              }
            })(marker, i));
        }
      }  

      var source     = new google.maps.places.Autocomplete(document.getElementById('source'));
      var infoWindow = new google.maps.InfoWindow();
      var marker     = new google.maps.Marker({             
          map: map,                 
          suppressMarkers: true
      });

      google.maps.event.addListener(source, 'place_changed', function() {
        infoWindow.close();
        var place = source.getPlace();        
        marker.setPosition(place.geometry.location);
        sourceLat = marker.getPosition().lat();
        sourceLng = marker.getPosition().lng();
        document.getElementById('source_lat').value=sourceLat;
        document.getElementById('source_lng').value=sourceLng;
        console.log(place.address_components);
        //console.log(place.address_components[0].types[0]);
        var city=''; country='';
        for (var c = 0; c < place.address_components.length; c++) {
          if(place.address_components[c].types[0]=='locality'){
            city = place.address_components[c].long_name;
          }
          if(place.address_components[c].types[0]=='country'){
            country = place.address_components[c].long_name;
          }
        }
        if(country=='Myanmar (Burma)'){
          country='Myanmar';
        }
        if(dispatch_country!=country || dispatch_city!=city){
          alert('You can book only in '+dispatch_country+' of city '+dispatch_city);
          location.reload(true);
        }
        //country = place.address_components[5].long_name;
        //city = place.address_components[2].long_name;
        //console.log(country+','+city);
        //console.log(place.address_components);
        infoWindow.setContent('<div><strong>' + place.name + '</strong><br>');  
        //infoWindow.open(map, marker);           
      });

      var destination = new google.maps.places.Autocomplete(document.getElementById('destination'));      
      var infoWindow  = new google.maps.InfoWindow();
      var marker = new google.maps.Marker({ 
        suppressMarkers: true,      
          map: map, 
          icon: base_url+'/mapmarker/pin3.png'          
      });

      google.maps.event.addListener(destination, 'place_changed', function() {
        infoWindow.close();
        var marker = new google.maps.Marker({ 
        suppressMarkers: true,      
          map: map, 
          icon: base_url+'/mapmarker/flag3.png'         
        });
        var place = destination.getPlace();
        var descity='';
        for (var c = 0; c < place.address_components.length; c++) {
          if(place.address_components[c].types[0]=='locality'){
            descity = place.address_components[c].long_name;
            console.log(descity);
          }          
        }       
        if(dispatch_city!=descity){
          alert('Source and destination city must be same'); 
          document.getElementById('destination').value = ""; 
          return false;      
        }
        marker.setPosition(place.geometry.location);
        destinationLat = marker.getPosition().lat();        
        destinationLng = marker.getPosition().lng();
        document.getElementById('dest_lat').value=destinationLat;
        document.getElementById('dest_lng').value=destinationLng;
        
        infoWindow.setContent('<div><strong>' + place.name + '</strong><br>');
        //infoWindow.open(map, marker);    
        //Same event, draw route
        var start = new google.maps.LatLng(sourceLat, sourceLng);
        var end = new google.maps.LatLng(destinationLat, destinationLng);
        var request = {
          origin: start,
          destination: end,
          travelMode: google.maps.TravelMode.DRIVING
        };
        directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            dist = response.routes[0].legs[0].distance.text;
            time = response.routes[0].legs[0].duration.text;
            console.log(dist+','+time);
            document.getElementById('mapdist').innerHTML ='Route : '+dist+', '+time;
            document.getElementById('distance').value =dist;
            document.getElementById('time').value =time;            
            
            directionsDisplay.setDirections(response);
            directionsDisplay.setMap(map,marker);         
            
            // Box around the overview path of the first route
            var path = response.routes[0].overview_path;
            var boxes = routeBoxer.box(path, distance);
            var pathsTemp = [];
            for (var i = 0; i < boxes.length; i++) {
              var bounds = boxes[i];
              // Perform search over this bounds
              pathsTemp.push(bounds.getCenter());
              routeBounds.push(bounds);
            }
            var temp = {}
            cascadiaFault = new google.maps.Polyline({
              paths: pathsTemp
             });
            //alert(pathsTemp);
            //alert(cascadiaFault.getPath());
          } else {
            alert("Directions Request from " + start.toUrlValue(7) + " to " + end.toUrlValue(7) + " failed: " + status);
          }
        });
      });
    }   
    google.maps.event.addDomListener(window, "load", initialize);

    </script>




