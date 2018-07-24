      //===================================== Map functionality start ==========================================-->

  

    //<script type="text/javascript">
        
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
            $.ajax({
            type: "POST",
            data:{'country':country,'city':city},
            url: "<?php echo site_url('Fleet/last_hour_booking');?>",                        
            dataType:'json',         
            success:function(booking_data){ 
            console.log(booking_data);             
              if(booking_data.success==1){   
                initMap();                                       
                $.each(booking_data.data, function(k, v) {                                                     
                    arr.push(new google.maps.LatLng(v.lat,v.lng));                   
                })
                 heatmap = new google.maps.visualization.HeatmapLayer({data: arr,map: map});
                 /*var gradient = ['rgba(0, 255, 255, 0)']
                  heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);*/
              }
              else{
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
      // document.getElementById('map').style.display="none";
      document.getElementById('mymap').style.display="block";
      var map = new google.maps.Map(document.getElementById('mymap'), {
      zoom: 13,
      center: new google.maps.LatLng(locations[0][1],locations[0][2]),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      gestureHandling: 'greedy'
      });    
      
      //heat Map  
      heatmap = new google.maps.visualization.HeatmapLayer();
      google.maps.event.addDomListener(document.getElementById('HeatMap'), 'click', HeatMap);

      trafficLayer = new google.maps.TrafficLayer();
      google.maps.event.addDomListener(document.getElementById('trafficToggle'), 'click', toggleTraffic);   

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

    
    //</script>

  
  
    //<!--==============================HeatMap finish=======================================================-->      

    //<script>
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
    //</script>
