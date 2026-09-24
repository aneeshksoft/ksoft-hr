<div class="row">
    <!-- <div class="col-sm-12">

        <div class="card">
            <div class="card-body">
                <form action="" name="edit_employee" id="edit_employee" method="POST" enctype="multipart/form-data">
                    <? //= pr($employee); 
                    ?>

                    <div class="row">

                        <div class="col-md-3 col-sm-12">
                            <div class="form-group">
                                <label>Limit<sup>*</sup></label>
                                <select class="form-control form-control-sm select2" name="limit" id="limit">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="">All</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-1 text-right">
                            <div class="mt-4">
                                <button type="submit" name="submit" class="btn btn-primary">Import</button>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div> -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                
                <div class="col-sm-2 text-center">
                    <div class="mt-4">
                        <button id="btn-sink" class="btn btn-primary">
                            <span id="btn-text">Import</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-12">
                    <table class="table table-bordered" id="imported-employees-table" style="display: none;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamically populated rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- <script type="text/javascript">
    $(document).ready(function() {

        $("#edit_employee").validate({
            rules: {
                // name: {
                //     required: true,
                //     alphaspaces: true
                // },
            },
            // messages: {

            // },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2') && element.next('.select2-container').length) {
                    error.insertAfter(element.next('.select2-container')).addClass('d-block');
                } else {
                    element.closest('.form-group').append(error);
                }
            },
            submitHandler: function(form, e) {
                e.preventDefault();
                $(form).find(':submit').prop('disabled', true).text('Processing...')
                var form_data = new FormData(form);
                // console.log(form_data)
                setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?= base_url('import/import_post') ?>',
                        cache: false,
                        async: false,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            var obj = $.parseJSON(response);
                            if (obj.status) {
                                swal({
                                    title: "",
                                    text: message(obj),
                                    type: "success",
                                    closeOnConfirm: true,
                                    html: true
                                });

                            } else {
                                swal({
                                    title: "",
                                    text: message(obj),
                                    type: "error",
                                    closeOnConfirm: true,
                                    html: true
                                });

                            }
                            $(form).find(':submit').prop('disabled', false).text('Import');

                        },
                        error: function(error) {
                            swal("Cancelled", "Something went wrong!", "error");
                            $(form).find(':submit').prop('disabled', false).text('Import');
                        }
                    });

                }, 500);
                return false;
            }

        });

        function message(response) {
            html = "<div class='d-flex flex-column justify-content-center'>";
            html += "<div class='text-info'>Total:" + response.total + "</div>";
            html += "<div class='text-success mt-1'>Inserted:" + response.inserted + "</div>";
            html += "<div class='text-warning mt-1'>Skipped:" + response.skipped + "</div>";
            html += "<div class='text-danger mt-1'>Failed:" + response.failed + "</div>";
            html += "</div>";
            return html;
        }

    });
</script> -->

<script>
    $(document).ready(function () {
        $('#btn-sink').on('click', function (e) {
            e.preventDefault(); // Prevent default form submission if in a form

            const $button = $(this);
            const $spinner = $('#spinner');
            const $btnText = $('#btn-text');
            const $table = $('#imported-employees-table');
            const $tableBody = $table.find('tbody');

            // Disable button and show spinner
            $button.prop('disabled', true);
            $btnText.text('Processing');
            $spinner.removeClass('d-none');

            // Perform AJAX call
            $.ajax({
                url: '<?= base_url("employee/import_employees") ?>', // Replace with your endpoint
                type: 'POST',
                data: {}, // Add necessary data
                success: function (response) {
                    const obj = $.parseJSON(response);
                    if (obj.status) {
                        // Handle success and populate the table
                        $tableBody.empty(); // Clear previous data
                        if (obj.data && obj.data.length > 0) {
                            obj.data.forEach(employee => {
                                $tableBody.append(`
                                    <tr>
                                        <td>${employee.name}</td>
                                        <td>${employee.email}</td>
                                        <td>${employee.code}</td>
                                    </tr>
                                `);
                            });
                            $table.show();
                        }
                        toaster('success', obj.msg);
                    } else {
                        // Handle error response
                        toaster('error', obj.msg);
                    }
                },
                error: function (xhr, status, error) {
                    // Handle AJAX error
                    alert('An error occurred: ' + error);
                },
                complete: function () {
                    // Stop processing state
                    $button.prop('disabled', false);
                    $btnText.text('Import');
                    $spinner.addClass('d-none');
                }
            });
        });
    });
</script>