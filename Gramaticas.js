document.getElementById("btnSearch").addEventListener("click",function(){Search()});

var queryprint;

function Search(){
    var query = document.getElementById("search").value;
    window.location = "Consulta.php?search=" + query;
}


