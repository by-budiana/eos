<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TasksExport;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with('user');
        
        if (Auth::user()->role === 'engineer') {
            $query->where('assigned_to', Auth::id());
        }

        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $engineers = User::where('role', 'engineer')->get();
        return view('tasks.create', compact('engineers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Pending,On Progress,Done',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        
        $validated['date'] = date('Y-m-d');
        
        Task::create($validated);
        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $engineers = User::where('role', 'engineer')->get();
        return view('tasks.edit', compact('task', 'engineers'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'status' => 'required|in:Pending,On Progress,Done',
            'priority' => 'required|in:Low,Medium,High',
            'deadline' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update($validated);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
    
    public function export(Request $request)
    {
        return Excel::download(new TasksExport($request->start_date, $request->end_date, $request->status, Auth::user()), 'tasks.xlsx');
    }
}
