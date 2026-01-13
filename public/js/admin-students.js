// Admin Students Management
document.addEventListener("DOMContentLoaded", () => {
  loadStudents()

  document.getElementById("searchStudent").addEventListener("input", filterStudents)
  document.getElementById("filterStatus").addEventListener("change", filterStudents)
})

async function loadStudents() {
  try {
    const response = await fetch("/api/admin/students")
    const students = await response.json()

    renderStudentsTable(students)
  } catch (error) {
    console.error("Error loading students:", error)
  }
}

function renderStudentsTable(students) {
  const tbody = document.getElementById("studentsTableBody")
  tbody.innerHTML = students
    .map(
      (student) => `
        <tr>
            <td>${student.id}</td>
            <td>${student.name}</td>
            <td>${student.email}</td>
            <td>${student.enrolled_courses}</td>
            <td><span class="badge badge-${student.status === "active" ? "success" : "danger"}">${student.status}</span></td>
            <td>
                <button onclick="editStudent(${student.id})" class="btn btn-secondary">Edit</button>
                <button onclick="deleteStudent(${student.id})" class="btn btn-secondary">Delete</button>
            </td>
        </tr>
    `,
    )
    .join("")
}

function filterStudents() {
  const searchTerm = document.getElementById("searchStudent").value.toLowerCase()
  const statusFilter = document.getElementById("filterStatus").value

  // Implement filtering logic with your Laravel API
  loadStudents()
}

function openAddStudentModal() {
  document.getElementById("addStudentModal").classList.add("active")
}

function closeAddStudentModal() {
  document.getElementById("addStudentModal").classList.remove("active")
}

function editStudent(id) {
  // Implement edit functionality
  console.log("Edit student:", id)
}

function deleteStudent(id) {
  if (confirm("Are you sure you want to delete this student?")) {
    // Call Laravel API to delete student
    fetch(`/api/admin/students/${id}`, {
      method: "DELETE",
    }).then(() => loadStudents())
  }
}

// Form submission
document.getElementById("addStudentForm").addEventListener("submit", async function (e) {
  e.preventDefault()

  const formData = new FormData(this)

  try {
    const response = await fetch("/admin/students/add", {
      method: "POST",
      body: formData,
    })

    if (response.ok) {
      closeAddStudentModal()
      loadStudents()
      this.reset()
    }
  } catch (error) {
    console.error("Error adding student:", error)
  }
})
