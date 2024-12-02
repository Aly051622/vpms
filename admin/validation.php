<?php
session_start();
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error_message']}</div>";
    unset($_SESSION['error_message']); // Clear the message after displaying
}

if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success'>{$_SESSION['success_message']}</div>";
    unset($_SESSION['success_message']); // Clear the message after displaying
}

if (isset($_SESSION['uploaded_image']) && isset($_SESSION['email'])) {
    $uploaded_image = $_SESSION['uploaded_image'];
    $email = $_SESSION['email'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Driver's License | CTU Danao Parking System</title>
    <!-- Add your other styles and links here -->
</head>
<body>

<?php include_once('includes/sidebar.php'); ?>
<?php include_once('includes/header.php'); ?>

<!-- Breadcrumbs Section -->
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Driver's License Validation</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="validation.php">Validation</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Container Section -->
<h2 class="mb-5">Update Driver's License</h2>
<div class="container">
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required><br>

        <label for="license_image">Select Driver's License Image:</label>
        <input type="file" id="license_image" name="license_image" accept="image/*" required><br>

        <button type="submit" id="submit">Submit</button>
    </form>
</div>

<!-- Display uploaded data -->
<?php if (isset($uploaded_image) && isset($email)): ?>
    <div class="container mt-5">
        <h3>Uploaded Information</h3>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        <p><strong>Uploaded Image:</strong></p>
        <img src="<?php echo htmlspecialchars($uploaded_image); ?>" alt="Uploaded License" width="300" />
    </div>
<?php endif; ?>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
