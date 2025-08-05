-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 07 juin 2024 à 08:15
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `telemedecine_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `configuration_systeme`
--

DROP TABLE IF EXISTS `configuration_systeme`;
CREATE TABLE IF NOT EXISTS `configuration_systeme` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Parametre` varchar(255) NOT NULL,
  `Description` text,
  `UniteMesure` varchar(50) DEFAULT NULL,
  `SeuilMin` decimal(10,2) DEFAULT NULL,
  `SeuilMax` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `configuration_systeme`
--

INSERT INTO `configuration_systeme` (`ID`, `Parametre`, `Description`, `UniteMesure`, `SeuilMin`, `SeuilMax`) VALUES
(1, 'SeuilAsphyxie', 'Threshold for asphyxia', '%', '90.00', '100.00'),
(2, 'SeuilHypoxemie', 'Threshold for hypoxemia', '%', '0.00', '93.00'),
(3, 'SeuilApnee', 'Threshold for apnea', '%', '0.00', '90.00'),
(4, 'SeuilHypopnee', 'Threshold for hypopnea', '%', '0.00', '50.00'),
(5, 'SeuilHyperoxie', 'Threshold for hyperoxia', '%', '99.00', '100.00'),
(6, 'SeuilCovid', 'Threshold for COVID-19', '%', '0.00', '90.00'),
(7, 'SeuilLargeurQRS', 'Threshold for normal QRS width', 'sec', '0.06', '0.10'),
(8, 'SeuilFrequCardiaque', 'Threshold for heart rate', 'bpm', '0.00', '350.00'),
(9, 'SeuilSPO2', 'Threshold for oxygen saturation', '%', '0.00', '100.00'),
(10, 'SeuilAirflow', 'Threshold for airflow', '%', '0.00', '100.00'),
(11, 'SeuilAsystole', 'Threshold for asystole', 'bpm', '0.00', '0.00'),
(12, 'SeuilFibrillationAtriale', 'Threshold for atrial fibrillation', 'bpm', '350.00', '1000.00'),
(13, 'SeuilRythmeIdioventriculaireAccelere', 'Threshold for accelerated idioventricular rhythm', 'bpm', '50.00', '120.00'),
(14, 'SeuilFlutterAtrial', 'Threshold for atrial flutter', 'bpm', '200.00', '350.00'),
(15, 'SeuilBradycardie', 'Threshold for bradycardia', 'bpm', '0.00', '60.00'),
(16, 'SeuilTachycardieVentriculairePolymorphe', 'Threshold for polymorphic ventricular tachycardia', 'bpm', '100.00', '300.00');

-- --------------------------------------------------------

--
-- Structure de la table `consultations`
--

