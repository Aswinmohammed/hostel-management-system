-- Insert initial data

USE hostel_management;

-- Insert hostels
INSERT INTO hostels (name, total_floors, rooms_per_floor, beds_per_room) VALUES
('Hostel-01', 3, 20, 4),
('Hostel-02', 3, 20, 4);

-- Insert rooms for Hostel-01
INSERT INTO rooms (hostel_id, floor_number, room_number) VALUES
-- Floor 1 (101-120)
(1, 1, '101'), (1, 1, '102'), (1, 1, '103'), (1, 1, '104'), (1, 1, '105'),
(1, 1, '106'), (1, 1, '107'), (1, 1, '108'), (1, 1, '109'), (1, 1, '110'),
(1, 1, '111'), (1, 1, '112'), (1, 1, '113'), (1, 1, '114'), (1, 1, '115'),
(1, 1, '116'), (1, 1, '117'), (1, 1, '118'), (1, 1, '119'), (1, 1, '120'),
-- Floor 2 (201-220)
(1, 2, '201'), (1, 2, '202'), (1, 2, '203'), (1, 2, '204'), (1, 2, '205'),
(1, 2, '206'), (1, 2, '207'), (1, 2, '208'), (1, 2, '209'), (1, 2, '210'),
(1, 2, '211'), (1, 2, '212'), (1, 2, '213'), (1, 2, '214'), (1, 2, '215'),
(1, 2, '216'), (1, 2, '217'), (1, 2, '218'), (1, 2, '219'), (1, 2, '220'),
-- Floor 3 (301-320)
(1, 3, '301'), (1, 3, '302'), (1, 3, '303'), (1, 3, '304'), (1, 3, '305'),
(1, 3, '306'), (1, 3, '307'), (1, 3, '308'), (1, 3, '309'), (1, 3, '310'),
(1, 3, '311'), (1, 3, '312'), (1, 3, '313'), (1, 3, '314'), (1, 3, '315'),
(1, 3, '316'), (1, 3, '317'), (1, 3, '318'), (1, 3, '319'), (1, 3, '320');

-- Insert rooms for Hostel-02
INSERT INTO rooms (hostel_id, floor_number, room_number) VALUES
-- Floor 1 (101-120)
(2, 1, '101'), (2, 1, '102'), (2, 1, '103'), (2, 1, '104'), (2, 1, '105'),
(2, 1, '106'), (2, 1, '107'), (2, 1, '108'), (2, 1, '109'), (2, 1, '110'),
(2, 1, '111'), (2, 1, '112'), (2, 1, '113'), (2, 1, '114'), (2, 1, '115'),
(2, 1, '116'), (2, 1, '117'), (2, 1, '118'), (2, 1, '119'), (2, 1, '120'),
-- Floor 2 (201-220)
(2, 2, '201'), (2, 2, '202'), (2, 2, '203'), (2, 2, '204'), (2, 2, '205'),
(2, 2, '206'), (2, 2, '207'), (2, 2, '208'), (2, 2, '209'), (2, 2, '210'),
(2, 2, '211'), (2, 2, '212'), (2, 2, '213'), (2, 2, '214'), (2, 2, '215'),
(2, 2, '216'), (2, 2, '217'), (2, 2, '218'), (2, 2, '219'), (2, 2, '220'),
-- Floor 3 (301-320)
(2, 3, '301'), (2, 3, '302'), (2, 3, '303'), (2, 3, '304'), (2, 3, '305'),
(2, 3, '306'), (2, 3, '307'), (2, 3, '308'), (2, 3, '309'), (2, 3, '310'),
(2, 3, '311'), (2, 3, '312'), (2, 3, '313'), (2, 3, '314'), (2, 3, '315'),
(2, 3, '316'), (2, 3, '317'), (2, 3, '318'), (2, 3, '319'), (2, 3, '320');

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insert sample student users (password: student123)
INSERT INTO users (username, password, role) VALUES
('john.doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('jane.smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('mike.johnson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student'),
('sarah.wilson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student');

-- Insert sample students with room assignments
INSERT INTO students (user_id, full_name, email, course_name, room_id, bed_number, phone) VALUES
(2, 'John Doe', 'john.doe@university.edu', 'Computer Science', 1, 1, '+1234567890'),
(3, 'Jane Smith', 'jane.smith@university.edu', 'Electrical Engineering', 1, 2, '+1234567891'),
(4, 'Mike Johnson', 'mike.johnson@university.edu', 'Mechanical Engineering', 1, 3, '+1234567892'),
(5, 'Sarah Wilson', 'sarah.wilson@university.edu', 'Civil Engineering', 2, 1, '+1234567893');

-- Insert sample announcements
INSERT INTO announcements (title, content, created_by, category) VALUES
('Welcome to New Academic Year', 'Welcome all students to the new academic year. Please ensure you follow all hostel rules and regulations.', 1, 'general'),
('Maintenance Schedule', 'Routine maintenance will be conducted on weekends. Please cooperate with the maintenance staff.', 1, 'maintenance'),
('Mess Timings Updated', 'New mess timings: Breakfast 7-9 AM, Lunch 12-2 PM, Dinner 7-9 PM', 1, 'mess');

-- Insert sample leave requests
INSERT INTO leave_requests (student_id, reason, start_date, end_date, status) VALUES
(1, 'Family emergency - need to visit home', '2024-01-15', '2024-01-20', 'pending'),
(2, 'Medical appointment in hometown', '2024-01-10', '2024-01-12', 'approved'),
(3, 'Wedding ceremony to attend', '2024-02-01', '2024-02-05', 'pending');

-- Insert sample complaints
INSERT INTO complaints (student_id, issue_type, description, status) VALUES
(1, 'Maintenance', 'Air conditioning not working in room 101', 'open'),
(2, 'Facilities', 'Hot water not available in morning hours', 'in_progress'),
(3, 'Noise', 'Loud music from neighboring room disturbing studies', 'open'),
(4, 'Cleanliness', 'Common area needs better cleaning schedule', 'resolved');
