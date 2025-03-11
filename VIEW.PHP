<?php
// Database connection
$servername = "sql301.infinityfree.com";
$username = "if0_38341067"; // Change to your database username
$password = "Grtnb137";    // Change to your database password
$dbname = "if0_38341067_tb"; // Change to your database name

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all records from the timetable table
    $stmt = $pdo->query("SELECT * FROM timetable");
    $timetable = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Timetable</title>
</head>
<body>
  <h1>Timetable</h1>
  <table border="1">
    <thead>
      <tr>
        <th>Subject</th>
        <th>Day</th>
        <th>Time</th>
        <th>Teacher</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($timetable as $row): ?>
        <tr>
          <td><?php echo htmlspecialchars($row['subject']); ?></td>
          <td><?php echo htmlspecialchars($row['day']); ?></td>
          <td><?php echo htmlspecialchars($row['time']); ?></td>
          <td><?php echo htmlspecialchars($row['teacher']); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
