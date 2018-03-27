        var apptimeline = $("#fairvalidate").validate({
                rules: {
                        service_type:{
                            required:true
                        },
                        maxload:{
                            required:true,
                            number:true
                        },
                        currency:{
                            required:true,
                        },
                        commsiontype:{
                            required:true
                        },
                        commissionRate:{
                            required:true,
                            number:true
                        },
                        distanceUnit:{
                            required:true
                        },
                        perEverymin_charge:{
                            required:true,
                        },
                        afterEverymin_charge:{
                            required:true,
                            number:true
                        },
                        minbase_fair:{
                            required:true,
                            number:true
                        },
                        minDistance:{
                            required:true,
                            number:true
                        },
                        mini_distancefair:{
                            required:true,
                            number:true
                        },
                        regularChargeUponKm:{
                            required:true,
                            number:true
                        },
                        uponMinuteCharge:{
                            required:true,
                            number:true
                        },
                        unitPerMinuteforCharge:{
                            required:true,
                            number:true
                        },
                        unitPerMinutecharge:{
                            required:true,
                            number:true
                        },
                        regWaitingStartAfterMinute:{
                            required:true,
                            number:true
                        },
                        regUnitTime:{
                            required:true,
                            number:true
                        },
                        regWaitingUnitTimePrice:{
                            required:true,
                            number:true
                        },
                        morningSurchargeUnit:{
                             required:true,
                        },
                        morningSurchargePrice:{
                            required:true,
                            number:true
                        },
                        morningSurchargeTimeStart:{
                            required:true,
                        },
                        morningSurchargeTimeEnd:{
                            required:true,
                        },
                        eveningSurchargeUnit:{
                             required:true,
                        },
                        eveningSurchargePrice:{
                            required:true,
                            number:true
                        },
                        eveningSurchargeTimeStart:{
                            required:true,
                        },
                        eveningSurchargeTimeEnd:{
                            required:true,
                        },
                        minNightSurchargeUnit:{
                             required:true,
                        },
                        minNightSurchargePrice:{
                            required:true,
                            number:true
                        },
                        minNightSurchargeTimeStart:{
                            required:true,
                        },
                        minNightSurchargeTimeEnd:{
                            required:true,
                        },
                        peakChargeAfterStart:{
                            required:true,
                        },
                        peakUnitTimePriceMin:{
                            required:true,
                            number:true
                        },
                        peakUnitTimePrice:{
                            required:true,
                            number:true
                        },
                        cancelChargeUnit:{
                            required:true,                          
                        },
                        stndCancelChargeDriver:{
                            required:true,
                            number:true
                        },
                        stndCancelChargePassenger:{
                            required:true,
                            number:true
                        },
                        peakHrCancelChargeDriver:{
                            required:true,
                            number:true
                        },
                        peakHrCancelChargePassenger:{
                            required:true,
                        },
                        peakHourBookingCancelbyPassenger:{
                            required:true,
                            digit:true
                        },
                        multiStopCharge:{
                            required:true,
                            number:true
                        },
                        pickup:{
                            required:true
                        },
                        pickupLat:{
                            required:true
                        },
                        pickupLong:{
                            required:true
                        },
                        dropoff:{
                            required:true
                        },
                        dropofLong:{
                            required:true
                        },
                        fixCharge:{
                            required:true
                        },
                        dropofLat:{
                            required:true
                        },
                        dropofLat:{
                            required:true
                        },
                        startTime:{
                            required:true
                        },
                        endTime:{
                            required:true
                        },
                        freeWaitingMinute:{
                            required:true
                        },
                        waitingUnitTime:{
                            required:true
                        },
                        waitingMinUnitCharge:{
                            required:true
                        }
                    },
                    messages: {                
                        service_type:{
                            required:"Please select service type",
                        },
                        maxload:{
                            required:'Please enter maximum passenger flexibility',
                            number:'Only digit allow',
                        },
                        regWaitingStartAfterMinute:{
                            required:'Please select free waiting charge minute',
                            number:'Please select free waiting charge minute'
                        }
                    },
                    errorPlacement: function(error, element) 
                    {
                      var placement = $(element).data('error');
                      if (placement) 
                      {
                        $(placement).append(error)
                      }
                      else 
                      {
                        error.insertAfter(element);
                      }
                    }
                });

            











            var apptimeline = $("#apptimeline").validate({
                rules: {   
                        timeline:{
                            accept: "image/*"
                        },
                        copyright:{
                            required:true,
                        },
                        weeklyTargetTrip:{
                            required:true,
                        },
                        rewardRate:{
                            required:true,
                        }
                    },
                    messages: {                
                    timeline: {
                        accept: "Please select only jpg or jpeg or png image"
                        },
                        copyright:{
                            required:"Please enter copyright information"
                        }
                    },
                    errorPlacement: function(error, element) 
                    {
                      var placement = $(element).data('error');
                      if (placement) 
                      {
                        $(placement).append(error)
                      }
                      else 
                      {
                        error.insertAfter(element);
                      }
                    }
                });


            var jvalidate = $("#jvalidate").validate({
                rules: {   
                        name:{
                            required:true
                        },
                        loginemail: {
                                required: true,
                                email: true,                                
                             },
                        email: {
                                required: true,
                                email: true,
                                remote: {
                                url: site_url+"/Driver/checkEmail",
                                type: "post",
                                data: {
                                    email: function(){ return $("#email").val(); }
                                }
                            }
                        },
                        mobile :{
                            required:true,
                            number: true,
                            remote: {
                                url:site_url+"/Driver/checkMobile",
                                type: "post",
                                data: {
                                    mobile: function(){ return $("#mobile").val(); }
                                }
                            }
                        },
                        old_password:{
                            required:true,
                        },
                        password: {
                            required:true,
                            minlength: 5,
                        },
                        loginpassword: {
                            required:true,
                        },

                        confirm_password: {
                            required: true,
                            minlength: 5,
                            equalTo: "#password1"
                        },
                        dob: {
                            required:true
                        },
                        bankname: {
                            required:true
                        },
                        branchCode_Name: {
                            required:true
                        },
                        accountNo: {
                            required:true
                        },
                        nationality: {
                            required:true
                        },
                        minDistance: {
                            required:true
                        },
                        address: {
                            required:true
                        },
                        licenseimage: {
                            required:true
                        },
                        licenseno:{
                            required:true
                        },
                        expiredate: {
                            required:true
                        },
                        driverimage: {
                            required:true
                        },
                        brand: {
                            required:true
                        },
                        subbrand: {
                            required:true
                        },
                        vehicle_NoPlate:{
                            required:true
                        },
                        insuranceCompany: {
                            required:true
                        },
                        insuranceNumber: {
                            required:true
                        },
                        insuranceExpiredate:{
                            required:true
                        },
                        fleet_company: {
                            required:true
                        },
                        fleet_country: {
                            required:true
                        },
                        fleet_address: {
                            required:true
                        },                        
                        bookingLimit: {
                            required:true,
                            number:true
                        },
                        gender:{
                            required:true
                        },
                        admin_img :{
                            required:true
                        }
                    },
                messages: {                
                name: {
                    required: "Please enter a Full Name.",
                    },
                gender:{
                    required:"Please select gender"
                },
                dob: {
                    required: "Please enter Date of Birth"
                    },
                email: {
                    required: "Please enter email address",
                    remote:"Email already registered"
                    }, 
                loginemail: {
                            required: "Please enter email address",                        
                    },                   
                mobile: {
                    required: "Please enter mobile number",
                    remote:"Mobile number already registered"
                    },
                loginpassword:{
                    required: "Please enter password"
                    },

                password: {
                    required: "Please enter password"
                    },
                confirm_password:{
                    required:"Please enter confirm password",
                    equalTo:"Password and Confirm password must be same"
                },
                bankname: {
                    required: "Please enter bankname"
                    },
                bankcode: {
                    required: "Please enter bankcode"
                    },
                accountNo: {
                    required: "Please enter Account Number"
                    },               
                nationality: {
                    required: "Please enter nationality"
                    },
                city: {
                    required: "Please enter city "
                    },
                address: {
                    required: "Please enter address"
                    },
                licenseimage: {
                    required: "Please Select driving license image"
                    },
                expiredate: {
                    required: "Please enter license expire date"
                    },
                driverimage: {
                    required: "Please Select driver image"
                    },
                brand: {
                    required: "Please enter brand"
                    },
                subbrand: {
                    required: "Please enter sub brand"
                    },
                vehicle_NoPlate:{
                    required: "Please enter vehicle plate number"
                },
                insuranceCompany: {
                    required: "Please enter insurance Company"
                    },
                insuranceNumber: {
                    required: "Please enter insurance Number"
                    },
                insuranceExpiredate:{
                    required:"Please select insurance expire date"
                },
                companyName: {
                    required: "Please enter insurance company Name"
                    },
                companyCountry: {
                    required: "Please enter insurance company Country"
                    },
                companyAddress: {
                    required: "Please enter insurance company Address"
                    },
                bookingLimit: {
                    required: "Please enter booking Limit"
                    },
                },
                errorPlacement: function(error, element) 
                    {
                      var placement = $(element).data('error');
                      if (placement) 
                      {
                        $(placement).append(error)
                      }
                      else 
                      {
                        error.insertAfter(element);
                      }
                    }

                });                                    

  