@extends('layouts.app')

@section('title', 'My Progress')

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
<a href="{{ route('student.grades') }}" class="nav-item">
    <span class="icon">📈</span>
    Grades
</a>
<a href="{{ route('student.progress') }}" class="nav-item active">
    <span class="icon">🎯</span>
    Progress
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>My Progress</h1>
        <p>Track your learning journey</p>
    </div>
</header>

<!-- Overall Progress -->
<div class="content-section">
    <h2>Overall Progress</h2>
    <div class="progress-overview">
        <div class="progress-stat">
            <span class="progress-label">Completion Rate</span>
            <div class="progress-bar-container">
                <div class="progress-bar" id="completionRate" style="width: {{ $completionRate ?? 0 }}%"></div>
            </div>
            <span class="progress-value" id="completionValue">{{ $completionRate ?? 0 }}%</span>
        </div>
    </div>
</div>

<!-- Course Progress -->
<div class="content-section">
    <h2>Course Progress</h2>
    <div class="progress-courses" id="courseProgressList">
        @forelse($courseProgress ?? [] as $course)
            <div class="progress-course-item" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 500;">{{ $course->title }}</span>
                    <span>{{ $course->progress }}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: {{ $course->progress }}%"></div>
                </div>
            </div>
        @empty
            <p>No course progress data available</p>
        @endforelse
    </div>
</div>

<!-- Recent Grades -->
<div class="content-section">
    <h2>Recent Grades</h2>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Assignment</th>
                    <th>Course</th>
                    <th>Grade</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody id="recentGradesBody">
                @forelse($recentGrades ?? [] as $grade)
                    <tr>
                        <td>{{ optional(optional($grade->submission)->assignment)->title ?? 'Assignment' }}</td>
                        <td>{{ optional(optional(optional($grade->submission)->assignment)->course)->title ?? 'Course' }}</td>
                        <td>
                            <span class="badge badge-{{ $grade->grade >= 70 ? 'success' : 'warning' }}">
                                {{ $grade->grade }}%
                            </span>
                        </td>
                        <td>{{ $grade->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No grades available yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/student-progress.js') }}"></script>
@endpush
