@extends('layouts.app')

@section('title', 'Assignments')

@section('portal-name', 'Student Portal')

@section('navigation')
<a href="{{ route('student.dashboard') }}" class="nav-item">
    <span class="icon">📊</span>
    Dashboard
</a>
<a href="{{ route('student.courses') }}" class="nav-item">
    <span class="icon">📚</span>
    My Courses
</a>
<a href="{{ route('student.assignments') }}" class="nav-item active">
    <span class="icon">📝</span>
    Assignments
</a>
<a href="{{ route('student.grades') }}" class="nav-item">
    <span class="icon">📈</span>
    Grades
</a>
<a href="{{ route('student.progress') }}" class="nav-item">
    <span class="icon">🎯</span>
    Progress
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Assignments</h1>
        <p>View and track your assigned tasks</p>
    </div>
</header>

{{-- If there are no assignments, show a clean blank state --}}
@if(empty($assignments) || (is_countable($assignments) && count($assignments) === 0))
    <div class="content-section">
        <p style="text-align: center; opacity: 0.8; padding: 2rem 0;">
            No assignments assigned yet.
        </p>
    </div>
@else
    <div class="content-section">
        <h2>Assigned Assignments</h2>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $assignment)
                
                        <tr>
                            <td>
                                <strong>{{ $assignment->title ?? 'Untitled' }}</strong>
                                @if(!empty($assignment->description))
                                    <div style="font-size: 0.9rem; opacity: 0.8;">
                                        {{ $assignment->description }}
                                    </div>
                                @endif
                            </td>

                            <td>
                                {{ $assignment->course->title ?? 'N/A' }}
                            </td>

                            <td>
                                @if(!empty($assignment->due_date))
                                    {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                @php
                                    // If your assignment has a related submission, use that
                                    // Otherwise fallback to blank
                                    $submitted = false;

                                    // Common patterns:
                                    // 1) $assignment->submission
                                    // 2) $assignment->submissions->where('student_id', auth()->id())->first()
                                    if (isset($assignment->submission) && $assignment->submission) {
                                        $submitted = true;
                                    }

                                    if (isset($assignment->submissions) && $assignment->submissions) {
                                        $submitted = $assignment->submissions
                                            ->where('student_id', auth()->id())
                                            ->isNotEmpty();
                                    }
                                @endphp

                                <span class="badge badge-{{ $submitted ? 'success' : 'warning' }}">
                                    {{ $submitted ? 'Submitted' : 'Pending' }}
                                </span>
                                <td>
                                    @if($assignment->file_path)
                                        <a href="{{ route('student.assignments.show', $assignment->id) }}"
                                            class="btn btn-sm btn-primary">
                                            View
                                        </a>


                                        <a href="{{ route('student.assignments.download', $assignment->id) }}"
                                        class="btn btn-sm btn-success" style="margin-bottom: 4px;">
                                            Download
                                        </a>
                                    @else
                                        <span style="opacity: 0.6;">No file</span>
                                    @endif
                                </td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
