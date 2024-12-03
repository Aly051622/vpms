<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Driver's License</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: whitesmoke;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: rgba(9, 30, 66, 0.25) 0px 1px 1px, rgba(9, 30, 66, 0.13) 0px 0px 1px 1px;            
            width: 500px;
            margin: 20px auto; 
        }
        h2 {
            font-family: 'Poppins', sans-serif;
            color: black;
            font-size: 30px;
            text-align: center;
        }
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid gray;
            border-radius: 7px;
            font-size: 14px;
            font-family: 'Poppins', sans-serif;
        }
        #submit {
            width: 100%;
            padding: 10px;
            border-radius: 9px;
            background-color: rgb(53, 97, 255);
            color: white;
            font-weight: bold;
            border: 2px solid white;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, 
                        rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, 
                        rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #submit:hover {
            background-color: darkblue;
            border: 2px solid darkblue;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update Driver's License</h2>
        <form action="upload.php" method="POST">
            <label for="email">Enter your email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required><br>

            <label for="license_date">Enter License Expiration Date:</label>
            <input type="date" id="license_date" name="license_date" required><br>

            <button type="submit" id="submit">Submit</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
