@extends('layouts.app')

@section('title', 'Edit Course')
@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item"><span class="icon">🏠</span> Dashboard</a>
<a href="{{ route('teacher.courses') }}" class="nav-item active"><span class="icon">📚</span> My Courses</a>
<a href="{{ route('teacher.content.index') }}" class="nav-item"><span class="icon">📄</span> Course Content</a>
<a href="{{ route('teacher.submissions') }}" class="nav-item"><span class="icon">📥</span> Submissions</a>
<a href="{{ route('teacher.grading') }}" class="nav-item"><span class="icon">✅</span> Grading</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Edit Course</h1>
        <p>Update course details</p>
    </div>
</header>

<div class="content-section" style="max-width: 760px;">
    <form method="POST" action="{{ route('teacher.courses.update', $course->id) }}">
        @csrf
        @method('PUT')

        <div style="display:grid; gap:.85rem;">
            <div>
                <label style="font-weight:600;">Course Title</label>
                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $course->title) }}"
                    required
                    style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                >
                @error('title') <div style="color:#b00020; font-size:.9rem;">{{ $message }}</div> @enderror
            </div>

            <div>
                <label style="font-weight:600;">Description</label>
                <textarea
                    name="description"
                    rows="4"
                    style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                >{{ old('description', $course->description) }}</textarea>
                @error('description') <div style="color:#b00020; font-size:.9rem;">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:.85rem;">
                <div>
                    <label style="font-weight:600;">Credits</label>
                    <input
                        type="number"
                        name="credits"
                        min="0"
                        value="{{ old('credits', $course->credits) }}"
                        style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                    >
                    @error('credits') <div style="color:#b00020; font-size:.9rem;">{{ $message }}</div> @enderror
                </div>

                <div>
                    <label style="font-weight:600;">Status</label>
                    <select
                        name="status"
                        style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;"
                    >
                        <option value="active" {{ old('status', $course->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $course->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div style="color:#b00020; font-size:.9rem;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display:flex; gap:.75rem; margin-top:.5rem;">
                <a href="{{ route('teacher.courses') }}" class="btn">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
