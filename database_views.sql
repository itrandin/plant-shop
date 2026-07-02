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
