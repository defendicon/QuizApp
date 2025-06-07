-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2025 at 12:12 PM
-- Server version: 10.6.22-MariaDB-log
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hamrooq1_quiz`
--

-- --------------------------------------------------------

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `chapter_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `chapter_name` varchar(255) NOT NULL,
  `chapter_number` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `learning_objectives` text DEFAULT NULL,
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'active',
  `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'intermediate',
  `estimated_hours` int(11) DEFAULT NULL,
  `prerequisites` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `chapters`
--

INSERT INTO `chapters` (`chapter_id`, `class_id`, `subject_id`, `chapter_name`, `chapter_number`, `description`, `learning_objectives`, `status`, `difficulty_level`, `estimated_hours`, `prerequisites`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Introduction to Biology', 1, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 10:39:17', '2025-05-15 10:39:17'),
(2, 1, 1, 'Cell Biology', 2, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 10:39:17', '2025-05-15 10:39:17'),
(3, 1, 2, 'Atomic Structure', 1, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 10:39:17', '2025-05-15 10:39:17'),
(4, 1, 2, 'Chemical Bonding', 2, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 10:39:17', '2025-05-15 10:39:17'),
(6, 4, 1, 'Biodiversity and Classification', 1, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:05:44', '2025-05-15 15:05:44'),
(7, 4, 1, 'Bacteria and Viruses', 2, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:06:03', '2025-05-15 15:06:03'),
(8, 4, 1, 'Cells and Subcellular Organelles', 3, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:06:11', '2025-05-15 15:06:11'),
(9, 4, 1, 'Molecular Biology', 4, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:06:54', '2025-05-15 15:06:54'),
(10, 4, 1, 'Enzymes', 5, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:07:08', '2025-05-15 15:07:08'),
(11, 4, 1, 'Bioenergetics', 6, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:07:22', '2025-05-15 15:07:22'),
(12, 4, 1, 'Structural and Computational Biology', 7, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:07:38', '2025-05-15 15:07:38'),
(13, 4, 1, 'Plant Physiology', 8, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:08:00', '2025-05-15 15:08:00'),
(14, 4, 1, 'Human Digestive System', 9, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:08:14', '2025-05-15 15:08:14'),
(15, 4, 1, 'Human Respiratory System', 10, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:08:27', '2025-05-15 15:08:27'),
(16, 4, 1, 'Human Circulatory System', 11, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:08:40', '2025-05-15 15:08:40'),
(17, 4, 1, 'Human Skeletal and Muscular Systems', 12, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-15 15:08:55', '2025-05-15 15:08:55'),
(18, 4, 1, 'none', 13, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-16 05:38:30', '2025-05-16 05:38:30'),
(19, 6, 1, 'inheritance', 1, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-16 11:55:53', '2025-05-16 11:55:53');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `class_id` int(11) NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `instructor_email` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `instructor_email`, `created_at`, `updated_at`) VALUES
(1, '9th', 'test@test.com', '2025-05-15 10:39:17', '2025-05-15 10:39:17'),
(2, '10th', 'test@test.com', '2025-05-15 10:39:17', '2025-05-15 10:39:17'),
(4, '1st Year', 'test@test.com', '2025-05-15 15:05:29', '2025-05-15 15:05:29'),
(6, '2nd Year', 'test@test.com', '2025-05-16 11:55:23', '2025-05-16 11:55:23');

-- --------------------------------------------------------

--
-- Table structure for table `class_sections`
--

CREATE TABLE `class_sections` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_class_section` (`class_id`,`section_name`),
  ADD KEY `idx_class_id` (`class_id`);

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD CONSTRAINT `fk_section_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `dropdown`
--

CREATE TABLE `dropdown` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `options` text NOT NULL,
  `answer` varchar(255) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `essay`
--

CREATE TABLE `essay` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fillintheblanks`
--

CREATE TABLE `fillintheblanks` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` varchar(255) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructorinfo`
--

