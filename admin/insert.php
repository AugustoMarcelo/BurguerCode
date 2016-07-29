<?php
    require('database.php');
    
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
        $isUploadSuccess    = false;

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
            $imageError = "This field can not be empty.";
            $isSuccess = false;
        } else { /* Se uma imagem for carregada... */
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

        if($isSuccess && $isUploadSuccess) {
            $db = Database::connect();
            $statement = $db->prepare("INSERT INTO items (name, description, price, category, image)
                                       VALUES (?, ?, ?, ?, ?)");
            $statement->execute(array($name, $description, $price, $category, $image));
            Database::disconnect();
            header("Location: index.php");
        }
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
                <h1><strong>Add item</strong></h1>
                <br>
                <form class="form" role="form" action="insert.php" method="post" enctype="multipart/form-data">
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
                                foreach($db->query('SELECT * FROM categories ORDER BY name') as $row) {
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                }
                                Database::disconnect();
                            ?>
                        </select>
                        <span class="help-inline"><?php echo $categoryError; ?></span> 
                    </div>
                    <div class="form-group">
                        <label for="image">Select an image:</label>
                        <input type="file" id="image" name="image">
                        <span class="help-inline"><?php echo $imageError; ?></span> 
                    </div>                
                    <br>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-pencil"></span> Add</button>
                        <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-arrow-left"></span> Back</a>
                    </div>
                </form>                                                  
            </div>
        </div>
    </body>
</html>