function prettyCurrency(text) {
    "use strict";
    var regexp = /€?\s*(\d+)[,.]?(\d{0,2})\d*/g,
        match = regexp.exec(text);

    var ending = "";

    if (match[2].length === 0) {
        ending = "00";
    } else if (match[2].length === 1) {
        ending = "0";
    }

    return "€ " + match[1] + "." + match[2] + ending;

}

function currencyToFloat(text) {
    "use strict";
    var regexp = /€?\s*(\d+)[,.]?(\d{0,2})\d*/g,
        match = regexp.exec(text);

    var ending = "";

    if (match[2].length === 0) {
        ending = "00";
    } else if (match[2].length === 1) {
        ending = "0";
    }
    
    var full = parseInt(match[1]);
    var part = parseInt(match[2]+ending);

    return full + (part/100);

}

function prettyTime(text) {
    "use strict";
    var regexp = /\s*(\d{0,2})?\s*[hu,.:]?\s*(\d{2})[\d\w]*/g;
    
    var match = regexp.exec(text);

    if (match === null) {
        match = [0, 0, '00'];
    }
    if (match[1]===undefined) {
        match[1] = "0";
    }
    
    return match[1]+":"+match[2];

}

function timeToIntMins(text) {
    "use strict";
    var regexp = /\s*(\d{0,2})?\s*[hu,.:]?\s*(\d{2})[\d\w]*/g;
    
    var match = regexp.exec(text);

    if (match === null) {
        match = [0, 0, '00'];
    }
    if (match[1]===undefined) {
        match[1] = "0";   
    }
    var hours = parseInt(match[1]);
    var minutes = parseInt(match[2]);
    return hours*60+minutes;
}

function minsToTime(mins) {
    "use strict";
    var hours = String(Math.floor(mins/60));
    var minutes = String(mins%60);
    
    if (minutes.length === 1) {
        minutes = "0" + minutes;  
    }
    
    return hours+":"+minutes;
}

$(".remove-action").click(function () {
    "use strict";
    $($(this).parent()).remove();
});

/**
 * Removable list
 * 
 *  Used for preparations
 */

$(".list-removable:not(.list-countable):not(.list-budgetary):not(.list-timeable) .list-new-action").click(function () {
    "use strict";
    $(this).before('<li><input type="checkbox" disabled /><a href="javascript:void(0);" class="remove-action"><span class="glyphicon glyphicon-minus"></span></a><input type="text" data-list="' + $(this).data("list") + '" data-list-field="name" value="" /></li>');
    $(".remove-action").click(function () {
        $($(this).parent()).remove();
    });

    $('input[data-list-field=name]').unbind("keyup");
    
    $('input[data-list-field=name]').keyup(function (e) {
        if (e.keyCode === 13) {
            $(this).parent().parent().find(".list-new-action").trigger("click");
        }
    });

    $($(this).prev()).find("input[data-list-field=name][type=text]").focus();

});

/**
 * Countable list
 * 
 *  Used for materials
 */

$(".list-countable:not(.list-budgetary) .list-new-action").click(function () {
    "use strict";
    $(this).before('<li><a href="javascript:void(0);" class="remove-action"><span class="glyphicon glyphicon-minus"></span></a><input type="number" min="0" class="list-counter" pattern="\d*" data-list="' + $(this).data("list") + '" data-list-field="amount" value="1"><input type="text" data-list="' + $(this).data("list") + '" data-list-field="name" value="" /></li>');
    $(".remove-action").click(function () {
        $($(this).parent()).remove();
    });
    
    $('input[data-list-field=name]').unbind("keyup");

    $('input[data-list-field=name]').keyup(function (e) {
        if (e.keyCode === 13) {
            $(this).parent().parent().find(".list-new-action").trigger("click");
        }
    });

    $($(this).prev()).find("input[data-list-field=name][type=text]").focus();

});

/**
 * Timeable list
 * 
 *  Used for planning
 */

$(".list-timeable").on("updateTimes", function() {
    "use strict";
    var totalMins = 0;
    
    $(this).find("li:not(.list-ignore)").each(function () {
        var timeText = $(this).find(".list-time-endurance").val();
        var time = timeToIntMins(timeText);

        totalMins += time;

        $(this).find(".list-time-cumulative").text(minsToTime(totalMins));

    });
    
    // update graph
    $("#activity-planning-graph").empty();
    var count = 0;    
    $(this).find("li:not(.list-ignore)").each(function () {
        var timeText = $(this).find(".list-time-endurance").val();
        var time = timeToIntMins(timeText);

        var percent = (time/totalMins)*100;

        $("#activity-planning-graph").append('<div class="bg-accent-'+count%5+'" style="width: '+percent+'%" title="'+$(this).find("[data-list-field=name]").val()+' ('+Math.round(percent)+'%)"><span style="width: '+percent+'%;">'+$(this).find("[data-list-field=name]").val()+'</span></div>');
        count++;
    });
    
    $("#activity-prop-duration-label").text(minsToTime(totalMins));
    
    
});

