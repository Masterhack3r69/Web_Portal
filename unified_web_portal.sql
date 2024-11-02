-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 02, 2024 at 12:16 PM
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
-- Database: `unified_web_portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `admin_type` enum('local','central') NOT NULL,
  `department_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`, `admin_type`, `department_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'centraladmin', '$2y$10$bUlqI0ESjq1UZn/lbNN5S.X3fQronEq3f.2I5WPPseB11OVNTC.7G', 'jd@bfkjbnfv', 'central', 0, 'active', '2024-10-07 05:28:59', '2024-10-11 17:52:02'),
(10, 'john', '$2y$10$fGfiqFOmjJKX5JzgjKHZ6ua9Gtd75kNx2poP2U72YZpCu6jWU5u/e', 'jdedusma@gmail.com', 'local', 82, 'active', '2024-10-20 16:46:46', '2024-10-20 16:46:46');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `department_head` varchar(255) NOT NULL,
  `contact_phone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `location` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `department_banner` varchar(255) DEFAULT NULL,
  `local_admin_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `description`, `department_head`, `contact_phone`, `email`, `status`, `location`, `logo`, `department_banner`, `local_admin_id`, `created_at`, `updated_at`) VALUES
(82, 'Public Employment Service Office (PESO)', 'The <strong>Public Employment Service Office (PESO)</strong> is a government initiative that provides employment services to job seekers and employers. It offers job placement assistance, career counseling, and training programs to enhance skills and employability. PESO also provides labor market information and supports businesses in recruiting qualified candidates, facilitating access to various government employment programs to promote job creation and economic development.', 'John Deckson Edusma', '09876543211', 'jdedusma@gmail.com', 'Active', 'P5 Quarenta, San Jose, Dinagat, Islands', '../../../assets/img/uploads/18c2438c-fc28-4a6b-a9f5-eac50d29dfe1.jpg', '../../../assets/img/uploads/—Pngtree—red gradient banner background_1190598.jpg', NULL, '2024-10-20 16:46:25', '2024-10-28 13:24:22'),
(83, 'Remedy Action Center', 'The <b>Public Employment Service Office (PESO)</b> in the Philippines is a government initiative that provides employment services to job seekers and employers. It offers job placement assistance, career counseling, and training programs to enhance skills and employability. PESO also provides labor market information and supports businesses in recruiting qualified candidates, facilitating access to various government employment programs to promote job creation and economic development.', 'Francis Ryan Ambat', '32546765', 'kite@gmail.com', 'Active', NULL, '../../../assets/img/uploads/PH-DIN_Flag.png', NULL, NULL, '2024-10-27 09:28:42', '2024-10-27 10:46:04');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `form_name` varchar(255) NOT NULL,
  `form_html` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `form_name`, `form_html`, `created_at`) VALUES
