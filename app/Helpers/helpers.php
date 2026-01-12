<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('format_date')) {
    function format_date($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}

if (!function_exists('format_date_time')) {
    function format_date_time($date)
    {
        return Carbon::parse($date)->format('d/m/Y H:i:s');
    }
}

if (!function_exists('set_breadcrumbs')) {
    function set_breadcrumbs($breadcrumbs)
    {
        view()->share('breadcrumbs', $breadcrumbs);
    }
}

if (!function_exists('add_breadcrumb')) {
    function add_breadcrumb($title, $url = null)
    {
        $breadcrumbs = session('breadcrumbs', []);
        $breadcrumbs[] = [
            'title' => $title,
            'url' => $url
        ];
        session(['breadcrumbs' => $breadcrumbs]);
        view()->share('breadcrumbs', $breadcrumbs);
    }
}

if (!function_exists('can')) {
    function can($permission)
    {
        if (auth()->user()->hasPermissionTo($permission) || auth()->user()->hasRole('ceo')) {
            return true;
        }

        return abort(403, 'Bạn không có quyền thực hiện hành động này');
    }
}

if (!function_exists('format_money')) {
    function format_money($money)
    {
        return number_format($money, 0, ',', '.');
    }
}

if (!function_exists('format_number_short')) {
    /**
     * Format number to short form with Vietnamese units
     * @param float|int $number
     * @param int $decimals Number of decimal places
     * @return string
     */
    function format_number_short($number, $decimals = 1)
    {
        if ($number < 1000) {
            return number_format($number, 0, ',', '.');
        }

        $units = [
            ['value' => 1000000000, 'unit' => 'tỷ'],
            ['value' => 1000000, 'unit' => 'triệu'],
            ['value' => 1000, 'unit' => 'nghìn'],
        ];

        foreach ($units as $unitData) {
            if ($number >= $unitData['value']) {
                $value = $number / $unitData['value'];
                // Remove trailing zeros
                $formatted = rtrim(rtrim(number_format($value, $decimals, ',', '.'), '0'), ',');
                return $formatted . ' ' . $unitData['unit'];
            }
        }

        return number_format($number, 0, ',', '.');
    }
}

if (!function_exists('generate_code')) {
    function generate_code($prefix, $table)
    {
        $lastRecord = DB::table($table)->orderBy('id', 'desc')->first();

        if (!$lastRecord) {
            return $prefix . str_pad(1, 5, '0', STR_PAD_LEFT);
        }

        $lastCode = $lastRecord->code;
        $lastNumber = (int)substr($lastCode, strlen($prefix));
        $newNumber = $lastNumber + 1;

        do {
            $newCode = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
            $exists = DB::table($table)->where('code', $newCode)->exists();
            if ($exists) {
                $newNumber++;
            }
        } while ($exists);

        return $newCode;
    }
}
