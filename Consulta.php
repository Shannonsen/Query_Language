<?php
//conexion a php
include("conexion.php");
$con = connect();

//OPERADORES
$OR = "OR";
$AND = "AND";
$NOT = "NOT";

$sentence = "SELECT product_name, quantity_per_unit, category FROM products  WHERE";

//solicitar la consulta de javascript a php
if (isset($_REQUEST['search']) && $_REQUEST['search'] != "") {
    $query = $_REQUEST['search'];

    //separar la consulta en palabras
    $queryparts = SEPARAR_ESPACIOS($query);

    $data = array();
    $campos = "";

    if (SIN_PARENTESIS(LAST_WORD($queryparts)) == "CAMPOS") {

        echo "ENTRE";

        $campos = CAMPOS($queryparts);
        $table = getTable($queryparts);

        echo "TABLA: ". $table;

        $query_complete = QUERY($queryparts,$campos); //FALTAN LAS CATEGORIAS.

        echo "<br>" . $query_complete . "<br>";
        $result = mysqli_query($con, $query_complete);

        if (!$result) {
            var_dump(mysqli_error($con));
            exit;
        }

        $data = CAMPOS_ARRAY($queryparts);

        while ($row = mysqli_fetch_assoc($result)) {

            for ($r = 0; $r < count($data); $r++) {
                echo "<br>" . $data[$r] . " = " . $row[$data[$r]];
            }
            echo "<br>";
        }
    } else {

        $campos = "product_name,quantity_per_unit,category";
        $query_complete = QUERY($queryparts,$campos); //FALTAN LAS CATEGORIAS;
    
        echo "<br>" . $query_complete . "<br>";
        $result = mysqli_query($con, $query_complete);

        if (!$result) {
            var_dump(mysqli_error($con));
            exit;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<br> product_name = " . $row["product_name"];
            echo " <br> quantity_per_unit = " . $row["quantity_per_unit"];
            echo "<br> category = " . $row["category"];
            echo "<br>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="estilo.css">
</head>

<body>
    <div class="title">
        <h1> Query Language 🔍</h1>
    </div>
    <div class="form">
        <form action="">
            <input type="text" id="search">
            <input type="button" class="btn" id="btnSearch" value="Search">
        </form>
    </div>

    <div class="query" id="print">
    </div>

    <script src="Gramaticas.js"></script>
</body>

</html>