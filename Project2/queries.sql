-- For a given list of products, get the names of all categories that contain this products.

SELECT DISTINCT c.name
FROM categories c
INNER JOIN product_categories pc ON c.id = pc.category_id
WHERE pc.product_id IN (1, 2, 3);

-- For a given category, get a list of offers for all products in this category and its child categories.

SELECT DISTINCT p.id, p.name, p.price, p.quantity_in_stock
FROM products p
INNER JOIN product_categories pc ON p.id = pc.product_id
INNER JOIN category_rels cr ON pc.category_id = cr.child_id
WHERE cr.parent_id = 1;

-- For a given list of categories, get the number of product offers in each category.

SELECT
    c.id AS category_id,
    c.name AS category_name,
    COUNT(DISTINCT pc.product_id) product_count
FROM categories c
LEFT JOIN product_categories pc ON c.id = pc.category_id
WHERE c.id IN (1, 2, 3)
GROUP BY c.id, c.name;

-- For a given list of categories, get the total number of unique product offers.

SELECT COUNT(DISTINCT product_id) total_qty
FROM product_categories
WHERE category_id IN (1, 2, 3);

-- For a given category, get its full path in the tree (breadcrumb).

SELECT GROUP_CONCAT(c.name ORDER BY FIND_IN_SET(c.id, REPLACE(cr.path, '/', ',')) SEPARATOR ' > ') breadcrumbs
FROM category_rels cr
INNER JOIN categories c ON FIND_IN_SET(c.id, REPLACE(cr.path, '/', ','))
WHERE cr.child_id = 5;
