<?php
// Database configuration
$servername = "sql301.infinityfree.com";
$username = "if0_38341067"; // Change to your database username
$password = "Grtnb137";    // Change to your database password
$dbname = "if0_38341067_tb"; // Change to your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $conn->connect_error]));
}

// Get raw POST data
$data = file_get_contents('php://input');
$classes = json_decode($data, true);

// Check if data was received and is valid
if (!is_array($classes) || empty($classes)) {
    echo json_encode(['status' => 'error', 'message' => 'No valid data received!']);
    exit;
}

// Prepare the SQL statement with the new "teacher" field
$stmt = $conn->prepare("INSERT INTO timetable (subject, day, time, teacher) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare SQL statement: ' . $conn->error]);
    exit;
}

// Initialize counters for success and failure
$successCount = 0;
$errorMessages = [];

// Process each class entry
foreach ($classes as $class) {
    // Validate required fields
    if (
        !isset($class['subject']) || empty($class['subject']) ||
        !isset($class['day']) || empty($class['day']) ||
        !isset($class['time']) || empty($class['time']) ||
        !isset($class['teacher']) || empty($class['teacher'])
    ) {
        $errorMessages[] = "Invalid data for class: " . json_encode($class);
        continue;
    }

    // Extract values
    $subject = $class['subject'];
    $day = $class['day'];
    $time = $class['time'];
    $teacher = $class['teacher'];

    // Bind parameters and execute the query
    $stmt->bind_param("ssss", $subject, $day, $time, $teacher);
    if ($stmt->execute()) {
        $successCount++;
    } else {
        $errorMessages[] = "Error inserting data for class: " . json_encode($class) . ". Error: " . $stmt->error;
    }
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$conn->close();

// Return the final response
$response = [
    'status' => $successCount > 0 ? 'success' : 'error',
    'message' => $successCount . ' record(s) saved successfully.',
];

if (!empty($errorMessages)) {
    $response['errors'] = $errorMessages;
}

echo json_encode($response);
?>
