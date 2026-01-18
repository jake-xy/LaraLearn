@extends('layouts.app')

@section('title', 'My Courses')

@section('portal-name', 'Student Portal')

@section('navigation')
<a href="{{ route('student.dashboard') }}" class="nav-item">
    <span class="icon">📊</span>
    Dashboard
</a>
<a href="{{ route('student.courses') }}" class="nav-item active">
    <span class="icon">📚</span>
    My Courses
</a>
<a href="{{ route('student.assignments') }}" class="nav-item">
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
        <h1>My Courses</h1>
        <p>Manage your enrolled courses and join new ones</p>
    </div>
</header>

@php
    $enrolled = $enrolledCourses ?? collect();
    $available = $availableCourses ?? collect();

    $hasEnrolled = is_countable($enrolled) && count($enrolled) > 0;
@endphp

{{-- ============ EMPTY STATE (No enrolled courses) ============ --}}
@if(!$hasEnrolled)
    <div class="content-section">
        <h2>You're not enrolled in any course yet</h2>
        <p style="opacity: 0.85;">Join a course to start learning.</p>

        <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1.5rem;">
            {{-- Join via Code --}}
            <div style="flex: 1; min-width: 320px; border:1px solid #eee; border-radius:12px; padding:1.25rem;">
                <h3 style="margin:0 0 .5rem 0;">Join with Course Code</h3>
                <p style="opacity:.8; margin:0 0 1rem 0;">Enter the code given by your teacher.</p>

                <form method="POST" action="{{ route('student.courses.join') }}">
                    @csrf
                    <div style="display:flex; gap:.5rem; align-items:center;">
                        <input
                            type="text"
                            name="code"
                            placeholder="e.g. ABC123"
                            value="{{ old('code') }}"
                            style="flex:1; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                            required
                        >
                        <button type="submit" class="btn btn-primary">
                            Join
                        </button>
                    </div>

                    @error('code')
                        <div style="color:#b00020; margin-top:.5rem; font-size:.9rem;">
                            {{ $message }}
                        </div>
                    @enderror
                </form>
            </div>

            {{-- Browse available courses (optional) --}}
            <div style="flex: 1; min-width: 320px; border:1px solid #eee; border-radius:12px; padding:1.25rem;">
                <h3 style="margin:0 0 .5rem 0;">Browse Available Courses</h3>
                <p style="opacity:.8; margin:0 0 1rem 0;">Choose a course and join instantly.</p>

                @if(is_countable($available) && count($available) > 0)
                    <div style="display:grid; gap:.75rem;">
                        @foreach($available as $course)
                            <div style="border:1px solid #f0f0f0; border-radius:12px; padding:1rem;">
                                <div style="display:flex; justify-content:space-between; gap:1rem;">
                                    <div>
                                        <div style="font-weight:600;">
                                            {{ $course->title ?? $course->name ?? 'Untitled Course' }}
                                        </div>
                                        <div style="opacity:.8; font-size:.9rem;">
                                            Teacher: {{ $course->teacher->name ?? 'N/A' }}
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('student.courses.enroll', $course->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary">
                                            Join
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="opacity:.8; margin:0;">No available courses listed yet.</p>
                @endif
            </div>
        </div>
    </div>
@else

{{-- ============ ENROLLED STATE (Has courses) ============ --}}
    <div class="content-section">
        <h2>Enrolled Courses</h2>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1rem; margin-top:1rem;">
            @foreach($enrolled as $course)
                <div style="border:1px solid #eee; border-radius:14px; padding:1.25rem;">
                    <div style="display:flex; justify-content:space-between; gap:1rem;">
                        <div>
                            <h3 style="margin:0;">
                                {{ $course->title ?? $course->name ?? 'Untitled Course' }}
                            </h3>
                            <p style="margin:.4rem 0 0 0; opacity:.8;">
                                Teacher: {{ $course->teacher->name ?? 'N/A' }}
                            </p>
                        </div>

        
                        <a href="{{ route('student.courses.show', $course->id) }}" class="btn btn-primary" style="margin-top: 1rem;">
                             Enter Course
                        </a>
                    </div>

                    {{-- Optional progress bar if your controller provides it --}}
                    @php
                        $progress = $course->progress ?? null;
                    @endphp

                    @if(!is_null($progress))
                        <div style="margin-top:1rem;">
                            <div style="display:flex; justify-content:space-between; margin-bottom:.4rem;">
                                <span style="opacity:.8;">Progress</span>
                                <span style="font-weight:600;">{{ $progress }}%</span>
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Optional description --}}
                    @if(!empty($course->description))
                        <p style="margin-top:1rem; opacity:.9;">
                            {{ $course->description }}
                        </p>
                    @endif

                    {{-- Optional: leave course --}}
                    <div style="margin-top:1rem; display:flex; gap:.5rem; justify-content:flex-end;">
                        @if(Route::has('student.courses.leave'))
                            <form method="POST" action="{{ route('student.courses.leave', $course->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger">
                                    Leave
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Join/Add more courses even if enrolled --}}
    <div class="content-section">
        <h2>Join Another Course</h2>

        <form method="POST" action="{{ route('student.courses.join') }}" style="max-width:520px;">
            @csrf
            <div style="display:flex; gap:.5rem; align-items:center;">
                <input
                    type="text"
                    name="code"
                    placeholder="Enter course code"
                    value="{{ old('code') }}"
                    style="flex:1; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                >
                <button type="submit" class="btn btn-primary">
                    Join
                </button>
            </div>

            @error('code')
                <div style="color:#b00020; margin-top:.5rem; font-size:.9rem;">
                    {{ $message }}
                </div>
            @enderror
        </form>
    </div>
@endif
@endsection
