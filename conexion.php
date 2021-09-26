<?php 

function connect(){
    $user ="root";
    $password = "";
    $server = "localhost";
    $db= "northwind";
    
    $con = new mysqli($server, $user, $password,$db);
    
    if ($con->connect_error) {
        die("Ha fallado la conexion: " . $con->connect_error);
    } 
    echo "Conectado correctamente";
    return $con;
}

//Valores entre parentesis CAMPOS(product.name) = product.name
function CAMPOS($cadena){
    $regex = '#\((([^()]+|(?R))*)\)#'; 
    if (preg_match_all($regex, $cadena ,$matches)) { 
        return implode(' ', $matches[1]); 
    } else { 
        //no parenthesis 
        echo $cadena; 
    } 
}

//valor fuera del parentesis CAMPOS(product.name) = CAMPOS
function SIN_PARENTESIS($cadena){
    $regex = '#\((([^()]+|(?R))*)\)#';
    return preg_replace($regex,"",$cadena);
}

//ultima palabra de un string "fruit CAMPOS(product.name)" = CAMPOS(product.name)
function LAST_WORD($queryparts){
  $longitud_query = count($queryparts);
  $lastWord = $queryparts[$longitud_query -1];

  return $lastWord;
}

//separa el string por comas "product.name,product.lastname" = 1.product.name 2. product.lastname.
function SEPARAR_COMAS($cadena){
$separador = ",";
$separada = explode($separador,$cadena);

return $separada;
}

function SEPARAR_PUNTOS($cadena){
    $separador = ".";
    $separada = explode($separador,$cadena);
    
    return $separada;
    }

//separar el string por espacios "PRODUCT OR FRUIT" = 1.PRODUCT 2.OR 3.FRUIT
function SEPARAR_ESPACIOS($cadena){
    $separador = " ";
    $separada = explode($separador,$cadena);

    return $separada;
}


//-------------- FUNCTIONS OPERATORS --------------

function OPERATOR_OR($operator,$query){
    $sentence_or = " " . $operator . " Concat(product_name, quantity_per_unit, category) LIKE "  . "'%" . $query . "%' ";
    return $sentence_or;
}

function OPERATOR_ANDNOT($operator,$not,$query){
    $sentence_andnot =  ") " . $operator . " " . $not . " Concat(product_name, quantity_per_unit, category) LIKE  " . "'%" . $query . "%' ";

    return $sentence_andnot;
}

function OPERATOR_AND($operator,$query){
    $sentence_and =  ") " . $operator .  " Concat(product_name, quantity_per_unit, category) LIKE  " . "'%" . $query . "%' ";
}

?>