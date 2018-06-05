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
                        
                        <div class="panel-body panel-body-table">
                        <div id="map"></div>
                        <div id="mymap"></div>
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

  <script src="http://maps.google.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&sensor=false" type="text/javascript"></script>
  <script type="text/javascript">
      document.getElementById('mymap').style.display="none";
      //var locations =fleetlocations();
      var locations =[];
      //console.log(locations.length);
    /*var locations = [
      ['Bondi Beach', -33.890542, 151.274856, 4],
      ['Coogee Beach', -33.923036, 151.259052, 5],
      ['Cronulla Beach', -34.028249, 151.157507, 3],
      ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
      ['Maroubra Beach', -33.950198, 151.259302, 1]
    ];*/
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(22.7239575, 75.7938098),
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
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }

    function fleetlocations()
    {      
       var locations = [
        ['Madhumillan',22.714066, 75.874868,6],
        /*['Bondi Beach', 22.714066, 75.868868, 5],
        ['Coogee Beach', -33.923036, 151.259052, 4],
        ['Cronulla Beach', -34.028249, 151.157507, 3],
        ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
        ['Maroubra Beach', -33.950198, 151.259302, 1]*/
      ];
      //console.log(locations);

      return locations;
    }

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
            infowindow.setContent('ID : '+locations[i][4]+'\nName: '+locations[i][3]);
            infowindow.open(map, marker);
          }
        })(marker, i));
    }
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
