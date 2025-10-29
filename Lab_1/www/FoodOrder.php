<?php
class FoodOrder {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($name, $email, $portions, $dish, $sauce, $delivery_type, $delivery_date) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO food_orders (name, email, portions, dish, sauce, delivery_type, delivery_date) 
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $email, $portions, $dish, $sauce, $delivery_type, $delivery_date]);
        return $this->pdo->lastInsertId();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM food_orders ORDER BY order_time DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM food_orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $email, $portions, $dish, $sauce, $delivery_type, $delivery_date) {
        $stmt = $this->pdo->prepare(
            "UPDATE food_orders 
             SET name=?, email=?, portions=?, dish=?, sauce=?, delivery_type=?, delivery_date=?
             WHERE id=?"
        );
        $stmt->execute([$name, $email, $portions, $dish, $sauce, $delivery_type, $delivery_date, $id]);
        return $stmt->rowCount();
    }


    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM food_orders WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function getDishStats() {
        $stmt = $this->pdo->query(
            "SELECT dish, COUNT(*) as count, SUM(portions) as total_portions 
             FROM food_orders 
             GROUP BY dish 
             ORDER BY count DESC"
        );
        return $stmt->fetchAll();
    }

    public function getByDeliveryType($delivery_type) {
        $stmt = $this->pdo->prepare("SELECT * FROM food_orders WHERE delivery_type = ? ORDER BY order_time DESC");
        $stmt->execute([$delivery_type]);
        return $stmt->fetchAll();
    }
}
?>