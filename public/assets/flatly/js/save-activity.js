function generateActivityObject() {
    "use strict";

    var actObj = {};
    // title
    actObj.title = $("#activity-title").val();

    actObj.slug = convertToSlug(actObj.title);

    actObj.id = $("#activity-id").val();

    // category
    actObj.category = parseInt($("#activity-prop-category").val());

    // difficulty
    actObj.difficulty = parseInt($("#activity-prop-difficulty").val());

    // guidance
    actObj.guidance = parseInt($("#activity-prop-guidance").val());

    // motivation
    actObj.motivation = parseInt($("#activity-prop-motivation").val());

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
    actObj.elaboration = $("#activity-elaboration").summernote('code');

    // planning
    var planning = [];
    $("#list-planning").find("li:not(.list-new-action)").each(function () {
        var planning_part = {};
        planning_part.duration = timeToIntMins($(this).find("[data-list-field=endurance]").val());
        planning_part.name = $(this).find("[data-list-field=name]").val();

        planning.push(planning_part);
    });
    actObj.planning = planning;

    // preparations
    var preparations = [];
    $("#list-preparations").find("li:not(.list-new-action)").each(function () {
        preparations.push($(this).find("[data-list-field=name]").val());
    });
    actObj.preparations = preparations;


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
        budgetary_part.price = currencyToFloat($(this).find("[data-list-field=price]").val());

        budgetary.push(budgetary_part);
    });
    actObj.budget = budgetary;


    return actObj;
}

function convertToSlug(text) {
    "use strict";
    return text
        .toLowerCase()
        .replace(/[^\w ]+/g, '')
        .replace(/\s+/g, '-')
        ;
}
    
    
