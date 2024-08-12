<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // For employees
    // public function index()
    // {
    //     $tasks = Task::where('employee_id', auth()->id())->get();
    //     return view('tasks.index', compact('tasks'));
    // }
    public function index()
    {
        $tasks = Task::where('employee_id', auth()->id())
                    ->where('status', '!=', 'rejected') // Exclude rejected tasks
                    ->get();
        return view('tasks.index', compact('tasks'));
    }


    // For admins
    public function adminIndex()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $employees = User::where('role', 0)->get(); // Assuming '0' is for employees
        return view('tasks.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'duration_days' => 'required|integer|min:1',
        ]);

        Task::create([
            'employee_id' => $request->employee_id,
            'title' => $request->title,
            'description' => $request->description,
            'duration_days' => $request->duration_days,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.tasks')->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function accept(Task $task)
    {
        if ($task->employee_id === auth()->id() && $task->status === 'pending') {
            $task->update(['status' => 'processing', 'accepted_at' => now()]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Unable to accept task.'], 403);
    }
    
    public function reject(Task $task)
    {
        if ($task->employee_id === auth()->id() && $task->status === 'pending') {
            $task->update(['status' => 'rejected']);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Unable to reject task.'], 403);
    }
    
    public function complete(Task $task)
    {
        if ($task->employee_id === auth()->id() && $task->status === 'processing') {
            $task->update(['status' => 'completed']);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'Unable to complete task.'], 403);
    }
}
