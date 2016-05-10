$(document).ready(function () {

    $(".formComment").submit(function () {

        var url = $(location).attr("href");
        var res = url.replace("news", "commenter");
        $("#main .alert").detach();
        setLoader(true);


        if ($(".formComment input[type=text]").val() == "") {

            $("#main .formComment").prepend("<div class='alert alert-info' role='alert'>Le champs de l'auteur est vide !</div>");
            setLoader(false);
            return false;
        }


        if ($(".formComment textarea").val() == "") {

            $("#main .formComment").prepend("<div class='alert alert-info' role='alert'>Le champs du commentaire est vide !</div>");
            setLoader(false);
            return false;
        }

        $.ajax({
            url: res,
            method: "post",
            dataType: "json",
            data: {
                "auteur": $(".formComment input[type=text]").val(),
                "contenu": $(".formComment textarea").val(),
                "offsetId": $(".comment-section fieldset").last().attr('data-id')
            }

        }).done(function (data) {

            if (data.error != true) {

                if(data.data[0] != null) {
                    $('.comment-section').prepend(data.data[0].html);

                    $(".formComment input[type=text]").val("");
                    $(".formComment textarea").val("");
                }
            }
            $("#main").prepend("<div class='alert alert-info' role='alert'>" + data.message + "</div>");
            setLoader(false);
        });

        return false;
    });

});


function setLoader(active) {

    if(active) {

        $(".formComment input[type=submit]").css('display', 'none');
        $(".formComment").append("<img class='loader' src='../images/loading.gif' alt='' />");

    } else {

        $(".formComment input[type=submit]").css('display', 'block');
        $(".loader").detach();

    }

}