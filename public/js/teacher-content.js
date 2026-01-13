// Teacher Content Upload
document.addEventListener("DOMContentLoaded", () => {
  loadCourses()
  loadRecentContent()

  document.getElementById("contentType").addEventListener("change", function () {
    const dueDateGroup = document.getElementById("dueDateGroup")
    dueDateGroup.style.display = this.value === "assignment" ? "block" : "none"
  })
})

async function loadCourses() {
  try {
    const response = await fetch("/api/teacher/courses")
    const courses = await response.json()

    const select = document.getElementById("course")
    select.innerHTML =
      '<option value="">Choose a course</option>' +
      courses.map((course) => `<option value="${course.id}">${course.title}</option>`).join("")
  } catch (error) {
    console.error("Error loading courses:", error)
  }
}

async function loadRecentContent() {
  try {
    const response = await fetch("/api/teacher/content/recent")
    const content = await response.json()

    const contentList = document.getElementById("contentList")
    contentList.innerHTML = content
      .map(
        (item) => `
            <div class="content-item">
                <h4>${item.title}</h4>
                <p>${item.course_name} - ${item.content_type}</p>
                <span>${item.uploaded_at}</span>
            </div>
        `,
      )
      .join("")
  } catch (error) {
    console.error("Error loading content:", error)
  }
}

//
