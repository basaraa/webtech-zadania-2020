<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Uploadovanie súborov</title>
    <link rel="shortcut icon" type="image/jpg" href="img/favicon.ico"/>
    <link rel="stylesheet" href="styles/Styles.css">
</head>
<body>
<header>
    <nav class="navs">
        <ul class="navs_">
            <li><a href="index.php">Domov</a></li>
            <li><a href="upload_file.php">Upload</a></li>
        </ul>
    </nav>
    <hr>
</header>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="NazovSuboru">Vyber súbor, ktorý chceš nahrať na server:</label><br>
    <input type="file" name="Upload_suboru" id="Upload_suboru" required><br>
    <label for="NazovSuboru">Zadaj názov súboru, pod ktorým ho chceš uložiť na server:</label><br>
    <input type="text" name="NazovSuboru" id="NazovSuboru" required><br>
    <input type="submit" value="Upload" name="submit" id="submit_uploadu">
</form>
</body>
</html>
