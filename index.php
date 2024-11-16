<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" contaent="width=device-width, initial-scale=1">
    <title>Welcome to CTU Danao Parking System</title>
    <link rel="stylesheet" href="styles/welcome.css">
    <link rel="apple-touch-icon" href="images/ctu.png">
    <link rel="shortcut icon" href="images/ctu.png">
</head>

<style>
    body {
        background-image: linear-gradient(to top, #1e3c72 0%, #1e3c72 1%, #2a5298 100%);
        overflow: hidden;
        font-family: 'Open Sans', sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }

    .loop-wrapper {
        margin: 0 auto;
        position: relative;
        display: block;
        width: 600px;
        height: 250px;
        overflow: hidden;
        border-bottom: 3px solid orange;
        color: #fff;
    }

    .mountain {
        position: absolute;
        right: -900px;
        bottom: -20px;
        width: 2px;
        height: 2px;
        box-shadow:
            0 0 0 50px orange,
            60px 50px 0 70px orange,
            90px 90px 0 50px orange,
            250px 250px 0 50px orange,
            290px 320px 0 50px orange,
            320px 400px 0 50px orange;
        transform: rotate(130deg);
        animation: mtn 20s linear infinite;
    }

    .hill {
        position: absolute;
        right: -900px;
        bottom: -50px;
        width: 400px;
        border-radius: 50%;
        height: 20px;
        box-shadow:
            0 0 0 50px orangered,
            -20px 0 0 20px orangered,
            -90px 0 0 50px orangered,
            250px 0 0 50px orangered,
            290px 0 0 50px orangered,
            620px 0 0 50px orangered;
        animation: hill 4s 2s linear infinite;
    }

    .tree,
    .tree:nth-child(2),
    .tree:nth-child(3) {
        position: absolute;
        height: 100px;
        width: 35px;
        bottom: 0;
        background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/130015/tree.svg) no-repeat;
    }

    .rock {
        margin-top: -17%;
        height: 2%;
        width: 2%;
        bottom: -2px;
        border-radius: 20px;
        position: absolute;
        background: #ff0000;
    }

    .truck,
    .wheels, .locator {
        transition: all ease;
        width: 85px;
        margin-right: -60px;
        bottom: 0px;
        right: 50%;
        position: absolute;
        background: #ff0000;
    }
    .locator {
        background: url(images/locator.png) no-repeat;
        background-size: contain;
        height: 139px;
    }
    .locator:before {
        position: absolute;
        width: 25px;
        box-shadow: -30px 28px 0 1.5px #fff, -35px 18px 0 1.5px #fff;
    }
    .truck {
    background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/130015/truck.svg) no-repeat;
    background-size: contain;
    height: 60px;
    filter: brightness(0) invert(1); /* Convert to white */
}


    .truck:before {
        content: " ";
        position: absolute;
        width: 25px;
        box-shadow: -30px 28px 0 1.5px #fff, -35px 18px 0 1.5px #fff;
    }

    .wheels {
        background: url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/130015/wheels.svg) no-repeat;
        height: 15px;
        margin-bottom: 0;
    }

    .tree {
        animation: tree 3s 0.000s linear infinite;
    }

    .tree:nth-child(2) {
        animation: tree2 2s 0.150s linear infinite;
    }

    .tree:nth-child(3) {
        animation: tree3 8s 0.050s linear infinite;
    }

    .rock {
        animation: rock 4s -0.530s linear infinite;
    }

    .truck {
        animation: truck 4s 0.080s ease infinite;
    }

    .wheels {
        animation: truck 4s 0.001s ease infinite;
    }

    .truck:before {
        animation: wind 1.5s 0.000s ease infinite;
    }

    @keyframes tree {
        0% {
            transform: translate(1350px);
        }

        50% {}

        100% {
            transform: translate(-50px);
        }
    }

    @keyframes tree2 {
        0% {
            transform: translate(650px);
        }

        50% {}

        100% {
            transform: translate(-50px);
        }
    }

    @keyframes tree3 {
        0% {
            transform: translate(2750px);
        }

        50% {}

        100% {
            transform: translate(-50px);
        }
    }

    @keyframes rock {
        0% {
            right: -200px;
        }

        100% {
            right: 2000px;
        }
    }

    @keyframes truck {
        0% {}

        6% {
            transform: translateY(0px);
        }

        7% {
            transform: translateY(-6px);
        }

        9% {
            transform: translateY(0px);
        }

        10% {
            transform: translateY(-1px);
        }

        11% {
            transform: translateY(0px);
        }

        100% {}
    }

    @keyframes wind {
        0% {}

        50% {
            transform: translateY(3px);
        }

        100% {}
    }

    @keyframes mtn {
        100% {
            transform: translateX(-2000px) rotate(130deg);
        }
    }

    @keyframes hill {
        100% {
            transform: translateX(-2000px);
        }
    }
</style>

<body>
    <div class="loop-wrapper">
        <div class="mountain"></div>
        <div class="hill"></div>
        <div class="tree"></div>
        <div class="tree"></div>
        <div class="tree"></div>
        <div class="rock"></div>
        <div class="truck"></div>
        <div class="locator"></div>
        <div class="wheels"></div>
    </div>

    <script>
    // Function to redirect to welcome.php
    function redirectToIndex() {
        // Add fade-out effect
        document.body.style.transition = "opacity 2s";
        document.body.style.opacity = 0;

        // Redirect to welcome.php after 2 seconds (adjust timing as needed)
        setTimeout(function () {
            window.location.href = "welcome.php";
        }, 2000);
    }

    // Set a timeout to redirect after 5 seconds
    setTimeout(function () {
        redirectToIndex();
    }, 5000);
</script>

</body>

</html>
