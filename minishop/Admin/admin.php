<?php
//session_start();
?>

<h1>Bienvenue</h1>
<a href="?action=add">Ajouter un produit</a>
<a href="?action=modifyanddelete">Modifier / supprimer un produit</a>

<a href="?action=add_category">Ajouter une categorie</a>

<?php

$bdd = mysqli_connect('localhost', 'root', 'lazy972', 'produit');
  if (!$bdd) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_SESSION["login"])){
  if(isset($_GET["action"])){
    if ($_GET["action"] == "add"){
      if (isset($_POST["submit"])) {
        $Nom = $_POST["Nom"];
        $category = $_POST['category'];
        $Description = $_POST["Description"];
        $Prix = $_POST["Prix"];
        $img = $_FILES['img']['name'];
        $img_tmp = $_FILES['img']['tmp_name'];
        if (!empty($img_tmp)) {
          $image = explode('.', $img);
          $image_ext = end($image);

          if (in_array(strtolower($image_ext), array('png', 'jpg', 'jpeg')) == false) {
            echo "Veuillez rentrer une image ayant pour extension : png, jpg ou jpeg";
          }
          else {
            $image_size = getimagesize($img_tmp);
            if ($image_size['mime'] == 'image/jpeg'){
              $image_src = imagecreatefromjpeg($img_tmp);
            }
            else if ($image_size['mime'] == 'image/png'){
              $image_src = imagecreatefrompng($img_tmp);
            }
            else {
              $image_src = false;
              echo "Veuillez renter une image valide";
            }
            if ($image_src !== false) {
              $image_width = 200;
              if ($image_size[0] == $image_width){
                $image_finale = $image_src;
              }
              else {
                $new_width[0] = $image_width;
                $new_height[1] = 200;
                $image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);
                imagecopyresampled($image_finale, $image_src, 0, 0, 0, 0, $new_width[0], $new_height[1], $image_size[0], $image_size[1]);
              }
              imagejpeg($image_finale, 'imgs/' .$Nom. '.jpg');
            }
          }
        }
        else {
          echo "Veuillez rentrée une image";
        }
        if ($Nom && $Description && $Prix){
          $sql = "INSERT INTO products (title, description, price, category) VALUES ('$Nom', '$Description', '$Prix', '$category')";
          if (mysqli_query($bdd, $sql)) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($bdd);
          }
          //mysqli_close($bdd);
        }
        else {
          echo "Veuillez remplir tout les chanps";
        }
      }
?>
  <form action="" method="post" enctype="multipart/form-data">
    <h3>Nom du Produit :<input type="text" name="Nom" /></h3>
    <h3>Description du produit :<textarea name="Description" ></textarea></h3>
    <h3>Prix : <input type="text" name="Prix" /></h3>
    <h3>Image :</h3>
    <input type="file" name="img"/><br/><br/><br/>
    <h3>Categorie :</h3><select name="category">
      <?php $sql = "SELECT * FROM category";
            $result = mysqli_query($bdd, $sql);
            while ($donnees = mysqli_fetch_assoc($result)){
              ?>
              <option><?php echo $donnees['name']; ?></option>
              <?php
            }
      ?>
    </select><br/><br/>
    <input type="submit" name="submit" />
  </form>
<?php
  }
  else if ($_GET["action"] == "modifyanddelete"){
    $sql = "SELECT * FROM products";
    $result = mysqli_query($bdd, $sql);
    while ($donnees = mysqli_fetch_assoc($result))
    {
      echo $donnees['title'];
      echo "\n";
      ?>
      <a href="?action=modify&amp;id=<?php echo $donnees['id'] ?>">Modifier</a>
      <a href="?action=delete&amp;id=<?php echo $donnees['id'] ?>">x</a><br/><br/>
      <?php
    }
    //mysqli_close($bdd);
  }
  else if ($_GET["action"] == "modify"){

    if( isset( $_GET['id'])) {
      $id = $_GET['id'];
    }
    $sql = "SELECT * FROM products WHERE id =". $id;
    $result = mysqli_query($bdd, $sql);
    while ($donnees = mysqli_fetch_assoc($result)) {
    ?>
    <form action="" method="post">
      <h3>Nom du Produit : <input value="<?php echo $donnees['title']?>" type="text" name="Nom" /></h3>
      <h3>Description du produit : <textarea name="Description" ><?php echo $donnees['description']?></textarea></h3>
      <h3>Prix : <input value="<?php echo $donnees['price']?>" type="text" name="Prix" /></h3>
      <input type="submit" name="submit" />
    </form>
    <?php
    if (isset($_GET['submit'])) {
      $id = $_GET['id'];
      $Nom = $_POST["Nom"];
      $Description = $_POST["Description"];
      $Prix = $_POST["Prix"];
      $sql = "UPDATE products SET title = '".$Nom."', description = '".$Description."', price = '".$Prix."' WHERE id ='". $id."'";
      $result = mysqli_query($bdd, $sql);
      echo "yoyo";
    }

    //mysqli_close($bdd);
  }
  }

  else if ($_GET["action"] == "delete") {
    // $id = (isset($_GET['id']));
    // echo $id;
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    }
    $sql = "DELETE FROM products WHERE id=".$id;
    echo $sql;
    if ($result = mysqli_query($bdd, $sql))
    {
      echo "suprimer";
    }
    mysqli_close($bdd);
  }

  else if($_GET['action'] == 'add_category'){
    if (isset($_POST['submit'])){
      $name = $_POST['name'];

      if ($name) {
        $sql = "INSERT INTO category (name) VALUES ('$name')";
        if (mysqli_query($bdd, $sql)) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($bdd);
        }
      }
      else {
        echo "Veuillez remplir tous les champs";
      }
    }
    ?>
    <form action="" method="post">
      <h3>Titre de la category :<input type="text" name="name"/> <br/><br/>
      <input type="submit" name="submit" value="Ajouter"/>
    <?php
  }

  else {
    echo "Une erreur s\'est produite.";
    exit ();
  }
}
  else
  {

  }


}
  else
  {
    header ('location: index.php');
  }
?>


<?php
?>
