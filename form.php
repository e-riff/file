<?php

session_start();

if (!isset($_SESSION['idInfos'])) {
    $_SESSION['idInfos'] = [];
}
if (!isset($_SESSION['idErrors'])) {
    $_SESSION['idErrors'] = [];
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $_SESSION['idErrors'] = [];
    $_SESSION['idInfos'] = [];

    $_SESSION['idInfos'] = array_map('trim', $_POST);
    $_SESSION['idInfos'] = array_map('htmlentities', $_SESSION['idInfos']);
    foreach ($_SESSION['idInfos'] as $type => $info) {
        if (empty($info)) {
            $_SESSION['idErrors'][$type] = "Missing Data";
        }
    }


    $errors = array();
    $uploadDir = './Received/';

    $_SESSION['idInfos']['uploadFile'] = $uploadDir . uniqid();
    move_uploaded_file($_FILES['idPicture']['tmp_name'], $_SESSION['idInfos']['uploadFile']);

    $extension = pathinfo($_FILES['idPicture']['name'], PATHINFO_EXTENSION);
    $authorizedExtensions = ['jpg', 'gif', 'png', 'webp'];
    $maxFileSize = 1000000;

    if ((!in_array($extension, $authorizedExtensions))) {
        $_SESSION['idErrors']['extension'] = 'Veuillez sélectionner une image de type Jpg, gif, webp ou Png !';
    }

    if (file_exists($_FILES['idPicture']['tmp_name']) && filesize($_FILES['idPicture']['tmp_name']) > $maxFileSize) {
        $_SESSION['idErrors']['taille'] = "Votre fichier doit faire moins de 1M";
    }
    header("Location: form.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://the.missing.style/v1.0.0/missing.min.css">
    <link rel="stylesheet" href="style.css">
    <title>ID</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <label for="name">First Name :</label>
            <input type="text" id="firstname" name="fname" placeholder="John">

            <label for="name">Last Name :</label>
            <input type="text" id="lastname" name="lname" placeholder="Doe">

            <label for="adress">Adress :</label>
            <input type="text" id="adress" name="adress" placeholder="85 Donuts Street, Baltimore">

            <label for="phone">Phone number :</label>
            <input type="tel" id="phone" name="phone" placeholder="0836656565">

            <label for="birthDate">Birth Date :</label>
            <input type="date" id="birthDate" name="birthDate" placeholder="01/01/2001">

            <label for=" imageUpload">Choose your ID picture (png, gif, webp, jpg - max 1mo)</label>
            <input type="file" name="idPicture" id="imageUpload" />

            <button type=" submit">Create your ID</button>
        </div>
    </form>

    <?php
    if (isset($_SESSION['idInfos'])) {
        if (empty($_SESSION['idErrors']) && !empty($_SESSION['idInfos'])) { ?>
            <div class="card">
                <img src="<?= $_SESSION['idInfos']['uploadFile'] ?>" alt="Avatar" style="width:100%">
                <div class="container">
                    <h3><b></b><?= $_SESSION['idInfos']['lname'] . " " . $_SESSION['idInfos']['fname'] ?> </b></h3>
                    <h4><?= $_SESSION['idInfos']['adress'] ?></h4>
                    <h4><?= $_SESSION['idInfos']['birthDate'] ?></h4>
                    <h4><?= $_SESSION['idInfos']['phone'] ?></h4>
                </div>
            </div>
        <?php unset($_SESSION);
        } else if (!empty($_SESSION['idErrors'])) { ?>
            <h1>Il va falloir recommencer !!!</h1>
            <p> Tu as fait les erreurs suivantes :</p>
            <ul>
                <?php
                foreach ($_SESSION['idErrors'] as $probleme => $error) {
                    echo "<li><strong>" . $probleme . " :</strong> " . $error . "</li>";
                }
                ?>
            </ul>
    <?php }
    } ?>

</body>

</html>