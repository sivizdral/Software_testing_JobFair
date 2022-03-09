<html>  
    <head>
        <title>JobFair</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="bootstrap-4.2.1/css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" href="img/jf19icon.ico">
<!--    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>-->
        <script src="bootstrap-4.2.1/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="bootstrap-4.2.1/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="bootstrap-4.2.1/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <script type="text/javascript"  src="js/funkcije.js"></script>
        <script type="text/javascript"  src="js/funkcijeCV.js"></script>
        <script type="text/javascript"  src="js/kompanijaFunkcije.js"></script>
        <script type="text/javascript"  src="js/funkcijeAdmin.js"></script>


    </head>
    <body>
        <div class="container" id='glavniKont'>

            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">

                <a class="navbar-brand" href="index.php"><img style='height:50px;'src='img/jf19.png'/></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Početna </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pretragaKompanija.php">Pretraga kompanija</a>
                        </li>

                        <?php
                        if (!isset($_SESSION))
                            session_start();

                        if (isset($_SESSION['tip'])) {
                            require_once 'inc/header_funkcija.php';
                            if ($_SESSION['tip'] == 1)
                                headerStudent();
                            elseif ($_SESSION['tip'] == 2)
                                headerKompanija();
                            elseif ($_SESSION['tip'] == 3)
                                headerAdmin();
                            ?>
                            <li class="nav-item desno">
                                <a class="nav-link" href="logout.php"><?php echo $_SESSION['korisnik']; ?> (Logout)</a>
                            </li>
                            <?php
                        }
                        ?>

                    </ul>

                </div>
            </nav>





