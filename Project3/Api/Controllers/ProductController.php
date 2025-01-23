<?php

namespace Api\Controllers;

use Api\Models\Database;
use PDO;

class ProductController
{
    public static function index(): void
    {
        $db = (new Database())->connect();

        $query = $db->query("SELECT * FROM products");

        echo json_encode($query->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $db = (new Database())->connect();

        $stmt = $db->prepare("INSERT INTO products (name, price, quantity_in_stock) VALUES (?, ?, ?)");
        $stmt->execute([$data['name'], $data['price'], $data['quantity_in_stock']]);

        echo json_encode(['success' => true, 'message' => 'Product created']);
    }

    /**
     * @param int $productId
     * @param int $categoryId
     */
    public static function addCategory(int $productId, int $categoryId): void
    {
        $db = (new Database())->connect();

        $stmt = $db->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
        $stmt->execute([$productId, $categoryId]);

        echo json_encode(['success' => true, 'message' => 'Product added to category']);
    }

    /**
     * @param int $productId
     * @param int $categoryId
     */
    public static function removeCategory(int $productId, int $categoryId): void
    {
        $db = (new Database())->connect();

        $stmt = $db->prepare("DELETE FROM product_categories WHERE product_id = ? AND category_id = ?");
        $stmt->execute([$productId, $categoryId]);

        echo json_encode(['success' => true, 'message' => 'Product removed from category']);
    }

    /**
     * @param int $productId
     * @param int $tagId
     */
    public static function addTag(int $productId, int $tagId): void
    {
        $db = (new Database())->connect();

        $stmt = $db->prepare("INSERT INTO product_tags (product_id, tag_id) VALUES (:product_id, :tag_id)");
        $stmt->execute([$productId, $tagId]);

        echo json_encode(['success' => true, 'message' => "Tag $tagId added to product $productId"]);
    }

    /**
     * @param int $productId
     * @param int $tagId
     */
    public static function removeTag(int $productId, int $tagId): void
    {
        $db = (new Database())->connect();

        $stmt = $db->prepare("DELETE FROM product_tags WHERE product_id = ? AND tag_id = ?");
        $stmt->execute([$productId, $tagId]);

        echo json_encode(['success' => true, 'message' => "Tag $tagId removed from product $productId"]);
    }
}
