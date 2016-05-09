$(document).ready(function () {


    $(".formComment").submit(function () {

        var url = $(location).attr("href");
        var res = url.replace("news", "commenter");

        if ($(".formComment input[type=text]").val() == "")
            return false;

        if($(".formComment textarea").val() == "")
            return false;

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

                $('.comment-section').append(data.data[0].html);

                $(".formComment input[type=text]").val("");
                $(".formComment textarea").val("");
            }
            $("#main").prepend("<div class='alert alert-info' role='alert'>"+data.message+"</div>");
        });

        return false;
    });

});