CREATE TABLE `instructorinfo` (
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `instructorinfo`
--

INSERT INTO `instructorinfo` (`name`, `email`, `password`) VALUES
('Test Instructor', 'test@test.com', 'test123');

-- --------------------------------------------------------

--
-- Table structure for table `mcqdb`
--

CREATE TABLE `mcqdb` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `optiona` varchar(255) NOT NULL,
  `optionb` varchar(255) NOT NULL,
  `optionc` varchar(255) NOT NULL,
  `optiond` varchar(255) NOT NULL,
  `answer` char(1) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mcqdb`
--

INSERT INTO `mcqdb` (`id`, `question`, `optiona`, `optionb`, `optionc`, `optiond`, `answer`, `chapter_id`) VALUES
(12, 'Which domain of life is characterized by organisms that often inhabit extreme \\r\\nenvironments and have cell membranes with ether-linked lipids?', 'Bacteria', 'Archaea', 'Eukarya', 'Protista', 'B', 6),
(13, 'What is a key difference between the domains Bacteria and Archaea?', 'Bacteria have membrane-bound organelles, while Archaea do not', 'Bacterial cell walls have peptidoglycan, while Archaeal cell walls do not have it.', 'Archaea are only found in extreme environments, while Bacteria are not.', 'Bacteria are all unicellular, while Archaea include multicellular organisms', 'B', 6),
(14, 'Which of the following kingdoms includes organisms that are mostly unicellular, \\r\\neukaryotic, and can be autotrophic or heterotrophic?', 'Fungi', 'Animalia', 'Plantae', 'Protoctista', 'D', 6),
(15, 'In which kingdom are organisms predominantly multicellular, autotrophic, and have \\r\\ncell walls made of cellulose?', 'Animalia', 'Fungi', 'Plantae', 'Protoctista', 'C', 6),
(16, 'Which of the following criteria is commonly used to classify viruses?', 'Their ability to cause specific diseases', 'The type of nucleic acid they contain', 'The colour of the virus particles', 'Their mode of transmission', 'B', 6),
(17, 'Which virus group includes viruses such as Coronaviruses and influenza viruses?', 'Double-stranded DNA viruses', 'Single-stranded DNA viruses', 'Double-stranded RNA viruses', 'Single-stranded RNA viruses', 'D', 6),
(18, 'At which level of biodiversity assessment do we evaluate the variety of different \\r\\nspecies within a particular habitat or ecosystem?', 'Genetic diversity', 'Ecosystem diversity', 'Species diversity', 'Functional diversity', 'C', 6),
(19, 'Which method is best suited for assessing the distribution of species across a \\r\\ngradient of environmental conditions within a single geographical area?', 'Quadrat Sampling', 'Point Counts', 'Transect Sampling', 'Remote Sensing', 'C', 6),
(20, 'Which of the following statements is true regarding the concept of a species?', 'A species is always defined by its physical characteristics alone.', 'Different species can interbreed and produce fertile offspring.', 'Members of the same species are reproductively isolated from members of other  species.', 'The concept of a species can be defined solely based on genetic similarity.', 'C', 6),
(21, 'What type of speciation occurs when populations are geographically separated by \\r\\na physical barrier?', 'Sympatric Speciation', 'Parapatric Speciation', 'Allopatric Speciation', 'Peripatric Speciation', 'C', 6);

-- --------------------------------------------------------

--
-- Table structure for table `numericaldb`
--

CREATE TABLE `numericaldb` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizconfig`
--

CREATE TABLE `quizconfig` (
  `quizid` int(11) NOT NULL,
  `quiznumber` int(11) NOT NULL,
  `quizname` varchar(50) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `duration` int(11) NOT NULL,
  `attempts` int(11) NOT NULL,
  `mcq` int(11) NOT NULL,
  `numerical` int(11) NOT NULL,
  `dropdown` int(11) NOT NULL,
  `fill` int(11) NOT NULL,
  `short` int(11) NOT NULL,
  `essay` int(11) NOT NULL,
  `mcqmarks` int(11) NOT NULL,
  `numericalmarks` int(11) NOT NULL,
  `dropdownmarks` int(11) NOT NULL,
  `fillmarks` int(11) NOT NULL,
  `shortmarks` int(11) NOT NULL,
  `essaymarks` int(11) NOT NULL,
  `maxmarks` int(11) NOT NULL DEFAULT 0,
  `typea` int(11) DEFAULT 0,
  `typeamarks` int(11) DEFAULT 0,
  `typeb` int(11) DEFAULT 0,
  `typebmarks` int(11) DEFAULT 0,
  `typec` int(11) DEFAULT 0,
  `typecmarks` int(11) DEFAULT 0,
  `typed` int(11) DEFAULT 0,
  `typedmarks` int(11) DEFAULT 0,
  `typee` int(11) DEFAULT 0,
  `typeemarks` int(11) DEFAULT 0,
  `typef` int(11) DEFAULT 0,
  `typefmarks` int(11) DEFAULT 0,
  `total_questions` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `chapter_ids` text DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL COMMENT 'Target section for the quiz',
  `is_random` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quizconfig`
--

INSERT INTO `quizconfig` (`quizid`, `quiznumber`, `quizname`, `starttime`, `endtime`, `duration`, `attempts`, `mcq`, `numerical`, `dropdown`, `fill`, `short`, `essay`, `mcqmarks`, `numericalmarks`, `dropdownmarks`, `fillmarks`, `shortmarks`, `essaymarks`, `maxmarks`, `typea`, `typeamarks`, `typeb`, `typebmarks`, `typec`, `typecmarks`, `typed`, `typedmarks`, `typee`, `typeemarks`, `typef`, `typefmarks`, `total_questions`, `class_id`, `chapter_ids`, `subject_id`, `section`, `is_random`) VALUES
(25, 1, 'biology', '2025-05-16 17:43:00', '2025-05-16 17:53:00', 10, 1, 5, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 5, 5, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 4, '6', 1, NULL, 1),
(26, 2, 'biology 2', '2025-05-16 17:45:00', '2025-05-16 17:55:00', 10, 1, 5, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 5, 5, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 4, '6', 1, NULL, 0),
(27, 3, 'bio', '2025-05-16 17:51:00', '2025-05-16 18:01:00', 10, 1, 10, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 10, 10, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 4, '6', 1, NULL, 1),
(28, 4, 'test', '2025-05-16 18:02:00', '2025-05-16 18:12:00', 10, 1, 4, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 4, 4, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 4, '6', 1, NULL, 1),
(29, 5, '1st chapter half', '2025-05-16 18:05:00', '2025-05-16 18:15:00', 10, 1, 4, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 4, 4, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 4, '6', 1, NULL, 1),
(30, 6, 'biology', '2025-05-16 22:08:00', '2025-05-16 22:18:00', 10, 1, 10, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 50, 10, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 4, '6', 1, 'A', 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizrecord`
--

CREATE TABLE `quizrecord` (
  `quizid` int(11) NOT NULL,
  `rollnumber` int(11) NOT NULL,
  `attempt` int(11) NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quizrecord`
--

INSERT INTO `quizrecord` (`quizid`, `rollnumber`, `attempt`, `starttime`, `endtime`) VALUES
(25, 121, 1, '2025-05-16 17:43:55', '2025-05-16 17:45:01'),
(26, 121, 1, '2025-05-16 17:46:42', '2025-05-16 17:47:19'),
(27, 1, 1, '2025-05-16 17:55:06', '2025-05-16 17:56:28'),
(27, 121, 1, '2025-05-16 17:51:02', '2025-05-16 17:51:41'),
(28, 1, 1, '2025-05-16 18:02:49', '2025-05-16 18:04:24'),
(29, 1, 1, '2025-05-16 18:06:20', '2025-05-16 18:08:09'),
(30, 123, 1, '2025-05-16 22:08:14', '2025-05-16 22:08:48');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_chapters`
--

CREATE TABLE `quiz_chapters` (
  `quiz_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response`
--

CREATE TABLE `response` (
  `quizid` int(11) NOT NULL,
  `rollnumber` int(11) NOT NULL,
  `attempt` int(11) NOT NULL,
  `qtype` varchar(20) NOT NULL,
  `qid` int(11) NOT NULL,
  `response` text NOT NULL,
  `serialnumber` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `response`
--

INSERT INTO `response` (`quizid`, `rollnumber`, `attempt`, `qtype`, `qid`, `response`, `serialnumber`) VALUES
(25, 121, 1, 'a', 14, 'A', 3),
(25, 121, 1, 'a', 15, 'A', 2),
(25, 121, 1, 'a', 16, 'A', 1),
(25, 121, 1, 'a', 19, 'A', 4),
(25, 121, 1, 'a', 20, 'A', 5),
(26, 121, 1, 'a', 12, 'C', 4),
(26, 121, 1, 'a', 16, 'C', 3),
(26, 121, 1, 'a', 18, 'C', 2),
(26, 121, 1, 'a', 19, 'C', 1),
(26, 121, 1, 'a', 20, 'C', 5),
(27, 1, 1, 'a', 12, 'A', 1),
(27, 1, 1, 'a', 13, 'A', 6),
(27, 1, 1, 'a', 14, 'B', 4),
(27, 1, 1, 'a', 15, 'B', 3),
(27, 1, 1, 'a', 16, 'B', 5),
(27, 1, 1, 'a', 17, 'B', 10),
(27, 1, 1, 'a', 18, 'C', 7),
(27, 1, 1, 'a', 19, 'B', 8),
(27, 1, 1, 'a', 20, 'B', 2),
(27, 1, 1, 'a', 21, 'C', 9),
(27, 121, 1, 'a', 12, 'C', 7),
(27, 121, 1, 'a', 13, 'C', 5),
(27, 121, 1, 'a', 14, 'C', 9),
(27, 121, 1, 'a', 15, 'C', 6),
(27, 121, 1, 'a', 16, 'C', 10),
(27, 121, 1, 'a', 17, 'C', 3),
(27, 121, 1, 'a', 18, 'C', 1),
(27, 121, 1, 'a', 19, 'C', 8),
(27, 121, 1, 'a', 20, 'C', 2),
(27, 121, 1, 'a', 21, 'C', 4),
(28, 1, 1, 'a', 12, 'B', 1),
(28, 1, 1, 'a', 13, 'B', 4),
(28, 1, 1, 'a', 16, 'B', 2),
(28, 1, 1, 'a', 19, 'C', 3),
(29, 1, 1, 'a', 13, 'B', 4),
(29, 1, 1, 'a', 14, 'D', 2),
(29, 1, 1, 'a', 16, 'B', 1),
(29, 1, 1, 'a', 19, 'C', 3),
(30, 123, 1, 'a', 12, 'C', 7),
(30, 123, 1, 'a', 13, 'C', 8),
(30, 123, 1, 'a', 14, 'C', 5),
(30, 123, 1, 'a', 15, 'C', 1),
(30, 123, 1, 'a', 16, '', 10),
(30, 123, 1, 'a', 17, 'C', 2),
(30, 123, 1, 'a', 18, 'C', 9),
(30, 123, 1, 'a', 19, 'C', 4),
(30, 123, 1, 'a', 20, 'C', 6),
(30, 123, 1, 'a', 21, 'C', 3);

-- --------------------------------------------------------

--
-- Table structure for table `result`
--

CREATE TABLE `result` (
  `quizid` int(11) NOT NULL,
  `rollnumber` int(11) NOT NULL,
  `attempt` int(11) NOT NULL,
  `mcqmarks` int(11) NOT NULL,
  `numericalmarks` int(11) NOT NULL,
  `dropdownmarks` int(11) NOT NULL,
  `fillmarks` int(11) NOT NULL,
  `shortmarks` int(11) NOT NULL,
  `essaymarks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `result`
--

INSERT INTO `result` (`quizid`, `rollnumber`, `attempt`, `mcqmarks`, `numericalmarks`, `dropdownmarks`, `fillmarks`, `shortmarks`, `essaymarks`) VALUES
(25, 121, 1, 0, 0, 0, 0, 0, 0),
(26, 121, 1, 3, 0, 0, 0, 0, 0),
(27, 1, 1, 3, 0, 0, 0, 0, 0),
(27, 121, 1, 5, 0, 0, 0, 0, 0),
(28, 1, 1, 4, 0, 0, 0, 0, 0),
(29, 1, 1, 4, 0, 0, 0, 0, 0),
(30, 123, 1, 25, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shortanswer`
--

CREATE TABLE `shortanswer` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `chapter_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `studentinfo`
--

CREATE TABLE `studentinfo` (
  `name` varchar(30) NOT NULL,
  `rollnumber` int(11) NOT NULL,
  `department` varchar(20) NOT NULL,
  `program` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL, 
  `section_id` int(11) DEFAULT NULL COMMENT 'Reference to class_sections table',
  `section` varchar(50) DEFAULT NULL COMMENT 'Legacy section field (for backward compatibility)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `studentinfo`
--

INSERT INTO `studentinfo` (`name`, `rollnumber`, `department`, `program`, `password`, `email`, `section`) VALUES
('ali', 1, '1st year', 'biology', 'ali', 'ali@ali.com', 'A'),
('Ahmad', 121, 'Biology', 'Science', '121', '121@121.com', 'B'),
('123', 123, 'science', '9th', '123', '123@123.com', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`) VALUES
(1, 'Biology'),
(2, 'Chemistry'),
(6, 'Computer'),
(3, 'Physics');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`chapter_id`),
  ADD UNIQUE KEY `uk_class_chapter_number` (`class_id`,`subject_id`,`chapter_number`),
  ADD UNIQUE KEY `uk_class_chapter_name` (`class_id`,`subject_id`,`chapter_name`),
  ADD KEY `idx_fk_class_id` (`class_id`),
  ADD KEY `idx_fk_subject_id` (`subject_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `instructor_email` (`instructor_email`);

--
-- Indexes for table `dropdown`
--
ALTER TABLE `dropdown`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_dropdown_chapter` (`chapter_id`);

--
-- Indexes for table `essay`
--
ALTER TABLE `essay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_essay_chapter` (`chapter_id`);

--
-- Indexes for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_fillintheblanks_chapter` (`chapter_id`);

--
-- Indexes for table `instructorinfo`
--
ALTER TABLE `instructorinfo`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `mcqdb`
--
ALTER TABLE `mcqdb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_mcqdb_chapter` (`chapter_id`);

--
-- Indexes for table `numericaldb`
--
ALTER TABLE `numericaldb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_numericaldb_chapter` (`chapter_id`);

--
-- Indexes for table `quizconfig`
--
ALTER TABLE `quizconfig`
  ADD PRIMARY KEY (`quizid`),
  ADD KEY `fk_quiz_class` (`class_id`),
  ADD KEY `fk_quiz_subject` (`subject_id`);

--
-- Indexes for table `quizrecord`
--
ALTER TABLE `quizrecord`
  ADD PRIMARY KEY (`quizid`,`rollnumber`,`attempt`),
  ADD KEY `rollnumber` (`rollnumber`);

--
-- Indexes for table `quiz_chapters`
--
ALTER TABLE `quiz_chapters`
  ADD PRIMARY KEY (`quiz_id`,`chapter_id`),
  ADD KEY `fk_quiz_chapter_chapter` (`chapter_id`);

--
-- Indexes for table `response`
--
ALTER TABLE `response`
  ADD PRIMARY KEY (`quizid`,`rollnumber`,`attempt`,`qtype`,`qid`),
  ADD KEY `rollnumber` (`rollnumber`),
  ADD KEY `idx_serialnumber` (`serialnumber`);

--
-- Indexes for table `result`
--
ALTER TABLE `result`
  ADD PRIMARY KEY (`quizid`,`rollnumber`,`attempt`),
  ADD KEY `rollnumber` (`rollnumber`);

--
-- Indexes for table `shortanswer`
--
ALTER TABLE `shortanswer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_shortanswer_chapter` (`chapter_id`);

--
-- Indexes for table `studentinfo`
--
ALTER TABLE `studentinfo`
  ADD PRIMARY KEY (`rollnumber`),
  ADD KEY `idx_section_id` (`section_id`);

--
-- Foreign keys for table `studentinfo`
--
ALTER TABLE `studentinfo`
  ADD CONSTRAINT `fk_student_section` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_name_unique` (`subject_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `dropdown`
--
ALTER TABLE `dropdown`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `essay`
--
ALTER TABLE `essay`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mcqdb`
--
ALTER TABLE `mcqdb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `numericaldb`
--
ALTER TABLE `numericaldb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizconfig`
--
ALTER TABLE `quizconfig`
  MODIFY `quizid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `shortanswer`
--
ALTER TABLE `shortanswer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chapters`
--
ALTER TABLE `chapters`
  ADD CONSTRAINT `fk_chapter_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_chapter_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`instructor_email`) REFERENCES `instructorinfo` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dropdown`
--
ALTER TABLE `dropdown`
  ADD CONSTRAINT `fk_dropdown_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `essay`
--
ALTER TABLE `essay`
  ADD CONSTRAINT `fk_essay_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  ADD CONSTRAINT `fk_fillintheblanks_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `mcqdb`
--
ALTER TABLE `mcqdb`
  ADD CONSTRAINT `fk_mcqdb_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `numericaldb`
--
ALTER TABLE `numericaldb`
  ADD CONSTRAINT `fk_numericaldb_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quizconfig`
--
ALTER TABLE `quizconfig`
  ADD CONSTRAINT `fk_quiz_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quiz_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quizrecord`
--
ALTER TABLE `quizrecord`
  ADD CONSTRAINT `quizrecord_ibfk_1` FOREIGN KEY (`quizid`) REFERENCES `quizconfig` (`quizid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `quizrecord_ibfk_2` FOREIGN KEY (`rollnumber`) REFERENCES `studentinfo` (`rollnumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_chapters`
--
ALTER TABLE `quiz_chapters`
  ADD CONSTRAINT `fk_quiz_chapter_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quiz_chapter_quiz` FOREIGN KEY (`quiz_id`) REFERENCES `quizconfig` (`quizid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `response`
--
ALTER TABLE `response`
  ADD CONSTRAINT `response_ibfk_1` FOREIGN KEY (`quizid`) REFERENCES `quizconfig` (`quizid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `response_ibfk_2` FOREIGN KEY (`rollnumber`) REFERENCES `studentinfo` (`rollnumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `result`
--
ALTER TABLE `result`
  ADD CONSTRAINT `result_ibfk_1` FOREIGN KEY (`quizid`) REFERENCES `quizconfig` (`quizid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `result_ibfk_2` FOREIGN KEY (`rollnumber`) REFERENCES `studentinfo` (`rollnumber`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shortanswer`
--
ALTER TABLE `shortanswer`
  ADD CONSTRAINT `fk_shortanswer_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` varchar(50) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notification_class` (`class_id`),
  ADD KEY `fk_notification_section` (`section_id`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notification_section` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
