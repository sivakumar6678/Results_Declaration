<?php
include ('topmenu.php');
?>
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
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

    </style>
</head>
<body >
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Admin Login</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <form name="loginForm" method="post" action="adminlogin.php" onsubmit="return validateForm()">
                                <!-- <legend>Admin Login</legend> -->
                                <label for="username">Username:</label>
                                <input type="text" name="username" class="form-control" placeholder="Username" maxlength="10">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" placeholder="Password" maxlength="10">
                                <!-- <div class="g-recaptcha" data-sitekey="6Lc6QqQaAAAAAFCZ4Z4Z4Z4Z4Z4Z4Z4Z4Z4Z4Z4Z"></div> --><br>
                                <input type="submit" name="submit" value="Login" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
