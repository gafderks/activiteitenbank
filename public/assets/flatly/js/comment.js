/*global baseUrl,Translator*/

/**
 * This script handles the creation of comments.
 *
 * Depends: jquery
 */
Comment = {

    /**
     * Submits the comment to the specified URL and then reloads the page.
     * @param apiUrl url to send the comment to
     */
    submit: function(apiUrl) {
        "use strict";
        if ($("#comment-body").val().length < 1) {
            alert(Translator.translate("You need to supply a message"));
        } else {
            $.ajax({
                type: "POST",
                url: apiUrl,
                data: JSON.stringify({comment: $("#comment-body").val(), didIt: $("#comment-didit").is(":checked")}),
                beforeSend: function (xhr, settings) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
                }
            })
            .done(function (msg, textStatus, xhr) {
                console.log(msg);
                if (xhr.status != 201) {
                    alert(Translator.translate("Unable to save comment..."));
                } else {
                    $("#comment-body").val(""); // clear the comment box
                    location.reload(true);
                }
            })
            .fail(function (a, b) {
                console.log("No luck..." + b);
                alert(Translator.translate("Unable to save comment..."));
            });
        }
    }

};