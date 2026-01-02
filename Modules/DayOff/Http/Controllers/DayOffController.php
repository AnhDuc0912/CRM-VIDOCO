<?php

namespace Modules\DayOff\Http\Controllers;

use Modules\Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\DayOff\Models\DayOff;
use Modules\Core\Enums\PermissionEnum;

class DayOffController extends Controller
{
    /**
     * Danh sách yêu cầu nghỉ
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if (PermissionEnum::DAY_OFF_VIEW_ALL) {
            $dayoffs = DayOff::with('user')->orderByDesc('created_at')->paginate(20);
        } else {
            $dayoffs = DayOff::where('user_id', $user->id)->orderByDesc('created_at')->paginate(20);
        }


        return view('dayoff::index', compact('dayoffs'));
    }

    /**
     * Trang tạo mới yêu cầu nghỉ
     */
    public function create()
    {
        can(PermissionEnum::DAY_OFF_CREATE);

        $userId = Auth::id();
        $year = now()->year;

        $plan = DayOff::where('user_id', $userId)->where('type', 'ke_hoach')->whereYear('date', $year)->count();
        $home = DayOff::where('user_id', $userId)->where('type', 'lam_viec_o_nha')->whereYear('date', $year)->count();
        $special = DayOff::where('user_id', $userId)->where('type', 'ngoai_le')->whereYear('date', $year)->count();

        $total = 12;
        $used = $plan + $home + $special;

        return view('dayoff::create', compact('plan', 'home', 'special', 'total', 'used'));
    }

    /**
     * Lưu yêu cầu nghỉ mới
     */
    public function store(Request $request)
    {
        can(PermissionEnum::DAY_OFF_CREATE);

        $request->validate([
            'date' => 'required|date',
            'session' => 'required',
            'type' => 'required|in:ke_hoach,lam_viec_o_nha,ngoai_le',
            'mode' => 'nullable|in:den_muon,ve_som,ra_ngoai',
            'time' => 'nullable|date_format:H:i',
            'reason_type' => 'nullable|in:tac_duong,nghi_om,viec_khan_cap,khac',
            'note' => 'nullable|string|max:500',
        ]);

        $mode = $request->type === 'ngoai_le' ? $request->mode : null;
        $time = $request->type === 'ngoai_le' ? $request->time : null;

        DayOff::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'session' => $request->session,
            'type' => $request->type,
            'mode' => $mode,
            'time' => $time,
            'reason_type' => $request->reason_type,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('dayoff.index')->with('success', 'Gửi yêu cầu nghỉ thành công!');
    }

    /**
     * Chi tiết yêu cầu nghỉ
     */
    public function show($id)
    {
        can(PermissionEnum::DAY_OFF_VIEW);

        $dayoff = DayOff::with('user')->findOrFail($id);
        $user = Auth::user();

        return view('dayoff::show', compact('dayoff'));
    }

    /**
     * Trang chỉnh sửa yêu cầu
     */
    public function edit($id)
    {
        can(PermissionEnum::DAY_OFF_UPDATE);

        $dayoff = DayOff::findOrFail($id);
        $userId = Auth::id();
        $year = now()->year;

        $plan = DayOff::where('user_id', $userId)->where('type', 'ke_hoach')->whereYear('date', $year)->count();
        $home = DayOff::where('user_id', $userId)->where('type', 'lam_viec_o_nha')->whereYear('date', $year)->count();
        $special = DayOff::where('user_id', $userId)->where('type', 'ngoai_le')->whereYear('date', $year)->count();

        $total = 12;
        $used = $plan + $home + $special;

        return view('dayoff::edit', compact('dayoff', 'plan', 'home', 'special', 'total', 'used'));
    }

    /**
     * Cập nhật yêu cầu
     */
    public function update(Request $request, $id)
    {
        can(PermissionEnum::DAY_OFF_UPDATE);

        $dayoff = DayOff::findOrFail($id);

        $request->validate([
            'type' => 'required|in:ke_hoach,lam_viec_o_nha,ngoai_le',
            'mode' => 'nullable|in:den_muon,ve_som,ra_ngoai',
            'date' => 'required|date',
            'session' => 'required',
            'reason_type' => 'required|in:tac_duong,nghi_om,viec_khan_cap,khac',
            'time' => $request->type === 'ngoai_le' ? 'required|date_format:H:i' : 'nullable',
            'note' => 'nullable|string|max:400',
        ]);

        $dayoff->update([
            'type' => $request->type,
            'mode' => $request->type === 'ngoai_le' ? $request->mode : null,
            'date' => $request->date,
            'session' => $request->session,
            'reason_type' => $request->reason_type,
            'time' => $request->type === 'ngoai_le' ? $request->time : null,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('dayoff.index')->with('success', 'Cập nhật yêu cầu nghỉ phép thành công.');
    }

    /**
     * Duyệt yêu cầu nghỉ
     */
    public function approve($id)
    {
        can(PermissionEnum::DAY_OFF_APPROVE);

        $user = Auth::user();
        $dayoff = DayOff::findOrFail($id);

        if (!$dayoff->isPending()) {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }

        $dayoff->update([
            'status' => 'approved',
            'approved_by' => $user->id,
        ]);

        return back()->with('success', 'Đã duyệt yêu cầu nghỉ.');
    }

    /**
     * Từ chối yêu cầu nghỉ
     */
    public function reject($id, Request $request)
    {
        can(PermissionEnum::DAY_OFF_APPROVE);

        $user = Auth::user();
        $request->validate(['reason_reject' => 'nullable|string|max:1000']);

        $dayoff = DayOff::findOrFail($id);
        if (!$dayoff->isPending()) {
            return back()->with('error', 'Yêu cầu đã được xử lý.');
        }

        $dayoff->update([
            'status' => 'rejected',
            'approved_by' => $user->id,
        ]);

        return back()->with('error', 'Đã từ chối yêu cầu nghỉ.');
    }
}
