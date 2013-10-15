var sizeX = 40;
var sizeY = 50;
var grid = {};
var autorun = false;

// Get a specific Grid Cell by its Coordinates. If it doesn't exist, create it
function getCell(x, y) {
    var cellname = 'cell_'+y+'_'+x;
    var cell = $('#'+cellname);
    if(!cell.length) {
        $('#conwaygrid').append('<div id="'+cellname+'" class="cell"></div>');
        cell = $('#'+cellname);

        cell.css("top", x*20);
        cell.css("left", y*20);

        cell.click(function() {
            $(this).toggleClass('alive');
            grid[y][x] = 1 - grid[y][x];
        });
    }
    return cell;
}

// Get JSON Object for API Request
function getJSONObject() {
    var obj = {
        sizeX: sizeX,
        sizeY: sizeY,
        grid: grid
    };
    return JSON.stringify(obj);
}

// Handle API Result Data
function handleData(data, withautorun) {
    if(data.indexOf("<div") >= 0) {
        $('#debug').html(data);
        $('#debug').show();
    } else {
        data = jQuery.parseJSON(data);
        displayGrid(data.grid);

        if(autorun && withautorun)
            setTimeout("step()", 1000);
    };
}

// Display Grid Data
function displayGrid(dGrid) {
    for(x=0; x<sizeX; x++) {
        for(y=0; y<sizeY; y++) {
            cell = getCell(x,y);
            if((dGrid[y][x] == 1) || (dGrid[y][x] == "1"))
                cell.addClass('alive');
            else
                cell.removeClass('alive');
        }
    }
    grid = dGrid;
}

// Do a single Step and display it
function step() {
    $.post('json.php', {steps: 1, jsondata: getJSONObject()})
        .done(function(data) {
            handleData(data, true);
        });
}



$(document).ready(function() {
    var x, y;
    for(y=0; y<sizeY; y++) {
        grid[y] = [];
        for(x=0; x<sizeX; x++)
            grid[y][x] = 0;
    }
    displayGrid(grid);

    // Do a single Step
    $('#btn_step').click(function() {
        step();
    });

    // Clear the Grid
    // Yes i am aware that i could do that without sending a request to the PHP API, but thats not the point
    $('#btn_clear').click(function() {
        $.post('json.php', {sizeX: sizeX, sizeY: sizeY, clear:true})
            .done(function(data) {
                handleData(data, false);
            });
    });

    // Randomize the Grid
    $('#btn_random').click(function() {
        $.post('json.php', {sizeX: sizeX, sizeY: sizeY, random:true})
            .done(function(data) {
                handleData(data, false);
            });
    });

    // Enable/Disable Autorun
    $('#btn_autorun').click(function() {
        autorun = !autorun;
        if(autorun) {
            step();
            $('#btn_autorun').html('Stop');
            $('#btn_step').hide();
        } else {
            $('#btn_autorun').html('Autorun');
            $('#btn_step').show();
        }
    });

    // Hide Debug Window
    $('#debug').click(function() {
        $('#debug').hide();
    });
});