/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function showTabsAccordingToSession()
{
    var outward = isset($_SESSION['outward']);
    var destination = isset($_SESSION['destination']);
    
    if(outward == "" || outward == null)
    {
        $(document).ready(function(){$("ul.steps li").hide()
        ;});
    }else if(outward != "" && destination != "")
    {
        $(document).ready(function(){$("#step3").hide()
        ;});
    }
}


