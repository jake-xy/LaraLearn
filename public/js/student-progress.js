// Student Progress Tracking
document.addEventListener("DOMContentLoaded", () => {
  loadOverallProgress()
  loadCourseProgress()
  loadRecentGrades()
})

async function loadOverallProgress() {
  try {
    const response = await fetch("/api/student/progress/overall")
    const data = await response.json()

    const completionRate = data.completion_rate || 0
    document.getElementById("completionRate").style.width = completionRate + "%"
    document.getElementById("completionValue").textContent = completionRate + "%"
  } catch (error) {
    console.error("Error loading progress:", error)
  }
}

async function loadCourseProgress() {
  try {
    const response = await fetch("/api/student/progress/courses")
    const courses = await response.json()

    const list = document.getElementById("courseProgressList")
    list.innerHTML = courses
      .map(
        (course) => `
            <div class="progress-course-item" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span style="font-weight: 500;">${course.title}</span>
                    <span>${course.progress}%</span>
                </div>
                <div class="progress-bar-container">
                    <div class="progress-bar" style="width: ${course.progress}%"></div>
                </div>
            </div>
        `,
      )
      .join("")
  } catch (error) {
    console.error("Error loading course progress:", error)
  }
}

async function loadRecentGrades() {
  try {
    const response = await fetch("/api/student/grades/recent")
    const grades = await response.json()

    const tbody = document.getElementById("recentGradesBody")
    tbody.innerHTML = grades
      .map(
        (grade) => `
            <tr>
                <td>${grade.assignment_title}</td>
                <td>${grade.course_name}</td>
                <td><span class="badge badge-${grade.grade >= 70 ? "success" : "warning"}">${grade.grade}%</span></td>
                <td>${grade.graded_at}</td>
            </tr>
        `,
      )
      .join("")
  } catch (error) {
    console.error("Error loading grades:", error)
  }
}
