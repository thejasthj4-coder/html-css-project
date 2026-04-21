<?php
require_once __DIR__ . '/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $age = intval($_POST['age'] ?? 0);

    if ($name === '' || $email === '' || $age <= 0) {
        $message = 'Please provide valid Name, Email and Age.';
    } else {
        $stmt = $pdo->prepare('INSERT INTO entries (name, email, age) VALUES (:name, :email, :age)');
        $stmt->execute([':name' => $name, ':email' => $email, ':age' => $age]);
        $message = 'Entry saved successfully.';
    }
}

$rows = [];
try {
    $stmt = $pdo->query('SELECT id, name, email, age, created_at FROM entries ORDER BY created_at DESC');
    $rows = $stmt->fetchAll();
} catch (Exception $e) {
    // ignore fetch errors (db may be empty/uninitialized)
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Exercise09 - PHP Form to MySQL</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Exercise 09: Save Form to Database</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="post" action="index.php">
        <input type="text" name="name" placeholder="Enter Name" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="number" name="age" placeholder="Enter Age" required min="1">
        <button type="submit">Save</button>
    </form>

    <h3>Saved Entries</h3>
    <table id="entriesTable">
        <thead>
            <tr><th>ID</th><th>Name</th><th>Email</th><th>Age</th><th>Created At</th></tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
            <tr>
                <td><?php echo $r['id']; ?></td>
                <td><?php echo htmlspecialchars($r['name']); ?></td>
                <td><?php echo htmlspecialchars($r['email']); ?></td>
                <td><?php echo htmlspecialchars($r['age']); ?></td>
                <td><?php echo htmlspecialchars($r['created_at']); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
            <tr><td colspan="5" style="text-align:center;">No entries yet.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>