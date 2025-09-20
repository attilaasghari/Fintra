<?php
// app/Helpers/JalaliHelper.php

namespace App\Helpers;

use Morilog\Jalali\Jalalian;

class JalaliHelper {
    
    // Convert Gregorian date to Jalali date string (Y/m/d)
    public static function toJalali($date) {
        if (empty($date) || $date === '0000-00-00') {
            return '';
        }
        
        try {
            return Jalalian::fromDateTime($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return $date; // Return original if conversion fails
        }
    }
    
    // Convert Jalali date string (Y/m/d) to Gregorian date (Y-m-d)
    public static function toGregorian($jalali_date) {
        if (empty($jalali_date)) {
            return false;
        }
        
        try {
            // Standardize to '-' separator only
            $jalali_date = str_replace(['/', '.', ' '], '-', $jalali_date);
            $parts = explode('-', $jalali_date);
            if (count($parts) !== 3) {
                return false;
            }
            $year = (int)$parts[0];
            $month = (int)$parts[1];
            $day = (int)$parts[2];
            // Basic range checks for Jalali calendar
            if ($year < 1200 || $year > 1600 || $month < 1 || $month > 12 || $day < 1 || $day > 31) {
                return false;
            }
            // Use Morilog Jalali for conversion
            $jalali = \Morilog\Jalali\Jalalian::fromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $day));
            $carbon = $jalali->toCarbon();
            return $carbon->format('Y-m-d');
        } catch (\Exception $e) {
            error_log("Jalali to Gregorian conversion failed: " . $e->getMessage());
            return false;
        }
    }
    
    // Universal method to convert Jalali to Gregorian
    private static function convertJalaliToGregorian($jy, $jm, $jd) {
        // This is a reliable algorithm for Jalali to Gregorian conversion
        // Works independently of the morilog/jalali library version
        
        if ($jy > 979) {
            $gy = 1600;
            $jy -= 979;
        } else {
            $gy = 621;
        }
        
        $days = (365 * $jy) + (((int)(($jy + 3) / 4)) - ((int)(($jy + 99) / 100)) + ((int)(($jy + 399) / 400)));
        
        if ($jm < 7) {
            $days += ($jm - 1) * 31;
        } else {
            $days += (($jm - 7) * 30) + 186;
        }
        
        $days += $jd - 1;
        
        $gy += 400 * ((int)($days / 146097));
        $days %= 146097;
        
        if ($days > 36524) {
            $gy += 100 * ((int)(--$days / 36524));
            $days %= 36524;
            if ($days >= 365) {
                $days++;
            }
        }
        
        $gy += 4 * ((int)($days / 1461));
        $days %= 1461;
        
        if ($days > 365) {
            $gy += (int)(($days - 1) / 365);
            $days = ($days - 1) % 365;
        }
        
        $gd = $days + 1;
        $sal_a = [0, 31, (($gy % 4 == 0 and $gy % 100 != 0) or ($gy % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $gm = 0;
        
        for ($i = 0; $i < 13 && $gd > $sal_a[$i]; $i++) {
            $gd -= $sal_a[$i];
            $gm = $i + 1;
        }
        
        return sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
    }
    
    // Get current Jalali date
    public static function now() {
        try {
            return Jalalian::now()->format('Y-m-d');
        } catch (\Exception $e) {
            // Fallback to manual calculation
            $date = getdate();
            return self::toJalali(date('Y-m-d', $date[0]));
        }
    }
    
    // Format Jalali date with custom format
    public static function format($date, $format = 'Y/m/d') {
        if (empty($date) || $date === '0000-00-00') {
            return '';
        }
        try {
            // If format is default, use '-'
            $fmt = ($format === 'Y/m/d') ? 'Y-m-d' : $format;
            return Jalalian::fromDateTime($date)->format($fmt);
        } catch (\Exception $e) {
            return $date;
        }
    }
    
    // Get Jalali month name
    public static function getMonthName($month_num) {
        $months = [
            1 => 'فروردین',
            2 => 'اردیبهشت',
            3 => 'خرداد',
            4 => 'تیر',
            5 => 'مرداد',
            6 => 'شهریور',
            7 => 'مهر',
            8 => 'آبان',
            9 => 'آذر',
            10 => 'دی',
            11 => 'بهمن',
            12 => 'اسفند'
        ];
        return $months[$month_num] ?? "ماه $month_num";
    }
    
    // Get Jalali day of week name
    public static function getDayName($date) {
        try {
            $jalali = Jalalian::fromDateTime($date);
            $day_num = $jalali->getDayOfWeek();
            
            $days = [
                6 => 'شنبه',
                0 => 'یکشنبه', 
                1 => 'دوشنبه',
                2 => 'سه‌شنبه',
                3 => 'چهارشنبه',
                4 => 'پنج‌شنبه',
                5 => 'جمعه'
            ];
            
            return $days[$day_num] ?? '';
        } catch (\Exception $e) {
            return '';
        }
    }
}