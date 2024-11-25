<?php
session_start();
error_reporting(E_ALL); // Enable error reporting for debugging
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Manila');

include('../DBconnection/dbconnection.php');

if (strlen($_SESSION['vpmsaid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $catename = $_POST['catename'];
        $vehcomp = $_POST['vehcomp'];
        $model = $_POST['model'];
        $color = $_POST['color'];
        $vehreno = $_POST['vehreno'];
        $ownername = $_POST['ownername'];
        $ownercontno = $_POST['ownercontno'];

        if ($_POST['model'] === "Others, please specify") {
            $model = $_POST['otherModel'];
        }

        $imagePath = '';
        if ($vehcomp === 'Chevrolet') {
            if ($model === 'Tracker') {
                $imagePath = '../admin/vehicles/chevrolet/tracker.png';
            } elseif ($model === 'Trailblazer') {
                $imagePath = '../admin/vehicles/chevrolet/trailblazer.png';
            } elseif ($model === 'Suburban') {
                $imagePath = '../admin/vehicles/chevrolet/suburban.jpg';
            } elseif ($model === 'Corvette') {
                $imagePath = '../admin/vehicles/chevrolet/corvette.png';
            } elseif ($model === 'Tahoe') {
                $imagePath = '../admin/vehicles/chevrolet/tahoe.jpg';
            } elseif ($model === 'Trax') {
                $imagePath = '../admin/vehicles/chevrolet/trax.png';
            } elseif ($model === 'Captiva') {
                $imagePath = '../admin/vehicles/chevrolet/captiva.png';
            } elseif ($model === 'Camaro') {
                $imagePath = '../admin/vehicles/chevrolet/camaro.png';
            }
        } elseif ($vehcomp === 'Honda Motors') {
            if ($model === 'DIO') {
                $imagePath = '../admin/vehicles/honda motors/honda_dio.png';
            }
        }

        $checkPlateQuery = mysqli_query($con, "SELECT * FROM tblvehicle WHERE RegistrationNumber='$vehreno'");
        $plateExists = mysqli_num_rows($checkPlateQuery);

        if ($plateExists > 0) {
            echo "<script>document.addEventListener('DOMContentLoaded', function() { 
                    showModal('Plate Number already exists'); 
                });</script>";
        } else {
            $checkContactQuery = mysqli_query($con, "SELECT * FROM tblregusers WHERE MobileNumber='$ownercontno'");
            $userExists = mysqli_num_rows($checkContactQuery);

            if ($userExists > 0) {
                $userData = mysqli_fetch_assoc($checkContactQuery);
                $firstName = $userData['FirstName'];
                $lastName = $userData['LastName'];
                $contactno = $userData['MobileNumber'];

                 // Encrypt QR Code Data
$encryptionKey = getenv('ENCRYPTION_KEY');  // Fetch encryption key securely from environment variable
if (!$encryptionKey) {
    die("Encryption key not set in environment variables.");
}
$encryptionKey = base64_decode($encryptionKey);  // Decode the base64-encoded key

$cipher = "AES-256-CBC";  // Encryption method
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));  // Generate an initialization vector

// Generate random values for the QR code
$randomValues = [
    'VehicleType' => bin2hex(random_bytes(3)),  // Random string for vehicle type
    'PlateNumber' => bin2hex(random_bytes(5)),  // Random string for plate number
    'Name' => bin2hex(random_bytes(6)),        // Random string for name
    'ContactNumber' => bin2hex(random_bytes(4)), // Random string for contact number
    'Model' => bin2hex(random_bytes(4)),       // Random string for model
];

// Prepare data for encryption
$qrCodeData = json_encode($randomValues);

// Encrypt the data
$encryptedData = openssl_encrypt($qrCodeData, $cipher, $encryptionKey, 0, $iv);
if ($encryptedData === false) {
    die("Encryption failed.");
}

// Combine encrypted data and IV, then encode as base64
$encryptedQrCodeData = base64_encode($encryptedData . "::" . base64_encode($iv));

// Generate the QR code with the encrypted data
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($encryptedQrCodeData) . "&size=150x150";

// Save the generated QR code as an image
$qrImageName = "qr_" . uniqid() . ".png";  // Generate a unique name for the QR code image
$qrImagePath = "qrcodes/" . $qrImageName;
$qrCodeContent = file_get_contents($qrCodeUrl);
if ($qrCodeContent === false) {
    die("Failed to fetch QR code from the server.");
}

if (file_put_contents($qrImagePath, $qrCodeContent) === false) {
    die("Failed to save the QR code image.");
}

// Output the result
echo "QR code generated and saved as: " . $qrImagePath;


                $inTime = date('Y-m-d H:i:s');

                // Update INSERT query to include the ImagePath column
                $query = "INSERT INTO tblvehicle (VehicleCategory, VehicleCompanyname, Model, Color, RegistrationNumber, OwnerName, OwnerContactNumber, QRCodePath, ImagePath, InTime) 
                          VALUES ('$catename', '$vehcomp', '$model', '$color', '$vehreno', '$ownername', '$ownercontno', '$qrImagePath', '$imagePath', '$inTime')";

                if (mysqli_query($con, $query)) {
                    echo "<script>alert('Vehicle Entry Detail has been added');</script>";
                    echo "<script>window.location.href ='manage-reg.php'</script>";
                } else {
                    echo "<script>alert('Error: " . mysqli_error($con) . "');</script>";
                }
            } else {
                echo "<script>alert('Contact number not found in the user database. Please ensure the contact number is registered.');</script>";
            }
        }
    }
