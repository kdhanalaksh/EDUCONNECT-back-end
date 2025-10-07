-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2025 at 11:05 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `educonnect_new_backend`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_performance`
--

CREATE TABLE `academic_performance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `completion_percent` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_request`
--

CREATE TABLE `assignment_request` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assignment_request`
--

INSERT INTO `assignment_request` (`id`, `student_id`, `assignment_id`, `status`) VALUES
(1, 1, 1, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `status` enum('Present','Absent','Late') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `class_id`, `subject_id`, `date`, `student_id`, `status`) VALUES
(1, 1, 8, '2025-08-10', 3, 'Present'),
(2, 1, 8, '2025-08-19', 2, 'Present'),
(4, 1, 8, '2025-08-19', 4, 'Present'),
(11, 1, 8, '2025-08-19', 5, 'Present'),
(13, 1, 8, '2025-08-19', 3, 'Absent');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `class_name` varchar(20) DEFAULT NULL,
  `section` varchar(5) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`id`, `class_name`, `section`, `teacher_id`) VALUES
(1, '10th Grade', 'A', 8),
(2, '10th Grade', 'A', 8);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `section` varchar(50) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `section`, `teacher_id`) VALUES
(1, '10th Grade', 'A', 8);

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollment`
--

CREATE TABLE `course_enrollment` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `status` enum('Requested','Accepted','Rejected') DEFAULT NULL,
  `requested_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_enrollment`
--

INSERT INTO `course_enrollment` (`id`, `student_id`, `class_id`, `status`, `requested_at`) VALUES
(1, 1, 1, 'Requested', '2025-08-10 11:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `type` enum('Sports','Cultural','Exam','Other') DEFAULT NULL,
  `description` text DEFAULT NULL,
  `visibility` enum('All','Students','Teachers') DEFAULT NULL,
  `status` enum('Upcoming','Completed','Cancelled') DEFAULT 'Upcoming'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `title`, `date`, `time`, `type`, `description`, `visibility`, `status`) VALUES
(1, 'sports day', '2025-07-20', '09:30:00', 'Sports', 'annual sports day', 'All', 'Upcoming'),
(2, 'sports day', '2025-07-20', '09:30:00', 'Sports', 'annual sports day', 'All', 'Upcoming');

-- --------------------------------------------------------

--
-- Table structure for table `homework`
--

CREATE TABLE `homework` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('Pending','Completed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework`
--

