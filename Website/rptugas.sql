-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 15, 2025 at 01:34 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u709106317_rptugas`
--

-- --------------------------------------------------------

--
-- Table structure for table `forecast_hes`
--

CREATE TABLE `forecast_hes` (
  `id` int(10) NOT NULL,
  `current_suhu` varchar(10) NOT NULL,
  `level_suhu` varchar(10) NOT NULL,
  `trend_suhu` varchar(10) NOT NULL,
  `forecast_suhu` varchar(10) NOT NULL,
  `current_tekanan` varchar(10) NOT NULL,
  `level_tekanan` varchar(10) NOT NULL,
  `trend_tekanan` varchar(10) NOT NULL,
  `forecast_tekanan` varchar(10) NOT NULL,
  `current_kelembaban` varchar(10) NOT NULL,
  `level_kelembaban` varchar(10) NOT NULL,
  `trend_kelembaban` varchar(10) NOT NULL,
  `forecast_kelembaban` varchar(10) NOT NULL,
  `current_cahaya` varchar(10) NOT NULL,
  `level_cahaya` varchar(10) NOT NULL,
  `trend_cahaya` varchar(10) NOT NULL,
  `forecast_cahaya` varchar(10) NOT NULL,
  `current_angin` varchar(10) NOT NULL,
  `level_angin` varchar(10) NOT NULL,
  `trend_angin` varchar(10) NOT NULL,
  `forecast_angin` varchar(10) NOT NULL,
  `realtime` varchar(25) NOT NULL,
  `tanggal` varchar(30) NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `monitoring_power`
--

CREATE TABLE `monitoring_power` (
  `id` int(10) NOT NULL,
  `tegangan_dinamis` varchar(10) NOT NULL,
  `tegangan_statis` varchar(10) NOT NULL,
  `arus_dinamis` varchar(10) NOT NULL,
  `arus_statis` varchar(10) NOT NULL,
  `power_dinamis` varchar(10) NOT NULL,
  `power_statis` varchar(10) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `realtime` varchar(35) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `motor_driver`
--

CREATE TABLE `motor_driver` (
  `id` int(10) NOT NULL,
  `ldrselatan` varchar(10) NOT NULL,
  `ldrutara` varchar(10) NOT NULL,
  `ldrtimur` varchar(10) NOT NULL,
  `ldrbarat` varchar(10) NOT NULL,
  `axis_a` varchar(10) NOT NULL,
  `axis_b` varchar(10) NOT NULL,
  `realtime` varchar(35) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tipbucket`
--

CREATE TABLE `tipbucket` (
  `id` int(10) NOT NULL,
  `jumlah_tip` int(10) NOT NULL,
  `curah_hujan_hari_ini` varchar(10) NOT NULL,
  `curah_hujan_per_menit` varchar(10) NOT NULL,
  `curah_hujan_per_jam` varchar(10) NOT NULL,
  `curah_hujan_per_hari` varchar(10) NOT NULL,
  `cuaca` varchar(20) NOT NULL,
  `realtime` varchar(35) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weather`
--

CREATE TABLE `weather` (
  `id` int(10) NOT NULL,
  `suhu` varchar(10) NOT NULL,
  `altitude` varchar(10) NOT NULL,
  `tekanan` varchar(10) NOT NULL,
  `kelembaban` varchar(10) NOT NULL,
  `lux` varchar(10) NOT NULL,
  `raindrop` varchar(10) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `realtime` varchar(35) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wind_weather`
--

CREATE TABLE `wind_weather` (
  `id` int(10) NOT NULL,
  `arah_angin` varchar(10) NOT NULL,
  `rps` varchar(10) NOT NULL,
  `velocity_ms` varchar(10) NOT NULL,
  `velocity_kmh` varchar(10) NOT NULL,
  `realtime` varchar(35) NOT NULL,
  `tanggal` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forecast_hes`
--
ALTER TABLE `forecast_hes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monitoring_power`
--
ALTER TABLE `monitoring_power`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `motor_driver`
--
ALTER TABLE `motor_driver`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipbucket`
--
ALTER TABLE `tipbucket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather`
--
ALTER TABLE `weather`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wind_weather`
--
ALTER TABLE `wind_weather`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forecast_hes`
--
ALTER TABLE `forecast_hes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monitoring_power`
--
ALTER TABLE `monitoring_power`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `motor_driver`
--
ALTER TABLE `motor_driver`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tipbucket`
--
ALTER TABLE `tipbucket`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weather`
--
ALTER TABLE `weather`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wind_weather`
--
ALTER TABLE `wind_weather`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
