@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="h1 mb-4 text-center border-bottom">
        <h1 class="text-center text-primary-dark">TASKS DETAILS</h1>
    </div>

    <div class="table-responsive shadow-sm rounded bg-light">
        <table class="table align-middle mb-0 bg-white">
            <thead class="thead-light">
                <tr class="text-center">
                    <th>#</th>
                    <th class="text-left">Title</th>
                    <th>Assigned For</th>
                    <th>Status</th>
                    <th>Assigned At</th>
                    <th>Accepted At</th>
                    <th>Set Due</th>
                    <th>Due In (Days)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $index => $task)
                <tr id="task-row-{{ $task->id }}" class="
                    @if($task->status_id === $pendingStatusId) table-warning 
                    @elseif($task->status_id === $processingStatusId) table-light
                    @elseif($task->status_id === $completedStatusId) table-light
                    @elseif($task->status_id === $rejectedStatusId) table-danger
                    @else table-secondary
                    @endif
                    text-dark">
                    <td class="text-center font-weight-bold">{{ $index + 1 }}</td>
                    <td>{{ $task->title }}</td>
                    <td class="text-center">{{ $task->employee->name }}</td>
                    <td class="text-center">
                        <span class="badge 
                            @if($task->status_id === $pendingStatusId) badge-warning
                            @elseif($task->status_id === $processingStatusId) badge-info
                            @elseif($task->status_id === $completedStatusId) badge-success
                            @elseif($task->status_id === $rejectedStatusId) badge-danger
                            @else badge-secondary
                            @endif">
                            {{ ucfirst($task->status->name) }}
                        </span>
                    </td>
                    <td class="text-center">{{ $task->created_at->format('Y-m-d H:i') }}</td>
                    <td class="text-center">
                        @if($task->accepted_at)
                            {{ $task->accepted_at->format('Y-m-d H:i') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="text-center">
                        @if($task->set_due)
                            {{ $task->set_due->format('Y-m-d H:i') }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $dueInDays = $task->set_due ? $task->created_at->diffInDays($task->set_due) : 'N/A';
                            echo $dueInDays;
                        @endphp
                    </td>
                    <td class="text-center">
                    @if(Auth::user()->role === 1)
                            <a href="{{ route('tasks.show', $task) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> View
                            </a>
                        @else
                        @if($task->status_id === $pendingStatusId)
                            <button onclick="handleTaskAction('accept', {{ $task->id }})" class="btn btn-success btn-sm">Accept</button>
                            <button onclick="handleTaskAction('reject', {{ $task->id }})" class="btn btn-danger btn-sm">Reject</button>
                        @elseif($task->status_id === $processingStatusId)
                            <button onclick="handleTaskAction('complete', {{ $task->id }})" class="btn btn-info btn-sm">Complete</button>
                        @else
                            <span class="text-muted">No Actions Available</span>
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
    }

    $.ajax({
        url: url,
        type: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                if (action === 'reject') {
                    $(`#task-row-${taskId}`).remove(); // Remove the rejected task from the table
                } else {
                    location.reload();
                }
            } else {
                alert(response.message);
            }
        },
        error: function(response) {
            alert('An error occurred while performing the action.');
        }
    });
}

</script>

@endsection
