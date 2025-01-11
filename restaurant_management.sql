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

-- 6 ocak 21:10 da buraya bu alter komutları eklendi
ALTER TABLE menu ADD COLUMN image VARCHAR(255);

ALTER TABLE Orders ADD COLUMN quantity int;

ALTER TABLE Suppliers ADD COLUMN service_area VARCHAR(255);

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

--kategori belirleme
UPDATE Menu 
SET category_id = (SELECT category_id FROM Categories WHERE name = 'Burger') 
WHERE dish_name IN ('Whopper', 'Big King');

UPDATE Menu 
SET category_id = (SELECT category_id FROM Categories WHERE name = 'Tatlı') 
WHERE dish_name = 'Çikolatalı Kek';

UPDATE Menu 
SET category_id = (SELECT category_id FROM Categories WHERE name = 'İçecek') 
WHERE dish_name IN ('Coca-Cola', 'Sprite');

UPDATE Menu 
SET category_id = (SELECT category_id FROM Categories WHERE name = 'Atıştırmalık') 
WHERE dish_name = 'Çikolatalı Kek';


--KATEGORİLERE GÖRE GÖRÜNTÜLEME
ALTER TABLE Menu ADD COLUMN category VARCHAR(255);

-- Kategori sütununu silme
ALTER TABLE Menu DROP COLUMN category;

--YENİ ÜRÜNLER ekleme
INSERT INTO Menu (category_id, dish_name, description, price, image_url) VALUES
(1, 'Cheeseburger', 'Lezzetli peynirli burger', 55.00, 'cheeseburger.jpg'),
(1, 'Double Whopper', 'Çift etli ikonik burger', 80.00, 'double_whopper.jpg'),
(1, 'Chicken Royale', 'Tavuklu burger', 50.00, 'chicken_royale.jpg'),
(1, 'Veggie Burger', 'Vejetaryen seçenek', 45.00, 'veggie_burger.jpg'),
(1, 'Classic Burger', 'Klasik burger', 40.00, 'classic_burger.jpg'),
(1, 'Fish Burger', 'Balıklı burger', 60.00, 'fish_burger.jpg'),


UPDATE Menu SET image = 'veggie_burger.jpg' WHERE menu_id = 21;
UPDATE Menu SET image = 'classic_burger.jpg' WHERE menu_id = 22;
UPDATE Menu SET image = 'fish_burger.jpg' WHERE menu_id = 23;


INSERT INTO Menu (category_id, dish_name, description, price, image_url) VALUES
(2, 'Sundae', 'Çikolatalı ve vanilyalı dondurma', 20.00, 'sundae.jpg'),
(2, 'Profiterol', 'Kremalı tatlı', 25.00, 'profiterol.jpg'),
(2, 'Cheesecake', 'Lezzetli cheesecake', 30.00, 'cheesecake.jpg'),
(2, 'Baklava', 'Fıstıklı baklava', 35.00, 'baklava.jpg'),
(2, 'Krema Dondurma', 'Sade dondurma', 15.00, 'cream_icecream.jpg'),
(2, 'Karpuzlu Dondurma', 'Karpuzlu dondurma', 20.00, 'watermelon_icecream.jpg'),
(2, 'Elmalı Turta', 'Elmalı tatlı', 25.00, 'apple_pie.jpg'),
(2, 'Çikolatalı Mousse', 'Lezzetli çikolatalı mousse', 28.00, 'chocolate_mousse.jpg')


UPDATE Menu SET image = 'sundae.jpg' WHERE menu_id = 24;
UPDATE Menu SET image = 'profiterol.jpg' WHERE menu_id = 25;
UPDATE Menu SET image = 'cheesecake.jpg' WHERE menu_id = 26;
UPDATE Menu SET image = 'baklava.jpg' WHERE menu_id = 27;
UPDATE Menu SET image = 'chocolate-cake.jpg' WHERE menu_id = 28;
UPDATE Menu SET image = 'cream_icecream.jpg' WHERE menu_id = 29;
UPDATE Menu SET image = 'watermelon_icecream.jpg' WHERE menu_id = 30;
UPDATE Menu SET image = 'apple_pie.jpg' WHERE menu_id = 31;
UPDATE Menu SET image = 'chocolate_mousse.jpg' WHERE menu_id = 32;


INSERT INTO Menu (category_id, dish_name, description, price, image_url) VALUES
(3, 'Fanta', 'Narenciye içeceği', 12.00, 'fanta.jpg'),
(3, 'Pepsi', 'Cola içeceği', 12.00, 'pepsi.jpg'),
(3, 'Ice Tea', 'Şekerli soğuk çay', 10.00, 'ice_tea.jpg'),
(3, 'Ayran', 'Yoğurtlu içecek', 8.00, 'ayran.jpg'),
(3, 'Limonata', 'Taze sıkılmış limonata', 15.00, 'lemonade.jpg'),
(3, 'Vişne Suyu', 'Vişne içeceği', 14.00, 'cherry_juice.jpg'),
(3, 'Portakal Suyu', 'Taze sıkılmış portakal suyu', 12.00, 'orange_juice.jpg')


UPDATE Menu SET image = 'fanta.jpg' WHERE menu_id = 32;
UPDATE Menu SET image = 'pepsi.jpg' WHERE menu_id = 33;
UPDATE Menu SET image = 'ice_tea.jpg' WHERE menu_id = 34;
UPDATE Menu SET image = 'ayran.jpg' WHERE menu_id = 35;
UPDATE Menu SET image = 'lemonade.jpg' WHERE menu_id = 36;
UPDATE Menu SET image = 'cherry_juice.jpg' WHERE menu_id = 37;
UPDATE Menu SET image = 'orange_juice.jpg' WHERE menu_id = 38;



