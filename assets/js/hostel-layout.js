// Hostel Layout Interactive Navigation
class HostelLayout {
  constructor() {
    this.currentView = "hostels"
    this.selectedHostel = null
    this.selectedFloor = null
    this.selectedRoom = null
    this.container = document.getElementById("hostel-layout-container")

    if (this.container) {
      this.loadHostels()
    }
  }

  async loadHostels() {
    try {
      const response = await fetch("../api/get_hostels.php")
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const hostels = await response.json()
      this.renderHostels(hostels)
    } catch (error) {
      console.error("Error loading hostels:", error)
      this.container.innerHTML = `
        <div class="error-message">
          <p>❌ Error loading hostels: ${error.message}</p>
          <button onclick="hostelLayout.loadHostels()" class="btn btn-primary">Retry</button>
        </div>
      `
    }
  }

  renderHostels(hostels) {
    this.container.innerHTML = `
      <div class="hostel-layout">
        <h2 class="section-title text-center mb-4">Select Hostel</h2>
        <div class="hostel-grid">
          ${hostels
            .map(
              (hostel) => `
            <div class="interactive-card hostel-card" onclick="hostelLayout.selectHostel(${hostel.id}, '${hostel.name}')">
              <div class="hostel-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M3 21h18"/>
                  <path d="M5 21V7l8-4v18"/>
                  <path d="m14 10 4 4 4-4"/>
                </svg>
              </div>
              <h3>${hostel.name}</h3>
              <div class="hostel-stats">
                <div>
                  <div class="number">${hostel.total_floors}</div>
                  <div class="label">Floors</div>
                </div>
                <div>
                  <div class="number">${hostel.total_floors * hostel.rooms_per_floor}</div>
                  <div class="label">Rooms</div>
                </div>
                <div>
                  <div class="number">${hostel.total_floors * hostel.rooms_per_floor * hostel.beds_per_room}</div>
                  <div class="label">Beds</div>
                </div>
              </div>
              <button class="btn btn-primary" style="margin-top: 1rem; width: 100%;">
                Explore ${hostel.name}
              </button>
            </div>
          `,
            )
            .join("")}
        </div>
      </div>
    `
  }

  selectHostel(hostelId, hostelName) {
    this.selectedHostel = { id: hostelId, name: hostelName }
    this.currentView = "floors"
    this.renderFloors()
  }

  renderFloors() {
    this.container.innerHTML = `
      <div class="hostel-layout">
        <div class="navigation-header">
          <button class="back-button" onclick="hostelLayout.goBack()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="15,18 9,12 15,6"/>
            </svg>
            Back to Hostels
          </button>
          <h2 class="section-title">${this.selectedHostel.name} - Select Floor</h2>
        </div>
        <div class="floor-grid">
          ${[1, 2, 3]
            .map(
              (floor) => `
            <div class="interactive-card floor-card" onclick="hostelLayout.selectFloor(${floor})">
              <h3>Floor ${floor}</h3>
              <div class="floor-number">${floor}</div>
              <p style="color: #6b7280; margin: 0.5rem 0;">
                Rooms ${floor}01 - ${floor}20
              </p>
              <p style="color: #9ca3af; font-size: 0.875rem;">20 rooms • 80 beds</p>
            </div>
          `,
            )
            .join("")}
        </div>
      </div>
    `
  }

  async selectFloor(floor) {
    this.selectedFloor = floor
    this.currentView = "rooms"
    await this.loadRooms()
  }

  async loadRooms() {
    try {
      this.container.innerHTML = `
        <div class="hostel-layout">
          <div class="navigation-header">
            <button class="back-button" onclick="hostelLayout.goBack()">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15,18 9,12 15,6"/>
              </svg>
              Back to Floors
            </button>
            <h2 class="section-title">${this.selectedHostel.name} - Floor ${this.selectedFloor}</h2>
          </div>
          <div class="loading">Loading rooms...</div>
        </div>
      `

      const response = await fetch(
        `../api/get_rooms.php?hostel_id=${this.selectedHostel.id}&floor=${this.selectedFloor}`,
      )
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const rooms = await response.json()
      this.renderRooms(rooms)
    } catch (error) {
      console.error("Error loading rooms:", error)
      this.container.innerHTML = `
        <div class="error-message">
          <p>❌ Error loading rooms: ${error.message}</p>
          <button onclick="hostelLayout.selectFloor(${this.selectedFloor})" class="btn btn-primary">Retry</button>
        </div>
      `
    }
  }

