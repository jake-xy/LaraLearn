// Admin Courses Management
document.addEventListener("DOMContentLoaded", () => {
  loadCourses()
  loadTeachers()
})

async function loadCourses() {
  try {
    const response = await fetch("/api/admin/courses")
    const courses = await response.json()

    renderCoursesGrid(courses)
  } catch (error) {
    console.error("Error loading courses:", error)
  }
}

function renderCoursesGrid(courses) {
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
            <div class="course-footer">
                <span>Teacher: ${course.teacher_name}</span>
                <div>
                    <button onclick="editCourse(${course.id})" class="btn btn-secondary">Edit</button>
                    <button onclick="deleteCourse(${course.id})" class="btn btn-secondary">Delete</button>
                </div>
            </div>
        </div>
    `,
    )
    .join("")
}

async function loadTeachers() {
  try {
    const response = await fetch("/api/admin/teachers")
    const teachers = await response.json()

    const select = document.getElementById("courseTeacher")
    select.innerHTML =
      '<option value="">Select a teacher</option>' +
      teachers.map((teacher) => `<option value="${teacher.id}">${teacher.name}</option>`).join("")
  } catch (error) {
    console.error("Error loading teachers:", error)
  }
}

function openAddCourseModal() {
  document.getElementById("addCourseModal").classList.add("active")
}

function closeAddCourseModal() {
  document.getElementById("addCourseModal").classList.remove("active")
}

function editCourse(id) {
  console.log("Edit course:", id)
}

function deleteCourse(id) {
  if (confirm("Are you sure you want to delete this course?")) {
    fetch(`/api/admin/courses/${id}`, {
      method: "DELETE",
    }).then(() => loadCourses())
  }
}

document.getElementById("addCourseForm").addEventListener("submit", async function (e) {
  e.preventDefault()

  const formData = new FormData(this)

  try {
    const response = await fetch("/admin/courses/add", {
      method: "POST",
      body: formData,
    })

    if (response.ok) {
      closeAddCourseModal()
      loadCourses()
      this.reset()
    }
  } catch (error) {
    console.error("Error adding course:", error)
  }
})
