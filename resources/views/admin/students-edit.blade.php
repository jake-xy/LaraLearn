@extends('layouts.app')

@section('title', 'Create Course')
@section('portal-name', 'Teacher Portal')

@section('navigation')
    <x-admin-navbar selectedItem='students'></x-admin-navbar>
@endsection

@section('content')
    <header class="page-header" style="display:flex; justify-content:space-between; align-items:flex-start; gap:1rem;">
        <div>
            <h1>Edit Student</h1>
            <p>Edit existing student's data</p>
        </div>

        <a href="{{ route('admin.students') }}" class="btn">← Back to Students</a>
    </header>

    <div class="content-section" style="max-width: 760px">
        @if (session('success update'))
            <div class="alert alert-success">{{ session('success update') }}</div>
        @endif
        <div style="border:1px solid #eee; border-radius:14px; padding:1.25rem;">
            <form action="{{ route('admin.students.update', $student) }}" method="POST">
                @csrf
                @method('PUT')
                <div style="display:grid; gap:.85rem;">
                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:.35rem;">Full Name</label>
                        <input type="text"
                            name="name"
                            value="{{ isset($student) ? $student->name : '' }}"
                            required
                            style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                        @error('title')
                            <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label style="display:block; font-weight:600; margin-bottom:.35rem;">E-mail</label>
                        <input type="email"
                            name="email"
                            value="{{ isset($student) ? $student->email : '' }}"
                            required
                            style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                        @error('title')
                            <div style="color:#b00020; font-size:.9rem; margin-top:.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="content-section" style="max-width: 760px">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div>
            <label style="display:block; font-weight:600; margin-bottom:.35rem;">Enrolled Courses | Units</label>
            <div style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px;">
                @foreach ($student->enrolledCourses()->get() as $course)
                    <form action="{{ route('admin.students.edit.delete-course', [$student, $course]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px; margin-bottom:0.5rem; display: flex; justify-content: space-between;">
                            <div>{{$course->title}} | {{$course->credits}}</div>
                            <button type="submit" style="margin-left:0.5rem; background:none; border:none; padding: none; cursor: pointer;" onclick="return confirm('Are you sure? This action is irreversible')">⛔</button>
                        </div>
                    </form>
                @endforeach
                <div style="margin-bottom: 0.5rem">
                    Total Units: {{ $student->enrolledCourses()->pluck('credits')->sum() }}
                </div>
                
                <form action="{{ route('admin.students.edit.add-course', $student) }}" method="POST">
                    @csrf
                    <div class="form-group" style="width:100%; padding:.7rem .9rem; border:1px solid #ddd; border-radius:10px; margin-bottom:0.5rem; display: flex; justify-content: center; align-items:center;">
                        <select name="addedItemId" id="" required>
                            <option value="" selected disabled>Select course to enroll for this student</option>
                            @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{$student->enrolledCourses()->get()->contains($course) ? 'disabled' : ''}}>{{ $course->title }} | {{$course->credits}}</option>
                            @endforeach
                        </select>
                        <button type="submit" style="margin-left:0.5rem; background:none; border:none; padding: none; cursor: pointer;">➕</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection