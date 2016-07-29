<?php
    require('database.php');

    if(!empty($_GET['id'])) {
        $id = checkInput($_GET['id']);
    }
    
    $nameError = $descriptionError = $priceError = $categoryError = $imageError = $name = $description = $price = $category = $image = "";

    if(!empty($_POST)) {
        $name               = checkInput($_POST['name']);
        $description        = checkInput($_POST['description']);
        $price              = checkInput($_POST['price']);
        $category           = checkInput($_POST['category']);
        $image              = checkInput($_FILES['image']['name']); /* Recuperando o nome da imagem */
        $imagePath          = '../images/' . basename($image);
        $imageExtension     = pathinfo($imagePath, PATHINFO_EXTENSION);
        $isSuccess          = true;        

        /* Verificando se os campos do formulário estão vazios */
        if(empty($name)) {
            $nameError = "This field can not be empty.";
            $isSuccess = false;
        }
        if(empty($description)) {
            $descriptionError = "This field can not be empty.";
            $isSuccess = false;
        }
        if(empty($price)) {
            $priceError = "This field can not be empty.";
            $isSuccess = false;
        }
        if(empty($category)) {
            $categoryError = "This field can not be empty.";
            $isSuccess = false;
        }
        if(empty($image)) {
            $isImageUpdated = false;
        } else { /* Se uma imagem for carregada... */
            $isImageUpdated = true;
            $isUploadSuccess = true;
            /* Verificando o tipo de extensão da imagem carregada */
            if($imageExtension != "jpg" && $imageExtension != "png" && $imageExtension != "jpeg" && $imageExtension != "gif") {
                $imageError = "The supported file extensions are: .jpg, .jpeg, .png, .gif";
                $isUploadSuccess = false;
            }
            /* Verificando se a imagem carregada já existe */
            if(file_exists($imagePath)) {
                $imageError = "This file already exists";
                $isUploadSuccess = false;
            }
            /* Verificando se a imagem possui mais de 500KB */
            if($_FILES["image"]["size"] > 500000) {
                $imageError = "The file can not be larger than 500KB";
                $isUploadSuccess = false;
            }
            /* Se o carregamento foi completado com sucesso... */
            if($isUploadSuccess) {
                /* Verificando se houve algum erro ao mover a imagem carregada para o campinho especificado */
                if(!move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath)) {
                    $imageError = "There was an error while uploading";
                    $isUploadSuccess = false;
                }
            }
        }

        /* Se todos os campos foram informados corretamente e a imagem foi carregada corretamente,
           Executar a inserção no banco de dados e redirecionar para a página principal (index.php) */

        if(($isSuccess && $isImageUpdated && $isUploadSuccess) || ($isSuccess && !$isImageUpdated)) {
            $db = Database::connect();
            if($isImageUpdated) {
                $statement = $db->prepare("UPDATE items 
                                           SET name = ?, description = ?, price = ?, category = ?, image = ?
                                           WHERE id = ?");
                $statement->execute(array($name, $description, $price, $category, $image, $id));
            } else {
                $statement = $db->prepare("UPDATE items 
                                           SET name = ?, description = ?, price = ?, category = ?
                                           WHERE id = ?");
                $statement->execute(array($name, $description, $price, $category, $id));
            }
            Database::disconnect();
            header("Location: index.php");

        } else if($isImageUpdated && !$isUploadSuccess) {
            $db = Database::connect();
            $statement = $db->prepare("SELECT image FROM items WHERE id = ?");
            $statement->execute(array($id));
            $item = $statement->fetch();
            $image = $item['image'];
            Database::disconnect();
        }

    } else {
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM items WHERE id = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();
        $name = $item['name'];
        $description = $item['description'];
        $price = $item['price'];
        $category = $item['category'];
        $image = $item['image'];
        Database::disconnect();
    }

    function checkInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Burger Code</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link href="http://fonts.googleapis.com/css?family=Holtwood+One+SC" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/styles.css">
    </head>

    <body>
        <h1 class="text-logo">
            <span class="glyphicon glyphicon-cutlery"></span> Burguer Code <span class="glyphicon glyphicon-cutlery"></span>            
        </h1>
        <div class="container admin">
            <div class="row">
                <div class="col-sm-6">
                    <h1><strong>Update item</strong></h1>
                    <br>
                    <form class="form" role="form" action="<?php echo 'update.php?id=' . $id; ?>" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $name; ?>">
                            <span class="help-inline"><?php echo $nameError; ?></span> 
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Description" value="<?php echo $description; ?>">
                            <span class="help-inline"><?php echo $descriptionError; ?></span>                         
                        </div>
                        <div class="form-group">
                            <label for="price">Price: (em €)</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" placeholder="Price" value="<?php echo $price; ?>">
                            <span class="help-inline"><?php echo $priceError; ?></span>                         
                        </div>
                        <div class="form-group">
                            <label for="category">Category:</label>
                            <select class="form-control" id="category" name="category">
                                <?php
                                    $db = Database::connect();
                                    foreach($db->query('SELECT * FROM categories') as $row) {
                                        if($row['id'] == $category) {
                                            echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                        } else {
                                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                        }
                                        
                                    }
                                    Database::disconnect();
                                ?>
                            </select>
                            <span class="help-inline"><?php echo $categoryError; ?></span> 
                        </div>
                        <div class="form-group">
                            <label>Image:</label>
                            <p><?php echo $image; ?></p>
                            <label for="image">Select an image:</label>
                            <input type="file" id="image" name="image">
                            <span class="help-inline"><?php echo $imageError; ?></span> 
                        </div>                
                        <br>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Update</button>
                            <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
                        </div>
                    </form>
                </div>
                <div class="col-sm-6 site">
                    <div class="thumbnail">
                        <img src="<?php echo '../images/' . $image; ?>" alt="...">
                        <div class="price"><?php echo number_format((float) $price, 2, ',', '') . ' €'; ?></div> <!-- Alt+0128-->
                        <div class="caption">
                            <h4><?php echo $name; ?></h4>
                            <p><?php echo $description; ?> </p>
                            <a href="#" class="btn btn-order" role="button"><span class="glyphicon glyphicon-shopping-cart"></span> Commander</a>
                        </div>
                    </div>
                </div>                                                  
            </div>
        </div>
    </body>
</html>