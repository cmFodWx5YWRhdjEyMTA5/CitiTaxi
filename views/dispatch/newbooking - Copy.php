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
        <div class="col-md-12">        
        <!-- ======================================================================= -->

        <div class="col-md-12">
            <div class="panel panel-default">
              <!-- Show loader -->                               
                <div class="sp-pre-con" style="display: none;"></div>
               <!-- Show loader -->
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>New Booking</strong></h3>              
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

        <div class="col-md-12">
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


<?php $this->load->view('dispatch/layout/footer');?> 
<script>
  /*function cities(sel)
  {   //alert(sel.value);
      $(".city option:gt(0)").remove(); 
      var countryid=sel.value;
      var countryname = sel.options[sel.selectedIndex].text;            
      $('.city').find("option:eq(0)").html("Please wait....");
      $('#search').prop('disabled', true);
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
              $('#search').prop('disabled', false);
              //console.log(data);  
          }
          else
          {
              $('#cityError').text('City is not found. Please select another country');
          }
          }
      });        
  }*/

</script>
  <script src="https://maps.google.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&libraries=visualization" type="text/javascript"></script>
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
                //console.log(locationdata);
                if(locationdata[0]!=''){
                  $(".sp-pre-con").css("display", "none"); 
                  search_result(locationdata);  
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

  </script>




