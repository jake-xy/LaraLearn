// Admin Dashboard JavaScript
document.addEventListener("DOMContentLoaded", () => {
  loadDashboardStats()
  loadRecentActivity()
})

async function loadDashboardStats() {
  try {
    // Replace with your Laravel API endpoint
    const response = await fetch("/api/admin/stats")
    const data = await response.json()

    document.getElementById("totalStudents").textContent = data.students || 0
    document.getElementById("totalTeachers").textContent = data.teachers || 0
    document.getElementById("totalCourses").textContent = data.courses || 0
    document.getElementById("totalEnrollments").textContent = data.enrollments || 0
  } catch (error) {
    console.error("Error loading stats:", error)
  }
}

async function loadRecentActivity() {
  try {
    const response = await fetch("/api/admin/activity")
    const activities = await response.json()

    const activityList = document.getElementById("activityList")
    activityList.innerHTML = activities
      .map(
        (activity) => `
            <div class="activity-item">
                <p><strong>${activity.user}</strong> ${activity.action}</p>
                <span class="activity-time">${activity.time}</span>
            </div>
        `,
      )
      .join("")
  } catch (error) {
    console.error("Error loading activity:", error)
  }
}
