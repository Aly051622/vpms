<?php
// Include the database connection
include('includes/dbconnection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/chartist@0.11.0/dist/chartist.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/jqvmap@1.5.1/dist/jqvmap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/weathericons@2.1.0/css/weather-icons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" rel="stylesheet" />

    <title>Admin View Comments | CTU DANAO Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            background-color: whitesmoke;
        }
        .feedback {
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            background-color: aliceblue;
            color: gray;
            text-align: left;
        }
        h4 {
            text-align: center;
            margin-top: 20px;
        }
        .feedback-header {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .feedback-section {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/header.php'); ?>

    <!-- Breadcrumbs -->
    <div class="breadcrumbs mb-3">
        <div class="breadcrumbs-inner">
            <div class="row m-0">
                <div class="col-sm-4">
                    <div class="page-header float-left">
                        <div class="page-title">
                            <h1>Dashboard</h1>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="page-header float-right">
                        <div class="page-title">
                            <ol class="breadcrumb text-right">
                                <li><a href="dashboard.php">Dashboard</a></li>
                                <li><a href="admin_feedback.php">All Feedbacks</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
    <div class="container mt-5">
        <h4>Feedbacks</h4>
        <div id="feedback-section" class="feedback-section"></div>
    </div>

    <script>
    // Load existing feedback on page load
    document.addEventListener('DOMContentLoaded', function () {
        fetch('get_feedback.php')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load feedback');
                }
                return response.json();  // Expecting a valid JSON response
            })
            .then(data => {
                if (data.success && Array.isArray(data.feedbacks)) {
                    data.feedbacks.forEach(feedback => {
                        addFeedbackToUI(feedback.username || 'Anonymous', feedback.feedback, feedback.admin_reply);
                    });
                } else {
                    alert('Failed to load feedback: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error loading feedback:', error);
                alert('An error occurred while loading feedback.');
            });
    });

    // Helper function to add a new feedback and reply to the UI
    function addFeedbackToUI(userName, feedbackText, adminReply = '') {
        const feedbackSection = document.getElementById('feedback-section');

        // Create feedback block
        const feedbackDiv = document.createElement('div');
        feedbackDiv.classList.add('feedback');

        const feedbackHeader = document.createElement('div');
        feedbackHeader.classList.add('feedback-header');
        feedbackHeader.textContent = `${userName} says:`;

        const feedbackBody = document.createElement('div');
        feedbackBody.textContent = feedbackText;

        feedbackDiv.appendChild(feedbackHeader);
        feedbackDiv.appendChild(feedbackBody);
        feedbackSection.appendChild(feedbackDiv);

        // If admin replied, show admin reply
        if (adminReply) {
            const replyDiv = document.createElement('div');
            replyDiv.classList.add('reply');

            const replyHeader = document.createElement('div');
            replyHeader.classList.add('reply-header');
            replyHeader.textContent = 'Admin replied:';

            const replyBody = document.createElement('div');
            replyBody.textContent = adminReply;

            replyDiv.appendChild(replyHeader);
            replyDiv.appendChild(replyBody);
            feedbackSection.appendChild(replyDiv);
        }
    }

    // Handle reply submission
    const replyButton = document.getElementById('reply-button');
    replyButton.addEventListener('click', function() {
        const replyInput = document.getElementById('reply-input').value.trim();
        if (replyInput === '') {
            alert('Please enter a reply.');
            return;
        }

        // Send the admin's reply to the server (for demo, the logic is simplified)
        fetch('send_reply.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ reply: replyInput })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addFeedbackToUI('Admin', replyInput, true);
                document.getElementById('reply-input').value = ''; // Clear input
            } else {
                alert('Failed to send reply: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error sending reply:', error);
        });
    });
    </script>

</body>
</html>
