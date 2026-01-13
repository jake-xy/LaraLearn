@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('portal-name', 'Student Portal')

@section('navigation')
<a href="{{ route('student.dashboard') }}" class="nav-item active">
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
        <h1>Student Dashboard</h1>
        <p>Track your learning progress</p>
    </div>
</header>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-content">
            <p class="stat-label">Enrolled Courses</p>
            <h3 class="stat-value" id="enrolledCourses">{{ ($enrolledCourses ?? collect())->count() }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-content">
            <p class="stat-label">Pending Assignments</p>
        <h3 class="stat-value" id="pendingAssignmentsCount">{{ $pendingAssignmentsCount ?? 0 }}</h3>

        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <p class="stat-label">Completed</p>
            <h3 class="stat-value" id="completedAssignments">{{ $completedAssignments ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-content">
            <p class="stat-label">Average Grade</p>
            <h3 class="stat-value" id="averageGrade">{{ $averageGrade ?? 0 }}%</h3>
        </div>
    </div>
</div>

<!-- My Courses -->
<div class="content-section">
    <div class="section-header">
        <h2>My Courses</h2>
        <a href="{{ route('student.courses') }}" class="link">View all</a>
    </div>
    <div class="courses-grid" id="coursesGrid">
        @forelse(($enrolledCourses ?? collect()) as $course)
            <div class="course-card">
                <div class="course-header">
                    <h3 class="course-title">{{ $course->title }}</h3>
                    <p class="course-code">{{ $course->code }}</p>
                </div>
                <p class="course-description">{{ $course->description }}</p>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: {{ $course->progress ?? 0 }}%"></div>
                </div>
                <p style="font-size: 0.875rem; color: var(--color-text-light); margin-top: 0.5rem;">
                    Progress: {{ $course->progress ?? 0 }}%
                </p>
            </div>
        @empty
        <p class="course-code">{{ $course->course_code ?? $course->code ?? '' }}</p>

        @endforelse
    </div>
</div>

<!-- Upcoming Assignments -->
    <div class="content-section">
        <div class="section-header">
            <h2>Upcoming Assignments</h2>
        </div>
        <div class="assignments-list" id="assignmentsList">
            @forelse($upcomingAssignments ?? [] as $assignment)
             <div class="assignment-item" style="padding: 1rem; border-bottom: 1px solid var(--color-border);">
                    <h4 style="margin-bottom: 0.5rem;">{{ $assignment->title }}</h4>
                    <p style="font-size: 0.875rem; color: var(--color-text-light);">
                    {{ optional($assignment->course)->title ?? 'No Course' }} - Due: {{ optional($assignment->due_date)->format('M d, Y') ?? 'No due date' }}

                    </p>
                <a href="{{ route('student.assignments') }}" class="btn btn-primary" style="margin-top: 0.5rem;">Submit</a>

                </div>
            @empty
                <p>No upcoming assignments</p>
            @endforelse
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/student-dashboard.js') }}"></script>
@endpush
