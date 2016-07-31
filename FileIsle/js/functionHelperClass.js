/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function callFunction(str) 
{
     if (str.length == 0) { 
         document.getElementById("txtHint").innerHTML = "";
         return;
     } else {
         var xmlhttp = new XMLHttpRequest();
         xmlhttp.onreadystatechange = function() {
             if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                 document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
             }
         }
         xmlhttp.open("GET", "gethint.php?q="+str, true);
         xmlhttp.send();
    }
}

