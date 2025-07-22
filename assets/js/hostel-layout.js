// Hostel Layout Interactive Navigation
class HostelLayout {
  constructor() {
    this.currentView = "hostels"
    this.selectedHostel = null
    this.selectedFloor = null
    this.selectedRoom = null
    this.container = document.getElementById("hostel-layout-container")

    this.loadHostels()
  }

  async loadHostels() {
    try {
      const response = await fetch("../api/get_hostels.php")
      const hostels = await response.json()
      this.renderHostels(hostels)
    } catch (error) {
      console.error("Error loading hostels:", error)
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
    this.renderRooms()
  }

  renderRooms() {
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
                    ${[1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
                      .map(
                        (room) => `
                        <div class="interactive-card room-card" onclick="hostelLayout.selectRoom(${room})">
                            <h3>Room ${this.selectedFloor}${room.toString().padStart(2, "0")}</h3>
                            <div class="room-number">${this.selectedFloor}${room.toString().padStart(2, "0")}</div>
                            <p style="color: #9ca3af; font-size: 0.875rem;">4 beds</p>
                        </div>
                    `,
                      )
                      .join("")}
                </div>
            </div>
        `
  }

  selectRoom(room) {
    this.selectedRoom = room
    this.currentView = "room-details"
    this.renderRoomDetails()
  }

  renderRoomDetails() {
    this.container.innerHTML = `
            <div class="hostel-layout">
                <div class="navigation-header">
                    <button class="back-button" onclick="hostelLayout.goBack()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15,18 9,12 15,6"/>
                        </svg>
                        Back to Rooms
                    </button>
                    <h2 class="section-title">${this.selectedHostel.name} - Room ${this.selectedFloor}${this.selectedRoom.toString().padStart(2, "0")}</h2>
                </div>
                <div class="room-details">
                    <p>Room Number: ${this.selectedFloor}${this.selectedRoom.toString().padStart(2, "0")}</p>
                    <p>Beds: 4</p>
                </div>
            </div>
        `
  }

  goBack() {
    if (this.currentView === "floors") {
      this.currentView = "hostels"
      this.selectedHostel = null
      this.loadHostels()
    } else if (this.currentView === "rooms") {
      this.currentView = "floors"
      this.selectedRoom = null
      this.renderFloors()
    } else if (this.currentView === "room-details") {
      this.currentView = "rooms"
      this.renderRooms()
    }
  }
}
