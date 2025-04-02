<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // Lấy danh sách tất cả các task (với filter)
    public function index(Request $request)
    {
        $query = Task::query();

        // Áp dụng các bộ lọc nếu có
        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }
        if ($request->has('created_by')) {
            $query->where('created_by', $request->created_by);
        }
        if ($request->has('manager')) {
            $query->where('manager', $request->manager);
        }
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    // Tạo mới một task
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'opportunity_id' => 'required|exists:opportunities,id',
            'contact_id' => 'nullable|exists:contacts,id',
            'manager' => 'required|string',
            'status' => 'required|string',
        ]);

        $task = Task::create($request->all());

        return response()->json($task, 201);
    }

    // Lấy thông tin một task cụ thể
    public function show(Task $task)
    {
        return response()->json($task);
    }

    // Cập nhật một task
    public function update(Request $request, Task $task)
    {
        $task->update($request->all());

        return response()->json($task);
    }

    // Xóa một task
    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
  

    public function filter(Request $request)
    {
        $query = Task::query();

        if ($request->has('title')) {
            $query->where('title', 'like', '%' . $request->input('title') . '%');
        }

        if ($request->has('created_at')) {
            $query->whereDate('created_at', $request->input('created_at'));
        }

        $tasks = $query->get();

        return response()->json($tasks);
    }
}

