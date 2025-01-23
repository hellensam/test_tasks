INSERT INTO products (id, name, price, quantity_in_stock) VALUES
    (1, 'iPhone 13', 999.99, 50),
    (2, 'Samsung Galaxy S21', 799.99, 30),
    (3, 'MacBook Pro', 2499.99, 20),
    (4, 'Dell XPS 15', 2199.99, 15),
    (5, 'Razer Blade 15', 2799.99, 10),
    (6, 'Anker Charger', 29.99, 200),
    (7, 'USB-C Cable', 19.99, 300),
    (8, 'Wireless Charging Pad', 49.99, 150),
    (9, 'AirPods Pro', 249.99, 100),
    (10, 'Sony WH-1000XM5', 399.99, 40);

INSERT INTO products (name, price, quantity_in_stock)
SELECT
    CONCAT('Test Product ', n) name,
    ROUND(RAND() * 1000, 2) price,
    FLOOR(RAND() * 100 + 1) quantity_in_stock
FROM
    (SELECT @row := @row + 1 AS n FROM (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) a,
        (SELECT 0 UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) b,
        (SELECT @row := 0) c) t
    LIMIT 90;

INSERT INTO categories (id, name, parent_id) VALUES
    (1, 'Electronics', NULL),
    (2, 'Smartphones', 1),
    (3, 'Laptops', 1),
    (4, 'Accessories', 1),
    (5, 'Gaming Laptops', 3),
    (6, 'Chargers', 4),
    (7, 'Cables', 4),
    (8, 'Wireless Chargers', 6);

INSERT INTO tags (id, name) VALUES
    (1, 'BestSeller'),
    (2, 'NewArrival'),
    (3, 'Discount'),
    (4, 'LimitedEdition'),
    (5, 'Popular'),
    (6, 'HighPerformance'),
    (7, 'BudgetFriendly'),
    (8, 'EcoFriendly'),
    (9, 'TopRated'),
    (10, 'Wireless');

INSERT INTO product_categories (product_id, category_id) VALUES
    (1, 2),
    (2, 2),
    (3, 3),
    (4, 3),
    (5, 5),
    (6, 6),
    (7, 7),
    (8, 8),
    (9, 4),
    (10, 4);

INSERT INTO product_tags (product_id, tag_id) VALUES
    (1, 1),
    (1, 2),
    (2, 5),
    (3, 6),
    (4, 6),
    (5, 4),
    (6, 7),
    (7, 8),
    (8, 10),
    (9, 1),
    (10, 9);

INSERT INTO category_rels (parent_id, child_id, path) VALUES
    (NULL, 1, '1'),
    (1, 2, '1/2'),
    (1, 3, '1/3'),
    (1, 4, '1/4'),
    (3, 5, '1/3/5'),
    (4, 6, '1/4/6'),
    (4, 7, '1/4/7'),
    (6, 8, '1/4/6/8');
