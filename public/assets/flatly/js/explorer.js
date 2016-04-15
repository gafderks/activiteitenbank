/*global baseUrl,Translator*/

/**
 * This script handles exploring activities.
 *
 * Depends: jquery
 */
Explorer = {

    init: function () {
        "use strict";

        var logarithmicSliders = $(".slider.logarithmic");
        logarithmicSliders.slider({
            tooltip: "always",
            scale: "logarithmic"
        });

        logarithmicSliders.change(function() {
            $("#filter-groupsize-ignore").prop("checked", false);
        });

        $(".slider.time").slider({
            tooltip: "always",
            tooltip_split: true,
            formatter: function(value) {
                if (Array.isArray(value)) {
                    var hours1   = Math.floor(value[0] / 60);
                    var minutes1 = String("00" + (value[0] % 60)).slice(-2);
                    var time1 = String(hours1 + ":" + minutes1);
                    var hours2   = Math.floor(value[1] / 60);
                    var minutes2 = String("00" + (value[1] % 60)).slice(-2);
                    var time2 = String(hours2 + ":" + minutes2);
                    return String(time1 + " - " + time2);
                } else {
                    var hours   = Math.floor(value / 60);
                    var minutes = String("00" + (value % 60)).slice(-2);
                    return String(hours + ":" + minutes);
                }
            }
        });

        $(".slider.budget").slider({
            tooltip: "always",
            tooltip_split: true,
            formatter: function(value) {
                if (Array.isArray(value)) {
                    var euros1   = Math.floor(value[0]);
                    var budget1 = "€ " + euros1 + ".00";
                    var euros2   = Math.floor(value[1]);
                    var budget2 = "€ " + euros2 + ".00";
                    return String(budget1 + " - " + budget2);
                } else {
                    var euros = Math.floor(value);
                    return "€ " + euros + ".00";
                }
            }
        });
    },

    /**
     * Filtering method.
     *
     *  This method is called for every activity in the list.
     *  If this method returns false, the activity is hidden.
     *  If this method returns true, the activity is shown.
     *
     * @param settings
     * @param data
     * @param dataIndex
     * @returns {boolean}
     */
    filter: function (settings, data, dataIndex) {
        "use strict";
//                var f_category = [];
//                $("[name=filter-category]:checked").each(function() {
//                    f_category.push($(this).data("filter-text"));
//                });
//                console.log(f_category);
//
        var name = data[0];
//                var category = data[1];
        var duration = data[2];
        var budget = data[3];
        var difficulty = data[4];
        var guidance = data[5];
        var motivation = data[6];
        var groupsize = data[7];
//                var state = data[7];
//                var activity_areas = data[8];
//                var suitable_groups = data[9];
//                var creator = data[10];


        /** Filter on search term */
        var filterTerm = $('#filter-term').val();
        if (filterTerm.length > 0) { // if nothing is entered, don't filter
            var pattern = new RegExp(filterTerm, "i"); // case-insensitive
            var one = false;
            // look for at least one column that matches the regular expression
            for (var i = 0; i < data.length; i++) {
                if (pattern.test(data[i])) {
                    one = true; // column found
                }
            }
            if (!one) {
                return false; // regular expression was never matched
            }
        }

        var filter;

        /** Filter on duration */
        filter = $("#filter-duration");
        var minDuration = filter.slider('getValue')[0];
        var maxDuration = filter.slider('getValue')[1];
        if (duration > maxDuration || duration < minDuration) {
            return false;
        }

        /** Filter on budget */
        filter = $("#filter-budget");
        var minBudget = filter.slider('getValue')[0];
        var maxBudget = filter.slider('getValue')[1];
        if (budget > maxBudget || budget < minBudget) {
            return false;
        }

        /** Filter on difficulty */
        filter = $("#filter-difficulty");
        var minDifficulty = filter.slider('getValue')[0];
        var maxDifficulty = filter.slider('getValue')[1];
        if (difficulty > maxDifficulty || difficulty < minDifficulty) {
            return false;
        }

        /** Filter on guidance */
        filter = $("#filter-guidance");
        var minGuidance = filter.slider('getValue')[0];
        var maxGuidance = filter.slider('getValue')[1];
        if (guidance > maxGuidance || guidance < minGuidance) {
            return false;
        }

        /** Filter on motivation */
        filter = $("#filter-motivation");
        var minMotivation = filter.slider('getValue')[0];
        var maxMotivation = filter.slider('getValue')[1];
        if (motivation > maxMotivation || motivation < minMotivation) {
            return false;
        }

        /** Filter on group size */
        var ignoreGroupSize = $("#filter-groupsize-ignore").is(":checked");
        filter = $("#filter-groupsize");
        var minGroupSize = groupsize.split(",")[0];
        if (minGroupSize === "null") {
            minGroupSize = null;
        } else {
            minGroupSize = parseInt(minGroupSize);
        }
        var maxGroupSize = groupsize.split(",")[1];
        if (maxGroupSize === "null") {
            maxGroupSize = null;
        } else {
            maxGroupSize = parseInt(maxGroupSize);
        }
        var filteredSize = filter.slider('getValue');
        if (!ignoreGroupSize) {
            if (minGroupSize === null) {
                return false; // do not show items with unknown groupsize
            }
            if (filteredSize < minGroupSize) {
                return false;
            }
            if (maxGroupSize !== null) {
                if (filteredSize > maxGroupSize) {
                    return false;
                }
            }
        }


        // category filter
//                if (f_category.length > 0) {
//                    if (f_category.indexOf(category)==-1) {
//                        return false;
//                    }
//                }
//
        return true;
    }
};