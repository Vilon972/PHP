<?php

session_start();

$logine = "lazy";
$passwde = "lazy2";

if (isset($_POST["submit"]))
{
  $login = $_POST["login"];
  $passwd = $_POST["passwd"];

  if ($login && $passwd){
    if ($login == $logine && $passwd == $passwde) {
      $_SESSION["login"] = $login;
      header('Location: admin.php');
    }
    else {
        echo "Error";
    }
  }
  else
    echo "Veuillez remplir tout les champs";
}
 ?>

<html>
  <body>
    <form action="" method="post">
  <h2>Identifiant: <input type="text" name="login" /></h2>
     <br />
  <h2>Mot de Passe: <input type="passeword" name ="passwd" /></h2>
     <input type="submit" name="submit"/>
   </form>
 </body>
</html>
