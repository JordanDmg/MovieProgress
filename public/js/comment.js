//Fonction permettant l'affichage correcte des dates et heurres
function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

$("#addComment").click(function (e) {
    var content = ($('textarea').val())
    var movieId = $(this).data("id-movie")
    var user = $(this).data("is-authenticated")


    //Permet l'affichage de la date et de l'heure a coté du commentaire
    var today = new Date();
    var dd = addZero(today.getDate());
    var mm = addZero(today.getMonth() + 1);
    var yy = today.getFullYear().toString().substr(-2);
    var hh = addZero(today.getHours());
    var minute = addZero(today.getMinutes());

    content = content.trim()      //Suppression des espace avant et apres les caractères


    if (content) {                  //S'il y a du contenu l'ajoute a la base de données
        $.ajax({
            url: "addcomment/" + movieId + "/" + content,
            success: function (idComment) {
                
                $('.textarea').val('')
                $('.comment').prepend(`<div class="row">
                                            <div class="col-3">
                                                `+ user + `  (<small>` + dd + `/` + mm + `/` + yy + ` à ` + hh + `:` + minute + `</small>)
                                            </div>
                                            <div class="col">
                                                `+ content + `
                                            </div>
                                            <div class="col-1">
                                                <a id="deleteComment" href="#" data-id-comment="`+ idComment +`" >x</a>
                                            </div>
                                        </div>`)
            },
            error: function () {
                alert('failure');
            }
        });

    } else {
        alert('veuillez entrer du texte')

    }
})

$(".deleteComment").click(function(e){
    e.preventDefault()
    var commentId = $(this).data("id-comment")    
    var el = $(this).parent().parent()
    $.ajax({
        url: "deletecomment/" + commentId,
        success: function () {
            el.remove()

        },
        error: function () {
            alert('Il y eu un probleme lors de la suppression du commentaire');
        }
    });
})