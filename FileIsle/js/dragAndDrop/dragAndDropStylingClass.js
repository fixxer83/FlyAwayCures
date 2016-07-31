/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function initiateDrag(e) {
  this.style.opacity = '1.0';  // this / e.target is the source node.
}

var cols = document.querySelectorAll('.drag_table');
[].forEach.call(cols, function(col) {
  col.addEventListener('dragstart', handleDragStart, false);
});