INSERT INTO Menu (category_id, dish_name, description, price, image_url) VALUES
(4, 'Patates Kızartması', 'Kızarmış patates', 35.00, 'fries.jpg'),
(4, 'Mozzarella Sticks', 'Mozzarella peyniri dilimleri', 55.00, 'mozzarella_sticks.jpg'),
(4, 'Onion Rings', 'Soğan halkaları', 18.00, 'onion_rings.jpg'),
(4, 'Chicken Nuggets', 'Tavuk nugget', 20.00, 'chicken_nuggets.jpg'),
(4, 'Kumpir', 'Fırın patates', 30.00, 'kumpir.jpg'),
(4, 'Cips', 'Patlamış mısır', 10.00, 'chips.jpg'),
(4, 'Dondurmalı Çörek', 'Tatlı çörek', 20.00, 'donut_icecream.jpg'),
(4, 'Pizza Dilimi', 'Küçük pizza dilimi', 22.00, 'pizza_slice.jpg'),
(4, 'Tavuk Kanatları', 'Barbekü soslu tavuk kanatları', 35.00, 'chicken_wings.jpg'),
(4, 'Börek', 'İçli börek', 25.00, 'borek.jpg')



UPDATE Menu SET image = 'fries.jpg' WHERE menu_id = 39;
UPDATE Menu SET image = 'mozzarella_sticks.jpg' WHERE menu_id =40;
UPDATE Menu SET image = 'onion_rings.jpg' WHERE menu_id = 41;
UPDATE Menu SET image = 'chicken_nuggets.jpg' WHERE menu_id = 42;
UPDATE Menu SET image = 'kumpir.jpg' WHERE menu_id = 43;
UPDATE Menu SET image = 'chips.jpg' WHERE menu_id = 44;
UPDATE Menu SET image = 'donut_icecream.jpg' WHERE menu_id = 45;
UPDATE Menu SET image = 'pizza_slice.jpg' WHERE menu_id = 46;
UPDATE Menu SET image = 'chicken_wings.jpg' WHERE menu_id = 47;
UPDATE Menu SET image = 'borek.jpg' WHERE menu_id = 48;

-- Tedarik için hizmet alanı ekleme
UPDATE Suppliers SET service_area = 'Bakliyat' WHERE name = 'Tedarikçi A';
UPDATE Suppliers SET service_area = 'Sebze' WHERE name = 'Tedarikçi B';


-- sipariş durumu için 3 seçenek eklenmesi
ALTER TABLE orders MODIFY COLUMN status ENUM('Pending', 'Preparing', 'Prepared') DEFAULT 'Pending'; 



-- Adding a Chefs Table
CREATE TABLE Chefs (
    chef_id INT AUTO_INCREMENT PRIMARY KEY, -- Unique identifier for each chef
    name VARCHAR(100) NOT NULL, -- Chef's name
    experience_years INT CHECK (experience_years >= 0), -- Years of experience, must be 0 or more
    specialty VARCHAR(100) NOT NULL, -- Chef's specialty area
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Timestamp of when the record was created
);



-- all_orders sayfası için gerekli prosedür
DELIMITER //

CREATE PROCEDURE GetOrderDetails()
BEGIN
    SELECT m.dish_name, od.quantity, o.status, o.created_at
    FROM orders o
    LEFT JOIN order_details od ON o.order_id = od.order_id
    LEFT JOIN menu m ON od.menu_id = m.menu_id;
END //

DELIMITER ;

-- invoices sayfası için gerekli prosedür
DELIMITER //
CREATE PROCEDURE GetInvoices()
BEGIN
    SELECT m.dish_name, od.quantity, o.total_price, o.status, o.created_at
    FROM orders o
    JOIN order_details od ON o.order_id = od.order_id
    JOIN menu m ON od.menu_id = m.menu_id;
END //
DELIMITER ;

-- stocks_status sayfası için gerekli prosedür
DELIMITER //
CREATE PROCEDURE GetStockStatus()
BEGIN
    SELECT ingredients.name, ingredients.unit, stock.quantity 
    FROM ingredients 
    INNER JOIN stock ON ingredients.ingredient_id = stock.ingredient_id;
END //
DELIMITER ;

-- suppliers sayfası için gerekli prosedür
DELIMITER //
CREATE PROCEDURE GetSuppliers()
BEGIN
    SELECT name, service_area, contact_info FROM suppliers;
END //
DELIMITER ;


--stocks_page sayfası için gerekli prosedür
DELIMITER //

CREATE PROCEDURE update_stock_quantity(IN p_ingredient_id INT, IN p_quantity INT)
BEGIN
    -- Quantity değeri 1'den küçük olamaz
    IF p_quantity < 1 THEN
        SET p_quantity = 1;
    END IF;

    -- Miktarı güncelleme
    UPDATE stock 
    SET quantity = p_quantity 
    WHERE ingredient_id = p_ingredient_id;
END //

DELIMITER ;

-- menu sayfası için prosedür
DELIMITER $$

CREATE PROCEDURE GetMenuByCategory(IN category_id INT)
BEGIN
    IF category_id IS NULL THEN
        SELECT menu_id, dish_name, description, price, image 
        FROM Menu;
    ELSE
        SELECT menu_id, dish_name, description, price, image 
        FROM Menu 
        WHERE category_id = category_id;
    END IF;
END $$

DELIMITER ;

