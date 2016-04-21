/*global baseUrl,Translator,zxcvbn*/

/**
 * This script handles the creation of comments.
 *
 * Depends: jquery,zxcvbn
 */
Login = {

    /**
     * Checks whether the change password submit can be enabled.
     * It is only enabled when the password field and the confirm field have the same value
     * and the score of the password is at least 3.
     *
     * @param password jQuery password field
     * @param confirm  jQuery password confirmation field
     * @param submit   jQuery submit button
     * @param info     jQuery submit information field
     */
    checkEnable: function(password, confirm, submit, info) {
        "use strict";
        var infoText = "";
        if (password.val() === confirm.val() && zxcvbn(password.val()).score >= 3) {
            submit.prop("disabled", false);
        } else {
            submit.prop("disabled", true);
            if (password.val() !== confirm.val()) {
                infoText += "<li>" + Translator.translate("The passwords do not match.") + "</li>";
            }
            if (zxcvbn(password.val()).score < 3) {
                infoText += "<li>" + Translator.translate("The strength of the password is insufficient.") + "</li>";
            }
        }
        info.html("<ul>" + infoText + "</ul>");
    },

    /**
     * Updates a password strength meter.
     * Uses zxcvbn for checking the strength of a password.
     *
     * elem must have a data-field 'meter' consisting of the jQuery selector for the associated meter.
     * elem must also have a data-field 'hint' consisting of the jQuery selector for the associated hint field.
     *
     * @param elem password field
     */
    updatePasswordMeter: function(elem) {
        "use strict";
        var strength = {
            0: Translator.translate("Worst"),
            1: Translator.translate("Bad"),
            2: Translator.translate("Weak"),
            3: Translator.translate("Good"),
            4: Translator.translate("Strong")
        };

        var password = elem;
        var meter = $($(elem).data("meter"));
        var text = $($(elem).data("hint"));

        var val = password.value;
        var result = zxcvbn(val);

        // Update the password strength meter
        meter.val(result.score);

        // Update the text indicator
        if (val !== "") {
            var suggestionList = "";
            for (var i = 0; i < result.feedback.suggestions.length; i++) {
                suggestionList += "<li>" +
                    Translator.translate(result.feedback.suggestions[i].replace(new RegExp('\"', 'g'), "'")) +
                    "</li>";
            }
            text.html(Translator.translate("Strength:") + " " + "<strong>" + strength[result.score] +
                "</strong>" + "<div class='password-feedback'>" +
                Translator.translate(result.feedback.warning.replace(new RegExp('\"', 'g'), "'")) + "<ul> " +
                suggestionList + "</ul></div>");
        }
        else {
            text.innerHTML = "";
        }
    }



};