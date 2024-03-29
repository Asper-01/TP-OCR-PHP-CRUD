<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php'; // On inclut la connexion à la bdd

$regex = "#^[a-z0-9][a-z0-9]*[-]?[a-z0-9]*[a-z0-9]+$#";

// Si les champs du formulaire ont été remplis :
if (!empty($_POST['pseudo']) && !empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['adresse']) && !empty($_POST['code_postal']) && !empty($_POST['ville']) && !empty($_POST['password']) && !empty($_POST['password_retype'])) {
    // Patch XSS
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $code_postal = htmlspecialchars($_POST['code_postal']);
    $ville = htmlspecialchars($_POST['ville']);
    $password = htmlspecialchars($_POST['password']);
    $password_retype = htmlspecialchars($_POST['password_retype']);

    var_dump($_POST); // Ajout du var_dump pour le débogage

    // On vérifie si l'utilisateur existe déjà
    $check = $bdd->prepare('SELECT nom, email, password FROM user WHERE email = ?');
    $check->execute(array($email));
    $data = $check->fetch();
    $row = $check->rowCount();
    $email = strtolower($email); // On transforme toutes les lettres majuscules en minuscules

    // Si la requête renvoie un 0 alors l'utilisateur n'existe pas
    if ($row == 0) {
        if (strlen($pseudo) <= 15) { // On vérifie que la longueur du pseudo <= 20
            if (strlen($pseudo) >= 3) { // On vérifie que la longueur du pseudo >= 3
                if (!empty($pseudo)) {
                    if (!preg_match("#^[a-z0-9][a-z0-9]*[-]?[a-z0-9]*[a-z0-9]$#i", "{($pseudo)}")) {
                        if (!empty($nom)) {
                            if (!empty($prenom)) {
                                if (strlen($email) <= 25) { // On vérifie que la longueur du mail <= 25
                                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) { // Si l'email est de la bonne forme
                                        if (!empty($adresse)) {
                                            if (!empty($code_postal)) {
                                                if (!empty($ville)) {
                                                    if ($password === $password_retype) { // Si les deux mots de passe saisis sont bons

                                                        // On hash le mot de passe avec Bcrypt, via un coût de 12
                                                        $cost = ['cost' => 12];
                                                        $password = password_hash($password, PASSWORD_BCRYPT, $cost);

                                                        // On stocke l'adresse IP :
                                                        $ip = $_SERVER['REMOTE_ADDR'];

                                                        // On insère dans la Bdd :
                                                        $insert = $bdd->prepare('INSERT INTO user(pseudo, nom, prenom, email, adresse, code_postal, ville, password, ip, token) VALUES(:pseudo, :nom, :prenom, :email, :adresse, :code_postal, :ville, :password, :ip, :token)');
                                                        $insert->execute(array(
                                                            'pseudo' => $pseudo,
                                                            'nom' => $nom,
                                                            'prenom' => $prenom,
                                                            'email' => $email,
                                                            'adresse' => $adresse,
                                                            'code_postal' => $code_postal,
                                                            'ville' => $ville,
                                                            'password' => $password,
                                                            'ip' => $ip,
                                                            'token' => bin2hex(openssl_random_pseudo_bytes(64))
                                                        ));
                                                        header('Location:inscription.php?reg_err=success');
                                                        die();
                                                    } else {
                                                        header('Location: inscription.php?reg_err=password');
                                                        die();
                                                    }
                                                } else {
                                                    header('Location: inscription.php?reg_err=ville_preg');
                                                    die();
                                                }
                                            } else {
                                                header('Location: inscription.php?reg_err=code_postal_preg');
                                                die();
                                            }
                                        } else {
                                            header('Location: inscription.php?reg_err=adresse_preg');
                                            die();
                                        }
                                    } else {
                                        header('Location: inscription.php?reg_err=email');
                                        die();
                                    }
                                } else {
                                    header('Location: inscription.php?reg_err=email_length');
                                    die();
                                }
                            } else {
                                header('Location: inscription.php?reg_err=prenom_preg');
                                die();
                            }
                        } else {
                            header('Location: inscription.php?reg_err=nom_preg');
                            die();
                        }
                    } else {
                        header('Location: inscription.php?reg_err=pseudo_preg');
                        die();
                    }
                }
            } else {
                header('Location: inscription.php?reg_err=pseudo_min_length');
                die();
            }
        } else {
            header('Location: inscription.php?reg_err=pseudo_length');
            die();
        }
    } else {
        header('Location: inscription.php?reg_err=already');
        die();
    }
}
?>