/*global baseUrl,Translator*/

/**
 * This script handles updating ratings.
 *
 * Depends: jquery
 */
Rating = {

    /**
     * Submits the rating to the specified URL.
     * @param apiUrl url to send the rating to
     */
    submit: function(apiUrl) {
        "use strict";
        $.ajax({
            type: "PUT",
            url: apiUrl,
            data: JSON.stringify({rate: parseInt($("#my-rating").rating('rate'))}),
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
            }
        })
        .done(function(msg, textStatus, xhr) {
            console.log(msg);
            if (xhr.status != 201) {
                alert(Translator.translate("Unable to save rating..."));
            }
        })
        .fail(function (a, b) {
            console.log("No luck..." + b);
            alert(Translator.translate("Unable to save rating..."));
        });
    }

};