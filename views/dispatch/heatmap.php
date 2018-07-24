<?php $data['page']='heatmap'; $data['title']='Heat map'; $this->load->view('dispatch/layout/header',$data);?> 

  <style>
       #map {
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
            <div class="panel-heading">
                    <h3 class="panel-title"><strong>Heat Map</strong></h3>     
                    <input type="hidden" id="country" value="<?php echo $this->country; ?>">
                    <input type="hidden" id="city" value="<?php echo $this->city; ?>">         
                </div>
                               
                <div class="container-fluid">
                    <div class="panel-body form-group-separated" style="padding:5px !important">
                      <!-- Show loader -->                               
                      <div class="sp-pre-con" style="display: none;"></div>
                     <!-- Show loader -->
                      <div id="button"><button class="btn btn-submit" id="trafficToggle">Toggle Traffic Layer</button>
                      <button class="btn btn-submit" id='HeatMap'>HeatMap</button>
                      </div>
                      <div class="panel-body panel-body-table">
                        <div id="map"></div>
                      </div>
                    </div>
                </div>                
            </div>                    
        </div>


<?php $this->load->view('dispatch/layout/footer');?> 
  <script src="http://maps.google.com/maps/api/js?key=AIzaSyCDXXQzlm8TXhlOKaxWEmxoky8JRBODFgw&libraries=visualization" type="text/javascript"></script>
  <script type="text/javascript">
      var trafficLayer; var heatmap; var arr=[];           
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
    var country = document.getElementById('country').value;
    var city = document.getElementById('city').value;   
    if(heatmap.getMap() == null){
          $(".sp-pre-con").css("display", "block");
          $.ajax({
            type: "POST",
            data:{'country':country,'city':city},
            url: "<?php echo site_url('Fleet/last_hour_booking');?>",                        
            dataType:'json',         
            success:function(booking_data){   
            $(".sp-pre-con").css("display", "none");           
              if(booking_data.success==1){                               
                $.each(booking_data.data, function(k, v) {                                                     
                    arr.push(new google.maps.LatLng(v.lat,v.lng));                   
                })
                 heatmap = new google.maps.visualization.HeatmapLayer({
                  data: arr,
                  map: map
                });                                      
              }                          
            },
            error:function(booking_data){
              alert('No booking found in last 3 hours');
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
    
    //=========================================== Initial Map finish====================================

  </script>



