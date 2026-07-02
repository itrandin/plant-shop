SET FOREIGN_KEY_CHECKS = 0;

DROP VIEW IF EXISTS vw_catalog;
DROP VIEW IF EXISTS vw_popular_plants;
DROP VIEW IF EXISTS vw_discount_plants;
DROP VIEW IF EXISTS vw_user_orders;
DROP VIEW IF EXISTS vw_plant_reviews;


DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS favorites;
DROP TABLE IF EXISTS plants;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS care_levels;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE roles (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    id_role INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES roles(id_role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE categories (
    id_category INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE care_levels (
    id_care_level INT AUTO_INCREMENT PRIMARY KEY,
    level_name VARCHAR(100) NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE plants (
    id_plant INT AUTO_INCREMENT PRIMARY KEY,
    id_category INT NOT NULL,
    id_care_level INT NOT NULL,
    plant_name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    quantity INT DEFAULT 0,
    discount INT DEFAULT 0,
    image VARCHAR(255),
    popularity INT DEFAULT 0,
    FOREIGN KEY (id_category) REFERENCES categories(id_category),
    FOREIGN KEY (id_care_level) REFERENCES care_levels(id_care_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE favorites (
    id_favorite INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_plant INT NOT NULL,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_plant) REFERENCES plants(id_plant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cart (
    id_cart INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_plant INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_plant) REFERENCES plants(id_plant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE orders (
    id_order INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Новый',
    total_amount DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES users(id_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE order_items (
    id_order_item INT AUTO_INCREMENT PRIMARY KEY,
    id_order INT NOT NULL,
    id_plant INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_order) REFERENCES orders(id_order),
    FOREIGN KEY (id_plant) REFERENCES plants(id_plant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE reviews (
    id_review INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_plant INT NOT NULL,
    rating INT NOT NULL,
    comment TEXT NOT NULL,
    review_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user),
    FOREIGN KEY (id_plant) REFERENCES plants(id_plant)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (role_name) VALUES
('Пользователь'),
('Администратор');

INSERT INTO categories (category_name, description) VALUES
('Декоративные', 'Комнатные растения для оформления интерьера.'),
('Неприхотливые', 'Растения, которые не требуют сложного ухода.'),
('Для офиса', 'Растения, подходящие для рабочих помещений.'),
('Для начинающих', 'Растения для пользователей без большого опыта ухода.');

INSERT INTO care_levels (level_name, description) VALUES
('Легкий уход', 'Подходит для начинающих, не требует частого полива.'),
('Средний уход', 'Требует регулярного полива и подходящего освещения.'),
('Требует внимания', 'Нуждается в стабильных условиях содержания.');

INSERT INTO plants (id_category, id_care_level, plant_name, description, price, quantity, discount, image, popularity) VALUES
(1, 2, 'Монстера делициоза', 'Крупное декоративное растение с выразительными резными листьями.', 2490.00, 15, 0, 'monstera.png', 95),
(2, 1, 'Фикус эластика', 'Неприхотливое комнатное растение с плотными зелеными листьями.', 1890.00, 12, 0, 'ficus.png', 88),
(4, 1, 'Замиокулькас', 'Идеален для начинающих, хорошо переносит редкий полив.', 1690.00, 20, 0, 'zamioculcas.png', 91),
(2, 1, 'Сансевиерия', 'Стильное растение для интерьера, устойчивое к засухе.', 1390.00, 18, 10, 'sansevieria.png', 86),
(1, 2, 'Спатифиллум', 'Комнатное растение с белыми цветами и мягкой зеленой листвой.', 1590.00, 10, 0, 'spathiphyllum.png', 74),
(3, 1, 'Хлорофитум', 'Подходит для офиса и дома, быстро растет и очищает воздух.', 990.00, 25, 5, 'chlorophytum.png', 70);

/* ===== Представления базы данных ===== */

DROP VIEW IF EXISTS vw_catalog;
DROP VIEW IF EXISTS vw_popular_plants;
DROP VIEW IF EXISTS vw_discount_plants;
DROP VIEW IF EXISTS vw_user_orders;
DROP VIEW IF EXISTS vw_plant_reviews;

CREATE VIEW vw_catalog AS
SELECT
    p.id_plant,
    p.plant_name,
    c.category_name,
    cl.level_name AS care_level,
    p.description,
    p.price,
    p.quantity,
    p.discount,
    p.image,
    p.popularity
FROM plants p
INNER JOIN categories c ON p.id_category = c.id_category
INNER JOIN care_levels cl ON p.id_care_level = cl.id_care_level;

CREATE VIEW vw_popular_plants AS
SELECT
    p.id_plant,
    p.plant_name,
    c.category_name,
    p.price,
    p.popularity
FROM plants p
INNER JOIN categories c ON p.id_category = c.id_category
WHERE p.popularity >= 80
ORDER BY p.popularity DESC;

CREATE VIEW vw_discount_plants AS
SELECT
    p.id_plant,
    p.plant_name,
    c.category_name,
    p.price,
    p.discount,
    ROUND(p.price - (p.price * p.discount / 100), 2) AS price_with_discount
FROM plants p
INNER JOIN categories c ON p.id_category = c.id_category
WHERE p.discount > 0;

CREATE VIEW vw_user_orders AS
SELECT
    o.id_order,
    u.id_user,
    u.name AS user_name,
    u.email,
    o.order_date,
    o.status,
    o.total_amount,
    COALESCE(GROUP_CONCAT(p.plant_name SEPARATOR ', '), 'Заказ') AS order_products
FROM orders o
INNER JOIN users u ON o.id_user = u.id_user
LEFT JOIN order_items oi ON o.id_order = oi.id_order
LEFT JOIN plants p ON oi.id_plant = p.id_plant
GROUP BY o.id_order, u.id_user, u.name, u.email, o.order_date, o.status, o.total_amount;

CREATE VIEW vw_plant_reviews AS
SELECT
    r.id_review,
    p.id_plant,
    p.plant_name,
    u.id_user,
    u.name AS user_name,
    r.rating,
    r.comment,
    r.review_date
FROM reviews r
INNER JOIN plants p ON r.id_plant = p.id_plant
INNER JOIN users u ON r.id_user = u.id_user;

/* ===== Сообщения обратной связи ===== */

CREATE TABLE IF NOT EXISTS contact_messages (
    id_message INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
