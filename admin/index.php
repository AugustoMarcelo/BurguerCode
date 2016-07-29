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
                <h1><strong>Lista de Itens   </strong>                
                <a href="insert.php" class="btn btn-success btn-lg"><span class="glyphicon glyphicon-plus"></span> Adicionar</a></h1>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Descrição</th>
                            <th>Preço</th>
                            <th>Categoria</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require('database.php');
                            
                            $db = Database::connect();
                            $statement = $db->query('SELECT i.id, i.name, i.description, i.price, c.name AS category 
                                                     FROM items i LEFT JOIN categories c 
                                                     ON i.category = c.id 
                                                     ORDER BY i.id DESC');

                            while ($item = $statement->fetch()) {
                                echo '<tr>';
                                echo '<td>' . $item['name'] . '</td>';
                                echo '<td>' . $item['description'] . '</td>';
                                echo '<td>' . number_format((float) $item['price'], 2, ',', '') . '</td>';
                                echo '<td>' . $item['category'] . '</td>';
                                echo '<td width=300>';
                                echo '<a class="btn btn-default" href="view.php?id=' . $item['id'] . '"><span class="glyphicon glyphicon-eye-open"></span> Ver</a>';
                                echo ' ';
                                echo '<a class="btn btn-primary" href="update.php?id=' . $item['id'] . '"><span class="glyphicon glyphicon-pencil"></span> Atualizar</a>';
                                echo ' ';
                                echo '<a class="btn btn-danger" href="delete.php?id=' . $item['id'] . '"><span class="glyphicon glyphicon-remove"></span> Deletar</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                            Database::disconnect();
                        ?>                        
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>