// Teacher Dashboard JavaScript
document.addEventListener("DOMContentLoaded", () => {
  loadTeacherStats()
})

async function loadTeacherStats() {
  try {
    const response = await fetch("/api/teacher/stats")
    const data = await response.json()

    document.getElementById("myCourses").textContent = data.courses || 0
    document.getElementById("myStudents").textContent = data.students || 0
    document.getElementById("pendingSubmissions").textContent = data.pending_submissions || 0
    document.getElementById("gradedThisWeek").textContent = data.graded_this_week || 0
  } catch (error) {
    console.error("Error loading stats:", error)
  }
}
