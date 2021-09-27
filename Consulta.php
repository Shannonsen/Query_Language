<?php
//conexion a php
include("conexion.php");
$con = connect();

//OPERADORES
$OR = "OR";
$AND = "AND";
$NOT = "NOT";

//solicitar la consulta de javascript a php
if (isset($_REQUEST['search']) && $_REQUEST['search'] != "") {
    $query = $_REQUEST['search'];

    //separar la consulta en palabras
    $queryparts = SEPARAR_ESPACIOS($query);

    $data = array();
    $campos="";

    if (SIN_PARENTESIS(LAST_WORD($queryparts)) == "CAMPOS") {

        echo "ESTOY EN LA CONDICION DE CAMPOS";
        /*
        $lastword = LAST_WORD(($queryparts));
        $campos = ENTRE_PARENTESIS($lastword);

        $campos_divididos = SEPARAR_COMAS($campos);
        for ($n = 0; $n < count($campos_divididos); $n++) {
            echo "<br>" . $campos_divididos[$n];
            $campos_completos = SEPARAR_PUNTOS($campos_divididos[$n]);

            $table = $campos_completos[0];
            $data[] = $campos_completos[1];

            for ($i = 0; $i < count($data); $i++) {
                //echo "<br>" . $data[$i];
                if($i==0){
                $data_string = $data[$i];
                }else{
                    $data_string = $data_string . "," . $data[$i];
                }
            }       
        }
        echo " <br> dataString:" . $data_string;
        	*/

        $campos = CAMPOS($queryparts);
        $table = getTable($queryparts);
        $sentence = "SELECT ". $campos . " FROM " . $table . " WHERE ";
        $initialSentence = "Concat(". $campos . ") LIKE " . "'%" . $queryparts[0] . "%'";

        $initialSentence = QUERY($campos,$query,$queryparts, $initialSentence,$OR,$AND,$NOT);
        $sentence = $sentence . $initialSentence;

        echo "<br>" . $sentence . "<br>";
        $result = mysqli_query($con, $sentence);

        if (!$result) {
            var_dump(mysqli_error($con));
            exit;
        }        
        $data = CAMPOS_ARRAY($queryparts) ;

        while ($row = mysqli_fetch_assoc($result)) {
        
                for($r = 0; $r < count($data); $r++){
                    echo "<br>". $data[$r]. " = " . $row[$data[$r]];
                }
                echo "<br>";
        }

    } else {

        $sentence = "SELECT product_name, quantity_per_unit, category FROM products  WHERE";
        $initialSentence = " Concat(product_name, quantity_per_unit, category) LIKE " . "'%" . $queryparts[0] . "%'";

        $campos = "product_name, quantity_per_unit, category";

        $initialSentence = QUERY($campos,$query,$queryparts, $initialSentence,$OR,$AND,$NOT);

        $sentence = $sentence . $initialSentence;

        echo "<br>" . $sentence . "<br>";
        $result = mysqli_query($con, $sentence);

        if (!$result) {
            var_dump(mysqli_error($con));
            exit;
        }

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<br> product_name = " . $row["product_name"] ;
            echo" <br> quantity_per_unit = ". $row["quantity_per_unit"];
            echo "<br> category = " . $row["category"];
            echo "<br>" ;
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