<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('check_user_login')) {
    function check_user_login()
    {
        $ci = get_instance();

        if ($ci->session->userdata('is_user_login') != true) {

            $array_items = array('employee_id', 'code', 'designation_id', 'department_id', 'is_user_login');
            $ci->session->unset_userdata($array_items);
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('check_login')) {
    function check_login($type = '')
    {
        $ci = get_instance();
        if ($ci->session->userdata('is_user_login') != true) {
            $ci->session->sess_destroy();
            return false;
        }
        if (!empty($type)) {
            if ($ci->session->userdata('type') != $type) {
                return false;
            }
        }
        return true;
    }
}

if (!function_exists('get_date')) {
    function get_date($original_date = "")
    {

        if ($original_date == "" || empty($original_date) || $original_date == '0000-00-00 00:00:00' || $original_date == '0000-00-00') {
            $new_date = null;
        } else {
            $timestamp = strtotime($original_date);
            $new_date = date("d-m-Y", $timestamp);
        }
        return $new_date;
    }
}
if (!function_exists('set_date')) {
    function set_date($original_date = "")
    {

        if ($original_date == "" || empty($original_date)) {
            $new_date = null;
        } else {
            $original_date = str_replace('/', '-', $original_date);
            $timestamp = strtotime($original_date);
            $new_date = date("Y-m-d", $timestamp);
        }
        return $new_date;
    }
}

if (!function_exists('time_ago')) {
    function time_ago($ptime)
    {

        //convert the utc to local and then time ago apply
        $utctolocal = utcdate_to_localdate($ptime);
        $ptime = strtotime($utctolocal);

        $etime = time() - $ptime;
        if ($etime < 1) {
            return 'less than 1 second ago';
        }

        $count = array(
            365 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        $count_plural = array(
            'year' => 'years',
            'month' => 'months',
            'day' => 'days',
            'hour' => 'hours',
            'minute' => 'minutes',
            'second' => 'seconds'
        );
        foreach ($count as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $count_plural[$str] : $str) . ' ago';
            }
        }
    }
}

if (!function_exists('utcdate_to_localdate')) {
    //convert UTC from database to local time
    function utcdate_to_localdate($gmdate)
    {
        /* $gmdate must be in YYYY-mm-dd H:i:s format*/
        $timezone = date_default_timezone_get();
        //echo $timezone.'<br>'.date("Y-m-d H:i:s").'<br>';
        $userTimezone = new DateTimeZone($timezone);
        $gmtTimezone = new DateTimeZone('UTC');
        $myDateTime = new DateTime($gmdate, $gmtTimezone);
        $offset = $userTimezone->getOffset($myDateTime);
        return date("Y-m-d H:i:s", strtotime($gmdate) + $offset);
    }
}

if (!function_exists('saveNotification')) {
    function saveNotification($notify)
    {
        $ci = get_instance();
        $notify['details'] = json_encode($notify['details']);
        $notify['read_status'] = '0';
        $notify['created_at'] = gmdate('Y-m-d H:i:s');
        @$ci->db->insert('tbl_notification', $notify);
    }
}

if (!function_exists('delete_directory')) {
    function delete_directory($dirname)
    {
        if (is_dir($dirname)) {
            $dir_handle = opendir($dirname);
        }
        if (!$dir_handle) {
            return false;
        }
        while ($file = readdir($dir_handle)) {
            if ($file != "." && $file != "..") {
                if (!is_dir($dirname . "/" . $file)) {
                    unlink($dirname . "/" . $file);
                } else {
                    delete_directory($dirname . '/' . $file);
                }
            }
        }
        closedir($dir_handle);
        rmdir($dirname);
        return true;
    }
}

if (!function_exists('getUserImage')) {
    function getUserImage($image_path = '')
    {
        if (!empty($image_path)) {
            $path = 'assets/uploads/user_pic/' . $image_path;
            if (file_exists($path)) {
                return base_url() . $path;
            } else {
                return base_url() . 'assets/common/default.png';
            }
        } else {
            return base_url() . 'assets/common/default.png';
        }
    }
}

if (!function_exists('employee_status_n')) {
    function employee_status_n($name = "")
    {

        $img = array('active' => 'Active', 'inactive' => 'Inactive', 'leave' => 'Leave', 'resigned' => 'Resigned', 'noticeperiod' => 'Notice Period');
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}

if (!function_exists('airticket_status')) {
    function airticket_status($name = "")
    {

        $img = array('byperson' => 'By Person', 'bycompany' => 'By Company', 'notapplicable' => '-');
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}

if (!function_exists('employee_status_c')) {
    function employee_status_c($name = "")
    {

        $img = array('active' => '<span class="badge badge-success">Active</span>', 'inactive' => '<span class="badge badge-danger">Inactive</span>', 'leave' => '<span class="badge badge-info">Leave</span>', 'resigned' => '<span class="badge badge-default">Resigned</span>', 'noticeperiod' => '<span class="badge badge-warning">Notice Period</span>');
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}
if (!function_exists('leave_status_c')) {
    function leave_status_c($name = "")
    {

        $img = array('approved' => '<span class="badge badge-success">Approved</span>', 'pending' => '<span class="badge badge-warning">Pending</span>', 'rejected' => '<span class="badge badge-danger">Rejected</span>');
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}

if (!function_exists('rejoin_status')) {
    function rejoin_status($name = "")
    {

        $img = array('yes' => '<span class="badge badge-success">YES</span>', 'no' => '<span class="badge badge-danger">NO</span>');
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}

if (!function_exists('get_countries')) {

    function get_countries()
    {

        $countries = array(
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );

        return $countries;
    }
}

if (!function_exists('image_resize')) {
    function image_resize($image_path = "", $width = "", $height = "", $ratio = "")
    {

        $CI = get_instance();
        if (file_exists($image_path)) {

            $CI->load->library('image_lib');
            $config['image_library']    = 'gd2';
            $config['source_image']     = $image_path;
            $config['new_image']        = $image_path;
            $config['maintain_ratio']   = $ratio;
            $config['width']            = $width;
            $config['height']           = $height;
            $config['master_dim']       = 'auto';
            $CI->image_lib->initialize($config);
            $CI->image_lib->resize();
            $CI->image_lib->clear();
        }
    }
}
if (!function_exists('slugify')) {
    function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        $ci = get_instance();
        $ci->db->like('slug', $text, 'after');
        $ci->db->from('roles');
        $tot = $ci->db->count_all_results();

        if (empty($text)) {
            return 'role' . time() . rand(0, 1000);
        }

        return ($tot > 0) ? ($text . '-' . $tot) : $text;
    }
}

if (!function_exists('check_permission')) {
    function check_permission($module_id = "", $permission = "")
    {
        $ci = &get_instance();
        $roles = $ci->session->userdata('type');
        if ($roles != "") {
            $role_array = explode(",", $roles);
            if (in_array('1', $role_array)) {
                //check super admin
                return true;
            } elseif ($permission != "" && $module_id != "") {
                $qry = "select module_permission_id from module_permission where module_id = ? and role_id in (" . $roles . ") and '1' in (" . $permission . ")";
                $res = $ci->db->query($qry, $module_id)->result_array();
                if (!empty($res)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('check_role_permission')) {
    function check_role_permission($role)
    {

        $ci = &get_instance();
        $roles = $ci->session->userdata('type');
        if ($roles != "") {
            $role_array = explode(",", $roles);
            if (in_array($role, $role_array)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

if (!function_exists('AmountInWords')) {
    function AmountInWords($num)
    {

        $ones = array(
            0 => "ZERO",
            1 => "ONE",
            2 => "TWO",
            3 => "THREE",
            4 => "FOUR",
            5 => "FIVE",
            6 => "SIX",
            7 => "SEVEN",
            8 => "EIGHT",
            9 => "NINE",
            10 => "TEN",
            11 => "ELEVEN",
            12 => "TWELVE",
            13 => "THIRTEEN",
            14 => "FOURTEEN",
            15 => "FIFTEEN",
            16 => "SIXTEEN",
            17 => "SEVENTEEN",
            18 => "EIGHTEEN",
            19 => "NINETEEN",
            "014" => "FOURTEEN"
        );
        $tens = array(
            0 => "ZERO",
            1 => "TEN",
            2 => "TWENTY",
            3 => "THIRTY",
            4 => "FORTY",
            5 => "FIFTY",
            6 => "SIXTY",
            7 => "SEVENTY",
            8 => "EIGHTY",
            9 => "NINETY"
        );
        $hundreds = array(
            "HUNDRED",
            "THOUSAND",
            "MILLION",
            "BILLION",
            "TRILLION",
            "QUARDRILLION"
        ); /*limit t quadrillion */
        $num = number_format($num, 2, ".", ",");
        $num_arr = explode(".", $num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",", $wholenum));
        krsort($whole_arr, 1);
        $rettxt = "";
        foreach ($whole_arr as $key => $i) {

            while (substr($i, 0, 1) == "0") {
                $i = substr($i, 1, 5);
            }
            if ($i < 20) {
                /* echo "getting:".$i; */
                $rettxt .= $ones[$i];
            } elseif ($i < 100) {
                if (substr($i, 0, 1) != "0") {
                    $rettxt .= $tens[substr($i, 0, 1)];
                }
                if (substr($i, 1, 1) != "0") {
                    $rettxt .= " " . $ones[substr($i, 1, 1)];
                }
            } else {
                if (substr($i, 0, 1) != "0") {
                    $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                }
                if (substr($i, 1, 1) != "0") {
                    $rettxt .= " " . $tens[substr($i, 1, 1)];
                }
                if (substr($i, 2, 1) != "0") {
                    $rettxt .= " " . $ones[substr($i, 2, 1)];
                }
            }
            if ($key > 0) {
                $rettxt .= " " . $hundreds[$key] . " ";
            }
        }
        if ($decnum > 0) {
            $rettxt .= " and ";
            if ($decnum < 20) {
                $rettxt .= $ones[$decnum];
            } elseif ($decnum < 100) {
                $rettxt .= $tens[substr($decnum, 0, 1)];
                $rettxt .= " " . $ones[substr($decnum, 1, 1)];
            }
        }
        return $rettxt;
    }
}

if (!function_exists('day_diff')) {
    function day_diff($from_date, $to_date)
    {
        $totdays = 0;
        $datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($from_date . ' 00:00:00')));
        $datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($to_date . ' 23:59:59')));
        $difference = $datetime2->diff($datetime1);
        $totdays    = ($difference->days + 1);
        return $totdays;
    }
}

///////////////////////Sudheesh  Start//////////////////////

if (!function_exists('resignation_status_format')) {
    function resignation_status_format($name = "")
    {
        //$ci = get_instance();

        $img = array(
            '1' => '<span class="badge badge-warning">Resignation Submitted</span>',
            '2' => '<span class="badge badge-success">Approved by Reporting Manager</span>',
            '3' => '<span class="badge badge-warning">Exit Clearance Triggered</span>',
            '4' => '<span class="badge badge-warning">On Job Clearance Completed</span>',
            '5' => '<span class="badge badge-warning">Asset Details Completed by IT</span>',
            '6' => '<span class="badge badge-warning">IT Clearance Completed by RM</span>',
            '7' => '<span class="badge badge-warning">Validation Completed by IT</span>',
            '8' => '<span class="badge badge-warning">Admin Clearance Completed</span>',
            '9' => '<span class="badge badge-warning">HOD Clearance Completed</span>',
            '10' => '<span class="badge badge-warning">HCM Clearance Completed</span>',
            '11' => '<span class="badge badge-warning">Exit Clearance Approved</span>',
            '12' => '<span class="badge badge-warning">Exit Clearance Completed</span>',
            '13' => '<span class="badge badge-warning">Exit Interview Completed</span>',
            '14' => '<span class="badge badge-success">Resigned</span>',
            '15' => '<span class="badge badge-success">Process Closed</span>',
            '16' => '<span class="badge badge-danger">Resignation Revoked</span>',
            '17' => '<span class="badge badge-warning">Exit Interview Triggered</span>',
            '18' => '<span class="badge badge-warning">Exit Interview Submitted</span>'
        );
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}
if (!function_exists('absconding_resignation_status_format')) {
    function absconding_resignation_status_format($name = "")
    {

        $img = array(
            '1' => '<span class="badge badge-warning">Submitted</span>',
            '2' => '<span class="badge badge-success">Approved by HR</span>',
            '3' => '<span class="badge badge-success">Absconding Letter Sent</span>',
            '4' => '<span class="badge badge-success">Resignation Process Started</span>',
            '5' => '<span class="badge badge-danger">Revoked</span>',
        );
        if ($name != "") {
            return @$img[$name];
        } else {
            return false;
        }
    }
}
if (!function_exists('approval_status_format')) {
    function approval_status_format($value)
    {
        $img = array('0' => '<span class="badge badge-warning">Pending</span>', '1' => '<span class="badge badge-success">Approved</span>', '2' => '<span class="badge badge-danger">Rejected</span>', '3' => '<span class="badge badge-info">Draft</span>');
        if ($value != "") {
            return @$img[$value];
        } else {
            return false;
        }
    }
}
function cleanFileName($fileName)
{
    $fileName = preg_replace("/[^a-zA-Z0-9_.-]/", "_", $fileName);
    $fileName = preg_replace("/_+/", "_", $fileName);
    $fileName = trim($fileName, "_");

    $timestamp = microtime(true);
    $milliseconds = round($timestamp * 1000);

    if (empty($fileName)) {
        $fileName = date('dmYhis') . '_' . uniqid();
    } else {
        $fileName = $fileName . '_' . $milliseconds;
    }

    return $fileName;
}

///////////////////////Sudheesh End//////////////////////
//////////////  Aneesh Start //////////////

if (!function_exists('addDaysToDate')) {
    function addDaysToDate($givenDate, $daysToAdd)
    {
        // $givenDateObj = new DateTime($givenDate);
        // $givenDateObj->add(new DateInterval('P' . $daysToAdd . 'D'));
        // return $givenDateObj->format('Y-m-d');

        // $date = strtotime($givenDate);
        $date = strtotime($givenDate);
        $days = $daysToAdd ?? 0;
        $date = strtotime("+" . $days . " day", $date);
        $date = date('Y-m-d', $date);
        return $date;
    }
}

if (!function_exists('probation_status')) {
    function probation_status($name)
    {
        $img = array(
            '0' => '<span class="badge badge-success">Approved</span>',
            '1' => '<span class="badge badge-danger">Not Approved</span>'
        );

        return @$img[$name];
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 6)
    {
        // Define the characters that can be used in the random string
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        // Use str_shuffle to shuffle the characters and substr to get the desired length
        $randomString = substr(str_shuffle($characters), 0, $length);

        return $randomString;
    }
}

if (!function_exists('ageCalculator')) {
    function ageCalculator($dob)
    {
        // dob Format:yyyy-mm-dd'
        if (!empty($dob)) {
            $birthdate = new DateTime($dob);
            $today   = new DateTime('today');
            $age = $birthdate->diff($today)->y;
            return $age;
        } else {
            return 0;
        }
    }
}

if (!function_exists('display_images')) {
    function display_images($files, $employee_id)
    {
        if ($files) {
            $files = explode(',', $files);

            $html = '<div class="mt-1">';
            foreach ($files as $key => $file) {
                $url = base_url('assets/uploads/user_docs/candidates_docs/' . $employee_id . '/' . $file);
                $html .= '  <a href="' . $url . '" class="badge badge-default mr-1" title="Click to view the file" target="_blank">File' . ($key + 1) . '</a>';
            }
            $html .= '</div>';
            return $html;
        } else {
            return "";
        }
    }
}

if (!function_exists('display_images_verified')) {
    function display_images_verified($files, $employee_id)
    {
        if ($files) {
            $files = explode(',', $files);

            $html = '<div>';
            foreach ($files as $key => $file) {
                $url = base_url('assets/uploads/verified_docs/' . $employee_id . '/' . $file);
                $html .= '  <a href="' . $url . '" class="badge badge-default mr-1" title="Click to view the file" target="_blank">File ' . ($key + 1) . '</a>';
            }
            $html .= '</div>';
            return $html;
        } else {
            return "";
        }
    }
}
if (!function_exists('clean_string')) {
    function clean_string($str)
    {
        $clean = preg_replace('/[^a-z0-9]+/', '-', strtolower($str));
        return $clean;
    }
}
if (!function_exists('get_last_date_of_a_month')) {
    function get_last_date_of_a_month($date)
    {
        if (isValidDate($date, 'Y-m-d')) {
            $d = new DateTime($date);
            return $d->format('Y-m-t');
        } else {
            return "";
        }
    }
}
function isValidDate($date)
{
    return date('Y-m-d', strtotime($date)) === $date;
}

function getDatesBetweenTwoDates($startDate, $endDate) {
    $dates = array();
    $start = strtotime($startDate);
    $end = strtotime($endDate);

    while($start <= $end) {
        $dates[] = date('Y-m-d', $start);
        $start = strtotime('+1 day', $start);
    }

    return $dates;
}
///////////// Aneesh End ////////////////

/////////// Harish Start//////
if (!function_exists('cancel_status')) {
    function cancel_status($value)
    {
        $img = array('1' => '<span class="badge badge-danger">Cancelled</span>', '0' => '<span class="badge badge-success">Active</span>');
        if ($value != "") {
            return @$img[$value];
        } else {
            return false;
        }
    }
}

if (!function_exists('leave_status')) {
    function leave_status($duration)
    {

        $parts = explode(':', $duration);
        $time =  $parts[0] + $parts[1] / 60;

        if ($time < 4.5) {
            return 'Leave';
        } elseif ($time >= 4.5 && $time < 9) {
            return 'Half Day';
        } else {
            return 'Present';
        }
    }
}
if (!function_exists('day_diff_sign')) {
    function day_diff_sign($from_date, $to_date)
    {
        $totdays = 0;
        $datetime1  = new DateTime(date('Y-m-d H:i:s', strtotime($from_date)));
        $datetime2  = new DateTime(date('Y-m-d H:i:s', strtotime($to_date)));
        $difference = $datetime2->diff($datetime1);
        $totdays =  $difference->format('%r%a');
        return $totdays;
    }
}
if (!function_exists('generate_uuid')) {
    function generate_uuid()
    {
        // Generate 16 bytes of random data
        $data = random_bytes(16);

        // Set the version (4) and variant (2)
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Format the UUID
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}

function is_admin()
{
    $ci = &get_instance();
    $roles = $ci->session->userdata('type');
    if ($roles != "") {
        if ($roles == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function is_rm()
{
    $ci = &get_instance();
    $is_rm = $ci->session->userdata('is_rm');
    if ($is_rm != "" && $is_rm == 1) {
        return true;
    } else {
        return false;
    }
}
//this is for checking approval of leave and all those things
function is_in_headlist() {
    $ci = &get_instance();
    $session_id = $ci->session->userdata('employee_id');    
    $qry = "select employee_id from employee where reporting_manager_id IN (?)";
    $res = $ci->db->query($qry, array($session_id))->result_array();   
    $qry1 = "select head_id from additional_leave_approve_head where head_id = ?";
    $res1 = $ci->db->query($qry1, array($session_id))->row_array();   
   
    if (!empty($res) || !empty($res1)) {
        return true;
    } else {
        return false;
    }
}

function is_in_rmlist() {
    $ci = &get_instance();
    $session_id = $ci->session->userdata('employee_id');    
    $qry = "select employee_id from employee where reporting_manager_id IN (?)";
    $res = $ci->db->query($qry, array($session_id))->result_array();   
    if (!empty($res) || !empty($res1)) {
        return true;
    } else {
        return false;
    }
}

if (!function_exists('is_halfday')) {
    function is_halfday($value)
    {
        $img = array('1' => '<span class="badge badge-warning">Half Day</span>', '0' => '<span class="badge badge-primary">Full Day</span>');
        if ($value != "") {
            return @$img[$value];
        } else {
            return false;
        }
    }
}

function trimDecimal($value) {
    if (strpos($value, '.') !== false) {
        $value = rtrim(rtrim($value, '0'), '.'); 
        return $value;
    }
}

///////////////////////// Harish End/////////////////////