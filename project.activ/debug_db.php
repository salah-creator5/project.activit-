<?php
require_once 'includes/db.php';

echo "<h2>Testing Insert + Update Flow</h2>";
$pdo->beginTransaction();
try {
    // 1. Insert
    $stmt = $pdo->prepare("INSERT INTO inscriptions (activite_id, nom, prenom, email, telephone) VALUES (1, 'Simulation', 'User', 'sim@test.com', '0600000000')");
    $stmt->execute();

    // Check ID immediately
    $id1 = $pdo->lastInsertId();
    echo "ID after Insert: " . var_export($id1, true) . "<br>";

    // 2. Try Update
    // First check if activites table exists
    try {
        $pdo->prepare("UPDATE activites SET places_disponibles = places_disponibles - 1 WHERE id = 1")->execute();
        echo "Update executed.<br>";
    } catch (PDOException $e) {
        echo "Update failed (expected if table missing): " . $e->getMessage() . "<br>";
    }

    // 3. Check ID after Update
    $id2 = $pdo->lastInsertId();
    echo "ID after Update: " . var_export($id2, true) . "<br>";

    // 4. Check if Inscription actually exists
    $check = $pdo->query("SELECT * FROM inscriptions WHERE id = $id1")->fetch();
    echo "Inscription record: " . ($check ? "Found" : "Not Found") . "<br>";

    $pdo->commit();
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Transaction failed: " . $e->getMessage();
}
?>