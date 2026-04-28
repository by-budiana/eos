<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $today = date('Y-m-d');
        
        $attendanceToday = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        // Stats for Dashboard
        $totalHadirHariIni = \App\Models\Attendance::where('date', $today)->count();
        $belumCheckOut = \App\Models\Attendance::where('date', $today)->whereNull('check_out_time')->count();
        
        // Weekly Data for Graph
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $weeklyData['labels'][] = date('D, d M', strtotime($date));
            $weeklyData['data'][] = \App\Models\Attendance::where('date', $date)->count();
        }

        // Presentase (Example logic: Assuming total users count)
        $totalUsers = \App\Models\User::count();
        $presentaseKehadiran = $totalUsers > 0 ? round(($totalHadirHariIni / $totalUsers) * 100) : 0;

        return view('attendances.dashboard', compact(
            'attendanceToday', 
            'totalHadirHariIni', 
            'belumCheckOut', 
            'weeklyData', 
            'presentaseKehadiran'
        ));
    }

    public function history(Request $request)
    {
        $query = \App\Models\Attendance::with('user')->orderBy('date', 'desc')->orderBy('check_in_time', 'desc');

        if ($request->has('date') && $request->date != '') {
            $query->where('date', $request->date);
        }

        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if (auth()->user()->role != 'admin') {
            $query->where('user_id', auth()->user()->id);
        }

        $attendances = $query->paginate(15);
        $users = \App\Models\User::all();

        return view('attendances.history', compact('attendances', 'users'));
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $user = auth()->user();
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');

        $existing = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah check in hari ini.');
        }

        // Tentukan status
        $status = ($currentTime > '08:00:00') ? 'terlambat' : 'hadir';

        \App\Models\Attendance::create([
            'user_id' => $user->id,
            'date' => $today,
            'check_in_time' => $currentTime,
            'check_in_latitude' => $request->latitude,
            'check_in_longitude' => $request->longitude,
            'status' => $status
        ]);

        return redirect()->back()->with('success', 'Berhasil Check In!');
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
        ]);

        $user = auth()->user();
        $today = date('Y-m-d');
        $currentTime = date('H:i:s');

        $attendance = \App\Models\Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Anda belum check in hari ini.');
        }

        if ($attendance->check_out_time) {
            return redirect()->back()->with('error', 'Anda sudah check out hari ini.');
        }

        $attendance->update([
            'check_out_time' => $currentTime,
            'check_out_latitude' => $request->latitude,
            'check_out_longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'Berhasil Check Out!');
    }

    public function export(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\AttendanceExport($request->all()), 'attendances_'.date('YmdHis').'.xlsx');
    }
}
