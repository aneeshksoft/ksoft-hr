$(function () {
  "use strict";

  $.fn.exists = function () {
    return this.length > 0;
  };

  var menu = localStorage.getItem("menu");
  if (menu === "layout-fullwidth") {
    $("body").addClass("layout-fullwidth");
    $(".btn-toggle-fullwidth")
      .find(".fa")
      .toggleClass("fa-arrow-left fa-arrow-right");
  }

  $("a.scrollLink").click(function (event) {
    event.preventDefault();
    $("html, body").animate(
      { scrollTop: $($(this).attr("href")).offset().top - 140 },
      500
    );
  });

  // ///////////////////////////////////

  $("body").popover({
    html: true,
    selector: '[data-toggle="popover"]',
    trigger: "hover",
  });

  $(".uppercase").on("input", function () {
    $(this).val($(this).val().toUpperCase());
  });

  $(".aadhar").on("input", function () {
    $(this).val(
      $(this)
        .val()
        .replace(/[^\dA-Z]/g, "")
        .replace(/(.{4})/g, "$1 ")
        .trim()
    );
  });

  $(".digits").on("input", function () {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9]/g, "")
    );
  });

  $.validator.addMethod(
    "alphaspaces",
    function (value, element) {
      return this.optional(element) || /^[a-zA-Z\s]+$/i.test(value);
    },
    "Letters and space only"
  );

  $.validator.addMethod(
    "aadharverify",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[2-9]{1}[0-9]{3}\s{1}[0-9]{4}\s{1}[0-9]{4}$/i.test(value)
      );
    },
    "Should be this format: 0000 1111 2222"
  );

  $.validator.addMethod(
    "maxupload",
    function (value, element, param) {
      var length = element.files.length;
      return this.optional(element) || length <= param;
    },
    "You can only upload a maximum of 3 files"
  );

  $.validator.addMethod(
    "maxfilesize",
    function (value, element, param) {
      var length = element.files.length;
      var fileSize = 0;
      if (length > 0) {
        for (var i = 0; i < length; i++) {
          fileSize = element.files[i].size;
          fileSize = fileSize / 1024; //file size in Kb
          fileSize = fileSize / 1024; //file size in Mb
          return this.optional(element) || fileSize <= param;
        }
      } else {
        return this.optional(element) || fileSize <= param;
      }
    },
    "File size must not be more than 2 MB"
  );

  $.validator.addMethod(
    "current_ctc",
    function (value) {
      return /^\d{0,2}\.?\d{0,2}$/.test(value);
    },
    "Please enter number valid number."
  );

  $.validator.addMethod(
    "passverify",
    function (value, element) {
      return this.optional(element) || /[A-Z]{1}[0-9]{7}$/i.test(value);
    },
    "Should be this format: A1234567"
  );

  // ////////////////

  $.validator.addMethod(
    "lettersonly",
    function (value, element) {
      return this.optional(element) || /^[a-z]+$/i.test(value);
    },
    "Letters only please"
  );
  $.validator.addMethod(
    "numberonly",
    function (value, element) {
      return this.optional(element) || /^[0-9.]+$/i.test(value);
    },
    "Number only please"
  );
  $.validator.addMethod(
    "email",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[+a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value)
      );
    },
    "Email Id format is wrong"
  );
  $.validator.addMethod(
    "alphanumeric",
    function (value, element) {
      return this.optional(element) || /^[a-zA-Z0-9]+$/i.test(value);
    },
    "Letters & numbers only"
  );
  $.validator.addMethod(
    "purl",
    function (value, element) {
      return this.optional(element) || /^[a-z0-9]+(?:-[a-z0-9]+)*$/.test(value);
    },
    "Letters, hyphen and number only"
  );
  $.validator.addMethod(
    "amount",
    function (value, element) {
      return this.optional(element) || /^[+-]?([0-9]*[.])?[0-9]+$/i.test(value);
    },
    "Invalid amount"
  );
  $(document)
    .find("body .amount")
    .keypress(function (event) {
      return isNumber(event, this);
    });
  $(document)
    .find("body .number")
    .keypress(function (event) {
      return isNumberonly(event, this);
    });
  $.validator.addMethod(
    "uaephone",
    function (value, element) {
      return (
        this.optional(element) ||
        /^(?:\+971|00971|0)(?:2|3|4|6|7|9|50|51|52|55|56)[0-9]{7}$/i.test(value)
      );
    },
    "Invalid phone"
  );
  $.validator.addMethod("needsSelection", function (value, element) {
    var count = $(element).find("option:selected").length;
    return count > 0;
  });

  $.validator.addMethod('time24', function (value, element) {
    return /^\d{2}:\d{2}$/.test(value);
  }, 'Please enter a valid time in HH:MM format');
  
});

