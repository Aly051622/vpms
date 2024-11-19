<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support & Feedback - CTU Danao Parking System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <div class="support-container">
        <h1>Customer Support & Feedback</h1>
        <p>We are here to help! If you have any questions, concerns, or feedback, feel free to reach out to us using the form below.</p>
        
        <!-- Support & Feedback Form -->
        <form action="submit_feedback.php" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" required><br>

    <label for="email">Email (optional):</label>
    <input type="email" name="email"><br>

   

            <div class="form-group">
                <label for="issue">Issue/Concern</label>
                <select id="issue" name="issue" required>
                    <option value="" disabled selected>Select your issue</option>
                    <option value="login_issue">Login Issue</option>
                    <option value="registration_issue">Registration Issue</option>
                    <option value="payment_problem">Payment Problem</option>
                    <option value="technical_support">Technical Support</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Your Message/Feedback</label>
                <textarea id="message" name="message" rows="5" placeholder="Describe your issue or feedback" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" class="btn-submit">Submit Feedback</button>
            </div>
        </form>
    </div>

</body>
</html>