  renderRooms(rooms) {
    this.container.innerHTML = `
      <div class="hostel-layout">
        <div class="navigation-header">
          <button class="back-button" onclick="hostelLayout.goBack()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="15,18 9,12 15,6"/>
            </svg>
            Back to Floors
          </button>
          <h2 class="section-title">${this.selectedHostel.name} - Floor ${this.selectedFloor}</h2>
        </div>
        <div class="room-grid">
          ${rooms
            .map(
              (room) => `
            <div class="interactive-card room-card" onclick="hostelLayout.selectRoom(${room.id}, '${room.room_number}')">
              <h3>Room ${room.room_number}</h3>
              <div class="room-occupancy">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                  <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>${room.occupied_beds || 0}/${room.capacity}</span>
              </div>
              <div class="badge ${(room.occupied_beds || 0) === room.capacity ? "badge-danger" : "badge-success"}">
                ${(room.occupied_beds || 0) === room.capacity ? "Full" : "Available"}
              </div>
            </div>
          `,
            )
            .join("")}
        </div>
      </div>
    `
  }

  async selectRoom(roomId, roomNumber) {
    this.selectedRoom = { id: roomId, number: roomNumber }
    this.currentView = "room-details"
    await this.loadRoomDetails()
  }

  async loadRoomDetails() {
    try {
      this.container.innerHTML = `
        <div class="hostel-layout">
          <div class="navigation-header">
            <button class="back-button" onclick="hostelLayout.goBack()">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15,18 9,12 15,6"/>
              </svg>
              Back to Rooms
            </button>
            <h2 class="section-title">${this.selectedHostel.name} - Room ${this.selectedRoom.number}</h2>
          </div>
          <div class="loading">Loading room details...</div>
        </div>
      `

      const response = await fetch(`../api/get_roommates.php?room_id=${this.selectedRoom.id}`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }
      const roommates = await response.json()
      this.renderRoomDetails(roommates)
    } catch (error) {
      console.error("Error loading room details:", error)
      this.container.innerHTML = `
        <div class="error-message">
          <p>❌ Error loading room details: ${error.message}</p>
          <button onclick="hostelLayout.selectRoom(${this.selectedRoom.id}, '${this.selectedRoom.number}')" class="btn btn-primary">Retry</button>
        </div>
      `
    }
  }

  renderRoomDetails(roommates) {
    this.container.innerHTML = `
      <div class="hostel-layout">
        <div class="navigation-header">
          <button class="back-button" onclick="hostelLayout.goBack()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="15,18 9,12 15,6"/>
            </svg>
            Back to Rooms
          </button>
          <h2 class="section-title">${this.selectedHostel.name} - Room ${this.selectedRoom.number}</h2>
        </div>
        <div class="bed-grid">
          ${[1, 2, 3, 4]
            .map((bedNumber) => {
              const student = roommates.find((s) => s.bed_number == bedNumber)
              return `
                <div class="bed-card ${student ? "occupied" : "available"}">
                  <div class="bed-header">
                    <div class="bed-title">
                      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 4v16"/>
                        <path d="M2 8h18a2 2 0 0 1 2 2v10"/>
                        <path d="M2 17h20"/>
                        <path d="M6 8V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v4"/>
                      </svg>
                      Bed ${bedNumber}
                    </div>
                    <div class="badge ${student ? "badge-success" : "badge-secondary"}">
                      ${student ? "Occupied" : "Available"}
                    </div>
                  </div>
                  <div class="bed-content">
                    ${
                      student
                        ? `
                      <div class="student-info">
                        <div class="student-name">
                          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                          </svg>
                          ${student.full_name}
                        </div>
                        <div class="student-details">
                          <p>${student.course_name}</p>
                          <p>${student.email}</p>
                          ${student.phone ? `<p>${student.phone}</p>` : ""}
                        </div>
                      </div>
                    `
                        : '<p style="color: #6b7280;">No student assigned</p>'
                    }
                  </div>
                </div>
              `
            })
            .join("")}
        </div>
      </div>
    `
  }

  goBack() {
    if (this.currentView === "room-details") {
      this.currentView = "rooms"
      this.selectedRoom = null
      this.loadRooms()
    } else if (this.currentView === "rooms") {
      this.currentView = "floors"
      this.selectedFloor = null
      this.renderFloors()
    } else if (this.currentView === "floors") {
      this.currentView = "hostels"
      this.selectedHostel = null
      this.loadHostels()
    }
  }
}

// Initialize the hostel layout when the page loads
document.addEventListener("DOMContentLoaded", () => {
  if (document.getElementById("hostel-layout-container")) {
    window.hostelLayout = new HostelLayout()
  }
})
