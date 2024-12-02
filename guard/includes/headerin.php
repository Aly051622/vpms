<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
     .container{
        padding-top:10px;
        margin-top:-8px;
    }
    .navbar{
        background-color: #1e3c72; /* Solid blue background */
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
    box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 6px; /* Subtle shadow */
    }

    .navbar a {
    text-decoration: none;
    color: white;
    font-size: 1.2em;
    font-weight: bold;
    margin-right: 15px;
}

.navbar a:hover {
    color: orange; /* Highlight effect */
}
    /*qrbutton add css*/
    .dropbtns{
            color: white;
            padding: 8px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background-color: orange;
            border-radius: 9px;
            font-weight: bold;
            border: solid;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .dropbtns:hover{
            background-color: white;
            color: orange;
            border: solid orange;
        }
        .navbar-toggler {
    font-size: 1.5em;
    color: white;
    cursor: pointer;
    display: none; /* Hidden by default */
    border: none;
    background: none;
}
    @media (max-width: 480px){
    .container{
        padding-top:10px;
        margin-top:-8px;
    }
    .navbar-brand{
        margin-left: 10px;
    }
    .navbar-toggler{
        margin-top: -33px;
        margin-left: 11em;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .navbar-toggler {
        display: block; /* Show toggler on small screens */
    }

    .navbar-menu {
        display: none; /* Hidden by default */
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
        background-color: #1e3c72;
        padding: 10px 0;
    }

    .navbar-menu a {
        margin: 10px 20px;
    }

    .navbar-menu.show {
        display: flex; /* Show menu when toggled */
    }
}
</style>

<nav class="navbar">
<div class="container">
    <div class="navbar-brand"><a href="monitor.php">Parking Slot Manager</a></div>
    <div class="navbar-toggler" onclick="toggleMenu()">&#9776;</div>
    <div class="navbar-menu" id="navbarMenu">
        <!-- QR Login Button -->
        <a href="qrlogin.php" class="navbar-item dropbtns"><i class="bi bi-car-front-fill"></i> QR Log-in</a>
      

        <!-- Manual Input Button -->
        <a href="malogin.php" class="navbar-item dropbtns"><i class="bi bi-display-fill"></i> Manual Log-in</a>

        <a href="logout.php" class="navbar-item dropbtns"><i class="bi bi-house-fill"></i> Home</a>
       
    </div>
</div>
</nav>
