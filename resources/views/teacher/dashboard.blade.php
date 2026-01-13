@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item active">
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
<a href="{{ route('teacher.submissions') }}" class="nav-item">
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
        <h1>Teacher Dashboard</h1>
        <p>Manage your courses and students</p>
    </div>
</header>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-content">
            <p class="stat-label">My Courses</p>
            <h3 class="stat-value" id="myCourses">{{ $myCourses ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
            <p class="stat-label">Total Students</p>
            <h3 class="stat-value" id="myStudents">{{ $totalStudents ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-content">
            <p class="stat-label">Pending Submissions</p>
            <h3 class="stat-value" id="pendingSubmissions">{{ $pendingSubmissions ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <p class="stat-label">Graded This Week</p>
            <h3 class="stat-value" id="gradedThisWeek">{{ $gradedThisWeek ?? 0 }}</h3>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="content-section">
    <div class="section-header">
        <h2>Quick Actions</h2>
    </div>
    <div class="action-cards">
       <a href="{{ route('teacher.content-upload') }}" class="action-card">
            <span class="action-icon">📤</span>
            <h3>Upload Content</h3>
            <p>Add new course materials</p>
        </a>
        <a href="{{ route('teacher.submissions') }}" class="action-card">
            <span class="action-icon">📋</span>
            <h3>View Submissions</h3>
            <p>Check student work</p>
        </a>
        <a href="{{ route('teacher.grading') }}" class="action-card">
            <span class="action-icon">✏️</span>
            <h3>Grade Assignments</h3>
            <p>Evaluate student performance</p>
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/teacher-dashboard.js') }}"></script>
@endpush
