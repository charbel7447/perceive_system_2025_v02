-- Table creation (manual)
CREATE TABLE sidebar_links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    route_path VARCHAR(255) DEFAULT NULL,
    icon VARCHAR(255) DEFAULT NULL,
    parent_id INT DEFAULT NULL,
    is_main TINYINT(1) DEFAULT 0,
    tab_type VARCHAR(50) DEFAULT NULL,
    sort_order INT DEFAULT 0,
    extra_class VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- DASHBOARD_TAB
INSERT INTO sidebar_links (title, route_path, icon, is_main, tab_type, sort_order) VALUES
('Dashboard', '/', 'fa fa-dashboard', 1, 'dashboard_tab', 1),
('General Reports', '/sidebar_reports', 'fa fa-home', 1, 'dashboard_tab', 2),
('General Setup', '/general_settings', 'fa fa-cogs', 1, 'dashboard_tab', 3),
('Custom Query', '/custom_query', 'fa fa-cogs', 1, 'dashboard_tab', 4);

-- SALES_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Sales', 'fa fa-dashboard', 1, 'sales_tab', 1);

-- Get the last inserted ID for Sales parent menu
SET @sales_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Sales Orders', '/sales_orders', 'fa fa-file-text-o', 0, @sales_id, 'sales_tab', 1),
('Client Invoices', '/invoices', 'fa fa-file-text-o', 0, @sales_id, 'sales_tab', 2),
('Quotations', '/quotations', 'fa fa-file-text-o', 0, @sales_id, 'sales_tab', 3),
('Clients', '/clients', 'fa fa-user', 0, @sales_id, 'sales_tab', 4);

-- PROCUREMENT_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Purchase Orders', 'fa fa-cogs', 1, 'procurment_tab', 1);

SET @procure_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Receive Orders', '/receive_orders', 'fa fa-file-text-o', 0, @procure_id, 'procurment_tab', 1),
('Purchase Orders', '/purchase_orders', 'fa fa-file-text-o', 0, @procure_id, 'procurment_tab', 2);

INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Procurment & Stock', 'fa fa-cogs', 1, 'procurment_tab', 2);

SET @procure_stock_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Products', '/products', 'fa fa-cubes', 0, @procure_stock_id, 'procurment_tab', 1),
('Customer Returns', '/customer_returns', 'fa fa-cubes', 0, @procure_stock_id, 'procurment_tab', 2);

INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Procurment & Stock (Extended)', 'fa fa-cogs', 1, 'procurment_tab', 3);

SET @procure_stock_ext_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Products', '/products', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 1),
('Warehouses', '/warehouses', 'fa fa-home', 0, @procure_stock_ext_id, 'procurment_tab', 2),
('Raw Material Type', '/raw_material_type', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 3),
('Receive Orders', '/receive_orders', 'fa fa-file-text-o', 0, @procure_stock_ext_id, 'procurment_tab', 4),
('Vendors', '/vendors', 'fa fa-users', 0, @procure_stock_ext_id, 'procurment_tab', 5),
('Purchase Orders', '/purchase_orders', 'fa fa-file-text-o', 0, @procure_stock_ext_id, 'procurment_tab', 6),
('Transfers', '/transfers', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 7),
('Products Division', '/products_division', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 8),
('Products Aggregation', '/products_aggregation', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 9),
('Stock Movement', '/stock_movement', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 10),
('Stock Count', '/stock_count', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 11),
('Damaged Deteriorate', '/damaged_deteriorate', 'fa fa-cubes', 0, @procure_stock_ext_id, 'procurment_tab', 12);

-- ACCOUNTING_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Accounting', 'fa fa-dashboard', 1, 'accounting_tab', 1);

SET @accounting_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Clients', '/clients', 'fa fa-user', 0, @accounting_id, 'accounting_tab', 1),
('Journal Vouchers', '/journal_vouchers', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 2),
('JV Movement', '/journal_vouchers_movement', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 3),
('Receipt Voucher', '/receipt_vouchers', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 4),
('Payment Voucher', '/payment_vouchers', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 5),
('Client Advance Payments', '/advance_payments', 'fa fa-money', 0, @accounting_id, 'accounting_tab', 6),
('Client Invoices', '/invoices', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 7),
('Credit Notes', '/credit_notes', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 8),
('Debit Notes', '/debit_notes', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 9),
('Client SOA', '/statement', 'fa fa-money', 0, @accounting_id, 'accounting_tab', 10),
('Vendor Expenses', '/expenses', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 11),
('Vendor Bills', '/bills', 'fa fa-file-text-o', 0, @accounting_id, 'accounting_tab', 12),
('Vendor SOA', '/vendor_statement', 'fa fa-money', 0, @accounting_id, 'accounting_tab', 13);

-- COMPANY_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Company', 'fa fa-dashboard', 1, 'company_tab', 1);

SET @company_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Chart of Accounts', '/chart_of_accounts', 'fa fa-money', 0, @company_id, 'company_tab', 1),
('Balance Sheet', '/balance_sheet', 'fa fa-money', 0, @company_id, 'company_tab', 2),
('General Ledger', '/general_ledger', 'fa fa-money', 0, @company_id, 'company_tab', 3),
('Profit & Loss (Income Statement)', '/profit_loss', 'fa fa-money', 0, @company_id, 'company_tab', 4),
('Transfer Accounts', '/transfer_accounts', 'fa fa-money', 0, @company_id, 'company_tab', 5),
('Deposit', '/deposits', 'fa fa-money', 0, @company_id, 'company_tab', 6),
('Return Deposit', '/return_deposits', 'fa fa-money', 0, @company_id, 'company_tab', 7);

-- PRODUCTION_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Production', 'fa fa-cogs', 1, 'production_tab', 1);

SET @production_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('Machines', '/machines', 'fa fa-cubes', 0, @production_id, 'production_tab', 1),
('Product', '/finished_product', 'fa fa-cubes', 0, @production_id, 'production_tab', 2),
('Product Type', '/finished_product_type', 'fa fa-cubes', 0, @production_id, 'production_tab', 3),
('Job Orders', '/job_order', 'fa fa-cubes', 0, @production_id, 'production_tab', 4),
('Line Production', '/line_production', 'fa fa-cubes', 0, @production_id, 'production_tab', 5),
('Packaging', '/packaging', 'fa fa-cubes', 0, @production_id, 'production_tab', 6),
('Delivery Note', '/delivery_note', 'fa fa-cubes', 0, @production_id, 'production_tab', 7),
('Attributes', '/attributes', 'fa fa-cubes', 0, @production_id, 'production_tab', 8);

-- SETTINGS_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('General Parameters', 'fa fa-dashboard', 1, 'settings_tab', 1);

SET @settings_id = LAST_INSERT_ID();

INSERT INTO sidebar_links (title, route_path, icon, is_main, parent_id, tab_type, sort_order) VALUES
('General Settings', '/general_settings', 'fa fa-home', 0, @settings_id, 'settings_tab', 1),
('Custom Reports', '/sidebar_reports', 'fa fa-home', 0, @settings_id, 'settings_tab', 2);

-- ADMIN_TAB
INSERT INTO sidebar_links (title, icon, is_main, tab_type, sort_order) VALUES
('Administrator', 'fa fa-cogs', 1, 'admin', 1);
