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
    </style>
<div class="page-content-wrap">
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

                        <div class="col-md-3">                       
                            <div class="col-md-12" style="margin-bottom: 2%;">City</div>
                            <div class="col-md-12">
                                <select name='city_id' id="city" class="form-control city">
                                    <option value="">Please Select city</option>                                             
                                </select>   
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top: 1.3%;">                       
                            <div class="col-md-12">                              
                              <input type="button" onclick="search_driver()" name="" value="search" class="btn btn-submit">
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
                        <div id="button"><button id="trafficToggle">Toggle Traffic Layer</button>
                        <button onclick="toggleHeatmap()">Toggle Heatmap</button>
                        <button id='HeatMap'>HeatMap</button>
                        </div>
                        <div class="panel-body panel-body-table">
                        <div id="map"></div>
                        <div id="mymap"></div>
                        <div id="heatmap"></div>
                          <!-- <?php echo $map['js']; ?> -->
                          <!-- <?php echo $map['html']; ?> -->
                        </div>
                    </div>
                </div>                
            </div>                    
        </div>


<?php $this->load->view('layout/footer');?> 
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
                    $('.city').append(data.data);//alert(data);
                    //console.log(data);  
                }
                else
                {
                    $('#cityError').text('City is not found. Please select another country');
                }
                }
            });        
        }

</script>
  <script src="http://maps.google.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&libraries=visualization" type="text/javascript"></script>
  <script type="text/javascript">
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
     //heat Map  
     heatmap = new google.maps.visualization.HeatmapLayer();
     google.maps.event.addDomListener(document.getElementById('HeatMap'), 'click', HeatMap);

      trafficLayer = new google.maps.TrafficLayer();
      google.maps.event.addDomListener(document.getElementById('trafficToggle'), 'click', toggleTraffic);

    function HeatMap() {   
    if(heatmap.getMap() == null){
          $.ajax({
            type: "POST",
            url: "<?php echo site_url('Fleet/last_hour_booking');?>",                        
            dataType:'json',         
            success:function(booking_data){              
              if(booking_data.success==1){                               
                $.each(booking_data.data, function(k, v) {                                                     
                    arr.push(new google.maps.LatLng(v.lat,v.lng));                   
                })
                 heatmap = new google.maps.visualization.HeatmapLayer({
                  data: arr,
                  map: map
                });

                 /*var gradient = [
                    'rgba(0, 255, 255, 0)',
                    'rgba(0, 4, 255,1)',  
                    'rgba(0, 4, 255,2)',                  
                    'rgba(63, 0, 91, 1)',
                    'rgba(127, 0, 63, 1)',
                    'rgba(191, 0, 31, 1)',
                    'rgba(255, 0, 0, 3)'
                  ]
                  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);*/
                                                         
              }                          
            }
          });
          //trafficLayer.setMap(map);
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

    function fleetlocations()
    {      
       var locations = [
        ['Madhumillan',22.714066, 75.874868,6],        
      ];
      //console.log(locations);

      return locations;
    }
    //=========================================== Initial Map finish====================================

    function search_driver()
    {
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
                search_result(locationdata);  
              }              
          },
          error: function(){
            alert('Search result is not found');
            location.reload(true);
          }
        });
    }

    function search_result(locations){      
      document.getElementById('map').style.display="none";
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
          icon: 'http://localhost/projects/cititaxi/mapmarker/car.png'
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



    function toggleHeatmap() {
      //alert('heat');
      initMap();
      heatmap.setMap(heatmap.getMap() ? null : map);
    }

    var map, heatmap; var arr=[];
    function initMap() {
      document.getElementById('map').style.display="none";
      document.getElementById('mymap').style.display="none";
      document.getElementById('heatmap').style.display="block";

      map = new google.maps.Map(document.getElementById('heatmap'), {
        zoom: 13,
        center: {lat: 22.714066, lng: 75.874868},
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        gestureHandling: 'greedy'             
      });

      $.ajax({
            type: "POST",
            url: "<?php echo site_url('Fleet/last_hour_booking');?>",                        
            dataType:'json',         
            success:function(booking_data){              
              if(booking_data.success==1){                               
                $.each(booking_data.data, function(k, v) {                                                     
                    arr.push(new google.maps.LatLng(v.lat,v.lng));                   
                })
                 heatmap = new google.maps.visualization.HeatmapLayer({
                  data: arr,
                  map: map
                });
                                                         
              }                          
            }
          });     
    }

    //==============================HeatMap finish=======================================================      


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

    function getmarkers(){
      // return [new google.maps.LatLng(22.714195, 75.873585),(22.714076, 75.873768),(22.714848, 75.874733),(22.713403, 75.877405)];

      //return
      var heat = [new google.maps.LatLng(22.714195, 75.873585),
          new google.maps.LatLng(22.714076, 75.873768),
          new google.maps.LatLng(22.714848, 75.874733),
          new google.maps.LatLng(22.713403, 75.877405),
          new google.maps.LatLng(22.713405, 75.877455),
          ];
          //console.log(heat);
          /*var heat = [
            [22.714195,75.873585],
            [22.714076,75.873768],
            [22.714848,75.874733],
            [22.713403,75.877405],
            [22.713405,75.877455],
          ];*/
          console.log('hee'+heat);
          return heat;
          //return ["22.713969,75.874621", "22.713969,75.874621", "22.713969,75.874621", "22.713969,75.874621", "22.713969,75.874621"];
    }

  </script>




<!--script>
      function initMap() {
        var contentString = 'indore';
         var infowindow = new google.maps.InfoWindow({
            content: contentString
          });

        var indore = {lat: 22.7239575, lng: 75.7938098};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: indore,
          gestureHandling: 'greedy'
        });
        var marker = new google.maps.Marker({
          position: indore,
          map: map,         
        });

        marker.addListener('click', function() {
          infowindow.open(map, marker);
        });
      }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&callback=initMap"></script-->
