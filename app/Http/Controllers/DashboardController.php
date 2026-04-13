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

        return view('dashboard', compact(
            'totalTasksToday',
            'completed',
            'pending',
            'onProgress',
            'progress',
            'overdue',
            'latestActivities'
        ));
    }
}