INSERT INTO `homework` (`id`, `class_id`, `subject_id`, `title`, `description`, `due_date`, `status`) VALUES
(1, 1, 1, 'maths assignment', 'solve all exercise', '2025-07-27', 'Completed'),
(2, 1, 8, 'Quiz 1', 'Science quiz', '2025-08-10', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `homework_submission`
--

CREATE TABLE `homework_submission` (
  `id` int(11) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT NULL,
  `submission_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `homework_submission`
--

INSERT INTO `homework_submission` (`id`, `student_id`, `assignment_id`, `status`, `submission_date`) VALUES
(3, 3, 1, 'Pending', '2025-08-10 11:18:51');

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `meeting_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `purpose` text DEFAULT NULL,
  `notify_parents` tinyint(1) DEFAULT 1,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `meeting_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meeting`
--

INSERT INTO `meeting` (`id`, `teacher_id`, `title`, `meeting_date`, `start_time`, `end_time`, `purpose`, `notify_parents`, `status`, `created_at`, `updated_at`, `meeting_link`) VALUES
(7, 8, 'PTM', '2025-09-01', '01:10:00', '02:10:00', 'STD', 1, 'Scheduled', '2025-08-25 16:38:27', '2025-09-01 08:28:49', 'https://meet.google.com/zow-xgpm-wft'),
(8, 8, 'PTM', '2025-08-24', '12:00:00', '01:01:00', 'SPD', 1, 'Scheduled', '2025-08-24 08:19:27', '2025-08-30 09:17:15', NULL),
(9, 8, 'PTM Meeting', '2025-08-28', '10:00:00', '11:00:00', 'Discuss student progress', 1, 'Scheduled', '2025-08-25 11:52:19', '2025-08-30 09:17:30', ''),
(11, 8, 'ptm', '2025-09-11', '12:00:00', '12:00:00', 'tt', 1, 'Scheduled', '2025-09-08 04:38:59', '2025-09-08 04:41:47', 'ggg'),
(12, 8, 'dhanna', '2025-09-08', '01:05:00', '04:21:00', 'meeting', 1, 'Scheduled', '2025-09-08 07:48:14', '2025-09-08 07:48:14', 'meet.google.com/eyy-gyuf-szx'),
(13, 8, 'parent meeting', '2025-09-09', '01:01:00', '04:00:00', 'students created successfully', 1, 'Scheduled', '2025-09-08 07:51:40', '2025-09-08 07:51:40', 'meet.google.com/xbg-sisz-kfq'),
(14, 8, 'ptm', '2025-09-09', '11:16:00', '12:16:00', 'ptm', 1, 'Scheduled', '2025-09-09 10:35:25', '2025-09-09 10:35:25', 'https://meet.google.com/zow-xgpm-wft');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `type` enum('Academic','Event','General') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `user_id`, `title`, `type`, `message`, `created_at`) VALUES
(1, 3, 'homework update', 'Academic', 'submiy homework by today', '2025-07-31 09:22:00');

-- --------------------------------------------------------

--
-- Table structure for table `otp_verification`
--

CREATE TABLE `otp_verification` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_verification`
--

INSERT INTO `otp_verification` (`id`, `email`, `otp`, `created_at`) VALUES
(1, 'madhumitha2005@gmail.com', '119711', '2025-07-30 22:11:53'),
(2, 'dhana2004@gmail.com', '482738', '2025-07-31 10:29:23'),
(3, 'dhana2004@gmail.com', '204395', '2025-08-04 09:40:13'),
(4, 'tahir@gmail.com', '338556', '2025-08-04 20:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `total_students` int(11) NOT NULL,
  `completed_students` int(11) NOT NULL,
  `quiz_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `day_of_week` varchar(10) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `email`, `teacher_id`) VALUES
(1, 'Alice', 'alice@example.com', 8),
(2, 'Bob', 'bob@example.com', 8),
(3, 'Charlie', 'charlie@example.com', 8);

-- --------------------------------------------------------

--
-- Table structure for table `student_attendance`
--

CREATE TABLE `student_attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_attendance`
--

INSERT INTO `student_attendance` (`id`, `student_id`, `class_id`, `date`, `status`) VALUES
(2, 3, 1, '2025-07-30', 'Present'),
(3, 3, 1, '2025-07-31', 'Present');

-- --------------------------------------------------------

--
-- Table structure for table `student_profile`
--

CREATE TABLE `student_profile` (
  `user_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `parent_name` varchar(100) DEFAULT NULL,
  `parent_contact` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_profile`
--

INSERT INTO `student_profile` (`user_id`, `class_id`, `dob`, `parent_name`, `parent_contact`) VALUES
(1, 1, '2005-08-15', 'ramesh', '7339046931'),
(1, 1, '2005-08-15', 'ramesh', '7339046931'),
(9, 1, '2005-05-20', 'suresh', '9876543210'),
(10, 1, '2005-12-01', 'lakshmi', '9123456780');

-- --------------------------------------------------------

--
-- Table structure for table `student_teacher`
--

CREATE TABLE `student_teacher` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`id`, `name`) VALUES
(1, 'Physics'),
(2, 'Mathematics'),
(3, 'Chemistry'),
(8, 'maths');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_preferences`
--

CREATE TABLE `teacher_preferences` (
  `id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_preferences`
--

INSERT INTO `teacher_preferences` (`id`, `teacher_id`, `setting_name`, `setting_value`) VALUES
(1, 8, 'school_announcements', 1),
(4, 8, 'class_updates', 0),
(6, 8, 'assignment_reminders', 1),
(8, 8, 'emergency_alerts', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_profile`
--

