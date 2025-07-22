<?php
require_once '../includes/session.php';
require_once '../includes/hostel_functions.php';

requireAdmin();

$hostelManager = new HostelManager();
$stats = $hostelManager->getAdminStats();
$hostels = $hostelManager->getHostels();
$announcements = $hostelManager->getAnnouncements();
$leaveRequests = $hostelManager->getLeaveRequests();
$complaints = $hostelManager->getComplaints();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hostel Management</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="dashboard">
    <div class="header">
        <div class="header-content">
            <div class="header-title">
                <h1>Admin Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?></p>
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
        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Total Students</h3>
                    <p><?php echo $stats['total_students']; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon green">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21h18"/>
                        <path d="M5 21V7l8-4v18"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Total Rooms</h3>
                    <p><?php echo $stats['total_rooms']; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon orange">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 21h18"/>
                        <path d="M5 21V7l8-4v18"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Occupied Rooms</h3>
                    <p><?php echo $stats['occupied_rooms']; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon purple">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Pending Leaves</h3>
                    <p><?php echo $stats['pending_leaves']; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon red">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <div class="stat-info">
                    <h3>Open Complaints</h3>
                    <p><?php echo $stats['open_complaints']; ?></p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <ul class="tab-list">
                <li><button class="tab-button active" data-tab="hostel-layout">Hostel Layout</button></li>
                <li><button class="tab-button" data-tab="students">Students</button></li>
                <li><button class="tab-button" data-tab="announcements">Announcements</button></li>
                <li><button class="tab-button" data-tab="leaves">Leave Requests</button></li>
                <li><button class="tab-button" data-tab="complaints">Complaints</button></li>
            </ul>
        </div>

        <!-- Tab Contents -->
        <div class="tab-content active" id="hostel-layout">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18"/>
                                <path d="M5 21V7l8-4v18"/>
                            </svg>
                            Interactive Hostel Layout
                        </h2>
                        <p class="card-description">Click through hostels → floors → rooms → beds to view student details</p>
                    </div>
                </div>
                <div class="card-content">
                    <div id="hostel-layout-container">
                        <!-- This will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="students">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Student Management</h2>
                        <p class="card-description">Manage student registrations and room assignments</p>
                    </div>
                    <button class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        Add Student
                    </button>
                </div>
                <div class="card-content">
                    <p style="color: #6b7280;">Student management features will be implemented here.</p>
                </div>
            </div>
        </div>

        <div class="tab-content" id="announcements">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Announcements</h2>
                        <p class="card-description">Manage hostel and campus announcements</p>
                    </div>
                    <button class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"/>
                            <line x1="5" y1="12" x2="19" y2="12"/>
                        </svg>
                        New Announcement
                    </button>
                </div>
                <div class="card-content">
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
                </div>
            </div>
        </div>

        <div class="tab-content" id="leaves">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Leave Requests</h2>
                        <p class="card-description">Review and approve student leave applications</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="item-list">
                        <?php foreach ($leaveRequests as $leave): ?>
                        <div class="list-item">
                            <div class="list-item-header">
                                <h3 class="list-item-title"><?php echo htmlspecialchars($leave['student_name']); ?></h3>
                                <span class="badge badge-<?php echo $leave['status'] == 'pending' ? 'warning' : ($leave['status'] == 'approved' ? 'success' : 'danger'); ?>">
                                    <?php echo ucfirst($leave['status']); ?>
                                </span>
                            </div>
                            <div class="list-item-content">
                                <?php echo htmlspecialchars($leave['reason']); ?>
                            </div>
                            <div class="list-item-meta">
                                <?php echo date('M j, Y', strtotime($leave['start_date'])); ?> to <?php echo date('M j, Y', strtotime($leave['end_date'])); ?>
                            </div>
                            <?php if ($leave['status'] == 'pending'): ?>
                            <div class="list-item-actions">
                                <button class="btn btn-success btn-sm">Approve</button>
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="complaints">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h2 class="card-title">Complaints</h2>
                        <p class="card-description">Manage student complaints and issues</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="item-list">
                        <?php foreach ($complaints as $complaint): ?>
                        <div class="list-item">
                            <div class="list-item-header">
                                <h3 class="list-item-title"><?php echo htmlspecialchars($complaint['student_name']); ?></h3>
                                <span class="badge badge-<?php echo $complaint['status'] == 'open' ? 'danger' : ($complaint['status'] == 'resolved' ? 'success' : 'warning'); ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $complaint['status'])); ?>
                                </span>
                            </div>
                            <div class="list-item-content">
                                <strong>Type:</strong> <?php echo htmlspecialchars($complaint['issue_type']); ?><br>
                                <?php echo htmlspecialchars($complaint['description']); ?>
                            </div>
                            <div class="list-item-meta">
                                Submitted: <?php echo date('M j, Y', strtotime($complaint['submitted_at'])); ?>
                            </div>
                            <?php if ($complaint['status'] == 'open'): ?>
                            <div class="list-item-actions">
                                <button class="btn btn-primary btn-sm">Mark In Progress</button>
                                <button class="btn btn-success btn-sm">Resolve</button>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/hostel-layout.js"></script>
</body>
</html>
