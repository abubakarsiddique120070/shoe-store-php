-- ============================================================
-- Shoe Store Complete Database
-- Database: admin_system
-- ============================================================

CREATE DATABASE IF NOT EXISTS admin_system;
USE admin_system;

-- ── USERS TABLE (Admin & Manager login) ──────────────────────
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(100) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','manager') DEFAULT 'manager',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DEFAULT ACCOUNTS (password = "password123" for both)
-- Hashed using PHP: password_hash('password123', PASSWORD_DEFAULT)
INSERT IGNORE INTO users (name, email, password, role) VALUES
('Super Admin',   'admin@shoestore.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Store Manager', 'manager@shoestore.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager');

-- ── CATEGORIES ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
  id              INT AUTO_INCREMENT PRIMARY KEY,
  category_name   VARCHAR(100) NOT NULL,
  description     TEXT,
  parent_category INT DEFAULT 0,
  status          ENUM('active','inactive') DEFAULT 'active',
  category_image  VARCHAR(255),
  seo_keyword     VARCHAR(255),
  created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (category_name, description, status, seo_keyword) VALUES
('Men Shoes',   'All types of men footwear', 'active', 'men shoes'),
('Women Shoes', 'All types of women footwear', 'active', 'women shoes'),
('Sports',      'Sports and athletic shoes', 'active', 'sports shoes'),
('Casual',      'Everyday casual footwear', 'active', 'casual shoes');

-- ── PRODUCTS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS products (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  product_name  VARCHAR(150) NOT NULL,
  category      VARCHAR(100),
  price         DECIMAL(10,2) NOT NULL,
  stock         INT DEFAULT 0,
  description   TEXT,
  product_image VARCHAR(255),
  status        ENUM('available','out_of_stock') DEFAULT 'available',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (product_name, category, price, stock, description, status) VALUES
('Nike Air Max 270',  'Sports',      12500.00, 20, 'Lightweight running shoe with Air Max cushioning', 'available'),
('Adidas Stan Smith', 'Casual',       8500.00, 35, 'Classic tennis-inspired sneaker', 'available'),
('Puma RS-X',         'Men Shoes',    9200.00, 15, 'Bold retro-running design', 'available'),
('Nike Blazer Mid',   'Women Shoes',  7800.00, 25, 'Vintage basketball-inspired silhouette', 'available'),
('Bata Comfit',       'Casual',       3500.00, 50, 'Comfortable all-day wear shoe', 'available');

-- ── CUSTOMERS ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS customers (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(100) NOT NULL,
  email         VARCHAR(150) UNIQUE,
  password      VARCHAR(255),
  phone         VARCHAR(20),
  city          VARCHAR(100),
  address       TEXT,
  gender        ENUM('male','female','other'),
  status        ENUM('active','inactive') DEFAULT 'active',
  profile_image VARCHAR(255),
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO customers (customer_name, email, phone, city, gender, status) VALUES
('Ali Hassan',    'ali@email.com',    '03001234567', 'Lahore',      'male',   'active'),
('Sara Khan',     'sara@email.com',   '03111234567', 'Karachi',     'female', 'active'),
('Usman Ahmed',   'usman@email.com',  '03211234567', 'Islamabad',   'male',   'active'),
('Ayesha Malik',  'ayesha@email.com', '03311234567', 'Faisalabad',  'female', 'active');

-- ── ORDERS ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
  id             INT AUTO_INCREMENT PRIMARY KEY,
  customer_name  VARCHAR(100) NOT NULL,
  product_name   VARCHAR(150),
  quantity       INT DEFAULT 1,
  price          DECIMAL(10,2),
  order_date     DATE,
  payment_method VARCHAR(50),
  order_status   ENUM('pending','completed','cancelled') DEFAULT 'pending',
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO orders (customer_name, product_name, quantity, price, order_date, payment_method, order_status) VALUES
('Ali Hassan',   'Nike Air Max 270',  1, 12500.00, '2026-05-01', 'cash',   'completed'),
('Sara Khan',    'Adidas Stan Smith', 2, 17000.00, '2026-05-05', 'card',   'pending'),
('Usman Ahmed',  'Puma RS-X',         1,  9200.00, '2026-05-08', 'online', 'completed'),
('Ayesha Malik', 'Nike Blazer Mid',   1,  7800.00, '2026-05-10', 'cash',   'cancelled');

-- ── ORDER ITEMS ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  order_id     INT,
  product_name VARCHAR(150),
  quantity     INT,
  total_price  DECIMAL(10,2),
  created_at   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- ── PAYMENTS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS payments (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  method     VARCHAR(100),
  note       TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── REVIEWS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS reviews (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  customer_name VARCHAR(100),
  shoe_name     VARCHAR(150),
  rating        TINYINT CHECK (rating BETWEEN 1 AND 5),
  review_text   TEXT,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO reviews (customer_name, shoe_name, rating, review_text) VALUES
('Ali Hassan',  'Nike Air Max 270',  5, 'Bahut comfortable hain, delivery fast thi!'),
('Sara Khan',   'Adidas Stan Smith', 4, 'Stylish shoes, packaging bhi achi thi.');

-- ── COMPLAINTS ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS complains (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  full_name  VARCHAR(100),
  phone      VARCHAR(20),
  email      VARCHAR(150),
  message    TEXT,
  status     ENUM('open','resolved') DEFAULT 'open',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ── VIEWS / ANALYTICS ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS views (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  product_name VARCHAR(150),
  view_count   INT DEFAULT 1,
  viewed_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO views (product_name, view_count) VALUES
('Nike Air Max 270', 145),
('Adidas Stan Smith', 98),
('Puma RS-X', 67),
('Nike Blazer Mid', 54),
('Bata Comfit', 32);
