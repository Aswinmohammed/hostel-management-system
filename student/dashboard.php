<?php
require_once '../includes/session.php';
require_once '../includes/hostel_functions.php';

requireStudent();

$hostelManager = new HostelManager();
$student = $hostelManager->getStudentByUserId($_SESSION['user_id']);

if (!$student) {
    echo "Student profile not found. Please contact administrator.";
    exit();
}

$announcements = $hostelManager->getAnnouncements();
$leaveRequests = $hostelManager->getStudentLeaves($student['id']);
$complaints = $hostelManager->getStudentComplaints($student['id']);

// Get roommates if student has a room
$roommates = [];
if ($student['room_id']) {
    $allRoommates = $hostelManager->getRoommates($student['room_id']);
    $roommates = array_filter($allRoommates, function($roommate) use ($student) {
        return $roommate['id'] != $student['id'];
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Hostel Management</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="dashboard">
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Student Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($student['full_name']); ?></p>
            </div>
            <a href="../logout.php" class="btn btn-outline">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16,17 21,12 16,7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </a>
        </div>
    </div>

    <div class="main-content">
        <!-- Student Profile Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h2 class="card-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    My Profile
                </h2>
            </div>
            <div class="card-content">
                <div class="profile-grid">
                    <div class="profile-item">
                        <h3>Full Name</h3>
                        <p><?php echo htmlspecialchars($student['full_name']); ?></p>
                    </div>
                    <div class="profile-item">
                        <h3>Course</h3>
                        <p><?php echo htmlspecialchars($student['course_name']); ?></p>
                    </div>
                    <div class="profile-item">
                        <h3>Email</h3>
                        <p><?php echo htmlspecialchars($student['email']); ?></p>
                    </div>
                    <div class="profile-item">
                        <h3>Phone</h3>
                        <p><?php echo htmlspecialchars($student['phone'] ?: 'Not provided'); ?></p>
                    </div>
                    <div class="profile-item">
                        <h3>Hostel</h3>
                        <p>
                            <?php if ($student['hostel_name']): ?>
                                <span class="flex items-center gap-2">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 21h18"/>
                                        <path d="M5 21V7l8-4v18"/>
                                    </svg>
                                    <?php echo htmlspecialchars($student['hostel_name']); ?>
                                </span>
                            <?php else: ?>
                                <span style="color: #6b7280;">Not assigned</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="profile-item">
                        <h3>Room</h3>
                        <p><?php echo $student['room_number'] ? 'Room ' . htmlspecialchars($student['room_number']) : 'Not assigned'; ?></p>
                    </div>
                    <div class="profile-item">
                        <h3>Bed</h3>
                        <p><?php echo $student['bed_number'] ? 'Bed ' . htmlspecialchars($student['bed_number']) : 'Not assigned'; ?></p>
                    </div>
                    <div class="profile-item">
                        <h3>Registration Date</h3>
                        <p><?php echo date('M j, Y', strtotime($student['created_at'])); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <ul class="tab-list">
                <li><button class="tab-button active" data-tab="announcements">Announcements</button></li>
                <li><button class="tab-button" data-tab="roommates">Roommates</button></li>
                <li><button class="tab-button" data-tab="leaves">My Leaves</button></li>
                <li><button class="tab-button" data-tab="complaints">My Complaints</button></li>
            </ul>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content active" id="announcements">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            Latest Announcements
                        </h2>
                        <p class="card-description">Stay updated with hostel and campus news</p>
                    </div>
                </div>
                <div class="card-content">
                    <?php if (!empty($announcements)): ?>
                        <div class="item-list">
                            <?php foreach ($announcements as $announcement): ?>
                            <div class="list-item">
                                <div class="list-item-header">
                                    <h3 class="list-item-title"><?php echo htmlspecialchars($announcement['title']); ?></h3>
                                    <span class="badge badge-info"><?php echo htmlspecialchars($announcement['category']); ?></span>
                                </div>
                                <div class="list-item-content">
                                    <?php echo htmlspecialchars($announcement['content']); ?>
                                </div>
                                <div class="list-item-meta">
                                    Posted on <?php echo date('M j, Y', strtotime($announcement['created_at'])); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center" style="color: #6b7280; padding: 2rem;">No announcements available</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="tab-content" id="roommates">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18"/>
                                <path d="M5 21V7l8-4v18"/>
                            </svg>
                            My Roommates
                        </h2>
                        <p class="card-description">
                            <?php if ($student['room_number']): ?>
                                Room <?php echo htmlspecialchars($student['room_number']); ?> - <?php echo htmlspecialchars($student['hostel_name']); ?>
                            <?php else: ?>
                                No room assigned
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="card-content">
                    <?php if ($student['room_id']): ?>
                        <?php if (!empty($roommates)): ?>
                            <div class="roommates-grid">
                                <?php foreach ($roommates as $roommate): ?>
                                <div class="roommate-card">
                                    <div class="roommate-avatar">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                    <div class="roommate-info">
                                        <h3><?php echo htmlspecialchars($roommate['full_name']); ?></h3>
                                        <p><?php echo htmlspecialchars($roommate['course_name']); ?></p>
                                        <p class="roommate-bed">Bed <?php echo htmlspecialchars($roommate['bed_number']); ?></p>
                                        <p class="roommate-email"><?php echo htmlspecialchars($roommate['email']); ?></p>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-center" style="color: #6b7280; padding: 2rem;">No roommates found</p>
                        <?php endif; ?>
                    <?php else: ?>
                        <p class="text-center" style="color: #6b7280; padding: 2rem;">
                            You haven't been assigned a room yet. Please contact the admin.
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="tab-content" id="leaves">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            My Leave Requests
                        </h2>
                        <p class="card-description">Track your leave applications</p>
                    </div>
                    <button class="btn btn-primary" onclick="showLeaveForm()">Apply for Leave</button>
                </div>
                <div class="card-content">
                    <?php if (!empty($leaveRequests)): ?>
                        <div class="item-list">
                            <?php foreach ($leaveRequests as $leave): ?>
                            <div class="list-item">
                                <div class="list-item-header">
                                    <h3 class="list-item-title">Leave Request</h3>
                                    <span class="badge badge-<?php echo $leave['status'] == 'pending' ? 'warning' : ($leave['status'] == 'approved' ? 'success' : 'danger'); ?>">
                                        <?php echo ucfirst($leave['status']); ?>
                                    </span>
                                </div>
                                <div class="list-item-content">
                                    <?php echo htmlspecialchars($leave['reason']); ?>
                                </div>
                                <div class="list-item-meta">
                                    <?php echo date('M j, Y', strtotime($leave['start_date'])); ?> to <?php echo date('M j, Y', strtotime($leave['end_date'])); ?>
                                    <br>Applied: <?php echo date('M j, Y', strtotime($leave['created_at'])); ?>
                                </div>
                                <?php if ($leave['admin_remarks']): ?>
                                <div class="admin-remarks">
                                    <strong>Admin Remarks:</strong> <?php echo htmlspecialchars($leave['admin_remarks']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center" style="color: #6b7280; padding: 2rem;">No leave requests found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="tab-content" id="complaints">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                            </svg>
                            My Complaints
                        </h2>
                        <p class="card-description">Track your submitted complaints</p>
                    </div>
                    <button class="btn btn-primary" onclick="showComplaintForm()">File Complaint</button>
                </div>
                <div class="card-content">
                    <?php if (!empty($complaints)): ?>
                        <div class="item-list">
                            <?php foreach ($complaints as $complaint): ?>
                            <div class="list-item">
                                <div class="list-item-header">
                                    <h3 class="list-item-title"><?php echo htmlspecialchars($complaint['issue_type']); ?></h3>
                                    <span class="badge badge-<?php echo $complaint['status'] == 'open' ? 'danger' : ($complaint['status'] == 'resolved' ? 'success' : 'warning'); ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                    </span>
                                </div>
                                <div class="list-item-content">
                                    <?php echo htmlspecialchars($complaint['description']); ?>
                                </div>
                                <div class="list-item-meta">
                                    Submitted: <?php echo date('M j, Y', strtotime($complaint['submitted_at'])); ?>
                                    <?php if ($complaint['resolved_at']): ?>
                                        <br>Resolved: <?php echo date('M j, Y', strtotime($complaint['resolved_at'])); ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($complaint['admin_response']): ?>
                                <div class="admin-remarks">
                                    <strong>Admin Response:</strong> <?php echo htmlspecialchars($complaint['admin_response']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center" style="color: #6b7280; padding: 2rem;">No complaints found</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Leave Application Modal -->
    <div id="leaveModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Apply for Leave</h3>
                <button class="modal-close" onclick="hideLeaveForm()">&times;</button>
            </div>
            <form id="leaveForm" method="POST" action="../api/submit_leave.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="leave_reason">Reason for Leave</label>
                        <textarea id="leave_reason" name="reason" required rows="3" placeholder="Please provide reason for leave"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" id="end_date" name="end_date" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideLeaveForm()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Complaint Modal -->
    <div id="complaintModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>File a Complaint</h3>
                <button class="modal-close" onclick="hideComplaintForm()">&times;</button>
            </div>
            <form id="complaintForm" method="POST" action="../api/submit_complaint.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="issue_type">Issue Type</label>
                        <select id="issue_type" name="issue_type" required>
                            <option value="">Select issue type</option>
                            <option value="Maintenance">Maintenance</option>
                            <option value="Facilities">Facilities</option>
                            <option value="Noise">Noise</option>
                            <option value="Cleanliness">Cleanliness</option>
                            <option value="Security">Security</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="complaint_description">Description</label>
                        <textarea id="complaint_description" name="description" required rows="4" placeholder="Please describe the issue in detail"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="hideComplaintForm()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Complaint</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/student.js"></script>
</body>
</html>
