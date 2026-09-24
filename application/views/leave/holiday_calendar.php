<link rel="stylesheet" href="<?=base_url()?>assets/vendor/fullcalendar/lib/main.css">
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card">
            <div class="body">
                <div id="calendar" style="width:100% !important;display:inline-block;"></div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url() ?>assets/vendor/fullcalendar/lib/main.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,listWeek'
        },
        initialDate: new Date().toISOString().slice(0, 10),
        editable: false,
        navLinks: true,
        dayMaxEvents: true,
        handleWindowResize: true,
        events: {
            url: base_url + 'leave/get_holidays'
        },
        windowResize: function(arg) {

        },
        eventClick: function(info) {
            var url = base_url + 'leave/popup/view_holiday/' + info.event.id;
            showAjaxModal(url, 'View Holiday');
        },
        dateClick: function(info) {
            var url = base_url + 'leave/popup/create_holiday';
            showAjaxModal(url, 'Create Holiday');
            setTimeout(function() {
                $('body input[name="holiday_date"]').val(formatDate(info.dateStr));
            }, 1000);
        }
    });

    calendar.render();

});

function formatDate(input) {
    var datePart = input.match(/\d+/g),
        year = datePart[0],
        month = datePart[1],
        day = datePart[2];
    return day + '/' + month + '/' + year;
}

function deleteHoliday(holiday_id) {
    swal({
        title: "Are you sure?",
        text: "You will not be able to recover this data!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: true
    }, function() {

        $.ajax({
            type: 'POST',
            url: base_url + 'leave/holiday_delete_process',
            cache: false,
            async: false,
            data: "id=" + holiday_id,
            dataType: "html",
            success: function(response) {
                var obj = $.parseJSON(response);
                if (obj.status == 1) {
                    toaster('success', obj.msg);
                    if ($("#calendar").length !== 0) {
                        calendar.refetchEvents();
                    }
                    $('#modal_ajax').modal('toggle');
                } else {
                    toaster('success', obj.msg);
                }
            },
            error: function(error) {
                toaster('success', error);
            }
        });
    });
}
</script>