$(function () {
   "use strict";

   $("#signin").validate({
      rules: {
         employee_code: {
            required: true
         },
         password: {
            required: true
         }
      },

      messages: {
         employee_code: "Please enter employee code",
         password: "Please enter password"
      },

      submitHandler: function (form, e) {
         
         e.preventDefault();
         $('.btnsmt').prop('disabled', true).html('<i class="fa fa-circle-o-notch fa-spin mr-2"></i>Processing...');
         var formData = new FormData(form);
         setTimeout(function () {
            $.ajax({
               type: 'POST',
               url: base_url + 'common/signinProcess',
               processData: false,
               contentType: false,
               cache: false,
               async: false,
               data: formData,
               success: function (result) {
                  var obj = $.parseJSON(result);
                  if (obj.status == '1') {
                     //$('.alert').html(obj.message).removeClass('d-none').addClass('alert-success');
                     setTimeout(function () {
                        window.location.href = base_url + 'dashboard';
                     }, 100);
                  } else {
                     $('.alert').html(obj.message).removeClass('d-none').addClass('alert-danger');
                     $('.btnsmt').prop('disabled', false).html('SIGN IN');
                     fadeAlert();
                  }
               },
               error: function (error) {
                  $('.alert').html('Something went wrong!').removeClass('d-none').addClass('alert-danger');
                  $('.btnsmt').prop('disabled', false).html('SIGN IN');
                  fadeAlert();
               }
            });
         }, 500);
         return false;
      }
   });



   $("#forgot").validate({
      rules: {
         email: {
            required: true,
            email: true,
         }
      },

      submitHandler: function (form, e) {
         console.log("inside forgot validation")
   
         e.preventDefault();
         $('.btnsmt').prop('disabled', true).html('Processing...');
         var formData = new FormData(form);
         setTimeout(function () {
            $.ajax({
               type: 'POST',
               url: base_url + 'common/forgotProcess',
               processData: false,
               contentType: false,
               cache: false,
               async: false,
               data: formData,
               success: function (response) {
                  var obj = $.parseJSON(response);
                  if (obj.status == '1') {
                     //   $('.alert').html(obj.message).removeClass('d-none').addClass('alert-success');
                     //   $('.btnsmt').prop('disabled', false).html('SEND OTP');
                     //   fadeAlert();
                     setTimeout(function () {
                        window.location.href = base_url + 'forgotVerify';
                     }, 2000);
                  } else {
                     $('.alert').html(obj.message).removeClass('d-none').addClass('alert-danger');
                     $('.btnsmt').prop('disabled', false).html('SEND OTP');
                     fadeAlert();
                  }
               },
               error: function (error) {
                  $('.alert').html('Something went wrong!').removeClass('d-none').addClass('alert-danger');
                  $('.btnsmt').prop('disabled', false).html('SEND OTP');
                  fadeAlert();
               }
            });
         }, 500);
         return false;
   
      }
   });
   
   $("#forgotVerify").validate({
      rules: {
         otp: {
            required: true,
         }
      },
      messages: {
         otp: "Please enter valid Otp"
      },
   
      submitHandler: function (form, e) {
   
         e.preventDefault();
         $('.btnsmt').prop('disabled', true).html('Processing...');
         var formData = new FormData(form);
         setTimeout(function () {
            $.ajax({
               type: 'POST',
               url: base_url + 'common/forgotVerifyProcess',
               processData: false,
               contentType: false,
               cache: false,
               async: false,
               data: formData,
               success: function (response) {
                  var obj = $.parseJSON(response);
                  if (obj.status == '1') {
                     //   $('.alert').html(obj.message).removeClass('d-none').addClass('alert-success');
                     //   $('.btnsmt').prop('disabled', false).html('VERIFY OTP');
                     //   fadeAlert();
                     setTimeout(function () {
                        window.location.href = base_url + 'change';
                     }, 2000);
                  } else {
                     $('.alert').html(obj.message).removeClass('d-none').addClass('alert-danger');
                     $('.btnsmt').prop('disabled', false).html('VERIFY OTP');
                     fadeAlert();
                  }
               },
               error: function (error) {
                  $('.alert').html('Something went wrong!').removeClass('d-none').addClass('alert-danger');
                  $('.btnsmt').prop('disabled', false).html('VERIFY OTP');
                  fadeAlert();
               }
            });
         }, 500);
         return false;
      }
   });
   
   $("#change").validate({
      rules: {
         new_password: {
            required: true,
         },
         confirm_password: {
            required: true,
            equalTo: "#new_password",
         },
      },
   
      messages: {
         new_password: "Please enter new password",
         confirm_password: {
            required: "Please confirm password",
            equalTo: "Please enter the same password as above"
         },
      },
   
      submitHandler: function (form, e) {
   
         e.preventDefault();
         $('.btnsmt').prop('disabled', true).html('Processing...');
         var formData = new FormData(form);
         setTimeout(function () {
            $.ajax({
               type: 'POST',
               url: base_url + 'common/changeProcess',
               processData: false,
               contentType: false,
               cache: false,
               async: false,
               data: formData,
               success: function (response) {
                  var obj = $.parseJSON(response);
                  
                  if (obj.status == '1') 
                  {
                     //   $('.alert').html(obj.message).removeClass('d-none').addClass('alert-success');
                     //   $('.btnsmt').prop('disabled', false).html('VERIFY OTP');
                     //   fadeAlert();
                     // swal({
                     //    html: true,
                     //    title: "Password Changed Successfully!",
                     //    text: "Now you can signin using your Username and new Password",
                     //    type: "success",
                     //    allowEscapeKey: false,
                     // }, function () {
                     //    window.location.href = base_url + ('signin');
                     // });
                     window.location.href = base_url + ('signin');
                     
                  } else {
                     $('.alert').html(obj.message).removeClass('d-none').addClass('alert-danger');
                     $('.btnsmt').prop('disabled', false).html('CHANGE PASSWORD');
                     fadeAlert();
                  }
               },
               error: function (error) {
                  $('.alert').html('Something went wrong!').removeClass('d-none').addClass('alert-danger');
                  $('.btnsmt').prop('disabled', false).html('CHANGE PASSWORD');
                  fadeAlert();
               }
            });
         }, 500);
         return false;
      }
   });

});

function resend_forgot_otp() {
   $.ajax({
     type: 'POST',
     url: base_url + 'common/resend_forgot_otp_process',
     cache: false,
     async: false,
     success: function(response) {
       var obj = $.parseJSON(response);
       if(obj.status==1) {
         $('.alert').html(obj.message).removeClass('d-none').addClass('alert-success');
         fadeAlert();      
         timer(5);
       } else {
         $('.alert').html(obj.message).removeClass('d-none').addClass('alert-danger');
         fadeAlert();           
       }
     },
  });
 }
 

function fadeAlert() {
   setTimeout(function () {
      $(".alert").html('').addClass('d-none').removeClass(function (index, css) {
         return (css.match(/\balert-\S+/g) || []).join(' ');
      });;
   }, 4000);
}