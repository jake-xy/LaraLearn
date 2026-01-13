@extends('layouts.app')

@section('title', 'Grading Dashboard')

@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item">
    <span class="icon">📊</span>
    Dashboard
</a>
<a href="{{ route('teacher.courses') }}" class="nav-item">
    <span class="icon">📚</span>
    My Courses
</a>
<a href="{{ route('teacher.content.index') }}" class="nav-item">
    <span class="icon">📄</span>
    Course Content
</a>
<a href="{{ route('teacher.submissions') }}" class="nav-item active">
    <span class="icon">📝</span>
    Submissions
</a>
<a href="{{ route('teacher.grading') }}" class="nav-item">
    <span class="icon">✅</span>
    Grading
</a>
@endsection
@section('content')
<header class="page-header">
    <div>
        <h1>{{ $course->title }} — Submissions</h1>
        <p>{{ $course->description ?? 'No description provided.' }}</p>
    </div>
</header>

<div class="content-section" style="display:flex; justify-content:space-between; align-items:center;">
    <h2 style="margin:0;">Assignments & Submissions</h2>
    <a class="btn btn-secondary" href="{{ route('teacher.submissions') }}">← Back to Courses</a>
</div>

@if($assignments->isEmpty())
    <div class="content-section">
        <p style="opacity:.85;">No submissions because there are no assignments for this course.</p>
    </div>
@else
    <div class="content-section">
        <h3 style="margin-top:0;">Assignments</h3>
        <ul style="margin:0; padding-left:1.25rem; opacity:.9;">
            @foreach($assignments as $a)
                <li>
                    <strong>{{ $a->title ?? 'Untitled' }}</strong>
                    <span style="opacity:.75;">
                        — {{ optional($a->due_date)->format('M d, Y') ?? 'No due date' }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="content-section">
        <h3 style="margin-top:0;">Submissions</h3>

        @if($submissions->isEmpty())
            <p style="opacity:.85;">No student submissions yet.</p>
        @else
            <div class="table-container" style="margin-top:1rem;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Assignment</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($submissions as $s)
                            <tr>
                                <td>{{ $s->student->name ?? 'Student' }}</td>
                                <td>{{ $s->assignment->title ?? 'Assignment' }}</td>
                                <td>{{ $s->status ?? 'submitted' }}</td>
                                <td>{{ optional($s->created_at)->format('M d, Y h:i A') ?? '—' }}</td>
                                <td>
                                    @if(!empty($s->file_path))
                                        <a class="btn btn-secondary" href="{{ route('teacher.submissions.download', $s->id) }}">
                                            View / Download
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endif
@endsection
