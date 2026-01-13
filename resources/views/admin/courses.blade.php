@extends('layouts.app')

@section('title', 'Manage Courses')

@section('portal-name', 'Admin Panel')

@section('navigation')
    <x-admin-navbar selectedItem='courses'></x-admin-navbar>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Manage Courses</h1>
        <p>Create and manage course offerings</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">+ Add Course</a>
        {{-- <button class="btn btn-primary" onclick="openAddCourseModal()">+ Add Course</button> --}}
    </div>
</header>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
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
            @if(!empty($course->course_code))
                            <div style="margin-top:.9rem; font-size:.9rem; opacity:.85;">
                                Join Code: <strong>{{ $course->course_code }}</strong>
                            </div>
                        @endif
            <div class="course-footer">
                <span>Teacher: {{ $course->teacher->name ?? 'Not assigned' }}</span>
                <div>
                    <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-secondary">Edit</a>
                    <form action="{{ route('admin.courses.delete', $course->id) }}" method="POST" style="display: inline;">
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
@endsection

@push('scripts')
<script src="{{ asset('js/admin-courses.js') }}"></script>
@endpush
