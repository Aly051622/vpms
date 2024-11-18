<style>
    #header{
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
            rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
            rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    .nav-link:hover{
        border-radius: 4px;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    #hh{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        font: 16x;
           }
     /* logout message */
     .alert-message {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: #f9fcff;
        background-image: linear-gradient(147deg, #f9fcff 0%, #dee4ea 74%);
        border: solid 5px orange;
        color: red;
        font-weight: bold;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    }
    </style>
<div id="right-panel" class="right-panel">
<header id="header" class="header">
            <div class="top-left">
                <div class="navbar-header" style="background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);">
                    <a class="navbar-brand" href="dashboard.php"><img src="images/hclogo.png" alt="Logo" style=" width: 120px; height: auto;"></a>
                    <a class="navbar-brand hidden" href="./"><img src="images/logo3.png" alt="Logo"></a>
                    <a id="menuToggle" class="menutoggle"><i class="fa fa-bars"></i></a>
                </div>
            </div>
            <div class="top-right">
            <div class="header-menu">
                <div class="header-left">
                    <div class="form-inline">
                    </div>
                </div>

                <div class="user-area dropdown float-right">
                    <a href="#" class="dropdown-toggle active" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="user-avatar rounded-circle" src="images/images.png" alt="User Avatar">
                    </a>

                    <div class="user-menu dropdown-menu" id="hh">
                        <a class="nav-link" href="admin-profile.php"><i class="fa fa-user"></i>My Profile</a>
                        <a class="nav-link" href="change-password.php"><i class="fa fa-cog"></i>Change Password</a>
                        <a class="nav-link" href="../welcome.php" onclick="logout()"><i class="fa fa-power-off"></i> Logout</a>
                    </div>
                </div>
                <div class="alert-message" id="logout-alert" style="display: none;">
                    You have successfully logged out.
                </div>
            </div>
        </div>
        <script>
            // Function to handle the logout
            function logout() {
                // Perform any necessary logout actions here

                // Show the logout alert
                var alertMessage = document.getElementById("logout-alert");
                alertMessage.style.display = "block";

                // You can redirect to the login page or perform other actions after logout if needed
                // Example: window.location.href = "login.php";
            }
        </script>
        </header>