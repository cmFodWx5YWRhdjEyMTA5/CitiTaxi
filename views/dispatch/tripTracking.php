<!DOCTYPE html>
<html>
  <head>
    <title>Track Trip</title>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />        
    <link rel="SHORTCUT ICON" href="<?php echo base_url('assest/favicon.png');?>" type="image/png" />
    <script>var site_url = '<?php echo site_url(); ?>';</script>
    <script>var base_url = '<?php echo base_url(); ?>';</script>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
        border: 1px solid black;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 90%;        
        margin: 0;
        padding: 10px;
        background:#9e9e9eab;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/jquery/jquery.min.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/jquery/jquery-ui.min.js"></script>        
      <script type="text/javascript" src="<?php echo base_url();?>assest/js/plugins/bootstrap/bootstrap.min.js"></script> 
      <script src="https://maps.google.com/maps/api/js?key=AIzaSyCAaSsKL0bekeYUkT3GyP-od5YdJzANQO0&libraries=visualization,places,geometry" type="text/javascript"></script>        

      <script>
       var interval = null;
        $('document').ready(function(){
          driverlocation();
         interval = setInterval(driverlocation, 4000);
        });

      var geocoder;
      var map;
      var directionsDisplay;
      var dmarkers;
      var directionsService = new google.maps.DirectionsService();

      var pickLat = '<?php echo $book->pickupLat; ?>';
      var pickLng = '<?php echo $book->pickupLong; ?>';
      var booking_id ='<?php echo $book->booking_id; ?>';
      var driver_id ='<?php echo $book->driver_id; ?>';
      var rendererOptions = {
          map: map,
          suppressMarkers: false,
          polylineOptions: {
            strokeColor: 'blue'
          }
        };
        directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
        directionsDisplay.setOptions({map: map,suppressMarkers: true });
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: new google.maps.LatLng(pickLat,pickLng),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            gestureHandling: 'greedy',
            mapTypeId: google.maps.MapTypeId.ROADMAP
          });
        
        // get pickup and destination latitude, longitude;
        var locations = function () {
          var markers = null;
          $.ajax({
            async: false,
            type:'POST',
            data:{'booking_id':booking_id},
            url:'<?php echo site_url('Dispatch/tripMarkers'); ?>',
            dataType:"json",            
            success:function(res){
              //console.log(res);
              markers = res;
            }
          });
          return markers;
        }(); 
        console.log(locations);
        //get driver location
        function driverlocation() {          
          var pre;
          var prelat=''; var prelng='';
          $.ajax({
            async: false,
            type:'POST',
            data:{'driver_id':driver_id,'booking_id':booking_id},
            url:'<?php echo site_url('Dispatch/driverLiveLocation'); ?>',
            dataType:"json",            
            success:function(ress){              
              removeMarker(marker,4);              
              marker     = new google.maps.Marker({
                position: new google.maps.LatLng(ress[0][1], ress[0][2]),        
                map: map,
                icon: base_url+'/mapmarker/car.png',
                id:'4'                
              });
              if(ress[0][4]==2 || ress[0][4]==3 || ress[0][4]==4 || ress[0][7]){
                clearInterval(interval);
              }

            }
          });         
        }      

        var removeMarker = function(markers, markerId) {          
            markers.setMap(null); // set markers setMap to null to remove it from map
            delete markers[markerId]; // delete marker instance from markers object
        };
       

        directionsDisplay.setMap(map);
        var infowindow = new google.maps.InfoWindow();
        var marker, i;
        var request = {
          travelMode: google.maps.TravelMode.DRIVING
        };
       var addresses = locations.length;
       var lst = addresses-1;
       marker     = new google.maps.Marker({
            position: new google.maps.LatLng(locations[lst][1], locations[lst][2]),        
            map: map,
            icon: base_url+'mapmarker/flag3.png',            
          });

       google.maps.event.addListener(marker, 'click', (function(marker,lst) {
            return function() {
              var content = '<div>'+locations[lst][0]+'</div>';
              infowindow.setContent(content);
              //infowindow.setContent('ID : '+locations[i][4]+'\n Name: '+locations[i][3]);
              infowindow.open(map, marker);
            }
          })(marker,lst));

        for (i=0; i<addresses; i++) {            
          if(locations[i][3]=='p'){
            var icon = base_url+'/mapmarker/pin3.png'; 
          }
          if(locations[i][3]=='d')
          {
            var icon = base_url+'mapmarker/flag'+i+'.png';
          }
            marker     = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),        
            map: map,
            icon: icon,            
          });


          google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
              var content = '<div>'+locations[i][0]+'</div>';
              infowindow.setContent(content);
              //infowindow.setContent('ID : '+locations[i][4]+'\n Name: '+locations[i][3]);
              infowindow.open(map, marker);
            }
          })(marker, i));
          
          if (i == 0){request.origin = marker.getPosition();}
          else if(i == locations.length - 1){ request.destination = marker.getPosition();}
          else {
            if (!request.waypoints) request.waypoints = [];
            request.waypoints.push({
              location: marker.getPosition(),
              stopover: true
            });
          }
          directionsService.route(request, function(result, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(result);
          }
          });
        }    
      </script>
      

        


      


  </body>
</html>








