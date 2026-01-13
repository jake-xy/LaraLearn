@extends('layouts.app')

@section('title', 'Manage Students')

@section('portal-name', 'Admin Panel')

@section('navigation')
    <x-admin-navbar selectedItem='teachers'></x-admin-navbar>
@endsection

@section('content')
    <header class="page-header">
        <div>
            <h1>Manage Teacher</h1>
            <p>View and manage teacher accounts</p>
        </div>
        <div class="header-actions">
            <span class="user-info">{{ Auth::user()->email }}</span>
        </div>  
    </header>


    <!-- Search and Filter -->
    <form action="{{ route('admin.teachers') }}" method="GET" style="display: inline;">
        <div class="table-controls">
            <input type="text" name="search" id="searchTeacher" value="{{ request('search') }}" placeholder="Search teachers..." class="search-input">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.teachers') }}" class="btn btn-secondary">Clear</a>
            @endif
        </div>
    </form>

    <!-- Teachers Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Enrolled Courses</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentsTableBody">
                @forelse($teachers ?? [] as $teacher)
                    <tr>
                        <td>{{ $teacher->id }}</td>
                        <td>{{ $teacher->name }}</td>
                        <td>{{ $teacher->email }}</td>
                        <td>{{ $teacher->taughtCourses()->count() }}</td>
                        <td>
                            <form action="{{ route('admin.teachers.delete', $teacher->id) }}" method="POST" style="display: inline;">
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