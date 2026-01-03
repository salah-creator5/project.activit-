<?php
require_once 'includes/db.php';

echo "<h2>Testing Fixed Logic</h2>";
$pdo->beginTransaction();
try {
    // 1. Insert
    $stmt = $pdo->prepare("INSERT INTO inscriptions (activite_id, nom, prenom, email, telephone) VALUES (1, 'FixTest', 'User', 'fix@test.com', '0600000000')");
    $stmt->execute();

    // 2. Capture ID immediately (THE FIX)
    $last_id = $pdo->lastInsertId();
    echo "ID Captured immediately: " . var_export($last_id, true) . "<br>";

    // 3. Update
    try {
        $pdo->prepare("UPDATE activites SET places_disponibles = places_disponibles - 1 WHERE id = 1")->execute();
        echo "Update executed.<br>";
    } catch (PDOException $e) {
        echo "Update failed (ignoring for test): " . $e->getMessage() . "<br>";
    }

    // 4. Verify we still have the correct ID
    echo "ID used for redirect would be: " . var_export($last_id, true) . "<br>";

    // 5. Clean up (rollback) because we don't want to pollute DB more than necessary
    $pdo->rollBack();
    echo "Transaction rolled back (cleanup). Test Successful if ID > 0.";

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Transaction failed: " . $e->getMessage();
}
?>