?>



<!doctype html>
<html class="no-js" lang="">
<head>
    
    <title>CTU- Danao Parking Management System- Add Vehicle</title>
   

    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
    <link rel="apple-touch-icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">
    <link rel="shortcut icon" href="https://upload.wikimedia.org/wikipedia/commons/9/9a/CTU_new_logo.png">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/normalize.css@8.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lykmapipo/themify-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pixeden-stroke-7-icon@1.2.3/pe-icon-7-stroke/dist/pe-icon-7-stroke.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.2.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <style>
         /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: whitesmoke; 
            padding-top: 60px; 
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    
    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->
    
    <script>
let modal;

function showModal(message) {
    modal = document.createElement('div');
    modal.className = 'modal';
    modal.innerHTML = `<div class="modal-content"><span class="close" onclick="closeModal()">&times;</span><p>${message}</p></div>`;
    document.body.appendChild(modal);
    modal.style.display = "block";
    window.onclick = e => { if (e.target === modal) closeModal(); };
}

function closeModal() {
    if (modal) {
        modal.style.display = "none";
        document.body.removeChild(modal);
        modal = null;
    }
}

function updateMakeBrandOptions() {
    const catename = document.getElementById("catename").value.trim();
    const vehcomp = document.getElementById("vehcomp");
    const otherMakeInput = document.getElementById("otherMake");
    vehcomp.innerHTML = '<option value="">Select Make/Brand</option>';
    const options = {
        "Two Wheeler Vehicle": ["Benelli", "CFMoto", "Honda Motors", "Kawasaki", "Kymco", "MotorStar", "Piaggio","Racal", "Rusi", "Suzuki Motors", "SYM", "TVS", "Yamaha", "Others, please specify"],
        "Four Wheeler Vehicle": ["Chevrolet", "Ford", "Honda", "Hyundai", "Isuzu", "Kia", "Lexus", "MG (Morris Garages)", "Mitsubishi", "Nissan", "Peugeot", "Subaru", "Suzuki", "Toyota", "Volkswagen", "Others, please specify"],
        "Bicycles": ["Battle", "Brusko", "Cannondale", "GT", "Hiland", "Kona", "Nakto", "RoyalBaby", "Others, please specify"]
    };

    if (options[catename]) {
        options[catename].forEach(make => {
            const option = document.createElement("option");
            option.value = make;
            option.text = make;
            vehcomp.appendChild(option);
        });
    }

    vehcomp.addEventListener("change", () => {
        otherMakeInput.style.display = vehcomp.value === "Others, please specify" ? "block" : "none";
    });
}

