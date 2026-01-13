@extends('layouts.app')

@section('title', 'View Assignment')
@section('portal-name', 'Student Portal')

@section('navigation')
<a href="{{ route('student.dashboard') }}" class="nav-item"><span class="icon">📊</span>Dashboard</a>
<a href="{{ route('student.courses') }}" class="nav-item"><span class="icon">📚</span>My Courses</a>
<a href="{{ route('student.assignments') }}" class="nav-item active"><span class="icon">📝</span>Assignments</a>
<a href="{{ route('student.grades') }}" class="nav-item"><span class="icon">📈</span>Grades</a>
<a href="{{ route('student.progress') }}" class="nav-item"><span class="icon">🎯</span>Progress</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>{{ $assignment->title }}</h1>
        <p>{{ $assignment->course->title ?? 'Course' }}</p>
    </div>
</header>

<div class="content-section">
    <h2>Details</h2>

    <p style="margin-top: 10px;">
        <strong>Description:</strong><br>
        {{ $assignment->description ?? '—' }}
    </p>

    <p style="margin-top: 10px;">
        <strong>Due Date:</strong>
        @if(!empty($assignment->due_date))
            {{ \Carbon\Carbon::parse($assignment->due_date)->format('M d, Y') }}
        @else
            —
        @endif
    </p>

    <hr style="margin: 20px 0;">

    <h2>Attached File</h2>

    @if($assignment->file_path)

        <a href="{{ route('student.assignments.download', $assignment->id) }}"
           class="btn btn-sm btn-success">
            Download File
        </a>

        @if(!empty($assignment->file_original_name))
            <p style="margin-top: 8px; opacity: 0.8;">
                File: {{ $assignment->file_original_name }}
            </p>
        @endif
    @else
        <p style="opacity: 0.7;">No file attached.</p>
    @endif
</div>

<div class="content-section" style="margin-top: 20px;">
    <h2>Submit Your Work</h2>

    {{-- Success/Error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Existing submission info --}}
    @if($submission)
        <p style="margin-bottom: 10px;">
            <strong>Status:</strong>
            <span class="badge badge-success">Submitted</span>
        </p>

        @if($submission->file_path)
            <p style="opacity: 0.85;">
                Submitted File: {{ $submission->file_original_name ?? basename($submission->file_path) }}
            </p>
        @endif

        <p style="opacity: 0.75;">
            Submitted at: {{ \Carbon\Carbon::parse($submission->submitted_at ?? $submission->created_at)->format('M d, Y h:i A') }}
        </p>

        <p style="opacity: 0.7; margin-top: 10px;">
            You may resubmit to replace your previous file.
        </p>
    @else
        <p style="opacity: 0.7;">No submission yet.</p>
    @endif

    <form action="{{ route('student.assignments.submit', $assignment->id) }}"
          method="POST"
          enctype="multipart/form-data"
          style="margin-top: 15px;">
        @csrf

        <div style="margin-bottom: 10px;">
            <label><strong>Upload File</strong></label><br>
            <input type="file" name="file" required>
        </div>

        <button type="submit" class="btn btn-primary">
            {{ $submission ? 'Resubmit' : 'Submit' }}
        </button>

        <a href="{{ route('student.assignments') }}" class="btn btn-secondary" style="margin-left: 8px;">
            Back
        </a>
    </form>
</div>
@endsection
