SET FOREIGN_KEY_CHECKS = 0;

CREATE DATABASE IF NOT EXISTS `campushub`;
USE `campushub`;

-- 1. Status table
DROP TABLE IF EXISTS `status`;
CREATE TABLE `status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Active'),
(2, 'Pending'),
(3, 'Inactive');

-- 2. User Type table
DROP TABLE IF EXISTS `user_type`;
CREATE TABLE `user_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `user_type` (`id`, `type`) VALUES
(1, 'Main Admin'),
(2, 'Sub Admin'),
(3, 'Student');

-- 3. Campus table
DROP TABLE IF EXISTS `campus`;
CREATE TABLE `campus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `campus` (`id`, `name`, `code`) VALUES
(1, 'University of Colombo', 'UOC'),
(2, 'University of Peradeniya', 'UOP'),
(3, 'University of Moratuwa', 'UOM'),
(4, 'University of Kelaniya', 'UOK'),
(5, 'SLIIT / Other Institution', 'SLIIT');

-- 4. User table
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `email` varchar(100) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `institution` varchar(100) DEFAULT 'University of Colombo',
  `campus_id` int(11) DEFAULT NULL,
  `student_number` varchar(50) DEFAULT NULL,
  `user_type_id` int(11) NOT NULL DEFAULT 3,
  `status_id` int(11) NOT NULL DEFAULT 2,
  `joined_date` datetime NOT NULL,
  `profile_img` varchar(255) DEFAULT 'resources/profile_images/default.png',
  `vcode` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`email`),
  KEY `fk_user_user_type` (`user_type_id`),
  KEY `fk_user_status` (`status_id`),
  KEY `fk_user_campus` (`campus_id`),
  CONSTRAINT `fk_user_status` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  CONSTRAINT `fk_user_user_type` FOREIGN KEY (`user_type_id`) REFERENCES `user_type` (`id`),
  CONSTRAINT `fk_user_campus` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Users
INSERT INTO `user` (`email`, `fname`, `lname`, `password`, `mobile`, `institution`, `campus_id`, `student_number`, `user_type_id`, `status_id`, `joined_date`, `profile_img`, `vcode`) VALUES
('admin@campushub.lk', 'System', 'Main Admin', 'admin123', '0771234567', 'CampusHub HQ', NULL, NULL, 1, 1, '2026-01-01 09:00:00', 'resources/profile_images/default.png', NULL),
('colombo.admin@campushub.lk', 'Colombo', 'SubAdmin', 'subadmin123', '0771112223', 'University of Colombo', 1, NULL, 2, 1, '2026-01-02 09:00:00', 'resources/profile_images/default.png', NULL),
('peradeniya.admin@campushub.lk', 'Peradeniya', 'SubAdmin', 'subadmin123', '0772223334', 'University of Peradeniya', 2, NULL, 2, 1, '2026-01-02 09:00:00', 'resources/profile_images/default.png', NULL),
('moratuwa.admin@campushub.lk', 'Moratuwa', 'SubAdmin', 'subadmin123', '0773334445', 'University of Moratuwa', 3, NULL, 2, 1, '2026-01-02 09:00:00', 'resources/profile_images/default.png', NULL),
('john@gmail.com', 'John', 'Doe', 'john123', '0712345678', 'University of Peradeniya', 2, 'UOP-2026-001', 3, 1, '2026-02-15 10:30:00', 'resources/profile_images/default.png', 'CH-102938'),
('sarah@gmail.com', 'Sarah', 'Smith', 'sarah123', '0778889990', 'University of Moratuwa', 3, 'UOM-2026-042', 3, 1, '2026-03-10 14:20:00', 'resources/profile_images/default.png', 'CH-492019'),
('pending.student@gmail.com', 'Kamal', 'Perera', 'kamal123', '0779990001', 'University of Colombo', 1, 'UOC-2026-089', 3, 2, '2026-08-25 11:00:00', 'resources/profile_images/default.png', 'CH-778899');

-- 5. Category table
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Student Clubs'),
(2, 'Workshops'),
(3, 'Competitions'),
(4, 'Sports Activities'),
(5, 'Cultural Events');

-- 6. Event table
DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) NOT NULL,
  `campus_id` int(11) NOT NULL DEFAULT 1,
  `is_open_to_all` tinyint(1) NOT NULL DEFAULT 0,
  `event_date` datetime NOT NULL,
  `location` varchar(100) NOT NULL,
  `banner_img` varchar(255) DEFAULT 'resources/event_images/default_event.jpg',
  `max_participants` int(11) NOT NULL DEFAULT 50,
  `created_by` varchar(100) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_event_category` (`category_id`),
  KEY `fk_event_user` (`created_by`),
  KEY `fk_event_campus` (`campus_id`),
  CONSTRAINT `fk_event_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fk_event_user` FOREIGN KEY (`created_by`) REFERENCES `user` (`email`),
  CONSTRAINT `fk_event_campus` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `event` (`id`, `title`, `description`, `category_id`, `campus_id`, `is_open_to_all`, `event_date`, `location`, `banner_img`, `max_participants`, `created_by`, `status_id`) VALUES
