@extends('layouts.app')

@section('title', 'Grade Submission')
@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item"><span class="icon">📊</span>Dashboard</a>
<a href="{{ route('teacher.courses') }}" class="nav-item"><span class="icon">📚</span>My Courses</a>
<a href="{{ route('teacher.submissions') }}" class="nav-item"><span class="icon">📨</span>Submissions</a>
<a href="{{ route('teacher.grading') }}" class="nav-item active"><span class="icon">✅</span>Grading</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Grade Submission</h1>
        <p>Assignment: {{ optional($submission->assignment)->title ?? 'Assignment' }}</p>
    </div>
</header>

<div class="content-section">
    <h2>Student</h2>
    <p>{{ optional($submission->student)->name ?? 'Student' }}</p>

    <hr style="margin: 16px 0;">

    <h2>Submitted File</h2>
    @if($submission->file_path)
        <a class="btn btn-secondary"
           href="{{ route('teacher.submissions.download', $submission->id) }}">
            Download Submission
        </a>
        <p style="margin-top:8px; opacity:.8;">
            {{ $submission->file_original_name ?? basename($submission->file_path) }}
        </p>
    @else
        <p style="opacity:.7;">No file uploaded.</p>
    @endif
</div>

<div class="content-section" style="margin-top: 20px;">
    <h2>Grade (0 - 100)</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teacher.grading.store', $submission->id) }}">
        @csrf

        <div style="margin-bottom: 12px;">
            <label><strong>Grade</strong></label><br>
            <input type="number"
                   name="grade"
                   min="0" max="100"
                   value="{{ old('grade', $existingGrade->points_earned ?? '') }}"
                   required>
        </div>

        <div style="margin-bottom: 12px;">
            <label><strong>Feedback (optional)</strong></label><br>
            <textarea name="feedback" rows="3" style="width:100%;">{{ old('feedback', $existingGrade->feedback ?? '') }}</textarea>
        </div>

        <button class="btn btn-primary" type="submit">Save Grade</button>
        <a class="btn btn-secondary" href="{{ route('teacher.grading') }}" style="margin-left:8px;">Back</a>
    </form>
</div>
@endsection
