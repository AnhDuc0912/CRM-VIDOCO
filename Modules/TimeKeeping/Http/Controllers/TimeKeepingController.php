<?php

namespace Modules\TimeKeeping\Http\Controllers;

use Carbon\Carbon;
use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\PermissionEnum;
use Modules\Employee\Models\Employee;
use Modules\TimeKeeping\Models\TimeKeeping;

class TimeKeepingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $date = $request->get('date');

        $query = TimeKeeping::with('employee');

        if ($date) {
            $query->whereDate('check_in', $date);
        } else {
            $query->whereMonth('check_in', now()->month)
                ->whereYear('check_in', now()->year);
        }

        if (!PermissionEnum::DAY_OFF_VIEW_ALL) {
            $query->where('employee_id', $user->employee->id);
        }

        $query->orderBy('id', 'desc');

        $timekeepings = $query->paginate(20);

        $todayCheckin = TimeKeeping::where('employee_id', Auth::id())
            ->whereDate('check_in', today())
            ->first();

        return view('timekeeping::index', compact('timekeepings', 'todayCheckin', 'date'));
    }





    public function checkinStatus()
    {
        $exists = TimeKeeping::where('employee_id', Auth::user()->employee->id)
            ->whereDate('check_in', today())
            ->first();

        return response()->json([
            'checked_in' => $exists ? true : false,
            'time' => $exists->check_in ?? null
        ]);
    }


    public function checkin(Request $request)
    {
        TimeKeeping::create([
            'employee_id'   => Auth::user()->employee->id,
            'check_in'      => now(),
            'ip_check_in'   => $request->ip(),
            'device_check_in' => $request->header('User-Agent')
        ]);

        return back()->with('success', 'Check-in thành công!');
    }

    public function checkout(Request $request)
    {
        $check = TimeKeeping::where('employee_id', Auth::user()->employee->id)
            ->whereNull('check_out')
            ->orderBy('id', 'desc')
            ->first();

        if (!$check) {
            return back()->with('error', 'Bạn chưa check-in hôm nay!');
        }

        $check->update([
            'check_out'        => now(),
            'ip_check_out'     => $request->ip(),
            'device_check_out' => $request->header('User-Agent')
        ]);

        return back()->with('success', 'Check-out thành công!');
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $monthNum] = explode('-', $month);

        $employees = Employee::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->get();

        $timekeepings = TimeKeeping::whereYear('check_in', $year)
            ->whereMonth('check_in', $monthNum)
            ->get()
            ->groupBy('employee_id');

        $workingDays = [];
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);

        for ($d = 1; $d <= $totalDays; $d++) {
            $dateObj = Carbon::createFromDate($year, $monthNum, $d);
            if ($dateObj->dayOfWeek !== Carbon::SUNDAY) {
                $workingDays[] = $d;
            }
        }

        $summary = [];

        foreach ($employees as $emp) {

            $empData = [
                'employee'      => $emp,
                'days'          => [],
                'late_days'     => 0,
                'early_days'    => 0,
                'day_off'       => 0,
                'work_days'     => 0,
            ];

            foreach ($workingDays as $day) {

                $date = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);

                $tk = optional($timekeepings[$emp->id] ?? collect())
                    ->first(function ($item) use ($date) {
                        return $item->check_in >= $date . " 00:00:00" &&
                            $item->check_in <= $date . " 23:59:59";
                    });

                if ($tk) {
                    $status = [
                        'check_in'  => (bool)$tk->check_in,
                        'check_out' => (bool)$tk->check_out,
                        'late'      => $tk->late,
                        'early'     => $tk->early_leave,
                    ];

                    $empData['work_days']++;
                    if ($tk->late)        $empData['late_days']++;
                    if ($tk->early_leave) $empData['early_days']++;
                } else {
                    $status = null;
                    $empData['day_off']++;
                }

                $empData['days'][$day] = $status;
            }

            $summary[] = $empData;
        }

        return view('timekeeping::monthly', compact(
            'summary',
            'month',
            'workingDays'
        ));
    }
}
