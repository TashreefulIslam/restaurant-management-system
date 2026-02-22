-- create_database
CREATE DATABASE IF NOT EXISTS restaurant_db;
USE restaurant_db;

-- Admins (resturent admin)
CREATE TABLE IF NOT EXISTS admins (
  admin_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  contact VARCHAR(20),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- customer (public users)
CREATE TABLE IF NOT EXISTS customer (
  customer_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  contact VARCHAR(20),
  address TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- food menu
CREATE TABLE IF NOT EXISTS menu (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category ENUM('burger','pizza','drink') NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- order table
CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  status ENUM('pending','approved','declined','delivered') DEFAULT 'pending',
  delivery_time VARCHAR(50), -- e.g. "30 mins"
  total DECIMAL(10,2) DEFAULT 0.00, -- total order cost
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customer(customer_id) ON DELETE CASCADE
);

-- order items
CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  menu_id INT NOT NULL,
  quantity INT NOT NULL DEFAULT 1,
  price DECIMAL(10,2) NOT NULL, -- price per item (copied from menu at time of order)
  FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
  FOREIGN KEY (menu_id) REFERENCES menu(id) ON DELETE CASCADE
);


-- INSERT Data

INSERT INTO menu (name, category, description, price, image)
VALUES 
-- BURGER
('Double Patty Beef Burger', 'burger', 'Delicious beef burger with cheese', 499, 'double_patty.jpg'),
('Beef Volcano', 'burger', 'beef, cheese, white sauce', 349, 'beef_volcano.jpg'),
('Chicken Burger', 'burger', 'chicken, cheese, vegetables', 199, 'chicken_burger.jpg'),
-- PiZZA
('Four Flavour', 'pizza', 'four different tastes at once', 895, 'four_flavour.jpg'),
('Deep Sea Fantasy', 'pizza', 'tuna bhorta, saltwater shrimp', 695, 'deep_sea_fantasy.jpg'),
('Sausage Carnival', 'pizza', 'sausage, mushroom, spice', 559, 'Sausage_carnival.jpg'),
('BBQ Meat Machine', 'pizza', 'beef & chicken. Too much juicy. The BEST!!!', 695, 'BBQ_Meat_machin.jpg'),
('Cheese Overload', 'pizza', 'cheese cheese cheese!!!!!', 1195, 'cheese_overload.jpg'),
-- DRINK
('Mocktail', 'drink', 'fresh fruits juice', 149, 'mocktail.jpg'),
('Mojo Orange', 'drink', 'Cold soft drink 250ml', 50, 'mojo_orange.jpg'),
('Mojo', 'drink', 'Cold soft drink 250ml', 50, 'mojo.jpg');