CREATE TABLE `teacher_profile` (
  `user_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `experience` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teacher_profile`
--

INSERT INTO `teacher_profile` (`user_id`, `subject_id`, `qualifications`, `experience`) VALUES
(1, 1, 'msc physics', 5),
(8, 1, 'MSc Mathematics', 5),
(8, 1, 'MSc Mathematics', 5),
(8, 1, 'MSc Mathematics', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('student','teacher','admin') DEFAULT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `otp_created_at` datetime DEFAULT current_timestamp(),
  `teacher_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `email`, `password`, `role`, `contact_number`, `gender`, `profile_picture`, `otp`, `otp_created_at`, `teacher_id`) VALUES
(1, 'Madhumitha', 'madhumitha2005@gmail.com', '$2y$10$czVr9b6Dr76w7nYVmDI1ROGoDdn5A9YdbInCyc25eVXBNeWe9hUWW', 'student', '6380139029', 'female', '1753894943_Alex.png', NULL, '2025-08-05 13:49:29', 8),
(2, 'kevin', 'kevin2004@gmail.com', '$2y$10$6FBPON2BBlRwXx15qR2LD.TzO09F99rjlTfa8bDR/tLobsjStdKH6', 'teacher', '7339046932', 'male', NULL, NULL, '2025-08-05 13:49:29', NULL),
(3, 'shreya', 'shreya2004@gmail.com', '$2y$10$DOZTW0gKgWYxNWgOJur0sOqAY6wF/8P5i/I4Dy79g6EqZ1qg0kMc6', 'student', '7339046933', 'female', NULL, NULL, '2025-08-05 13:49:29', NULL),
(4, 'John Doe', 'john@example.com', '123456', 'teacher', '9876543210', 'male', NULL, NULL, '2025-08-05 13:49:29', NULL),
(5, 'dhana', 'dhana2004@gmail.com', '$2y$10$KISruFxzHNCM6BdWnV4xg.pBKmkVL2ZIBxiU/81lvy6j5GDerb4/G', 'student', '7339046935', 'female', NULL, NULL, '2025-08-05 13:49:29', NULL),
(6, 'khan', 'khan@gmail.com', '$2y$10$odkA8VL469d1mSH/vPoP0eZDTrVvm.V43N7I2LcyqMFoSkNKHF4Sq', 'student', '76766986698', 'male', NULL, NULL, '2025-08-05 13:49:29', 8),
(7, 'tahir', 'tahir@gmail.com', '$2y$10$kfBg0XtE5Cu/4fRQ3rxxO.CoK6aBUTQbckKSAcwl5wQzHWi9i7ph2', 'teacher', '9865877296', 'male', NULL, NULL, '2025-08-05 13:49:29', NULL),
(8, 'dhana', 'kdhanalakshmi2005@gmail.com', '$2y$10$GGefa9nMBOKuL/ICKtGum.s8G8q1FCWbxI0No22oSiBKhIQGjsXVS', 'teacher', '9876543211', 'Female', 'C:UsersDELLOneDrivePictures', '539144', '2025-09-11 12:05:59', NULL),
(9, 'arun', 'arun2005@gmail.com', 'arun123', 'student', '9876543210', 'male', NULL, NULL, '2025-08-19 19:49:56', 8),
(10, 'kavya', 'kavya2005@gmail.com', 'kavya123', 'student', '9123456780', 'female', NULL, NULL, '2025-08-19 19:49:56', 8),
(11, 'Tahir khan', 'dhanalakshmi4201.sse@saveetha.com', '$2y$10$R6DSN0UuemQYOubbrYCvmeJnQPOEbvifm.FHMUx9qoIsVMwEMArI.', 'student', '9876543210', 'male', 'null', NULL, '2025-08-29 10:40:11', NULL),
(12, 'Bhuvana', 'kbhuvana@gmail.com', '$2y$10$uF25rIumWw..4F59PDqNzu9VQdiNQnIVi6mnNE5tzzk6UPw5bZB6q', 'student', '1122334455', 'Female', NULL, NULL, '2025-08-29 12:58:08', NULL),
(13, 'Santhosh', 'santhosh@gmail.c9m', '$2y$10$vrLlghw5kaYMsGWylCm0xeYaXzjEG7icLFGxYBkjNAuw4Wfjl.Ul.', 'student', '3425689462', 'Male', NULL, NULL, '2025-08-29 13:04:26', NULL),
(14, 'Wasim', 'wasim2005@gmail.com', '$2y$10$oJUa1le2gdKWUksfg9vXaeg5bC7KhWQSBu2RTu6h4DhcTuFDzJtRK', 'student', '7339046931', 'Male', NULL, NULL, '2025-09-08 10:41:54', NULL),
(15, 'wasim shaik', 'wasimshaik.ai@gmail.com', '$2y$10$zTiGi2NJhw33.1kDxAI5OemsGQ.b0MStE4xA86wcvV/aGbdqWSJIK', 'student', '9553336060', 'Male', NULL, NULL, '2025-09-08 13:24:37', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_performance`
--
ALTER TABLE `academic_performance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `assignment_request`
--
ALTER TABLE `assignment_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`class_id`,`subject_id`,`date`,`student_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_enrollment`
--
ALTER TABLE `course_enrollment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `homework_submission`
--
ALTER TABLE `homework_submission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `otp_verification`
--
ALTER TABLE `otp_verification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `teacher_id` (`teacher_id`);

--
-- Indexes for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_profile`
--
ALTER TABLE `student_profile`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `class_id` (`class_id`);

--
-- Indexes for table `student_teacher`
--
ALTER TABLE `student_teacher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pref` (`teacher_id`,`setting_name`);

--
-- Indexes for table `teacher_profile`
--
ALTER TABLE `teacher_profile`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `academic_performance`
--
ALTER TABLE `academic_performance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `assignment_request`
--
ALTER TABLE `assignment_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course_enrollment`
--
ALTER TABLE `course_enrollment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `homework_submission`
--
ALTER TABLE `homework_submission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `otp_verification`
--
ALTER TABLE `otp_verification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_attendance`
--
ALTER TABLE `student_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `student_teacher`
--
ALTER TABLE `student_teacher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_performance`
--
ALTER TABLE `academic_performance`
  ADD CONSTRAINT `academic_performance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `academic_performance_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

--
-- Constraints for table `assignment_request`
--
ALTER TABLE `assignment_request`
  ADD CONSTRAINT `assignment_request_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `assignment_request_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `homework` (`id`);

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `course_enrollment`
--
ALTER TABLE `course_enrollment`
  ADD CONSTRAINT `course_enrollment_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `course_enrollment_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`);

--
-- Constraints for table `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `homework_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `homework_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

--
-- Constraints for table `homework_submission`
--
ALTER TABLE `homework_submission`
  ADD CONSTRAINT `homework_submission_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `homework_submission_ibfk_2` FOREIGN KEY (`assignment_id`) REFERENCES `homework` (`id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
  ADD CONSTRAINT `quiz_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `schedule_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `schedule_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `student_attendance`
--
ALTER TABLE `student_attendance`
  ADD CONSTRAINT `student_attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `student_attendance_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`);

--
-- Constraints for table `student_profile`
--
ALTER TABLE `student_profile`
  ADD CONSTRAINT `student_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `student_profile_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`);

--
-- Constraints for table `teacher_preferences`
--
ALTER TABLE `teacher_preferences`
  ADD CONSTRAINT `teacher_preferences_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `teacher_profile`
--
ALTER TABLE `teacher_profile`
  ADD CONSTRAINT `teacher_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `teacher_profile_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subject` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
