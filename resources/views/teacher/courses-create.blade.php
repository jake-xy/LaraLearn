@extends('layouts.app')

@section('title', 'Create Course')
@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item">
    <span class="icon">🏠</span> Dashboard
</a>
<a href="{{ route('teacher.courses') }}" class="nav-item active">
    <span class="icon">📚</span> My Courses
</a>
<a href="{{ route('teacher.content.index') }}" class="nav-item">
    <span class="icon">📄</span> Course Content
</a>
<a href="{{ route('teacher.submissions') }}" class="nav-item">
    <span class="icon">📥</span> Submissions
</a>
<a href="{{ route('teacher.grading') }}" class="nav-item">
    <span class="icon">✅</span> Grading
</a>
@endsection

@section('content')
<header class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
    <div>
        <h1>Create Course</h1>
        <p>Add a new course with title, description, and credits.</p>
    </div>

    <a href="{{ route('teacher.courses') }}" class="btn">← Back to Courses</a>
</header>

<div class="content-section" style="max-width:760px;">
    <div style="border:1px solid #eee; border-radius:14px; padding:1.25rem;">
        <form method="POST" action="{{ route('teacher.courses.store') }}">
            @csrf

            <div style="display:grid; gap:.85rem;">

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:.35rem;">Course Title</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                    @error('title')
                        <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:.35rem;">Description</label>
                    <textarea name="description"
                              rows="4"
                              style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">{{ old('description') }}</textarea>
                    @error('description')
                        <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:.35rem;">Credits</label>
                    <input type="number"
                           name="credits"
                           value="{{ old('credits', 0) }}"
                           min="0"
                           style="width:220px; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                    @error('credits')
                        <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="display:flex; gap:.6rem; margin-top:.25rem;">
                    <button type="submit" class="btn btn-primary">Create Course</button>
                    <a href="{{ route('teacher.courses') }}" class="btn">Cancel</a>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
