@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4 text-center text-primary">Tasks</h1>

    <div class="table-responsive shadow-sm rounded bg-light">
        <table class="table table-hover table-bordered table-striped">
            <thead class="thead-dark">
                <tr class="text-center">
                    <th>#</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Assigned For</th>
                    <th>Status</th>
                    <th>Assigned At</th>
                    <th>Accepted At</th>
                    <th>Due In (Days)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $index => $task)
                <tr id="task-row-{{ $task->id }}" class="
                    @if($task->status === 'pending') table-warning
                    @elseif($task->status === 'processing') table-info
                    @elseif($task->status === 'completed') table-light
                    @elseif($task->status === 'rejected') table-danger
                    @else table-secondary
                    @endif
                    text-dark
                    ">
                    <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ $task->employee->name }}</td>
                    <td class="text-center">
                        <span class="badge 
                            @if($task->status === 'pending') badge-warning
                            @elseif($task->status === 'processing') badge-info
                            @elseif($task->status === 'completed') badge-success
                            @elseif($task->status === 'rejected') badge-danger
                            @else badge-secondary
                            @endif
                            ">{{ ucfirst($task->status) }}</span>
                    </td>
                    <td>{{ $task->created_at->format('Y-m-d') }}</td>
                    <td>{{ $task->accepted_at ? $task->accepted_at->format('Y-m-d') : 'N/A' }}</td>
                    <td>{{ $task->accepted_at ? number_format($task->duration_days - $task->accepted_at->diffInDays(now()), 0) : number_format($task->duration_days, 0) }}</td>
                    <td class="text-center">
                        @if(Auth::user()->role === 1)
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        @else
                            @if($task->status === 'pending')
                                <button class="btn btn-outline-success btn-sm" onclick="handleTaskAction('accept', {{ $task->id }})">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="handleTaskAction('reject', {{ $task->id }})">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            @elseif($task->status === 'processing')
                                <button class="btn btn-outline-warning btn-sm" onclick="handleTaskAction('complete', {{ $task->id }})">
                                    <i class="fas fa-clipboard-check"></i> Complete
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function handleTaskAction(action, taskId) {
    let url;
    switch(action) {
        case 'accept':
            url = `{{ route('tasks.accept', ':id') }}`.replace(':id', taskId);
            break;
        case 'reject':
            url = `{{ route('tasks.reject', ':id') }}`.replace(':id', taskId);
            break;
        case 'complete':
            url = `{{ route('tasks.complete', ':id') }}`.replace(':id', taskId);
            break;
        default:
            console.error('Unknown action:', action);
            return;
    }

    $.ajax({
        url: url,
        type: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}',
        },
        success: function(response) {
            if (response.success) {
                if (action === 'reject') {
                    $(`#task-row-${taskId}`).fadeOut(500, function() {
                        $(this).remove();
                    });
                } else {
                    location.reload();
                }
            } else {
                alert(response.message || 'An error occurred while performing the action.');
            }
        },
        error: function(response) {
            console.error('AJAX error:', response);
            alert('An error occurred while performing the action.');
        }
    });
}
</script>
@endsection