(1, 'Hackathon 2026 Innovation Challenge', 'Join the biggest campus coding competition of the year. Build innovative web apps and win exciting prizes!', 3, 1, 1, '2026-09-15 09:00:00', 'Main Auditorium, UoC', 'resources/event_images/hackathon.jpg', 100, 'colombo.admin@campushub.lk', 1),
(2, 'Web Development Workshop with Bootstrap & PHP', 'Hands-on practical workshop covering responsive UI design with Bootstrap 5 and backend development with PHP.', 2, 3, 0, '2026-09-20 13:30:00', 'IT Lab 02, UoM', 'resources/event_images/workshop.jpg', 60, 'moratuwa.admin@campushub.lk', 1),
(3, 'Inter-Campus Cricket Tournament 2026', 'Annual cricket championship between universities. Register your campus squad today!', 4, 2, 1, '2026-10-01 08:00:00', 'Campus Sports Ground, UoP', 'resources/event_images/cricket.jpg', 150, 'peradeniya.admin@campushub.lk', 1),
(4, 'Robotics & AI Student Club Meetup', 'Monthly meetup of robotics enthusiasts, showcasing student project prototypes and drone demos.', 1, 3, 0, '2026-10-05 15:00:00', 'Engineering Complex, UoM', 'resources/event_images/robotics.jpg', 40, 'moratuwa.admin@campushub.lk', 1);

-- 7. Event Registration table
DROP TABLE IF EXISTS `event_registration`;
CREATE TABLE `event_registration` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `registered_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Registered',
  PRIMARY KEY (`id`),
  KEY `fk_reg_event` (`event_id`),
  KEY `fk_reg_user` (`user_email`),
  CONSTRAINT `fk_reg_event` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reg_user` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `event_registration` (`id`, `event_id`, `user_email`, `registered_date`, `status`) VALUES
(1, 1, 'john@gmail.com', '2026-08-20 11:00:00', 'Registered'),
(2, 2, 'sarah@gmail.com', '2026-08-22 14:15:00', 'Registered');

-- 8. Announcement table
DROP TABLE IF EXISTS `announcement`;
CREATE TABLE `announcement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `content` text NOT NULL,
  `campus_id` int(11) DEFAULT NULL,
  `date_published` datetime NOT NULL,
  `author_email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ann_user` (`author_email`),
  KEY `fk_ann_campus` (`campus_id`),
  CONSTRAINT `fk_ann_user` FOREIGN KEY (`author_email`) REFERENCES `user` (`email`) ON DELETE CASCADE,
  CONSTRAINT `fk_ann_campus` FOREIGN KEY (`campus_id`) REFERENCES `campus` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `announcement` (`id`, `title`, `content`, `campus_id`, `date_published`, `author_email`) VALUES
(1, 'Welcome to CampusHub 2026!', 'CampusHub official web platform is now live across all universities. Explore events, workshops, sports activities and register online.', NULL, '2026-08-20 08:00:00', 'admin@campushub.lk'),
(2, 'Annual General Meeting - UoC Rotaract Club', 'The AGM for all student club members will take place this Friday at 4 PM in the Student Center.', 1, '2026-08-24 10:00:00', 'colombo.admin@campushub.lk'),
(3, 'UoM Engineering Hackathon Registration Open', 'Calling all Moratuwa undergraduates to submit software project proposals.', 3, '2026-08-25 14:00:00', 'moratuwa.admin@campushub.lk');

-- 9. Media gallery
DROP TABLE IF EXISTS `media_gallery`;
CREATE TABLE `media_gallery` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `media_type` varchar(20) NOT NULL,
  `uploaded_by` varchar(100) NOT NULL,
  `upload_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_media_user` (`uploaded_by`),
  CONSTRAINT `fk_media_user` FOREIGN KEY (`uploaded_by`) REFERENCES `user` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `media_gallery` (`id`, `title`, `description`, `file_path`, `media_type`, `uploaded_by`, `upload_date`) VALUES
(1, 'Hackathon Kickoff 2025 Highlights', 'Photographs from last year\'s hackathon opening ceremony.', 'resources/media_files/hackathon_photo.jpg', 'image', 'admin@campushub.lk', '2026-08-21 10:00:00'),
(2, 'Campus Anthem Podcast Episode 1', 'Audio discussion on student leadership and upcoming sports meet.', 'resources/media_files/campus_podcast.mp3', 'audio', 'admin@campushub.lk', '2026-08-22 11:30:00'),
(3, 'Robotics Drone Demo Highlight', 'Video demonstration of the quadcopter built by engineering students.', 'resources/media_files/drone_demo.mp4', 'video', 'admin@campushub.lk', '2026-08-23 16:45:00');

-- 10. Online form submission
DROP TABLE IF EXISTS `online_form_submission`;
CREATE TABLE `online_form_submission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `category` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `submitted_date` datetime NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`),
  KEY `fk_form_user` (`user_email`),
  CONSTRAINT `fk_form_user` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `online_form_submission` (`id`, `user_email`, `subject`, `category`, `message`, `attachment`, `submitted_date`, `status`) VALUES
(1, 'john@gmail.com', 'Request for New Photography Club', 'Club Application', 'We would like to establish an official Photography & Cinematography student club under CampusHub.', NULL, '2026-08-24 09:30:00', 'Pending');

-- 11. User Profile Image History table
DROP TABLE IF EXISTS `user_profile_image`;
CREATE TABLE `user_profile_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(100) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_prof_img_user` (`user_email`),
  CONSTRAINT `fk_prof_img_user` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;