function updateModelOptions() {
    const vehcomp = document.getElementById("vehcomp").value;
    const model = document.getElementById("model");
    model.innerHTML = '<option value="">Select Model</option>';
    const models = {
        "Benelli": ["Benelli Leoncino 500", "Benelli TNT135", "Benelli 3025", "Others, please specify"],
        "CFMoto": ["CFMoto 300SR", "CFMoto 400NK", "CFMoto 650MT"],
        "Honda Motors": ["Click 125", "Click 125 (Special Edition)", "Honda Click 150i", "DIO","AirBlade160", "Beat (Playful)", "Beat (Premium)", "PCX160 - CBS", "PCX160 - ABS", "ADV160", "CBR150R", "CB150X", "Winner X (ABS Premium)", "Wave RSX (DISC)", "Wave RSX (Drum)", "Winner X (Standard)", "Winner X (ABS Premium)", "Winner X (ABS Racing Type)", "XRM125 DS", "XRM125 DSX", "XRM125 MOTARD", "RS125", "XR150L", "CRF150L", "CRF300L", "CRF300 Rally", "XL750 Transalp", "X-ADV", "NX500", "CRF1100L Africa Twin", "CRF1100L Africa Twin Adventure Sports", "EM1 e", "TMX125 Alpha", "TMX SUPREMO"],
        "Kawasaki": [ "Kawasaki Rouser NS200", "Kawasaki Rouser RS200", "Kawasaki Barako II", "Kawasaki CT100", "Kawasaki Dominar 400", "Kawasaki Ninja 400", "Kawasaki Ninja ZX-25R"],
        "Kymco": ["Kymco Super 8", "Kymco Xciting 300i", "Kymco AK550", "Kymco Like 150i"],
        "MotorStar": ["MotorStar MSX200-II", "MotorStar Xplorer X200R", "MotorStar Nicess 110"],
        "Piaggio": ["Piaggio Vespa Primavera", "Piaggio Vespa GTS"],
        "Rusi": ["Rusi Flash 150", "Rusi Mojo 200", "Rusi Classic 250"],
        "Suzuki Motors": ["Suzuki Raider R150", "Suzuki Skydrive", "Suzuki Burgman Street", "Suzuki Smash 115", "Suzuki GSX-R150", "Suzuki Gixxer"],
        "SYM": ["SYM Maxsym 400i", "SYM Bonus X", "SYM RV1-2"],
        "TVS": ["TVS Apache RTR 200", "TVS Apache RTR 160", "TVS Dazz", "TVS XL100"],
        "Racal" : ["Racal 115", "Racal King 175", "Racal Raptor 250", "Racal Classic 150", "Racal KRZ 150", "Racal Adventure 200", "Racal 160"],
        "Yamaha": ["Yamaha Mio Aerox", "Yamaha Mio Soul i 125", "Yamaha Mio i 125", "Yamaha Mio Sporty", "Yamaha NMAX", "Yamaha XMAX", "Yamaha FZi", "Yamaha YZF-R15", "Yamaha Sniper 155"],
        "Chevrolet": ["Colorado", "Spark", "Trailblazer", "Tracker", "Trax", "Tahoe", "Suburban", "Corvette", "Camaro", "Captiva"],
        "Ford": ["EcoSport", "Everest", "Mustang", "Ranger", "Expedition"],
        "Honda": ["Accord", "Brio", "Civic", "City", "CR-V", "HR-V", "Pilot"],
        "Hyundai": ["Accent", "Kona", "Santa Fe", "Tucson", "Elantra"],
        "Isuzu": ["Alterra", "D-Max", "MU-X", "N-Series (Truck)", "F-Series (Truck)"],
        "Kia": ["Carnival", "Picanto", "Rio", "Seltos", "Sportage"],
        "Lexus": ["ES", "NX", "RX"],
        "MG (Morris Garages)": ["MG6", "RX5", "ZS"],
        "Mitsubishi": ["Lancer", "Mirage", "Mirage G4", "Montero Sport", "Outlander", "Strada", "Xpander", "L300"],
        "Nissan": ["Almera", "Juke", "Livina", "Navara", "Patrol", "Terra", "X-Trail"],
        "Peugeot": ["308", "3008", "5008"],
        "Subaru": ["Forester", "Legacy", "Outback", "XV"],
        "Suzuki": ["Celerio", "Ertiga", "Jimny", "S-Presso", "Swift", "Vitara"],
        "Toyota": ["Corolla", "Fortuner", "Innova", "Land Cruiser", "RAV4", "Vios"],
        "Volkswagen": ["Golf", "Jetta", "Passat", "Tiguan"],
        "Battle": ["Battle Excellence-870 Mountain Bike"],
        "Brusko": ["Brusko Arrow Mountain Bike"],
        "Cannondale": ["Cannondale Trail 7 Mountain Bike"],
        "GT": ["GT Avalanche Elite Mountain Bike"],
        "Hiland": ["Hiland 26er Mountain Bike"],
        "Kona": ["Kona Lava Dome Mountain Bike"],
        "Nakto": ["Nakto Ranger Electric Bike"],
        "RoyalBaby": ["RoyalBaby Freestyle Kids Mountain Bike"]

    };

    if (models[vehcomp]) {
        models[vehcomp].forEach(modelOption => {
            const option = document.createElement("option");
            option.value = modelOption;
            option.text = modelOption;
            model.appendChild(option);
        });
    }
}
</script>