function isNumber(evt, element) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (
    (charCode != 45 || $(element).val().indexOf("-") != -1) &&
    (charCode != 46 || $(element).val().indexOf(".") != -1) &&
    (charCode < 48 || charCode > 57)
  )
    return false;
  return true;
}

function isNumberonly(evt, element) {
  var charCode = evt.which ? evt.which : event.keyCode;
  if (charCode < 48 || charCode > 57) return false;
  return true;
}
function toaster(type, message) {
  toastr.options.closeButton = true;
  toastr.options.positionClass = "toast-top-right";
  toastr[type](message);
}

function showConfirmMessage(code) {
  swal(
    {
      title: "Saved Successfully!",
      text: "Do you want to add more details?",
      type: "success",
      showCancelButton: true,
      confirmButtonColor: "#009c3b",
      confirmButtonText: "No. Go to profile",
      cancelButtonText: "Yes. Back to form",
      closeOnConfirm: false,
      closeOnCancel: false,
    },
    function (isConfirm) {
      if (isConfirm) {
        window.location.href = base_url + "profile/" + code;
      } else {
        window.location.reload();
      }
    }
  );
}

function showRegistrationMessage(code) {
  swal(
    {
      title: "Saved Successfully!",
      text: "Do you want to add more details?",
      type: "success",
      showCancelButton: true,
      confirmButtonColor: "#009c3b",
      confirmButtonText: "No. Go to profile",
      cancelButtonText: "Yes. Back to form",
      closeOnConfirm: false,
      closeOnCancel: false,
    },
    function (isConfirm) {
      if (isConfirm) {
        window.location.href = base_url + "profile/" + code;
      } else {
        window.location.href = base_url + "employee-additional/" + code;
      }
    }
  );
}

