@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">{{ $task->title }}</h1>

    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Description</th>
                    <td>{{ $task->description }}</td>
                </tr>
                <tr>
                    <th>Duration (Days)</th>
                    <td>{{ $task->duration_days }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $task->status }}</td>
                </tr>
                @if($task->status === 'completed')
                <tr>
                    <th>Completion Status</th>
                    <td class="text-success">Task Completed!</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
