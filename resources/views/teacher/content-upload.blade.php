@extends('layouts.app')

@section('title', 'Upload Content')

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
<a href="{{ route('teacher.content.index') }}" class="nav-item active">
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
        <h1>Upload Course Content</h1>
        <p>Add materials for your students</p>
    </div>
</header>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Upload Form -->
<div class="content-section">
    <form id="contentUploadForm" action="{{ route('teacher.content.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
        @csrf
       <div class="form-group">
            <label for="course">Select Course</label>
            <select id="course" name="course_id" required>
                <option value="">Choose a course</option>

                @foreach(($courses ?? collect()) as $course)
                    <option value="{{ $course->id }}"
                        {{ (string) old('course_id', $selectedCourseId ?? '') === (string) $course->id ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>

            @error('course_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

    
        <div class="form-group">
            <label for="contentType">Content Type</label>
            <select id="contentType" name="content_type" required>
                <option value="">Select type</option>
                <option value="lecture">Lecture Notes</option>
                <option value="assignment">Assignment</option>
                <option value="reading">Reading Material</option>
                <option value="video">Video</option>
                <option value="other">Other</option>
            </select>
            @error('content_type')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="Enter content title">
            @error('title')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" placeholder="Describe the content">{{ old('description') }}</textarea>
            @error('description')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="file">Upload File</label>
            <div class="file-upload">
                <input type="file" id="file" name="file" required>
                <p class="file-info">Supported formats: PDF, DOC, DOCX, PPT, PPTX, MP4, ZIP (Max: 50MB)</p>
            </div>
            @error('file')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form-group" id="dueDateGroup" style="display: none;">
            <label for="dueDate">Due Date (for assignments)</label>
            <input type="datetime-local" id="dueDate" name="due_date" value="{{ old('due_date') }}">
            @error('due_date')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Content</button>
    </form>
</div>

<!-- Uploaded Content List -->
<div class="content-section">
    <div class="section-header">
        <h2>Recent Uploads</h2>
    </div>
    <div class="content-list" id="contentList">
        @forelse($recentContent ?? [] as $content)
            <div class="content-item">
                <h4>{{ $content->title ?? 'Untitled' }}</h4>
                     <p>
                        {{ optional($content->course)->title ?? 'Unknown Course' }}
                        -
                        {{ ucfirst($content->content_type ?? 'unknown') }}
                    </p>

                    <span>
                        {{ optional($content->created_at)->diffForHumans() ?? '' }}
                    </span>
                </div>
        @empty
            <p>No content uploaded yet</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/teacher-content.js') }}"></script>
@endpush
