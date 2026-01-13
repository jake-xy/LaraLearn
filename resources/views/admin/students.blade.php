@extends('layouts.app')

@section('title', 'Manage Students')

@section('portal-name', 'Admin Panel')

@section('navigation')
    <x-admin-navbar selectedItem='students'></x-admin-navbar>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Manage Students</h1>
        <p>View and manage student accounts</p>
    </div>
    {{-- <div class="header-actions">
        <button class="btn btn-primary" onclick="openAddStudentModal()">+ Add Student</button>
    </div> --}}
</header>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Search and Filter -->
<form action="{{ route('admin.students') }}" method="GET">
    <div class="table-controls">
        <input type="text" value="{{ request('search') }}" name='search' id="searchStudent" placeholder="Search students..." class="search-input">
        <button type="submit" class="btn btn-primary">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.students') }}" class="btn btn-secondary">Clear</a>
        @endif
    </div>
</form>

<!-- Students Table -->
<div class="table-container">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Enrolled Courses</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="studentsTableBody">
            @forelse($students ?? [] as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->enrollments->count() }}</td>
                    <td>
                        <span class="badge badge-{{ $student->status === 'active' ? 'success' : 'danger' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-secondary">Edit</a>
                        <form action="{{ route('admin.students.delete', $student->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure? This action cannot be undone')">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No students found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

<!-- Add Student Modal -->
@section('modals')
<div id="addStudentModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Student</h2>
            <button class="modal-close" onclick="closeAddStudentModal()">&times;</button>
        </div>
        <form id="addStudentForm" action="{{ route('admin.students.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="studentName">Full Name</label>
                <input type="text" id="studentName" name="name" required>
            </div>
            <div class="form-group">
                <label for="studentEmail">Email</label>
                <input type="email" id="studentEmail" name="email" required>
            </div>
            <div class="form-group">
                <label for="studentPassword">Password</label>
                <input type="password" id="studentPassword" name="password" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddStudentModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Student</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/admin-students.js') }}"></script>
@endpush
