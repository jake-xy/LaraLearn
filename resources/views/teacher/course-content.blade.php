@extends('layouts.app')

@section('title', 'Course Content')
@section('portal-name', 'Teacher Portal')

@section('navigation')
<a href="{{ route('teacher.dashboard') }}" class="nav-item">
    <span class="icon">🏠</span> Dashboard
</a>

<a href="{{ route('teacher.courses') }}" class="nav-item active">
    <span class="icon">📚</span> My Courses
</a>

<a href="{{ route('teacher.content-upload') }}" class="nav-item">
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
<header class="page-header">
    <div>
        <h1>{{ $course->title }}</h1>
        <p>{{ $course->description ?? 'No description provided.' }}</p>
    </div>
</header>

{{-- ✅ Show success message after upload/update/delete --}}
@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="content-section">
    <h2>Course Info</h2>
    <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-top:1rem;">
        <div style="border:1px solid #eee; border-radius:14px; padding:1rem; min-width:240px;">
            <div style="opacity:.8;">Status</div>
            <div style="font-weight:600;">{{ $course->status ?? 'active' }}</div>
        </div>

        @if(!empty($course->code))
            <div style="border:1px solid #eee; border-radius:14px; padding:1rem; min-width:240px;">
                <div style="opacity:.8;">Join Code</div>
                <div style="font-weight:700; letter-spacing:.5px;">{{ $course->code }}</div>
            </div>
        @endif
    </div>
</div>

<div class="content-section">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2 style="margin:0;">Lessons</h2>

        <a class="btn btn-primary" href="{{ route('teacher.content-upload') }}">
            + Add Lesson / Upload Content
        </a>
    </div>

    @php $lessons = $lessons ?? collect(); @endphp

    @if($lessons->isEmpty())
        <p style="opacity:.85; margin-top:1rem;">No lessons yet. Upload course materials to get started.</p>
    @else
        <div class="table-container" style="margin-top:1rem;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Lesson</th>
                        <th>Description</th>
                        <th>Date Added</th>
                        <th>Actions</th> {{-- moved inside the table --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach($lessons as $lesson)
                        <tr>
                            <td><strong>{{ $lesson->title ?? 'Untitled Lesson' }}</strong></td>
                            <td>{{ $lesson->description ?? '—' }}</td>
                            <td>{{ optional($lesson->created_at)->format('M d, Y') ?? '—' }}</td>

                            <td style="display:flex; gap:.5rem; flex-wrap:wrap;">
                                <a class="btn btn-secondary"
                                   href="{{ route('teacher.content.download', $lesson->id) }}">
                                    View / Download
                                </a>

                                <a class="btn btn-primary"
                                   href="{{ route('teacher.content.edit', $lesson->id) }}">
                                    Edit
                                </a>

                                <form action="{{ route('teacher.content.delete', $lesson->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this lesson?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    @endif
</div>
@endsection
