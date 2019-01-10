<?php
//session_start();
$bdd = mysqli_connect('localhost', 'root', 'lazy972', 'produit');
  if (!$bdd) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['show'])) {

  $product = $_GET['show'];
  $sql = "SELECT * FROM products WHERE title = '" . mysqli_escape_string($bdd, $product) . "';";
  $result = mysqli_query($bdd, $sql);
  while ($donnees = mysqli_fetch_assoc($result)) {
    $description = $donnees['description'];
    $description_finale = wordwrap($description, 100, '<br />', true);
    ?>
    <br/><div style="text-align:center;">
    <img src="../Admin/imgs/<?php echo $donnees['title']; ?>.jpg" />
    <h1><?php echo $donnees['title']; ?></h1>
    <h5><?php echo $description_finale; ?></h5>
  </div><br/>
    <?php
}

}

else {


if (isset($_GET['category'])) {
  $category = $_GET['category'];
  $sql = "SELECT * FROM products WHERE category = '" . mysqli_escape_string($bdd, $category) . "';";
  $result = mysqli_query($bdd, $sql);

  while ($donnees = mysqli_fetch_assoc($result)) {
    $lenght = 50;
    $description = $donnees['description'];
    $new_description = substr($description, 0, $lenght)."...";
    $description_finale = wordwrap($new_description, 40, '<br />', true);
    ?>

    <a href="?show=<?php echo $donnees['title'];?>"><img src="../Admin/imgs/<?php echo $donnees['title']; ?>.jpg" /></a>
    <a href="?show=<?php echo $donnees['title'];?>"<h2><?php echo $donnees['title'];?></h2></a>
    <h5><?php echo $description_finale;?></h2>
    <h6><?php echo $donnees['price'];?></h2>
    <a href="">Ajouter au Panier</a>
    <br/><br/>
    <?php
  }

}
else {


$sql = "SELECT * FROM category";
$result = mysqli_query($bdd, $sql);

while ($donnees = mysqli_fetch_assoc($result))
{
  ?>
  <a href="?category=<?php echo $donnees['name'];?>"><h3> <?php echo $donnees['name'];  ?></h3></a>
  <?php
}
}
}
 ?>