function deleteEmployee(employee_id, code) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/employee_delete_process",
        cache: false,
        async: false,
        data: "employee_id=" + employee_id + "&code=" + code,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Employee details deleted!", "success");
            $("#employee_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function deleteAssignment(reporting_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/delete_assignment_process",
        cache: false,
        async: false,
        data: "reporting_id=" + reporting_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Assignment details deleted!", "success");
            getAssignment();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function deleteLocationAssignment(assignment_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/delete_location_assignment_process",
        cache: false,
        async: false,
        data: "assignment_id=" + assignment_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Assignment details deleted!", "success");
            $("#employee_work_location").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function deleteEmployeeLetter(employee_letter_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/delete_employee_letter_process",
        cache: false,
        async: false,
        data: "employee_letter_id=" + employee_letter_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Letter deleted!", "success");
            $("#print_letters").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function getHeads(a) {
  var x = a.value || a.options[a.selectedIndex].value;
  $.ajax({
    type: "POST",
    url: base_url + "user/get_heads",
    cache: false,
    async: false,
    data: "role=" + x,
    dataType: "html",
    success: function (response) {
      var dropdown = $('select[name="reporting_head_id"]');
      dropdown.empty();
      dropdown.append('<option value="">Select head</option>');
      var obj = $.parseJSON(response);
      for (var i = 0; i < obj.length; i++) {
        dropdown.append(
          $("<option></option>")
            .attr("value", obj[i].employee_id)
            .text(obj[i].name)
        );
      }
    },
  });
}

function calcAge(a) {
  var dob = a.value;

  if (dob != "") {
    var today = new Date();
    var str = dob.split("/");
    var birthDate = new Date(str[1] + "/" + str[0] + "/" + str[2]);
    var age = today.getFullYear() - birthDate.getFullYear();
    if (age < 0) {
      age = 0;
    }
    $('input[name="age"]').val(age);
  }
}

function editDepartment(name, department_id) {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  $('input[name="name"]').val(name);
  $('input[name="department_id"]').val(department_id);
}

function deleteDepartment(department_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "admin/delete_department_process",
        cache: false,
        async: false,
        data: "department_id=" + department_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Department deleted!", "success");
            $("#department_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function editDesignation(name, designation_id) {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  $('input[name="name"]').val(name);
  $('input[name="designation_id"]').val(designation_id);
}

function deleteDesignation(designation_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "admin/delete_designation_process",
        cache: false,
        async: false,
        data: "designation_id=" + designation_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Designation deleted!", "success");
            $("#designation_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function editRole(name, head_assignment, role_id) {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  $('input[name="name"]').val(name);
  $('select[name="head_assignment"]').val(head_assignment);
  $('input[name="role_id"]').val(role_id);
}

function deleteRole(role_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "admin/delete_role_process",
        cache: false,
        async: false,
        data: "role_id=" + role_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Role deleted!", "success");
            $("#role_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function editLeaveType(title, carry_fwd_limit, leave_type_id, type,is_sandwich) {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  $('input[name="title"]').val(title);
  $('input[name="carry_fwd_limit"]').val(carry_fwd_limit);
  $('select[name="is_sandwich"]').val(is_sandwich);
  $('select[name="type"]').val(type);
  $('input[name="leave_type_id"]').val(leave_type_id);
}

function deleteLeaveType(leave_type_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "leave/delete_leave_type_process",
        cache: false,
        async: false,
        data: "leave_type_id=" + leave_type_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Leave type deleted!", "success");
            $("#leave_type_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}
function deleteLeaveApprovalHead(approval_order_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "admin/delete_leave_approval_order_process",
        cache: false,
        async: false,
        data: "approval_order_id=" + approval_order_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Role deleted!", "success");
            $("[data-approval_order_id=" + approval_order_id + "]").remove();
            setTimeout(function () {
              $(".dd").trigger("change");
            }, 2000);
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function deleteImbursementApprovalHead(imbursement_approval_order_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "admin/delete_imbursement_approval_order_process",
        cache: false,
        async: false,
        data: "imbursement_approval_order_id=" + imbursement_approval_order_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Role deleted!", "success");
            $(
              "[data-imbursement_approval_order_id=" +
                imbursement_approval_order_id +
                "]"
            ).remove();
            setTimeout(function () {
              $(".dd").trigger("change");
            }, 2000);
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function editWorkLocation(
  name,
  description,
  work_location_id,
  latitude,
  longitude
) {
  $("html, body").animate({ scrollTop: 0 }, "slow");
  $('input[name="name"]').val(name);
  $('input[name="description"]').val(description);
  $('input[name="latitude"]').val(latitude);
  $('input[name="longitude"]').val(longitude);
  $('input[name="work_location_id"]').val(work_location_id);
}
function deleteWorkLocation(work_location_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "admin/delete_work_location_process",
        cache: false,
        async: false,
        data: "work_location_id=" + work_location_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Role deleted!", "success");
            $("#work_location_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function removeDoc(type, docs_id, path, employee_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: true,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/removeDocs",
        cache: false,
        async: false,
        data:
          "type=" +
          type +
          "&docs_id=" +
          docs_id +
          "&path=" +
          path +
          "&employee_id=" +
          employee_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            toaster("success", obj.msg);
            $("#" + type + docs_id).remove();
          } else {
            toaster("error", obj.msg);
          }
        },
        error: function (error) {
          toaster("error", obj.msg);
        },
      });
    }
  );
}
function deleteAdditional(type, id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: true,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/deleteAdditional",
        cache: false,
        async: false,
        data: "type=" + type + "&id=" + id,
        dataType: "html",

        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            toaster("success", obj.msg);
            setTimeout(function () {
              window.location.reload();
            }, 2000);
          } else {
            toaster("error", obj.msg);
          }
        },
        error: function (error) {
          toaster("error", obj.msg);
        },
      });
    }
  );
}

