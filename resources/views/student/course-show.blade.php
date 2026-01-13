@extends('layouts.app')

@section('title', 'Course')

@section('portal-name', 'Student Portal')

@section('navigation')
<a href="{{ route('student.dashboard') }}" class="nav-item">
    <span class="icon">📊</span> Dashboard
</a>
<a href="{{ route('student.courses') }}" class="nav-item active">
    <span class="icon">📚</span> My Courses
</a>
<a href="{{ route('student.assignments') }}" class="nav-item">
    <span class="icon">📝</span> Assignments
</a>
<a href="{{ route('student.grades') }}" class="nav-item">
    <span class="icon">📈</span> Grades
</a>
<a href="{{ route('student.progress') }}" class="nav-item">
    <span class="icon">🎯</span> Progress
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>{{ $course->title }}</h1>
        <p>{{ $course->description }}</p>
    </div>

    <div>
        <a href="{{ route('student.courses') }}" class="btn btn-secondary">Back</a>
    </div>
</header>

{{-- Assignments --}}
<div class="content-section">
    <h2>Lectures and Posts</h2>

    @if(($assignments ?? collect())->isEmpty())
        <p style="opacity:.8;">No assignments yet.</p>
    @else
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($assignments as $a)
                    @php
                        $submitted = $a->submissions && $a->submissions->isNotEmpty();
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $a->title }}</strong>
                            @if($a->description)
                                <div style="font-size:.9rem;opacity:.8;">{{ $a->description }}</div>
                            @endif
                        </td>
                        <td>{{ $a->due_date ? \Carbon\Carbon::parse($a->due_date)->format('M d, Y') : '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $submitted ? 'success' : 'warning' }}">
                                {{ $submitted ? 'Submitted' : 'Pending' }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm"
                               href="{{ route('student.assignments.show', $a->id) }}">
                                Open
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Course Content (Lectures / Posts / Readings / etc) --}}
<div class="content-section">
    <h2>Assignments</h2>

    @if(($contents ?? collect())->isEmpty())
        <p style="opacity:.8;">No content posted yet.</p>
    @else
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>File</th>
                        <th>Posted</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($contents as $c)
                    <tr>
                        <td><strong>{{ $c->title }}</strong></td>
                        <td>{{ $c->description ?? '—' }}</td>
                        <td>
                            @if($c->file_path)
                                <a class="btn btn-sm btn-secondary"
                                   href="{{ asset('storage/'.$c->file_path) }}"
                                   target="_blank">
                                    View
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ optional($c->created_at)->format('M d, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
