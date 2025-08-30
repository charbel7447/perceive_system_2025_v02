-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 25, 2025 at 05:11 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zutlizte_app_system_v01`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `currency_id`, `name`, `balance`, `created_at`, `updated_at`) VALUES
(1, 1, 'USD Account', -10.5, NULL, '2025-08-03 10:13:15'),
(2, 2, 'OMR Account', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `account_items`
--

CREATE TABLE `account_items` (
  `id` int(191) NOT NULL,
  `account_id` int(255) DEFAULT NULL,
  `amount` float DEFAULT NULL,
  `document` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(191) NOT NULL,
  `log_name` text NOT NULL,
  `batch_uuid` varchar(255) DEFAULT NULL,
  `properties` text NOT NULL,
  `description` text NOT NULL,
  `causer_id` text,
  `causer_type` text,
  `text` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `batch_uuid`, `properties`, `description`, `causer_id`, `causer_type`, `text`, `created_at`, `updated_at`) VALUES
(1, 'default', NULL, '{\"customProperty\":\"2025-08-25T08:40:05.801579Z\"}', 'charbelkabbouchi@gmail.com', '1', 'App\\User', NULL, '2025-08-25 16:40:05', '2025-08-25 16:40:05'),
(2, 'default', NULL, '{\"customProperty\":\"2025-08-25T08:41:48.021935Z\"}', 'Log out', NULL, NULL, NULL, '2025-08-25 16:41:48', '2025-08-25 16:41:48'),
(3, 'default', NULL, '{\"customProperty\":\"2025-08-25T08:41:54.330312Z\"}', 'test@test.com', '11', 'App\\User', NULL, '2025-08-25 16:41:54', '2025-08-25 16:41:54'),
(4, 'default', NULL, '{\"customProperty\":\"2025-08-25T08:43:02.673300Z\"}', 'Log out', NULL, NULL, NULL, '2025-08-25 16:43:02', '2025-08-25 16:43:02'),
(5, 'default', NULL, '{\"customProperty\":\"2025-08-25T08:43:08.159015Z\"}', 'charbelkabbouchi@gmail.com', '1', 'App\\User', NULL, '2025-08-25 16:43:08', '2025-08-25 16:43:08'),
(6, 'default', NULL, '{\"customProperty\":\"2025-08-25T09:57:34.394895Z\"}', 'charbelkabbouchi@gmail.com', '1', 'App\\User', NULL, '2025-08-25 17:57:34', '2025-08-25 17:57:34');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `role` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'editor',
  `is_customer` int(255) DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `commission` float NOT NULL DEFAULT '0',
  `commission_balance` double DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_payments`
--

CREATE TABLE `advance_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `amount_received` decimal(8,2) DEFAULT NULL,
  `amount_received_usd` decimal(8,2) DEFAULT NULL,
  `amount_received_lbp` decimal(8,2) DEFAULT NULL,
  `exchangerate` decimal(8,2) DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `applied_date` date DEFAULT NULL,
  `applied_amount` decimal(8,2) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_payment_items`
--

CREATE TABLE `advance_payment_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `advance_payment_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_payment_report`
--

CREATE TABLE `advance_payment_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `advance_payment_report_items`
--

CREATE TABLE `advance_payment_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `advance_payment_id` text COLLATE utf8mb4_unicode_ci,
  `advance_payment_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `amount_received` double DEFAULT NULL,
  `amount_applied` float DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `invoice_id` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(255) DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total` decimal(8,2) NOT NULL,
  `subtotal` decimal(8,2) DEFAULT NULL,
  `totaltax` decimal(8,2) NOT NULL,
  `exchangerate` decimal(8,2) DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL,
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `amount_paid` decimal(8,2) NOT NULL DEFAULT '0.00',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bills_log`
--

