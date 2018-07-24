<?php $data['page']='one'; $data['title']='Dashboard'; $this->load->view('layout/header',$data);?> 

<style>
       #map {
        /*height: 500px;*/
        width: 100%;
       }
       .widget{
        min-height: 85px !important;
       }
       .widget-item-left{
        padding: 5% 0px !important;
       }
       .widget-title, .num-count{
        color:white !important;
       }
    </style>
<div class="page-content-wrap">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><strong>Dashboard</strong></h3>              
                </div>
                <div class="container-fluid">
                    <div class="panel-body form-group-separated" style="min-height: 50px;">
                        <div class="panel-body panel-body-table">
                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:red !important;">
                                <div class="widget-item-left">
                                <span><img src="<?php echo base_url('assest/images/faicon/revenue1.png'); ?>" width='70%'></span>
                                    
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Revenue</div>                
                                          <!-- total booking fare -->
                                      <?php 
                                        $total_fare = getSum('booking','total_fare',array('booking_status'=>4));
                                        $total_cancel_charge = getSum('booking','cancel_charge',array()); 
                                        $total_revenue = $total_fare+$total_cancel_charge; 
                                        $total_revenue = round($total_revenue,2);
                                      ?>  
                                    <div class="widget-int num-count">
                                       <?php echo $total_revenue; ?>
                                    </div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(255,62,158); !important;">
                              <div class="widget-item-left">
                                <span class="fa fa-gift" style="font-size:40px !important;"></span>
                                  <!-- <span><img src="<?php echo base_url('assest/images/faicon/active_driver.png'); ?>" width='50%'></span> -->
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Driver Reward Returned Amount</div> 
                                    <div class="widget-int num-count"><?php $driver_return = getSum('driverweeklyreward_history','commission_return',array()); echo $driver_return; ?>                                      
                                    </div>
                                  </div>                                
                              </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(97,173,145) !important;">
                              <div class="widget-item-left">                              
                                  <span><img src="<?php echo base_url('assest/images/faicon/discount.png'); ?>" width='70%'></span> 
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Discounts</div>     
                                   <!-- Promo Discount+Referral Discount -->
                                   <?php
                                      $promo_discount = getSum('booking','promo_earn',array());
                                      $user_referral = getSum('user_referral_bonus','user_bonus',array('status'=>1));
                                      $referral_bonus = getSum('user_referral_bonus','referral_bonus',array('status'=>1));
                                      $total_referral_discount = $user_referral+$referral_bonus;
                                      $total_discount = $total_referral_discount+$promo_discount;
                                    ?>  
                                  <div class="widget-int num-count"><?php echo $total_discount; ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(163,73,164)  !important;">
                                <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/commission.png'); ?>" width='70%'></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Comission</div>
                                    <!-- Total Company commission -driver_return(due to weekly reward) -->
                                    <?php 
                                    $company_commission = getSum('booking_earning','total_commission',array());
                                    $total_commission   = $company_commission-$driver_return;  ?>                                       
                                    <div class="widget-int num-count"><?php echo $total_commission; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(145,186,20) !important;">
                                <div class="widget-item-left">
                                <span><img src="<?php echo base_url('assest/images/faicon/total_profit.png'); ?>" width='70%'></span>
                                    
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total profit</div>                                   
                                    <div class="widget-int num-count">--</div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(255,201,14) !important;">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/paytodriver.png'); ?>" width='70%'></span>                                    
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Payment to Drivers</div>                                  
                                  <div class="widget-int num-count">--</div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(185,122,87) !important;">
                              <div class="widget-item-left">
                                   <span><img src="<?php echo base_url('assest/images/faicon/cash_payment.png'); ?>" width='70%'></span> 
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Cash Payment</div>                                  
                                  <div class="widget-int num-count"><?php $cash_payment = getSum('booking','total_fare',array('payment_type'=>'Cash','booking_status'=>4)); echo round($cash_payment,2); ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(128,128,255) !important;">
                                <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/wallet.png'); ?>" width='50%'></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Wallet Payment</div>                                    
                                    <div class="widget-int num-count">
                                    <?php 
                                      $citi_payment = getSum('booking','total_fare',array('payment_type'=>'citipay','booking_status'=>4));
                                      $citi_payment = round($citi_payment,2);
                                      echo $citi_payment; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(255,128,64) !important;">
                                <div class="widget-item-left">
                                <span class="fa fa-credit-card"></span>
                                <!-- <span><img src="<?php echo base_url('assest/images/faicon/driver.png'); ?>" width='50%'></span> -->
                                    
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Card Payment</div>                                   
                                    <div class="widget-int num-count">
                                    <?php $card_payment = getSum('booking','total_fare',array('payment_type'=>'card','booking_status'=>4));
                                          $card_payment = round($card_payment); echo $card_payment; ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(192,67,14) !important;">
                              <div class="widget-item-left">
                                <span class="fa fa-google-wallet"></span>
                                  <!-- <span><img src="<?php echo base_url('assest/images/faicon/active_driver.png'); ?>" width='50%'></span>                                     -->
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Driver Recharged</div>                                  
                                  <div class="widget-int num-count">--</div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(0,179,45) !important;">
                              <div class="widget-item-left">
                                <span class="fa fa-google-wallet"></span>
                                <!-- <span><img src="<?php echo base_url('assest/images/faicon/inactive_driver.png'); ?>" width='50%'></span>  -->
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Passenger Recharged</div>                                  
                                  <div class="widget-int num-count">--</div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(217,0,108) !important;">
                                <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/redeem.ico'); ?>" width='50%'></span>
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total redeemed promo code</div>                                    
                                    <div class="widget-int num-count">--</div>
                                </div>                                
                            </div>
                          </div>                          
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(153,136,210) !important;">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/active_driver.png'); ?>" width='50%'></span>                                    
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Trip</div>                                  
                                  <div class="widget-int num-count"><?php $total_booking = getCount('booking',array()); echo $total_booking; ?></div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(255,174,201) !important;">
                              <div class="widget-item-left">
                                <span class="fa fa-bar-chart" style="font-size:40px !important;"></span>
                                   <!-- <span><img src="<?php echo base_url('assest/images/faicon/inactive_driver.png'); ?>" width='50%'></span>  -->
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Average Revenue Per Trip</div>  
                                  <?php 
                                      $total_completeTrip = getCount('booking',array('booking_status'=>4)); 
                                      $average_revenue_perTrip = $total_revenue/$total_completeTrip;
                                  ?>                                
                                  <div class="widget-int num-count"><?php echo round($average_revenue_perTrip,2);  ?></div>
                              </div>                                
                          </div>
                          </div>

                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(47,174,223) !important;">
                                <div class="widget-item-left">
                                  <span class="fa fa-bar-chart" style="font-size:40px !important;"></span>
                                  <!-- <span><img src="<?php echo base_url('assest/images/faicon/passenger.png'); ?>" width='50%'></span> -->
                                </div>                             
                                <div class="widget-data">
                                  <?php 
                                    $total_booking_drivers = getCountGroup_by('booking','driver_id',array());
                                    $per_driver_revenue = $total_revenue/$total_booking_drivers;
                                  ?>                                
                                  <div class="widget-title">Average Revenue Per driver</div>                                    
                                  <div class="widget-int num-count">
                                    <?php echo round($per_driver_revenue,2); ?>
                                  </div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(64,128,128) !important;">
                                <div class="widget-item-left">
                                  <span class="fa fa-bar-chart" style="font-size:40px !important;"></span>
                                  <!-- <span><img src="<?php echo base_url('assest/images/faicon/driver.png'); ?>" width='50%'></span> -->
                                </div>                             
                                <div class="widget-data">
                                  <?php 
                                    $total_booking_passenger = getCountGroup_by('booking','customer_id',array());
                                    $per_passenger_revenue = $total_revenue/$total_booking_passenger;
                                  ?>
                                    <div class="widget-title">Average Revenue Per Passenger</div>                                   
                                    <div class="widget-int num-count"><?php echo round($per_passenger_revenue,2); ?></div>
                                </div>                                
                            </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(0,128,255) !important;">
                              <div class="widget-item-left">
                                <span class="fa fa-asl-interpreting" style="font-size:40px !important;"></span>
                                  <!-- <span><img src="<?php echo base_url('assest/images/faicon/active_driver.png'); ?>" width='50%'></span>                                     -->
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Products Code Used</div>                                  
                                  <div class="widget-int num-count">--</div>
                              </div>                                
                          </div>
                          </div>
                          <div class="col-md-3">
                          <div class="widget widget-default widget-item-icon" style="background-color:rgb(0,128,128) !important;">
                              <div class="widget-item-left">
                                  <span><img src="<?php echo base_url('assest/images/faicon/active_driver.png'); ?>" width='50%'></span>                                    
                              </div>                             
                              <div class="widget-data">
                                  <div class="widget-title">Total Complete Trip</div>                                  
                                  <div class="widget-int num-count"><?php $t = getCount('booking',array('booking_status'=>4)); echo $t; ?></div>
                              </div>                                
                          </div>
                          </div>                          

                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(192,192,192) !important;">
                                <div class="widget-item-left">
                                  <span class="fa fa-credit-card"></span>
                                  <!-- <span><img src="<?php echo base_url('assest/images/faicon/passenger.png'); ?>" width='50%'></span> -->
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Wallet Account User</div>                                    
                                    <div class="widget-int num-count"><?php $total_wallet_manager = getCount('wallet_manager',array()); echo $total_wallet_manager; ?></div>
                                </div>                                
                            </div>
                          </div>

                          <div class="col-md-3">
                            <div class="widget widget-default widget-item-icon" style="background-color:rgb(80,226,179) !important;">
                                <div class="widget-item-left">
                                <span class="fa fa-dollar"></span>
                                <!-- <span><img src="<?php echo base_url('assest/images/faicon/driver.png'); ?>" width='50%'></span> -->
                                    
                                </div>                             
                                <div class="widget-data">
                                    <div class="widget-title">Total Wallet User Credit Amount</div>                                   
                                    <div class="widget-int num-count">--</div>
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