</head>
<body>
   <?php include_once('includes/sidebar.php');?>
    <!-- Right Panel -->

   <?php include_once('includes/header.php');?>

<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Add Vehicle</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="add-vehicle.php">Vehicle</a></li>
                            <li class="active">Add Vehicle</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">


        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    
                   
                </div> <!-- .card -->

            </div><!--/.col-->


            <!-- Plate Number Exists Modal -->
<div class="modal fade" id="plateExistsModal" tabindex="-1" role="dialog" aria-labelledby="plateExistsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="plateExistsModalLabel">Duplicate Plate Number</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The plate number you entered already exists in the database. Please check your entry.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

      

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong>Add </strong> Vehicle
                    </div>
                    <div class="card-body card-block">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            

                        <!-- Vehicle Category Dropdown (Type Vehicle) -->
<div class="row form-group">
<div class="col col-md-3"><label for="select" class="form-control-label">Vehicle Type</label></div>
<div class="col-12 col-md-9">
<select name="catename" id="catename" class="form-control" onchange="updateMakeBrandOptions()">
    <option value="0">Select Vehicle Type</option>
    <?php 
    $query = mysqli_query($con, "SELECT * FROM tblcategory");
    while ($row = mysqli_fetch_array($query)) { ?>    
        <option value="<?php echo $row['VehicleCat'];?>"><?php echo $row['VehicleCat'];?></option>
    <?php } ?> 
</select>
</div>
</div>

<!-- Make/Brand Dropdown (Make/Brand) - Initially empty -->
<div class="row form-group">
    <div class="col col-md-3"><label for="vehcomp" class="form-control-label">Make/Brand</label></div>
    <div class="col-12 col-md-9">
        <select id="vehcomp" name="vehcomp" class="form-control" required="true" onchange="updateModelOptions()">
            <option value="">Select Make/Brand</option>
            </select>

        <!-- Custom input for Make/Brand -->
<input type="text" id="otherMake" name="otherMake" class="form-control" placeholder="Please specify Make/Brand" style="display:none; margin-top:10px;">
</div>
</div>

<!-- Model Dropdown -->
<div class="row form-group">
    <div class="col col-md-3"><label for="model" class="form-control-label">Model</label></div>
    <div class="col-12 col-md-9">
        <select id="model" name="model" class="form-control" required="true">
            <option value="">Select Model</option>
        </select>
        
        <!-- Custom input for Model -->
<input type="text" id="otherModelInput" name="otherModel" class="form-control" placeholder="Please specify Model" style="display:none; margin-top:10px;">
</div>
</div>

        <!-- Color Input Field with Autocomplete -->
<div class="row form-group">
<div class="col col-md-3">
<label for="color" class="form-control-label">Color</label>
</div>
<div class="col-12 col-md-9">
<!-- Color input field with datalist for autocomplete -->
<input type="text" id="color" name="color" class="form-control" placeholder="Vehicle Color" required="true" list="colorList">
<!-- Datalist with predefined colors -->
<datalist id="colorList">
<option value="Black"></option>
<option value="White"></option>
<option value="Blue"></option>
<option value="Red"></option>
<option value="Green"></option>
<option value="Yellow"></option>
<option value="Gray"></option>
<option value="Silver"></option>
<option value="Orange"></option>
<option value="Pink"></option>
<option value="Purple"></option>
<option value="Brown"></option>
</datalist>
</div>
</div>

                         
                             <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Plate Number </label></div>
                                <div class="col-12 col-md-9"><input type="text" id="vehreno" name="vehreno" class="form-control" placeholder="Plate Number" required="true"></div>
                            </div>
                            <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Owner Name</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="ownername" name="ownername" class="form-control" placeholder="Owner Name" required="true"></div>
                            </div>
                             <div class="row form-group">
                                <div class="col col-md-3"><label for="text-input" class=" form-control-label">Owner Contact Number</label></div>
                                <div class="col-12 col-md-9"><input type="text" id="ownercontno" name="ownercontno" class="form-control" placeholder="Owner Contact Number" required="true" maxlength="11" pattern="[0-9]+"></div>
                            </div>
                           
                            
                            
                           <p style="text-align: center;"> <button type="submit" class="btn btn-primary btn-sm" name="submit" ><i class="fa fa-plus"> Add</i></button></p>
                        </form>
                            </div>
                            
                        </div>
                        
                    </div>

                    <div class="col-lg-6">
                        
                  
                </div>

           

            </div>


        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>


</div><!-- /#right-panel -->

<!-- Right Panel -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
<script src="assets/js/main.js"></script>


</body>
</html>
<?php }  ?>