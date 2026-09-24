<style>
.loader-line {
    width: 200px;
    height: 3px;
    position: relative;
    overflow: hidden;
    background-color: #ddd;
    margin-top: 20px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
}

.loader-line:before {
    content: "";
    position: absolute;
    left: -50%;
    height: 3px;
    width: 40%;
    background-color: coral;
    -webkit-animation: lineAnim 1s linear infinite;
    -moz-animation: lineAnim 1s linear infinite;
    animation: lineAnim 1s linear infinite;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
}

@keyframes lineAnim {
    0% {
        left: -40%;
    }

    50% {
        left: 20%;
        width: 80%;
    }

    100% {
        left: 100%;
        width: 100%;
    }
}
</style>
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form class="form-auth-small" action="" id="form1" method="POST" enctype="multipart/form-data">
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Upload File<sup>*</sup>(csv, xlsx, xls)</label> <br />
                                <input type="file" name="products">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input type="submit" class="btn btn-lg btn-primary btnsmt1" value="Upload" />
                            <a href="<?php echo base_url('attendance/attendance_import')?>" class="btn btn-lg btn-danger">Reset</a>
                            <a href="<?=base_url()?>assets/common/sample-attendance.csv" class="pull-right mt-2" download><strong>Download Sample</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php //if (!empty($productsData)) :?>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <form action="<?= site_url('attendance/save_products'); ?>" method="POST" enctype="multipart/form-data" id="uploadForm">

            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <table class="table table-bordered table-sm" id="ddd">
                            <thead>
                                <tr>
                                    <td style="white-space: nowrap;font-weight:bold;">Employee code</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Employee Name</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Department</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Date</td>
                                    <td style="white-space: nowrap;font-weight:bold;">In Time</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Out Time</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Shift</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Total Duration</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Status</td>
                                    <td style="white-space: nowrap;font-weight:bold;">Remarks</td>
                                </tr>
                            </thead>
                            <tbody class="edata" id="edata">
                                <!-- ajax data coming here -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-lg btn-primary btnsmt d-none" value="Import" />
                    <div class="log"></div>
                    <div class="resp"></div>
                    <div class="loader-line d-none"></div>
                </div>
            </div>

        </form>
    </div>
    <?php //endif;?>

    <script type="text/javascript">
    var jsonObj = [];
    const chunkSize = 50;
    var count = 0;
    var k = 0;

    var part = 500;
    var partcount = 0;
    var appendobj = [];
    var s = 0;
    var m = 0;

    $(function() {

        $("#form1").validate({
            rules: {

                products: {
                    required: true
                }

            },
            messages: {

            },
            submitHandler: function(form, e) {
                e.preventDefault();
                var form_data = new FormData(form);
                $('.btnsmt1').prop('disabled', true).attr('value', 'Processing...');

                setTimeout(function() {

                    $.ajax({
                        type: 'POST',
                        url: base_url + 'attendance/get_excel_data',
                        cache: false,
                        async: true,
                        data: form_data,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            appendobj = JSON.parse(response);
                            if (appendobj.data.length > 0) {
                                data_append(partcount);
                            } else {                                                                            
                                toaster('error', 'Please upload valid file!');
                                $('.btnsmt1').prop('disabled', false).attr('value', 'Upload');
                                $('.btnsmt').addClass('d-none');
                            }
                        },
                        error: function(error) {
                            toaster('error', 'Ajax Error!');
                            $('.btnsmt1').prop('disabled', false).attr('value', 'Upload');
                            $('.btnsmt').addClass('d-none');
                        }
                    });

                });
            }
        });

        function data_append(partcount) {

            var html = [];
            var chunk1 = appendobj.data.slice(partcount, partcount + part);
            if (chunk1.length > 0) {
                $.each(chunk1, function(key, value) {
                    html.push('<tr><td><input type="text" name="code[' + m + ']" id="code' + m + '" class="form-control" value="' + value.code + '" /></td><td><input type="text" name="name[' + m + ']" id="name' + m + '" class="form-control" value="' + value.name + '" /></td><td><input type="text" name="department[' + m + ']" id="department' + m + '" class="form-control" value="' + value.department + '" /></td><td><input type="text" name="attendance_date[' + m + ']" id="attendance_date' + m + '" class="form-control" value="' + value.attendance_date + '" /></td><td><input type="text" name="in_time[' + m + ']" id="in_time' + m + '" class="form-control" value="' + value.in_time + '" /></td><td><input type="text" name="out_time[' + m + ']" id="out_time' + m + '" class="form-control text-center" value="' + value.out_time + '" /></td><td><input type="text" name="shift[' + m + ']" id="shift' + m + '" class="form-control text-right" value="' + value.shift + '" /></td><td><input type="text" name="duration[' + m + ']" id="duration' + m + '" class="form-control text-right" value="' + value.duration + '" /></td><td><input type="text" name="status[' + m + ']" id="status' + m + '" class="form-control text-center" value="' + value.status + '" /></td><td><input type="text" name="remarks[' + m + ']" id="remarks' + m + '" class="form-control text-center" value="' + value.remarks + '" /></td></tr>');                
                    m++;
                });
                $('.edata').append(html.join(''));
                s += chunk1.length;
                partcount += part;
                $('.btnsmt1').prop('disabled', true).attr('value', 'Processing...' + s + ' out of ' + appendobj.data.length);
                if (s == appendobj.data.length) {
                    $('.btnsmt1').attr('value', s + ' out of ' + appendobj.data.length + ' ready to import');
                    $('.btnsmt').removeClass('d-none');
                } else {
                    setTimeout(function() {
                        data_append(partcount);
                    }, 2000);
                }

            } else {
                console.log('empty chunk. s=' + s + ' total=' + appendobj.data.length);
                $('.btnsmt1').prop('disabled', false).attr('value', 'Upload');
            }

        }

        $("#uploadForm").validate({
            rules: {

            },
            messages: {

            },
            submitHandler: function(form, e) {
                e.preventDefault();

                $('.btnsmt').prop('disabled', true).attr('value', 'Processing...');

                var j = 0;

                $("table#ddd tr").each(function(i, v) {
                    if (j < i) {
                        data = {};
                        data['code'] = $('#code' + j).val();
                        data['name'] = $('#name' + j).val();
                        data['department'] = $('#department' + j).val();
                        data['attendance_date'] = $('#attendance_date' + j).val();
                        data['in_time'] = $('#in_time' + j).val();
                        data['out_time'] = $('#out_time' + j).val();
                        data['shift'] = $('#shift' + j).val();
                        data['duration'] = $('#duration' + j).val();
                        data['status'] = $('#status' + j).val();
                        data['remarks'] = $('#remarks' + j).val();                        
                        jsonObj.push(data);
                        j++;
                    }
                });

                if (jsonObj.length > 0) {
                    ajax_call(count);
                } else {
                    toaster('success', 'Empty data!');
                    $('.btnsmt').prop('disabled', false).attr('value', 'Import');
                }

                return false;
            }
        });

    });

    function ajax_call(count) {

        //console.log('count' + count);
        //console.log(jsonObj);
        var chunk = jsonObj.slice(count, count + chunkSize);

        //console.log(chunk);
        $('.loader-line').removeClass('d-none');

        if (chunk.length > 0) {
            setTimeout(function() {

                $.ajax({
                    type: 'POST',
                    url: base_url + 'attendance/save_products',
                    cache: false,
                    async: true,
                    data: JSON.stringify(chunk),
                    success: function(response) {

                        var obj = $.parseJSON(response);
                        if (obj.status == 1) {
                            k += chunk.length;
                            $('.resp').html(k + ' out of ' + jsonObj.length + ' processed!');
                            $('.log').append('<br/>' + obj.attendance_added_count + ' - Added. | ' + obj.attendance_error_count + ' - Error. | '+ obj.no_emp_exist + ' - Employees Not Exist. | '+obj.attendance_exist+' - Attendance Already Added');

                            count += chunkSize;
                            if (k == jsonObj.length) {
                                $('.log').append('<br/>Completed!');
                                $('.loader-line').addClass('d-none');
                                $('.btnsmt').prop('disabled', true).attr('value', 'Import');
                            } else {
                                ajax_call(count);
                            }
                        } else {
                            $('.log').append('<br/>Response Error!');
                            $('.loader-line').addClass('d-none');
                            $('.btnsmt').prop('disabled', false).attr('value', 'Import');
                        }

                    },
                    error: function(error) {
                        $('.log').append('<br/>Ajax error!');
                        $('.loader-line').addClass('d-none');
                        $('.btnsmt').prop('disabled', false).attr('value', 'Import');
                    }
                });
            }, 500);
        } else {
            $('.log').append('<br/>completed!');
            $('.loader-line').addClass('d-none');
            $('.btnsmt').prop('disabled', false).attr('value', 'Import');
        }

    }
    </script>