<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        
        $query = Task::query();
        // If engineer, they might only see their tasks. Admin sees all.
        // If you want everyone to see all or specific, let's just make it all for admin, and assigned for engineer.
        if (Auth::user()->role == 'engineer') {
            $query->where('assigned_to', Auth::id());
        }

        $totalTasksToday = (clone $query)->whereDate('date', $today)->count();
        
        $completed = (clone $query)->where('status', 'Done')->count();
        $pending = (clone $query)->where('status', 'Pending')->count();
        $onProgress = (clone $query)->where('status', 'On Progress')->count();
        $totalAll = $completed + $pending + $onProgress;
        
        $progress = $totalAll > 0 ? round(($completed / $totalAll) * 100, 2) : 0;
        
        $overdue = (clone $query)
            ->where('deadline', '<', $today)
            ->where('status', '!=', 'Done')
            ->count();
            
        $latestActivities = (clone $query)->orderBy('updated_at', 'desc')->take(5)->get();

        // Absensi Logic
        $user = auth()->user();
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

        return view('dashboard', compact(
            'totalTasksToday',
            'completed',
            'pending',
            'onProgress',
            'progress',
            'overdue',
            'latestActivities',
            'attendanceToday', 
            'totalHadirHariIni', 
            'belumCheckOut', 
            'weeklyData', 
            'presentaseKehadiran'
        ));
    }
}
