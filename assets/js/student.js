// Student Dashboard JavaScript Functions

// Modal Functions
function showLeaveForm() {
  document.getElementById("leaveModal").style.display = "flex"
  // Set minimum date to today
  const today = new Date().toISOString().split("T")[0]
  document.getElementById("start_date").min = today
  document.getElementById("end_date").min = today
}

function hideLeaveForm() {
  document.getElementById("leaveModal").style.display = "none"
  document.getElementById("leaveForm").reset()
}

function showComplaintForm() {
  document.getElementById("complaintModal").style.display = "flex"
}

function hideComplaintForm() {
  document.getElementById("complaintModal").style.display = "none"
  document.getElementById("complaintForm").reset()
}

// Date validation for leave form
document.addEventListener("DOMContentLoaded", () => {
  const startDateInput = document.getElementById("start_date")
  const endDateInput = document.getElementById("end_date")

  if (startDateInput && endDateInput) {
    startDateInput.addEventListener("change", function () {
      endDateInput.min = this.value
      if (endDateInput.value && endDateInput.value < this.value) {
        endDateInput.value = this.value
      }
    })
  }

  // Handle form submissions
  const leaveForm = document.getElementById("leaveForm")
  if (leaveForm) {
    leaveForm.addEventListener("submit", (e) => {
      e.preventDefault()
      submitLeaveRequest()
    })
  }

  const complaintForm = document.getElementById("complaintForm")
  if (complaintForm) {
    complaintForm.addEventListener("submit", (e) => {
      e.preventDefault()
      submitComplaint()
    })
  }
})

// Submit leave request
async function submitLeaveRequest() {
  const form = document.getElementById("leaveForm")
  const formData = new FormData(form)

  try {
    const response = await fetch("../api/submit_leave.php", {
      method: "POST",
      body: formData,
    })

    const result = await response.json()

    if (result.success) {
      alert("Leave request submitted successfully!")
      hideLeaveForm()
      location.reload() // Refresh to show new leave request
    } else {
      alert("Error: " + result.message)
    }
  } catch (error) {
    alert("An error occurred while submitting the leave request.")
    console.error("Error:", error)
  }
}

// Submit complaint
async function submitComplaint() {
  const form = document.getElementById("complaintForm")
  const formData = new FormData(form)

  try {
    const response = await fetch("../api/submit_complaint.php", {
      method: "POST",
      body: formData,
    })

    const result = await response.json()

    if (result.success) {
      alert("Complaint submitted successfully!")
      hideComplaintForm()
      location.reload() // Refresh to show new complaint
    } else {
      alert("Error: " + result.message)
    }
  } catch (error) {
    alert("An error occurred while submitting the complaint.")
    console.error("Error:", error)
  }
}

// Close modals when clicking outside
window.addEventListener("click", (event) => {
  const leaveModal = document.getElementById("leaveModal")
  const complaintModal = document.getElementById("complaintModal")

  if (event.target === leaveModal) {
    hideLeaveForm()
  }

  if (event.target === complaintModal) {
    hideComplaintForm()
  }
})

// Close modals with Escape key
document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    hideLeaveForm()
    hideComplaintForm()
  }
})
