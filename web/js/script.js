"use strict";

$(document).ready(function () {

    const MIN_SEARCH = 2;

    //---------------------------------------GESTION TAGS-------------------------------------------------

    // initialisation pour la modification de news
    if ($(".update-news input[name=tagString]").length) {
        var terms = split($(".update-news input[name=tagString]").val());
        $(".update-news input[name=tagString]").val("");
    }

    for (var term in terms) {

        $("input[name=tagString]").before(spanTag(terms[term]));
    }

    $("input[name=tagString]").bind("keydown", function (event) {

        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete("instance").menu.active) {
            event.preventDefault();
        }

        if (event.keyCode === $.ui.keyCode.BACKSPACE && this.value.length == 0) {

            $(".input-auto").last().detach();
            event.preventDefault();
        }

        if (event.keyCode === $.ui.keyCode.SPACE && this.value.trim().length > MIN_SEARCH) {

            event.preventDefault();
            $("input[name=tagString]").before(spanTag(this.value.trim()));
            this.value = "";

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
        delay: 500,

        search: function () {

            var term = extractLast(this.value);
            if (term.length < MIN_SEARCH) {
                return false;
            }
        },
        focus: function () {

            return false;
        },
        select: function (event, ui) {

            $("input[name=tagString]").before(spanTag(ui.item.value));
            this.value = "";

            return false;
        }
    });


    $(".insert-news").submit(verifTags);
    $(".update-news").submit(verifTags);

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
                    setTimeout(function () {

                        comment.removeClass("popin");
                    }, 500);

                    if ($(".comment").length == 0) {

                        $('.formComment + p').detach();
                    }

                    $('.comment-section').prepend(comment);

                    $(".formComment input[type=text]").val("");
                    $(".formComment textarea").val("");
                    $(".delete-comment").click(deleteComment);
                }
            }
            $("#main .alert").detach();
            $("#main").prepend("<div class='alert alert-info' role='alert'>" + data.message + "</div>");
            setLoader(false);
        });

        return false;
    });

    //--------------------------------------DELETE COMMENT--------------------------------------------

    $('.delete-comment').click(deleteComment);

    function deleteComment(event) {

        event.preventDefault();
        var a = $(event.target);

        $.ajax({
            url: a.attr("href"),
            method: "get",
            datatype: "json"

        }).done(function (data) {

            if (!data.error) {

                a.parent().parent().addClass("removeComment");
                setTimeout(function () {

                    a.parent().parent().detach();
                }, 500);

            }
            $("#main .alert").detach();
            $("#main").prepend("<div class='alert alert-info' role='alert'>" + data.message + "</div>");
        });

        return false;
    }

    //--------------------------------------REGISTER--------------------------------------------------

    $("input[name=name]").after("<span class='info-exist'></span>");
    $("input[name=name]").bind("keyup", function () {

        var input = $(this);
        var valeur = input.val();

        if (valeur.length >= MIN_SEARCH + 2) {

            typewatch(function () {

                $.ajax({
                    url: "http://monsupersite/checkName",
                    method: "post",
                    dataType: "json",
                    data: {"name": valeur},

                }).done(function (data) {

                    $(".info-exist").html(data.message);

                    if (data.error) {

                        input.addClass("invalid");
                    } else {

                        input.removeClass("invalid");
                    }

                });

            }, 500);

        } else {

            $(".info-exist").html("");
            input.removeClass("invalid");
        }

    });

    var typewatch = function () {
        var timer = 0;  // store the timer id
        return function (callback, ms) {
            clearTimeout(timer);  // if the function is called before the timeout
            timer = setTimeout(callback, ms); // clear the timer and start it over
        }
    }();

    //--------------------------------------EMAIL CHECK-----------------------------------------------
    $("input[name=email]").after("<span class='info-mail'></span>");
    $("input[name=email]").on("keyup", function () {

        var input = $(this);

        typewatch(function () {

            if (validateEmail(input.val())) {

                $(".info-mail").html("Email valide");
                input.removeClass("invalid");
            } else {

                $(".info-mail").html("Email invalide !");
                input.addClass("invalid");
            }

        }, 500);

    });

    //--------------------------------------PASSWORD CONFIRM-------------------------------------------
    $("input[name=password_confirm]").after("<span class='info-passcheck'></span>");

    $("input[name=password_confirm]").bind("keyup", function () {

        var input = $(this);

        typewatch(function () {
            if ($("input[name=password_confirm]").val() == $("input[name=password]").val()) {

                $(".info-passcheck").html("Confirmation valide");
                input.removeClass("invalid");
            } else {

                if ($("input[name=password_confirm]").val().length > MIN_SEARCH) {

                    $(".info-passcheck").html("Mots de passe différents !");
                    input.addClass("invalid");

                } else {

                    $(".info-passcheck").html("");
                    input.removeClass("invalid");
                }

            }

        }, 500);

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

function spanTag(val) {

    return "<span class='input-auto label label-primary'>" + val + "</span>";
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
function verifTags() {

    var input = $("input[name=tagString]");
    var value = "";

    $(".input-auto").each(function () {

        value += $(this).html() + ", ";

    });
    input.val(value);

}

function validateEmail(mail) {
    return (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail));
}