DROP TABLE IF EXISTS `consultations`;
CREATE TABLE IF NOT EXISTS `consultations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `PatientNom` varchar(50) NOT NULL,
  `PatientPrenom` varchar(50) NOT NULL,
  `MedecinNom` varchar(50) NOT NULL,
  `MedecinPrenom` varchar(50) NOT NULL,
  `DateConsultation` datetime DEFAULT NULL,
  `Diagnostic` text,
  `Prescription` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `consultations`
--

INSERT INTO `consultations` (`ID`, `PatientNom`, `PatientPrenom`, `MedecinNom`, `MedecinPrenom`, `DateConsultation`, `Diagnostic`, `Prescription`) VALUES
(1, 'John', 'Emma', 'Dr. mokni', 'Mokni', '2024-05-30 10:00:00', 'Apnea Severe', 'Repos et médicaments antiviraux'),
(2, 'fatma', 'Zahra', 'Dr. mokni', 'Mokni', '2024-05-27 10:00:00', 'Apnea Moderate', 'Repos et médicaments antiviraux'),
(3, 'khadija', 'Abid', 'Dr. mokni', 'Mokni', '2024-05-27 10:00:00', 'Hypopnea', 'Repos et médicaments antiviraux'),
(4, 'yacine', 'Ben Said', 'Dr. mokni', 'Mokni', '2024-05-10 10:00:00', 'Hypoxemia', 'Repos et médicaments antiviraux');

-- --------------------------------------------------------

--
-- Structure de la table `donnees_sante`
--

DROP TABLE IF EXISTS `donnees_sante`;
CREATE TABLE IF NOT EXISTS `donnees_sante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `nom_patient` varchar(255) DEFAULT NULL,
  `spo2` int(11) DEFAULT NULL,
  `heart_rate` int(11) DEFAULT NULL,
  `ecg` float DEFAULT NULL,
  `etat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nom_patient` (`nom_patient`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `donnees_sante`
--

INSERT INTO `donnees_sante` (`id`, `timestamp`, `nom_patient`, `spo2`, `heart_rate`, `ecg`, `etat`) VALUES
(24, '2024-06-07 01:33:53', NULL, 45, 34, 5, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `health_assessments`
--

DROP TABLE IF EXISTS `health_assessments`;
CREATE TABLE IF NOT EXISTS `health_assessments` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) DEFAULT NULL,
  `assessment_value` int(11) DEFAULT NULL,
  `assessment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `patient_id` (`patient_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `health_assessments`
--

INSERT INTO `health_assessments` (`ID`, `patient_id`, `assessment_value`, `assessment_date`) VALUES
(1, 1, 3, '2024-06-06 21:56:40'),
(2, 1455693, 4, '2024-06-06 21:59:11'),
(3, 1455693, 4, '2024-06-06 22:00:44'),
(4, 1455693, 5, '2024-06-06 22:00:57'),
(5, 1455694, 4, '2024-06-06 22:10:31'),
(6, 1455694, 5, '2024-06-06 22:12:35'),
(7, 1455695, 4, '2024-06-06 22:38:07'),
(8, 1455693, 3, '2024-06-06 22:38:31'),
(9, 1455693, 3, '2024-06-06 23:07:17'),
(10, 1455696, 2, '2024-06-06 23:12:23'),
(11, 1455696, 3, '2024-06-06 23:15:06'),
(12, 1455696, 5, '2024-06-06 23:15:20'),
(13, 1455696, 4, '2024-06-06 23:17:38'),
(14, 1455696, 1, '2024-06-06 23:19:53'),
(15, 1455696, 1, '2024-06-06 23:19:55'),
(16, 1455696, 1, '2024-06-06 23:19:58'),
(17, 1455696, 1, '2024-06-06 23:19:59'),
(18, 1455696, 1, '2024-06-06 23:20:00'),
(19, 1455696, 1, '2024-06-06 23:20:37'),
(20, 1455696, 1, '2024-06-06 23:20:41'),
(21, 1455695, 1, '2024-06-06 23:21:08'),
(22, 1455693, 4, '2024-06-07 00:49:42'),
(23, 1455693, 4, '2024-06-07 00:51:11'),
(24, 1455693, 3, '2024-06-07 00:52:33'),
(25, 1455693, 3, '2024-06-07 01:34:09');

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `DateNaissance` date DEFAULT NULL,
  `Sexe` enum('M','F') DEFAULT NULL,
  `Adresse` varchar(100) DEFAULT NULL,
  `Telephone` varchar(20) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=MyISAM AUTO_INCREMENT=1455698 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `patients`
--

INSERT INTO `patients` (`ID`, `Nom`, `Prenom`, `DateNaissance`, `Sexe`, `Adresse`, `Telephone`, `Email`, `patient_id`) VALUES
(1455693, 'Fatma', 'zahra', '2024-06-04', 'F', 'tunis', '54042358', 'fatmazhra@gmail.com', NULL),
(1455697, 'Khouloud', 'Khouloud', '2024-05-14', 'F', 'Tunis', '96257006', 'khouloud@gmail', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Nom` varchar(255) NOT NULL,
  `Prenom` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Specialite` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=14515516 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`ID`, `Nom`, `Prenom`, `Email`, `Specialite`) VALUES
(14515515, '', '', '', '');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
