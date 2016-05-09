$(document).ready(function () {


    $(".formComment").submit(function () {

        var url = $(location).attr("href");
        var res = url.replace("news", "commenter");

        $.ajax({
            url:res,
            method:"post",
            dataType: "json",
            data: {
                "auteur": $(".formComment input[type=text]").val(),
                "contenu" : $(".formComment textarea").val()
            }

        }).done(function (data) {

            if(data.error != true) {

                $('.comment-section').empty();
                $('.comment-section').append(data.data);

                $(".formComment input[type=text]").val("");
                $(".formComment textarea").val("");
            }
        });


        return false;
    });

});