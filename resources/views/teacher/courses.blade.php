@extends('layouts.app')

@section('title', 'My Courses')
@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item">
    <span class="icon">🏠</span> Dashboard
</a>
<a href="{{ route('teacher.courses') }}" class="nav-item active">
    <span class="icon">📚</span> My Courses
</a>
<a href="{{ route('teacher.content.index') }}" class="nav-item">
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
        <h1>My Courses</h1>
        <p>Create and manage your courses</p>
    </div>
    <a href="{{ route('teacher.courses.create') }}" class="btn btn-primary">
        + Create Course
    </a>

</header>

@php
    $courses = $courses ?? collect();
@endphp

{{-- EMPTY STATE: No courses created --}}
@if($courses->isEmpty())
    <div class="content-section">
        <h2>No courses created yet</h2>
        <p style="opacity:.85;">Create your first course to start uploading lessons and accepting students.</p>

        <div style="margin-top:1.25rem; display:flex; gap:1rem; flex-wrap:wrap;">
            <div style="flex:1; min-width:320px; border:1px solid #eee; border-radius:14px; padding:1.25rem;">
                <h3 style="margin:0 0 .5rem 0;">Create a Course</h3>

                <form method="POST" action="{{ route('teacher.courses.store') }}">
                    @csrf

                    <div style="display:grid; gap:.75rem;">
                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Course Title"
                            required
                            style="padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                        >

                        <textarea
                            name="description"
                            rows="3"
                            placeholder="Course Description (optional)"
                            style="padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                        >{{ old('description') }}</textarea>

                        <input
                            type="number"
                            name="credits"
                            value="{{ old('credits') }}"
                            placeholder="Credits (optional)"
                            min="0"
                            style="padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                        >

                        @error('title')
                            <div style="color:#b00020; font-size:.9rem;">{{ $message }}</div>
                        @enderror

                        <button class="btn btn-primary" type="submit">
                            Create Course
                        </button>
                    </div>
                </form>
            </div>

            <p style="opacity:.8; margin-top:1rem;">
                After creating a course, you can upload lessons and manage students.
            </p>
        </div>
    </div>

{{-- HAS COURSES --}}
@else
    <div class="content-section" style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2 style="margin:0;">Your Courses</h2>
            <p style="margin:.25rem 0 0 0; opacity:.8;">Click a course to view its content and lessons</p>
        </div>
    </div>

    <div class="content-section">
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:1rem;">
            @foreach($courses as $course)
                    <div style="border:1px solid #eee; border-radius:14px; padding:1.25rem;">
                        <div style="display:flex; justify-content:space-between; gap:1rem;">
                            <div>
                                <h3 style="margin:0;">{{ $course->title }}</h3>
                                <p style="margin:.4rem 0 0 0; opacity:.8;">
                                    {{ $course->description ?? 'No description provided.' }}
                                </p>
                            </div>

                            <div style="text-align:right;">
                                <div class="badge badge-success">{{ $course->status ?? 'active' }}</div>
                            </div>
                        </div>

                        <div style="display:flex; gap:1rem; margin-top:1rem; opacity:.9;">
                            <span>👥 {{ $course->enrollments_count ?? 0 }} Students</span>
                            <span>📝 {{ $course->assignments_count ?? 0 }} Assignments</span>
                            <span>🎓 {{ $course->credits ?? 0 }} Credits</span>
                        </div>

                        @if(!empty($course->course_code))
                            <div style="margin-top:.9rem; font-size:.9rem; opacity:.85;">
                                Join Code: <strong>{{ $course->course_code }}</strong>
                            </div>
                        @endif

                        <div style="display:flex; gap:.5rem; margin-top:1rem; flex-wrap:wrap;">
                            <a href="{{ route('teacher.courses.show', $course->id) }}"
                            class="btn">
                                View
                            </a>

                            <a href="{{ route('teacher.courses.edit', $course->id) }}"
                            class="btn">
                                Edit
                            </a>

                            <form method="POST"
                                action="{{ route('teacher.courses.destroy', $course->id) }}"
                                onsubmit="return confirm('Delete this course? This cannot be undone.');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="btn"
                                        style="border:1px solid #f2b8b5; color:#b00020;">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
