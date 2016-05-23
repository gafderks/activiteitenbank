/*global baseUrl,Translator*/

/**
 * This script handles editing activities.
 *
 * Depends: jquery, dropzoneInit
 */
Editor = {

    /**
     * Attaches methods to elements in the GUI.
     */
    init: function() {
        "use strict";
        $(".activity-property-edit").click(function() {
            $($(this).data("property-editor")).addClass("active");
            $(this).hide();
        });

        $(".activity-property-cancel").click(function() {
            $($(this).data("property-editor")).removeClass("active");
            $($(this).data("property-edit")).show();
        });

        $(".activity-prop-ok").click(function() {

            // set field
            var values = [];
            var checkedInputs = $('input[name='+$(this).data("prop-radio")+']:checked');
            checkedInputs.each(function(index) {
                values.push($(this).val());
            });
            $($(this).data("prop-field")).val(values.join(","));

            // set label
            var labels = [];
            checkedInputs.each(function(index) {
                labels.push($(this).data("prop-text"));
            });
            $($(this).data("prop-label")).text(labels.join(", "));

            // hide editor
            $($(this).data("prop-editor")).removeClass("active");

            $($(this).data("property-edit")).show();
        });

        $(".activity-prop-ok-groupsize").click(Editor.updateGroupSize);

        // groupsize editor
        $("#groupsize-unknown").change(function() {
            var inputMinimum = $("#groupsize-minimum");
            var inputMaximum = $("#groupsize-maximum");
            var inputNoMaximum = $("#groupsize-no-maximum");
            if ($(this).is(":checked")) {
                inputMinimum.prop("disabled", true);
                inputMaximum.prop("disabled", true);
                inputNoMaximum.prop("disabled", true);
                inputMinimum.val("");
                inputMaximum.val("");
                inputNoMaximum.prop("checked", false);
            } else {
                inputMinimum.prop("disabled", false);
                inputMaximum.prop("disabled", false);
                inputNoMaximum.prop("disabled", false);
                inputMinimum.val("1");
                inputMaximum.val("1");
            }
        });

        $("#groupsize-no-maximum").change(function() {
            var inputMaximum = $("#groupsize-maximum");
            if ($(this).is(":checked")) {
                inputMaximum.prop("disabled", true);
                inputMaximum.val("");
            } else {
                inputMaximum.prop("disabled", false);
                inputMaximum.val("1");
            }
        });

        $(".badge-selectors>li>label>input[type=checkbox]").change(function() {
            $(this).parent().parent().toggleClass("active");
        });


        $(".badge-selectors>li>label>input[type=checkbox]:checked").parent().parent().addClass("active");



        $(".wizard-label").click(function(e) {

            e.preventDefault();
            var hash = $(this).attr("data-wizard-tab");
            window.location.hash = "#"+hash;

            $(".wizard-label").removeClass("active");
            $(".wizard-content").removeClass("active");

            $(this).addClass("active");
            $("#"+$(this).attr("data-wizard-tab")).addClass("active");

            return false;
        });

        Editor.initWysiwyg();

        Editor.initDropzone();


    },

    /**
     * Initializes the WYSIWYG for the elaboration of the activity.
     */
    initWysiwyg: function () {
        "use strict";
        CKEDITOR.replace('activityElaboration', {
            height: 250,
            // Add plugins providing functionality popular in BBCode environment.
            extraPlugins: 'bbcode,smiley,font,colorbutton',
            // Remove unused plugins.
            removePlugins: 'filebrowser,format,horizontalrule,pastetext,pastefromword,scayt,showborders,stylescombo,table,tabletools,wsc',
            // Remove unused buttons.
            removeButtons: 'Anchor,BGColor,Font,Strike,Subscript,Superscript',
            // Width and height are not supported in the BBCode format, so object resizing is disabled.
            disableObjectResizing: true,
            // Define font sizes in percent values.
            fontSize_sizes: "30/30%;50/50%;100/100%;120/120%;150/150%;200/200%;300/300%",
            // Strip CKEditor smileys to those commonly used in BBCode.
            smiley_images: [
                'regular_smile.png', 'sad_smile.png', 'wink_smile.png', 'teeth_smile.png', 'tongue_smile.png',
                'embarrassed_smile.png', 'omg_smile.png', 'whatchutalkingabout_smile.png', 'angel_smile.png',
                'shades_smile.png', 'cry_smile.png', 'kiss.png'
            ],
            smiley_descriptions: [
                'smiley', 'sad', 'wink', 'laugh', 'cheeky', 'blush', 'surprise',
                'indecision', 'angel', 'cool', 'crying', 'kiss'
            ],
            skin: '../../ckeditor-skin-minimalist',
            resize_minHeight: 500,
            language: Translator.translate('en')
        });
    },

    /**
     * Initializes the Dropzone control.
     */
    initDropzone: function () {
        "use strict";
        Dropzone.options.attachmentsDropzone = {
            dictDefaultMessage: Translator.translate("Drop files here to upload"),
            dictFallbackMessage: Translator.translate("Your browser does not support drag'n'drop file uploads."),
            dictFallbackText: Translator.translate("Please use the fallback form below to upload your files like in the olden days."),
            dictInvalidFileType: Translator.translate("You can't upload files of this type."),
            dictFileTooBig: Translator.translate("File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB."),
            dictResponseError: Translator.translate("Server responded with {{statusCode}} code."),
            dictCancelUpload: Translator.translate("Cancel upload"),
            dictCancelUploadConfirmation: Translator.translate("Are you sure you want to cancel this upload?"),
            dictRemoveFile: Translator.translate("Remove file"),
            dictMaxFilesExceeded: Translator.translate("You can not upload any more files."),
            addRemoveLinks: true,
            paramName: "file",
            headers: {"Authorization": "Bearer " + authToken},
            init: dropzoneInit
        };
    },

    /**
     * Updates the group size control. Is called by the OK button.
     * Makes sure that no illegal group size is set.
     */
    updateGroupSize: function() {
        "use strict";
        // set field
        var minimum;
        var maximum;
        var proplabel;

        if ($("#groupsize-unknown").is(":checked")) {
            minimum = null;
            maximum = null;
            proplabel = Translator.translate("Unspecified");
        } else {
            var inputMinimum = $("#groupsize-minimum");
            if (parseInt(inputMinimum.val()) > 0) {
                minimum = inputMinimum.val();
            } else { alert(Translator.translate("Minimum group size needs to be at least 1")); return; }

            if ($("#groupsize-no-maximum").is(":checked")) {
                maximum = null;
                proplabel = minimum + " " + Translator.translate("or more participants");
            } else {
                var inputMaximum = $("#groupsize-maximum");
                if (parseInt(inputMaximum.val()) > 0) {
                    if (parseInt(inputMaximum.val()) >= parseInt(inputMinimum.val())) {
                        maximum = inputMaximum.val();
                        proplabel = minimum + " - " + maximum + " " + Translator.translate("participants");
                    } else { alert(Translator.translate("Maximum group size needs to larger than or equal to minimum group size")); return; }
                } else { alert(Translator.translate("Maximum group size needs to be at least 1")); return; }
            }
        }
        $("#activity-prop-groupsize-min").val(minimum);
        $("#activity-prop-groupsize-max").val(maximum);

        // set label
        $($(this).data("prop-label")).text(proplabel);

        // hide editor
        $($(this).data("prop-editor")).removeClass("active");

        $($(this).data("property-edit")).show();
    },

    /**
     * Saves the activity according to the fields on the page.
     *
     * @param apiUrl URL of the API endpoint
     */
    save: function(apiUrl) {
        "use strict";
        $("#save-button").attr("disabled", "disabled");
        $("#status-saving").show();
        $.ajax({
            type: "POST",
            url: apiUrl,
            data: JSON.stringify(Editor.generateActivityObject()),
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
            }
        })
        .done(function(msg, textStatus, xhr) {
            console.log(msg);
            if (xhr.status === 201) {
                window.location.href = baseUrl + "/edit/" + msg.id + "/" + msg.slug;
            } else {
                alert(Translator.translate("Saving failed..."));
            }
        })
        .fail(function (a, b) {
            console.log("No luck..." + b);
            alert(Translator.translate("Saving failed... Are you still connected?"));
            $("#save-button").removeAttr("disabled");
        });
    },

    /**
     * Updates the activity according to the fields on the page.
     *
     * @param apiUrl URL of the API endpoint
     */
    update: function (apiUrl) {
        "use strict";
        $("#update-button").attr("disabled", "disabled");
        $("#status-saving").show();
        $.ajax({
            type: "PUT",
            url: apiUrl,
            data: JSON.stringify(Editor.generateActivityObject()),
            beforeSend: function(xhr, settings) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
            }
        })
        .done(function(msg, textStatus, xhr) {
            console.log(msg);
            if (xhr.status === 202) {
                $("#update-button").removeAttr("disabled");
                $("#status-saving").hide();
                $("#status-saved").show();
                setTimeout(function() { $("#status-saved").fadeOut(); }, 2000);
            } else {
                $("#update-button").removeAttr("disabled");
                $("#status-saving").hide();
                alert(Translator.translate("Saving failed..."));
            }
        })
        .fail(function (a, b) {
            console.log("No luck..." + b);
            $("#status-saving").hide();
            alert(Translator.translate("Saving failed... Are you still connected?"));
            $("#update-button").removeAttr("disabled");
        });
    },

    /**
     * Deleted the activity.
     *
     * @param apiUrl URL of the API endpoint
     */
    delete: function(apiUrl) {
        "use strict";
        if (confirm(Translator.translate("Are you sure you want to delete this activity?"))) {
            $.ajax({
                type: "DELETE",
                url: apiUrl,
                beforeSend: function(xhr, settings) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + authToken);
                }
            })
            .done(function(msg, textStatus, xhr) {
                console.log(msg);
                if (xhr.status === 204) {
                    window.location.href = baseUrl;
                } else {
                    alert(Translator.translate("Deleting failed..."));
                }
            })
            .fail(function (a, b) {
                console.log("No luck..." + b);
                alert(Translator.translate("Deleting failed... Are you still connected?"));
            });
        }
    },

    /**
     * Generates an object from the current inputs representing the activity.
     * This object can be posted to the API.
     *
     * @returns {{}}
     */
    generateActivityObject: function () {
        "use strict";

        var actObj = {};
        // title
        actObj.title = $("#activity-title").val();

        actObj.id = $("#activity-id").val();

        // category
        var categoryArray = $("#activity-prop-category").val().split(",").map(Number);
        actObj.category = categoryArray;

        // difficulty
        actObj.difficulty = parseInt($("#activity-prop-difficulty").val());

        // guidance
        actObj.guidance = parseInt($("#activity-prop-guidance").val());

        // motivation
        actObj.motivation = parseInt($("#activity-prop-motivation").val());

        // group size
        var groupSizeMinimum = $("#activity-prop-groupsize-min").val();
        var groupSizeMaximum = $("#activity-prop-groupsize-max").val();
        if (groupSizeMinimum === "") {
            actObj.groupSizeMin = null;
        } else {
            actObj.groupSizeMin = parseInt(groupSizeMinimum);
        }
        if (groupSizeMaximum === "") {
            actObj.groupSizeMax = null;
        } else {
            actObj.groupSizeMax = parseInt(groupSizeMaximum);
        }

        // visibility
        actObj.visibility = parseInt($("#activity-prop-visibility").val());

        // activity areas
        var activity_areas = [];
        $("input[name=activity-badge-selectors]:checked").each(function () {
            activity_areas.push(parseInt($(this).val()));
        });
        actObj.activityAreas = activity_areas;
        // suitable groups
        var groups = [];
        $("input[name=suitable-group-badge-selectors]:checked").each(function () {
            groups.push($(this).val());
        });
        actObj.groups = groups;

        // elaboration
        actObj.elaboration = CKEDITOR.instances.activityElaboration.getData();

        // planning
        var planning = [];
        $("#list-planning").find("li:not(.list-new-action)").each(function () {
            var planning_part = {};
            planning_part.duration = timeToIntMins($(this).find("[data-list-field=endurance]").val());
            planning_part.description = $(this).find("[data-list-field=name]").val();

            planning.push(planning_part);
        });
        actObj.planning = planning;

        // preparations checklist
        var checklist = [];
        $("#list-preparations").find("li:not(.list-new-action)").each(function () {
            checklist.push($(this).find("[data-list-field=name]").val());
        });
        actObj.checklist = checklist;


        // materials
        var materials = [];
        $("#list-materials").find("li:not(.list-new-action)").each(function () {
            var materials_part = {};
            materials_part.amount = parseInt($(this).find("[data-list-field=amount]").val());
            materials_part.description = $(this).find("[data-list-field=name]").val();

            materials.push(materials_part);
        });
        actObj.materials = materials;

        // budgetary
        var budgetary = [];
        $("#list-budgetary").find("li:not(.list-new-action):not(.list-total)").each(function () {
            var budgetary_part = {};
            budgetary_part.amount = parseInt($(this).find("[data-list-field=amount]").val());
            budgetary_part.description = $(this).find("[data-list-field=name]").val();
            budgetary_part.cost = currencyToFloat($(this).find("[data-list-field=price]").val());

            budgetary.push(budgetary_part);
        });
        actObj.budget = budgetary;


        return actObj;
    }

};