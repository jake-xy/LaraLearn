@extends('layouts.app')

@section('title', 'Create Course')
@section('portal-name', 'Teacher Portal')

@section('navigation')
    <x-admin-navbar selectedItem='courses'></x-admin-navbar>
@endsection

@section('content')
<header class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
    <div>
        <h1>{{ isset($course) ? "Edit Course" : "Create Course" }}</h1>
        <p>
            {{ isset($course) ? 
                "Edit existing course's title, description, course code, credits, or assigned teacher" : 
                "Add a new course with title, description, credits, and assign the teacher." 
            }}
        </p>
    </div>

    <a href="{{ route('admin.courses') }}" class="btn">← Back to Courses</a>
</header>

<div class="content-section" style="max-width:760px;">
    <div style="border:1px solid #eee; border-radius:14px; padding:1.25rem;">
        <form method="POST" action="{{ isset($course) ? route('admin.courses.edit', $course) : route('admin.courses.store') }}">
            @method(isset($course) ? 'PUT' : 'POST')
            @csrf

            <div style="display:grid; gap:.85rem;">

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:.35rem;">Course Title</label>
                    <input type="text"
                           name="title"
                           value="{{ isset($course) ? $course->title : old('title') }}"
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
                              style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">{{ isset($course) ? $course->description : old('description') }}</textarea>
                    @error('description')
                        <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                @isset($course)
                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:.35rem;">Course Code</label>
                        <input type="text"
                            name="course_code"
                            value="{{ isset($course) ? $course->course_code : old('title') }}"
                            required
                            minlength="6"
                            maxlength="6"
                            style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                        @error('course_code')
                            <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                @endisset

                <div>
                    <label style="display:block; font-weight:600; margin-bottom:.35rem;">Credits</label>
                    <input type="number"
                           name="credits"
                           value="{{ isset($course) ? $course->credits : old('credits', 0) }}"
                           min="0"
                           style="width:220px; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                    @error('credits')
                        <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="courseTeacher">Assign Teacher</label>
                    <select id="courseTeacher" name="teacher_id" required>
                        <option value="" disabled selected>Select a teacher</option>
                        @foreach($teachers ?? [] as $teacher)
                            @if (isset($course) && $teacher->id == $selectedTeacherId)
                                <option value="{{ $teacher->id }}" selected>{{ $teacher->name }}</option>
                            @else
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div style="display:flex; gap:.6rem; margin-top:.25rem;">
                    <button type="submit" class="btn btn-primary">{{ isset($course) ? "Update Course" : "Create Course" }}</button>
                    <a href="{{ route('admin.courses') }}" class="btn">Cancel</a>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection
