@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Tasks</h1>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Duration (Days)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                    <tr>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->duration_days }}</td>
                        <td>
                            @if(Auth::user()->role === 1)
                                <a href="{{ route('tasks.show', $task) }}" class="btn btn-info btn-sm">View Details</a>
                            @else
                                <form method="POST" action="{{ route('tasks.accept', $task) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('tasks.reject', $task) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
