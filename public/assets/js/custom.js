/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(document).ready(function() {
 
         var table = $('#dataTable').DataTable();

        var modal = document.getElementById("myModal");

         var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        
         // Get the <span> element that closes the modal
         var span = document.getElementsByClassName("close")[0];
 
         table.on('click', '#myImg', function (){
 
             $tr = $(this).closest('tr');
             if ($($tr).hasClass('child')) {
                 $tr = $tr.prev('.parent');
             }
 
             var data = table.row($tr).data();
             console.log(data);
 
            modalImg.src = this.src;
            captionText.innerHTML = data[1];

 
             $('#myModal').modal('show');
         });


 
      });