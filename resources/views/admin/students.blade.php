@extends('layouts.app')

@section('title', 'Manage Students')

@section('portal-name', 'Admin Panel')

@section('navigation')
<a href="{{ route('admin.dashboard') }}" class="nav-item">
    <span class="icon">📊</span>
    Dashboard
</a>
<a href="{{ route('admin.students.index') }}" class="nav-item active">
    <span class="icon">👥</span>
    Students
</a>
<a href="{{ route('admin.teachers.index') }}" class="nav-item">
    <span class="icon">👨‍🏫</span>
    Teachers
</a>
<a href="{{ route('admin.courses.index') }}" class="nav-item">
    <span class="icon">📚</span>
    Courses
</a>
<a href="{{ route('admin.enrollments.index') }}" class="nav-item">
    <span class="icon">📝</span>
    Enrollments
</a>
<a href="{{ route('admin.reports') }}" class="nav-item">
    <span class="icon">📈</span>
    Reports
</a>
@endsection

@section('content')
<header class="page-header">
    <div>
        <h1>Manage Students</h1>
        <p>View and manage student accounts</p>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="openAddStudentModal()">+ Add Student</button>
    </div>
</header>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Search and Filter -->
<div class="table-controls">
    <input type="text" id="searchStudent" placeholder="Search students..." class="search-input">
    <select id="filterStatus" class="filter-select">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
</div>

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
                        <button onclick="editStudent({{ $student->id }})" class="btn btn-secondary">Edit</button>
                        <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure?')">Delete</button>
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
