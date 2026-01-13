// Teacher Grading Dashboard
document.addEventListener("DOMContentLoaded", () => {
  loadSubmissions()
  loadCourseFilter()

  document.getElementById("filterCourse").addEventListener("change", loadSubmissions)
  document.getElementById("filterStatus").addEventListener("change", loadSubmissions)
})

async function loadCourseFilter() {
  try {
    const response = await fetch("/api/teacher/courses")
    const courses = await response.json()

    const select = document.getElementById("filterCourse")
    select.innerHTML =
      '<option value="">All Courses</option>' +
      courses.map((course) => `<option value="${course.id}">${course.title}</option>`).join("")
  } catch (error) {
    console.error("Error loading courses:", error)
  }
}

async function loadSubmissions() {
  const courseId = document.getElementById("filterCourse").value
  const status = document.getElementById("filterStatus").value

  try {
    const response = await fetch(`/api/teacher/submissions?course=${courseId}&status=${status}`)
    const submissions = await response.json()

    renderSubmissionsTable(submissions)
  } catch (error) {
    console.error("Error loading submissions:", error)
  }
}

function renderSubmissionsTable(submissions) {
  const tbody = document.getElementById("submissionsTableBody")
  tbody.innerHTML = submissions
    .map(
      (sub) => `
        <tr>
            <td>${sub.student_name}</td>
            <td>${sub.course_name}</td>
            <td>${sub.assignment_title}</td>
            <td>${sub.submitted_at}</td>
            <td><span class="badge badge-${sub.status === "graded" ? "success" : "warning"}">${sub.status}</span></td>
            <td>${sub.grade || "-"}</td>
            <td>
                <button onclick="openGradingModal(${sub.id})" class="btn btn-primary">Grade</button>
            </td>
        </tr>
    `,
    )
    .join("")
}

function openGradingModal(submissionId) {
  fetch(`/api/teacher/submissions/${submissionId}`)
    .then((response) => response.json())
    .then((submission) => {
      document.getElementById("submissionId").value = submission.id
      document.getElementById("submissionTitle").textContent = submission.assignment_title
      document.getElementById("submissionStudent").textContent = submission.student_name
      document.getElementById("submissionDate").textContent = submission.submitted_at
      document.getElementById("submissionFileLink").href = submission.file_url

      document.getElementById("gradingModal").classList.add("active")
    })
}

function closeGradingModal() {
  document.getElementById("gradingModal").classList.remove("active")
  document.getElementById("gradingForm").reset()
}

document.getElementById("gradingForm").addEventListener("submit", async function (e) {
  e.preventDefault()

  const formData = new FormData(this)

  try {
    const response = await fetch("/teacher/grading/submit", {
      method: "POST",
      body: formData,
    })

    if (response.ok) {
      alert("Grade submitted successfully!")
      closeGradingModal()
      loadSubmissions()
    }
  } catch (error) {
    console.error("Error submitting grade:", error)
  }
})
