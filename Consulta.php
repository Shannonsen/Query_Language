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

    $initialSentence = " Concat(product_name, quantity_per_unit, category) LIKE " . "'%" . $queryparts[0] . "%'";
    /*
        if(SIN_PARENTESIS(LAST_WORD($queryparts))== "CAMPOS"){
            $sentence = "SELECT ".  "FROM products  WHERE";
        }
        */
    $data = array();

    if (SIN_PARENTESIS(LAST_WORD($queryparts)) == "CAMPOS") {

        $lastword = LAST_WORD(($queryparts));
        $campos = CAMPOS($lastword);

        $campos_divididos = SEPARAR_COMAS($campos);
        for ($n = 0; $n < count($campos_divididos); $n++) {
            echo "<br>" . $campos_divididos[$n];
            $campos_completos = SEPARAR_PUNTOS($campos_divididos[$n]);

            $table = $campos_completos[0];
            $data[] = $campos_completos[1];

            for($i=0; $i < count($data); $i++){
                echo "<br>" . $data[$i];
            }
        }
    }else{
        for ($j = 0; $j < count($queryparts); $j++) {
            echo "<br>" . $queryparts[$j];
            if ($queryparts[$j] == $OR) {

              $sentence_or = OPERATOR_OR($queryparts[$j],$queryparts[$j + 1]);
              $initialSentence = $initialSentence . $sentence_or;

            } else {
                if ($queryparts[$j] == $AND) {
                    if ($queryparts[$j + 1] == $NOT) {
                        $parenthesis = "(" . $initialSentence;
                        $initialSentence = $parenthesis;
                       $sentence_andnot = OPERATOR_ANDNOT($queryparts[$j],$queryparts[$j + 1], $queryparts[$j + 2]);
                        $initialSentence = $initialSentence . $sentence_andnot;
                    } else {
                        $parenthesis = "(" . $initialSentence;
                        $initialSentence = $parenthesis;
                        $sentence_and = OPERATOR_AND($queryparts[$j],$queryparts[$j + 1]);
                        $initialSentence = $initialSentence . $sentence_and;
                    }
                }
            }
        }

        $sentence = $sentence . $initialSentence;

        echo "<br>" . $sentence . "<br>";
        $result = mysqli_query($con, $sentence);

        if (!$result) {
            var_dump(mysqli_error($con));
            exit;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<br>" . $row["product_name"] . $row["quantity_per_unit"] . $row["category"];
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