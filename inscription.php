<?php
include 'config.php';
include 'header.php';

?>

<body>

    <body class="d-flex flex-column">
        <div class="container-fluid">
            <div class="login-form">
                <?php //Les messages d'erreur sont générés en HTML et les classes d'alerte du framework Bootstrap CSS sont utilisées pour les mettre en forme
                if (isset($_GET['reg_err'])) { //Verification des données envoyées en GET:
                    $err = htmlspecialchars($_GET['reg_err']);

                    switch ($err) {
                        case 'success': //Si pas d'erreurs:
                ?>
                            <div class="alert alert-success text-center">
                                <strong>Félicitations !</strong> Votre compte a été crée !
                            </div>
                            <div class="text-center mb-3">
                                <a href="form_co.php">
                                    <button class="btn btn-primary btn-lg">Je me connecte</button>
                                </a>

                            </div>
                        <?php
                            break;

                        case 'password': // Si erreurs de password:
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> mot de passe différent
                            </div>
                        <?php
                            break;

                        case 'email': // Si erreurs de mail:
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> email non valide
                            </div>
                        <?php
                            break;

                        case 'email_length':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> adresse mail trop longue
                            </div>
                        <?php
                            break;

                        case 'pseudo_length':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> pseudo trop long
                            </div>

                        <?php
                        case 'reg_err=pseudo_preg':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> Votre pseudo ne doit contenir que des caractères alphanumérique (A-Z, a-z, 0-9)
                            </div>

                        <?php
                        case 'pseudo_min_length':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> pseudo doit contenir au moins 3 caractères
                            </div>
                        <?php
                        case 'already':
                        ?>
                            <div class="alert alert-danger">
                                <strong>Erreur</strong> compte deja existant
                            </div>
                <?php

                    }
                }
                ?>
                <form action="inscription_traitement.php" method="post">
                    <h2 class="text-center">Inscription</h2>
                    <div class="form-group">
                        <input type="text" name="pseudo" class="form-control" placeholder="Pseudo" required="required" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Email" required="required" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Mot de passe" required="required" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password_retype" class="form-control" placeholder="Re-tapez le mot de passe" required="required" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Inscription</button>
                    </div>
                    <a href="index.php" class="btn btn-danger btn-block">Retour à l'acceuil</a>

                </form>
            </div>



            <?php include "footer.php"; ?>