(15, 'tupad form(1)', '\n                            \n                        <div class=\"mb-3\" id=\"field-1\">\n                            <label class=\"form-check-label\">First Name:</label>\n                            <input type=\"text\" name=\"First Name\" class=\"form-control\" placeholder=\"Enter First Name\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-2\">\n                            <label class=\"form-check-label\">Middle Name:</label>\n                            <input type=\"text\" name=\"Middle Name\" class=\"form-control\" placeholder=\"Enter Middle Name\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-3\">\n                            <label class=\"form-check-label\">Last Name:</label>\n                            <input type=\"text\" name=\"Last Name\" class=\"form-control\" placeholder=\"Enter Last Name\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-4\">\n                            <label class=\"form-check-label\">Birthdate:</label>\n                            <input type=\"date\" name=\"Birthdate\" class=\"form-control\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-5\">\n                            <label class=\"form-check-label\">Address:</label>\n                            <input type=\"text\" name=\"Address\" class=\"form-control\" placeholder=\"Enter Address\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-6\">\n                            <label class=\"form-check-label\">Type of ID:</label>\n                            <select name=\"Type of ID\" class=\"form-select\" required=\"\">\n                                <option>SSS</option><option>Voter\'s ID</option>\n                            </select>\n                        </div><div class=\"mb-3\" id=\"field-7\">\n                            <label class=\"form-check-label\">ID Number:</label>\n                            <input type=\"text\" name=\"ID Number\" class=\"form-control\" placeholder=\"Enter ID Number\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-8\">\n                            <label class=\"form-check-label\">Contact No.:</label>\n                            <input type=\"text\" name=\"Contact No.\" class=\"form-control\" placeholder=\"Enter Contact No.\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-9\">\n                            <label class=\"form-check-label\">Type of Beneficiary:</label>\n                            <select name=\"Type of Beneficiary\" class=\"form-select\" required=\"\">\n                                <option>Students</option><option>Old Citizen</option><option>Women</option><option>Unemployed</option>\n                            </select>\n                        </div><div class=\"mb-3\" id=\"field-10\">\n                            <label class=\"form-check-label d-block\">Sex:</label>\n                            <div class=\"d-inline-flex\">\n                                <div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Sex\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Male</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Sex\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Female</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Sex\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Other</label>\n                </div>\n                            </div>\n                        </div><div class=\"mb-3\" id=\"field-11\">\n                            <label class=\"form-check-label d-block\">Civil Status:</label>\n                            <div class=\"d-inline-flex\">\n                                <div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Married</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Single</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Widow</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Divorced</label>\n                </div>\n                            </div>\n                        </div><div class=\"mb-3\" id=\"field-12\">\n                            <label class=\"form-check-label\">Age:</label>\n                            <input type=\"text\" name=\"Age\" class=\"form-control\" placeholder=\"Enter Age\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-13\">\n                            <label class=\"form-check-label\">Dependent (name of Beneficiary of the Micro-insurance Holder):</label>\n                            <input type=\"text\" name=\"Dependent (name of Beneficiary of the Micro-insurance Holder)\" class=\"form-control\" placeholder=\"Enter Dependent (name of Beneficiary of the Micro-insurance Holder)\" required=\"\">\n                        </div>', '2024-10-27 16:08:20'),
(18, 'tupad form(1)(1)', '\n                            \n                        <div class=\"mb-3\" id=\"field-1\">\n                            <label class=\"form-check-label\">First Name:</label>\n                            <input type=\"text\" name=\"First Name\" class=\"form-control\" placeholder=\"Enter First Name\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-2\">\n                            <label class=\"form-check-label\">Middle Name:</label>\n                            <input type=\"text\" name=\"Middle Name\" class=\"form-control\" placeholder=\"Enter Middle Name\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-3\">\n                            <label class=\"form-check-label\">Last Name:</label>\n                            <input type=\"text\" name=\"Last Name\" class=\"form-control\" placeholder=\"Enter Last Name\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-4\">\n                            <label class=\"form-check-label\">Birthdate:</label>\n                            <input type=\"date\" name=\"Birthdate\" class=\"form-control\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-5\">\n                            <label class=\"form-check-label\">Address:</label>\n                            <input type=\"text\" name=\"Address\" class=\"form-control\" placeholder=\"Enter Address\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-6\">\n                            <label class=\"form-check-label\">Type of ID:</label>\n                            <select name=\"Type of ID\" class=\"form-select\" required=\"\">\n                                <option>SSS</option><option>Voter\'s ID</option>\n                            </select>\n                        </div><div class=\"mb-3\" id=\"field-7\">\n                            <label class=\"form-check-label\">ID Number:</label>\n                            <input type=\"text\" name=\"ID Number\" class=\"form-control\" placeholder=\"Enter ID Number\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-8\">\n                            <label class=\"form-check-label\">Contact No.:</label>\n                            <input type=\"text\" name=\"Contact No.\" class=\"form-control\" placeholder=\"Enter Contact No.\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-9\">\n                            <label class=\"form-check-label\">Type of Beneficiary:</label>\n                            <select name=\"Type of Beneficiary\" class=\"form-select\" required=\"\">\n                                <option>Students</option><option>Old Citizen</option><option>Women</option><option>Unemployed</option>\n                            </select>\n                        </div><div class=\"mb-3\" id=\"field-10\">\n                            <label class=\"form-check-label d-block\">Sex:</label>\n                            <div class=\"d-inline-flex\">\n                                <div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Sex\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Male</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Sex\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Female</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Sex\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Other</label>\n                </div>\n                            </div>\n                        </div><div class=\"mb-3\" id=\"field-11\">\n                            <label class=\"form-check-label d-block\">Civil Status:</label>\n                            <div class=\"d-inline-flex\">\n                                <div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Married</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Single</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Widow</label>\n                </div><div class=\"form-check form-check-inline\">\n                    <input type=\"radio\" name=\"Civil Status\" class=\"form-check-input\" required=\"\">\n                    <label class=\"form-check-label\">Divorced</label>\n                </div>\n                            </div>\n                        </div><div class=\"mb-3\" id=\"field-12\">\n                            <label class=\"form-check-label\">Age:</label>\n                            <input type=\"text\" name=\"Age\" class=\"form-control\" placeholder=\"Enter Age\" required=\"\">\n                        </div><div class=\"mb-3\" id=\"field-13\">\n                            <label class=\"form-check-label\">Dependent (name of Beneficiary of the Micro-insurance Holder):</label>\n                            <input type=\"text\" name=\"Dependent (name of Beneficiary of the Micro-insurance Holder)\" class=\"form-control\" placeholder=\"Enter Dependent (name of Beneficiary of the Micro-insurance Holder)\" required=\"\">\n                        </div>', '2024-10-28 04:24:27'),
(19, 'exercise', '\n                            \n                        <div class=\"mb-3\" id=\"field-1\">\n                            <label class=\"form-check-label\">name:</label>\n                            <input type=\"text\" name=\"name\" class=\"form-control\" placeholder=\"Enter name\" required=\"\">\n                        </div>', '2024-10-28 04:44:05'),
(21, 'exercise(1)', '\n                            \n                        <div class=\"mb-3\" id=\"field-1\">\n                            <label class=\"form-check-label\">name:</label>\n                            <input type=\"text\" name=\"name\" class=\"form-control\" placeholder=\"Enter name\" required=\"\">\n                        </div>', '2024-10-28 13:12:10'),
(22, 'form responsive', '\n                            \n                        <div class=\"mb-3 col-12 col-md-6\" id=\"field-1\">\n                            <label class=\"form-check-label\">Name:</label>\n                            <input type=\"text\" name=\"Name\" class=\"form-control\" placeholder=\"Enter Name\" required=\"\">\n                        </div><div class=\"mb-3 col-12 col-md-6\" id=\"field-2\">\n                            <label class=\"form-check-label\">MiddleName:</label>\n                            <input type=\"text\" name=\"MiddleName\" class=\"form-control\" placeholder=\"Enter MiddleName\" required=\"\">\n                        </div><div class=\"mb-3 col-12 col-md-6\" id=\"field-3\">\n                            <label class=\"form-check-label\">Last Name:</label>\n                            <input type=\"text\" name=\"Last Name\" class=\"form-control\" placeholder=\"Enter Last Name\" required=\"\">\n                        </div><div class=\"mb-3 col-12 col-md-6\" id=\"field-4\">\n                            <label class=\"form-check-label d-block\">Gender:</label>\n                            <div class=\"d-inline-flex\">\n                                <div class=\"form-check form-check-inline me-3\">\n                    <input type=\"radio\" name=\"Gender\" class=\"form-check-input\" value=\"Male\" required=\"\">\n                    <label class=\"form-check-label\">Male</label>\n                </div><div class=\"form-check form-check-inline me-3\">\n                    <input type=\"radio\" name=\"Gender\" class=\"form-check-input\" value=\"Female\" required=\"\">\n                    <label class=\"form-check-label\">Female</label>\n                </div>\n                            </div>\n                        </div><div class=\"mb-3 col-12 col-md-6\" id=\"field-5\">\n                            <label class=\"form-check-label\">Email:</label>\n                            <input type=\"email\" name=\"Email\" class=\"form-control\" placeholder=\"Enter Email\" required=\"\">\n                        </div><div class=\"mb-3 col-12 col-md-6\" id=\"field-7\">\n                            <label class=\"form-check-label\">Phone Number:</label>\n                            <input type=\"number\" name=\"Phone Number\" class=\"form-control\" placeholder=\"Enter Phone Number\" required=\"\">\n                        </div><div class=\"mb-3 col-12 col-md-6\" id=\"field-8\">\n                            <label class=\"form-check-label\">BirthDate:</label>\n                            <input type=\"date\" name=\"BirthDate\" class=\"form-control\" required=\"\">\n                        </div>', '2024-11-01 18:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `form_submissions`
--

CREATE TABLE `form_submissions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `submission_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`submission_data`)),
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_submissions`
--

INSERT INTO `form_submissions` (`id`, `form_id`, `program_id`, `user_id`, `submission_data`, `status`, `created_at`) VALUES
(12, 15, 54, 0, '{\"First_Name\":\"abe\",\"Middle_Name\":\"Sagranada\",\"Last_Name\":\"Edusma\",\"Birthdate\":\"2003-02-12\",\"Address\":\"qwerty\",\"Type_of_ID\":\"SSS\",\"ID_Number\":\"09876543212\",\"Contact_No_\":\"09876543211\",\"Type_of_Beneficiary\":\"Unemployed\",\"Sex\":\"on\",\"Civil_Status\":\"on\",\"Age\":\"122\",\"Dependent_(name_of_Beneficiary_of_the_Micro-insurance_Holder)\":\"Siony Edusma\"}', 'approved', '2024-10-28 04:13:07'),
(13, 18, 55, 0, '{\"First_Name\":\"john\",\"Middle_Name\":\"Sagranada\",\"Last_Name\":\"Edusma\",\"Birthdate\":\"2002-12-12\",\"Address\":\"qwerty\",\"Type_of_ID\":\"Voter\'s ID\",\"ID_Number\":\"09876543212\",\"Contact_No_\":\"09876543211\",\"Type_of_Beneficiary\":\"Students\",\"Sex\":\"on\",\"Civil_Status\":\"on\",\"Age\":\"12\",\"Dependent_(name_of_Beneficiary_of_the_Micro-insurance_Holder)\":\"Siony Edusma\"}', NULL, '2024-10-28 04:26:42'),
(24, 21, 55, 4, '{\"name\":\"john\"}', 'Pending', '2024-10-29 10:05:53'),
(25, 21, 55, 10, '{\"name\":\"john\"}', 'Pending', '2024-10-30 05:13:45'),
(26, 15, 54, 10, '{\"First_Name\":\"\",\"Middle_Name\":\"\",\"Last_Name\":\"\",\"Birthdate\":\"\",\"Address\":\"\",\"Type_of_ID\":\"SSS\",\"ID_Number\":\"\",\"Contact_No_\":\"\",\"Type_of_Beneficiary\":\"Students\",\"Age\":\"\",\"Dependent_(name_of_Beneficiary_of_the_Micro-insurance_Holder)\":\"\"}', 'Pending', '2024-10-30 16:35:14');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `email`, `message`, `created_at`, `status`) VALUES
(1, 'decksn907@gmail.com', 'hello', '2024-11-01 16:33:53', 'read'),
(2, 'hello@vv', 'hi', '2024-11-01 17:03:48', 'read'),
(10, 'ggrgtr@gmail.com', 'vcc', '2024-11-01 17:26:27', 'read'),
(11, 'cvc@fdd', 'dds', '2024-11-01 17:40:56', 'read'),
(12, 'bvv@dfd', 'gffg', '2024-11-01 17:42:12', 'read'),
(13, 'hi@fgfgf', 'fgfgffg', '2024-11-02 07:32:21', 'unread');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `small_description` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('draft','pending','approved','rejected') NOT NULL DEFAULT 'draft',
  `department_id` int(11) DEFAULT NULL,
  `program_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `published_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `beneficiary` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `guidelines` text NOT NULL,
  `requirements` text NOT NULL,
  `duration` varchar(255) NOT NULL,
  `location_format` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL,
  `schedule` varchar(255) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `form_id` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `title`, `description`, `beneficiary`, `status`, `guidelines`, `requirements`, `duration`, `location_format`, `contact_email`, `schedule`, `banner_image`, `form_id`, `department_id`, `created_at`) VALUES
(53, 'Cash For Work', '<div style=\"font-family: Consolas, \" courier=\"\" new\",=\"\" monospace;=\"\" font-size:=\"\" 14px;=\"\" line-height:=\"\" 19px;=\"\" white-space:=\"\" pre;\"=\"\"><p style=\"font-size: 16px;\">The <span style=\"font-weight: 700;\">Tulong Panghanapbuhay sa Ating Disadvantaged/Displaced Workers</span> <span style=\"font-weight: 700;\">(TUPAD)</span> program is administered by the <span style=\"font-weight: 700;\">Department of Labor and Employment (DOLE)</span> and offers temporary employment opportunities to workers who are in need of immediate income. It often targets marginalized sectors, such as those affected by disasters, pandemic-related job losses, and other adverse circumstances.</p><p></p></div>', 'Students', 'Active', '<p style=\"font-size: 16px;\">The <span style=\"font-weight: 700;\">Tulong Panghanapbuhay sa Ating Disadvantaged/Displaced Workers</span> <span style=\"font-weight: 700;\">(TUPAD)</span> program is administered by the <span style=\"font-weight: 700;\">Department of Labor and Employment (DOLE)</span> and offers temporary employment opportunities to workers who are in need of immediate income. It often targets marginalized sectors, such as those affected by disasters, pandemic-related job losses, and other adverse circumstances.</p>', '<p style=\"font-size: 16px;\">The <span style=\"font-weight: 700;\">Tulong Panghanapbuhay sa Ating Disadvantaged/Displaced Workers</span> <span style=\"font-weight: 700;\">(TUPAD)</span> program is administered by the <span style=\"font-weight: 700;\">Department of Labor and Employment (DOLE)</span> and offers temporary employment opportunities to workers who are in need of immediate income. It often targets marginalized sectors, such as those affected by disasters, pandemic-related job losses, and other adverse circumstances.</p>', 'may 20', 'school', '09345@fffdf', '2024-10-16', '../../../assets/img/uploads/dashboard local admin.png', 22, 82, '2024-10-26 09:33:35'),
(54, 'Tulong Panghanapbuhay sa Ating Disadvantaged/Displaced Workers (TUPAD)', '<p>The <b>Tulong Panghanapbuhay sa Ating Disadvantaged/Displaced Workers</b> <b>(TUPAD)</b> program is administered by the <strong>Department of Labor and Employment (DOLE)</strong> and offers temporary employment opportunities to workers who are in need of immediate income. It often targets marginalized sectors, such as those affected by disasters, pandemic-related job losses, and other adverse circumstances.</p><p><b>Benefits of TUPAD Program</b></p><ol><li><strong>Temporary Employment</strong>: Immediate job opportunities for disadvantaged workers.</li><li><strong>Daily Wages</strong>: Fair compensation at or near minimum wage.</li><li><strong>Skills Development</strong>: Training to enhance employability.</li><li><strong>Community Development</strong>: Participation in projects that improve local infrastructure and services.</li><li><strong>Access to Other Programs</strong>: Links to additional government assistance and training.</li><li><strong>Networking Opportunities</strong>: Connects workers with local organizations for future job prospects.</li><li><strong>Financial Relief</strong>: Helps families meet basic needs during unemployment.</li></ol>', 'Displaced Workers, Marginalized Sectors, Local Communities, Youth,', 'Active', '<p><strong style=\"font-size: 1rem; background-color: var(--bs-card-bg); text-align: var(--bs-body-text-align);\">Steps to Apply:</strong><br></p><blockquote><ul><li>Click the <strong>\"Apply Now\"</strong> button below.</li><li>Fill out the online application form with personal details, including full name, address, contact information, and proof of displacement.</li><li>Upload required documents, such as a valid ID and certification of displacement (if applicable).</li><li>Submit your application for review.</li></ul></blockquote><p><strong><a rel=\"noopener\" href=\"#\">Employment Duration</a></strong></p><blockquote><p>The TUPAD program offers short-term employment, typically lasting from a few days to several weeks, depending on project requirements.</p></blockquote><p><strong><a rel=\"noopener\" href=\"#\">Work Assignments</a></strong></p><blockquote><p>Work assignments may involve community-focused projects, including:</p><ul><li>Sanitation and waste management</li><li>Environmental protection</li><li>Road and infrastructure maintenance</li><li>Other public service activities</li></ul></blockquote><p><strong><a rel=\"noopener\" href=\"#\">Wage Rates</a></strong></p><blockquote><p>Daily wages align with the minimum wage in the region and are typically paid on a weekly basis.</p></blockquote><p><strong><a rel=\"noopener\" href=\"#\">Project Identification</a></strong></p><blockquote><p>Projects are selected based on community needs and fund availability, with a focus on high-impact local development.</p></blockquote>', 'not available yet', '10 days', 'not yet available', 'vvffdf@ff', '2024-10-30', '../../../assets/img/uploads/—Pngtree—red gradient banner background_1190598.jpg', 15, 82, '2024-10-26 10:50:40'),
(55, 'SPES', '<p>lorem</p>', 'residents', 'Active', '<p>lorem</p>', '<p>lorem</p>', 'dec 23', 'school', '09345@fffdfdd', '2025-02-12', '—Pngtree—red gradient banner background_1190598.jpg', 21, 82, '2024-10-28 03:51:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `middle_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `first_name`, `last_name`, `created_at`, `updated_at`, `middle_name`) VALUES
(4, 'john', '$2y$10$VltSnd32yVKF6/ok8eX5W.eTWqDdC10IaO5HD7ULw6.Nu/R5/wGSe', 'jdedusma@gmail.com', 'john Deckson', 'Edusma', '2024-10-15 14:48:48', '2024-10-15 14:48:48', 'Sagranada');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `local_admin_id` (`local_admin_id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `program_id` (`program_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `form_submissions`
--
ALTER TABLE `form_submissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`local_admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `form_submissions`
--
ALTER TABLE `form_submissions`
  ADD CONSTRAINT `form_submissions_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_created_by_fk` FOREIGN KEY (`created_by`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `news_department_fk` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `news_program_fk` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
