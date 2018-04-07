<?php $data['page']='fleet'; $data['title']='fleet tracking'; $this->load->view('layout/header',$data);?> 

<style>
       #map {
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
                                    <div class="widget-int num-count">48</div>
                                    <div class="widget-title">Total Fleet</div>                                   
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-int num-count">48</div>
                                  <div class="widget-title">Total Assigned Fleets</div>                                  
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-int num-count">48</div>
                                  <div class="widget-title">Total Unassigned Fleets</div>                                  
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                            <div class="widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-car"></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-int num-count">48</div>
                                    <div class="widget-title">Total Available Vehicles</div>                                    
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-int num-count">48</div>
                                  <div class="widget-title">Total Unavailable Vehicles</div>                                  
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-int num-count">48</div>
                                  <div class="widget-title">Total In-Active Vehicles</div>                                  
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
                            <select name='service_type' id="service_type" class="form-control" onChange="service(this)">
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
                          <div class="col-md-12"><input type="text" name="" class="form-control"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="col-md-12" style="margin-bottom: 2%;">Country</div>
                            <div class="col-md-12">
                              <select name='country_id' class="form-control select" data-live-search="true" onChange="cities(this)" required>
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
                                <select name='city_id' id="city" class="form-control city" required onChange="citiname(this)">
                                    <option value="">Select City</option>                                             
                                </select>   
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top: 1.3%;">                       
                            <div class="col-md-12">                              
                              <input type="button" name="" value="search" class="btn btn-submit">
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

</script>

  <script src="http://maps.google.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&sensor=false" type="text/javascript"></script>
<script type="text/javascript">

      var locations =fleetlocations();
    /*var locations = [
      ['Bondi Beach', -33.890542, 151.274856, 4],
      ['Coogee Beach', -33.923036, 151.259052, 5],
      ['Cronulla Beach', -34.028249, 151.157507, 3],
      ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
      ['Maroubra Beach', -33.950198, 151.259302, 1]
    ];*/
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng( 22.7239575, 75.7938098),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'greedy'
    });    

    var infowindow = new google.maps.InfoWindow();

    var marker, i;

    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: 'http://localhost/projects/cititaxi/mapmarker/car2.png'
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
        ['Bondi Beach', -33.890542, 151.274856, 4],
        ['Coogee Beach', -33.923036, 151.259052, 5],
        ['Cronulla Beach', -34.028249, 151.157507, 3],
        ['Manly Beach', -33.80010128657071, 151.28747820854187, 2],
        ['Maroubra Beach', -33.950198, 151.259302, 1]
      ];

      return locations;
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
