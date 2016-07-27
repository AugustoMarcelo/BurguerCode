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
                        <tr>
                            <td>Item 1</td>
                            <td>Descrição 1</td>
                            <td>Preço 1</td>
                            <td>Categoria 1</td>
                            <td width=300>
                                <a class="btn btn-default" href="view.php"><span class="glyphicon glyphicon-eye-open"></span> Ver</a>
                                <a class="btn btn-primary" href="update.php"><span class="glyphicon glyphicon-pencil"></span> Atualizar</a>
                                <a class="btn btn-danger" href="delete.php"><span class="glyphicon glyphicon-remove"></span> Deletar</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>