@extends('layouts.app')

@section('title', 'Edit Lesson')
@section('portal-name', 'Teacher Portal')

@section('content')
<header class="page-header">
    <div>
        <h1>Edit Lesson</h1>
        <p>Update lesson title/description</p>
    </div>
</header>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:1.25rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="content-section">
    <form method="POST" action="{{ route('teacher.content.update', $content->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Title</label>
            <input type="text" name="title" value="{{ old('title', $content->title) }}" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4">{{ old('description', $content->description) }}</textarea>
        </div>

        <button class="btn btn-primary" type="submit">Save Changes</button>
        <a class="btn btn-secondary" href="{{ route('teacher.courses.show', $content->course_id) }}">Cancel</a>
    </form>
</div>
@endsection
