DROP TABLE IF EXISTS Customers;
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE Menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    dish_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES Categories(category_id)
);

CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    total_price DECIMAL(10, 2),
    status ENUM('Pending', 'Preparing', 'Completed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id)
);

CREATE TABLE Order_Details (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES Orders(order_id),
    FOREIGN KEY (menu_id) REFERENCES Menu(menu_id)
);

CREATE TABLE Ingredients (
    ingredient_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    unit VARCHAR(50),
    price_per_unit DECIMAL(10, 2)
);

CREATE TABLE Stock (
    stock_id INT AUTO_INCREMENT PRIMARY KEY,
    ingredient_id INT NOT NULL,
    quantity INT NOT NULL,
    threshold INT DEFAULT 10, 
    FOREIGN KEY (ingredient_id) REFERENCES Ingredients(ingredient_id)
);

CREATE TABLE Admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE Suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_info TEXT
);


-- Örnek veri ekleme

-- Categories
INSERT INTO Categories (name) VALUES 
('Burger'), 
('Tatlı'), 
('İçecek'), 
('Atıştırmalık');

-- Menu
INSERT INTO Menu (category_id, dish_name, description, price, image_url) VALUES
(1, 'Whopper', 'İkonik Burger', 60.00, 'whopper.jpg'),
(1, 'Big King', 'Efsane Lezzet', 50.00, 'bigking.jpg'),
(3, 'Coca-Cola', '350ml Kutu İçecek', 10.00, 'cola.jpg'),
(3, 'Sprite', '350ml Kutu İçecek', 10.00, 'sprite.jpg'),
(2, 'Çikolatalı Kek', 'Tatlı kriziniz için ideal', 20.00, 'cake.jpg');

-- Customers
INSERT INTO Customers (name, email, phone, password, address) VALUES
('Ahmet Yılmaz', 'ahmet@gmail.com', '05555555555', '123456', 'Kadıköy, İstanbul'),
('Elif Demir', 'elif@gmail.com', '05554443322', '654321', 'Beşiktaş, İstanbul');

-- Orders
INSERT INTO Orders (customer_id, total_price, status) VALUES
(1, 70.00, 'Pending'),
(2, 40.00, 'Preparing');

-- Order_Details
INSERT INTO Order_Details (order_id, menu_id, quantity, price) VALUES
(1, 1, 1, 60.00),
(1, 3, 1, 10.00),
(2, 2, 1, 50.00);

-- Ingredients
INSERT INTO Ingredients (name, unit, price_per_unit) VALUES 
('Dana Eti', 'kg', 200.00),
('Marul', 'adet', 2.50),
('Domates', 'adet', 1.50);

-- Stock
INSERT INTO Stock (ingredient_id, quantity, threshold) VALUES
(1, 50, 10),
(2, 200, 20),
(3, 300, 30);

-- Admins
INSERT INTO Admins (name, email, password) VALUES
('Admin User', 'admin@restaurant.com', 'admin123');

-- Suppliers
INSERT INTO Suppliers (name, contact_info) VALUES
('Tedarikçi A', '05321234567, İstanbul'),
('Tedarikçi B', '05443334444, Ankara');


