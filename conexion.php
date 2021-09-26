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

function CADENA($cadena){

}

function CAMPOS_ARRAY($queryparts){
    $data_string="";
    $lastword = LAST_WORD(($queryparts));
    $campos = ENTRE_PARENTESIS($lastword);

    $campos_divididos = SEPARAR_COMAS($campos);
    for ($n = 0; $n < count($campos_divididos); $n++) {
        echo "<br>" . $campos_divididos[$n];
        $campos_completos = SEPARAR_PUNTOS($campos_divididos[$n]);

        $table = $campos_completos[0];
        $data[] = $campos_completos[1];      
    }
    return $data;
}

function CAMPOS($queryparts){
    $data_string="";
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
    return $data_string;
}

function getTable($queryparts){
    $data_string="";
    $lastword = LAST_WORD(($queryparts));
    $campos = ENTRE_PARENTESIS($lastword);

    $campos_divididos = SEPARAR_COMAS($campos);
    for ($n = 0; $n < count($campos_divididos); $n++) {
        echo "<br>" . $campos_divididos[$n];
        $campos_completos = SEPARAR_PUNTOS($campos_divididos[$n]);

        $table = $campos_completos[0];
    }
    return $table;
}

//Valores entre parentesis CAMPOS(product.name) = product.name
function ENTRE_PARENTESIS($cadena){
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

function OPERATOR_OR($data_string, $operator,$query){
    $sentence_or = " " . $operator . " Concat(" . $data_string . ") LIKE "   . "'%" . $query . "%' ";
    return $sentence_or;
}

function OPERATOR_ANDNOT($data_string,$operator,$not,$query){
    $sentence_andnot = " ". $operator . " " . $not . " Concat(" . $data_string .") LIKE  " . "'%" . $query . "%' ";
    return $sentence_andnot;
}

function OPERATOR_AND($data_string,$operator,$query){
    $sentence_and =  " ". $operator .  " Concat(".$data_string . ") LIKE  " . "'%" . $query . "%' ";
    return $sentence_and;
}

//-------------- OPERATIONS -------------

function QUERY($campos,$queryparts, $initialSentence,$OR,$AND,$NOT){
    for ($j = 0; $j < count($queryparts); $j++) {
        echo "<br>" . $queryparts[$j];
        if ($queryparts[$j] == $OR) {
            $sentence_or = OPERATOR_OR($campos,$queryparts[$j], $queryparts[$j + 1]);
             $initialSentence = $initialSentence . $sentence_or;
        } else {
            if ($queryparts[$j] == $AND) {
                if ($queryparts[$j + 1] == $NOT) {
                    //$parenthesis = "(" . $initialSentence;
                    //$initialSentence = $parenthesis;
                    $sentence_andnot = OPERATOR_ANDNOT($campos,$queryparts[$j], $queryparts[$j + 1], $queryparts[$j + 2]);
                    $initialSentence = $initialSentence . $sentence_andnot;
                } else {
                    //$parenthesis = "(" . $initialSentence;
                    //$initialSentence = $parenthesis;
                    $sentence_and = OPERATOR_AND($campos,$queryparts[$j], $queryparts[$j + 1]);
                    $initialSentence = $initialSentence . $sentence_and;
                }
            }
        }
    }
    return $initialSentence;
}

?>