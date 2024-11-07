<?php // Code within app\Helpers\Helper.php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

function hideMiddleDigits($phoneNumber)
{
    // Kiểm tra xem $phoneNumber có đúng định dạng số điện thoại không
    if (preg_match('/^(\d{4})(\d+)(\d{3})$/', $phoneNumber, $matches)) {
        $maskedNumber = $matches[1] . '***' . $matches[3];
        return $maskedNumber;
    } else {
        // Nếu không phải số điện thoại hợp lệ, trả về nguyên văn bản
        return $phoneNumber;
    }
}

if (!function_exists('getFieldValue')) {
    function getFieldValue($row, ...$fields)
    {
        foreach ($fields as $field) {
            if (isset($row[$field])) {
                return preg_replace('/\s+/', ' ', $row[$field]);
            }
        }
        return null;
    }
}

if (!function_exists('convertWord')) {
    function convertWord($array)
    {
        if ($array) {
            return implode(',', $array);
        }
        return null;
    }
}

if (!function_exists('asset_url')) {
    function asset_url($url)
    {
        return asset('storage/' . $url);
    }
}

if (!function_exists('Account_time')) {
    function Account_time($end_time)
    {
        if ($end_time != 0) {
            $endTime = $end_time; // Thời gian kết thúc dưới dạng timestamp
            $currentTime = time(); // Lấy thời gian hiện tại dưới dạng timestamp
            $diff = date_diff(date_create("@$currentTime"), date_create("@$endTime")); // Tính khoảng cách giữa hai thời gian dưới dạng đối tượng DateInterval
            $daysLeft = $diff->d; // Trích xuất số ngày còn lại
            $hoursLeft = $diff->h; // Trích xuất số giờ còn lại
            return $daysLeft . ' Ngày ' . $hoursLeft . ' Giờ';
        }
        return 'Trọn đời';
    }
}

if (!function_exists('remove_link')) {
    function remove_link($filename)
    {
        if (file_exists(base_path('public/storage/') . $filename)) {
            return unlink(base_path('public/storage/') . $filename);
        }
    }
}



if (!function_exists('convert_parse_time')) {
    function convert_parse_time($strtotime)
    {
        $time = Carbon::parse(Carbon::createFromTimestamp($strtotime)->format('Y-m-d H:i:s'));
        return $time;
    }
}


if (!function_exists('convert_time')) {
    function convert_time($strtotime)
    {
        $time = Carbon::createFromTimestamp($strtotime)->format('Y-m-d H:i:s');
        return $time;
    }
}


//    active time
if (!function_exists('active_time')) {
    function active_time($time)
    {
        $lastSeen = Carbon::parse($time);
        $now = Carbon::now();
        $minutes = $now->diffInMinutes($lastSeen);
        $hours = $now->diffInHours($lastSeen);
        $day = $now->diffInDays($lastSeen);
        $week = $now->diffInWeeks($lastSeen);
        $moth = $now->diffInMonths($lastSeen);
        $year = $now->diffInYears($lastSeen);
        if ($minutes > 60) {
            if ($hours <= 24) {
                return $hours . ' Giờ trước';
            } else {
                if ($day <= 8) {
                    return $day . ' Ngày trước';
                } else {
                    if ($week <= 30) {
                        return $week . ' Tuần trước';
                    } else {
                        if ($moth <= 12) {
                            return $week . ' Tháng trước';
                        } else {
                            return $year . ' Năm trước';
                        }
                    }
                }
            }
        } else {
            return $minutes . ' Phút trước';
        }
    }
}

function saveConvertTime($time)
{
    $dateTime = \DateTime::createFromFormat('d-m-Y', $time);
    if ($dateTime === false) {
        $dateTime = \DateTime::createFromFormat('d/m/Y', $time);
        if ($dateTime === false) {
            return \DateTime::createFromFormat('Y/m/d', $time)->getTimestamp();
        }
        return $dateTime->getTimestamp();
    }
    return $dateTime->getTimestamp();
}


if (!function_exists('removeSpecialCharacters')) {
    // Hàm để loại bỏ các ký tự đặc biệt trong một chuỗi
    function removeSpecialCharacters($string)
    {
        // Các ký tự đặc biệt bạn muốn loại bỏ
        $specialCharacters = ['"', "'", "\n", "\t", "\r", '!', '@', '#', '*', '&', '(', ')'];
        // Loại bỏ các ký tự đặc biệt
        $string = str_replace($specialCharacters, '', $string);

        return $string;
    }
}

function getCategoriesTable($categories, $char = '', &$result = [])
{
    if (!empty($categories)) {
        foreach ($categories as $key => $category) {
            $row = $category;
            $row['name'] = $char . $row['name'];
            unset($row['children']);
            $result[] = $row;
            if (!empty($category['children'])) {
                getCategoriesTable($category['children'], $char . '|__ ', $result);
            }
        }
    }
    return $result;
}

if (!function_exists('buildCategoryOptions')) {
    function buildCategoryOptions($categories, $parentId = 0, $level = 0, $selectedId = null)
    {
        $html = '';
        foreach ($categories as $category) {
            if ($category->parent_id == $parentId) {
                $selected = $category->id == $selectedId ? ' selected' : ''; // Kiểm tra xem có phải là giá trị được chọn không
                $html .= '<option value="' . $category->id . '"' . $selected . '>' . str_repeat('|__', $level) . ' ' . $category->name . '</option>';
                $html .= buildCategoryOptions($categories, $category->id, $level + 1, $selectedId);
            }
        }
        return $html;
    }
}


if (!function_exists('isRole')) {
    function isRole($dataArr, $moduleName): bool
    {
        return !empty($dataArr) && in_array($moduleName, $dataArr);
    }
}
