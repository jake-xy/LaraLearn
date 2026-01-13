@extends('layouts.app')

@section('title', 'Course Content')
@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item">
    <span class="icon">🏠</span> Dashboard
</a>
<a href="{{ route('teacher.courses') }}" class="nav-item">
    <span class="icon">📚</span> My Courses
</a>
<a href="{{ route('teacher.content.index') }}" class="nav-item active">
    <span class="icon">📄</span> Course Content
</a>
<a href="{{ route('teacher.submissions') }}" class="nav-item">
    <span class="icon">📥</span> Submissions
</a>
<a href="{{ route('teacher.grading') }}" class="nav-item">
    <span class="icon">✅</span> Grading
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Course Content</h1>
        <p>View and manage the lessons/materials you uploaded</p>
    </div>
</header>

@php
    $courses = $courses ?? collect();
    $uploads = $uploads ?? collect();
@endphp

{{-- If no courses --}}
@if($courses->isEmpty())
    <div class="content-section">
        <h2>No courses yet</h2>
        <p style="opacity:.85;">Create a course first so you can upload lessons and materials.</p>
        <div style="margin-top:1rem;">
            <a class="btn btn-primary" href="{{ route('teacher.courses') }}">Go to My Courses</a>
        </div>
    </div>

{{-- Has courses --}}
@else
    <div class="content-section" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2 style="margin:0;">Your Uploads</h2>
            <p style="margin:.25rem 0 0 0; opacity:.8;">All materials uploaded across your courses</p>
        </div>

        <a class="btn btn-primary" href="{{ route('teacher.content-upload') }}">
            + Upload Content
        </a>
    </div>

    {{-- No uploads --}}
    @if($uploads->isEmpty())
        <div class="content-section">
            <p style="opacity:.85;">No content uploaded yet. Click “Upload Content” to add lessons or files.</p>
        </div>
    @else
        <div class="content-section">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Course</th>
                            <th>Type</th>
                            <th>Date Uploaded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uploads as $upload)
                            <tr>
                                <td><strong>{{ $upload->title ?? 'Untitled' }}</strong></td>
                                <td>{{ $upload->course->course_name ?? $upload->course->title ?? '—' }}</td>
                                <td>{{ $upload->type ?? 'File' }}</td>
                                <td>{{ optional($upload->created_at)->format('M d, Y') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endif
@endsection
