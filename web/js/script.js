"use strict";

$(document).ready(function () {

    const MIN_SEARCH = 2;

    //---------------------------------------POST COMMENTAIRE-------------------------------------------------
    $("input[name=tagString]").bind("keydown", function (event) {

        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete("instance").menu.active) {
            event.preventDefault();
        }

    }).autocomplete({
        source: function (request, response) {

            $.ajax({
                url: "http://monsupersite/searchTag/",
                method: "post",
                dataType: "json",
                data: {
                    "name": extractLast(request.term)
                },
                success: function (data) {

                    response($.map(data.data, function (object) {

                        return object.name;
                    }));
                },
            }, response);
        },
        search: function () {
            // custom minLength
            var term = extractLast(this.value);
            if (term.length < MIN_SEARCH) {
                return false;
            }
        },
        focus: function () {
            // prevent value inserted on focus
            return false;
        },
        select: function (event, ui) {
            var terms = split(this.value);
            // remove the current input
            terms.pop();
            // add the selected item
            terms.push(ui.item.value);
            // add placeholder to get the comma-and-space at the end
            terms.push("");
            this.value = terms.join(", ");
            return false;
        }
    });


    //---------------------------------------POST COMMENTAIRE-------------------------------------------------

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
                    var comment = $(data.data[0].html).addClass('popin');
                    $('.comment-section').prepend(comment);

                    $(".formComment input[type=text]").val("");
                    $(".formComment textarea").val("");
                }
            }
            $("#main").prepend("<div class='alert alert-info' role='alert'>" + data.message + "</div>");
            setLoader(false);
        });

        return false;
    });

    //--------------------------------------REGISTER--------------------------------------------------

    $("input[name=name]").after("<span class='info-exist'></span>");
    $("input[name=name]").bind("keyup", function () {

        var input = $(this);
        var valeur = input.val();

        if (valeur.length >= MIN_SEARCH + 2) {

            $.ajax({
                url: "http://monsupersite/checkName",
                method: "post",
                dataType: "json",
                data: {"name": valeur},

            }).done(function (data) {

                $(".info-exist").html(data.message);

                if(data.error) {

                    input.addClass("invalid");
                } else {

                    input.removeClass("invalid");
                }

            });

        } else {

            $(".info-exist").html("");
            input.removeClass("invalid");
        }

    });

    //------------------------------------VOIR PLUS----------------------------------------------------

    var scrollAuto = false;

    $('.voirPlus').click(function () {

        var button = $(this);
        button.detach();
        loadMore();

    });

    function loadMore() {

        $('.pre-footer').append("<img class='loader' src='../images/loading.gif' alt='' />");

        $.ajax({

            url: "http://monsupersite/listeNews/",
            method: "post",
            dataType: "json",
            data: {
                "offset": $(".news").length
            }

        }).done(function (data) {

            if (data.data.length > 0) {

                scrollAuto = true;

                for (var news in data.data) {

                    $('.pre-footer').before("<div class='news fadein'>" + data.data[news].titre + "<p>" + data.data[news].content + "</p></div>");

                }

                $(".loader").detach();

            } else {

                scrollAuto = false;
                $('.pre-footer').before("<p style='text-align: center'>Vous êtes arrivé à la fin !</p>");
                $(".loader").detach();

            }
        });

    }

    $(window).scroll(function () {

        if ($(window).scrollTop() + $(window).height() == $(document).height()) {


            if (scrollAuto) {
                scrollAuto = false;
                loadMore();

            }

        }

    });


    //--------------------CLOUD SYSTEM--------------------------------------------------------------------

    $(".cloud a").each(function () {

        $(this).css("fontSize", $(this).parent().attr("data-poids") * 5);

    });


    //--------------------CHECK BOX FILTER--------------------------------------------------------------------
    $("#check-news").click(function () {

        $(".type-news").slideToggle(500);

    });

    $("#check-comment").click(function () {

        $(".type-comment").slideToggle(500);

    });


});


//--------------------FONCTIONS-------------------------------------------------------------------
function split(val) {
    return val.split(/,\s*/);
}

function extractLast(term) {
    return split(term).pop();
}


function setLoader(active) {

    if (active) {

        $(".formComment input[type=submit]").css('display', 'none');
        $(".formComment").append("<img class='loader' src='../images/loading.gif' alt='' />");

    } else {

        $(".formComment input[type=submit]").css('display', 'block');
        $(".loader").detach();

    }

}