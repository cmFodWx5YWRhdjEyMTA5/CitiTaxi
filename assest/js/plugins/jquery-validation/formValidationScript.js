
            var apptimeline = $("#apptimeline").validate({
                rules: {   
                        timeline:{
                            accept: "image/*"
                        },
                        copyright:{
                            required:true
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
                        city: {
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
                        insuranceCompany: {
                            required:true
                        },
                        insuranceNumber: {
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
                            required:true
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
                    required: "Please enter driving license image"
                    },
                expiredate: {
                    required: "Please enter license expire date"
                    },
                driverimage: {
                    required: "Please enter driver image"
                    },
                brand: {
                    required: "Please enter brand"
                    },
                subbrand: {
                    required: "Please enter sub brand"
                    },
                insuranceCompany: {
                    required: "Please enter insurance Company"
                    },
                insuranceNumber: {
                    required: "Please enter insurance Number"
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
                }

                });                                    

  