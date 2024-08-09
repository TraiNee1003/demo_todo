@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="h1 mb-4 text-center border-bottom">Create Task</h1>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf

        <div class="mb-3">
            <label for="employee_id" class="form-label">Employee</label>
            <select class="form-select rounded" name="employee_id" id="employee_id" required>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control rounded" id="title" name="title" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea style="min-height: 50px; max-height: 50px;" id="description" min-row="2" max-row="4"class="form-control  rounded" name="description" required></textarea>
        </div>

        <div class="mb-3">
            <label for="duration_days" class="form-label">Duration (Days)</label>
            <input type="number" class="form-control  rounded" id="duration_days" name="duration_days" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Create Task</button>
    </form>
</div>
@endsection
