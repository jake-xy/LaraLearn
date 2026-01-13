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
<a href="{{ route('teacher.submissions') }}" class="nav-item">
    <span class="icon">📝</span>
    Submissions
</a>
<a href="{{ route('teacher.grading') }}" class="nav-item active">
    <span class="icon">✅</span>
    Grading
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Grading Dashboard</h1>
        <p>Review and grade student submissions</p>
    </div>
</header>

<!-- Filter Options -->
<div class="table-controls">
    <form action="{{ route('teacher.grading') }}" method="GET">
        <select name="course" id="filterCourse" class="filter-select" onchange="this.form.submit()">
            <option value="">All Courses</option>
            @foreach($courses ?? [] as $course)
                <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
        <select name="status" id="filterStatus" class="filter-select" onchange="this.form.submit()">
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="graded" {{ request('status') == 'graded' ? 'selected' : '' }}>Graded</option>
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
        </select>
    </form>
</div>

<!-- Submissions Table -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Assignment</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Grade</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="submissionsTableBody">
            @forelse($submissions ?? [] as $submission)
                <tr>
                    <td>{{ $submission->student->name }}</td>
                    <td>{{ $submission->assignment->course->title }}</td>
                    <td>{{ $submission->assignment->title }}</td>
                    <td>{{ $submission->created_at->format('M d, Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $submission->grade ? 'success' : 'warning' }}">
                            {{ $submission->grade ? 'Graded' : 'Pending' }}
                        </span>
                    </td>
                    <td>
                        @if($submission->grade)
                            {{ $submission->grade->points_earned }} / {{ $submission->grade->max_points }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                       <a href="{{ route('teacher.grading.show', $submission->id) }}" class="btn btn-primary">Grade</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No submissions found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/teacher-grading.js') }}"></script>
@endpush
