var sizeX = 20;
var sizeY = 20;
var grid = {};

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

function getJSONObject() {
    var obj = {
        sizeX: sizeX,
        sizeY: sizeY,
        grid: grid
    };
    return obj;
}

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

$(document).ready(function() {
    var x, y;
    for(y=0; y<sizeY; y++) {
        grid[y] = [];
        for(x=0; x<sizeX; x++)
            grid[y][x] = 0;
    }
    displayGrid(grid);

    $('#btn_step').click(function() {
        $.post('json.php', {jsondata: getJSONObject(), steps: 1})
            .done(function(data) {
                data = jQuery.parseJSON(data);
                displayGrid(data.grid);
            });
    });

    $('#btn_clear').click(function() {
        $.post('json.php', {sizeX: sizeX, sizeY: sizeY, clear:true})
            .done(function(data) {
                data = jQuery.parseJSON(data);
                displayGrid(data.grid);
            });
    });

    $('#btn_random').click(function() {
        $.post('json.php', {sizeX: sizeX, sizeY: sizeY, random:true})
            .done(function(data) {
                data = jQuery.parseJSON(data);
                displayGrid(data.grid);
            });
    });
});