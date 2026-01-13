// Authentication JavaScript
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content")

document.getElementById("loginForm")?.addEventListener("submit", function (e) {
  e.preventDefault()

  const formData = new FormData(this)

  fetch(this.action, {
    method: "POST",
    body: formData,
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        window.location.href = data.redirect
      } else {
        alert(data.message || "Login failed")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("An error occurred during login")
    })
})

document.getElementById("registerForm")?.addEventListener("submit", function (e) {
  e.preventDefault()

  const password = document.getElementById("password").value
  const confirmPassword = document.getElementById("password_confirmation").value

  if (password !== confirmPassword) {
    alert("Passwords do not match!")
    return
  }

  const formData = new FormData(this)

  fetch(this.action, {
    method: "POST",
    body: formData,
    headers: {
      "X-CSRF-TOKEN": csrfToken,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Registration successful! Please login.")
        window.location.href = data.redirect || "/login"
      } else {
        alert(data.message || "Registration failed")
      }
    })
    .catch((error) => {
      console.error("Error:", error)
      alert("An error occurred during registration")
    })
})
