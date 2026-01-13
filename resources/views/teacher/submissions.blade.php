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
        <h1>Submissions</h1>
        <p>Open a course to view assignment submissions.</p>
    </div>
</header>

@if($courses->isEmpty())
    <div class="content-section">
        <p style="opacity:.85;">No courses created.</p>
    </div>
@else
    <div class="content-section">
        <h2>Your Courses</h2>

        <div style="display:grid; gap:1rem; margin-top:1rem;">
            @foreach($courses as $course)
                <div style="border:1px solid #eee; border-radius:14px; padding:1rem; display:flex; justify-content:space-between; gap:1rem; align-items:center;">
                    <div>
                        <div style="font-weight:700;">{{ $course->title }}</div>
                        <div style="opacity:.8;">{{ $course->description ?? 'No description' }}</div>
                    </div>

                    <a class="btn btn-primary" href="{{ route('teacher.courses.submissions', $course->id) }}">
                        Open
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
