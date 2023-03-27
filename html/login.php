    <?php
    if(isset($_COOKIE["logged"])){
        echo "cookie";
    }
    ?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="row space" style="margin-top:3%"></div><!--spazio superiore-->
        <div class="row">
            <div class="col-xxl-4 col-lg-3 col-md-3 col-sm-2 col-1"></div> <!--spazio sx-->
            <!--<div class="col-lg-4 gx-lg-0 col-md-7 gx-1 col-sm-8 col-9">-->
            <div class="col-xxl-4 col-lg-6 col-md-6 col-sm-8 col-10">
                <?php
                    session_start();
                    if(isset($_SESSION["error"])){?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button class="btn-close" aria-label="close" data-bs-dismiss="alert"></button>
                            <img src="../icons/error.png" width="40px">
                            <hr>
                        <?php 
                            echo $_SESSION["error"]["message"];
                            unset($_SESSION["error"]);?>
                        </div>
                    <?php } ?>
                <img src="../icons/logo.png" class="logo-header">
                <div class="card text-center">
                    <div class="card-header fw-bold text-white fs-4">
                        Sing In <img src="../icons/login.png" width="23px">
                    </div>
                    <div class="card-body">
                        <div class="card-text">
                            <form action="../php/validateLogin.php" method="post">
                                <div class="input-group">
                                    <div class="input-group-text">
                                        <img src="../icons/username.png" width="23px">
                                    </div>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="username" id="username" placeholder=" "
                                        readonly onfocus="this.removeAttribute('readonly')">
                                        <!--è presente il js per il readonly per un bug tra bootstrap e chrome-->
                                        <label for="username" class="form-label">Username</label>
                                    </div>
                                </div>

                                <br><br>

                                <div class="input-group">
                                    <div class="input-group-text">
                                        <img src="../icons/password.png" width="23px">
                                    </div>
                                    <div class="form-floating">
                                        <input type="password" class="form-control" name="password" id="password" placeholder=" "
                                        readonly onfocus="this.removeAttribute('readonly')">
                                        <!--è presente il js per il readonly per un bug tra bootstrap e chrome-->
                                        <label for="password" class="form-label">Password</label>
                                    </div>
                                </div>



                                <br><br>
                                <input type="submit" class="btn btn-red fw-bold" value="Login">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xxl-4 col-lg-3 col-md-3 col-sm-2 col-1"></div><!--spazio dx-->
        </div>
    </div>
</body>
</html>