CREATE TABLE `bills_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `items` text COLLATE utf8mb4_unicode_ci,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `manager_id` int(255) DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total` double NOT NULL,
  `subtotal` double DEFAULT NULL,
  `totaltax` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL,
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `amount_paid` double NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `bill_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `vendor_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `tax_name` text COLLATE utf8mb4_unicode_ci,
  `tax_rate` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `percentage_total` decimal(18,3) DEFAULT NULL,
  `additional_expenses` decimal(18,3) DEFAULT NULL,
  `final_cost_price` decimal(18,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_items_log`
--

CREATE TABLE `bill_items_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `bill_log_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `vendor_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` double(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` double NOT NULL,
  `tax_name` text COLLATE utf8mb4_unicode_ci,
  `tax_rate` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bill_item_taxes`
--

CREATE TABLE `bill_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `bill_item_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `tax_authority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'def.png',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_log`
--

CREATE TABLE `cache_log` (
  `id` int(11) NOT NULL,
  `body` text,
  `seller_id` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cart_log`
--

CREATE TABLE `cart_log` (
  `id` int(11) NOT NULL,
  `body` text,
  `seller_id` text,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int(11) DEFAULT '0',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `number`, `parent_id`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Main Category', '10', 0, 'Main Category', 'Charbel El Kabbouchi', '2022-05-19 08:35:42', '2022-05-19 08:35:42');

-- --------------------------------------------------------

--
-- Table structure for table `chart_accounts`
--

CREATE TABLE `chart_accounts` (
  `id` int(11) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `name_ar` varchar(255) DEFAULT NULL,
  `class_code` varchar(10) DEFAULT NULL,
  `vat_account_id` int(255) DEFAULT NULL,
  `vat_account_code` varchar(255) DEFAULT NULL,
  `vat_account_name` varchar(255) DEFAULT NULL,
  `credit` decimal(18,3) DEFAULT NULL,
  `debit` decimal(18,3) DEFAULT NULL,
  `balance` decimal(18,3) DEFAULT NULL,
  `currency_id` int(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chart_accounts`
--

INSERT INTO `chart_accounts` (`id`, `code`, `name_en`, `name_ar`, `class_code`, `vat_account_id`, `vat_account_code`, `vat_account_name`, `credit`, `debit`, `balance`, `currency_id`, `created_at`, `updated_at`) VALUES
(16, '1000', 'Cash', 'النقد', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(17, '1010', 'Bank Accounts', 'الحسابات البنكية', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(18, '1020', 'Accounts Receivable', 'المدينون', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(19, '1030', 'Inventory', 'المخزون', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(20, '1040', 'Prepaid Expenses', 'المصاريف المدفوعة مقدماً', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(21, '1050', 'Fixed Assets', 'الأصول الثابتة', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(22, '1051', 'Accumulated Depreciation', 'مجمع الإهلاك', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(23, '2200', 'VAT Input', 'ضريبة القيمة المضافة المدفوعة', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(24, '2000', 'Accounts Payable', 'الدائنون', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(25, '2010', 'Accrued Expenses', 'المصاريف المستحقة', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(26, '2020', 'Taxes Payable', 'الضرائب المستحقة', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(27, '2030', 'Unearned Revenue', 'الإيرادات المؤجلة', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(28, '2100', 'Loan Payable', 'القروض المستحقة', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(29, '2110', 'VAT Output', 'ضريبة القيمة المضافة المحصلة', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(30, '2120', 'Short-Term Loan', 'قرض قصير الأجل', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(31, '2130', 'Long-Term Loan', 'قرض طويل الأجل', '2', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(32, '3000', 'Owner Capital', 'رأس مال المالك', '3', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(33, '3100', 'Retained Earnings', 'الأرباح المحتجزة', '3', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(34, '3200', 'Drawings', 'المسحوبات', '3', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(35, '4000', 'Sales Revenue', 'إيرادات المبيعات', '4', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(36, '4010', 'Service Revenue', 'إيرادات الخدمات', '4', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(37, '4100', 'Discount Received', 'الخصومات المكتسبة', '4', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(38, '4200', 'Other Revenue', 'إيرادات أخرى', '4', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(39, '5000', 'Cost of Goods Sold', 'تكلفة البضاعة المباعة', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(40, '5100', 'Direct Labor', 'العمالة المباشرة', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(41, '5200', 'Freight In', 'مصاريف النقل الداخل', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(42, '5300', 'Salaries and Wages', 'الرواتب والأجور', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(43, '5400', 'Rent Expense', 'مصروف الإيجار', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(44, '5500', 'Utilities', 'المرافق', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(45, '5600', 'Office Supplies', 'المستلزمات المكتبية', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(46, '5700', 'Depreciation Expense', 'مصروف الإهلاك', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(47, '5800', 'Advertising Expense', 'مصاريف الدعاية', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(48, '5900', 'Telephone & Internet', 'الهاتف والإنترنت', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(49, '5950', 'Insurance Expense', 'مصاريف التأمين', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(50, '6000', 'Interest Income', 'دخل الفوائد', '6', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(51, '7000', 'Interest Expense', 'مصاريف الفوائد', '7', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(52, '7100', 'Bank Charges', 'الرسوم البنكية', '7', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(53, '8000', 'Contingent Liabilities', 'الخصوم المحتملة', '8', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(54, '8100', 'Provisions', 'المخصصات', '8', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(55, '9000', 'Headcount', 'عدد الموظفين', '9', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(56, '9100', 'Units Produced', 'عدد الوحدات المنتجة', '9', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-30 23:21:28', '2025-07-30 23:21:28'),
(1001, '5010', 'Salaries Expense', 'مصاريف الرواتب', '5', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:03:19', '2025-07-31 19:03:19'),
(1002, '5020', 'Rent Expense', 'مصاريف الإيجار', '5', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:03:19', '2025-07-31 19:03:19'),
(1003, '5025', 'Office Expense', 'مصاريف المكتب', '5', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:03:19', '2025-07-31 19:03:19'),
(1004, '5030', 'Utilities Expense', 'مصاريف المرافق', '5', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:03:19', '2025-07-31 19:03:19'),
(1005, '5700', 'Depreciation Expense', 'مصاريف الإهلاك', '5', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:03:19', '2025-07-31 19:03:19'),
(1007, '6030', 'Foreign Exchange Gain', 'أرباح صرف العملات', '6', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:03:19', '2025-07-31 19:03:19'),
(1009, '1600', 'Fixed Assets', 'الأصول الثابتة', '1', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:06:28', '2025-07-31 19:06:28'),
(1010, '1610', 'Furniture & Fixtures', 'الأثاث والتجهيزات', '1', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:06:28', '2025-07-31 19:06:28'),
(1011, '1620', 'Equipment', 'المعدات', '1', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:06:28', '2025-07-31 19:06:28'),
(1012, '1630', 'Vehicles', 'المركبات', '1', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:06:28', '2025-07-31 19:06:28'),
(1013, '1650', 'Buildings', 'المباني', '1', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:06:28', '2025-07-31 19:06:28'),
(1014, '1660', 'Land', 'الأراضي', '1', NULL, NULL, NULL, 0.000, 0.000, 0.000, 1, '2025-07-31 19:06:28', '2025-07-31 19:06:28'),
(1015, '60101', 'Purchase of Goods', 'Purchase of Goods', '6', 2, NULL, NULL, NULL, NULL, NULL, 1, '2025-08-11 19:52:36', '2025-08-11 19:53:56'),
(1016, '60184', 'customs broker', 'customs broker', '6', 2, NULL, NULL, NULL, NULL, NULL, 1, '2025-08-11 19:53:32', '2025-08-11 19:53:32'),
(1017, '60185', 'Customs Tariffs', 'Customs Tariffs', '6', 1020, '1141', 'VAT Paid on Purchases', NULL, NULL, NULL, 1, '2025-08-11 19:53:49', '2025-08-13 20:05:56'),
(1018, '46101', 'Payables on Consignments', 'مستحقات على الشحنات', '4', NULL, NULL, NULL, NULL, NULL, NULL, 2, '2025-08-11 20:02:53', '2025-08-11 20:03:28'),
(1019, '46102', 'Other Operating Creditor Accounts', 'حسابات دائنة تشغيلية أخرى', '4', 1020, '1141', 'VAT Paid on Purchases', NULL, NULL, NULL, 1, '2025-08-11 20:03:20', '2025-08-18 20:12:47'),
(1020, '1141', 'VAT Paid on Purchases', 'ضريبة مسددة على شراء السلع', '1', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-08-12 23:22:27', '2025-08-13 20:02:42'),
(1025, '2900000', 'company1', 'شسيشسي', '5', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-08-22 19:11:03', '2025-08-22 19:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `chart_classes`
--

CREATE TABLE `chart_classes` (
  `code` varchar(10) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `name_ar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `chart_classes`
--

INSERT INTO `chart_classes` (`code`, `name_en`, `name_ar`) VALUES
('1', 'Assets', 'الأصول'),
('2', 'Liabilities', 'الخصوم'),
('3', 'Equity', 'رأس المال'),
('4', 'Revenue', 'الإيرادات'),
('5', 'Expenses', 'المصاريف'),
('6', 'Other Income', 'إيرادات أخرى'),
('7', 'Other Expenses', 'مصاريف أخرى'),
('8', 'Off-Balance Sheet Accounts', 'حسابات خارج الميزانية'),
('9', 'Statistical Accounts', 'الحسابات الإحصائية');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(255) NOT NULL,
  `ref_number` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `email_verified` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email_verify_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `state` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `updated_at` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field3` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field4` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field5` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field6` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field7` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field8` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field9` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field10` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `allow_mobile` int(255) DEFAULT NULL,
  `loyalty_point` float DEFAULT NULL,
  `company` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `person` varchar(255) COLLATE utf8_bin DEFAULT '0',
  `currency_id` int(255) DEFAULT NULL,
  `vat_status` int(255) DEFAULT NULL,
  `total_revenue` float DEFAULT NULL,
  `unused_credit` float DEFAULT NULL,
  `work_phone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `billing_address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `shipping_address` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `account_id` int(255) DEFAULT NULL,
  `account_code` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `to_be_paid` decimal(8,2) DEFAULT NULL,
  `paid` decimal(8,2) DEFAULT NULL,
  `balance` decimal(8,2) DEFAULT NULL,
  `last_payment_date` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `is_customer` int(255) DEFAULT NULL,
  `balance_status` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `price_class` int(255) DEFAULT NULL,
  `tax_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `paymentcondition_id` int(255) NOT NULL DEFAULT '1',
  `deliverycondition_id` int(255) NOT NULL DEFAULT '1',
  `paymentcondition_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `deliverycondition_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `postal_code` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` int(255) NOT NULL DEFAULT '1',
  `client_dropdown_1_id` int(255) DEFAULT NULL,
  `client_dropdown_2_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `clients__`
--

CREATE TABLE `clients__` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(255) DEFAULT NULL,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `person` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `vat_status` int(11) DEFAULT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `total_revenue` double NOT NULL DEFAULT '0',
  `unused_credit` double NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `field1` text COLLATE utf8mb4_unicode_ci,
  `field2` text COLLATE utf8mb4_unicode_ci,
  `field3` text COLLATE utf8mb4_unicode_ci,
  `field4` text COLLATE utf8mb4_unicode_ci,
  `field5` text COLLATE utf8mb4_unicode_ci,
  `field6` text COLLATE utf8mb4_unicode_ci,
  `field7` text COLLATE utf8mb4_unicode_ci,
  `field8` text COLLATE utf8mb4_unicode_ci,
  `field9` text COLLATE utf8mb4_unicode_ci,
  `field10` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_balance_report`
--

CREATE TABLE `client_balance_report` (
  `id` int(11) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client_balance_report_items`
--

CREATE TABLE `client_balance_report_items` (
  `id` int(11) NOT NULL,
  `client_balance_report_id` int(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `seller_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `balance` float DEFAULT NULL,
  `balance_30` float DEFAULT NULL,
  `balance_60` float DEFAULT NULL,
  `balance_90` float DEFAULT NULL,
  `balance_00` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `client_balance_schedule`
--

CREATE TABLE `client_balance_schedule` (
  `id` int(11) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_dropdown1`
--

CREATE TABLE `client_dropdown1` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `client_dropdown1`
--

INSERT INTO `client_dropdown1` (`id`, `name`, `created_at`, `updated_at`, `user_id`, `created_by`) VALUES
(1, 'Yes', '2024-12-18 02:15:04', '2024-12-18 02:15:04', 1, 'Charbel El Kabbouchi'),
(2, 'No', '2024-12-18 02:15:09', '2024-12-18 02:15:09', 1, 'Charbel El Kabbouchi');

-- --------------------------------------------------------

--
-- Table structure for table `client_dropdown2`
--

CREATE TABLE `client_dropdown2` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_payments`
--

CREATE TABLE `client_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `items1` text COLLATE utf8mb4_unicode_ci,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `amount_received` decimal(8,2) NOT NULL,
  `amount_received_usd` decimal(8,2) DEFAULT NULL,
  `amount_received_lbp` decimal(8,2) DEFAULT NULL,
  `amount_received_lbprate` decimal(8,2) DEFAULT NULL,
  `vat_paid` decimal(8,2) DEFAULT '0.00',
  `payment_date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `field1` text COLLATE utf8mb4_unicode_ci,
  `field2` text COLLATE utf8mb4_unicode_ci,
  `field3` text COLLATE utf8mb4_unicode_ci,
  `field4` text COLLATE utf8mb4_unicode_ci,
  `field5` text COLLATE utf8mb4_unicode_ci,
  `field6` text COLLATE utf8mb4_unicode_ci,
  `field7` text COLLATE utf8mb4_unicode_ci,
  `field8` text COLLATE utf8mb4_unicode_ci,
  `field9` text COLLATE utf8mb4_unicode_ci,
  `field20` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_payments_log`
--

CREATE TABLE `client_payments_log` (
  `id` int(11) NOT NULL,
  `comment` text,
  `body` text,
  `items` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `client_payment_items`
--

CREATE TABLE `client_payment_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_payment_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` decimal(8,2) NOT NULL,
  `amount_applied_lbp` decimal(8,2) DEFAULT '0.00',
  `amount_applied_lbp_rate` decimal(8,2) DEFAULT '1.00',
  `amount_applied_vat` decimal(8,2) DEFAULT '0.00',
  `amount_applied_vat_rate` decimal(8,2) DEFAULT '1.00',
  `vat_paid` decimal(8,2) DEFAULT '0.00',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total` decimal(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_payment_report`
--

CREATE TABLE `client_payment_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_payment_report_items`
--

CREATE TABLE `client_payment_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `client_payment_id` text COLLATE utf8mb4_unicode_ci,
  `client_payment_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `amount_received` double DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `amount_applied_lbp` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `invoice_id` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_orders`
--

CREATE TABLE `container_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `container_size` float DEFAULT NULL,
  `shipper_fees` float DEFAULT NULL,
  `total_weight` float DEFAULT NULL,
  `total_volume` float DEFAULT NULL,
  `total_qty` float DEFAULT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `shipper_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `delivery_time` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `total` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL,
  `totaltax` double DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `vat_status` tinyint(4) DEFAULT NULL,
  `is_received` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `declined_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping` float DEFAULT NULL,
  `discount` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_order_items`
--

CREATE TABLE `container_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(255) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `current_stock` float DEFAULT NULL,
  `container_order_id` int(10) UNSIGNED NOT NULL,
  `purchase_order_id` int(255) DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `vendor_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_rate` float DEFAULT NULL,
  `uom_id` int(255) DEFAULT NULL,
  `uom2_id` int(255) DEFAULT NULL,
  `qty_received` float DEFAULT NULL,
  `volume_box` float DEFAULT NULL,
  `weight_box` float DEFAULT NULL,
  `ct_box` float DEFAULT NULL,
  `shipper_id` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_order_item_products`
--

CREATE TABLE `container_order_item_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `container_order_item_id` int(10) UNSIGNED NOT NULL,
  `purchase_order_item_id` int(255) DEFAULT NULL,
  `purchase_order_item_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` int(255) DEFAULT NULL,
  `vendor_id` int(255) DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `unit_price` float DEFAULT NULL,
  `uom_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_received` float DEFAULT NULL,
  `tax_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_rate` float DEFAULT NULL,
  `weight_box` float DEFAULT NULL,
  `volume_box` float DEFAULT NULL,
  `ct_box` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_order_report`
--

CREATE TABLE `container_order_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_order_report_items`
--

CREATE TABLE `container_order_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `container_order_id` text COLLATE utf8mb4_unicode_ci,
  `container_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `shipper_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_receive_orders`
--

CREATE TABLE `container_receive_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `shipper_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `container_order_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `container_receive_order_items`
--

CREATE TABLE `container_receive_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `container_receive_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `container_order_item_id` int(10) UNSIGNED NOT NULL,
  `quantity` double(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom2_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `counters`
--

CREATE TABLE `counters` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `counters`
--

INSERT INTO `counters` (`id`, `key`, `prefix`, `value`, `created_at`, `updated_at`) VALUES
(1, 'quotation', 'QT-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(2, 'sales_order', 'SO-', '2500000', '2021-08-07 18:00:00', '2025-08-01 14:54:00'),
(3, 'advance_payment', 'AD-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(4, 'invoice', 'IN-', '2500000', '2021-08-07 18:00:00', '2022-09-07 21:20:45'),
(5, 'client_payment', 'CP-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(6, 'expense', 'EX-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(7, 'purchase_order', 'PO-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(8, 'bill', 'BL-', '2500001', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(9, 'vendor_payment', 'VP-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(10, 'receive_order', 'RO-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(11, 'goods_issue', 'GI-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(12, 'credit_notes', 'CN-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(13, 'debit_notes', 'DN-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(14, 'transfer_accounts', 'TA-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(15, 'payroll', 'PAY-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(16, 'deposit', 'DP-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(17, 'return_deposit', 'RDP-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(18, 'category', '', '10', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(19, 'subcategory', '', '41', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(20, 'subsubcategory', '-', '100', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(21, 'warehouse', 'WH-', '2500000', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(22, 'product', NULL, '2500004', '2021-08-07 18:00:00', '2023-09-16 14:19:35'),
(23, 'machines', 'RS-', '100', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(24, 'product_type', '', '100', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(25, 'finished_product', '', '100', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(26, 'attributes', 'AT-', '1000', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(27, 'raw_material_type', 'RMT-', '1001', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(28, 'damaged_request', 'DR--', '2500000', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(29, 'notifications', 'N-', '2500061', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(30, 'seller_payments', 'SP-', '2500000', NULL, NULL),
(31, 'seller_payments_docs', 'SPD-', '2500000', NULL, NULL),
(32, 'container_order', 'CO-', '2500000', NULL, NULL),
(33, 'container_receive_order', 'CRO-', '2500000', NULL, NULL),
(34, 'shipper_bill', 'SBL-', '2500000', NULL, NULL),
(35, 'shipper_payment', 'SPP-', '2500000', NULL, NULL),
(36, 'customer_returns', 'CRR-', '2500000', NULL, NULL),
(37, 'journal_vouchers', 'JV-', '2500001', NULL, NULL),
(38, 'third_parties_extras', 'TPE-', '25000001', NULL, NULL),
(39, 'vat_account', 'VA-', '25000001', NULL, NULL),
(40, 'receipt_voucher', 'RV-', '2500000', NULL, NULL),
(41, 'payment_voucher', 'PV-', '2500000', NULL, NULL),
(42, 'clients_code', NULL, '2900004', NULL, NULL),
(43, 'vendors_code', NULL, '2900001', NULL, NULL),
(44, 'product_lots', NULL, '711', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes`
--

CREATE TABLE `credit_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `amount_received` decimal(8,2) DEFAULT NULL,
  `amount_received_lbp` decimal(8,2) DEFAULT NULL,
  `exchangerate` decimal(8,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `applied_date` date DEFAULT NULL,
  `vat_received_lbp` decimal(18,3) NOT NULL DEFAULT '0.000',
  `vat_received_usd` decimal(18,3) NOT NULL DEFAULT '0.000',
  `applied_amount` decimal(8,2) DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `balance` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total` decimal(18,3) NOT NULL DEFAULT '0.000',
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_notes_items`
--

CREATE TABLE `credit_notes_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `credit_notes_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_note_report`
--

CREATE TABLE `credit_note_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `credit_note_report_items`
--

CREATE TABLE `credit_note_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `credit_note_id` text COLLATE utf8mb4_unicode_ci,
  `credit_note_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `amount_received` double DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `amount_applied_lbp` double DEFAULT NULL,
  `amount_applied_vat` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `invoice_id` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `decimal_place` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `code`, `name`, `decimal_place`, `created_at`, `updated_at`) VALUES
(1, 'USD', 'US Dollar', 3, '2021-06-30 18:00:00', '2021-06-30 18:00:00'),
(2, 'OMR', 'Omani Rial', 5, '2025-07-30 21:28:41', '2025-07-30 21:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_returns`
--

CREATE TABLE `customer_returns` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_returns_report`
--

CREATE TABLE `customer_returns_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_returns_report_items`
--

CREATE TABLE `customer_returns_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `customer_return_id` text COLLATE utf8mb4_unicode_ci,
  `customer_return_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty_returned` decimal(8,2) DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `invoice_item_id` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `exchangerate` double DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_return_items`
--

CREATE TABLE `customer_return_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `receive_order_id` int(10) UNSIGNED NOT NULL,
  `item_id` int(10) UNSIGNED NOT NULL,
  `invoice_item_id` int(10) UNSIGNED NOT NULL,
  `invoiced_qty` decimal(8,2) NOT NULL,
  `qty` decimal(8,2) DEFAULT NULL,
  `qty_returned` decimal(8,2) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `damaged`
--

CREATE TABLE `damaged` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `damaged_items`
--

CREATE TABLE `damaged_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `damaged_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `transfer_qty` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `dashboard_views`
--

CREATE TABLE `dashboard_views` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `active` int(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dashboard_views`
--

INSERT INTO `dashboard_views` (`id`, `name`, `link`, `class`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Sales', '/system/sales_dashboard', 'Dashboard_View_1', 0, NULL, NULL),
(2, 'Accounting', '/system/accounting_dashboard', 'Dashboard_View_1', 0, NULL, NULL),
(3, 'Procurment & Stock', '/system/procurment_dashboard', 'Dashboard_View_1', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes`
--

CREATE TABLE `debit_notes` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `amount_received` decimal(8,2) DEFAULT NULL,
  `amount_received_lbp` decimal(8,2) DEFAULT NULL,
  `exchangerate` decimal(8,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `applied_date` date DEFAULT NULL,
  `applied_amount` decimal(8,2) DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `balance` decimal(18,3) NOT NULL DEFAULT '0.000',
  `total` decimal(18,3) NOT NULL DEFAULT '0.000',
  `vat_received_lbp` decimal(18,3) NOT NULL DEFAULT '0.000',
  `vat_received_usd` decimal(18,3) NOT NULL DEFAULT '0.000',
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debit_notes_items`
--

CREATE TABLE `debit_notes_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `debit_notes_id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debit_note_report`
--

CREATE TABLE `debit_note_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `debit_note_report_items`
--

CREATE TABLE `debit_note_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `debit_note_id` text COLLATE utf8mb4_unicode_ci,
  `debit_note_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `amount_received` double DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `amount_applied_lbp` double DEFAULT NULL,
  `amount_applied_vat` double DEFAULT NULL,
  `invoice_id` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliverycondition`
--

CREATE TABLE `deliverycondition` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliverycondition`
--

INSERT INTO `deliverycondition` (`id`, `name`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'Delivery Condition', 1, 'Charbel El Kabbouchi', '2023-10-03 14:08:38', '2023-10-03 14:08:38'),
(2, 'To Client Warehouse', 1, 'Charbel El Kabbouchi', '2023-10-31 06:25:36', '2023-10-31 06:25:36'),
(3, 'PICK UP', 7, 'Admin', '2024-05-16 06:28:59', '2024-05-16 06:28:59');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_conditions`
--

CREATE TABLE `delivery_conditions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `deposit_date` date NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `salary` double DEFAULT NULL,
  `deposit` double DEFAULT '0',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `company` text COLLATE utf8mb4_unicode_ci,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `created_by`, `name`, `title`, `salary`, `deposit`, `currency_id`, `company`, `telephone`, `extension`, `mobile_number`, `email`, `created_at`, `updated_at`) VALUES
(1, 1, 'Charbel El Kabbouchi', 'Employee 1', 'Job Title 1', NULL, -234, 1, 'PerceiveSystem', NULL, NULL, NULL, NULL, '2023-04-14 10:13:20', '2025-07-30 21:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `employee_report`
--

CREATE TABLE `employee_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_report_items`
--

CREATE TABLE `employee_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `number` text COLLATE utf8mb4_unicode_ci,
  `amount_paid` double DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `payroll_id` int(11) DEFAULT NULL,
  `payroll_date` date DEFAULT NULL,
  `deposit_id` int(11) DEFAULT NULL,
  `deposit_date` date DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `deposit_amount` double DEFAULT NULL,
  `return_deposit_id` int(11) DEFAULT NULL,
  `return_deposit_date` date DEFAULT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `return_deposit_amount` double DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rate`
--

CREATE TABLE `exchange_rate` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `currency1` int(11) DEFAULT NULL,
  `currency2` int(11) DEFAULT NULL,
  `value1` int(11) DEFAULT NULL,
  `value2` double DEFAULT NULL,
  `created_by` text COLLATE utf8mb4_unicode_ci,
  `exchangedate` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exchange_rate`
--

INSERT INTO `exchange_rate` (`id`, `user_id`, `currency1`, `currency2`, `value1`, `value2`, `created_by`, `exchangedate`, `note`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 2, 1, 0.38, 'Charbel El Kabbouchi', '2025-07-31', NULL, '2025-07-30 21:30:35', '2025-07-30 21:30:35');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `amount_paid` double DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `payment_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) NOT NULL DEFAULT '0',
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vat_status` int(255) DEFAULT NULL,
  `bill_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bill_date` date DEFAULT NULL,
  `bill_id` int(255) DEFAULT NULL,
  `bill_total` decimal(18,3) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `total_usd_1` decimal(18,3) DEFAULT NULL,
  `total_lbp_1` decimal(18,3) DEFAULT NULL,
  `total_vat_1` decimal(18,3) DEFAULT NULL,
  `total_usd_2` decimal(18,3) DEFAULT NULL,
  `total_lbp_2` decimal(18,3) DEFAULT NULL,
  `total_vat_2` decimal(18,3) DEFAULT NULL,
  `total_debit` decimal(18,3) DEFAULT NULL,
  `total_credit` decimal(18,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_items`
--

CREATE TABLE `expenses_items` (
  `id` int(11) NOT NULL,
  `expense_id` int(255) DEFAULT NULL,
  `amount_applied` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `account_receivable_id` int(255) DEFAULT NULL,
  `account_receivable_name` varchar(255) DEFAULT NULL,
  `account_receivable_number` varchar(255) DEFAULT NULL,
  `account_receivable_currency_id` int(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `debit` decimal(18,3) DEFAULT NULL,
  `debit_vat` decimal(18,3) DEFAULT NULL,
  `account_receivable_debit_vat_name` varchar(255) DEFAULT NULL,
  `account_receivable_debit_vat_id` int(255) DEFAULT NULL,
  `account_receivable_debit_vat_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_items2`
--

CREATE TABLE `expenses_items2` (
  `id` int(11) NOT NULL,
  `expense_id` int(255) DEFAULT NULL,
  `amount_applied` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `account_payable_id` int(255) DEFAULT NULL,
  `account_payable_name` varchar(255) DEFAULT NULL,
  `account_payable_number` varchar(255) DEFAULT NULL,
  `account_payable_currency_id` int(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `debit` decimal(18,3) DEFAULT NULL,
  `debit_vat` decimal(18,3) DEFAULT NULL,
  `account_payable_debit_vat_name` varchar(255) DEFAULT NULL,
  `account_payable_debit_vat_id` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_report`
--

CREATE TABLE `expenses_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `amount_paid` double DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_report_items`
--

CREATE TABLE `expenses_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `expenses_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `amount_paid` double DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `payment_date` timestamp NULL DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

CREATE TABLE `file_uploads` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_issues`
--

CREATE TABLE `goods_issues` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sales_order_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_issue_items`
--

CREATE TABLE `goods_issue_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `goods_issue_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `sales_order_item_id` int(10) UNSIGNED NOT NULL,
  `qty` double(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchangerate` decimal(8,2) DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoiceable_id` int(11) DEFAULT NULL,
  `invoiceable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` text COLLATE utf8mb4_unicode_ci,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `discount` decimal(8,2) DEFAULT NULL,
  `discount_percentage` decimal(8,2) DEFAULT NULL,
  `shipping` decimal(8,2) DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `total` decimal(8,2) NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `amount_paid` decimal(8,2) NOT NULL DEFAULT '0.00',
  `debit_amount` decimal(8,2) DEFAULT '0.00',
  `credit_amount` decimal(8,2) DEFAULT '0.00',
  `vat_paid` decimal(8,2) DEFAULT '0.00',
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vatrate` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `field1` text COLLATE utf8mb4_unicode_ci,
  `field2` text COLLATE utf8mb4_unicode_ci,
  `field3` text COLLATE utf8mb4_unicode_ci,
  `field4` text COLLATE utf8mb4_unicode_ci,
  `field5` text COLLATE utf8mb4_unicode_ci,
  `field6` text COLLATE utf8mb4_unicode_ci,
  `field7` text COLLATE utf8mb4_unicode_ci,
  `field8` text COLLATE utf8mb4_unicode_ci,
  `field9` text COLLATE utf8mb4_unicode_ci,
  `field10` text COLLATE utf8mb4_unicode_ci,
  `discount_usd` float DEFAULT NULL,
  `discount_per` float DEFAULT NULL,
  `price_class` int(11) NOT NULL DEFAULT '0',
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `seller_commission` decimal(18,3) DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices_2`
--

CREATE TABLE `invoices_2` (
  `id` int(11) NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `balance` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices_log`
--

CREATE TABLE `invoices_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `items` text COLLATE utf8mb4_unicode_ci,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoiceable_id` int(11) DEFAULT NULL,
  `invoiceable_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` text COLLATE utf8mb4_unicode_ci,
  `delivery_date` text COLLATE utf8mb4_unicode_ci,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `sub_total` double NOT NULL,
  `discount` double DEFAULT NULL,
  `discount_percentage` float DEFAULT NULL,
  `shipping` double DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `total` double NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `amount_paid` double NOT NULL DEFAULT '0',
  `debit_amount` double DEFAULT '0',
  `credit_amount` double DEFAULT '0',
  `vat_paid` double DEFAULT '0',
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vatrate` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `field1` text COLLATE utf8mb4_unicode_ci,
  `field2` text COLLATE utf8mb4_unicode_ci,
  `field3` text COLLATE utf8mb4_unicode_ci,
  `field4` text COLLATE utf8mb4_unicode_ci,
  `field5` text COLLATE utf8mb4_unicode_ci,
  `field6` text COLLATE utf8mb4_unicode_ci,
  `field7` text COLLATE utf8mb4_unicode_ci,
  `field8` text COLLATE utf8mb4_unicode_ci,
  `field9` text COLLATE utf8mb4_unicode_ci,
  `field10` text COLLATE utf8mb4_unicode_ci,
  `discount_usd` float DEFAULT NULL,
  `discount_per` float DEFAULT NULL,
  `price_class` int(11) NOT NULL DEFAULT '0',
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `qty` decimal(8,2) DEFAULT NULL,
  `quantity` decimal(8,2) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `qty_on_hand` decimal(8,2) DEFAULT NULL,
  `invoice_qty_returned` decimal(8,2) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_usd` decimal(8,2) DEFAULT NULL,
  `discount_per` decimal(8,2) DEFAULT NULL,
  `cost_price` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items_log`
--

CREATE TABLE `invoice_items_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_log_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_id` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `qty` double(8,2) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `qty_on_hand` double(8,2) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `discount_usd` float DEFAULT NULL,
  `discount_per` float DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_item_taxes`
--

CREATE TABLE `invoice_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `invoice_item_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `tax_authority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_report`
--

CREATE TABLE `invoice_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_report_items`
--

CREATE TABLE `invoice_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `invoice_id` text COLLATE utf8mb4_unicode_ci,
  `invoice_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` double DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `journal_flow_mappings`
--

CREATE TABLE `journal_flow_mappings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `process` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mappings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_flow_mappings`
--

INSERT INTO `journal_flow_mappings` (`id`, `process`, `mappings`, `created_at`, `updated_at`) VALUES
(1, 'invoice', '[{\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"debit\"},{\"field\":\"Sub Total\",\"account_id\":\"35\",\"type\":\"credit\"},{\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},{\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},{\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}]', '2025-08-01 23:30:47', '2025-08-01 23:31:48'),
(2, 'vendor_payment', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"19\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(3, 'client_payment', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"18\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(4, 'advance_payment', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"27\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(5, 'bill', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"39\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(6, 'credit_note', '[{\"field\":\"Total Amount\",\"account_id\":\"52\",\"type\":\"debit\"},{\"field\":\"Sub Total\",\"account_id\":\"37\",\"type\":\"credit\"},{\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"credit\"},{\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},{\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}]', '2025-08-01 23:30:47', '2025-08-07 20:20:05'),
(7, 'debit_note', '[{\"field\":\"Total Amount\",\"account_id\":\"37\",\"type\":\"credit\"},{\"field\":\"Sub Total\",\"account_id\":\"39\",\"type\":\"debit\"},{\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},{\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},{\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}]', '2025-08-01 23:30:47', '2025-08-07 21:21:40'),
(8, 'expenses', '[\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"credit\"},\n    {\"field\":\"Sub Total\",\"account_id\":\"1001\",\"type\":\"debit\"},\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(9, 'purchase_order', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"19\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(10, 'sales_order', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"18\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"35\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(11, 'receipt_voucher', '[\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"credit\"},\n    {\"field\":\"Sub Total\",\"account_id\":\"1001\",\"type\":\"debit\"},\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\n]', NULL, NULL),
(12, 'payment_voucher', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"1001\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `journal_flow_mappings___`
--

CREATE TABLE `journal_flow_mappings___` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `process` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mappings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `journal_flow_mappings___`
--

INSERT INTO `journal_flow_mappings___` (`id`, `process`, `mappings`, `created_at`, `updated_at`) VALUES
(1, 'invoice', '[{\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"debit\"},{\"field\":\"Sub Total\",\"account_id\":\"35\",\"type\":\"credit\"},{\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},{\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},{\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}]', '2025-08-01 23:30:47', '2025-08-01 23:31:48'),
(2, 'vendor_payment', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"19\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(3, 'client_payment', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"18\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(4, 'advance_payment', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"27\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(5, 'bill', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"39\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(6, 'credit_note', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"39\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(7, 'debit_note', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"39\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(8, 'expenses', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"17\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"1001\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(9, 'purchase_order', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"24\",\"type\":\"credit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"19\",\"type\":\"debit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"23\",\"type\":\"debit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"debit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"credit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47'),
(10, 'sales_order', '[\r\n    {\"field\":\"Total Amount\",\"account_id\":\"18\",\"type\":\"debit\"},\r\n    {\"field\":\"Sub Total\",\"account_id\":\"35\",\"type\":\"credit\"},\r\n    {\"field\":\"VAT\",\"account_id\":\"29\",\"type\":\"credit\"},\r\n    {\"field\":\"Charges\",\"account_id\":\"52\",\"type\":\"credit\"},\r\n    {\"field\":\"Discount\",\"account_id\":\"37\",\"type\":\"debit\"}\r\n]', '2025-08-01 23:30:47', '2025-08-01 23:30:47');

-- --------------------------------------------------------

--
-- Table structure for table `journal_vouchers`
--

CREATE TABLE `journal_vouchers` (
  `id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `currency_name` varchar(255) DEFAULT NULL,
  `document_type` int(255) DEFAULT NULL,
  `document_id` int(255) DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `document_total` decimal(18,3) DEFAULT NULL,
  `document_currency_id` int(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total_debit` decimal(18,3) DEFAULT NULL,
  `total_credit` decimal(18,3) DEFAULT NULL,
  `exchange_rate` decimal(18,3) DEFAULT NULL,
  `vat_rate` decimal(18,3) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `year_date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL,
  `saved_at` timestamp NULL DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `posted_by` varchar(255) DEFAULT NULL,
  `terms` varchar(255) DEFAULT NULL,
  `manual_type` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `journal_vouchers_movement`
--

CREATE TABLE `journal_vouchers_movement` (
  `id` int(11) NOT NULL,
  `journal_voucher_id` int(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `currency_name` varchar(255) DEFAULT NULL,
  `document_type` int(255) DEFAULT NULL,
  `document_id` int(255) DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `document_date` date DEFAULT NULL,
  `document_total` decimal(18,3) DEFAULT NULL,
  `document_currency_id` int(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `total_debit` decimal(18,3) DEFAULT NULL,
  `total_credit` decimal(18,3) DEFAULT NULL,
  `exchange_rate` decimal(18,3) DEFAULT NULL,
  `vat_rate` decimal(18,3) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `year_date` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL,
  `saved_at` timestamp NULL DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `posted_by` varchar(255) DEFAULT NULL,
  `terms` varchar(255) DEFAULT NULL,
  `document_name` varchar(255) DEFAULT NULL,
  `items` text,
  `type` varchar(255) DEFAULT NULL,
  `movement_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `journal_voucher_items`
--

CREATE TABLE `journal_voucher_items` (
  `id` int(11) NOT NULL,
  `journal_voucher_id` int(255) DEFAULT NULL,
  `account_id` int(255) DEFAULT NULL,
  `account_code` varchar(255) DEFAULT NULL,
  `account_name_en` varchar(255) DEFAULT NULL,
  `account_name_ar` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `debit` decimal(18,3) DEFAULT NULL,
  `credit` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `media_uploads`
--

CREATE TABLE `media_uploads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` text COLLATE utf8mb4_unicode_ci,
  `path` text COLLATE utf8mb4_unicode_ci,
  `alt` text COLLATE utf8mb4_unicode_ci,
  `size` text COLLATE utf8mb4_unicode_ci,
  `dimensions` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Clients', '/clients', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(2, 'Quotations', '/quotations', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(3, 'Sales Orders', '/sales_orders', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(4, 'Raw Materials', '/products', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(5, 'Raw Material Type', '/raw_material_type', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(6, 'Receive Orders', '/receive_orders', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(7, 'Vendors', '/vendors', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(8, 'Purchase Orders', '/purchase_orders', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(9, 'Transfers', '/transfers', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(10, 'Products Division', '/products_division', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(11, 'Products Aggregation', '/products_aggregation', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(12, 'Stock Movement', '/stock_movement', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(13, 'Damaged Deteriorate', '/damaged_deteriorate', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(14, 'Delivery Condition', '/deliverycondition', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(15, 'Payment Condition', '/paymentcondition', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(16, 'Exchange Rate', '/exchangerate', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(17, 'UOM', '/uom', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(18, 'Counters', '/counters', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(19, 'Currencies', '/currencies', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(20, 'Warehouses', '/warehouses', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(21, 'Categories', '/categories', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(22, 'Sub Categories', '/subcategories', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(23, 'Accounts', '/accounts', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(24, 'Transfer Accounts', '/transfer_accounts', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(25, 'Deposit', '/deposits', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(26, 'Return Deposit', '/return_deposits', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(27, 'Employees', '/employees', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(28, 'Payroll', '/payroll', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(29, 'Client Advance Payments', '/advance_payments', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(30, 'Client Invoices', '/invoices', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(31, 'Credit Notes', '/credit_notes', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(32, 'Debit Notes', '/debit_notes', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(33, 'Client Payments', '/client_payments', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(34, 'Client Statement of Account', '/statement', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(35, 'Vendor Expenses', '/expenses', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(36, 'Vendor Bills', '/bills', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(37, 'Vendor Payments', '/vendor_payments', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(38, 'Vendor Statement of Account', '/vendor_statement', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(39, 'Settings', '/settings', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(40, 'Users', '/users', '2022-08-16 02:00:00', '2022-08-16 02:00:00'),
(41, 'Vat_Rate', '/vatrate', NULL, NULL),
(42, 'Define_Shippers', '/shippers', NULL, NULL),
(43, 'Shipments', '/container_orders', NULL, NULL),
(44, 'Receive_Shipments', '/container_receive_orders', NULL, NULL),
(45, 'Shippers_Bills', '/shipper_bills', NULL, NULL),
(46, 'Shippers_Payments', '/shipper_payments', NULL, NULL),
(47, 'Shippers_SOA', '/shipper_statement', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2017_08_30_185039_create_settings_table', 1),
(3, '2017_08_30_192002_create_currencies_table', 1),
(4, '2017_08_30_194308_create_counters_table', 1),
(5, '2017_08_30_201029_create_clients_table', 1),
(6, '2017_08_31_084950_create_vendors_table', 1),
(7, '2017_08_31_091403_create_products_table', 1),
(8, '2017_08_31_110440_create_quotations_table', 1),
(9, '2017_09_01_190913_create_sales_orders_table', 1),
(10, '2017_09_02_075610_create_advance_payments_table', 1),
(11, '2017_09_02_084703_create_invoices_table', 1),
(12, '2017_09_02_155936_create_client_payments_table', 1),
(13, '2017_09_03_165235_create_expenses_table', 1),
(14, '2017_09_03_181636_create_purchase_orders_table', 1),
(15, '2017_09_03_184656_create_bills_table', 1),
(16, '2017_09_04_095619_create_vendor_payments_table', 1),
(17, '2017_10_23_083158_create_receive_orders_table', 1),
(18, '2017_11_20_080935_create_goods_issues_table', 1),
(19, '2020_08_05_212928_create_customers_table', 1),
(20, '2020_08_23_190054_create_statement_table', 1),
(21, '2020_08_27_163002_create_paymentcondition_table', 1),
(22, '2020_08_27_171317_create_deliverycondition_table', 1),
(23, '2020_08_27_171317_create_deliveryterm_table', 1),
(24, '2020_09_12_092456_create_credit_notes_table', 1),
(25, '2020_09_13_233839_create_debit_notes_table', 1),
(26, '2021_01_20_195451_create_exchange_rate_table', 1),
(27, '2021_01_21_215603_create_uom_table', 1),
(28, '2021_05_22_224852_create_receive_order_report_table', 1),
(29, '2021_05_23_155145_create_accounts_table', 1),
(30, '2021_05_23_202524_create_vendor_statement_table', 1),
(31, '2021_05_23_220043_create_invoice_report_table', 1),
(32, '2021_05_23_225230_create_purchase_order_report_table', 1),
(33, '2021_05_23_230911_create_transfer_accounts_table', 1),
(34, '2021_05_28_195707_create_employees_table', 1),
(35, '2021_05_28_195800_create_employee_report_table', 1),
(36, '2021_05_28_202301_create_payrolls_table', 1),
(37, '2021_05_28_213416_create_deposits_table', 1),
(38, '2021_05_28_222837_create_return_deposits_table', 1),
(39, '2021_06_26_202314_create_quotation_report_table', 1),
(40, '2021_06_26_205751_create_sales_order_report_table', 1),
(41, '2021_06_26_222712_create_advance_payment_report_table', 1),
(42, '2021_06_26_231008_create_credit_note_report_table', 1),
(43, '2021_06_26_231052_create_debit_note_report_table', 1),
(44, '2021_06_27_000716_create_client_payment_report_table', 1),
(45, '2021_06_27_005935_create_expenses_report_table', 1),
(46, '2021_06_27_130827_create_vendor_bills_report_table', 1),
(47, '2021_06_27_130901_create_vendor_payments_report_table', 1),
(48, '2016_06_01_000001_create_oauth_auth_codes_table', 2),
(49, '2016_06_01_000002_create_oauth_access_tokens_table', 2),
(50, '2016_06_01_000003_create_oauth_refresh_tokens_table', 2),
(51, '2016_06_01_000004_create_oauth_clients_table', 2),
(52, '2016_06_01_000005_create_oauth_personal_access_clients_table', 2),
(53, '2019_12_14_000001_create_personal_access_tokens_table', 2),
(54, '2025_08_19_220620_stock_counts', 3),
(55, '2025_08_19_220723_stock_count_products', 3),
(56, '2025_08_23_124905_create_stock_count_lots_table', 4),
(57, '2025_08_24_233852_report_widgets_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT NULL,
  `viewed` int(11) DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `manager_id`, `number`, `document_type`, `description`, `link`, `document_number`, `document_id`, `qty`, `date`, `viewed`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'N-2500021', 'invoices', 'New Invoice Created IN-2500005', 'invoices/', 'IN-2500005', 1, NULL, '2025-08-16 12:21:51', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-16 12:21:51', '2025-08-16 12:21:51'),
(2, 1, 1, 'N-2500022', 'invoices', 'Invoice IN-2500005 Approved', 'invoices/', 'IN-2500005', 1, NULL, '2025-08-16 12:21:56', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-16 12:21:56', '2025-08-16 12:21:56'),
(3, 1, 1, 'N-2500023', 'invoices', 'New Invoice Created IN-2500006', 'invoices/', 'IN-2500006', 2, NULL, '2025-08-16 21:05:54', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-16 21:05:54', '2025-08-16 21:05:54'),
(4, 1, 1, 'N-2500024', 'invoices', 'Invoice IN-2500006 Approved', 'invoices/', 'IN-2500006', 2, NULL, '2025-08-16 21:05:56', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-16 21:05:56', '2025-08-16 21:05:56'),
(5, 1, 1, 'N-2500025', 'invoices', 'New Invoice Created IN-2500007', 'invoices/', 'IN-2500007', 1, NULL, '2025-08-17 14:18:54', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 14:18:54', '2025-08-17 14:18:54'),
(6, 1, 1, 'N-2500026', 'invoices', 'Invoice IN-2500007 Approved', 'invoices/', 'IN-2500007', 1, NULL, '2025-08-17 14:19:05', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 14:19:05', '2025-08-17 14:19:05'),
(7, 1, 1, 'N-2500027', 'invoices', 'New Invoice Created IN-2500008', 'invoices/', 'IN-2500008', 1, NULL, '2025-08-17 14:25:20', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 14:25:20', '2025-08-17 14:25:20'),
(8, 1, 1, 'N-2500028', 'invoices', 'Invoice IN-2500008 Approved', 'invoices/', 'IN-2500008', 1, NULL, '2025-08-17 14:25:23', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 14:25:23', '2025-08-17 14:25:23'),
(9, 1, 1, 'N-2500029', 'invoices', 'New Invoice Created IN-2500009', 'invoices/', 'IN-2500009', 2, NULL, '2025-08-17 15:02:45', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 15:02:45', '2025-08-17 15:02:45'),
(10, 1, 1, 'N-2500030', 'invoices', 'Invoice IN-2500009 Approved', 'invoices/', 'IN-2500009', 2, NULL, '2025-08-17 15:02:47', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 15:02:47', '2025-08-17 15:02:47'),
(11, 1, 1, 'N-2500031', 'invoices', 'New Invoice Created IN-2500010', 'invoices/', 'IN-2500010', 1, NULL, '2025-08-17 20:44:26', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 20:44:26', '2025-08-17 20:44:26'),
(12, 1, 1, 'N-2500032', 'invoices', 'Invoice IN-2500010 Approved', 'invoices/', 'IN-2500010', 1, NULL, '2025-08-17 20:44:37', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-17 20:44:37', '2025-08-17 20:44:37'),
(13, 1, 1, 'N-2500033', 'purchases', 'Supplier Invoice BL-2500006 Created', 'bills/', 'BL-2500006', 1, NULL, '2025-08-18 20:02:42', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-18 20:02:42', '2025-08-18 20:02:42'),
(14, 1, 1, 'N-2500034', 'purchases', 'Supplier Invoice BL-2500006 Approved', 'bills/', 'BL-2500006', 1, NULL, '2025-08-18 20:02:57', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-18 20:02:57', '2025-08-18 20:02:57'),
(15, 1, 1, 'N-2500035', 'purchases', 'Supplier Invoice BL-2500007 Created', 'bills/', 'BL-2500007', 1, NULL, '2025-08-18 21:04:09', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-18 21:04:09', '2025-08-18 21:04:09'),
(16, 1, 1, 'N-2500036', 'purchases', 'Supplier Invoice BL-2500007 Approved', 'bills/', 'BL-2500007', 1, NULL, '2025-08-18 21:04:27', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-18 21:04:27', '2025-08-18 21:04:27'),
(17, 1, 1, 'N-2500037', 'invoices', 'New Invoice Created IN-2500011', 'invoices/', 'IN-2500011', 2, NULL, '2025-08-22 19:24:39', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-22 19:24:39', '2025-08-22 19:24:39'),
(18, 1, 3, 'N-2500038', 'invoices', 'Invoice IN-2500011 Approved', 'invoices/', 'IN-2500011', 2, NULL, '2025-08-22 19:24:42', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-22 19:24:42', '2025-08-22 19:24:42'),
(19, 1, 1, 'N-2500039', 'purchases', 'Supplier Invoice BL-2500008 Created', 'bills/', 'BL-2500008', 1, NULL, '2025-08-22 20:06:20', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-22 20:06:20', '2025-08-22 20:06:20'),
(20, 1, 1, 'N-2500040', 'purchases', 'Supplier Invoice BL-2500008 Approved', 'bills/', 'BL-2500008', 1, NULL, '2025-08-22 20:06:24', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-22 20:06:24', '2025-08-22 20:06:24'),
(21, 1, 1, 'N-2500041', 'purchases', 'New Purchase Order Created PO-2500001', 'purchase_orders/', 'PO-2500001', 1, NULL, '2025-08-23 08:32:08', NULL, 'manager', 'Charbel El Kabbouchi', '2025-08-23 08:32:08', '2025-08-23 08:32:08'),
(22, 1, 1, 'N-2500042', 'purchases', 'Purchase Order PO-2500001 Approved', 'purchase_orders/', 'PO-2500001', 1, NULL, '2025-08-23 08:32:10', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-23 08:32:10', '2025-08-23 08:32:10'),
(23, 1, 1, 'N-2500043', 'purchases', 'New Purchase Order Created PO-2500002', 'purchase_orders/', 'PO-2500002', 2, NULL, '2025-08-23 08:52:34', NULL, 'manager', 'Charbel El Kabbouchi', '2025-08-23 08:52:34', '2025-08-23 08:52:34'),
(24, 1, 1, 'N-2500044', 'purchases', 'Purchase Order PO-2500002 Approved', 'purchase_orders/', 'PO-2500002', 2, NULL, '2025-08-23 08:52:35', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-23 08:52:35', '2025-08-23 08:52:35'),
(25, 1, 1, 'N-2500045', 'purchases', 'New Purchase Order Created PO-2500003', 'purchase_orders/', 'PO-2500003', 3, NULL, '2025-08-23 09:20:00', NULL, 'manager', 'Charbel El Kabbouchi', '2025-08-23 09:20:00', '2025-08-23 09:20:00'),
(26, 1, 1, 'N-2500046', 'purchases', 'Purchase Order PO-2500003 Approved', 'purchase_orders/', 'PO-2500003', 3, NULL, '2025-08-23 09:20:02', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-23 09:20:02', '2025-08-23 09:20:02'),
(27, 1, 1, 'N-2500047', 'purchases', 'New Purchase Order Created PO-2500004', 'purchase_orders/', 'PO-2500004', 4, NULL, '2025-08-23 09:33:12', NULL, 'manager', 'Charbel El Kabbouchi', '2025-08-23 09:33:12', '2025-08-23 09:33:12'),
(28, 1, 1, 'N-2500048', 'purchases', 'Purchase Order PO-2500004 Approved', 'purchase_orders/', 'PO-2500004', 4, NULL, '2025-08-23 09:33:13', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-23 09:33:13', '2025-08-23 09:33:13'),
(29, 1, 1, 'N-2500049', 'purchases', 'Purchase Order PO-2500005 Approved', 'purchase_orders/', 'PO-2500005', 5, NULL, '2025-08-23 09:56:28', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-23 09:56:28', '2025-08-23 09:56:28'),
(30, 1, 1, 'N-2500050', 'purchases', 'Purchase Order PO-2500006 Approved', 'purchase_orders/', 'PO-2500006', 6, NULL, '2025-08-23 10:29:19', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-23 10:29:19', '2025-08-23 10:29:19'),
(31, 1, 1, 'N-2500051', 'invoices', 'New Invoice Created IN-2500012', 'invoices/', 'IN-2500012', 3, NULL, '2025-08-24 07:10:44', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 07:10:44', '2025-08-24 07:10:44'),
(32, 1, 3, 'N-2500052', 'invoices', 'Invoice IN-2500012 Approved', 'invoices/', 'IN-2500012', 3, NULL, '2025-08-24 07:10:46', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 07:10:47', '2025-08-24 07:10:47'),
(33, 1, 1, 'N-2500053', 'invoices', 'New Invoice Created IN-2500013', 'invoices/', 'IN-2500013', 4, NULL, '2025-08-24 08:51:10', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 08:51:10', '2025-08-24 08:51:10'),
(34, 1, 3, 'N-2500054', 'invoices', 'Invoice IN-2500013 Approved', 'invoices/', 'IN-2500013', 4, NULL, '2025-08-24 08:51:59', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 08:51:59', '2025-08-24 08:51:59'),
(35, 1, 1, 'N-2500055', 'invoices', 'New Invoice Created IN-2500014', 'invoices/', 'IN-2500014', 5, NULL, '2025-08-24 21:48:28', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 21:48:28', '2025-08-24 21:48:28'),
(36, 1, 1, 'N-2500056', 'purchases', 'Supplier Invoice BL-2500009 Created', 'bills/', 'BL-2500009', 2, NULL, '2025-08-24 22:05:15', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 22:05:15', '2025-08-24 22:05:15'),
(37, 1, 1, 'N-2500057', 'purchases', 'Supplier Invoice BL-2500009 Approved', 'bills/', 'BL-2500009', 2, NULL, '2025-08-24 22:05:20', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-24 22:05:20', '2025-08-24 22:05:20'),
(38, 1, 1, 'N-2500058', 'purchases', 'Purchase Order PO-2500007 Approved', 'purchase_orders/', 'PO-2500007', 1, NULL, '2025-08-25 06:55:54', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-25 06:55:54', '2025-08-25 06:55:54'),
(39, 1, 1, 'N-2500059', 'purchases', 'Purchase Order PO-2500008 Approved', 'purchase_orders/', 'PO-2500008', 2, NULL, '2025-08-25 07:04:27', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-25 07:04:28', '2025-08-25 07:04:28'),
(40, 1, 1, 'N-2500060', 'purchases', 'Supplier Invoice BL-2500000 Created', 'bills/', 'BL-2500000', 1, NULL, '2025-08-25 08:17:34', NULL, 'user', 'Charbel El Kabbouchi', '2025-08-25 08:17:34', '2025-08-25 08:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paymentcondition`
--

CREATE TABLE `paymentcondition` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `paymentcondition`
--

INSERT INTO `paymentcondition` (`id`, `name`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, '14 DAYS', 1, '', NULL, NULL),
(2, 'C.O.D CASH ON DELEVERY', 1, '', NULL, NULL),
(3, 'LIPARI TERMS', 7, 'Admin', '2024-05-16 06:29:36', '2024-05-16 06:29:36');

-- --------------------------------------------------------

--
-- Table structure for table `payment_options`
--

CREATE TABLE `payment_options` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `balance` decimal(10,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `payment_options`
--

INSERT INTO `payment_options` (`id`, `name`, `balance`, `created_at`, `updated_at`, `user_id`, `created_by`) VALUES
(1, 'Cash', 64597.442, '2023-11-05 22:03:46', '2023-11-05 22:03:46', 1, 'Charbel El Kabbouchi'),
(2, 'Cheque', 969501.830, '2023-11-05 22:04:01', '2023-11-05 22:04:01', 1, 'Charbel El Kabbouchi'),
(3, 'Bank Transfer', 198897.620, '2023-11-05 22:04:05', '2023-11-05 22:04:05', 1, 'Charbel El Kabbouchi'),
(4, 'Other', 37965.450, '2023-11-05 22:04:12', '2023-11-05 22:04:12', 1, 'Charbel El Kabbouchi'),
(5, 'CREDIT CARD / DEBIT CARD', 117717.140, '2023-11-14 06:20:17', '2023-11-14 06:20:17', 7, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `payment_options_items`
--

CREATE TABLE `payment_options_items` (
  `id` int(11) NOT NULL,
  `payment_options_id` int(255) DEFAULT NULL,
  `payment` decimal(8,2) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `year_date` varchar(255) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `document_id` int(255) DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_options_report`
--

CREATE TABLE `payment_options_report` (
  `id` int(11) NOT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `vendor_id` int(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_options_report_items`
--

CREATE TABLE `payment_options_report_items` (
  `id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `payment_options_id` int(255) DEFAULT NULL,
  `payment` decimal(8,2) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `year_date` varchar(255) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `document_id` int(255) DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_vouchers`
--

CREATE TABLE `payment_vouchers` (
  `id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `vendor_id` int(255) DEFAULT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `currency_code` varchar(255) DEFAULT NULL,
  `global_vat_percentage` decimal(18,3) DEFAULT NULL,
  `vendor_balance` decimal(18,3) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `exchange_rate` decimal(18,3) DEFAULT NULL,
  `lines` int(255) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `total_debit` decimal(18,3) DEFAULT NULL,
  `total_debit_usd` decimal(18,3) DEFAULT NULL,
  `total_debit_vat` decimal(18,3) DEFAULT NULL,
  `balance_amount` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `year_date` varchar(255) DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL,
  `vat_status` int(255) DEFAULT NULL,
  `posted` int(255) DEFAULT '0',
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `applied_amount` decimal(18,3) DEFAULT NULL,
  `applied_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_voucher_bills`
--

CREATE TABLE `payment_voucher_bills` (
  `id` int(11) NOT NULL,
  `payment_voucher_id` int(255) DEFAULT NULL,
  `bill_id` int(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `runningBalance` decimal(18,3) DEFAULT NULL,
  `amount_applied` decimal(18,3) DEFAULT NULL,
  `amount_applied_usd` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payment_voucher_items`
--

CREATE TABLE `payment_voucher_items` (
  `id` int(11) NOT NULL,
  `payment_voucher_id` int(255) DEFAULT NULL,
  `account_receivable_id` int(255) DEFAULT NULL,
  `account_receivable_number` varchar(255) DEFAULT NULL,
  `account_receivable_name` varchar(255) DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `account_receivable_currency_code` varchar(255) DEFAULT NULL,
  `account_receivable_currency_id` int(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `account_receivable_debit_vat_id` int(255) DEFAULT NULL,
  `account_receivable_debit_vat_code` varchar(255) DEFAULT NULL,
  `account_receivable_debit_vat_name` varchar(255) DEFAULT NULL,
  `debit` decimal(18,3) DEFAULT NULL,
  `debit_vat` decimal(18,3) DEFAULT NULL,
  `debit_usd` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `amount_paid` double DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `payment_date` date NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `price_changes`
--

CREATE TABLE `price_changes` (
  `id` int(11) NOT NULL,
  `product_id` int(255) DEFAULT NULL,
  `product_name` text,
  `unit_price` decimal(8,2) DEFAULT NULL,
  `unitprice` decimal(8,2) DEFAULT NULL,
  `class_a_price` decimal(8,2) DEFAULT NULL,
  `class_b_price` decimal(8,2) DEFAULT NULL,
  `class_c_price` decimal(8,2) DEFAULT NULL,
  `nb_boxes_1` float DEFAULT NULL,
  `nb_boxes_1_price` decimal(8,2) DEFAULT NULL,
  `nb_boxes_2` float DEFAULT NULL,
  `nb_boxes_2_price` decimal(8,2) DEFAULT NULL,
  `nb_boxes_3` float DEFAULT NULL,
  `nb_boxes_3_price` decimal(8,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sale_price` decimal(10,3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `price_changes_report`
--

CREATE TABLE `price_changes_report` (
  `id` int(11) NOT NULL,
  `product_id` int(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `price_changes_report_items`
--

CREATE TABLE `price_changes_report_items` (
  `id` int(11) NOT NULL,
  `report_id` int(255) DEFAULT NULL,
  `product_id` int(255) DEFAULT NULL,
  `product_name` text,
  `unit_price` float DEFAULT NULL,
  `unitprice` float DEFAULT NULL,
  `class_a_price` float DEFAULT NULL,
  `class_b_price` float DEFAULT NULL,
  `class_c_price` float DEFAULT NULL,
  `nb_boxes_1` float DEFAULT NULL,
  `nb_boxes_1_price` float DEFAULT NULL,
  `nb_boxes_2` float DEFAULT NULL,
  `nb_boxes_2_price` float DEFAULT NULL,
  `nb_boxes_3` float DEFAULT NULL,
  `nb_boxes_3_price` float DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` double NOT NULL,
  `barcode` text COLLATE utf8_bin,
  `title` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `summary` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `category_id` double DEFAULT NULL,
  `sub_category_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sub_categoryid` double DEFAULT NULL,
  `sub_sub_categoryid` double DEFAULT NULL,
  `sub_sub_category_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `image` double DEFAULT NULL,
  `product_image_gallery` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `price` float(8,2) DEFAULT NULL,
  `unitprice` float(8,2) NOT NULL DEFAULT '0.00',
  `original_price` float NOT NULL DEFAULT '1',
  `sale_price` float(8,2) DEFAULT NULL,
  `special_price` float(8,2) DEFAULT '0.00',
  `tax_percentage` double DEFAULT NULL,
  `badge` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `attributes` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `sold_count` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `deleted_at` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_at` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `updated_at` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `field1` text COLLATE utf8_bin,
  `field2` text COLLATE utf8_bin,
  `field3` text COLLATE utf8_bin,
  `field4` text COLLATE utf8_bin,
  `field5` text COLLATE utf8_bin,
  `field6` text COLLATE utf8_bin,
  `field7` text COLLATE utf8_bin,
  `field8` text COLLATE utf8_bin,
  `field9` text COLLATE utf8_bin,
  `field10` text COLLATE utf8_bin,
  `added_by` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `user_id` double DEFAULT NULL,
  `brand_id` double DEFAULT NULL,
  `product_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `featured` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `best_selling` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `deal_of_the_day` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `deal_date` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `discount_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `minimum_order_qty` double DEFAULT NULL,
  `free_shipping` double DEFAULT NULL,
  `colors` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `featured_status` double DEFAULT NULL,
  `request_status` double DEFAULT NULL,
  `published` double DEFAULT NULL,
  `variation` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `choice_options` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `refundable` double DEFAULT NULL,
  `min_qty` double DEFAULT NULL,
  `meta_image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `color_image` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `category_ids` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `tag` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `purchase_price` float(8,2) DEFAULT NULL,
  `tax` double DEFAULT NULL,
  `tax_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `tax_model` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'placeholder.png',
  `images` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `details` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `shipping_cost` double DEFAULT NULL,
  `unit_price` float(8,2) DEFAULT NULL,
  `current_stock` double NOT NULL DEFAULT '0',
  `lot_qty` decimal(18,3) DEFAULT NULL,
  `on_hold_qty` decimal(8,2) NOT NULL DEFAULT '0.00',
  `multiply_qty` double DEFAULT NULL,
  `special_image` double NOT NULL DEFAULT '0',
  `top_search` int(255) NOT NULL DEFAULT '0',
  `warehouse_id` int(255) NOT NULL DEFAULT '1',
  `volume_box` decimal(8,2) DEFAULT NULL,
  `ct_box` decimal(8,2) DEFAULT NULL,
  `weight_box` decimal(8,2) DEFAULT NULL,
  `warehouse_qty` decimal(8,2) DEFAULT NULL,
  `currency_id` bigint(255) DEFAULT NULL,
  `updated_by` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `tax_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `tax_rate` float DEFAULT NULL,
  `uom_id` int(255) DEFAULT NULL,
  `minimum_stock` float NOT NULL DEFAULT '0',
  `vendor_id` int(255) NOT NULL DEFAULT '0',
  `product_rating` int(255) NOT NULL DEFAULT '1',
  `rating_value` decimal(8,0) NOT NULL DEFAULT '0',
  `nb_boxes_1` float(8,2) NOT NULL DEFAULT '0.00',
  `nb_boxes_1_price` float(8,2) NOT NULL DEFAULT '0.00',
  `nb_boxes_2` float(8,2) NOT NULL DEFAULT '0.00',
  `nb_boxes_2_price` float(8,2) NOT NULL DEFAULT '0.00',
  `nb_boxes_3` float(8,2) NOT NULL DEFAULT '0.00',
  `nb_boxes_3_price` float(8,2) NOT NULL DEFAULT '0.00',
  `digital_product_type` int(255) DEFAULT NULL,
  `video_provider` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'youtube',
  `video_url` text COLLATE utf8_bin,
  `meta_title` text COLLATE utf8_bin,
  `meta_description` text COLLATE utf8_bin,
  `size` varchar(255) COLLATE utf8_bin DEFAULT '0',
  `location` varchar(255) COLLATE utf8_bin DEFAULT '0',
  `class_a_price` float(8,2) NOT NULL DEFAULT '0.00',
  `class_b_price` float(8,2) NOT NULL DEFAULT '0.00',
  `class_c_price` float(8,2) NOT NULL DEFAULT '0.00',
  `item_box` float(8,2) DEFAULT NULL,
  `upc_number` text COLLATE utf8_bin,
  `body` text COLLATE utf8_bin,
  `document1` text COLLATE utf8_bin,
  `document2` text COLLATE utf8_bin,
  `document3` text COLLATE utf8_bin,
  `product_dropdown_1_id` int(255) DEFAULT NULL,
  `product_dropdown_2_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `barcode`, `title`, `summary`, `description`, `category_id`, `sub_category_id`, `sub_categoryid`, `sub_sub_categoryid`, `sub_sub_category_id`, `image`, `product_image_gallery`, `price`, `unitprice`, `original_price`, `sale_price`, `special_price`, `tax_percentage`, `badge`, `status`, `slug`, `attributes`, `sold_count`, `deleted_at`, `created_at`, `updated_at`, `field1`, `field2`, `field3`, `field4`, `field5`, `field6`, `field7`, `field8`, `field9`, `field10`, `added_by`, `user_id`, `brand_id`, `product_type`, `code`, `new`, `featured`, `best_selling`, `deal_of_the_day`, `deal_date`, `discount`, `discount_type`, `minimum_order_qty`, `free_shipping`, `colors`, `featured_status`, `request_status`, `published`, `variation`, `choice_options`, `refundable`, `min_qty`, `meta_image`, `color_image`, `category_ids`, `name`, `tag`, `purchase_price`, `tax`, `tax_type`, `tax_model`, `thumbnail`, `images`, `details`, `unit`, `shipping_cost`, `unit_price`, `current_stock`, `lot_qty`, `on_hold_qty`, `multiply_qty`, `special_image`, `top_search`, `warehouse_id`, `volume_box`, `ct_box`, `weight_box`, `warehouse_qty`, `currency_id`, `updated_by`, `created_by`, `tax_name`, `tax_rate`, `uom_id`, `minimum_stock`, `vendor_id`, `product_rating`, `rating_value`, `nb_boxes_1`, `nb_boxes_1_price`, `nb_boxes_2`, `nb_boxes_2_price`, `nb_boxes_3`, `nb_boxes_3_price`, `digital_product_type`, `video_provider`, `video_url`, `meta_title`, `meta_description`, `size`, `location`, `class_a_price`, `class_b_price`, `class_c_price`, `item_box`, `upc_number`, `body`, `document1`, `document2`, `document3`, `product_dropdown_1_id`, `product_dropdown_2_id`) VALUES
(2500003, 'iVBORw0KGgoAAAANSUhEUgAAEpAAAAAeAQMAAAAPqHhZAAAABlBMVEX///8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAapJREFUeJztklFKAwEUAwUP4JG8mkftMfyyVjB1HSKiqIyQ+SmF7HSbl5vzhYebN+4u3x8vn7fnAy+B+8P3BJ5envgocOUYOL3+RLgawunwDu8MLVDf4XR48Hv/4n8bWFR4OjZ6f/6kyfD1PfyA4qPAFTa1Sf2qgUWFTYqGFtCd02BgUWGToqEFdOc0GFhU2KRoaAHdOQ0GFhU2KRpaQHdOg4FFhU2KhhbQndNgYFFhk6KhBXTnNBhYVNikaGgB3TkNBhYVNikaWkB3ToOBRYVNioYW0J3TYGBRYZOioQV05zQYWFTYpGhoAd05DQYWFTYpGlpAd06DgUWFTYqGFtCd02BgUWGToqEFdOc0GFhU2KRoaAHdOQ0GFhU2KRpaQHdOg4FFhU2KhhbQndNgYFFhk6KhBXTnNBhYVNikaGgB3TkNBhYVNikaWkB3ToOBRYVNioYW0J3TYGBRYZOioQV05zQYWFTYpGhoAd05DQYWFTYpGlpAd06DgUWFTYqGFtCd02BgUWGToqEFdOc0GFhU2KRoaAHdOQ0GFhU2KRpaQHdOg4FFhb+a1DOYGkV2vlEZ9wAAAABJRU5ErkJggg==', 'Rez 20KG', 'Rez 20KG', 'Rez 20KG', 1, '[\"1\"]', 1, NULL, '[\"\"]', 9999999, '[\"9999999\"]', 15.00, 15.00, 15, 10.00, 0.00, 0, NULL, 'publish', '2500003', '[]', NULL, NULL, '2025-08-25 09:54:44', '2025-08-25 10:08:16', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'seller', 1, NULL, 'physical', '2500003', NULL, NULL, NULL, NULL, NULL, 0, 'flat', 0, 0, '[]', 1, 1, 1, '[]', '[]', 1, 1, 'def.png', '[]', '[{\"id\":\"1\",\"position\":1}]', 'Rez 20KG', 'Rez 20KG', 15.00, 0, 'percent', 'exclude', 'placeholder.png', '[\"placeholder.png\"]', 'Rez 20KG', 'KG', 0, 15.00, 20, 20.000, 0.00, 0, 0, 0, 1, NULL, NULL, NULL, NULL, 1, 'Charbel El Kabbouchi', 'Charbel El Kabbouchi', NULL, NULL, 1, 0, 1, 1, 33, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, 'youtube', NULL, NULL, NULL, '0', '0', 0.00, 0.00, 0.00, 1.00, NULL, '1', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `products_aggregation`
--

CREATE TABLE `products_aggregation` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `item_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` double DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` text COLLATE utf8mb4_unicode_ci,
  `status` text COLLATE utf8mb4_unicode_ci,
  `unit_price` double DEFAULT NULL,
  `minimum_stock` double DEFAULT '0',
  `has_inventory` tinyint(1) NOT NULL DEFAULT '0',
  `qty_on_hand` double NOT NULL DEFAULT '0',
  `current_stock` double DEFAULT NULL,
  `uom_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_id` int(11) DEFAULT '1',
  `category_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  `sub_sub_category_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT '0',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_division`
--

CREATE TABLE `products_division` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` double NOT NULL,
  `item_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `uom_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_uom_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_on_hand` double DEFAULT NULL,
  `to_qty_on_hand` double DEFAULT NULL,
  `current_stock` double DEFAULT NULL,
  `to_current_stock` double DEFAULT NULL,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products_lots`
--

CREATE TABLE `products_lots` (
  `id` int(11) NOT NULL,
  `product_id` int(255) DEFAULT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `qty` decimal(18,3) DEFAULT NULL,
  `uom_id` int(255) DEFAULT NULL,
  `vendor_id` int(255) DEFAULT NULL,
  `balance` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `receive_order_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products_report`
--

CREATE TABLE `products_report` (
  `id` int(11) NOT NULL,
  `product_id` int(255) DEFAULT NULL,
  `vendor_id` int(255) DEFAULT NULL,
  `body` text,
  `category_id` int(255) DEFAULT NULL,
  `subcategory_id` int(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `from_code` varchar(255) DEFAULT NULL,
  `to_code` varchar(255) DEFAULT NULL,
  `from_qty` float DEFAULT NULL,
  `to_qty` float DEFAULT NULL,
  `from_p_price` float DEFAULT NULL,
  `to_p_price` float DEFAULT NULL,
  `from_price` float DEFAULT NULL,
  `to_price` float DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products_report_items`
--

CREATE TABLE `products_report_items` (
  `id` int(11) NOT NULL,
  `report_id` int(255) DEFAULT NULL,
  `product_id` int(255) DEFAULT NULL,
  `product_name` text,
  `code` varchar(255) DEFAULT NULL,
  `thumbnail` text,
  `vendor_id` int(255) DEFAULT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `current_stock` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `sale_price` float DEFAULT NULL,
  `on_hold_qty` float DEFAULT NULL,
  `category_id` int(255) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `subcategory_id` int(255) DEFAULT NULL,
  `subcategory_name` varchar(255) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `unitprice` float DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `tax_percentage` varchar(255) DEFAULT NULL,
  `sub_category_id` varchar(255) DEFAULT NULL,
  `sub_categoryid` varchar(255) DEFAULT NULL,
  `sub_sub_categoryid` varchar(255) DEFAULT NULL,
  `sub_sub_category_id` varchar(255) DEFAULT NULL,
  `product_image_gallery` varchar(255) DEFAULT NULL,
  `special_price` varchar(255) DEFAULT NULL,
  `original_price` varchar(255) DEFAULT NULL,
  `field4` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `field2` varchar(255) DEFAULT NULL,
  `field3` varchar(255) DEFAULT NULL,
  `field1` varchar(255) DEFAULT NULL,
  `field5` varchar(255) DEFAULT NULL,
  `field6` varchar(255) DEFAULT NULL,
  `field7` varchar(255) DEFAULT NULL,
  `field8` varchar(255) DEFAULT NULL,
  `field9` varchar(255) DEFAULT NULL,
  `field10` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `brand_id` varchar(255) DEFAULT NULL,
  `product_type` varchar(255) DEFAULT NULL,
  `new` varchar(255) DEFAULT NULL,
  `featured` varchar(255) DEFAULT NULL,
  `best_selling` varchar(255) DEFAULT NULL,
  `deal_of_the_day` varchar(255) DEFAULT NULL,
  `deal_date` varchar(255) DEFAULT NULL,
  `minimum_order_qty` varchar(255) DEFAULT NULL,
  `purchase_price` varchar(255) DEFAULT NULL,
  `shipping_cost` varchar(255) DEFAULT NULL,
  `unit_price` varchar(255) DEFAULT NULL,
  `nb_boxes_1` varchar(255) DEFAULT NULL,
  `rating_value` varchar(255) DEFAULT NULL,
  `volume_box` text,
  `ct_box` varchar(255) DEFAULT NULL,
  `weight_box` varchar(255) DEFAULT NULL,
  `warehouse_qty` varchar(255) DEFAULT NULL,
  `product_rating` varchar(255) DEFAULT NULL,
  `nb_boxes_1_price` varchar(255) DEFAULT NULL,
  `class_c_price` varchar(255) DEFAULT NULL,
  `nb_boxes_2` varchar(255) DEFAULT NULL,
  `nb_boxes_2_price` varchar(255) DEFAULT NULL,
  `nb_boxes_3` varchar(255) DEFAULT NULL,
  `nb_boxes_3_price` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `class_a_price` varchar(255) DEFAULT NULL,
  `class_b_price` varchar(255) DEFAULT NULL,
  `item_box` varchar(255) DEFAULT NULL,
  `upc_number` varchar(255) DEFAULT NULL,
  `barcode` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products_report_items_old`
--

CREATE TABLE `products_report_items_old` (
  `id` int(11) NOT NULL,
  `report_id` int(255) DEFAULT NULL,
  `product_id` int(255) DEFAULT NULL,
  `product_name` text,
  `code` varchar(255) DEFAULT NULL,
  `barcode` text,
  `thumbnail` text,
  `vendor_id` int(255) DEFAULT NULL,
  `vendor_name` varchar(255) DEFAULT NULL,
  `current_stock` float DEFAULT NULL,
  `price` float DEFAULT NULL,
  `sale_price` float DEFAULT NULL,
  `on_hold_qty` float DEFAULT NULL,
  `category_id` int(255) DEFAULT NULL,
  `category_name` varchar(255) DEFAULT NULL,
  `subcategory_id` int(255) DEFAULT NULL,
  `subcategory_name` varchar(255) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `unitprice` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products__`
--

CREATE TABLE `products__` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `item_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` text COLLATE utf8mb4_unicode_ci,
  `status` text COLLATE utf8mb4_unicode_ci,
  `unit_price` double DEFAULT NULL,
  `minimum_stock` double DEFAULT '0',
  `has_inventory` tinyint(1) NOT NULL DEFAULT '0',
  `qty_on_hand` double NOT NULL DEFAULT '0',
  `raw_material_type_id` int(191) DEFAULT '0',
  `uom_id` int(11) NOT NULL,
  `warehouse_id` int(11) DEFAULT '1',
  `category_id` int(11) NOT NULL,
  `product_type` int(11) DEFAULT NULL,
  `sub_category_id` int(11) NOT NULL,
  `sub_sub_category_id` int(11) DEFAULT NULL,
  `qty` double DEFAULT '0',
  `currency_id` int(10) UNSIGNED NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` int(191) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_aggregation_items`
--

CREATE TABLE `product_aggregation_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_aggregation_id` int(10) UNSIGNED NOT NULL,
  `product_id` double UNSIGNED NOT NULL,
  `product_code` text COLLATE utf8mb4_unicode_ci,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `product_price` double DEFAULT NULL,
  `qty_on_hand` int(10) UNSIGNED DEFAULT NULL,
  `current_stock` double DEFAULT NULL,
  `uom_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `attribute_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

CREATE TABLE `product_brands` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` int(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `featured` int(255) DEFAULT NULL,
  `order` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `product_brands`
--

INSERT INTO `product_brands` (`id`, `title`, `status`, `image`, `featured`, `order`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Brand', NULL, NULL, NULL, 1, '2023-11-29 07:14:13', '2023-11-29 07:14:13', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(255) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `title`, `status`, `image`, `featured`, `created_at`, `updated_at`, `name`, `number`, `description`, `order`) VALUES
(1, 'Main Category', 'publish', NULL, 1, '2023-11-29 05:13:43', '2023-11-29 05:13:43', 'Main Category', 1, 'Main Category', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_conversions`
--

CREATE TABLE `product_conversions` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `base_uom_id` int(191) DEFAULT NULL,
  `base_qty` int(255) NOT NULL DEFAULT '1',
  `converted_qty` decimal(18,3) DEFAULT NULL,
  `converted_uom_id` int(255) DEFAULT NULL,
  `converted_uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `converted_uom_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_conversions`
--

INSERT INTO `product_conversions` (`id`, `product_id`, `base_uom_id`, `base_qty`, `converted_qty`, `converted_uom_id`, `converted_uom_code`, `converted_uom_unit`, `created_at`, `updated_at`) VALUES
(1, 2500000, NULL, 1, 0.100, 5, 'Pallet', 'Pallet', '2025-08-07 13:15:36', '2025-08-23 08:31:28'),
(2, 2500001, NULL, 1, 0.100, 5, 'Pallet', 'Pallet', '2025-08-23 08:31:51', '2025-08-23 08:31:51'),
(3, 2500002, NULL, 1, 0.002, 5, 'Pallet', 'Pallet', '2025-08-23 08:51:41', '2025-08-23 08:51:41'),
(4, 2500003, NULL, 1, 0.100, 2, 'Pallet', 'Pallet', '2025-08-25 06:55:27', '2025-08-25 06:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_dropdown1`
--

CREATE TABLE `product_dropdown1` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_dropdown1`
--

INSERT INTO `product_dropdown1` (`id`, `name`, `created_at`, `updated_at`, `user_id`, `created_by`) VALUES
(1, 'Yes', '2024-12-18 02:16:27', '2024-12-18 02:16:27', 1, 'Charbel El Kabbouchi'),
(2, 'No', '2024-12-18 02:16:35', '2024-12-18 02:16:35', 1, 'Charbel El Kabbouchi');

-- --------------------------------------------------------

--
-- Table structure for table `product_dropdown2`
--

CREATE TABLE `product_dropdown2` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_dropdown2`
--

INSERT INTO `product_dropdown2` (`id`, `name`, `created_at`, `updated_at`, `user_id`, `created_by`) VALUES
(1, 'x', '2025-08-07 14:53:04', '2025-08-07 14:53:04', 1, 'Charbel El Kabbouchi'),
(2, 'y', '2025-08-07 14:53:07', '2025-08-07 14:53:07', 1, 'Charbel El Kabbouchi');

-- --------------------------------------------------------

--
-- Table structure for table `product_inventories`
--

CREATE TABLE `product_inventories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock_count` int(11) DEFAULT NULL,
  `sold_count` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_inventories`
--

INSERT INTO `product_inventories` (`id`, `product_id`, `sku`, `stock_count`, `sold_count`, `created_at`, `updated_at`) VALUES
(1, 2500003, '2500003', 0, 0, '2025-08-25 06:54:44', '2025-08-25 06:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_items`
--

CREATE TABLE `product_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` double UNSIGNED DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_item_nb` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_items`
--

INSERT INTO `product_items` (`id`, `product_id`, `vendor_id`, `reference`, `supplier_item_nb`, `price`, `currency_id`, `created_at`, `updated_at`) VALUES
(1, 2500003, 1, 'reference', NULL, 10, 1, '2025-08-25 06:55:27', '2025-08-25 06:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_log`
--

CREATE TABLE `product_log` (
  `id` int(11) NOT NULL,
  `body` text,
  `comment` text,
  `items` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_log`
--

INSERT INTO `product_log` (`id`, `body`, `comment`, `items`, `created_at`, `updated_at`) VALUES
(1, '[{\"id\":2500003,\"barcode\":\"iVBORw0KGgoAAAANSUhEUgAAAJAAAAAeAQMAAAD5MgVTAAAABlBMVEX\\/\\/\\/8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAB5JREFUKJFj6N61Y92LV4t3QeA7re5dDKNCo0KEhQB+IXE06KVd+AAAAABJRU5ErkJggg==\",\"title\":\"Rez 20KG\",\"summary\":\"Rez 20KG\",\"description\":\"Rez 20KG\",\"category_id\":1,\"sub_category_id\":\"[\\\"1\\\"]\",\"sub_categoryid\":1,\"sub_sub_categoryid\":null,\"sub_sub_category_id\":\"[\\\"\\\"]\",\"image\":9999999,\"product_image_gallery\":\"[\\\"9999999\\\"]\",\"price\":15,\"unitprice\":0,\"original_price\":15,\"sale_price\":10,\"special_price\":0,\"tax_percentage\":0,\"badge\":null,\"status\":\"publish\",\"slug\":\"2500003\",\"attributes\":\"[]\",\"sold_count\":null,\"deleted_at\":null,\"created_at\":\"2025-08-25T06:54:44.000000Z\",\"updated_at\":\"2025-08-25T06:54:44.000000Z\",\"field1\":null,\"field2\":null,\"field3\":null,\"field4\":null,\"field5\":null,\"field6\":null,\"field7\":null,\"field8\":null,\"field9\":null,\"field10\":null,\"added_by\":\"seller\",\"user_id\":1,\"brand_id\":null,\"product_type\":\"physical\",\"code\":\"2500003\",\"new\":null,\"featured\":null,\"best_selling\":null,\"deal_of_the_day\":null,\"deal_date\":null,\"discount\":0,\"discount_type\":\"flat\",\"minimum_order_qty\":0,\"free_shipping\":0,\"colors\":\"[]\",\"featured_status\":1,\"request_status\":1,\"published\":1,\"variation\":\"[]\",\"choice_options\":\"[]\",\"refundable\":1,\"min_qty\":1,\"meta_image\":\"def.png\",\"color_image\":\"[]\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"name\":\"Rez 20KG\",\"tag\":\"Rez 20KG\",\"purchase_price\":15,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"exclude\",\"thumbnail\":\"placeholder.png\",\"images\":\"[\\\"placeholder.png\\\"]\",\"details\":\"Rez 20KG\",\"unit\":\"KG\",\"shipping_cost\":0,\"unit_price\":15,\"current_stock\":0,\"lot_qty\":\"20.000\",\"on_hold_qty\":\"0.00\",\"multiply_qty\":0,\"special_image\":0,\"top_search\":0,\"warehouse_id\":1,\"volume_box\":null,\"ct_box\":null,\"weight_box\":null,\"warehouse_qty\":null,\"currency_id\":1,\"updated_by\":null,\"created_by\":\"Charbel El Kabbouchi\",\"tax_name\":null,\"tax_rate\":null,\"uom_id\":1,\"minimum_stock\":0,\"vendor_id\":0,\"product_rating\":1,\"rating_value\":\"-50\",\"nb_boxes_1\":0,\"nb_boxes_1_price\":0,\"nb_boxes_2\":0,\"nb_boxes_2_price\":0,\"nb_boxes_3\":0,\"nb_boxes_3_price\":0,\"digital_product_type\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"meta_title\":null,\"meta_description\":null,\"size\":\"0\",\"location\":\"0\",\"class_a_price\":0,\"class_b_price\":0,\"class_c_price\":0,\"item_box\":1,\"upc_number\":null,\"body\":null,\"document1\":null,\"document2\":null,\"document3\":null,\"product_dropdown_1_id\":null,\"product_dropdown_2_id\":null,\"text\":\"2500003 - Rez 20KG - <span style=\\\"color:red;\\\">[(0 KG)]<\\/span>\"}]', 'Store', '[]', '2025-08-25 06:54:44', '2025-08-25 06:54:44'),
(2, '[{\"id\":2500003,\"barcode\":\"iVBORw0KGgoAAAANSUhEUgAAEpAAAAAeAQMAAAAPqHhZAAAABlBMVEX\\/\\/\\/8AAABVwtN+AAAAAXRSTlMAQObYZgAAAAlwSFlzAAAOxAAADsQBlSsOGwAAAapJREFUeJztklFKAwEUAwUP4JG8mkftMfyyVjB1HSKiqIyQ+SmF7HSbl5vzhYebN+4u3x8vn7fnAy+B+8P3BJ5envgocOUYOL3+RLgawunwDu8MLVDf4XR48Hv\\/4n8bWFR4OjZ6f\\/6kyfD1PfyA4qPAFTa1Sf2qgUWFTYqGFtCd02BgUWGToqEFdOc0GFhU2KRoaAHdOQ0GFhU2KRpaQHdOg4FFhU2KhhbQndNgYFFhk6KhBXTnNBhYVNikaGgB3TkNBhYVNikaWkB3ToOBRYVNioYW0J3TYGBRYZOioQV05zQYWFTYpGhoAd05DQYWFTYpGlpAd06DgUWFTYqGFtCd02BgUWGToqEFdOc0GFhU2KRoaAHdOQ0GFhU2KRpaQHdOg4FFhU2KhhbQndNgYFFhk6KhBXTnNBhYVNikaGgB3TkNBhYVNikaWkB3ToOBRYVNioYW0J3TYGBRYZOioQV05zQYWFTYpGhoAd05DQYWFTYpGlpAd06DgUWFTYqGFtCd02BgUWGToqEFdOc0GFhU2KRoaAHdOQ0GFhU2KRpaQHdOg4FFhb+a1DOYGkV2vlEZ9wAAAABJRU5ErkJggg==\",\"title\":\"Rez 20KG\",\"summary\":\"Rez 20KG\",\"description\":\"Rez 20KG\",\"category_id\":1,\"sub_category_id\":\"[\\\"1\\\"]\",\"sub_categoryid\":1,\"sub_sub_categoryid\":null,\"sub_sub_category_id\":\"[\\\"\\\"]\",\"image\":9999999,\"product_image_gallery\":\"[\\\"9999999\\\"]\",\"price\":15,\"unitprice\":15,\"original_price\":15,\"sale_price\":10,\"special_price\":0,\"tax_percentage\":0,\"badge\":null,\"status\":\"publish\",\"slug\":\"2500003\",\"attributes\":\"[]\",\"sold_count\":null,\"deleted_at\":null,\"created_at\":\"2025-08-25T06:54:44.000000Z\",\"updated_at\":\"2025-08-25T06:55:27.000000Z\",\"field1\":null,\"field2\":null,\"field3\":null,\"field4\":null,\"field5\":null,\"field6\":null,\"field7\":null,\"field8\":null,\"field9\":null,\"field10\":null,\"added_by\":\"seller\",\"user_id\":1,\"brand_id\":null,\"product_type\":\"physical\",\"code\":\"2500003\",\"new\":null,\"featured\":null,\"best_selling\":null,\"deal_of_the_day\":null,\"deal_date\":null,\"discount\":0,\"discount_type\":\"flat\",\"minimum_order_qty\":0,\"free_shipping\":0,\"colors\":\"[]\",\"featured_status\":1,\"request_status\":1,\"published\":1,\"variation\":\"[]\",\"choice_options\":\"[]\",\"refundable\":1,\"min_qty\":1,\"meta_image\":\"def.png\",\"color_image\":\"[]\",\"category_ids\":\"[{\\\"id\\\":\\\"1\\\",\\\"position\\\":1}]\",\"name\":\"Rez 20KG\",\"tag\":\"Rez 20KG\",\"purchase_price\":15,\"tax\":0,\"tax_type\":\"percent\",\"tax_model\":\"exclude\",\"thumbnail\":\"placeholder.png\",\"images\":\"[\\\"placeholder.png\\\"]\",\"details\":\"Rez 20KG\",\"unit\":\"KG\",\"shipping_cost\":0,\"unit_price\":15,\"current_stock\":0,\"lot_qty\":\"20.000\",\"on_hold_qty\":\"0.00\",\"multiply_qty\":0,\"special_image\":0,\"top_search\":0,\"warehouse_id\":1,\"volume_box\":null,\"ct_box\":null,\"weight_box\":null,\"warehouse_qty\":null,\"currency_id\":1,\"updated_by\":\"Charbel El Kabbouchi\",\"created_by\":\"Charbel El Kabbouchi\",\"tax_name\":null,\"tax_rate\":null,\"uom_id\":1,\"minimum_stock\":0,\"vendor_id\":1,\"product_rating\":1,\"rating_value\":\"33\",\"nb_boxes_1\":0,\"nb_boxes_1_price\":0,\"nb_boxes_2\":0,\"nb_boxes_2_price\":0,\"nb_boxes_3\":0,\"nb_boxes_3_price\":0,\"digital_product_type\":null,\"video_provider\":\"youtube\",\"video_url\":null,\"meta_title\":null,\"meta_description\":null,\"size\":\"0\",\"location\":\"0\",\"class_a_price\":0,\"class_b_price\":0,\"class_c_price\":0,\"item_box\":1,\"upc_number\":null,\"body\":\"1\",\"document1\":null,\"document2\":null,\"document3\":null,\"product_dropdown_1_id\":null,\"product_dropdown_2_id\":null,\"text\":\"2500003 - Rez 20KG - <span style=\\\"color:red;\\\">[(0 KG)]<\\/span>\"}]', 'Update', '[{\"id\":1,\"product_id\":2500003,\"vendor_id\":1,\"reference\":\"reference\",\"supplier_item_nb\":null,\"price\":10,\"currency_id\":1,\"created_at\":\"2025-08-25T06:55:27.000000Z\",\"updated_at\":\"2025-08-25T06:55:27.000000Z\",\"text\":10}]', '2025-08-25 06:55:27', '2025-08-25 06:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_categories`
--

CREATE TABLE `product_sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `number` int(255) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_sub_categories`
--

INSERT INTO `product_sub_categories` (`id`, `title`, `status`, `image`, `category_id`, `created_at`, `updated_at`, `name`, `number`, `description`, `order`) VALUES
(1, 'Sub Category', 'publish', NULL, 1, '2025-08-25 06:54:39', '2025-08-25 06:54:39', 'Sub Category', 1, 'Sub Category', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_sub_sub_categories`
--

CREATE TABLE `product_sub_sub_categories` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sub_category_id` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_taxes`
--

CREATE TABLE `product_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `tax_authority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_taxes`
--

INSERT INTO `product_taxes` (`id`, `product_id`, `name`, `rate`, `tax_authority`, `created_at`, `updated_at`) VALUES
(1, 2500003, 'Vat', 5.00, 'tax_authority', '2025-08-25 06:55:27', '2025-08-25 06:55:27');

-- --------------------------------------------------------

--
-- Table structure for table `product_units`
--

CREATE TABLE `product_units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_units`
--

INSERT INTO `product_units` (`id`, `name`, `unit`, `created_at`, `updated_at`, `created_by`, `user_id`) VALUES
(1, 'KG', 'KG', '2025-08-25 06:53:05', '2025-08-25 06:53:05', 'Charbel El Kabbouchi', 1),
(2, 'Pallet', 'Pallet', '2025-08-25 06:53:09', '2025-08-25 06:53:09', 'Charbel El Kabbouchi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `delivery_time` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `subtotal` decimal(8,2) NOT NULL,
  `discount` decimal(8,2) DEFAULT NULL,
  `total_weight` decimal(8,2) DEFAULT NULL,
  `total_volume` decimal(8,2) DEFAULT NULL,
  `total_qty` decimal(8,2) DEFAULT NULL,
  `totaltax` decimal(8,2) NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `vat_status` tinyint(4) DEFAULT NULL,
  `is_received` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `declined_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping` decimal(8,2) DEFAULT NULL,
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders_log`
--

CREATE TABLE `purchase_orders_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `items` text COLLATE utf8mb4_unicode_ci,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `delivery_time` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `total` double NOT NULL,
  `subtotal` double NOT NULL,
  `discount` float DEFAULT NULL,
  `total_weight` float DEFAULT NULL,
  `total_volume` float DEFAULT NULL,
  `total_qty` float DEFAULT NULL,
  `totaltax` double NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `vat_status` tinyint(4) DEFAULT NULL,
  `is_received` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `declined_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping` float DEFAULT NULL,
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `vendor_id` int(255) DEFAULT NULL,
  `vendor_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_received` decimal(8,2) NOT NULL DEFAULT '0.00',
  `unit_price` decimal(8,2) NOT NULL,
  `tax_name` text COLLATE utf8mb4_unicode_ci,
  `tax_rate` double DEFAULT NULL,
  `weight_box` decimal(8,2) DEFAULT NULL,
  `volume_box` decimal(8,2) DEFAULT NULL,
  `ct_box` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items_log`
--

CREATE TABLE `purchase_order_items_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_order_log_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `vendor_id` int(255) DEFAULT NULL,
  `vendor_reference` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` double(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `qty_received` double(8,2) NOT NULL DEFAULT '0.00',
  `unit_price` double NOT NULL,
  `tax_name` text COLLATE utf8mb4_unicode_ci,
  `tax_rate` double DEFAULT NULL,
  `weight_box` float DEFAULT NULL,
  `volume_box` float DEFAULT NULL,
  `ct_box` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_item_taxes`
--

CREATE TABLE `purchase_order_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_order_item_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `tax_authority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_report`
--

CREATE TABLE `purchase_order_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_report_items`
--

CREATE TABLE `purchase_order_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `purchase_order_id` text COLLATE utf8mb4_unicode_ci,
  `purchase_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `vendor_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_menu`
--

CREATE TABLE `quick_menu` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `quick_menu`
--

INSERT INTO `quick_menu` (`id`, `name`, `link`, `class`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Product', '/products/create', 'quick-link-proudcts', '_self', NULL, NULL),
(2, 'Sales Order', '/sales_orders/create', 'quick-link-sales-orders', '_self', NULL, NULL),
(3, 'Invoices', '/invoices/create', 'quick-link-invoices', '_self', NULL, NULL),
(4, 'Purchase Order', '/purchase_orders/create', 'quick-link-purchase-orders', '_self', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchangerate` decimal(8,2) DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_date` text COLLATE utf8mb4_unicode_ci,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `sub_total` decimal(8,2) NOT NULL,
  `discount` decimal(8,2) DEFAULT NULL,
  `shipping` decimal(8,2) DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `total` decimal(8,2) NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `vatrate` float DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `declined_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price_class` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations_log`
--

CREATE TABLE `quotations_log` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `items` text COLLATE utf8mb4_unicode_ci,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_date` text COLLATE utf8mb4_unicode_ci,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `sub_total` double NOT NULL,
  `discount` double DEFAULT NULL,
  `shipping` double DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `total` double NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `vatrate` float DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `declined_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `price_class` int(11) NOT NULL DEFAULT '0',
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` text COLLATE utf8mb4_unicode_ci,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `qty` decimal(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_item_taxes`
--

CREATE TABLE `quotation_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_item_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `tax_authority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_log_items`
--

CREATE TABLE `quotation_log_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `quotation_log_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `qty` double(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_report`
--

CREATE TABLE `quotation_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_report_items`
--

CREATE TABLE `quotation_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `quotation_id` text COLLATE utf8mb4_unicode_ci,
  `quotation_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `raw_material_type`
--

CREATE TABLE `raw_material_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_vouchers`
--

CREATE TABLE `receipt_vouchers` (
  `id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `currency_code` varchar(255) DEFAULT NULL,
  `global_vat_percentage` decimal(18,3) DEFAULT NULL,
  `client_balance` decimal(18,3) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `exchange_rate` decimal(18,3) DEFAULT NULL,
  `lines` int(255) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `total_debit` decimal(18,3) DEFAULT NULL,
  `total_debit_usd` decimal(18,3) DEFAULT NULL,
  `total_debit_vat` decimal(18,3) DEFAULT NULL,
  `balance_amount` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `year_date` varchar(255) DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL,
  `vat_status` int(255) DEFAULT NULL,
  `posted` int(255) DEFAULT '0',
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `applied_amount` decimal(18,3) DEFAULT NULL,
  `applied_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_voucher_invoices`
--

CREATE TABLE `receipt_voucher_invoices` (
  `id` int(11) NOT NULL,
  `receipt_voucher_id` int(255) DEFAULT NULL,
  `invoice_id` int(255) DEFAULT NULL,
  `number` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL,
  `total` decimal(18,3) DEFAULT NULL,
  `runningBalance` decimal(18,3) DEFAULT NULL,
  `amount_applied` decimal(18,3) DEFAULT NULL,
  `amount_applied_usd` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_voucher_items`
--

CREATE TABLE `receipt_voucher_items` (
  `id` int(11) NOT NULL,
  `receipt_voucher_id` int(255) DEFAULT NULL,
  `account_receivable_id` int(255) DEFAULT NULL,
  `account_receivable_number` varchar(255) DEFAULT NULL,
  `account_receivable_name` varchar(255) DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `account_receivable_currency_code` varchar(255) DEFAULT NULL,
  `account_receivable_currency_id` int(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `account_receivable_debit_vat_id` int(255) DEFAULT NULL,
  `account_receivable_debit_vat_code` varchar(255) DEFAULT NULL,
  `account_receivable_debit_vat_name` varchar(255) DEFAULT NULL,
  `debit` decimal(18,3) DEFAULT NULL,
  `debit_vat` decimal(18,3) DEFAULT NULL,
  `debit_usd` decimal(18,3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `receive_orders`
--

CREATE TABLE `receive_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receive_orders_report`
--

CREATE TABLE `receive_orders_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receive_orders_report_items`
--

CREATE TABLE `receive_orders_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `receive_order_id` text COLLATE utf8mb4_unicode_ci,
  `receive_order_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `received_qty` double DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `purchase_order_item_id` double DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `vendor_id` text COLLATE utf8mb4_unicode_ci,
  `exchangerate` double DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receive_order_items`
--

CREATE TABLE `receive_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `receive_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `purchase_order_item_id` int(10) UNSIGNED NOT NULL,
  `qty` double(8,2) NOT NULL,
  `purchase_qty` decimal(18,3) DEFAULT NULL,
  `nb_of_lots` decimal(18,3) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_uom_id` int(255) DEFAULT NULL,
  `received_uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_uom_unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `release_note`
--

CREATE TABLE `release_note` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `checked_by` int(255) NOT NULL DEFAULT '0',
  `body` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `release_note`
--

INSERT INTO `release_note` (`id`, `user_id`, `checked_by`, `body`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n  <span>Products:</span>\n  <ul>\n    <li class=\"announcement-li-li\">\n      <p>Brands Adjustment.</p>\n    </li>\n    <li class=\"announcement-li-li\">\n      <p>Items Per Box.</p>\n    </li>\n  \n  </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2024-05-09 13:25:59'),
(2, 2, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n  <span>Products:</span>\n  <ul>\n    <li class=\"announcement-li-li\">\n      <p>Brands Adjustment.</p>\n    </li>\n    <li class=\"announcement-li-li\">\n      <p>Items Per Box.</p>\n    </li>\n  \n  </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-10-23 07:11:46'),
(3, 3, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n  <span>Products:</span>\n  <ul>\n    <li class=\"announcement-li-li\">\n      <p>Brands Adjustment.</p>\n    </li>\n    <li class=\"announcement-li-li\">\n      <p>Items Per Box.</p>\n    </li>\n  \n  </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-10-23 07:11:46'),
(4, 4, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n  <span>Products:</span>\n  <ul>\n    <li class=\"announcement-li-li\">\n      <p>Brands Adjustment.</p>\n    </li>\n    <li class=\"announcement-li-li\">\n      <p>Items Per Box.</p>\n    </li>\n  \n  </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-10-24 16:57:20'),
(5, 5, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n  <span>Products:</span>\n  <ul>\n    <li class=\"announcement-li-li\">\n      <p>Brands Adjustment.</p>\n    </li>\n    <li class=\"announcement-li-li\">\n      <p>Items Per Box.</p>\n    </li>\n  \n  </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-10-23 07:11:46'),
(6, 6, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-10-23 07:11:46'),
(7, 7, 7, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2024-05-10 04:12:37'),
(8, 8, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-11-02 00:38:48');
INSERT INTO `release_note` (`id`, `user_id`, `checked_by`, `body`, `created_at`, `updated_at`) VALUES
(9, 9, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-11-02 02:17:14'),
(10, 10, 0, '<ul class=\"form-group\">\n<li class=\"announcement-li\">\n    <span>Reports:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>All Reports have  been adjusted to be exported with proper values as PDF/Excel.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Missing Reports have been added.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Price Changes Report Done.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Invoices:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Now you can Re-open Confirmed/Sent Documents.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>If the invoice is already paid or partially paid, when clicking reopen, the amount paid will be converted to a credit note document, to be applied later once the invoice editing is done!</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Product name will not change after renaming submited products</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Discount Calculation bug fixed.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Quick History:</span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>When creating an Invoice/Purchase Document, after selecting Client/Vendor and the Product, a quick link button will appear</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>This link will display the sale/purchase history for the selected fields.</p>\n      </li>\n    </ul>\n</li>\n<li class=\"announcement-li\">\n    <span>Important Note:&nbsp;<i style=\"color: darkblue;\" class=\"fa fa-exclamation-triangle\"></i></span>\n    <ul>\n      <li class=\"announcement-li-li\">\n        <p>Make sure after submitting a new purchase order, to receive the quantities as reception, in order to adjust the stock.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Once Received, better to generate a supplier invoice \"Vendor Bill\", in order to add payments to these bills.</p>\n      </li>\n      <li class=\"announcement-li-li\">\n        <p>Following this procedure will allow us to monitor the balance amounts and audit the profit by the next year.</p>\n      </li>\n    </ul>\n</li>\n</ul>\n<style>\n\n.modal {\n  border-radius: .8rem;\n  color: var(--light);\n  background: var(--background);\n  box-shadow: var(--m-shadow, .4rem .4rem 10.2rem .2rem) var(--shadow-1);\n  overflow: hidden;\n}\n\n.modal__title {\n  font-size: 3.2rem;\n}\n.modal__text {\n  padding: 0 4rem;\n  margin-top: 4rem;\n\n  font-size: 1.6rem;\n  line-height: 2;\n}\n\n.modal__btn {\n  margin-top: 4rem;\n  padding: 1rem 1.6rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  background: transparent;\n  font-size: 1.4rem;\n  font-family: inherit;\n  letter-spacing: .2rem;\n\n  transition: .2s;\n  cursor: pointer;\n}\n\n.modal__btn:nth-of-type(1) {\n  margin-right: 1rem;\n}\n\n.modal__btn:hover,\n.modal__btn:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n\n/* link-... */\n.link-1 {\n  font-size: 1.8rem;\n\n  color: var(--light);\n  background: var(--background);\n  box-shadow: .4rem .4rem 2.4rem .2rem var(--shadow-1);\n  border-radius: 100rem;\n  padding: 1.4rem 3.2rem;\n\n  transition: .2s;\n}\n\n.link-1:hover,\n.link-1:focus {\n  transform: translateY(-.2rem);\n  box-shadow: 0 0 4.4rem .2rem var(--shadow-2);\n}\n\n.link-1:focus {\n  box-shadow:\n    0 0 4.4rem .2rem var(--shadow-2),\n    0 0 0 .4rem var(--global-background),\n    0 0 0 .5rem var(--focus);\n}\n\n.link-2 {\n  width: 4rem;\n  height: 4rem;\n  border: 1px solid var(--border-color);\n  border-radius: 100rem;\n\n  color: inherit;\n  font-size: 2.2rem;\n\n  position: absolute;\n  top: 2rem;\n  right: 2rem;\n\n  display: flex;\n  justify-content: center;\n  align-items: center;\n\n  transition: .2s;\n}\n\n.link-2::before {\n  content: \'×\';\n\n  transform: translateY(-.1rem);\n}\n\n.link-2:hover,\n.link-2:focus {\n  background: var(--focus);\n  border-color: var(--focus);\n  transform: translateY(-.2rem);\n}\n\n.abs-site-link {\n  position: fixed;\n  bottom: 20px;\n  left: 20px;\n  color: hsla(0, 0%, 1000%, .6);\n  font-size: 1.6rem;\n}\n.announcement-li {\n    list-style: auto;\n    margin:10px;\n}\n.announcement-li-li {\n  list-style: circle;\n    color: darkcyan;\n    margin: 10px 20px;\n}\n.announcement-li > span{\n    color:red; font-weight:bold\n}\n\n.switch {\n  position: relative;\n  display: inline-block;\n  width: 36px;\n  height: 24px;\n}\n\n.switch input { \n  opacity: 0;\n  width: 0;\n  height: 0;\n}\n\n.slider {\n  position: absolute;\n  cursor: pointer;\n  top: 0;\n  left: 0;\n  right: 0;\n  bottom: 0;\n  background-color: #ccc;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\n.slider:before {\n  position: absolute;\n  content: \"\";\n  height: 16px;\n  width: 16px;\n  left: 4px;\n  bottom: 4px;\n  background-color: white;\n  -webkit-transition: .4s;\n  transition: .4s;\n}\n\ninput:checked + .slider {\n  background-color: #2196F3;\n}\n\ninput:focus + .slider {\n  box-shadow: 0 0 1px #2196F3;\n}\n\ninput:checked + .slider:before {\n  -webkit-transform: translateX(26px);\n  -ms-transform: translateX(26px);\n  transform: translateX(26px);\n}\n\n/* Rounded sliders */\n.slider.round {\n  border-radius: 34px;\n}\n\n.slider.round:before {\n  border-radius: 50%;\n}\n\n.active-slider::before{\n    left: 16px !important;\n}\n.active-slider {\n    background: green;\n}\n\n\n.modal-wrapper {\n    overflow: hidden !important;\n}\n</style>\n', NULL, '2023-11-21 03:20:38');

-- --------------------------------------------------------

--
-- Table structure for table `report_views`
--

CREATE TABLE `report_views` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `active` int(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `report_views`
--

INSERT INTO `report_views` (`id`, `name`, `link`, `class`, `active`, `created_at`, `updated_at`) VALUES
(1, 'test', 'test', NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_widgets`
--

CREATE TABLE `report_widgets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_widgets`
--

INSERT INTO `report_widgets` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Top 10 Sales Invoices', 'Top 10 Sales Invoices', '2025-08-24 21:01:57', '2025-08-24 21:01:57'),
(2, 'Top 10 Purchase Orders', 'Top 10 Purchase Orders', '2025-08-24 21:01:57', '2025-08-24 21:01:57'),
(3, 'Profit & Loss', 'Profit & Loss', '2025-08-24 21:01:57', '2025-08-24 21:01:57');

-- --------------------------------------------------------

--
-- Table structure for table `return_deposits`
--

CREATE TABLE `return_deposits` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `return_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE `sales_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `seller_commission` float DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discounted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` float DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_gateway` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_track` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_details` longtext COLLATE utf8mb4_unicode_ci,
  `payment_meta` text COLLATE utf8mb4_unicode_ci,
  `shipping_address_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_shipping_option` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_image_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `field1` text COLLATE utf8mb4_unicode_ci,
  `field2` text COLLATE utf8mb4_unicode_ci,
  `field3` text COLLATE utf8mb4_unicode_ci,
  `field4` text COLLATE utf8mb4_unicode_ci,
  `field5` text COLLATE utf8mb4_unicode_ci,
  `field6` text COLLATE utf8mb4_unicode_ci,
  `field7` text COLLATE utf8mb4_unicode_ci,
  `field8` text COLLATE utf8mb4_unicode_ci,
  `field9` text COLLATE utf8mb4_unicode_ci,
  `field10` text COLLATE utf8mb4_unicode_ci,
  `status_id` int(255) NOT NULL DEFAULT '1',
  `date` date DEFAULT NULL,
  `currency_id` int(255) NOT NULL DEFAULT '1',
  `paymentcondition_id` int(255) NOT NULL DEFAULT '1',
  `deliverycondition_id` int(255) NOT NULL DEFAULT '1',
  `shipping` float NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '0',
  `sub_total` float DEFAULT NULL,
  `manager_id` int(255) DEFAULT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_status` int(255) DEFAULT '0',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `exchangerate` float DEFAULT NULL,
  `vatrate` float DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `confirmed_date` date DEFAULT NULL,
  `sent_date` date DEFAULT NULL,
  `unit_price` float NOT NULL DEFAULT '0',
  `qty` float NOT NULL DEFAULT '0',
  `invoiceable_id` int(255) DEFAULT NULL,
  `invoiceable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT NULL,
  `price_class` float NOT NULL DEFAULT '0',
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `quotation_id` int(255) DEFAULT NULL,
  `document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders_log`
--

CREATE TABLE `sales_orders_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `items` text COLLATE utf8mb4_unicode_ci,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `seller_commission` float DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` longtext COLLATE utf8mb4_unicode_ci,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discounted` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total` float DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_gateway` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_track` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_details` longtext COLLATE utf8mb4_unicode_ci,
  `payment_meta` text COLLATE utf8mb4_unicode_ci,
  `shipping_address_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selected_shipping_option` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checkout_image_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `field1` text COLLATE utf8mb4_unicode_ci,
  `field2` text COLLATE utf8mb4_unicode_ci,
  `field3` text COLLATE utf8mb4_unicode_ci,
  `field4` text COLLATE utf8mb4_unicode_ci,
  `field5` text COLLATE utf8mb4_unicode_ci,
  `field6` text COLLATE utf8mb4_unicode_ci,
  `field7` text COLLATE utf8mb4_unicode_ci,
  `field8` text COLLATE utf8mb4_unicode_ci,
  `field9` text COLLATE utf8mb4_unicode_ci,
  `field10` text COLLATE utf8mb4_unicode_ci,
  `status_id` int(255) NOT NULL DEFAULT '1',
  `date` date DEFAULT NULL,
  `currency_id` int(255) NOT NULL DEFAULT '1',
  `paymentcondition_id` int(255) NOT NULL DEFAULT '1',
  `deliverycondition_id` int(255) NOT NULL DEFAULT '1',
  `shipping` float NOT NULL DEFAULT '0',
  `discount` float NOT NULL DEFAULT '0',
  `sub_total` float DEFAULT NULL,
  `manager_id` int(255) DEFAULT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_status` int(255) DEFAULT '0',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `exchangerate` float DEFAULT NULL,
  `vatrate` float DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `confirmed_date` date DEFAULT NULL,
  `sent_date` date DEFAULT NULL,
  `unit_price` float NOT NULL DEFAULT '0',
  `qty` float NOT NULL DEFAULT '0',
  `invoiceable_id` int(255) DEFAULT NULL,
  `invoiceable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` timestamp NULL DEFAULT NULL,
  `price_class` float NOT NULL DEFAULT '0',
  `line1_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line1_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line2_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line3_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `line4_value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders___`
--

CREATE TABLE `sales_orders___` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(255) DEFAULT NULL,
  `client_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quotation_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `delivery_date` text COLLATE utf8mb4_unicode_ci,
  `paymentcondition_id` int(11) DEFAULT NULL,
  `deliverycondition_id` int(11) DEFAULT NULL,
  `sub_total` double NOT NULL,
  `discount` double DEFAULT NULL,
  `shipping` double DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `total` double NOT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `confirmed_date` date DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL DEFAULT '1',
  `vatrate` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_items`
--

CREATE TABLE `sales_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `order_id` bigint(20) NOT NULL,
  `attributes` text COLLATE utf8mb4_unicode_ci,
  `quantity` decimal(10,3) DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lb',
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uom_id` int(255) DEFAULT NULL,
  `cost_price` double(8,2) DEFAULT NULL,
  `discount_usd` decimal(8,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_items_log`
--

CREATE TABLE `sales_order_items_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) NOT NULL,
  `product_name` text COLLATE utf8mb4_unicode_ci,
  `order_id` bigint(20) NOT NULL,
  `attributes` text COLLATE utf8mb4_unicode_ci,
  `quantity` int(11) DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lb',
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `uom_id` int(255) DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_items___`
--

CREATE TABLE `sales_order_items___` (
  `id` int(10) UNSIGNED NOT NULL,
  `sales_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `qty` double(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `qty_issued` double(8,2) NOT NULL DEFAULT '0.00',
  `unit_price` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_item_taxes`
--

CREATE TABLE `sales_order_item_taxes` (
  `id` int(10) UNSIGNED NOT NULL,
  `sales_order_item_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate` double(8,2) NOT NULL,
  `tax_authority` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_report`
--

CREATE TABLE `sales_order_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_report_items`
--

CREATE TABLE `sales_order_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `sales_order_id` text COLLATE utf8mb4_unicode_ci,
  `sales_order_date` timestamp NULL DEFAULT NULL,
  `product_id` text COLLATE utf8mb4_unicode_ci,
  `qty` double DEFAULT NULL,
  `qty_received` double DEFAULT NULL,
  `total` double DEFAULT NULL,
  `unit_price` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` text COLLATE utf8mb4_unicode_ci,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_queries`
--

CREATE TABLE `saved_queries` (
  `id` int(11) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `body` text,
  `saved` int(255) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `saved_queries`
--

INSERT INTO `saved_queries` (`id`, `user_id`, `body`, `saved`, `created_at`, `updated_at`, `created_by`) VALUES
(1, 1, 'SELECT jv.*, jvi.*\r\nFROM journal_vouchers jv\r\nJOIN journal_voucher_items jvi\r\n    ON jvi.journal_voucher_id = jv.id\r\nWHERE jv.number = \'10652\'\r\nLIMIT 100;', 0, '2025-08-25 07:48:25', '2025-08-25 07:48:25', 'Charbel El Kabbouchi'),
(2, 1, 'SELECT jv.*, jvi.*\r\nFROM journal_vouchers jv\r\nJOIN journal_voucher_items jvi\r\n    ON jvi.journal_voucher_id = jv.id\r\n \r\nLIMIT 100;', 0, '2025-08-25 07:48:33', '2025-08-25 07:48:33', 'Charbel El Kabbouchi');

-- --------------------------------------------------------

--
-- Table structure for table `segments`
--

CREATE TABLE `segments` (
  `id` int(11) NOT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `segments`
--

INSERT INTO `segments` (`id`, `body`) VALUES
(1, 'http://app.perceive-agency.com/api/products');

-- --------------------------------------------------------

--
-- Table structure for table `seller_payments`
--

CREATE TABLE `seller_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `order_amount` float DEFAULT NULL,
  `amount_received` float DEFAULT NULL,
  `amount_pending` float DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_at` timestamp NULL DEFAULT NULL,
  `paid_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_payments_docs`
--

CREATE TABLE `seller_payments_docs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `total_amount_received` float DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_at` timestamp NULL DEFAULT NULL,
  `paid_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` text COLLATE utf8mb4_unicode_ci,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_payments_docs_items`
--

CREATE TABLE `seller_payments_docs_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `seller_payments_docs_id` int(10) UNSIGNED DEFAULT NULL,
  `seller_payment_id` int(255) DEFAULT NULL,
  `client_payment_id` int(255) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seller_id` int(10) UNSIGNED DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `sales_order_id` int(10) UNSIGNED DEFAULT NULL,
  `currency_id` int(10) UNSIGNED DEFAULT NULL,
  `total_amount` float DEFAULT NULL,
  `order_amount` float DEFAULT NULL,
  `amount_received` float DEFAULT NULL,
  `amount_pending` float DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `payment_at` timestamp NULL DEFAULT NULL,
  `paid_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date DEFAULT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_payments_report`
--

CREATE TABLE `seller_payments_report` (
  `id` int(11) NOT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `seller_payments_report_items`
--

CREATE TABLE `seller_payments_report_items` (
  `id` int(11) NOT NULL,
  `report_id` int(255) DEFAULT NULL,
  `seller_payment_id` int(255) DEFAULT NULL,
  `seller_id` int(255) DEFAULT NULL,
  `client_id` int(255) DEFAULT NULL,
  `sales_order_id` int(255) DEFAULT NULL,
  `body` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `amount_received` float DEFAULT NULL,
  `payment_mode` int(255) DEFAULT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `seller_statement`
--

CREATE TABLE `seller_statement` (
  `id` int(10) UNSIGNED NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci,
  `phone` text COLLATE utf8mb4_unicode_ci,
  `commission` float NOT NULL,
  `commission_balance` float DEFAULT NULL,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `seller_statement_items`
--

CREATE TABLE `seller_statement_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `statement_id` int(11) NOT NULL,
  `reference_number` text COLLATE utf8mb4_unicode_ci,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `seller_payment_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `amount_received` float DEFAULT NULL,
  `amount_pending` float DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `reference_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'currency_id', '1', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(2, 'app_title', 'PerceiveSystem', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(3, 'uploaded_logo', 'ljuf0WxhGFdSiae72wfrzDBcbw2AzFx9.png', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(4, 'company_name', 'PerceiveSystem', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(5, 'company_type', '0', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(6, 'company_address', 'Beirut Office  \r\nBeirut Beirut , Beirut, Lebanon', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(7, 'company_telephone', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(8, 'company_email', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(9, 'company_website', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(10, 'company_payment_details', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(11, 'sent_from_email', 'charbelkabbouchi@gmail.com', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(12, 'sent_from_name', 'PerceiveSystem Support', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(13, 'global_bcc_email', 'charbelkabbouchi@gmail.com', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(14, 'footer_line_1', 'PerceiveSystem Support LLC', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(15, 'footer_line_2', 'sales@perceive-agency.com  •   www.perceive-agency.com   •   Tel.: +(961) 71 38 7447  •', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(16, 'footer_line_3', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(17, 'header', 'cIXyzFBfKvkI1QkV0n3JMQKThfK17OV3.png', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(18, 'footer', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(19, 'header-html', '/home2/zutlizte/sites/system/app.perceive-agency.com/storage/app/header.html', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(20, 'footer-html', NULL, '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(21, 'erp', '#ERP#2021#Perceive', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(22, 'display_vat', '1', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(23, 'working_days', '7', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(24, 'starting_date', '2025', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(25, 'disable_second_currency', '0', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(26, 'invoices_available_qty', '0', '2021-08-07 18:00:00', '2021-08-07 18:00:00'),
(27, 'purchase_orders_email', '0', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(28, 'purchase_orders_notification', '1', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(29, 'invoices_email', '0', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(30, 'invoices_notification', '1', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(31, 'sales_orders_email', '0', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(32, 'sales_orders_notification', '1', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(33, 'quotations_email', '0', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(34, 'quotations_notification', '0', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(35, 'bills_email', '0', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(36, 'bills_notification', '1', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(37, 'app_color', '#714B67', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(38, 'text_color', '#fff', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(39, 'copyrights', 'v2.2.7 perceive-agency.com © Copyright 2025. All Rights Reserved.', '2022-08-15 23:00:00', '2022-08-15 23:00:00'),
(40, 'nav_color', '#714B67', NULL, NULL),
(41, 'license_email', 'info@perceive-agency.com', NULL, NULL),
(42, 'box_1', '1', NULL, NULL),
(43, 'box_2', '1', NULL, NULL),
(44, 'box_3', '1', NULL, NULL),
(45, 'box_4', '1', NULL, NULL),
(46, 'box_5', '0', NULL, NULL),
(47, 'box_6', '1', NULL, NULL),
(48, 'box_7', '1', NULL, NULL),
(49, 'box_8', '0', NULL, NULL),
(50, 'box_9', '1', NULL, NULL),
(51, 'box_10', '1', NULL, NULL),
(52, 'box_11', '0', NULL, NULL),
(53, 'box_12', '0', NULL, NULL),
(54, 'box_13', '1', NULL, NULL),
(55, 'box_14', '1', NULL, NULL),
(56, 'box_15', '0', NULL, NULL),
(57, 'chart_1', '1', NULL, NULL),
(58, 'chart_2', '1', NULL, NULL),
(59, 'display_exchange_rate', '1', NULL, NULL),
(60, 'display_vat_rate', '1', NULL, NULL),
(61, 'header_line_1', 'PerceiveSystem Supports LLC', NULL, NULL),
(62, 'header_line_2', 'Lebanon, Beqaa', NULL, NULL),
(63, 'header_line_3', 'Zahle, Main Road', NULL, NULL),
(64, 'extra_line', 'extra_line', NULL, NULL),
(65, 'quotation_field_1', NULL, NULL, NULL),
(66, 'quotation_field_2', NULL, NULL, NULL),
(67, 'quotation_field_3', NULL, NULL, NULL),
(68, 'quotation_field_4', NULL, NULL, NULL),
(69, 'sales_order_field_1', NULL, NULL, NULL),
(70, 'sales_order_field_2', NULL, NULL, NULL),
(71, 'sales_order_field_3', NULL, NULL, NULL),
(72, 'sales_order_field_4', NULL, NULL, NULL),
(73, 'invoice_field_1', NULL, NULL, NULL),
(74, 'invoice_field_2', NULL, NULL, NULL),
(75, 'invoice_field_3', 'Total Weight', NULL, NULL),
(76, 'invoice_field_4', 'Total Pallets', NULL, NULL),
(77, 'bill_field_1', NULL, NULL, NULL),
(78, 'bill_field_2', NULL, NULL, NULL),
(79, 'bill_field_3', NULL, NULL, NULL),
(80, 'bill_field_4', NULL, NULL, NULL),
(81, 'purchase_order_field_1', NULL, NULL, NULL),
(82, 'purchase_order_field_2', NULL, NULL, NULL),
(83, 'purchase_order_field_3', NULL, NULL, NULL),
(84, 'purchase_order_field_4', NULL, NULL, NULL),
(85, 'app_categories_columns_count', '3', NULL, NULL),
(86, 'app_categories_card_height', '290', NULL, NULL),
(87, 'app_categories_image_height', '0', NULL, NULL),
(88, 'app_categories_image_width', '0', NULL, NULL),
(89, 'app_categories_image_fill', 'fitWidth', NULL, NULL),
(90, 'app_categories_image_card_height', '175', NULL, NULL),
(91, 'product_dropdown_1', NULL, NULL, NULL),
(92, 'product_dropdown_2', NULL, NULL, NULL),
(93, 'client_dropdown_1', 'Special Client', NULL, NULL),
(94, 'client_dropdown_2', NULL, NULL, NULL),
(95, 'global_vat_percentage', '5', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE `shippers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `person` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_status` int(11) DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `payment_details` text COLLATE utf8mb4_unicode_ci,
  `container_size` float DEFAULT NULL,
  `total_expense` double NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipper_bills`
--

CREATE TABLE `shipper_bills` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(255) DEFAULT NULL,
  `shipper_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `container_order_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `year_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `total` double NOT NULL,
  `subtotal` double DEFAULT NULL,
  `totaltax` double NOT NULL,
  `exchangerate` double DEFAULT NULL,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` tinyint(4) NOT NULL,
  `vat_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `amount_paid` double NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipper_bill_items`
--

CREATE TABLE `shipper_bill_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `shipper_bill_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `vendor_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` double(8,2) NOT NULL,
  `uom_id` int(11) DEFAULT NULL,
  `uom_unit` text COLLATE utf8mb4_unicode_ci,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uom_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` double NOT NULL,
  `tax_name` text COLLATE utf8mb4_unicode_ci,
  `tax_rate` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipper_payments`
--

CREATE TABLE `shipper_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipper_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `amount_paid` double DEFAULT NULL,
  `amount_paid_usd` double DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `amount_paid_lbprate` double DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipper_payment_items`
--

CREATE TABLE `shipper_payment_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `shipper_payment_id` int(10) UNSIGNED NOT NULL,
  `shipper_bill_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` double DEFAULT '0',
  `amount_applied_lbp` double DEFAULT '0',
  `amount_applied_lbp_rate` double DEFAULT '1',
  `amount_applied_vat` double DEFAULT '0',
  `amount_applied_vat_rate` double DEFAULT '1',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `vat_paid` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipper_statement`
--

CREATE TABLE `shipper_statement` (
  `id` int(10) UNSIGNED NOT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `person` text COLLATE utf8mb4_unicode_ci,
  `company` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipper_statement_items`
--

CREATE TABLE `shipper_statement_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `shipper_id` int(11) DEFAULT NULL,
  `statement_id` int(11) NOT NULL,
  `reference_number` text COLLATE utf8mb4_unicode_ci,
  `reference_id` int(11) DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `reference_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sidebar_links`
--

CREATE TABLE `sidebar_links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `route_path` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `is_main` tinyint(1) DEFAULT '0',
  `tab_type` varchar(50) DEFAULT NULL,
  `sort_order` int(11) DEFAULT '0',
  `extra_class` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sidebar_links`
--

INSERT INTO `sidebar_links` (`id`, `title`, `route_path`, `icon`, `parent_id`, `is_main`, `tab_type`, `sort_order`, `extra_class`, `created_at`, `updated_at`) VALUES
(4, 'Custom Query', '/custom_query', 'fa fa-database', 60, 0, 'dashboard_tab', 4, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:37'),
(5, 'Sales', NULL, 'fa fa-shopping-cart', NULL, 1, 'sales_tab', 1, 'Sales_Tab', '2025-08-24 07:40:03', '2025-08-25 07:23:37'),
(6, 'Sales Orders', '/sales_orders', 'fa fa-file-invoice', 5, 0, 'sales_tab', 1, 'Sales _Orders', '2025-08-24 07:40:03', '2025-08-25 07:23:37'),
(7, 'Client Invoices', '/invoices', 'fa fa-file-invoice-dollar', 5, 0, 'sales_tab', 2, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:37'),
(8, 'Quotations', '/quotations', 'fa fa-file-signature', 5, 0, 'sales_tab', 3, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:37'),
(9, 'Clients', '/clients', 'fa fa-user-tie', 5, 0, 'sales_tab', 4, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(15, 'Customer Returns', '/customer_returns', 'fa fa-undo-alt', 16, 0, 'procurment_tab', 2, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(16, 'Procurment & Stock', NULL, 'fa fa-truck-loading', NULL, 1, 'procurment_tab', 3, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(17, 'Products', '/products', 'fa fa-box', 16, 0, 'procurment_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(18, 'Warehouses', '/warehouses', 'fa fa-warehouse', 16, 0, 'procurment_tab', 2, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(19, 'Raw Material Type', '/raw_material_type', 'fa fa-cogs', 16, 0, 'procurment_tab', 3, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(20, 'Receive Orders', '/receive_orders', 'fa fa-truck', 16, 0, 'procurment_tab', 4, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(21, 'Vendors', '/vendors', 'fa fa-users', 16, 0, 'procurment_tab', 5, NULL, '2025-08-24 07:40:03', '2025-08-24 07:40:03'),
(22, 'Purchase Orders', '/purchase_orders', 'fa fa-file-invoice', 16, 0, 'procurment_tab', 6, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(23, 'Transfers', '/transfers', 'fa fa-exchange-alt', 16, 0, 'procurment_tab', 7, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(24, 'Products Division', '/products_division', 'fa fa-project-diagram', 16, 0, 'procurment_tab', 8, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(25, 'Products Aggregation', '/products_aggregation', 'fa fa-layer-group', 16, 0, 'procurment_tab', 9, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(26, 'Stock Movement', '/stock_movement', 'fa fa-random', 16, 0, 'procurment_tab', 10, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(27, 'Stock Count', '/stock_count', 'fa fa-clipboard-check', 16, 0, 'procurment_tab', 11, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(28, 'Damaged Deteriorate', '/damaged_deteriorate', 'fa fa-exclamation-triangle', 16, 0, 'procurment_tab', 12, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(29, 'Accounting', NULL, 'fa fa-calculator', NULL, 1, 'accounting_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(30, 'Clients', '/clients', 'fa fa-user-tie', 29, 0, 'accounting_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(31, 'Journal Vouchers', '/journal_vouchers', 'fa fa-book', 29, 0, 'accounting_tab', 2, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(32, 'JV Movement', '/journal_vouchers_movement', 'fa fa-exchange-alt', 29, 0, 'accounting_tab', 3, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(33, 'Receipt Voucher', '/receipt_vouchers', 'fa fa-receipt', 29, 0, 'accounting_tab', 4, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(34, 'Payment Voucher', '/payment_vouchers', 'fa fa-money-check-alt', 29, 0, 'accounting_tab', 5, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(36, 'Client Invoices', '/invoices', 'fa fa-file-invoice-dollar', 29, 0, 'accounting_tab', 7, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:37'),
(37, 'Credit Notes', '/credit_notes', 'fa fa-file-contract', 29, 0, 'accounting_tab', 8, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(38, 'Debit Notes', '/debit_notes', 'fa fa-file-invoice', 29, 0, 'accounting_tab', 9, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(39, 'Client SOA', '/statement', 'fa fa-balance-scale', 29, 0, 'accounting_tab', 10, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(40, 'Vendor Expenses', '/expenses', 'fa fa-money-bill-wave', 29, 0, 'accounting_tab', 11, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(41, 'Vendor Bills', '/bills', 'fa fa-file-invoice-dollar', 29, 0, 'accounting_tab', 12, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(42, 'Vendor SOA', '/vendor_statement', 'fa fa-file-alt', 29, 0, 'accounting_tab', 13, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(43, 'Company', NULL, 'fa fa-building', NULL, 1, 'company_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(44, 'Chart of Accounts', '/chart_of_accounts', 'fa fa-project-diagram', 43, 0, 'company_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(45, 'Balance Sheet', '/balance_sheet', 'fa fa-chart-line', 43, 0, 'company_tab', 2, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(46, 'General Ledger', '/general_ledger', 'fa fa-book-open', 43, 0, 'company_tab', 3, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(47, 'Profit & Loss (Income Statement)', '/profit_loss', 'fa fa-chart-pie', 43, 0, 'company_tab', 4, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(60, 'General Parameters', NULL, 'fa fa-cogs', NULL, 1, 'settings_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(61, 'General Settings', '/general_settings', 'fa fa-sliders-h', 60, 0, 'settings_tab', 1, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(62, 'Custom Reports', '/sidebar_reports', 'fa fa-chart-bar', 60, 0, 'settings_tab', 2, NULL, '2025-08-24 07:40:03', '2025-08-25 07:23:38'),
(65, 'Journal Flow', '/journal_vouchers_flow', 'fa fa-bus', 61, 0, 'settings_tab', 2, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(66, 'Third Parties Extras', '/third_parties_extras', 'fa fa-bus', 61, 0, 'settings_tab', 3, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(67, 'Delivery Condition', '/deliverycondition', 'fa fa-bus', 61, 0, 'settings_tab', 4, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(68, 'Payment Condition', '/paymentcondition', 'fa fa-credit-card', 61, 0, 'settings_tab', 5, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(69, 'Payment Options', '/payment_options', 'fa fa-credit-card', 61, 0, 'settings_tab', 6, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(70, 'VAT Rate', '/vatrate', 'fa fa-exchange', 61, 0, 'settings_tab', 7, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(71, 'Exchange Rate', '/exchangerate', 'fa fa-exchange', 61, 0, 'settings_tab', 8, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(72, 'UOM', '/uom', 'fa fa-balance-scale', 61, 0, 'settings_tab', 9, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(73, 'Counters', '/counters', 'fa fa-cogs', 61, 0, 'settings_tab', 10, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(74, 'Currencies', '/currencies', 'fa fa-money', 61, 0, 'settings_tab', 11, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(75, 'Categories', '/categories', 'fa fa-list-alt', 61, 0, 'settings_tab', 12, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(76, 'Sub Categories', '/subcategories', 'fa fa-list-alt', 61, 0, 'settings_tab', 13, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(77, 'Brands', '/brands', 'fa fa-list-alt', 61, 0, 'settings_tab', 14, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(78, 'Trial Balance Report', '/trial_balance_report', 'fa fa-balance-scale', 62, 0, 'settings_tab', 1, 'Trial_balance_Report', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(79, 'Price Changes', '/price_changes_report/create', 'fa fa-chart-line', 62, 0, 'settings_tab', 2, 'Price_Changes', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(80, 'Cost Changes', '/cost_changes_report/create', 'fa fa-chart-line', 62, 0, 'settings_tab', 3, 'Cost_Changes', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(81, 'Sales Orders', '/sales_orders_report/create', 'fa fa-file-invoice', 62, 0, 'settings_tab', 4, 'Sales_Orders_Report', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(82, 'Sales Invoice', '/invoices_report/create', 'fa fa-file-invoice-dollar', 62, 0, 'settings_tab', 5, 'Sales_Invoice', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(83, 'Quotations', '/quotations_report/create', 'fa fa-file-signature', 62, 0, 'settings_tab', 6, 'Quotations', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(84, 'Receive Orders', '/receive_orders_report/create', 'fa fa-truck', 62, 0, 'settings_tab', 7, 'Receive_Orders', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(85, 'Purchase Orders', '/purchase_orders_report/create', 'fa fa-file-invoice', 62, 0, 'settings_tab', 8, 'Purchase_Orders', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(86, 'Products', '/products_report/create', 'fa fa-box', 62, 0, 'settings_tab', 9, 'Products', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(87, 'Products Catalogue Landscape', '/products_catalogue/create', 'fa fa-book', 62, 0, 'settings_tab', 10, 'Products_Catalogue_Landscape', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(88, 'Products Catalogue Portrait', '/products_catalogue/show', 'fa fa-book', 62, 0, 'settings_tab', 11, 'Products_Catalogue_Portrait', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(89, 'Client SOA', '/statement', 'fa fa-balance-scale', 62, 0, 'settings_tab', 12, 'Client_SOA', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(90, 'Client Balance', '/clients_balance_report/create', 'fa fa-balance-scale', 62, 0, 'settings_tab', 13, 'Client_Balance', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(91, 'Sellers Payments', '/seller_payments_docs_report/create', 'fa fa-money-bill-wave', 62, 0, 'settings_tab', 14, 'Sellers_Payments', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(92, 'Payment Options', '/payment_options_report/create', 'fa fa-credit-card', 62, 0, 'settings_tab', 15, 'Payment_Options', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(93, 'Import Tools', '/import_tools', 'fa fa-file-import', 62, 0, 'settings_tab', 16, 'Import_Tools', '2025-08-25 07:59:31', '2025-08-25 07:59:31'),
(94, 'Users', '/users', 'fa fa-users', 61, 0, 'settings_tab', 15, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54'),
(95, 'Settings', '/settings', 'fa fa-sliders-h', 61, 0, 'settings_tab', 16, NULL, '2025-08-25 07:57:54', '2025-08-25 07:57:54');

-- --------------------------------------------------------

--
-- Table structure for table `statement`
--

CREATE TABLE `statement` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `person` text COLLATE utf8mb4_unicode_ci,
  `company` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statement_items`
--

CREATE TABLE `statement_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `statement_id` int(11) NOT NULL,
  `reference_number` text COLLATE utf8mb4_unicode_ci,
  `reference_id` int(11) DEFAULT NULL,
  `amount_applied` decimal(18,3) DEFAULT NULL,
  `amount_applied_vat` decimal(18,3) DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `reference_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_counts`
--

CREATE TABLE `stock_counts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `count_date` date DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_counts`
--

INSERT INTO `stock_counts` (`id`, `count_date`, `category_id`, `sub_category_id`, `sub_sub_category_id`, `submitted_at`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(23, '2025-08-25', NULL, NULL, NULL, NULL, NULL, 'Charbel El Kabbouchi', '2025-08-25 07:09:57', '2025-08-25 07:09:57'),
(24, '2025-08-25', NULL, NULL, NULL, '2025-08-25 07:14:47', NULL, 'Charbel El Kabbouchi', '2025-08-25 07:12:14', '2025-08-25 07:12:14'),
(25, '2025-08-25', NULL, NULL, NULL, NULL, NULL, 'Charbel El Kabbouchi', '2025-08-25 07:13:33', '2025-08-25 07:13:33');

-- --------------------------------------------------------

--
-- Table structure for table `stock_count_lots`
--

CREATE TABLE `stock_count_lots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_count_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_stock` decimal(15,3) NOT NULL DEFAULT '0.000',
  `balance` decimal(18,3) DEFAULT NULL,
  `inventoried_stock` decimal(15,3) NOT NULL DEFAULT '0.000',
  `variance` decimal(15,3) NOT NULL DEFAULT '0.000',
  `scanned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_count_lots`
--

INSERT INTO `stock_count_lots` (`id`, `stock_count_id`, `lot_id`, `product_id`, `code`, `category_id`, `sub_category_id`, `sub_sub_category_id`, `uom_id`, `current_stock`, `balance`, `inventoried_stock`, `variance`, `scanned_at`, `created_at`, `updated_at`) VALUES
(1, 24, 3, 2500003, '2500003707', 1, 1, NULL, 1, 20.000, 20.000, 20.000, 0.000, '2025-08-25 07:12:19', '2025-08-25 07:12:14', '2025-08-25 07:12:19'),
(2, 25, 3, 2500003, '2500003707', 1, 1, NULL, 1, 20.000, 20.000, 20.000, 0.000, '2025-08-25 07:13:37', '2025-08-25 07:13:33', '2025-08-25 07:13:37');

-- --------------------------------------------------------

--
-- Table structure for table `stock_count_products`
--

CREATE TABLE `stock_count_products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_count_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `uom_id` bigint(20) UNSIGNED DEFAULT NULL,
  `current_stock` decimal(18,3) NOT NULL DEFAULT '0.000',
  `inventoried_stock` decimal(18,3) NOT NULL DEFAULT '0.000',
  `variance` decimal(18,3) DEFAULT NULL,
  `scanned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_movement`
--

CREATE TABLE `stock_movement` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` double DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `qty` double(8,2) DEFAULT NULL,
  `uom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `purchase_order` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` text COLLATE utf8mb4_unicode_ci,
  `parent_id` int(11) DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_bill`
--

CREATE TABLE `temp_bill` (
  `id` int(191) NOT NULL,
  `currency` int(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `temp_bill`
--

INSERT INTO `temp_bill` (`id`, `currency`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE `test` (
  `id` int(255) NOT NULL,
  `text` text,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`id`, `text`, `body`) VALUES
(1, '7', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test1`
--

CREATE TABLE `test1` (
  `id` int(11) NOT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test1`
--

INSERT INTO `test1` (`id`, `body`) VALUES
(1, '0');

-- --------------------------------------------------------

--
-- Table structure for table `test2`
--

CREATE TABLE `test2` (
  `id` int(11) NOT NULL,
  `text` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test2`
--

INSERT INTO `test2` (`id`, `text`, `created_at`, `updated_at`) VALUES
(1, '[{\"seller_payment_id\":\"2\",\"number\":\"SP-100033\",\"order_amount\":\"690\",\"total_amount\":\"82.8\",\"amount_pending\":\"62.8\",\"client_id\":\"1\",\"amount_received\":\"10\",\"sales_order_id\":\"1\",\"sales_order_number\":\"1000094\",\"payment_date\":\"2023-10-03\"},{\"seller_payment_id\":\"3\",\"number\":\"SP-100034\",\"order_amount\":\"690\",\"total_amount\":\"82.8\",\"amount_pending\":\"37.8\",\"client_id\":\"3\",\"amount_received\":\"20\",\"sales_order_id\":\"2\",\"sales_order_number\":\"1000095\",\"payment_date\":\"2023-10-04\"},{\"seller_payment_id\":\"4\",\"number\":\"SP-100035\",\"order_amount\":\"690\",\"total_amount\":\"82.8\",\"amount_pending\":\"22.8\",\"client_id\":\"2\",\"amount_received\":\"30\",\"sales_order_id\":\"3\",\"sales_order_number\":\"1000096\",\"payment_date\":\"2023-10-07\"}]', NULL, NULL),
(2, '[{\"seller_payment_id\":\"2\",\"number\":\"SP-100033\",\"order_amount\":\"690\",\"total_amount\":\"82.8\",\"amount_pending\":\"62.8\",\"client_id\":\"1\",\"amount_received\":\"10\",\"sales_order_id\":\"1\",\"sales_order_number\":\"1000094\",\"payment_date\":\"2023-10-03\"},{\"seller_payment_id\":\"3\",\"number\":\"SP-100034\",\"order_amount\":\"690\",\"total_amount\":\"82.8\",\"amount_pending\":\"37.8\",\"client_id\":\"3\",\"amount_received\":\"20\",\"sales_order_id\":\"2\",\"sales_order_number\":\"1000095\",\"payment_date\":\"2023-10-04\"},{\"seller_payment_id\":\"4\",\"number\":\"SP-100035\",\"order_amount\":\"690\",\"total_amount\":\"82.8\",\"amount_pending\":\"22.8\",\"client_id\":\"2\",\"amount_received\":\"30\",\"sales_order_id\":\"3\",\"sales_order_number\":\"1000096\",\"payment_date\":\"2023-10-07\"}]', NULL, NULL),
(3, '1000', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test4`
--

CREATE TABLE `test4` (
  `id` int(11) NOT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `test4`
--

INSERT INTO `test4` (`id`, `body`) VALUES
(1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `test5`
--

CREATE TABLE `test5` (
  `id` int(255) NOT NULL,
  `body` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test5`
--

INSERT INTO `test5` (`id`, `body`) VALUES
(1, NULL),
(2, '{\"description\":null,\"debit\":\"0\",\"debit_vat\":\"0\",\"date\":\"2025-08-13\"}');

-- --------------------------------------------------------

--
-- Table structure for table `test6`
--

CREATE TABLE `test6` (
  `id` int(11) NOT NULL,
  `body` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `test6`
--

INSERT INTO `test6` (`id`, `body`) VALUES
(1, 'x');

-- --------------------------------------------------------

--
-- Table structure for table `third_parties_extras`
--

CREATE TABLE `third_parties_extras` (
  `id` int(11) NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `currency_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `third_parties_extras`
--

INSERT INTO `third_parties_extras` (`id`, `number`, `name`, `description`, `created_at`, `updated_at`, `user_id`, `created_by`, `currency_id`) VALUES
(1, 'TPE-25000000', 'Custom Fees', 'debit', '2025-08-08 03:11:48', '2025-08-08 09:32:04', 1, 'Charbel El Kabbouchi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `transfers`
--

CREATE TABLE `transfers` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `item_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `warehouse_id` int(11) DEFAULT NULL,
  `to_warehouse_id` int(11) DEFAULT NULL,
  `qty_on_hand` int(11) DEFAULT NULL,
  `uom_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `vendor_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transfer_accounts`
--

CREATE TABLE `transfer_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_account_id` int(11) DEFAULT NULL,
  `to_account_id` int(11) DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `exchangerate` double DEFAULT NULL,
  `transfer_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uom`
--

CREATE TABLE `uom` (
  `id` int(10) UNSIGNED NOT NULL,
  `unit` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `uom`
--

INSERT INTO `uom` (`id`, `unit`, `user_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'PC', 1, 'Charbel El Kabbouchi', '2021-03-07 10:37:51', '2023-04-14 03:26:17');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `manager_id` int(191) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telephone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extension` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `is_settings_tab` tinyint(1) DEFAULT NULL,
  `is_procurment_tab` tinyint(1) DEFAULT NULL,
  `is_sales_tab` tinyint(1) DEFAULT NULL,
  `is_accounting_tab` tinyint(1) DEFAULT NULL,
  `is_company_tab` tinyint(1) DEFAULT NULL,
  `is_dashboard` tinyint(1) DEFAULT NULL,
  `is_deliverycondition_tab` tinyint(1) DEFAULT NULL,
  `is_paymentcondition_tab` tinyint(1) DEFAULT NULL,
  `is_exchangerate_tab` tinyint(1) DEFAULT NULL,
  `is_uom_tab` tinyint(1) DEFAULT NULL,
  `is_counters_tab` tinyint(1) DEFAULT NULL,
  `is_currencies_tab` tinyint(1) DEFAULT NULL,
  `is_warehouses_tab` tinyint(1) DEFAULT NULL,
  `is_categories_tab` tinyint(1) DEFAULT NULL,
  `is_subcategories_tab` tinyint(1) DEFAULT NULL,
  `is_deliverycondition_create` tinyint(1) DEFAULT NULL,
  `is_deliverycondition_edit` tinyint(1) DEFAULT NULL,
  `is_deliverycondition_delete` tinyint(1) DEFAULT NULL,
  `is_deliverycondition_view` tinyint(1) DEFAULT NULL,
  `is_paymentcondition_create` tinyint(1) DEFAULT NULL,
  `is_paymentcondition_edit` tinyint(1) DEFAULT NULL,
  `is_paymentcondition_delete` tinyint(1) DEFAULT NULL,
  `is_paymentcondition_view` tinyint(1) DEFAULT NULL,
  `is_exchangerate_create` tinyint(1) DEFAULT NULL,
  `is_exchangerate_edit` tinyint(1) DEFAULT NULL,
  `is_exchangerate_delete` tinyint(1) DEFAULT NULL,
  `is_exchangerate_view` tinyint(1) DEFAULT NULL,
  `is_uom_create` tinyint(1) DEFAULT NULL,
  `is_uom_edit` tinyint(1) DEFAULT NULL,
  `is_uom_delete` tinyint(1) DEFAULT NULL,
  `is_uom_view` tinyint(1) DEFAULT NULL,
  `is_counters_create` tinyint(1) DEFAULT NULL,
  `is_counters_edit` tinyint(1) DEFAULT NULL,
  `is_counters_delete` tinyint(1) DEFAULT NULL,
  `is_counters_view` tinyint(1) DEFAULT NULL,
  `is_currencies_create` tinyint(1) DEFAULT NULL,
  `is_currencies_edit` tinyint(1) DEFAULT NULL,
  `is_currencies_delete` tinyint(1) DEFAULT NULL,
  `is_currencies_view` tinyint(1) DEFAULT NULL,
  `is_warehouses_create` tinyint(1) DEFAULT NULL,
  `is_warehouses_edit` tinyint(1) DEFAULT NULL,
  `is_warehouses_delete` tinyint(1) DEFAULT NULL,
  `is_warehouses_view` tinyint(1) DEFAULT NULL,
  `is_categories_create` tinyint(1) DEFAULT NULL,
  `is_categories_edit` tinyint(1) DEFAULT NULL,
  `is_categories_delete` tinyint(1) DEFAULT NULL,
  `is_categories_view` tinyint(1) DEFAULT NULL,
  `is_subcategories_create` tinyint(1) DEFAULT NULL,
  `is_subcategories_edit` tinyint(1) DEFAULT NULL,
  `is_subcategories_delete` tinyint(1) DEFAULT NULL,
  `is_subcategories_view` tinyint(1) DEFAULT NULL,
  `is_accounts_tab` tinyint(1) DEFAULT NULL,
  `is_transferaccounts_tab` tinyint(1) DEFAULT NULL,
  `is_deposit_tab` tinyint(1) DEFAULT NULL,
  `is_returndeposit_tab` tinyint(1) DEFAULT NULL,
  `is_employees_tab` tinyint(1) DEFAULT NULL,
  `is_payroll_tab` tinyint(1) DEFAULT NULL,
  `is_accounts_create` tinyint(1) DEFAULT NULL,
  `is_accounts_edit` tinyint(1) DEFAULT NULL,
  `is_accounts_delete` tinyint(1) DEFAULT NULL,
  `is_accounts_view` tinyint(1) DEFAULT NULL,
  `is_transferaccounts_create` tinyint(1) DEFAULT NULL,
  `is_transferaccounts_edit` tinyint(1) DEFAULT NULL,
  `is_transferaccounts_delete` tinyint(1) DEFAULT NULL,
  `is_transferaccounts_view` tinyint(1) DEFAULT NULL,
  `is_deposit_create` tinyint(1) DEFAULT NULL,
  `is_deposit_edit` tinyint(1) DEFAULT NULL,
  `is_deposit_delete` tinyint(1) DEFAULT NULL,
  `is_deposit_view` tinyint(1) DEFAULT NULL,
  `is_returndeposit_create` tinyint(1) DEFAULT NULL,
  `is_returndeposit_edit` tinyint(1) DEFAULT NULL,
  `is_returndeposit_delete` tinyint(1) DEFAULT NULL,
  `is_returndeposit_view` tinyint(1) DEFAULT NULL,
  `is_employees_create` tinyint(1) DEFAULT NULL,
  `is_employees_edit` tinyint(1) DEFAULT NULL,
  `is_employees_delete` tinyint(1) DEFAULT NULL,
  `is_employees_view` tinyint(1) DEFAULT NULL,
  `is_payroll_create` tinyint(1) DEFAULT NULL,
  `is_payroll_edit` tinyint(1) DEFAULT NULL,
  `is_payroll_delete` tinyint(1) DEFAULT NULL,
  `is_payroll_view` tinyint(1) DEFAULT NULL,
  `is_clients_tab` tinyint(1) DEFAULT NULL,
  `is_quotations_tab` tinyint(1) DEFAULT NULL,
  `is_salesorders_tab` tinyint(1) DEFAULT NULL,
  `is_clients_create` tinyint(1) DEFAULT NULL,
  `is_clients_edit` tinyint(1) DEFAULT NULL,
  `is_clients_delete` tinyint(1) DEFAULT NULL,
  `is_clients_view` tinyint(1) DEFAULT NULL,
  `is_quotations_create` tinyint(1) DEFAULT NULL,
  `is_quotations_edit` tinyint(1) DEFAULT NULL,
  `is_quotations_delete` tinyint(1) DEFAULT NULL,
  `is_quotations_view` tinyint(1) DEFAULT NULL,
  `is_salesorders_create` tinyint(1) DEFAULT NULL,
  `is_salesorders_edit` tinyint(1) DEFAULT NULL,
  `is_salesorders_delete` tinyint(1) DEFAULT NULL,
  `is_salesorders_view` tinyint(1) DEFAULT NULL,
  `is_advancepayments_tab` tinyint(1) DEFAULT NULL,
  `is_invoices_tab` tinyint(1) DEFAULT NULL,
  `is_creditnotes_tab` tinyint(1) DEFAULT NULL,
  `is_debitnotes_tab` tinyint(1) DEFAULT NULL,
  `is_clientpayments_tab` tinyint(1) DEFAULT NULL,
  `is_clientsoa_tab` tinyint(1) DEFAULT NULL,
  `is_vendorexpenses_tab` tinyint(1) DEFAULT NULL,
  `is_bills_tab` tinyint(1) DEFAULT NULL,
  `is_vendorpayments_tab` tinyint(1) DEFAULT NULL,
  `is_vendorsoa_tab` tinyint(1) DEFAULT NULL,
  `is_advancepayments_create` tinyint(1) DEFAULT NULL,
  `is_advancepayments_edit` tinyint(1) DEFAULT NULL,
  `is_advancepayments_delete` tinyint(1) DEFAULT NULL,
  `is_advancepayments_view` tinyint(1) DEFAULT NULL,
  `is_invoices_create` tinyint(1) DEFAULT NULL,
  `is_invoices_edit` tinyint(1) DEFAULT NULL,
  `is_invoices_delete` tinyint(1) DEFAULT NULL,
  `is_invoices_view` tinyint(1) DEFAULT NULL,
  `is_creditnotes_create` tinyint(1) DEFAULT NULL,
  `is_creditnotes_edit` tinyint(1) DEFAULT NULL,
  `is_creditnotes_delete` tinyint(1) DEFAULT NULL,
  `is_creditnotes_view` tinyint(1) DEFAULT NULL,
  `is_debitnotes_create` tinyint(1) DEFAULT NULL,
  `is_debitnotes_edit` tinyint(1) DEFAULT NULL,
  `is_debitnotes_delete` tinyint(1) DEFAULT NULL,
  `is_debitnotes_view` tinyint(1) DEFAULT NULL,
  `is_clientpayments_create` tinyint(1) DEFAULT NULL,
  `is_clientpayments_edit` tinyint(1) DEFAULT NULL,
  `is_clientpayments_delete` tinyint(1) DEFAULT NULL,
  `is_clientpayments_view` tinyint(1) DEFAULT NULL,
  `is_clientsoa_create` tinyint(1) DEFAULT NULL,
  `is_clientsoa_edit` tinyint(1) DEFAULT NULL,
  `is_clientsoa_delete` tinyint(1) DEFAULT NULL,
  `is_clientsoa_view` tinyint(1) DEFAULT NULL,
  `is_vendorexpenses_create` tinyint(1) DEFAULT NULL,
  `is_vendorexpenses_edit` tinyint(1) DEFAULT NULL,
  `is_vendorexpenses_delete` tinyint(1) DEFAULT NULL,
  `is_vendorexpenses_view` tinyint(1) DEFAULT NULL,
  `is_bills_create` tinyint(1) DEFAULT NULL,
  `is_bills_edit` tinyint(1) DEFAULT NULL,
  `is_bills_delete` tinyint(1) DEFAULT NULL,
  `is_bills_view` tinyint(1) DEFAULT NULL,
  `is_vendorpayments_create` tinyint(1) DEFAULT NULL,
  `is_vendorpayments_edit` tinyint(1) DEFAULT NULL,
  `is_vendorpayments_delete` tinyint(1) DEFAULT NULL,
  `is_vendorpayments_view` tinyint(1) DEFAULT NULL,
  `is_vendorsoa_create` tinyint(1) DEFAULT NULL,
  `is_vendorsoa_edit` tinyint(1) DEFAULT NULL,
  `is_vendorsoa_delete` tinyint(1) DEFAULT NULL,
  `is_vendorsoa_view` tinyint(1) DEFAULT NULL,
  `is_products_tab` tinyint(1) DEFAULT NULL,
  `is_receiveorders_tab` tinyint(1) DEFAULT NULL,
  `is_vendors_tab` tinyint(1) DEFAULT NULL,
  `is_purchaseorders_tab` tinyint(1) DEFAULT NULL,
  `is_transfers_tab` tinyint(1) DEFAULT NULL,
  `is_productsdivision_tab` tinyint(1) DEFAULT NULL,
  `is_productsaggregation_tab` tinyint(1) DEFAULT NULL,
  `is_products_create` tinyint(1) DEFAULT NULL,
  `is_products_edit` tinyint(1) DEFAULT NULL,
  `is_products_delete` tinyint(1) DEFAULT NULL,
  `is_products_view` tinyint(1) DEFAULT NULL,
  `is_receiveorders_create` tinyint(1) DEFAULT NULL,
  `is_receiveorders_edit` tinyint(1) DEFAULT NULL,
  `is_receiveorders_delete` tinyint(1) DEFAULT NULL,
  `is_receiveorders_view` tinyint(1) DEFAULT NULL,
  `is_vendors_create` tinyint(1) DEFAULT NULL,
  `is_vendors_edit` tinyint(1) DEFAULT NULL,
  `is_vendors_delete` tinyint(1) DEFAULT NULL,
  `is_vendors_view` tinyint(1) DEFAULT NULL,
  `is_purchaseorders_create` tinyint(1) DEFAULT NULL,
  `is_purchaseorders_edit` tinyint(1) DEFAULT NULL,
  `is_purchaseorders_delete` tinyint(1) DEFAULT NULL,
  `is_purchaseorders_view` tinyint(1) DEFAULT NULL,
  `is_transfers_create` tinyint(1) DEFAULT NULL,
  `is_transfers_edit` tinyint(1) DEFAULT NULL,
  `is_transfers_delete` tinyint(1) DEFAULT NULL,
  `is_transfers_view` tinyint(1) DEFAULT NULL,
  `is_productsdivision_create` tinyint(1) DEFAULT NULL,
  `is_productsdivision_edit` tinyint(1) DEFAULT NULL,
  `is_productsdivision_delete` tinyint(1) DEFAULT NULL,
  `is_productsdivision_view` tinyint(1) DEFAULT NULL,
  `is_productsaggregation_create` tinyint(1) DEFAULT NULL,
  `is_productsaggregation_edit` tinyint(1) DEFAULT NULL,
  `is_productsaggregation_delete` tinyint(1) DEFAULT NULL,
  `is_productsaggregation_view` tinyint(1) DEFAULT NULL,
  `is_displayoverview_tab` tinyint(1) DEFAULT NULL,
  `is_displaysales_tab` tinyint(1) DEFAULT NULL,
  `is_displayaccounting_tab` tinyint(1) DEFAULT NULL,
  `is_displaystock_tab` tinyint(1) DEFAULT NULL,
  `is_displayproduction_tab` int(191) NOT NULL DEFAULT '0',
  `is_stockmovement_tab` int(255) NOT NULL DEFAULT '0',
  `is_stockmovement_view` int(191) NOT NULL DEFAULT '0',
  `is_production_tab` int(191) NOT NULL DEFAULT '0',
  `email_signature` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_shipping_tab` int(255) DEFAULT NULL,
  `is_Define_Shippers_tab` int(255) DEFAULT NULL,
  `is_Define_Shippers_create` int(255) DEFAULT NULL,
  `is_Define_Shippers_edit` int(255) DEFAULT NULL,
  `is_Define_Shippers_delete` int(255) DEFAULT NULL,
  `is_Define_Shippers_view` int(255) DEFAULT NULL,
  `is_Shipments_tab` int(255) DEFAULT NULL,
  `is_Shipments_create` int(255) DEFAULT NULL,
  `is_Shipments_edit` int(255) DEFAULT NULL,
  `is_Shipments_delete` int(255) DEFAULT NULL,
  `is_Shipments_view` int(255) DEFAULT NULL,
  `is_Receive_Shipments_tab` int(255) DEFAULT NULL,
  `is_Receive_Shipments_create` int(255) DEFAULT NULL,
  `is_Receive_Shipments_edit` int(255) DEFAULT NULL,
  `is_Receive_Shipments_delete` int(255) DEFAULT NULL,
  `is_Receive_Shipments_view` int(255) DEFAULT NULL,
  `is_Shippers_Bills_tab` int(255) DEFAULT NULL,
  `is_Shippers_Bills_create` int(255) DEFAULT NULL,
  `is_Shippers_Bills_edit` int(255) DEFAULT NULL,
  `is_Shippers_Bills_delete` int(255) DEFAULT NULL,
  `is_Shippers_Bills_view` int(255) DEFAULT NULL,
  `is_Shippers_Payments_tab` int(255) DEFAULT NULL,
  `is_Shippers_Payments_edit` int(255) DEFAULT NULL,
  `is_Shippers_Payments_delete` int(255) DEFAULT NULL,
  `is_Shippers_Payments_view` int(255) DEFAULT NULL,
  `is_Shippers_SOA_tab` int(255) DEFAULT NULL,
  `is_Shippers_SOA_create` int(255) DEFAULT NULL,
  `is_Shippers_SOA_edit` int(255) DEFAULT NULL,
  `is_Shippers_SOA_delete` int(255) DEFAULT NULL,
  `is_Shippers_SOA_view` int(255) DEFAULT NULL,
  `is_Shippers_Payments_create` int(255) DEFAULT NULL,
  `is_journalvoucher_tab` int(255) NOT NULL DEFAULT '0',
  `is_journalvoucher_create` int(255) NOT NULL DEFAULT '0',
  `is_journalvoucher_edit` int(255) NOT NULL DEFAULT '0',
  `is_journalvoucher_delete` int(255) NOT NULL DEFAULT '0',
  `is_journalvoucher_view` int(255) NOT NULL DEFAULT '0',
  `is_trialbalance_tab` int(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `manager_id`, `name`, `title`, `telephone`, `extension`, `mobile_number`, `email`, `password`, `is_admin`, `is_active`, `is_settings_tab`, `is_procurment_tab`, `is_sales_tab`, `is_accounting_tab`, `is_company_tab`, `is_dashboard`, `is_deliverycondition_tab`, `is_paymentcondition_tab`, `is_exchangerate_tab`, `is_uom_tab`, `is_counters_tab`, `is_currencies_tab`, `is_warehouses_tab`, `is_categories_tab`, `is_subcategories_tab`, `is_deliverycondition_create`, `is_deliverycondition_edit`, `is_deliverycondition_delete`, `is_deliverycondition_view`, `is_paymentcondition_create`, `is_paymentcondition_edit`, `is_paymentcondition_delete`, `is_paymentcondition_view`, `is_exchangerate_create`, `is_exchangerate_edit`, `is_exchangerate_delete`, `is_exchangerate_view`, `is_uom_create`, `is_uom_edit`, `is_uom_delete`, `is_uom_view`, `is_counters_create`, `is_counters_edit`, `is_counters_delete`, `is_counters_view`, `is_currencies_create`, `is_currencies_edit`, `is_currencies_delete`, `is_currencies_view`, `is_warehouses_create`, `is_warehouses_edit`, `is_warehouses_delete`, `is_warehouses_view`, `is_categories_create`, `is_categories_edit`, `is_categories_delete`, `is_categories_view`, `is_subcategories_create`, `is_subcategories_edit`, `is_subcategories_delete`, `is_subcategories_view`, `is_accounts_tab`, `is_transferaccounts_tab`, `is_deposit_tab`, `is_returndeposit_tab`, `is_employees_tab`, `is_payroll_tab`, `is_accounts_create`, `is_accounts_edit`, `is_accounts_delete`, `is_accounts_view`, `is_transferaccounts_create`, `is_transferaccounts_edit`, `is_transferaccounts_delete`, `is_transferaccounts_view`, `is_deposit_create`, `is_deposit_edit`, `is_deposit_delete`, `is_deposit_view`, `is_returndeposit_create`, `is_returndeposit_edit`, `is_returndeposit_delete`, `is_returndeposit_view`, `is_employees_create`, `is_employees_edit`, `is_employees_delete`, `is_employees_view`, `is_payroll_create`, `is_payroll_edit`, `is_payroll_delete`, `is_payroll_view`, `is_clients_tab`, `is_quotations_tab`, `is_salesorders_tab`, `is_clients_create`, `is_clients_edit`, `is_clients_delete`, `is_clients_view`, `is_quotations_create`, `is_quotations_edit`, `is_quotations_delete`, `is_quotations_view`, `is_salesorders_create`, `is_salesorders_edit`, `is_salesorders_delete`, `is_salesorders_view`, `is_advancepayments_tab`, `is_invoices_tab`, `is_creditnotes_tab`, `is_debitnotes_tab`, `is_clientpayments_tab`, `is_clientsoa_tab`, `is_vendorexpenses_tab`, `is_bills_tab`, `is_vendorpayments_tab`, `is_vendorsoa_tab`, `is_advancepayments_create`, `is_advancepayments_edit`, `is_advancepayments_delete`, `is_advancepayments_view`, `is_invoices_create`, `is_invoices_edit`, `is_invoices_delete`, `is_invoices_view`, `is_creditnotes_create`, `is_creditnotes_edit`, `is_creditnotes_delete`, `is_creditnotes_view`, `is_debitnotes_create`, `is_debitnotes_edit`, `is_debitnotes_delete`, `is_debitnotes_view`, `is_clientpayments_create`, `is_clientpayments_edit`, `is_clientpayments_delete`, `is_clientpayments_view`, `is_clientsoa_create`, `is_clientsoa_edit`, `is_clientsoa_delete`, `is_clientsoa_view`, `is_vendorexpenses_create`, `is_vendorexpenses_edit`, `is_vendorexpenses_delete`, `is_vendorexpenses_view`, `is_bills_create`, `is_bills_edit`, `is_bills_delete`, `is_bills_view`, `is_vendorpayments_create`, `is_vendorpayments_edit`, `is_vendorpayments_delete`, `is_vendorpayments_view`, `is_vendorsoa_create`, `is_vendorsoa_edit`, `is_vendorsoa_delete`, `is_vendorsoa_view`, `is_products_tab`, `is_receiveorders_tab`, `is_vendors_tab`, `is_purchaseorders_tab`, `is_transfers_tab`, `is_productsdivision_tab`, `is_productsaggregation_tab`, `is_products_create`, `is_products_edit`, `is_products_delete`, `is_products_view`, `is_receiveorders_create`, `is_receiveorders_edit`, `is_receiveorders_delete`, `is_receiveorders_view`, `is_vendors_create`, `is_vendors_edit`, `is_vendors_delete`, `is_vendors_view`, `is_purchaseorders_create`, `is_purchaseorders_edit`, `is_purchaseorders_delete`, `is_purchaseorders_view`, `is_transfers_create`, `is_transfers_edit`, `is_transfers_delete`, `is_transfers_view`, `is_productsdivision_create`, `is_productsdivision_edit`, `is_productsdivision_delete`, `is_productsdivision_view`, `is_productsaggregation_create`, `is_productsaggregation_edit`, `is_productsaggregation_delete`, `is_productsaggregation_view`, `is_displayoverview_tab`, `is_displaysales_tab`, `is_displayaccounting_tab`, `is_displaystock_tab`, `is_displayproduction_tab`, `is_stockmovement_tab`, `is_stockmovement_view`, `is_production_tab`, `email_signature`, `remember_token`, `created_at`, `updated_at`, `is_shipping_tab`, `is_Define_Shippers_tab`, `is_Define_Shippers_create`, `is_Define_Shippers_edit`, `is_Define_Shippers_delete`, `is_Define_Shippers_view`, `is_Shipments_tab`, `is_Shipments_create`, `is_Shipments_edit`, `is_Shipments_delete`, `is_Shipments_view`, `is_Receive_Shipments_tab`, `is_Receive_Shipments_create`, `is_Receive_Shipments_edit`, `is_Receive_Shipments_delete`, `is_Receive_Shipments_view`, `is_Shippers_Bills_tab`, `is_Shippers_Bills_create`, `is_Shippers_Bills_edit`, `is_Shippers_Bills_delete`, `is_Shippers_Bills_view`, `is_Shippers_Payments_tab`, `is_Shippers_Payments_edit`, `is_Shippers_Payments_delete`, `is_Shippers_Payments_view`, `is_Shippers_SOA_tab`, `is_Shippers_SOA_create`, `is_Shippers_SOA_edit`, `is_Shippers_SOA_delete`, `is_Shippers_SOA_view`, `is_Shippers_Payments_create`, `is_journalvoucher_tab`, `is_journalvoucher_create`, `is_journalvoucher_edit`, `is_journalvoucher_delete`, `is_journalvoucher_view`, `is_trialbalance_tab`) VALUES
(1, 1, 'Charbel El Kabbouchi', 'Administrator', '+000 00 123 456', '100', '+000 00 123 456', 'charbelkabbouchi@gmail.com', '$2y$10$ekbfuge9xKneECym3FE0yOwR1GF19gb/.El.sDgm3ChsrCNpzTsxa', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 1, 1, 1, 'Best Regards', 'yD5KQIDBiFGzaxsklyAWjEEptCNeoGn7Uxi9SI7DQuomhuRdmwXggHX6EGrc', '2021-08-08 13:09:40', '2025-08-04 06:58:03', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(11, NULL, 'Mohammad', 'test', NULL, 'test@test.com', NULL, 'test@test.com', '$2y$10$r7DVZ8AKeKKKEndGZXk/POZkgivdWYNrP.eFAvjm6/MGXc5SylMIe', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 'Best Regards', NULL, '2025-08-24 20:31:05', '2025-08-25 16:43:50', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_dashboards`
--

CREATE TABLE `user_dashboards` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `widgets` text COLLATE utf8mb4_unicode_ci,
  `layout` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_dashboards`
--

INSERT INTO `user_dashboards` (`id`, `user_id`, `name`, `widgets`, `layout`, `created_at`, `updated_at`) VALUES
(7, 1, 'MyDashboard', '[{\"lib_id\":\"1\",\"x\":0,\"y\":0,\"w\":12,\"h\":3},{\"lib_id\":\"3\",\"x\":0,\"y\":3,\"w\":4,\"h\":3},{\"lib_id\":\"2\",\"x\":4,\"y\":3,\"w\":8,\"h\":3}]', NULL, '2025-08-24 22:31:11', '2025-08-24 22:31:50');

-- --------------------------------------------------------

--
-- Table structure for table `vat_accounts`
--

CREATE TABLE `vat_accounts` (
  `id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vat_accounts`
--

INSERT INTO `vat_accounts` (`id`, `currency_id`, `name`, `name_ar`, `code`, `created_at`, `updated_at`) VALUES
(2, 2, 'ضريبة مسددة على شراء السلع', 'ضريبة مسددة على شراء السلع', 'VA-25000000', '2025-08-11 19:28:38', '2025-08-11 19:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `vat_rate`
--

CREATE TABLE `vat_rate` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `currency1` int(11) DEFAULT NULL,
  `currency2` int(11) DEFAULT NULL,
  `value1` int(11) DEFAULT NULL,
  `value2` double DEFAULT NULL,
  `created_by` text COLLATE utf8mb4_unicode_ci,
  `exchangedate` date DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vat_rate`
--

INSERT INTO `vat_rate` (`id`, `user_id`, `currency1`, `currency2`, `value1`, `value2`, `created_by`, `exchangedate`, `note`, `created_at`, `updated_at`) VALUES
(6, 1, 1, 2, 1, 0.38, 'Charbel El Kabbouchi', '2025-07-31', NULL, '2025-07-30 21:32:48', '2025-07-30 21:32:48');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `account_id` int(255) DEFAULT NULL,
  `account_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_ar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `person` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` float DEFAULT NULL,
  `work_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vat_status` int(11) DEFAULT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `billing_address` text COLLATE utf8mb4_unicode_ci,
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `payment_details` text COLLATE utf8mb4_unicode_ci,
  `total_expense` double NOT NULL DEFAULT '0',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_process` int(11) NOT NULL DEFAULT '0',
  `balance` decimal(18,3) DEFAULT NULL,
  `paid` decimal(18,3) DEFAULT NULL,
  `last_payment_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `account_id`, `account_code`, `name_ar`, `person`, `company`, `email`, `discount`, `work_phone`, `mobile_number`, `vat_number`, `vat_status`, `currency_id`, `billing_address`, `shipping_address`, `payment_details`, `total_expense`, `created_by`, `created_at`, `updated_at`, `shipping_process`, `balance`, `paid`, `last_payment_date`) VALUES
(1, 1, 5, '2900000', 'شسيشسي', 'test', 'company1', NULL, NULL, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 0, 'Charbel El Kabbouchi', '2025-08-22 19:15:05', '2025-08-24 22:05:20', 0, 0.000, 0.000, '2025-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bills_report`
--

CREATE TABLE `vendor_bills_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_bills_report_items`
--

CREATE TABLE `vendor_bills_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `purchase_order_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `amount_paid` double DEFAULT NULL,
  `status_id` int(11) DEFAULT NULL,
  `total` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `bill_date` timestamp NULL DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments`
--

CREATE TABLE `vendor_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vendor_id` int(10) UNSIGNED NOT NULL,
  `currency_id` int(10) UNSIGNED NOT NULL,
  `amount_paid` decimal(8,2) DEFAULT NULL,
  `amount_paid_usd` decimal(8,2) DEFAULT NULL,
  `amount_paid_lbp` double DEFAULT NULL,
  `amount_paid_lbprate` decimal(8,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_option_id` int(255) DEFAULT NULL,
  `payment_mode` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `document` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_id` int(11) NOT NULL DEFAULT '1',
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `posted` int(255) DEFAULT NULL,
  `journal_id` int(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments_log`
--

CREATE TABLE `vendor_payments_log` (
  `id` int(11) NOT NULL,
  `comment` text,
  `body` text,
  `items` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments_report`
--

CREATE TABLE `vendor_payments_report` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `amount_applied_lbp` double DEFAULT NULL,
  `exchangerate` double DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payments_report_items`
--

CREATE TABLE `vendor_payments_report_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `vendor_payment_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `amount_applied` double DEFAULT NULL,
  `amount_applied_lbp` double DEFAULT NULL,
  `amount_applied_lbp_rate` double DEFAULT NULL,
  `amount_applied_vat` double DEFAULT NULL,
  `amount_applied_vat_rate` double DEFAULT NULL,
  `payment_mode` text COLLATE utf8mb4_unicode_ci,
  `payment_date` timestamp NULL DEFAULT NULL,
  `from_date` timestamp NULL DEFAULT NULL,
  `to_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_payment_items`
--

CREATE TABLE `vendor_payment_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_payment_id` int(10) UNSIGNED NOT NULL,
  `bill_id` int(10) UNSIGNED NOT NULL,
  `amount_applied` decimal(8,2) DEFAULT '0.00',
  `amount_applied_lbp` decimal(8,2) DEFAULT '0.00',
  `amount_applied_lbp_rate` decimal(8,2) DEFAULT '1.00',
  `amount_applied_vat` decimal(8,2) DEFAULT '0.00',
  `amount_applied_vat_rate` decimal(8,2) DEFAULT '1.00',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `vat_paid` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_statement`
--

CREATE TABLE `vendor_statement` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `person` text COLLATE utf8mb4_unicode_ci,
  `company` text COLLATE utf8mb4_unicode_ci,
  `date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_by` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_statement_items`
--

CREATE TABLE `vendor_statement_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `vendor_id` int(11) DEFAULT NULL,
  `statement_id` int(11) NOT NULL,
  `reference_number` text COLLATE utf8mb4_unicode_ci,
  `reference_id` int(11) DEFAULT NULL,
  `amount_applied` decimal(18,3) DEFAULT NULL,
  `amount_applied_vat` decimal(18,3) DEFAULT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `reference_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `number` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `user_id`, `name`, `number`, `description`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Finished Product Warehouse', 'WH-1000000', 'Finished Product Warehouse', 'Charbel El Kabbouchi', '2022-05-19 04:27:39', '2022-08-17 05:15:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_items`
--
ALTER TABLE `account_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Indexes for table `advance_payments`
--
ALTER TABLE `advance_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `advance_payments_number_unique` (`number`);

--
-- Indexes for table `advance_payment_items`
--
ALTER TABLE `advance_payment_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advance_payment_report`
--
ALTER TABLE `advance_payment_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `advance_payment_report_items`
--
ALTER TABLE `advance_payment_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bills_number_unique` (`number`);

--
-- Indexes for table `bills_log`
--
ALTER TABLE `bills_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_items_log`
--
ALTER TABLE `bill_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_item_taxes`
--
ALTER TABLE `bill_item_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache_log`
--
ALTER TABLE `cache_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_log`
--
ALTER TABLE `cart_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chart_accounts`
--
ALTER TABLE `chart_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_code` (`class_code`);

--
-- Indexes for table `chart_classes`
--
ALTER TABLE `chart_classes`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients__`
--
ALTER TABLE `clients__`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_balance_report`
--
ALTER TABLE `client_balance_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_balance_report_items`
--
ALTER TABLE `client_balance_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_balance_schedule`
--
ALTER TABLE `client_balance_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_dropdown1`
--
ALTER TABLE `client_dropdown1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_dropdown2`
--
ALTER TABLE `client_dropdown2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_payments`
--
ALTER TABLE `client_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `client_payments_number_unique` (`number`);

--
-- Indexes for table `client_payments_log`
--
ALTER TABLE `client_payments_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_payment_items`
--
ALTER TABLE `client_payment_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_payment_report`
--
ALTER TABLE `client_payment_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_payment_report_items`
--
ALTER TABLE `client_payment_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `container_orders`
--
ALTER TABLE `container_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `container_orders_number_unique` (`number`);

--
-- Indexes for table `container_order_items`
--
ALTER TABLE `container_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `container_order_item_products`
--
ALTER TABLE `container_order_item_products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `container_order_report`
--
ALTER TABLE `container_order_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `container_order_report_items`
--
ALTER TABLE `container_order_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `container_receive_orders`
--
ALTER TABLE `container_receive_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `container_receive_orders_number_unique` (`number`);

--
-- Indexes for table `container_receive_order_items`
--
ALTER TABLE `container_receive_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `counters`
--
ALTER TABLE `counters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `counters_key_unique` (`key`);

--
-- Indexes for table `credit_notes`
--
ALTER TABLE `credit_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `credit_notes_number_unique` (`number`);

--
-- Indexes for table `credit_notes_items`
--
ALTER TABLE `credit_notes_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_note_report`
--
ALTER TABLE `credit_note_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_note_report_items`
--
ALTER TABLE `credit_note_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currencies_code_unique` (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_returns`
--
ALTER TABLE `customer_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_returns_number_unique` (`number`);

--
-- Indexes for table `customer_returns_report`
--
ALTER TABLE `customer_returns_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_returns_report_items`
--
ALTER TABLE `customer_returns_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_return_items`
--
ALTER TABLE `customer_return_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `damaged`
--
ALTER TABLE `damaged`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `damaged_items`
--
ALTER TABLE `damaged_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dashboard_views`
--
ALTER TABLE `dashboard_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debit_notes`
--
ALTER TABLE `debit_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `debit_notes_number_unique` (`number`);

--
-- Indexes for table `debit_notes_items`
--
ALTER TABLE `debit_notes_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debit_note_report`
--
ALTER TABLE `debit_note_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `debit_note_report_items`
--
ALTER TABLE `debit_note_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deliverycondition`
--
ALTER TABLE `deliverycondition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_conditions`
--
ALTER TABLE `delivery_conditions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deposits_number_unique` (`number`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `employee_report`
--
ALTER TABLE `employee_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_report_items`
--
ALTER TABLE `employee_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exchange_rate`
--
ALTER TABLE `exchange_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expenses_number_unique` (`number`);

--
-- Indexes for table `expenses_items`
--
ALTER TABLE `expenses_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_items2`
--
ALTER TABLE `expenses_items2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_report`
--
ALTER TABLE `expenses_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses_report_items`
--
ALTER TABLE `expenses_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods_issues`
--
ALTER TABLE `goods_issues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_issues_number_unique` (`number`);

--
-- Indexes for table `goods_issue_items`
--
ALTER TABLE `goods_issue_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_number_unique` (`number`);

--
-- Indexes for table `invoices_2`
--
ALTER TABLE `invoices_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices_log`
--
ALTER TABLE `invoices_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_items_log`
--
ALTER TABLE `invoice_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_item_taxes`
--
ALTER TABLE `invoice_item_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_report`
--
ALTER TABLE `invoice_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_report_items`
--
ALTER TABLE `invoice_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_flow_mappings`
--
ALTER TABLE `journal_flow_mappings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_flow_mappings___`
--
ALTER TABLE `journal_flow_mappings___`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_vouchers_movement`
--
ALTER TABLE `journal_vouchers_movement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `journal_voucher_items`
--
ALTER TABLE `journal_voucher_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media_uploads`
--
ALTER TABLE `media_uploads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `paymentcondition`
--
ALTER TABLE `paymentcondition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_options`
--
ALTER TABLE `payment_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_options_items`
--
ALTER TABLE `payment_options_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_options_report`
--
ALTER TABLE `payment_options_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_options_report_items`
--
ALTER TABLE `payment_options_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_vouchers`
--
ALTER TABLE `payment_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_voucher_bills`
--
ALTER TABLE `payment_voucher_bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_voucher_items`
--
ALTER TABLE `payment_voucher_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payrolls_number_unique` (`number`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `price_changes`
--
ALTER TABLE `price_changes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_changes_report`
--
ALTER TABLE `price_changes_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_changes_report_items`
--
ALTER TABLE `price_changes_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_aggregation`
--
ALTER TABLE `products_aggregation`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_aggregation_item_code_unique` (`item_code`);

--
-- Indexes for table `products_division`
--
ALTER TABLE `products_division`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_lots`
--
ALTER TABLE `products_lots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_report`
--
ALTER TABLE `products_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_report_items`
--
ALTER TABLE `products_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_report_items_old`
--
ALTER TABLE `products_report_items_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products__`
--
ALTER TABLE `products__`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_item_code_unique` (`item_code`);

--
-- Indexes for table `product_aggregation_items`
--
ALTER TABLE `product_aggregation_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_brands`
--
ALTER TABLE `product_brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_conversions`
--
ALTER TABLE `product_conversions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_dropdown1`
--
ALTER TABLE `product_dropdown1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_dropdown2`
--
ALTER TABLE `product_dropdown2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_inventories`
--
ALTER TABLE `product_inventories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_items`
--
ALTER TABLE `product_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_log`
--
ALTER TABLE `product_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sub_categories`
--
ALTER TABLE `product_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_sub_sub_categories`
--
ALTER TABLE `product_sub_sub_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_taxes`
--
ALTER TABLE `product_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_units`
--
ALTER TABLE `product_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_number_unique` (`number`);

--
-- Indexes for table `purchase_orders_log`
--
ALTER TABLE `purchase_orders_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_items_log`
--
ALTER TABLE `purchase_order_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_item_taxes`
--
ALTER TABLE `purchase_order_item_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_report`
--
ALTER TABLE `purchase_order_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_report_items`
--
ALTER TABLE `purchase_order_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quick_menu`
--
ALTER TABLE `quick_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_number_unique` (`number`);

--
-- Indexes for table `quotations_log`
--
ALTER TABLE `quotations_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_item_taxes`
--
ALTER TABLE `quotation_item_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_log_items`
--
ALTER TABLE `quotation_log_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_report`
--
ALTER TABLE `quotation_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quotation_report_items`
--
ALTER TABLE `quotation_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_vouchers`
--
ALTER TABLE `receipt_vouchers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_voucher_invoices`
--
ALTER TABLE `receipt_voucher_invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_voucher_items`
--
ALTER TABLE `receipt_voucher_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_orders`
--
ALTER TABLE `receive_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `receive_orders_number_unique` (`number`);

--
-- Indexes for table `receive_orders_report`
--
ALTER TABLE `receive_orders_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_orders_report_items`
--
ALTER TABLE `receive_orders_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive_order_items`
--
ALTER TABLE `receive_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `release_note`
--
ALTER TABLE `release_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_views`
--
ALTER TABLE `report_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `report_widgets`
--
ALTER TABLE `report_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_deposits`
--
ALTER TABLE `return_deposits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `return_deposits_number_unique` (`number`);

--
-- Indexes for table `sales_orders`
--
ALTER TABLE `sales_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_orders_log`
--
ALTER TABLE `sales_orders_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_orders___`
--
ALTER TABLE `sales_orders___`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_orders_number_unique` (`number`);

--
-- Indexes for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_items_log`
--
ALTER TABLE `sales_order_items_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_items___`
--
ALTER TABLE `sales_order_items___`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_item_taxes`
--
ALTER TABLE `sales_order_item_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_report`
--
ALTER TABLE `sales_order_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales_order_report_items`
--
ALTER TABLE `sales_order_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saved_queries`
--
ALTER TABLE `saved_queries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `segments`
--
ALTER TABLE `segments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_payments`
--
ALTER TABLE `seller_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_payments_docs`
--
ALTER TABLE `seller_payments_docs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_payments_docs_items`
--
ALTER TABLE `seller_payments_docs_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_payments_report`
--
ALTER TABLE `seller_payments_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_payments_report_items`
--
ALTER TABLE `seller_payments_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_statement`
--
ALTER TABLE `seller_statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seller_statement_items`
--
ALTER TABLE `seller_statement_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `shippers`
--
ALTER TABLE `shippers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipper_bills`
--
ALTER TABLE `shipper_bills`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipper_bills_number_unique` (`number`);

--
-- Indexes for table `shipper_bill_items`
--
ALTER TABLE `shipper_bill_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipper_payments`
--
ALTER TABLE `shipper_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipper_payments_number_unique` (`number`);

--
-- Indexes for table `shipper_payment_items`
--
ALTER TABLE `shipper_payment_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipper_statement`
--
ALTER TABLE `shipper_statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipper_statement_items`
--
ALTER TABLE `shipper_statement_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sidebar_links`
--
ALTER TABLE `sidebar_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statement`
--
ALTER TABLE `statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `statement_items`
--
ALTER TABLE `statement_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_counts`
--
ALTER TABLE `stock_counts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_count_lots`
--
ALTER TABLE `stock_count_lots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_count_products`
--
ALTER TABLE `stock_count_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_count_products_stock_count_id_foreign` (`stock_count_id`);

--
-- Indexes for table `stock_movement`
--
ALTER TABLE `stock_movement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `temp_bill`
--
ALTER TABLE `temp_bill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test1`
--
ALTER TABLE `test1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test2`
--
ALTER TABLE `test2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test4`
--
ALTER TABLE `test4`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test5`
--
ALTER TABLE `test5`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test6`
--
ALTER TABLE `test6`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `third_parties_extras`
--
ALTER TABLE `third_parties_extras`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transfer_accounts`
--
ALTER TABLE `transfer_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transfer_accounts_number_unique` (`number`);

--
-- Indexes for table `uom`
--
ALTER TABLE `uom`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_dashboards`
--
ALTER TABLE `user_dashboards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_accounts`
--
ALTER TABLE `vat_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vat_rate`
--
ALTER TABLE `vat_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_bills_report`
--
ALTER TABLE `vendor_bills_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_bills_report_items`
--
ALTER TABLE `vendor_bills_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendor_payments_number_unique` (`number`);

--
-- Indexes for table `vendor_payments_log`
--
ALTER TABLE `vendor_payments_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments_report`
--
ALTER TABLE `vendor_payments_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payments_report_items`
--
ALTER TABLE `vendor_payments_report_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_payment_items`
--
ALTER TABLE `vendor_payment_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_statement`
--
ALTER TABLE `vendor_statement`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_statement_items`
--
ALTER TABLE `vendor_statement_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `account_items`
--
ALTER TABLE `account_items`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_payments`
--
ALTER TABLE `advance_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_payment_items`
--
ALTER TABLE `advance_payment_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_payment_report`
--
ALTER TABLE `advance_payment_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `advance_payment_report_items`
--
ALTER TABLE `advance_payment_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bills_log`
--
ALTER TABLE `bills_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_items_log`
--
ALTER TABLE `bill_items_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bill_item_taxes`
--
ALTER TABLE `bill_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cache_log`
--
ALTER TABLE `cache_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_log`
--
ALTER TABLE `cart_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chart_accounts`
--
ALTER TABLE `chart_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1027;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients__`
--
ALTER TABLE `clients__`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_balance_report`
--
ALTER TABLE `client_balance_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_balance_report_items`
--
ALTER TABLE `client_balance_report_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_balance_schedule`
--
ALTER TABLE `client_balance_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_dropdown1`
--
ALTER TABLE `client_dropdown1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `client_dropdown2`
--
ALTER TABLE `client_dropdown2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payments`
--
ALTER TABLE `client_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payments_log`
--
ALTER TABLE `client_payments_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payment_items`
--
ALTER TABLE `client_payment_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payment_report`
--
ALTER TABLE `client_payment_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_payment_report_items`
--
ALTER TABLE `client_payment_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_orders`
--
ALTER TABLE `container_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_order_items`
--
ALTER TABLE `container_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_order_item_products`
--
ALTER TABLE `container_order_item_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_order_report`
--
ALTER TABLE `container_order_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_order_report_items`
--
ALTER TABLE `container_order_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_receive_orders`
--
ALTER TABLE `container_receive_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `container_receive_order_items`
--
ALTER TABLE `container_receive_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `counters`
--
ALTER TABLE `counters`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `credit_notes`
--
ALTER TABLE `credit_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_notes_items`
--
ALTER TABLE `credit_notes_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_note_report`
--
ALTER TABLE `credit_note_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `credit_note_report_items`
--
ALTER TABLE `credit_note_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_returns`
--
ALTER TABLE `customer_returns`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_returns_report`
--
ALTER TABLE `customer_returns_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_returns_report_items`
--
ALTER TABLE `customer_returns_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_return_items`
--
ALTER TABLE `customer_return_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `damaged`
--
ALTER TABLE `damaged`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `damaged_items`
--
ALTER TABLE `damaged_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dashboard_views`
--
ALTER TABLE `dashboard_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `debit_notes`
--
ALTER TABLE `debit_notes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debit_notes_items`
--
ALTER TABLE `debit_notes_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debit_note_report`
--
ALTER TABLE `debit_note_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `debit_note_report_items`
--
ALTER TABLE `debit_note_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliverycondition`
--
ALTER TABLE `deliverycondition`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `delivery_conditions`
--
ALTER TABLE `delivery_conditions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_report`
--
ALTER TABLE `employee_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_report_items`
--
ALTER TABLE `employee_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exchange_rate`
--
ALTER TABLE `exchange_rate`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_items`
--
ALTER TABLE `expenses_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_items2`
--
ALTER TABLE `expenses_items2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_report`
--
ALTER TABLE `expenses_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_report_items`
--
ALTER TABLE `expenses_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_uploads`
--
ALTER TABLE `file_uploads`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_issues`
--
ALTER TABLE `goods_issues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_issue_items`
--
ALTER TABLE `goods_issue_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices_2`
--
ALTER TABLE `invoices_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices_log`
--
ALTER TABLE `invoices_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_items_log`
--
ALTER TABLE `invoice_items_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_item_taxes`
--
ALTER TABLE `invoice_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_report`
--
ALTER TABLE `invoice_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_report_items`
--
ALTER TABLE `invoice_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_flow_mappings`
--
ALTER TABLE `journal_flow_mappings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `journal_flow_mappings___`
--
ALTER TABLE `journal_flow_mappings___`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `journal_vouchers`
--
ALTER TABLE `journal_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_vouchers_movement`
--
ALTER TABLE `journal_vouchers_movement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `journal_voucher_items`
--
ALTER TABLE `journal_voucher_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `media_uploads`
--
ALTER TABLE `media_uploads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paymentcondition`
--
ALTER TABLE `paymentcondition`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_options`
--
ALTER TABLE `payment_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_options_items`
--
ALTER TABLE `payment_options_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_options_report`
--
ALTER TABLE `payment_options_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_options_report_items`
--
ALTER TABLE `payment_options_report_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_vouchers`
--
ALTER TABLE `payment_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_voucher_bills`
--
ALTER TABLE `payment_voucher_bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_voucher_items`
--
ALTER TABLE `payment_voucher_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_changes`
--
ALTER TABLE `price_changes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_changes_report`
--
ALTER TABLE `price_changes_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `price_changes_report_items`
--
ALTER TABLE `price_changes_report_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` double NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2500004;

--
-- AUTO_INCREMENT for table `products_aggregation`
--
ALTER TABLE `products_aggregation`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_division`
--
ALTER TABLE `products_division`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_lots`
--
ALTER TABLE `products_lots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_report`
--
ALTER TABLE `products_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_report_items`
--
ALTER TABLE `products_report_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_report_items_old`
--
ALTER TABLE `products_report_items_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products__`
--
ALTER TABLE `products__`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_aggregation_items`
--
ALTER TABLE `product_aggregation_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_brands`
--
ALTER TABLE `product_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_conversions`
--
ALTER TABLE `product_conversions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_dropdown1`
--
ALTER TABLE `product_dropdown1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_dropdown2`
--
ALTER TABLE `product_dropdown2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_inventories`
--
ALTER TABLE `product_inventories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_items`
--
ALTER TABLE `product_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_log`
--
ALTER TABLE `product_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_sub_categories`
--
ALTER TABLE `product_sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_sub_sub_categories`
--
ALTER TABLE `product_sub_sub_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_taxes`
--
ALTER TABLE `product_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_units`
--
ALTER TABLE `product_units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders_log`
--
ALTER TABLE `purchase_orders_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items_log`
--
ALTER TABLE `purchase_order_items_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_item_taxes`
--
ALTER TABLE `purchase_order_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_report`
--
ALTER TABLE `purchase_order_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_report_items`
--
ALTER TABLE `purchase_order_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quick_menu`
--
ALTER TABLE `quick_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations_log`
--
ALTER TABLE `quotations_log`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_item_taxes`
--
ALTER TABLE `quotation_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_log_items`
--
ALTER TABLE `quotation_log_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_report`
--
ALTER TABLE `quotation_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_report_items`
--
ALTER TABLE `quotation_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_vouchers`
--
ALTER TABLE `receipt_vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_voucher_invoices`
--
ALTER TABLE `receipt_voucher_invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_voucher_items`
--
ALTER TABLE `receipt_voucher_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_orders`
--
ALTER TABLE `receive_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_orders_report`
--
ALTER TABLE `receive_orders_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_orders_report_items`
--
ALTER TABLE `receive_orders_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_order_items`
--
ALTER TABLE `receive_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `release_note`
--
ALTER TABLE `release_note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `report_views`
--
ALTER TABLE `report_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `report_widgets`
--
ALTER TABLE `report_widgets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `return_deposits`
--
ALTER TABLE `return_deposits`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders`
--
ALTER TABLE `sales_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders_log`
--
ALTER TABLE `sales_orders_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_orders___`
--
ALTER TABLE `sales_orders___`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_items`
--
ALTER TABLE `sales_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_items_log`
--
ALTER TABLE `sales_order_items_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_items___`
--
ALTER TABLE `sales_order_items___`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_item_taxes`
--
ALTER TABLE `sales_order_item_taxes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_report`
--
ALTER TABLE `sales_order_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_report_items`
--
ALTER TABLE `sales_order_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_queries`
--
ALTER TABLE `saved_queries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `segments`
--
ALTER TABLE `segments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `seller_payments`
--
ALTER TABLE `seller_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_payments_docs`
--
ALTER TABLE `seller_payments_docs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_payments_docs_items`
--
ALTER TABLE `seller_payments_docs_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_payments_report`
--
ALTER TABLE `seller_payments_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_payments_report_items`
--
ALTER TABLE `seller_payments_report_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_statement`
--
ALTER TABLE `seller_statement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `seller_statement_items`
--
ALTER TABLE `seller_statement_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `shippers`
--
ALTER TABLE `shippers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipper_bills`
--
ALTER TABLE `shipper_bills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipper_bill_items`
--
ALTER TABLE `shipper_bill_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipper_payments`
--
ALTER TABLE `shipper_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipper_payment_items`
--
ALTER TABLE `shipper_payment_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipper_statement`
--
ALTER TABLE `shipper_statement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipper_statement_items`
--
ALTER TABLE `shipper_statement_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sidebar_links`
--
ALTER TABLE `sidebar_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `statement`
--
ALTER TABLE `statement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statement_items`
--
ALTER TABLE `statement_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_counts`
--
ALTER TABLE `stock_counts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stock_count_lots`
--
ALTER TABLE `stock_count_lots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock_count_products`
--
ALTER TABLE `stock_count_products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_movement`
--
ALTER TABLE `stock_movement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_bill`
--
ALTER TABLE `temp_bill`
  MODIFY `id` int(191) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test`
--
ALTER TABLE `test`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test1`
--
ALTER TABLE `test1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test2`
--
ALTER TABLE `test2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `test4`
--
ALTER TABLE `test4`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `test5`
--
ALTER TABLE `test5`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `test6`
--
ALTER TABLE `test6`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `third_parties_extras`
--
ALTER TABLE `third_parties_extras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transfer_accounts`
--
ALTER TABLE `transfer_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uom`
--
ALTER TABLE `uom`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user_dashboards`
--
ALTER TABLE `user_dashboards`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `vat_accounts`
--
ALTER TABLE `vat_accounts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vat_rate`
--
ALTER TABLE `vat_rate`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_bills_report`
--
ALTER TABLE `vendor_bills_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_bills_report_items`
--
ALTER TABLE `vendor_bills_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payments`
--
ALTER TABLE `vendor_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payments_log`
--
ALTER TABLE `vendor_payments_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payments_report`
--
ALTER TABLE `vendor_payments_report`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payments_report_items`
--
ALTER TABLE `vendor_payments_report_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_payment_items`
--
ALTER TABLE `vendor_payment_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_statement`
--
ALTER TABLE `vendor_statement`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_statement_items`
--
ALTER TABLE `vendor_statement_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `stock_count_products`
--
ALTER TABLE `stock_count_products`
  ADD CONSTRAINT `stock_count_products_stock_count_id_foreign` FOREIGN KEY (`stock_count_id`) REFERENCES `stock_counts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
