/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function fileSelection()
{
    //Test Image
    test = document.getElementById('logo_img');
    test.addEventListener("dragstart", startDrag, false);
    
    dropzone = document.getElementById('main_container_div');   
    dropzone.addEventListener("dragenter", function(e){e.preventDefault();}, false);
    dropzone.addEventListener("dragover", function(e){e.preventDefault();}, false);
    dropzone.addEventListener("drop", dropped, false);
}

//This function will be triggerred upon dragging
function startDrag(e)
{
    var myimg = '<img id="logo_img" src="..//logos/logo003.png" alt="fileIsleLogo" style="width:80px;height:80px;" title="FileIsle"/>';
    e.dataTransfer.setData('Text', myimg);
}

//This function will be triggerred upon dropping the dragged object
function dropped(e)
{
    e.preventDefault();
    dropzone.innerHTML = e.dataTransfer.getData('Text');
}

window.addEventListener("load", fileSelection, false);