$(".list-timeable .list-new-action").click(function () {
    "use strict";
    $(this).before('<li><a href="javascript:void(0);" class="remove-action"><span class="glyphicon glyphicon-minus"></span></a><input type="text" class="list-time-endurance" data-list="' + $(this).data("list") + '" data-list-field="endurance" value="0:30"><span class="list-time-cumulative" data-list-field="cumulative">0:30</span><input type="text" data-list="' + $(this).data("list") + '" data-list-field="name" value="" /></li>');
    $(".remove-action").click(function () {
        var parent = $(this).parent().parent();
        $($(this).parent()).remove();
        parent.trigger("updateTimes");
    });

    $('input[data-list-field=name]').unbind("keyup");
    
    $('input[data-list-field=name]').keyup(function (e) {
        if (e.keyCode === 13) {
            $(this).parent().parent().find(".list-new-action").trigger("click");
        }
    });
    
    $('input[data-list-field=name]').change(function (e) {
        $(this).parent().parent().trigger("updateTimes");
    });
    
    $(".list-time-endurance").change(function () {
        $(this).val(prettyTime($(this).val()));
        $(this).parent().parent().trigger("updateTimes");
    });

    $($(this).prev()).find("input[data-list-field=name][type=text]").focus();
    
    $(this).parent().trigger("updateTimes");

});

/**
 * Budgetary list
 * 
 *  Used for budgetary
 */

$(".list-budgetary").on("updateSums", function () {
    "use strict";
    var totalsum = 0;

    // update sums
    $(this).find("li:not(.list-new-action):not(.list-total)").each(function () {
        var amount = $(this).find(".list-counter[data-list-field=amount]").val();
        var input = $(this).find(".list-price").val();
        var regexp = /€?\s*(\d+)[,.]?(\d{0,2})\d*/g;
        var match = regexp.exec(input);

        var ending = "";

        if (match[2].length === 0) {
            ending = "00";
        } else if (match[2].length === 1) {
            ending = "0";
        }

        var price = parseFloat(match[1] + "." + match[2] + ending);


        var textprice = String(amount * price);



        $(this).find(".list-price-sum").text(prettyCurrency(textprice));

        totalsum += amount * price;

    });


    // update total
    $(this).find(".list-total-value").text(prettyCurrency(totalsum));
    
    // update property total
    $("#activity-prop-budget-label").text(prettyCurrency(totalsum));
});

$(".list-budgetary .list-new-action").click(function () {
    "use strict";
    $(this).before('<li><a href="javascript:void(0);" class="remove-action"><span class="glyphicon glyphicon-minus"></span></a><input type="number" min="0" class="list-counter" pattern="\d*" data-list="' + $(this).data("list") + '" data-list-field="amount" value="1"><input type="text" class="list-name" data-list="' + $(this).data("list") + '" data-list-field="name" /><input type="text" class="list-price" data-list="' + $(this).data("list") + '" data-list-field="price" value="€ 0.00" /><span class="list-price-sum" data-list="' + $(this).data("list") + '" data-list-field="price-sum"></span></li>');
    $(".list-budgetary .remove-action").click(function () {
        var parent = $(this).parent().parent();
        $($(this).parent()).remove();
        parent.trigger("updateSums");
    });

    $(".list-counter[data-list=" + $(this).data("list") + "]").change(function () {
        $(this).parent().parent().trigger("updateSums");
    });
    
    $('input[data-list-field=name]').unbind("keyup");

    $('input[data-list-field=name]').keyup(function (e) {
        if (e.keyCode === 13) {
            $(this).parent().parent().find(".list-new-action").trigger("click");
        }
    });

    $(".list-budgetary .list-price").change(function () {

        // correct price
        $(this).val(prettyCurrency($(this).val()));

        // update sums
        $(this).parent().parent().trigger("updateSums");
    });

    $($(this).prev()).find("input[data-list-field=name][type=text]").focus();

    $(this).parent().trigger("updateSums");

});


/**
 * Code for document.load
 * 
 *  Used to set triggers for and edit PHP-generated content
 */

$(document).ready(function() {
    
    // assign triggers
    
        // budgetary
            "use strict";
            $(".list-budgetary .remove-action").click(function () {
                $($(this).parent()).remove();
                $("ul#list-budgetary").trigger("updateSums");
            });

            $(".list-counter[data-list=budgetary]").change(function () {
                $(this).parent().parent().trigger("updateSums");
            });

            $('input[data-list-field=name]').unbind("keyup");
            $('input[data-list-field=name]').keyup(function (e) {
                if (e.keyCode === 13) {
                    $(this).parent().parent().find(".list-new-action").trigger("click");
                }
            });

            $(".list-budgetary .list-price").change(function () {

                // correct price
                $(this).val(prettyCurrency($(this).val()));

                // update sums
                $(this).parent().parent().trigger("updateSums");
            });

        // timeable
            $(".list-timeable .remove-action").click(function () {
                $($(this).parent()).remove();
                $("ul#list-planning").trigger("updateTimes");
            });

            $('input[data-list-field=name]').unbind("keyup");
            $('input[data-list-field=name]').keyup(function (e) {
                if (e.keyCode === 13) {
                    $(this).parent().parent().find(".list-new-action").trigger("click");
                }
            });

            $('input[data-list-field=name]').change(function (e) {
                $(this).parent().parent().trigger("updateTimes");
            });

            $(".list-time-endurance").change(function () {
                $(this).val(prettyTime($(this).val()));
                $(this).parent().parent().trigger("updateTimes");
            });
    
    
    // validate input by triggering
    $(".list-budgetary .list-price").trigger("change");
    $(".list-timeable .list-time-endurance").trigger("change");
        
    
    // update dependent fields
    $(".list-budgetary").trigger("updateSums");
    $(".list-timeable").trigger("updateTimes");
    
});
