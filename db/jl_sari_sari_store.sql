-- =========================================
-- JL SARI-SARI STORE DATABASE (MySQL 8)
-- =========================================

DROP DATABASE IF EXISTS jl_sari_sari_store;
CREATE DATABASE jl_sari_sari_store;
USE jl_sari_sari_store;

-- =========================================
-- PRODUCTS TABLE
-- =========================================
CREATE TABLE Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    category VARCHAR(50),
    unit VARCHAR(20),
    selling_price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2),
    stock_quantity INT DEFAULT 0,
    reorder_level INT DEFAULT 5
) ENGINE=InnoDB;

-- =========================================
-- SUPPLIERS TABLE
-- =========================================
CREATE TABLE Suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20),
    address TEXT
) ENGINE=InnoDB;

-- =========================================
-- PURCHASES TABLE
-- =========================================
CREATE TABLE Purchases (
    purchase_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT,
    purchase_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) DEFAULT 0,

    CONSTRAINT fk_supplier
        FOREIGN KEY (supplier_id)
        REFERENCES Suppliers(supplier_id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =========================================
-- PURCHASE DETAILS TABLE
-- =========================================
CREATE TABLE Purchase_Details (
    purchase_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    purchase_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    cost_price DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_purchase
        FOREIGN KEY (purchase_id)
        REFERENCES Purchases(purchase_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_purchase_product
        FOREIGN KEY (product_id)
        REFERENCES Products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT unique_purchase_product
        UNIQUE (purchase_id, product_id)
) ENGINE=InnoDB;

-- =========================================
-- SALES TABLE
-- =========================================
CREATE TABLE Sales (
    sale_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2) DEFAULT 0
) ENGINE=InnoDB;

-- =========================================
-- SALE DETAILS TABLE
-- =========================================
CREATE TABLE Sale_Details (
    sale_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    selling_price DECIMAL(10,2) NOT NULL,

    CONSTRAINT fk_sale
        FOREIGN KEY (sale_id)
        REFERENCES Sales(sale_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_sale_product
        FOREIGN KEY (product_id)
        REFERENCES Products(product_id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT unique_sale_product
        UNIQUE (sale_id, product_id)
) ENGINE=InnoDB;

-- =========================================
-- TRIGGERS (MySQL 8 Compatible)
-- =========================================

DELIMITER //

-- Update total amount after purchase
CREATE TRIGGER update_purchase_total
AFTER INSERT ON Purchase_Details
FOR EACH ROW
BEGIN
    UPDATE Purchases
    SET total_amount = (
        SELECT SUM(quantity * cost_price)
        FROM Purchase_Details
        WHERE purchase_id = NEW.purchase_id
    )
    WHERE purchase_id = NEW.purchase_id;
END;
//

-- Update total amount after sale
CREATE TRIGGER update_sales_total
AFTER INSERT ON Sale_Details
FOR EACH ROW
BEGIN
    UPDATE Sales
    SET total_amount = (
        SELECT SUM(quantity * selling_price)
        FROM Sale_Details
        WHERE sale_id = NEW.sale_id
    )
    WHERE sale_id = NEW.sale_id;
END;
//

-- Stock IN after purchase
CREATE TRIGGER stock_in_after_purchase
AFTER INSERT ON Purchase_Details
FOR EACH ROW
BEGIN
    UPDATE Products
    SET stock_quantity = stock_quantity + NEW.quantity
    WHERE product_id = NEW.product_id;
END;
//

-- Stock OUT after sale
CREATE TRIGGER stock_out_after_sale
AFTER INSERT ON Sale_Details
FOR EACH ROW
BEGIN
    UPDATE Products
    SET stock_quantity = stock_quantity - NEW.quantity
    WHERE product_id = NEW.product_id;
END;
//

-- Prevent negative stock
CREATE TRIGGER prevent_negative_stock
BEFORE INSERT ON Sale_Details
FOR EACH ROW
BEGIN
    DECLARE current_stock INT;
    SELECT stock_quantity INTO current_stock
    FROM Products
    WHERE product_id = NEW.product_id;
    IF current_stock < NEW.quantity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Not enough stock!';
    END IF;
END;
//

DELIMITER ;

-- =========================================
-- SAMPLE DATA
-- =========================================

INSERT INTO Products (product_name, category, unit, selling_price, cost_price, stock_quantity)
VALUES
('Coke 1.5L', 'Beverages', 'Bottle', 70, 60, 50),
('Instant Noodles', 'Food', 'Pack', 15, 10, 100),
('Coffee Sachet', 'Beverages', 'Sachet', 10, 7, 200);

INSERT INTO Suppliers (supplier_name, contact_number, address)
VALUES
('ABC Trading', '09123456789', 'Quezon City'),
('XYZ Distributor', '09987654321', 'Manila');

-- Sample purchase
INSERT INTO Purchases (supplier_id) VALUES (1);
INSERT INTO Purchase_Details (purchase_id, product_id, quantity, cost_price)
VALUES
(1, 1, 10, 60),
(1, 2, 20, 10);

-- Sample sale
INSERT INTO Sales DEFAULT VALUES;
INSERT INTO Sale_Details (sale_id, product_id, quantity, selling_price)
VALUES
(1, 1, 2, 70),
(1, 2, 3, 15);

-- =========================================
-- END OF JL SARI-SARI STORE (MySQL 8)
-- =========================================