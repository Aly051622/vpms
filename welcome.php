<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
     
    <!-- jQuery, Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    

    <title>CTU DANAO PARKING SYSTEM|| Home Page</title>

    <link rel="stylesheet" href="css/styles.css">
    <style>
    /* Common styles for both PC and mobile */
    body {
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
        font-family: var(--bs-body-font-family);
        font-size: var(--bs-body-font-size);
        font-weight: var(--bs-body-font-weight);
        line-height: var(--bs-body-line-height);
        color: var(--bs-body-color);
        text-align: var(--bs-body-text-align);
        background-color: var(--bs-body-bg);
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
        height: 100vh;
    }
    @media (max-width: 768px) { /* Tablet and small screens */
    body {
        font-size: calc(var(--bs-body-font-size) * 0.9); /* Slightly smaller font for tablets */
        line-height: calc(var(--bs-body-line-height) * 1.1); /* Adjust line height */
    }
}

@media (max-width: 480px) { /* Mobile screens */
    body {
        font-size: calc(var(--bs-body-font-size) * 0.85); /* Further reduce font size for mobile */
        line-height: calc(var(--bs-body-line-height) * 1.2); /* Adjust line height */
        height: auto; /* Allow body height to adjust based on content */
    }
}

    /* Navigation styles */
    .navbar {
        background-color: #ff9933;
        padding: 10px 0;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }

    .navbar a {
        color: orange;
        text-decoration: none;
        margin: 0 15px;
        position: relative;
        transition: color 0.3s ease, transform 0.3s ease;
    }

    .navbar {
        display: flex;
        justify-content: space-around; /* Adjust alignment as needed */
        padding: 10px 0;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    @media (max-width: 768px) { /* Tablet and small screens */
    .navbar {
        padding: 15px 0; /* Adjust padding for smaller screens */
        flex-direction: column; /* Stack items vertically */
        align-items: center; /* Center items */
        font-size: 14px;
    }

    .navbar a {
        margin: -2px 0; /* Adjust margin for stacked links */
    }
}

@media (max-width: 480px) { /* Mobile screens */
    .navbar {
        padding: 3px 0; /* Further adjust padding for mobile */
    }

}
    .navbar-item {
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        background-color: white; /* Use a shade of blue for the border */
        border-radius: 10px;
        padding: 10px 20px; /* Adjust padding as needed */
        transition: all 0.3s ease; /* Smoothly transition all properties */
    }

    .navbar-item:hover {
        background-color: #2a5298;
        color: white;
        box-shadow: rgba(0, 0, 0, 0.06) 0px 4px 8px 0px inset;        
    }

    /* Media query for tablets */
    @media (max-width: 768px) {
        .navbar-item {
            padding: 8px 15px; /* Reduce padding for smaller screens */
            font-size: 20px; /* Adjust font size for better fit */
        }
    }

    /* Media query for mobile devices */
    @media (max-width: 480px) {
     #title{
        margin-top: -50px;
     }
    }

    #surbtn{
        font-size:24px;
        box-shadow: none;
        color: white;
        border: transparent;
        border-color: linear-gradient(316deg, #f94327 0%, #ff7d14 74%);
        background-color: transparent;
        font-weight: bold;
        text-shadow: 0px 6px 10px rgb(62, 57, 57);
    }

    #surbtn:hover{
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        border-radius: 100px;
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        text-shadow: none;
    }
    @media (max-width: 768px) { /* Tablet and small screens */
        #surbtn {
            font-size: 20px; /* Slightly smaller font */
            padding: 10px 20px; /* Adjust padding */
        }
        }

        @media (max-width: 480px) { /* Mobile screens */
            #surbtn {
                font-size: 24px; /* Even smaller font */
                padding: 3px; /* Smaller padding */
                box-shadow: none; /* Minimal shadow for simplicity */
                display: space-around;
            }
        }
    
    .navbar-brand {
        font-size: 24px;
        opacity: 0.8;
        position: absolute;
        cursor: auto;
    }
    @media (max-width: 768px) { /* Tablet and small screens */
    .navbar-brand {
        font-size: 20px; /* Smaller font for tablet screens */
        position: static; /* Changes to a more flexible position */
        text-align: center; /* Centers the brand if needed */
    }
}
 /* Masthead styles */
