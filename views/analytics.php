<?php $data['page']='analytic'; $data['title']='Analytic Dashboard'; $this->load->view('layout/header',$data);?> 

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
                    <h3 class="panel-title"><strong>Analytic Dashboard</strong></h3>
              
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
                                    <div class="widget-title">Total Drivers</div>                                   
                                    <div class="widget-int num-count"><?php $t = getCount('users',array('user_type'=>1)); echo $t; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Active Drivers</div>                                  
                                  <div class="widget-int num-count"><?php $t = getCount('users',array('user_type'=>1,'activeStatus'=>'Active')); echo $t; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">In-Active Drivers</div>                                  
                                  <div class="widget-int num-count"><?php $t = getCount('users',array('user_type'=>1,'activeStatus!='=>'Active')); echo $t; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                            <div class="widget widget-default widget-item-icon">
                                <div class="widget-item-left">
                                    <span class="fa fa-car"></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Passengers</div>                                    
                                    <div class="widget-int num-count"><?php $tp = getCount('users',array('user_type'=>0)); echo $tp; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Active Passengers</div>                                  
                                  <div class="widget-int num-count"><?php $ap = getCount('users',array('user_type'=>0,'activeStatus'=>'Active')); echo $ap; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">In-Active Passenger</div>                                  
                                  <div class="widget-int num-count"><?php $iap = getCount('users',array('user_type'=>0,'activeStatus!='=>'Active')); echo $iap; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Bookings</div>                                  
                                  <div class="widget-int num-count">48</div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Cancelled Trip</div>                                  
                                  <div class="widget-int num-count">48</div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Completed Booking</div>                                  
                                  <div class="widget-int num-count">48</div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Pending Booking</div>                                  
                                  <div class="widget-int num-count">48</div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Driver Cancelled Booking</div>                                  
                                  <div class="widget-int num-count">48</div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-4">
                          <div class="widget widget-default widget-item-icon">
                              <div class="widget-item-left">
                                  <span class="fa fa-car"></span>
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Passenger Cancelled Booking</div>                                  
                                  <div class="widget-int num-count">48</div>
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
