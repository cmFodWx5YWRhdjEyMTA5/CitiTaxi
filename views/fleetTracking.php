<?php $data['page']='fleet'; $data['title']='fleet tracking'; $this->load->view('layout/header',$data);?> 

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
  <div class="sp-pre-con" style="display: none;"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Fleet tracking</strong></h3>              
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated" style="min-height: 50px;">
                        <div class="panel-body panel-body-table">
                          <div class="col-md-4">
                            <div class="widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-car"></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Fleet</div>                                   
                                    <div class="widget-int num-count"><?php $t = getCount('fleets',array()); echo $t; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Assigned Driver</div>                                  
                                  <?php $where = '(booking_status=0 or booking_status=1 or booking_status=5 or booking_status=6)';?>
                                  <div class="widget-int num-count"><?php $AD = getCount('booking',$where); echo $AD; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Unassigned Driver</div>                                  
                                  <div class="widget-int num-count"><?php $UAD = getCount('users',array('online_status'=>'online','activeStatus'=>'Active')); echo $UAD; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <!--div class="col-md-4">Total Fleet</div>
                          <div class="col-md-4">Total Assign Fleet</div>
                          <div class="col-md-4">Total Unassign Fleet</div>  
                          <div class="col-md-4">Total Available Vehicles</div>
                          <div class="col-md-4">Total Unavailable Vehicles </div>
                          <div class="col-md-4">Total In-Active Vehicles</div-->                         
                        </div>
                    </div>
                </div>                
            </div>                    
        </div>
        <!-- ======================================================================= -->

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Manage Fleet</strong></h3>              
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated" style="min-height: 75px;">
                        <div class="panel-body panel-body-table">                       
                        <div class="col-md-3">
                          <div class="col-md-12" style="margin-bottom: 2%;">Vehicle type</div>
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
                          <div class="col-md-12" style="margin-bottom: 2%;">Driver id</div>
                          <div class="col-md-12"><input type="text" id='driver_id' name="driver_id" class="form-control"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-12" style="margin-bottom: 2%;">Country</div>
                            <div class="col-md-12">
                              <select name='country_id' id='country_id' class="form-control select" data-live-search="true" onChange="cities(this)" required>
                                <option value="">Select Country</option>
                                  <?php foreach(countryies() as $country) { ?>
                                    <option value="<?php print $country->id; ?>">
                                      <?php echo $country->name; ?>
                                    </option>
                                      <?php } ?> 
                              </select>
                            </div>
                        </div>

                        <div class="col-md-2">                       
                            <div class="col-md-12" style="margin-bottom: 2%;">City</div>
                            <div class="col-md-12">
                                <select name='city_id' id="city" class="form-control city">
                                    <option value="">Please Select city</option>                                             
                                </select>   
                            </div>
                        </div>
                        <div class="col-md-2" style="margin-top: 1.3%;">                       
                            <div class="col-md-6">                              
                              <input type="button" onclick="search_driver()" name="" value="search" class="btn btn-submit">
                            </div>           
                            <div class="col-md-6">                              
                              <input type="button" id='HeatMap' value="HeatMap" class="btn btn-submit">
                            </div>                                           
                        </div>
                    </div>
                </div>                
            </div>                    
        </div>

        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Fleet Tracking in map</strong></h3>              
                </div>                
                <div class="container-fluid">
                    <div class="panel-body form-group-separated" style="padding:5px !important">
                        <div id="button">
                          <button id="trafficToggle">Toggle Traffic Layer</button>                                            
                        </div>
                        <div class="panel-body panel-body-table">
                          <!--div id="map"></div-->
                          <div id="mymap"></div>
                          <div id="heatmap" style="display:none;"></div>                          
                        </div>
                    </div>
                </div>                
            </div>                    
        </div>
  <?php $this->load->view('layout/footer');?> 

  <script>
    function cities(sel){
       $(".sp-pre-con").css("display", "block");
      //alert(sel.value);
      $(".city option:gt(0)").remove(); 
      var countryid=sel.value;
      var countryname = sel.options[sel.selectedIndex].text;            
      $('.city').find("option:eq(0)").html("Please wait....");
      $('#HeatMap').prop('disabled', true);
          $.ajax({
          type: "get",
          url: "<?php echo site_url('Vehicle/cities/');?>"+countryid, 
          dataType: "json",  
          success:function(data){
          console.log(data);
          if(data!=null)
          {
              $(".sp-pre-con").css("display", "none"); 
              $('#country_name').val(countryname);
              $('.city').find("option:eq(0)").html("Please Select city");
              $('.city').append(data.data);//alert(data);
              $('#HeatMap').prop('disabled', false);
              //console.log(data);  
          }
          else
          {
            $(".sp-pre-con").css("display", "none"); 
            $('#cityError').text('City is not found. Please select another country');
          }
          }
      });        
    }
  </script>

        <!--===================================== Map functionality start ==========================================-->

  

    <script type="text/javascript">

    var map, heatmap; var arr=[];
    function initMap() {
      // document.getElementById('map').style.display="none";
      document.getElementById('mymap').style.display="none";
      document.getElementById('heatmap').style.display="block";

      map = new google.maps.Map(document.getElementById('heatmap'), {
        zoom: 13,
        center: {lat: 22.714066, lng: 75.874868},
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        gestureHandling: 'greedy'             
      });
       /*heat Map  */
      heatmap = new google.maps.visualization.HeatmapLayer();
      google.maps.event.addDomListener(document.getElementById('HeatMap'), 'click', HeatMap);

      trafficLayer = new google.maps.TrafficLayer();
      google.maps.event.addDomListener(document.getElementById('trafficToggle'), 'click', toggleTraffic);
    }


    function HeatMap() { 
      var ctry = document.getElementById('country_id');
      var country = ctry[ctry.selectedIndex].text;
      var cty = document.getElementById('city');
      var city = cty[cty.selectedIndex].text;      
      // alert(city);
        if(heatmap.getMap() == null){          
          if(country=='Select Country' || city=='Please Select city' || city=='Please Select city'){
            alert('Please select country and city');
          }
          else{ 
            $(".sp-pre-con").css("display", "block");           
            $.ajax({
            type: "POST",
            data:{'country':country,'city':city},
            url: "<?php echo site_url('api/Auth/get_heatmap_Data');?>",                        
            dataType:'json',         
            success:function(booking_data){ 
            console.log(booking_data);             
              if(booking_data.success==1){   
                $(".sp-pre-con").css("display", "none");
                initMap();                                       
                $.each(booking_data.data, function(k, v) {                                                     
                    arr.push(new google.maps.LatLng(v.lat,v.lng));                   
                })
                 heatmap = new google.maps.visualization.HeatmapLayer({data: arr,map: map});
                 /*var gradient = ['rgba(0, 255, 255, 0)']
                  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);*/
              }
              else{
                $(".sp-pre-con").css("display", "none");
                alert(booking_data.message);
                location.reload(true);
              }                          
            }
          });
          //trafficLayer.setMap(map);
        }          
      }
      else{
          heatmap.setMap(null);
        }
      //heatmap.setMap(heatmap.getMap() ? null : map);      
    }    

    function toggleTraffic(){
        if(trafficLayer.getMap() == null){
            //traffic layer is disabled.. enable it
            trafficLayer.setMap(map);
        } 
        else {
            //traffic layer is enabled.. disable it
            trafficLayer.setMap(null);             
        }
    }
    
    //=========================================== Initial Map finish====================================

    function search_driver()
    {
        $(".sp-pre-con").css("display", "block");
        var vtype = document.getElementById('service_type');
        var servicetype = vtype[vtype.selectedIndex].value;
        var driverid = document.getElementById('driver_id').value;
        var ctry = document.getElementById('country_id');
        var country = ctry[ctry.selectedIndex].text;
        var cty = document.getElementById('city');
        var city = cty[cty.selectedIndex].text;       
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Fleet/search_driver');?>", 
            data:{'servicetype':servicetype,'driverid':driverid,'country':country,'city':city}, 
            dataType:'json',         
            success:function(locationdata){
              console.log(locationdata);
              if(locationdata[0]!=''){
                $(".sp-pre-con").css("display", "none");
                search_result(locationdata);  
              }              
          },
          error: function(){
            $(".sp-pre-con").css("display", "none");
            alert('Search result is not found');
            location.reload(true);
          }
        });
    }

    function search_result(locations){      
      // document.getElementById('map').style.display="none";
      document.getElementById('mymap').style.display="block";
      var map = new google.maps.Map(document.getElementById('mymap'), {
      zoom: 13,
      center: new google.maps.LatLng(locations[0][1],locations[0][2]),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'greedy'
      });    
      var infowindow = new google.maps.InfoWindow();

      var marker, i;

      for (i = 0; i < locations.length; i++) {  
          marker     = new google.maps.Marker({
          position: new google.maps.LatLng(locations[i][1], locations[i][2]),        
          map: map,
          icon:'<?php echo base_url("mapmarker/car.png");?>'
          // icon: 'http://localhost/projects/cititaxi/mapmarker/car.png'
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

    
    </script>

  <script src="https://maps.google.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&libraries=visualization&callback=initMap" type="text/javascript"></script>
  
    <!--==============================HeatMap finish=======================================================-->      

    <script>
      function getMostlyBookingArea1(){  
        var lat; h; var ltlg; var arr1 =[];   var arr=[]; 
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('Fleet/last_hour_booking');?>",                        
            dataType:'json',         
            success:function(booking_data){              
              if(booking_data.success==1){                               
                $.each(booking_data.data, function(k, v) {                                                     
                    arr.push(new google.maps.LatLng(v.lat,v.lng));                   
                })
                console.log('he1'+arr1);                                         
              }                          
            }
          });
        //return h['ltlng'];
      }
    </script>
