@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('portal-name', 'Admin Panel')

@section('navigation')
<a href="{{ route('admin.dashboard') }}" class="nav-item active">
    <span class="icon">📊</span>
    Dashboard
</a>
<a href="{{ route('admin.students.index') }}" class="nav-item">
    <span class="icon">👥</span>
    Students
</a>
<a href="{{ route('admin.teachers.index') }}" class="nav-item">
    <span class="icon">👨‍🏫</span>
    Teachers
</a>
<a href="{{ route('admin.courses.index') }}" class="nav-item">
    <span class="icon">📚</span>
    Courses
</a>
<a href="{{ route('admin.enrollments.index') }}" class="nav-item">
    <span class="icon">📝</span>
    Enrollments
</a>
<a href="{{ route('admin.reports') }}" class="nav-item">
    <span class="icon">📈</span>
    Reports
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Admin Dashboard</h1>
        <p>Welcome back, {{ Auth::user()->name }}</p>
    </div>
    <div class="header-actions">
        <span class="user-info">{{ Auth::user()->email }}</span>
    </div>
</header>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
            <p class="stat-label">Total Students</p>
            <h3 class="stat-value" id="totalStudents">{{ $totalStudents ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">👨‍🏫</div>
        <div class="stat-content">
            <p class="stat-label">Total Teachers</p>
            <h3 class="stat-value" id="totalTeachers">{{ $totalTeachers ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div class="stat-content">
            <p class="stat-label">Active Courses</p>
            <h3 class="stat-value" id="totalCourses">{{ $totalCourses ?? 0 }}</h3>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-content">
            <p class="stat-label">Enrollments</p>
            <h3 class="stat-value" id="totalEnrollments">{{ $totalEnrollments ?? 0 }}</h3>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="content-section">
    <div class="section-header">
        <h2>Recent Activity</h2>
    </div>
    <div class="activity-list" id="activityList">
        @forelse($recentActivity ?? [] as $activity)
            <div class="activity-item">
                <p><strong>{{ $activity->user_name }}</strong> {{ $activity->action }}</p>
                <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
            </div>
        @empty
            <p>No recent activity</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-dashboard.js') }}"></script>
@endpush
