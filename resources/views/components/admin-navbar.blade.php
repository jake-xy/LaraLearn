@props(['selectedItem' => 'dashboard'])

<a href="{{ route('admin.dashboard') }}" class="nav-item {{ $selectedItem == 'dashboard' ? 'active' : ''}}">
    <span class="icon">📊</span>
    Dashboard
</a>
<a href="{{ route('admin.students') }}" class="nav-item {{ $selectedItem == 'students' ? 'active' : ''}}">
    <span class="icon">👥</span>
    Students
</a>
<a href="{{ route('admin.teachers') }}" class="nav-item {{ $selectedItem == 'teachers' ? 'active' : ''}}">
    <span class="icon">👨‍🏫</span>
    Teachers
</a>
<a href="{{ route('admin.courses') }}" class="nav-item {{ $selectedItem == 'courses' ? 'active' : ''}}">
    <span class="icon">📚</span>
    Courses
</a>
{{-- <a href="{{ route('admin.enrollments') }}" class="nav-item {{ $selectedItem == 'enrollment' ? 'active' : ''}}">
    <span class="icon">📝</span>
    Enrollments
</a> --}}
{{-- <a href="{{ route('admin.reports') }}" class="nav-item {{ $selectedItem == 'reports' ? 'active' : ''}}">
    <span class="icon">📈</span>
    Reports
</a> --}}
