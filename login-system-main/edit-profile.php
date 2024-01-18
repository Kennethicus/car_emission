<?php
// edit_profile.php
session_start();
include(__DIR__ . "/navbar.php");
include("connect/connection.php");

// Check if the user is logged in
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $query = "SELECT * FROM login WHERE email = ?";
    $stmt = $connect->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "User not found.";
        exit();
    }
} else {
    // If the user is not logged in, redirect to the login page
    header('Location: index.php');
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize user input to prevent SQL injection
    $newFirstName = filter_input(INPUT_POST, 'newFirstName', FILTER_SANITIZE_STRING);
    $newLastName = filter_input(INPUT_POST, 'newLastName', FILTER_SANITIZE_STRING);
    $newContactNo = filter_input(INPUT_POST, 'newContactNo', FILTER_SANITIZE_STRING);
    $newAddress = filter_input(INPUT_POST, 'newAddress', FILTER_SANITIZE_STRING);

    // Validate email to prevent header injection
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="alert alert-danger" role="alert">Invalid email format.</div>';
        exit();
    }

    // Update user information in the database using prepared statement to prevent SQL injection
    $updateQuery = "UPDATE login SET first_name=?, last_name=?, contact_no=?, address=? WHERE email=?";
    $stmt = $connect->prepare($updateQuery);
    $stmt->bind_param("sssss", $newFirstName, $newLastName, $newContactNo, $newAddress, $email);
    $updateResult = $stmt->execute();

    if ($updateResult) {
        // Successful update
        echo '<div class="alert alert-success" role="alert">Profile updated successfully!</div>';
    } else {
        // Failed update
        echo '<div class="alert alert-danger" role="alert">Error updating profile. Please try again.</div>';
    }
}

// Close the prepared statement
$stmt->close();

// Close the database connection
$connect->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(45deg, #4CAF50, #2196F3);
            border-bottom: none;
            border-radius: 15px 15px 0 0;
            color: white;
            text-align: center;
        }

        .card-title {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .card-text {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .edit-profile-link {
            color: #ffffff;
            text-decoration: none;
        }

        .edit-profile-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Edit Profile</h5>
            </div>
            <div class="card-body">
                <!-- Display user information -->
                <p class="card-text"><strong>Email:</strong> <?php echo $row['email']; ?></p>
                <form method="post" action="">
                    <div class="form-group">
                        <label for="newFirstName">New First Name:</label>
                        <input type="text" class="form-control" id="newFirstName" name="newFirstName" value="<?php echo htmlspecialchars($row['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newLastName">New Last Name:</label>
                        <input type="text" class="form-control" id="newLastName" name="newLastName" value="<?php echo htmlspecialchars($row['last_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newContactNo">New Contact No:</label>
                        <input type="tel" class="form-control" id="newContactNo" name="newContactNo" value="<?php echo htmlspecialchars($row['contact_no']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="newAddress">New Address:</label>
                        <textarea class="form-control" id="newAddress" name="newAddress" rows="3" required><?php echo htmlspecialchars($row['address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