function rejoinProcess(leave_rejoin_id) {
  swal(
    {
      title: "Are you sure?",
      text: "Want to rejoin the employee on this date?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, update it!",
      closeOnConfirm: true,
    },
    function () {
      var date = $("#lr" + leave_rejoin_id).val();

      $.ajax({
        type: "POST",
        url: base_url + "user/rejoin_process",
        cache: false,
        async: false,
        data: "leave_rejoin_id=" + leave_rejoin_id + "&date=" + date,
        dataType: "html",

        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            toaster("success", obj.msg);
            $("#rejoin").trigger("submit");
          } else {
            toaster("error", obj.msg);
          }
        },
        error: function (error) {
          toaster("error", obj.msg);
        },
      });
    }
  );
}

$('input[name="code"]').on("input", function (e) {
  $("#eligibilityWrapper").html("");
});
$('input[name="from_date"], input[name="to_date"]').on("change", function (e) {
  $("#eligibilityWrapper").html("");
});

// function checkLeaveEligibility() {
//   var code = $('input[name="code"]').val();
//   var from_date = $('input[name="from_date"]').val();
//   var to_date = $('input[name="to_date"]').val();
//   $("#eligibilityWrapper").html("");
//   if (code != "") {
//     $.ajax({
//       type: "POST",
//       url: base_url + "user/checkLeaveEligibility",
//       cache: false,
//       async: false,
//       data: "code=" + code + "&from_date=" + from_date + "&to_date=" + to_date,
//       dataType: "html",
//       success: function (response) {
//         $("#eligibilityWrapper").html(response);
//         $("input.hbtn").removeClass("d-none");
//       },
//       error: function (error) {
//         toaster("error", obj.msg);
//         $("input.hbtn").addClass("d-none");
//       },
//     });
//   } else {
//     toaster("error", "Please enter employee code!");
//   }
// }

function removeAsset(id, type) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: true,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/remove_asset",
        cache: false,
        async: false,
        data: "id=" + id + "&type=" + type,
        dataType: "html",

        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            toaster("success", obj.msg);
            if (type == "sub") {
              $("#td" + id).remove();
            } else {
              $(".td" + id).remove();
              $("#employee_assets").DataTable().ajax.reload();
            }
          } else {
            toaster("error", obj.msg);
          }
        },
        error: function (error) {
          toaster("error", obj.msg);
        },
      });
    }
  );
}

function deleteLeave(leave_application_id) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/delete_leave_process",
        cache: false,
        async: false,
        data: "leave_application_id=" + leave_application_id,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Leave details deleted!", "success");
            $("#leave_application_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

function deleteImbursement(unique_code) {
  swal(
    {
      title: "Are you sure?",
      text: "You will not be able to recover this data!",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete it!",
      closeOnConfirm: false,
    },
    function () {
      $.ajax({
        type: "POST",
        url: base_url + "user/delete_imbursement_process",
        cache: false,
        async: false,
        data: "unique_code=" + unique_code,
        dataType: "html",
        success: function (response) {
          var obj = $.parseJSON(response);
          if (obj.status == 1) {
            swal("Deleted!", "Reimbursement details deleted!", "success");
            $("#imbursement_application_list").DataTable().ajax.reload();
          } else {
            swal("Cancelled", "Something went wrong!", "error");
          }
        },
        error: function (error) {
          swal("Cancelled", "Something went wrong!", "error");
        },
      });
    }
  );
}

//to disable browser auto complete
setTimeout(function(){
  console.log('ac disabled');
  $("form, input, textarea, select").attr("autocomplete", randoms());  
},500);

function randoms() {
    let r = (Math.random() + 1).toString(36).substring(7);
    return ("random", r);
}
