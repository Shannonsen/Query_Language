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
        <?php
        //conexion a php
        include("conexion.php");
        $con = connect();

        //solicitar la consulta de javascript a php
        if (isset($_REQUEST['search']) && $_REQUEST['search'] != "") {
            $query = $_REQUEST['search'];

            //separar la consulta en palabras cada vez que se encuentre un espacio
            $queryparts = BREAK_SPACES($query);

            $data = array();

            //STRPOS = Dentro del string query se encuentran los caracteres CAMPOS
            if (strpos($query, "CAMPOS")) {
                $campos = FIELDS($queryparts); //String de campos
                $table = getTable($queryparts); //String de tabla
                $query_complete = QUERY($queryparts, $campos); //QUERY completo para solicitar

                echo "<br>" . $query_complete . "<br>";

                $result = mysqli_query($con, $query_complete); //consulta

                if (!$result) {
                    var_dump(mysqli_error($con));
                    exit;
                }

                if (mysqli_num_rows($result) <= 0) {
                    echo alert("Sin resultados encontrados");
                }

                $data = FIELDS_ARRAY($queryparts); //Array de campos

                //imprimir consulta en los campos correspondientes
                while ($row = mysqli_fetch_assoc($result)) {

                    for ($r = 0; $r < count($data); $r++) {
                        echo "<br>" . $data[$r] . " = " . $row[$data[$r]];
                    }
                    echo "<br>";
                }
            } else {
                $campos = "product_name,quantity_per_unit,category"; //String campos preterminados
                $query_complete = QUERY($queryparts, $campos);

                echo "<br>" . $query_complete . "<br>";
                $result = mysqli_query($con, $query_complete); //Consulta

                if (!$result) {
                    var_dump(mysqli_error($con));
                    exit;
                }

                if (mysqli_num_rows($result) <= 0) {
                   echo alert("Sin resultados encontrados");
                }

                //imprimir campos correspondientes
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<br> product_name = " . $row["product_name"];
                    echo " <br> quantity_per_unit = " . $row["quantity_per_unit"];
                    echo "<br> category = " . $row["category"];
                    echo "<br>";
                }
            }
        }

        ?>
    </div>

    <script src="Gramaticas.js"></script>
</body>

</html>