@extends('layouts.app')

@section('title', 'Grades')

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
<a href="{{ route('student.assignments') }}" class="nav-item">
    <span class="icon">📝</span>
    Assignments
</a>
<a href="{{ route('student.grades') }}" class="nav-item active">
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
        <h1>Grades</h1>
        <p>View your scores and performance</p>
    </div>
</header>

@php
    $enrolledCourses = $enrolledCourses ?? collect();
    $grades = $grades ?? collect();
    $courseGrades = $courseGrades ?? collect();
@endphp

{{-- No courses enrolled --}}
@if($enrolledCourses->isEmpty())
    <div class="content-section">
        <h2>No grades available</h2>
        <p style="opacity: .85; margin-top: .5rem;">
            You don’t have any grades yet because you are not enrolled in any courses.
        </p>
        <div style="margin-top: 1.25rem;">
            <a href="{{ route('student.courses') }}" class="btn btn-primary">
                Join a Course
            </a>
        </div>
    </div>

{{-- Has courses enrolled --}}
@else

    {{-- Course Summary --}}
    <div class="content-section">
        <h2>Course Summary</h2>

        @if($courseGrades->isEmpty())
            <p style="opacity:.85;">No graded items have been posted yet.</p>
        @else
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:1rem; margin-top:1rem;">
                @foreach($courseGrades as $row)
                    @php
                        $course = $row['course'] ?? null;
                        $percentage = $row['percentage'] ?? 0;
                    @endphp

                    <div style="border:1px solid #eee; border-radius:14px; padding:1.25rem;">
                        <h3 style="margin:0;">
                            {{ $course->title ?? 'Course' }}
                        </h3>

                        <div style="display:flex; justify-content:space-between; margin-top:.75rem;">
                            <span style="opacity:.8;">Average</span>
                            <span style="font-weight:600;">{{ $percentage }}%</span>
                        </div>

                        <div style="margin-top:1rem;">
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: {{ min(100, max(0, $percentage)) }}%"></div>
                            </div>
                        </div>

                        <div style="display:flex; justify-content:space-between; margin-top:.75rem;">
                            <span style="opacity:.8;">Points</span>
                            <span style="font-weight:600;">
                                {{ $row['total_earned'] ?? 0 }} / {{ $row['total_max'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Recent Grades --}}
    <div class="content-section">
        <h2>Recent Grades</h2>

        @if($grades->isEmpty())
            <p style="opacity:.85;">No grades have been posted yet for your enrolled courses.</p>
        @else
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Assignment</th>
                            <th>Course</th>
                            <th>Score</th>
                            <th>Feedback</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grades as $grade)
                            @php
                                $assignmentTitle = $grade->submission->assignment->title ?? '—';
                                $courseTitle = $grade->course->title ?? '—';

                                // Calculate percent from points
                                $earned = $grade->points_earned ?? 0;
                                $max = $grade->max_points ?? 0;
                                $percent = $max > 0 ? round(($earned / $max) * 100, 2) : 0;

                                $date = $grade->created_at ? $grade->created_at->format('M d, Y') : '—';
                            @endphp

                            <tr>
                                <td>{{ $assignmentTitle }}</td>
                                <td>{{ $courseTitle }}</td>
                                <td>
                                    <span class="badge badge-{{ $percent >= 70 ? 'success' : 'warning' }}">
                                        {{ $percent }}%
                                    </span>
                                    <span style="opacity:.8; margin-left:.5rem;">
                                        ({{ $earned }}/{{ $max }})
                                    </span>
                                </td>

                                {{-- FEEDBACK COLUMN --}}
                                 <td>
                                     @if($grade->feedback)
                                        <div class="feedback-box">
                                            {{ $grade->feedback }}
                                        </div>
                                     @else
                                        <span class="text-muted">
                                            No feedback yet
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endif
@endsection
