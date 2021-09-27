<?php

function connect()
{
    $user = "root";
    $password = "";
    $server = "localhost";
    $db = "northwind";

    $con = new mysqli($server, $user, $password, $db);

    if ($con->connect_error) {
        die("Ha fallado la conexion: " . $con->connect_error);
    }
    echo alert("Successfull Connection");
    return $con;
}

function alert($message)
{
    return "<script>alert('$message')</script>";
}

//devuelve array de campos
function FIELDS_ARRAY($queryparts)
{
    $data = array();
    $lastword = LAST_WORD(($queryparts));
    $campos = BETWEEN_PARENTHESES($lastword);

    $campos_divididos = BREAK_COMMAS($campos);
    for ($n = 0; $n < count($campos_divididos); $n++) {
        //echo "<br>" . $campos_divididos[$n];
        $campos_completos = BREAK_POINTS($campos_divididos[$n]);

        $table = $campos_completos[0];
        $data[] = $campos_completos[1];
    }
    return $data;
}

//devuelve la concatenacion de campos en un string
function FIELDS($queryparts)
{
    $data_string = "";
    $lastword = LAST_WORD(($queryparts));
    $campos = BETWEEN_PARENTHESES($lastword);

    $campos_divididos = BREAK_COMMAS($campos);
    for ($n = 0; $n < count($campos_divididos); $n++) {
       // echo "<br>" . $campos_divididos[$n];
        $campos_completos = BREAK_POINTS($campos_divididos[$n]);

        $table = $campos_completos[0];
        $data[] = $campos_completos[1];

        for ($i = 0; $i < count($data); $i++) {
            //echo "<br>" . $data[$i];
            if ($i == 0) {
                $data_string = $data[$i];
            } else {
                $data_string = $data_string . "," . $data[$i];
            }
        }
    }
    return $data_string;
}

//devuelve tabla para la consulta
function getTable($queryparts)
{
    $data_string = "";
    $lastword = LAST_WORD(($queryparts));
    $campos = BETWEEN_PARENTHESES($lastword);

    $campos_divididos = BREAK_COMMAS($campos);
    for ($n = 0; $n < count($campos_divididos); $n++) {
        //echo "<br>" . $campos_divididos[$n];
        $campos_completos = BREAK_POINTS($campos_divididos[$n]);

        $table = $campos_completos[0];
    }
    return $table;
}

//Valores entre parentesis CAMPOS(product.name) = product.name
function BETWEEN_PARENTHESES($cadena)
{
    $regex = '#\((([^()]+|(?R))*)\)#';
    if (preg_match_all($regex, $cadena, $matches)) {
        return implode(' ', $matches[1]);
    } else {
        //no parenthesis 
        echo $cadena;
    }
}

//valor fuera del parentesis CAMPOS(product.name) = CAMPOS
function WITHOUT_PARENTHESES($cadena)
{
    $regex = '#\((([^()]+|(?R))*)\)#';
    return preg_replace($regex, "", $cadena);
}

//ultima palabra de un string "fruit CAMPOS(product.name)" = CAMPOS(product.name)
function LAST_WORD($queryparts)
{
    $longitud_query = count($queryparts);
    $lastWord = $queryparts[$longitud_query - 1];

    return $lastWord;
}

//separa el string por comas "product.name,product.lastname" = 1.product.name 2. product.lastname.
function BREAK_COMMAS($cadena)
{
    $separador = ",";
    $separada = explode($separador, $cadena);

    return $separada;
}

//separa el string por puntos "products.product.name" = 1.products 2. product.name.
function BREAK_POINTS($cadena)
{
    $separador = ".";
    $separada = explode($separador, $cadena);

    return $separada;
}

//separar el string por espacios "PRODUCT OR FRUIT" = 1.PRODUCT 2.OR 3.FRUIT
function BREAK_SPACES($cadena)
{
    $separador = " ";
    $separada = explode($separador, $cadena);

    return $separada;
}

//-------------- FUNCTIONS OPERATORS --------------

//concatena los campos para consulta mas eficiente
function QUERY_CONCAT($fields, $query)
{
    $query_concat = " Concat(" . $fields . ")" . " LIKE '%" .  $query . "%'";
    return $query_concat;
}

function QUERY($queryparts, $fields)
{
    $query_initial = "SELECT " . $fields . " FROM " . "products " . "WHERE ";
    $query = "";
    $campos="";
    $count = 0;

    for ($j = 0; $j < count($queryparts); $j++) {
        switch ($queryparts[$j]) {
            case "OR":
                $query .= " OR ";
                $count++;
                break;
            case "AND":
                if ($count == 0) {
                    $query .= " AND ";
                } else {
                    $query .= ") AND ";
                    $parentesis = $query_initial . "(";
                    $query_initial = $parentesis;
                }
                $count++;
                break;
            case "NOT":
                $query .= "NOT ";
                $count++;
                break;
            default:
                $verification = strstr($queryparts[$j], '(', true); //verifica si es CADENA O PATRON.
                switch ($verification) {
                    case 'CADENA':
                        echo " <br> QUERYPARTS: " . $queryparts[$j] . " <br>";
                        $Inside_parentheses = substr(strstr($queryparts[$j], '('), 1, -1);
                        $query .= QUERY_CONCAT($fields, $Inside_parentheses);
                        $count++;
                        break;
                    case 'PATRON':
                        $Inside_parentheses = substr(strstr($queryparts[$j], '('), 1, -1);
                        $query .= QUERY_CONCAT($fields, $Inside_parentheses);
                        $count++;
                        break;
                    case 'CAMPOS':
                        break;
                    default:
                        $query .= QUERY_CONCAT($fields, $queryparts[$j]);
                        break;
                }
                break;
        }
    }
    $query_final = $query_initial . $query;
    //echo $query_final . "<br/><br/>";

    return $query_final;
}
