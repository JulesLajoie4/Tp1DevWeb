//------------------------------
// Fichier créé par Jules Lajoie
// 7 mars 2024
//------------------------------

$(document).ready(function () {
    $(".boutonModal").on('click', function (event) {
        //ATTENTION: le event.preventDefault() est nécessaire sinon la modale ne s'affiche pas
        event.preventDefault();

        $.get($(this).attr('href'), function (data) {
            $("#modaleDetails").html(data).dialog({
                height: 600,
                width: 600,
                modal: true
            });
        });
    });
});