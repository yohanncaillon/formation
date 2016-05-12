$(document).ready(function () {



    //----------------------------------------------------------------------------------------
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

                if (data.data[0] != null) {
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
    //----------------------------------------------------------------------------------------

    $("#subscribe").on("submit", function () {


        $.ajax({
            url: "http://monsupersite/registerAjax",
            method: "post",
            dataType: "json",
            data : $(this).serialize(),

        }).done(function (data) {

            console.log(data.error);
            if (data.error) {

                $(".wrap").html(data.data);
            } else {

                document.location.href="http://monsupersite/";
            }

        });

        return false;
    });

    //----------------------------------------------------------------------------------------
    $("input[name=tagString]").on("paste keyup", function () {

        var valeur = $("input[name=tagString]").val();
        valeur = valeur.split(",").slice(-1)[0].trim();

        if (valeur != "") {

            $.ajax({
                url: "http://monsupersite/searchTag/",
                method: "post",
                dataType: "json",
                data: {
                    "name": valeur
                }

            }).done(function (data) {

                var html = "";

                var existant = $("input[name=tagString]").val().split(",");

                for (var i in data.data) {

                    var exists = false;
                    for (var y in existant) {

                        if (data.data[i].name == existant[y].trim())
                            exists = true;

                    }
                    if (!exists)
                        html += "<i class='tag-item'>" + data.data[i].name + "</i> ";

                }

                $('.tagsProp span').html(html);

                $('.tag-item').click(function () {

                    var str = "";
                    if ($("input[name=tagString]").val().indexOf(',') > -1) {

                        str = $("input[name=tagString]").val().replace(/,[^,]+$/, "") + ", ";
                    }
                    $("input[name=tagString]").val(str + $(this).html());
                });

            });
        } else {

            $('.tagsProp span').html("");
        }

    });


});


function setLoader(active) {

    if (active) {

        $(".formComment input[type=submit]").css('display', 'none');
        $(".formComment").append("<img class='loader' src='../images/loading.gif' alt='' />");

    } else {

        $(".formComment input[type=submit]").css('display', 'block');
        $(".loader").detach();

    }

}