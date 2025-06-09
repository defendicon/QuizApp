-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 09, 2025 at 06:27 AM
-- Server version: 10.6.22-MariaDB-log
-- PHP Version: 8.3.21

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
(24, 10, 1, 'Chapter 1-THE SCIENCE OF BIOLOGY', 1, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:19:29', '2025-05-18 00:19:29'),
(25, 10, 1, 'Chapter 2- BIODIVERSITY', 2, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:20:54', '2025-05-18 00:20:54'),
(26, 10, 1, 'Chapter 3-THE CELL', 3, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:21:15', '2025-05-18 00:21:15'),
(27, 10, 1, 'Chapter 4- CELL CYCLE', 4, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:21:55', '2025-05-18 00:21:55'),
(28, 10, 1, 'Chapter 5- TISSUES, ORGANS, AND ORGAN SYSTEMS', 5, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:22:35', '2025-05-18 00:22:35'),
(29, 10, 1, 'Chapter 6- BIOMOLECULES', 6, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:22:50', '2025-05-18 00:22:50'),
(30, 10, 1, 'Chapter 7- ENZYMES', 7, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:23:12', '2025-05-18 00:23:12'),
(31, 10, 1, 'Chapter 8- BIOENERGETICS', 8, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:23:30', '2025-05-18 00:23:30'),
(32, 10, 1, 'Chapter 9- PLANT PHYSIOLOGY', 9, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:24:02', '2025-05-18 00:24:02'),
(33, 10, 1, 'Chapter 10-REPRODUCTION IN PLANTS', 10, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:25:21', '2025-05-18 00:25:21'),
(34, 10, 1, 'Chapter 11-BIOSTATISTICS', 11, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-18 00:25:36', '2025-05-18 00:25:36'),
(36, 12, 1, 'Enzymes', 1, NULL, NULL, 'active', 'intermediate', NULL, NULL, '2025-05-19 05:37:12', '2025-05-19 05:37:12');

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
(4, '1st Year', 'test@test.com', '2025-05-15 15:05:29', '2025-05-15 15:05:29'),
(10, '9th', 'Hassan.tariq771@gmail.com', '2025-05-18 00:17:55', '2025-05-18 00:17:55'),
(12, 'C1', 'Hassan.tariq771@gmail.com', '2025-05-19 05:36:12', '2025-05-19 05:36:12');

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
-- Dumping data for table `class_sections`
--

INSERT INTO `class_sections` (`id`, `class_id`, `section_name`, `created_at`, `updated_at`) VALUES
(7, 4, 'B', '2025-05-17 05:56:20', '2025-05-17 05:56:20'),
(10, 4, 'G', '2025-05-17 17:12:40', '2025-05-17 17:12:40'),
(14, 10, 'A+B', '2025-05-18 17:21:15', '2025-05-18 17:21:15'),
(15, 12, 'boys', '2025-05-19 05:36:45', '2025-05-19 05:36:45'),
(16, 12, 'girls', '2025-05-19 05:36:55', '2025-05-19 05:36:55');

-- --------------------------------------------------------

--
-- Table structure for table `dropdown`
--

CREATE TABLE `dropdown` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `options` text NOT NULL,
  `answer` varchar(255) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `essay`
--

CREATE TABLE `essay` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fillintheblanks`
--

CREATE TABLE `fillintheblanks` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` varchar(255) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
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
('Abdul Manan', 'am@am.com', '12345'),
('Hassan Tariq', 'Hassan.tariq771@gmail.com', 'hassan@nps'),
('t', 't@test.com', '123'),
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
  `chapter_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mcqdb`
--

INSERT INTO `mcqdb` (`id`, `question`, `optiona`, `optionb`, `optionc`, `optiond`, `answer`, `chapter_id`) VALUES
(12, 'Which domain of life is characterized by organisms that often inhabit extreme environments and have cell membranes with ether-linked lipids?', 'Bacteria', 'Archaea', 'Eukarya', 'Protista', 'B', 6),
(13, 'What is a key difference between the domains Bacteria and Archaea?', 'Bacteria have membrane-bound organelles, while Archaea do not', 'Bacterial cell walls have peptidoglycan, while Archaeal cell walls do not have it.', 'Archaea are only found in extreme environments, while Bacteria are not.', 'Bacteria are all unicellular, while Archaea include multicellular organisms', 'B', 6),
(14, 'Which of the following kingdoms includes organisms that are mostly unicellular, eukaryotic, and can be autotrophic or heterotrophic?', 'Fungi', 'Animalia', 'Plantae', 'Protoctista', 'D', 6),
(15, 'In which kingdom are organisms predominantly multicellular, autotrophic, and have cell walls made of cellulose?', 'Animalia', 'Fungi', 'Plantae', 'Protoctista', 'C', 6),
(16, 'Which of the following criteria is commonly used to classify viruses?', 'Their ability to cause specific diseases', 'The type of nucleic acid they contain', 'The colour of the virus particles', 'Their mode of transmission', 'B', 6),
(17, 'Which virus group includes viruses such as Coronaviruses and influenza viruses?', 'Double-stranded DNA viruses', 'Single-stranded DNA viruses', 'Double-stranded RNA viruses', 'Single-stranded RNA viruses', 'D', 6),
(18, 'At which level of biodiversity assessment do we evaluate the variety of different species within a particular habitat or ecosystem?', 'Genetic diversity', 'Ecosystem diversity', 'Species diversity', 'Functional diversity', 'C', 6),
(19, 'Which method is best suited for assessing the distribution of species across a gradient of environmental conditions within a single geographical area?', 'Quadrat Sampling', 'Point Counts', 'Transect Sampling', 'Remote Sensing', 'C', 6),
(20, 'Which of the following statements is true regarding the concept of a species?', 'A species is always defined by its physical characteristics alone.', 'Different species can interbreed and produce fertile offspring.', 'Members of the same species are reproductively isolated from members of other  species.', 'The concept of a species can be defined solely based on genetic similarity.', 'C', 6),
(48, 'What type of speciation occurs when populations are geographically separated by a physical barrier?', 'Sympatric Speciation', 'Parapatric Speciation', 'Allopatric Speciation', 'Peripatric Speciation', 'C', 6),
(49, 'Which branch of Biology focuses on the study of the structure and the function of cells?', 'Cytology', 'Microbiology', 'Histology', 'Ecology', 'A', 24),
(50, 'The study of the process of heredity and variation in living organisms is known as:', 'Ecology', 'Genetics', 'Anatomy', 'Embryology', 'B', 24),
(51, 'insulin made through bacteria is an example of the technique of:', 'Parasitology', 'Biotechnology', 'Biochemistry', 'Histology', 'B', 24),
(52, 'Heart pump blood, stomach digest food and kidneys excrete wastes. the statement comes from', 'physiology', 'Anatomy', 'Morphology', 'Histology', 'A', 24),
(53, 'Which branch of Biology involves the study of the classification of organisms?', 'Taxonomy', 'Physiology', 'Palaeontology', 'Biogeography', 'A', 24),
(54, 'Which step comes between making hypothesis and doing experiments?', 'Making deductions', 'Making observations', 'Summarizing results', 'Analysing data', 'A', 24),
(55, 'Which of the following is NOT a characteristic of the scientific method?', 'It relies on evidence', 'It involves formulating hypotheses', 'Hypothesis will always be correct', 'It requires rigorous testing', 'C', 24),
(56, 'Choose the correct sequence of steps of scientific method?', 'Observations - hypothesis - deduction - experiments', 'Observations - hypothesis - law- theory', 'Hypothesis - observations - deduction - experiments', 'Law - theory- deduction - observations', 'A', 24),
(57, 'People who slept near smoky fire had less chance to suffer from malaria, why?', 'Smoke kills Plasmodiumin their blood', 'Fire increases temperature and Plasmodium are killed in air', 'mosquitoes cannot tolerate smoke and are repelled', 'smoke kills plasmodium present in mosquitoes', 'C', 24),
(58, 'Experiments are very important in scientific method because a researcher:', 'Always gets correct results', 'Disprove many hypothesis and gets some hypothesis proved', 'Is sure that he will prove the hypothesis', 'Gets a chance to work in laboratory', 'B', 24),
(59, 'Which of the following component is not found in all kinds of bacteria?', 'Ribosomes', 'Cell membrane', 'Nucleoid', 'Capsule', 'C', 7),
(60, 'The bacterial chromosome is typically:', 'Circular, single-stranded RNA', 'Linear, double-stranded DNA', 'Circular, double-stranded DNA', 'Linear, single-stranded DNA', 'C', 7),
(61, 'In bacterial cells, respiration occurs at:', 'Mitochondria', 'Cell membrane', 'Ribosomes', 'Endoplasmic reticulum', 'B', 7),
(62, 'Which group of bacteria is known as a good source of antibiotics?', 'Omnibacteria', 'Spirochaetes', 'Pseudomonads', 'Actinomycetes', 'D', 7),
(63, 'What is the primary function of flagella in bacterial cells?', 'DNA replication', 'Motility', 'Protein synthesis', 'Cell division', 'B', 7),
(64, 'Which type of motility in bacteria is mediated by pili?', 'Brownian movement', 'Gliding motility', 'Swarming motility', 'Twitching motility', 'D', 7),
(65, 'Which of the following bacterial structures is responsible for detecting and responding to chemicals?', 'Capsule', 'Pili', 'Flagella', 'Ribosomes', 'C', 7),
(66, 'Which one of the following are not Nitrifying bacteria?', 'Nitrosomonas', 'Nitrobacter', 'Azotobacter', 'Pseudomonas', 'C', 7),
(67, 'The enzyme responsible for converting HIV RNA into DNA is:', 'RNA polymerase', 'Reverse transcriptase', 'DNA helicase', 'Integrase', 'B', 7),
(68, 'The HIV capsid contains:', 'Single-stranded DNA and reverse transcriptase', 'Single-stranded RNA and reverse transcriptase', 'Double-stranded DNA and integrase', 'Double-stranded RNA and RNA polymerase', 'B', 7),
(70, 'hthdhfghfgh', 'Single-stranded DNA and reverse transcriptase', 'Single-stranded RNA and reverse transcriptase', 'Double-stranded DNA and integrase', 'Double-stranded RNA and RNA polymerase', 'C', 36);

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
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `class_id`, `section_id`, `title`, `message`, `created_at`, `created_by`, `is_active`) VALUES
(1, 4, 7, 'notification', 'hello', '2025-05-18 16:39:08', 'test@test.com', 0),
(2, 4, 7, 'Quiz Starting time', 'Quiz of Biology Chapter 1 Will start at 9:00Am. ', '2025-05-18 17:07:45', 'Hassan.tariq771@gmail.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `numericaldb`
--

CREATE TABLE `numericaldb` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
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
  `topic_ids` text DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL COMMENT 'Target section for the quiz',
  `section_id` int(11) DEFAULT NULL,
  `is_random` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `quizconfig`
--

INSERT INTO `quizconfig` (`quizid`, `quiznumber`, `quizname`, `starttime`, `endtime`, `duration`, `attempts`, `mcq`, `numerical`, `dropdown`, `fill`, `short`, `essay`, `mcqmarks`, `numericalmarks`, `dropdownmarks`, `fillmarks`, `shortmarks`, `essaymarks`, `maxmarks`, `typea`, `typeamarks`, `typeb`, `typebmarks`, `typec`, `typecmarks`, `typed`, `typedmarks`, `typee`, `typeemarks`, `typef`, `typefmarks`, `total_questions`, `class_id`, `chapter_ids`, `topic_ids`, `subject_id`, `section`, `section_id`, `is_random`) VALUES
(98, 1, '1st', '2025-05-18 21:01:00', '2025-05-19 21:01:00', 10, 1, 5, 0, 0, 0, 5, 0, 1, 0, 0, 0, 2, 0, 15, 5, 1, 0, 0, 0, 0, 0, 0, 5, 2, 0, 0, 10, 4, '6', '', 1, 'B', NULL, 1),
(99, 2, '2nd', '2025-05-18 21:01:00', '2025-05-19 21:01:00', 10, 1, 5, 0, 0, 0, 5, 0, 1, 0, 0, 0, 2, 0, 15, 5, 1, 0, 0, 0, 0, 0, 0, 5, 2, 0, 0, 10, 4, '6', '', 1, 'B', NULL, 1),
(100, 3, '3rd', '2025-05-18 21:02:00', '2025-05-19 21:02:00', 10, 1, 5, 0, 0, 0, 5, 0, 1, 0, 0, 0, 2, 0, 15, 5, 1, 0, 0, 0, 0, 0, 0, 5, 2, 0, 0, 10, 4, '6', '', 1, 'B', NULL, 1),
(107, 4, '1st year chap 1', '2025-05-19 10:42:00', '2025-05-19 10:59:00', 10, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, '6,7', '', 1, 'B', NULL, 1),
(108, 5, 'testing', '2025-06-09 15:25:00', '2025-06-10 15:25:00', 10, 1, 5, 0, 0, 0, 0, 0, 5, 0, 0, 0, 0, 0, 25, 5, 5, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 4, '6', '', 1, 'B', NULL, 1);

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
(98, 2, 1, '2025-05-18 21:33:37', '2025-05-18 21:34:59'),
(99, 2, 1, '2025-05-18 21:35:03', '2025-05-18 21:35:36'),
(100, 1, 1, '2025-05-18 21:07:32', '2025-05-18 21:08:34'),
(100, 2, 1, '2025-05-18 21:32:51', '2025-05-18 21:33:33'),
(107, 1, 1, '2025-05-19 10:47:59', NULL),
(108, 1, 1, '2025-06-09 15:27:04', '2025-06-09 15:27:14');

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
-- Table structure for table `random_quiz_questions`
--

CREATE TABLE `random_quiz_questions` (
  `quizid` int(11) NOT NULL,
  `qtype` varchar(20) NOT NULL,
  `qid` int(11) NOT NULL,
  `serialnumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `random_quiz_questions`
--

INSERT INTO `random_quiz_questions` (`quizid`, `qtype`, `qid`, `serialnumber`) VALUES
(98, 'a', 13, 3),
(98, 'a', 16, 2),
(98, 'a', 17, 4),
(98, 'a', 19, 1),
(98, 'a', 20, 5),
(98, 'e', 2, 7),
(98, 'e', 4, 10),
(98, 'e', 5, 6),
(98, 'e', 7, 9),
(98, 'e', 8, 8),
(99, 'a', 13, 4),
(99, 'a', 15, 3),
(99, 'a', 16, 2),
(99, 'a', 18, 1),
(99, 'a', 19, 5),
(99, 'e', 2, 9),
(99, 'e', 3, 10),
(99, 'e', 4, 6),
(99, 'e', 6, 8),
(99, 'e', 8, 7),
(100, 'a', 12, 1),
(100, 'a', 13, 4),
(100, 'a', 17, 5),
(100, 'a', 20, 2),
(100, 'a', 48, 3),
(100, 'e', 3, 9),
(100, 'e', 4, 8),
(100, 'e', 5, 7),
(100, 'e', 7, 6),
(100, 'e', 8, 10),
(108, 'a', 12, 2),
(108, 'a', 14, 3),
(108, 'a', 15, 1),
(108, 'a', 18, 4),
(108, 'a', 20, 5);

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
(98, 2, 1, 'a', 13, 'B', 3),
(98, 2, 1, 'a', 16, 'B', 2),
(98, 2, 1, 'a', 17, 'A', 4),
(98, 2, 1, 'a', 19, 'C', 1),
(98, 2, 1, 'a', 20, 'A', 5),
(98, 2, 1, 'e', 2, 'sdgfdhh gg', 7),
(98, 2, 1, 'e', 4, '', 10),
(98, 2, 1, 'e', 5, 'fsgsg', 6),
(98, 2, 1, 'e', 7, '', 9),
(98, 2, 1, 'e', 8, '', 8),
(99, 2, 1, 'a', 13, 'B', 4),
(99, 2, 1, 'a', 15, 'D', 3),
(99, 2, 1, 'a', 16, 'B', 2),
(99, 2, 1, 'a', 18, 'C', 1),
(99, 2, 1, 'a', 19, 'C', 5),
(99, 2, 1, 'e', 2, '', 9),
(99, 2, 1, 'e', 3, '', 10),
(99, 2, 1, 'e', 4, 'fsdgfs', 6),
(99, 2, 1, 'e', 6, 'ggg', 8),
(99, 2, 1, 'e', 8, 'aaaa', 7),
(100, 1, 1, 'a', 12, 'B', 1),
(100, 1, 1, 'a', 13, 'B', 4),
(100, 1, 1, 'a', 17, 'D', 5),
(100, 1, 1, 'a', 20, 'C', 2),
(100, 1, 1, 'a', 48, 'C', 3),
(100, 1, 1, 'e', 3, '', 9),
(100, 1, 1, 'e', 4, '', 8),
(100, 1, 1, 'e', 5, '', 7),
(100, 1, 1, 'e', 7, '', 6),
(100, 1, 1, 'e', 8, '', 10),
(100, 2, 1, 'a', 12, 'B', 1),
(100, 2, 1, 'a', 13, 'B', 4),
(100, 2, 1, 'a', 17, 'C', 5),
(100, 2, 1, 'a', 20, 'C', 2),
(100, 2, 1, 'a', 48, 'B', 3),
(100, 2, 1, 'e', 3, '', 9),
(100, 2, 1, 'e', 4, '', 8),
(100, 2, 1, 'e', 5, '', 7),
(100, 2, 1, 'e', 7, '', 6),
(100, 2, 1, 'e', 8, '', 10),
(108, 1, 1, 'a', 12, '', 2),
(108, 1, 1, 'a', 14, '', 3),
(108, 1, 1, 'a', 15, '', 1),
(108, 1, 1, 'a', 18, '', 4),
(108, 1, 1, 'a', 20, '', 5);

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
(98, 2, 1, 3, 0, 0, 0, 0, 0),
(99, 2, 1, 4, 0, 0, 0, 0, 0),
(100, 1, 1, 5, 0, 0, 0, 0, 0),
(100, 2, 1, 3, 0, 0, 0, 0, 0),
(108, 1, 1, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `shortanswer`
--

CREATE TABLE `shortanswer` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `shortanswer`
--

INSERT INTO `shortanswer` (`id`, `question`, `answer`, `chapter_id`) VALUES
(2, 'What are the three domains of life and how do they differ in terms of cellular structure?', 'Three Domains', 6),
(3, 'Describe one key feature that differentiates Archaea from Bacteria', 'Archaea from Bacteria', 6),
(4, 'Which kingdom is characterized by organisms with chitin in their cell walls and that are mostly decomposers?', 'chitin, decomposers', 6),
(5, 'What type of speciation occurs when populations are geographically separated?', 'speciation', 6),
(6, 'What is the role of genetic drift in the process of speciation?', 'genetic drift, speciation', 6),
(7, 'What is the primary method used to assess species distribution along an environmental gradient?', 'species distribution', 6),
(8, 'Which level of biodiversity assessment involves evaluating the variety of ecosystems in a region?', 'biodiversity assessmen', 6),
(9, 'Define the Following branches of Biology genetics, Anatomy, Paleontology, Marine Biology, Pathology', 'Branches', 24),
(10, 'Which branch of Biology involves the study of the development of organisms from fertilization to birth or hatching?', 'development', 24),
(11, 'How is the profession of medicine and surgery different from animal husbandry?', 'difference', 24),
(12, 'Differentiate between Morphology and Physiology', 'Differentiate', 24),
(13, 'What is Computational Biology?', 'Computational Biology', 24),
(14, 'What is the role of observation and experimentation in the scientific method?', 'scientific method', 24),
(15, 'Link the study of Biology with that of Physics, Chemistry, Statistics, Geography, Economics and Computer Science.', 'link branches', 24),
(16, 'Explain how the study of Biology can lead to different professional studies.', 'Biology can lead to different professional studies.', 24),
(17, 'Science is a collaborative field in which scientists work together to share knowledge. Prove this statement by giving examples.', 'collaborative field', 24),
(18, 'How is a hypothesis converted to theory, law and principle?', 'hypothesis, theory, law and principle', 24),
(19, 'What are the basic steps a scientist adopts in order to solve a scientific problem?', 'scientific problem, steps', 24),
(20, 'Describe the work of different scientists in discovering the cause of malaria.', 'cause of malaria. work of different scientists', 24),
(21, 'Write a descriptive note on the experiments performed by Ross.', 'Ross experiment', 24),
(22, 'Write about the structural components of a bacterial cell wall and their arrangement.', 'bacterial cell wall', 7),
(23, 'Write the composition of the peptidoglycan layer in bacterial cell walls.', 'composition of the peptidoglycan layer', 7),
(24, 'What are mesosomes? What are their functions?', 'mesosomes', 7),
(25, 'How can plasmids be used in genetic engineering?', 'plasmids, genetic engineering', 7),
(26, 'Define sporulation.', 'sporulation', 7),
(27, 'What is the function of the bacterial capsule?', 'capsule', 7),
(28, 'Write the role of pili in bacterial cells. How do they differ from flagella?', 'pili , flagella', 7),
(29, 'What are plasmids, and how do they contribute to enabling bacteria to resistance against unfavourable conditions?', 'resistance against unfavourable conditions', 7),
(30, 'Write about the role of endospores in bacterial survival.', 'endospores , bacterial survival.', 7),
(31, 'What is the significance of lipopolysaccharides and lipoproteins in Gram-negative bacteria?', 'lipopolysaccharides and lipoproteins in Gram-negative bacteria', 7),
(32, 'How do spirochetes achieve motility?', 'spirochetes , motility', 7),
(33, 'Differentiate between twitching and gliding movements in bacterial motility.', 'twitching and gliding movements in bacteria', 7),
(34, 'How do bacteria without flagella achieve motility?', 'without flagella achieve motility', 7),
(35, 'What is the difference between swimming motility and swarming motility in bacteria?', 'swimming motility and swarming motility', 7);

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
  `section` varchar(50) DEFAULT NULL COMMENT 'Student section'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `studentinfo`
--

INSERT INTO `studentinfo` (`name`, `rollnumber`, `department`, `program`, `password`, `email`, `section_id`, `section`) VALUES
('ali', 1, '1st Year', '', '123', 'ali@test.com', 7, 'B'),
('husnain', 2, '1st Year', '', '123', 'husnain@test.com', 7, 'B'),
('alia', 3, '1st Year', '', '123', 'ali@test.com', 10, 'G'),
('fatima', 4, '1st Year', '', '123', 'fatima@test.com', 10, 'G'),
('ali', 322, '1st Year', '', '123', 'ali@gmail.com', 7, 'B');

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
(15, 'Computer'),
(16, 'stats');

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `topic_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `topic_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `topics`
--


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
-- Indexes for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_class_section` (`class_id`,`section_name`),
  ADD KEY `idx_class_id` (`class_id`);

--
-- Indexes for table `dropdown`
--
ALTER TABLE `dropdown`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_dropdown_chapter` (`chapter_id`),
  ADD KEY `idx_fk_dropdown_topic` (`topic_id`);

--
-- Indexes for table `essay`
--
ALTER TABLE `essay`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_essay_chapter` (`chapter_id`),
  ADD KEY `idx_fk_essay_topic` (`topic_id`);

--
-- Indexes for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_fillintheblanks_chapter` (`chapter_id`),
  ADD KEY `idx_fk_fillintheblanks_topic` (`topic_id`);

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
  ADD KEY `idx_fk_mcqdb_chapter` (`chapter_id`),
  ADD KEY `idx_fk_mcqdb_topic` (`topic_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notification_class` (`class_id`),
  ADD KEY `fk_notification_section` (`section_id`);

--
-- Indexes for table `numericaldb`
--
ALTER TABLE `numericaldb`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fk_numericaldb_chapter` (`chapter_id`),
  ADD KEY `idx_fk_numericaldb_topic` (`topic_id`);

--
-- Indexes for table `quizconfig`
--
ALTER TABLE `quizconfig`
  ADD PRIMARY KEY (`quizid`),
  ADD KEY `fk_quiz_class` (`class_id`),
  ADD KEY `fk_quiz_subject` (`subject_id`),
  ADD KEY `fk_quiz_section` (`section_id`);

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
-- Indexes for table `random_quiz_questions`
--
ALTER TABLE `random_quiz_questions`
  ADD PRIMARY KEY (`quizid`,`qtype`,`qid`);

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
  ADD KEY `idx_fk_shortanswer_chapter` (`chapter_id`),
  ADD KEY `idx_fk_shortanswer_topic` (`topic_id`);

--
-- Indexes for table `studentinfo`
--
ALTER TABLE `studentinfo`
  ADD PRIMARY KEY (`rollnumber`),
  ADD KEY `idx_section_id` (`section_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD UNIQUE KEY `subject_name_unique` (`subject_name`);

-- Indexes for table `topics`
ALTER TABLE `topics`
  ADD PRIMARY KEY (`topic_id`),
  ADD UNIQUE KEY `chapter_topic_unique` (`chapter_id`,`topic_name`),
  ADD KEY `idx_topic_chapter` (`chapter_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `chapter_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `class_sections`
--
ALTER TABLE `class_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `dropdown`
--
ALTER TABLE `dropdown`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `numericaldb`
--
ALTER TABLE `numericaldb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quizconfig`
--
ALTER TABLE `quizconfig`
  MODIFY `quizid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `shortanswer`
--
ALTER TABLE `shortanswer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

-- AUTO_INCREMENT for table `topics`
ALTER TABLE `topics`
  MODIFY `topic_id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for table `class_sections`
--
ALTER TABLE `class_sections`
  ADD CONSTRAINT `fk_section_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `dropdown`
--
ALTER TABLE `dropdown`
  ADD CONSTRAINT `fk_dropdown_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `essay`
--
ALTER TABLE `essay`
  ADD CONSTRAINT `fk_essay_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_essay_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fillintheblanks`
--
ALTER TABLE `fillintheblanks`
  ADD CONSTRAINT `fk_fillintheblanks_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fillintheblanks_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `mcqdb`
--
ALTER TABLE `mcqdb`
  ADD CONSTRAINT `fk_mcqdb_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mcqdb_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notification_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notification_section` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `numericaldb`
--
ALTER TABLE `numericaldb`
  ADD CONSTRAINT `fk_numericaldb_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_numericaldb_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quizconfig`
--
ALTER TABLE `quizconfig`
  ADD CONSTRAINT `fk_quiz_class` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quiz_section` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`id`) ON DELETE SET NULL,
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
-- Constraints for table `random_quiz_questions`
--
ALTER TABLE `random_quiz_questions`
  ADD CONSTRAINT `fk_random_quiz_quizid` FOREIGN KEY (`quizid`) REFERENCES `quizconfig` (`quizid`) ON DELETE CASCADE ON UPDATE CASCADE;

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
  ADD CONSTRAINT `fk_shortanswer_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_shortanswer_topic` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`topic_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `studentinfo`
--
ALTER TABLE `studentinfo`
  ADD CONSTRAINT `fk_student_section` FOREIGN KEY (`section_id`) REFERENCES `class_sections` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Constraints for table `topics`
ALTER TABLE `topics`
  ADD CONSTRAINT `fk_topics_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`chapter_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