.masthead {
    color: #F9A602;
    padding: 50px 0; /* Adjust padding for mobile devices */
    text-align: center;
    background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
    height: 100vh;
}

.masthead h1 {
    font-size: 24px; /* Adjust font size for mobile devices */
    text-shadow: 0 2px 1px #79a06d,
        -1px 3px 1px white,
        -2px 5px 1px orangered;
}

.masthead-avatar {
    max-width: 100%; /* Make the avatar responsive */
    width: 100px; /* Set a specific width for better control on mobile */
    border: 3px solid #fff; /* Adjust border properties */
    border-radius: 50%;
    cursor: zoom-in;
}

@media (max-width: 768px) { /* Tablet and small screens */
    .masthead {
        padding: 30px 0; /* Reduced padding for smaller devices */
    }

    .masthead h1 {
        font-size: 20px; /* Slightly smaller font for tablets */
    }

    .masthead-avatar {
        width: 80px; /* Smaller avatar size for tablets */
    }
}

@media (max-width: 480px) { /* Mobile screens */
    .masthead {
        padding: 20px 0; /* Further reduced padding for mobile */
    }

    .masthead h1 {
        font-size: 18px; /* Even smaller font for mobile */
    }

    .masthead-avatar {
        width: 60px; /* Smaller avatar size for mobile */
    }
}

    .divider-custom {
        margin: 30px 0;
        text-align: center;
    }

    .divider-custom div {
        display: inline-block;
        width: 50%;
        height: 2px;
        background-color: #fff;
    }

    .divider-custom-icon {
        padding: 0 15px;
    }

    .movcar {
        position: relative;
    }

    #vehicleImage {
        width: 65px;
        height: auto;
    }

    @keyframes moveForward {
        to {
            transform: translateX(100%);
        }
    }

    #vehicleImage {
        width: 60px;
        height: auto;
        transition: opacity 1s ease-in-out;
        animation: none;
    }

    /* Copyright styles */
    .copyright {
        margin-top: -40px;
        background-color: #2c2c2c; /* Dark gray color for the road */
        background-image:
            linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
            linear-gradient(orange 2px, transparent 5px),
            url('path/to/asphalt-texture.jpg'); /* Replace 'path/to/asphalt-texture.jpg' with the actual path to your asphalt texture image */
        background-size: 5px 100%, 100% 100%; /* Adjust the stripe width and texture size as needed */
        background-position: center; /* Center the texture image */
        padding: 5px 0;
        text-align: center;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px,
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px,
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        color: #ffffff;
        font-family: 'Arial', sans-serif;
    }

    .copyright small {
        color: #fff;
        font-weight: bold;
    }
    @media (max-width: 768px) {
    .copyright {
        padding: 10px 0; /* Increase padding for smaller screens */
        font-size: 14px; /* Adjust font size for readability */
        background-size: 3px 100%, 100% 100%; /* Adjust stripe size on smaller screens */
    }
}

    @media (max-width: 480px) {
        .copyright {
            padding: 15px 0;
            font-size: 12px; /* Further reduce font size for extra small screens */
            margin-top: -30px; /* Adjust margin for smaller devices */
            background-size: 2px 100%, 100% 100%;
        }
    }

    .form-floating input.form-control,
    .form-floating textarea.form-control {
        font-size: 1.5rem;
        border-left: 0;
        border-right: 0;
        border-top: 0;
        border-radius: 0;
        border-width: 1px;
    }

    .form-floating input.form-control:focus,
    .form-floating textarea.form-control:focus {
        box-shadow: none;
    }

    .form-floating label {
        font-size: 1.5rem;
        color: #6c757d;
    }

    @keyframes grandEntranceAnimation {
        0% {
            opacity: 0;
            transform: translateY(-50px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulsatingColorChange {
        0%, 100% {
            color: orange;
        }
        50% {
            color: red;
        }

        90% {
            color: white;
        }
    }

    .masthead .masthead-heading {
        font-size: 2.75rem;
        line-height: 2.75rem;
        animation: grandEntranceAnimation 2s ease-in-out forwards, pulsatingColorChange 4s linear;
    }

    .masthead {
        padding-top: calc(6rem + 74px);
        padding-bottom: 6rem;
        height:100vh;
    }

    .masthead .masthead-subheading {
        font-size: 1.25rem;
    }

    .masthead .masthead-avatar {
        opacity: 0.8;
        transition: transform 0.5s ease, box-shadow 0.5s ease;
    }

    .masthead .masthead-avatar:hover {
        box-shadow: 1px 10px 0px 0px rgba(255, 255, 255, 0.8),
            1px 20px 0px 0px rgba(255, 255, 255, 0.6),
            1px 30px 0px 0px rgba(255, 255, 255, 0.4),
            1px 42px 0px 0px rgba(255, 255, 255, 0.2);
        transform: scale(1.3) rotate(360deg);
        opacity: 1;
    }

    @media (min-width: 992px) {
        .masthead {
            padding-top: calc(6rem + 40px);
            padding-bottom: 2.6rem;
            height: 100vh;
        }
        .masthead .masthead-heading {
            font-size: 4rem;
            line-height: 5rem;
        }
        .masthead .masthead-subheading {
            font-size: 1.5rem;
        }
    }

    .container,
    .container-fluid,
    .container-xxl,
    .container-xl,
    .container-lg,
    .container-md,
    .container-sm {
        width: 100%;
        padding-right: var(--bs-gutter-x, 0.75rem);
        padding-left: var(--bs-gutter-x, 0.75rem);
        margin-right: auto;
        margin-left: auto;
    }

    @media (min-width: 576px) {
        .container-sm,
        .container {
            max-width: 540px;
        }
    }

    @media (min-width: 768px) {
        .container-md,
        .container-sm,
        .container {
            max-width: 720px;
        }
    }

    @media (min-width: 992px) {
        .container-lg,
        .container-md,
        .container-sm,
        .container {
            max-width: 960px;
        }
    }

    @media (min-width: 1200px) {
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm,
        .container {
            max-width: 1140px;
        }
    }

    @media (min-width: 1400px) {
        .container-xxl,
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm,
        .container {
            max-width: 1320px;
        }
    }

    .nav {
        display: flex;
        flex-wrap: wrap;
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
    }

    .nav-link {
        display: block;
        padding: 0.5rem 1rem;
        color: #1abc9c;
        text-decoration: none;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .nav-link {
            transition: none;
        }
    }

    
 /* Custom CSS for Modals using iFrame */
        p{
            color: black;
            font-weight: bold;
            text-align: left;
            text-indent: 30px;
        }
        h3{
            color:white;
            border: none;
            font-weight: bold;
            font-size: 20px;
        }
        .close{
            color: red;
            padding: 5px;
            border: none;
            color: white;
            text-shadow: gray 0px 6px 8px;
        }
        .close:hover{
            color: red;
            border-radius: 9px;
        }
.modal-header{
  background-color: #ff9933;
}
 .modal-dialog {
            width: 500px; /* Adjust modal width */
            margin-left: 27em;
            margin-top: 5em;
            border-radius: 15px;
            background: whitesmoke;
            text-align: center;
            overflow: auto;
            box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        }
        .modal-body {
            max-height:400px; /* Limit height if needed */
            overflow:hidden;  /* Enable scrolling for long content */
            border-radius: 10px;
        }
        .close{
            border: none;
            background: transparent;
            font-size: 16px;
            font-weight: bold;
        }

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-dialog {
        width: 80%; /* Reduce width on smaller screens */
        max-width: 90%;
        margin-top: 3em; /* Adjust top margin */
        border-radius: 8px;
    }
    .modal-body {
        max-height: 250px; /* Adjust max-height for smaller screens */
    }
}

@media (max-width: 480px) {
    .modal-dialog {
        width: 90%; /* Further reduce width for very small screens */
        margin-top: 2em; /* Reduce top margin */
        border-radius: 15px;
        margin-left: 20px;
    }
    .modal-body {
        max-height: 500px; /* Reduce max-height for extra small screens */
        overflow: auto;
    }
}
        </style>
    <script>
    // Function to change the vehicle image and move it forward after a specified time
function moveForward() {
    const vehicleImage = document.getElementById('vehicleImage');
    vehicleImage.style.animation = 'none'; // Reset animation
    void vehicleImage.offsetWidth; // Trigger reflow to ensure reset takes effect
    vehicleImage.style.animation = 'moveForward 3s forwards';
}

setTimeout(() => {
    moveForward();
    document.getElementById('vehicleImage').src = 'images/bike.png';
}, 3000);

setTimeout(() => {
    moveForward();
    document.getElementById('vehicleImage').src = 'images/motor.png';
}, 6000);

setTimeout(() => {
    moveForward();
    document.getElementById('vehicleImage').src = 'images/car.png';
}, 9000);

setTimeout(() => {
    const vehicleImage = document.getElementById('vehicleImage');
    vehicleImage.style.opacity = 0;
}, 12000);

 // JavaScript to toggle the mobile menu
 const navbarMenu = document.getElementById('navbarMenu');

document.addEventListener('DOMContentLoaded', function () {
    // Add a click event listener to the navbar brand
    document.querySelector('.navbar-brand').addEventListener('click', function () {
        // Toggle the 'active' class on the navbar menu
        navbarMenu.classList.toggle('active');
    });
});

    </script>
</head>
<body id="page-top">
        <!-- Navigation-->
    <nav class="navbar fixed-top" id="mainNav">
        <div class="container">
          
        <a class="navbar-item" href="users/service.php" id="surbtn"><i class="bi bi-headset"></i></a>
          <a class="navbar-brand" id="title" style="color:white; position: absolute; margin-left: 3.5em;">CTU Danao Parking System</a>
            <div class="navbar-menu">   
                <a class="navbar-item" href="admin/index.php">Admin</a>
                <a class="navbar-item" href="guard/login.php">Security</a>
                <a class="navbar-item" href="users/login.php">Client</a>
                <a class="navbar-item" href="index.html">About</a>
                <a class="navbar-item btn btn-primary" href="#" id="surbtn" data-toggle="modal" data-target="#commentModal">
                  <i class="bi bi-chat-fill"></i></a>                
              <a class="navbar-item btn btn-primary" href="#" id="surbtn" data-toggle="modal" data-target="#feedbackModal">
                  <i class="bi bi-envelope-paper-heart"></i></a>
            </div>
        </div>
    </nav>

    <!-- Modal for Comment Section -->
<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="commentModalLabel">Comment Section</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content from users/comment.php will be loaded here -->
                <iframe src="users/comment.php" style="width:100%; height:500px; border:none;" title="Comments Section"></iframe>

            </div>
        </div>
    </div>
</div>

   <!-- Modal for Feedbacks -->
   <div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="feedbackModalLabel">Feedbacks</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">X</span>
                </button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Content from users/feedbaacks.php will be loaded here -->
                <iframe src="users/feedbacks.php" style="width:100%; height:500px; border:none;" title="Feedbacks"></iframe>

            </div>
        </div>
    </div>
</div>

    <!-- Masthead-->
    <header class="masthead">
        <div class="container">
            <!-- Masthead Avatar Image-->
            <img class="masthead-avatar" src="images/welcome.png" alt="Logo" />
            <!-- Masthead Heading-->
            <h1 class="masthead-heading">CTU DANAO PARKING SYSTEM</h1>
            <!-- Icon Divider-->
            <div class="divider-custom">
    <div class="divider-custom-icon">
        <div class="movcar">
            <img src="images/truck.png" alt="Truck" id="vehicleImage">
        </div>
    </div>
</div>

    </header>
    <!-- Copyright Section-->
    <div class="copyright">
        <div class="container"><marquee direction="left"><small> CTU Danao Parking System @ 2023</small></marquee></div>
    </div>
   
</body>
</html>
