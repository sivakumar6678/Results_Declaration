<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Examination Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .section {
            display: inline-block;
            vertical-align: middle;
        }
        header{
            background-color: #c3f2ff;
            border: 2px solid aqua;
            box-shadow: 0 0 5px 1px black;
            }
        #collegeheading{
            margin: 2rem;
            justify-content: center;
            text-wrap: wrap;
            display: flex;
            /* align-items: center; */
        }
        .section img{
            width: 10rem;
            height: 10rem;
            filter: drop-shadow(0 0 0.50rem white);
        }
        .section h1{
            font-size: 2rem;
        }
        .section h2{
            font-size: 1.5rem;
        }
        .section h4{
            font-size: 1rem;
        }
        @media screen and (max-width: 840px) {
    .row>.col-lg-12{
        margin: 0;
        flex: 0 0 100%;
        max-width: 100%;
    }
    #collegeheading{
        padding: 0;
        text-wrap: wrap;
        display: flex;
        max-height: fit-content;
        float: left;
        text-wrap: wrap;

    }
    .section h1{
        font-size: 1rem;
    }
    .section h2{
        font-size: 1rem;
    }
    .section h4{
        font-size: 0.5rem;
    }
    .section img{
        width: 6rem;
        height:6rem;
    }
    
}

        .navbar-nav {
            /* background-color:gray; */
            font-size: 1.2rem;
            font-weight: 500;
            margin: 0 0 1rem 0;
            padding: 0.5rem 0 0.2rem 0;
            
        }
        nav ul li {
            padding: 0 1rem;
        }
        .logout{
            border-radius: 5px;
            background: #ff000099;
        }
        /* header{
            background-color: #c3f2ff;
            border: 2px solid aqua;
            box-shadow: 0 0 5px 1px black;
        } */
        #collegeheading{
            margin: 2rem;
            justify-content: center;
            text-wrap: wrap;
            display: flex;
            align-items: center; 
            /* background: #ff0000ed;  */
        }
    </style>
</head>
<body onload="toggleContent()">

    <header class="container-fluid" >
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center" id="collegeheading">
                    <div class="section">
                        <img src="../images/collegelogo.png" alt="JNTUACEK">
                    </div>
                    <div class="section">
                        <h1>JNTUA College of Engineering Kalikiri (Autonomous)</h1>
                        <h1>జవహర్‌లాల్ నెహ్రూ సాంకేతిక విశ్వవిద్యాలయం కలికిరి</h1>
                        <h4>Kalikiri, Annamayya Dist, Andhra Pradesh, India Pin : 517234</h4>
                        <h2 class="text-center">Examination Results</h2>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container-fluid navv">
        <nav class="navbar navbar-expand-lg ">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="admin.php">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <?php if(isset($_SESSION["admin"])) { ?>
                    <li class="nav-item logout">
                        <a class="nav-link" style="color:black;" href="logout.php">
                            <i class="fas fa-sign-out-alt"></i> Log Out
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item" >
                        <button  type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</body>
</html>
