<link rel=stylesheet href=<?= base_url('vendor/datatables/media/css/jquery.dataTables.css'); ?>>
<link rel=stylesheet href="https://cdn.datatables.net/buttons/1.2.3/css/buttons.dataTables.min.css">
<link rel=stylesheet href=<?= base_url('styles/app.min.df5e9cc9.css'); ?>>
<div class=panel>
   <div class="col-md-12" >
        <br>
      <!--  <a href="<?php //echo base_url('admin/createuser'); ?>" class="btn btn-success" role="button" style="float: right">Create New User</a> -->

    </div>
    <div class="panel-heading border">
        <ol class="breadcrumb mb0 no-padding">
            <li> <a href=javascript:void(0);>Delivery Location</a> </li>

        </ol>
    </div>    
    
  <!--===========================================  new map ======================================================-->
   
    
<?php   

foreach($mappoint as $row)
{
//echo '<pre>';  print_r($row);die;
if($row!='')
  {
  
$data[]="'<div>Delivery Id-".$row->order_id."<br>Type -".$row->delivery_type ."<br>Pickup -".$row->pickup_street_name."<br>Drop off-".$row->dropoff_stree_name."</div>'".','.$row->pickup_lat.','.$row->pickup_long;

if($row->vehicle_type=='Car') { $mar[]='car.png';}
if($row->vehicle_type=='Van')  { $mar[]='van.png';}
if($row->vehicle_type=='Bike')   { $mar[]='bike.png';}
if($row->vehicle_type=='Truck')   { $mar[]='truck.png';}

}
else
{
  $data[]="'<div>Driver Id-".$row->user_id."<br>Driver -".$row->firstname ."". $row->lastname ."<br>Type -".$row->vehicle_type."<br>Phone -".$row->phone."</div>'".','."21.7679".','."78.8718";  
}


}

//print_r($data);die;

foreach($data as $key)
{
  $r[]= '['.($key).']';

  /*foreach($vtype as $row){
  if($row->vehicle_type=='Car') { $mar[]='car.png';}
  if($row->vehicle_type=='Van')  { $mar[]='van.png';}
  if($row->vehicle_type=='Bike')   { $mar[]='bike.png';}
  if($row->vehicle_type=='Truck')   { $mar[]='truck.png';}
  }*/
}

//print_r($r);die;
//print_r($vtype);die;
$count=count($mar);
//echo $count;die;
?>
<!DOCTYPE html>
<html> 
<head> 
  <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
  <title>Google Maps Multiple Markers</title> 
  <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyABD_-9SQzs8Djf8nOUhvy4fVMBE5LksNI" type="text/javascript"></script>
</head> 
<body>
  <div id="map" style="width:100%; height:400px;"></div>  
  <script>     
    var locations = [     
     <?php  for($i=1; $i<$count; $i++)
      {
        print_r($r[$i]);
        echo ",";
      }
        print_r($r[0]);

      ?>
    ];

    // Define your locations: HTML content for the info window, latitude, longitude   
    
    // Setup the different icons and shadows
    var iconURLPrefix = 'http://freebizoffer.com/apptech/pick&drop/courierapp/';
    
    var icons = [     
      <?php  for($i=1; $i<$count; $i++)
      { ?>
      iconURLPrefix + '<?php print_r($mar[$i]); ?>',      
     <?php  }?>     
      iconURLPrefix + '<?php print_r($mar[0]);?>'   
    ]
    var iconsLength = icons.length;

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(22.719569,75.857726),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: false,
      streetViewControl: false,
      panControl: false,
      zoomControlOptions: {
         position: google.maps.ControlPosition.LEFT_BOTTOM
      }
    });

    var infowindow = new google.maps.InfoWindow({
      maxWidth: 160
    });

    var markers = new Array();
    
    var iconCounter = 0;
    
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: icons[iconCounter]
      });

      markers.push(marker);

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
      
      iconCounter++;
      // We only have a limited number of possible icon colors, so we may have to restart the counter
      if(iconCounter >= iconsLength) {
       iconCounter = 0;
      }
    }

    function autoCenter() {
      //  Create a new viewpoint bound
      var bounds = new google.maps.LatLngBounds();
      //  Go through each...
      for (var i = 0; i < markers.length; i++) {  
    bounds.extend(markers[i].position);
      }
      //  Fit these bounds to the map
      map.fitBounds(bounds);
    }
    autoCenter();
  </script> 
</body>
</html>
    
    
    <!--********************end ******************************************-->
    

	
</div>


<script src=<?= base_url('scripts/app.min.4fc8dd6e.js'); ?>></script>   
<script src=<?= base_url('vendor/datatables/media/js/jquery.dataTables.js'); ?>></script>     
<script src=<?= base_url('scripts/extentions/bootstrap-datatables.8df42543.js'); ?>></script> 
<script src=<?= base_url('scripts/pages/table-edit.adb541fe.js'); ?>></script> 
<script src="https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.3/js/dataTables.buttons.min.js"></script>

 <script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/tableexport/tableExport.js"></script>
	<script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/tableexport/jquery.base64.js"></script>
	<script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/tableexport/html2canvas.js"></script>
	<script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/tableexport/jspdf/libs/sprintf.js"></script>
	<script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/tableexport/jspdf/jspdf.js"></script>
	<script type="text/javascript" src="http://freebizoffer.com/apptech/pick&drop/courierapp/assest/js/plugins/tableexport/jspdf/libs/base64.js"></script>  

<script>
$('#new').hide();

$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>