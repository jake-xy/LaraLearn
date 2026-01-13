// Student Dashboard JavaScript
document.addEventListener("DOMContentLoaded", () => {
  loadStudentStats()
  loadMyCourses()
  loadUpcomingAssignments()
})

async function loadStudentStats() {
  try {
    const response = await fetch("/api/student/stats")
    const data = await response.json()

    document.getElementById("enrolledCourses").textContent = data.enrolled_courses || 0
    document.getElementById("pendingAssignments").textContent = data.pending_assignments || 0
    document.getElementById("completedAssignments").textContent = data.completed_assignments || 0
    document.getElementById("averageGrade").textContent = (data.average_grade || 0) + "%"
  } catch (error) {
    console.error("Error loading stats:", error)
  }
}

async function loadMyCourses() {
  try {
    const response = await fetch("/api/student/courses")
    const courses = await response.json()

    const grid = document.getElementById("coursesGrid")
    grid.innerHTML = courses
      .map(
        (course) => `
            <div class="course-card">
                <div class="course-header">
                    <h3 class="course-title">${course.title}</h3>
                    <p class="course-code">${course.code}</p>
                </div>
                <p class="course-description">${course.description}</p>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: ${course.progress}%"></div>
                </div>
                <p style="font-size: 0.875rem; color: var(--color-text-light); margin-top: 0.5rem;">
                    Progress: ${course.progress}%
                </p>
            </div>
        `,
      )
      .join("")
  } catch (error) {
    console.error("Error loading courses:", error)
  }
}

async function loadUpcomingAssignments() {
  try {
    const response = await fetch("/api/student/assignments/upcoming")
    const assignments = await response.json()

    const list = document.getElementById("assignmentsList")
    list.innerHTML = assignments
      .map(
        (assignment) => `
            <div class="assignment-item" style="padding: 1rem; border-bottom: 1px solid var(--color-border);">
                <h4 style="margin-bottom: 0.5rem;">${assignment.title}</h4>
                <p style="font-size: 0.875rem; color: var(--color-text-light);">
                    ${assignment.course_name} - Due: ${assignment.due_date}
                </p>
                <a href="/student/assignments/${assignment.id}" class="btn btn-primary" style="margin-top: 0.5rem;">Submit</a>
            </div>
        `,
      )
      .join("")
  } catch (error) {
    console.error("Error loading assignments:", error)
  }
}
