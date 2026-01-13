@extends('layouts.app')

@section('title', 'Manage Courses')

@section('portal-name', 'Admin Panel')

@section('navigation')
<a href="{{ route('admin.dashboard') }}" class="nav-item">
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
<a href="{{ route('admin.courses.index') }}" class="nav-item active">
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
        <h1>Manage Courses</h1>
        <p>Create and manage course offerings</p>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="openAddCourseModal()">+ Add Course</button>
    </div>
</header>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Courses Grid -->
<div class="courses-grid" id="coursesGrid">
    @forelse($courses ?? [] as $course)
        <div class="course-card">
            <div class="course-header">
                <h3 class="course-title">{{ $course->title }}</h3>
                <p class="course-code">{{ $course->code }}</p>
            </div>
            <p class="course-description">{{ $course->description }}</p>
            <div class="course-footer">
                <span>Teacher: {{ $course->teacher->name ?? 'Not assigned' }}</span>
                <div>
                    <button onclick="editCourse({{ $course->id }})" class="btn btn-secondary">Edit</button>
                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p>No courses available</p>
    @endforelse
</div>

<!-- Add Course Modal -->
<div id="addCourseModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Course</h2>
            <button class="modal-close" onclick="closeAddCourseModal()">&times;</button>
        </div>
        <form id="addCourseForm" action="{{ route('admin.courses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="courseTitle">Course Title</label>
                <input type="text" id="courseTitle" name="title" required>
            </div>
            <div class="form-group">
                <label for="courseCode">Course Code</label>
                <input type="text" id="courseCode" name="code" required>
            </div>
            <div class="form-group">
                <label for="courseDescription">Description</label>
                <textarea id="courseDescription" name="description" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="courseTeacher">Assign Teacher</label>
                <select id="courseTeacher" name="teacher_id" required>
                    <option value="">Select a teacher</option>
                    @foreach($teachers ?? [] as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddCourseModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Course</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-courses.js') }}"></script>
@endpush
