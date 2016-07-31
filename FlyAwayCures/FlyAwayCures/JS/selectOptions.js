
/* global google */

// 
function selectSelection()
{
   $(".selection").change(function() {
    
       $(".selection").each(function() {
           // If both buttons will be enabled else will be disabled
            if(selected)
            {
                $("#restart_btn").hide();
                $("#next_btn]").hide();
            }
            else
            {
                $("#restart_btn").hide();
                $("#next_btn]").hide();        
            }
        });
    });  
}

// Getting value of a checkbox
function getCheckedRow()
{
    var row = $(":checked").closest("tr").attr('id');
    
    return row;
}

function hideButton(rowNum)
{
    $("#hid_btn" + rowNum).show();
}

// Parsing row
function parseRow(row)
{
    // Splitting row (by _)
    var rowArr = row.split('_');
    
    return rowArr;
}

// Getting the row data and showing google map
function getRowAndShowMap()
{
    // Get raw row data
    var rawRow = getCheckedRow();
    // Get parsed row
    var parsedRow = parseRow(rawRow);
    // Get coordinates
    var coordinates = parsedRow[4].split(/\|/);
    // Get row number
    var num = parsedRow[5];

    $("#hid_btn" + num).show();
   
    // Initializing marked map with the set coordinates
    google.maps.event.addDomListener(window, 'load', initializeMarkedMap(coordinates[0],coordinates[1],12,'map2', parsedRow[1]));
}

// This function allows only one checkbox to be selected
function singleSelection()
{
    $("input[type='checkbox']").on('change', function()
    {
        $("input[type='checkbox']").not(this).prop('checked', false);
        $("input[type='submit']").not(this).hide();
    }); 
}

// Getting the parsed row data (for the current checked row)
function getParsedRowData()
{
    // Get raw row data
    var rawRow = getCheckedRow();
    // Get parsed row
    var headers = parseRow(rawRow);
    // Array for pushing row data
    var rowData = [];
    
    var mainHeaderRow = "<thead><th class='flight_info' colspan='10' name='location_header'>From: " + headers[0] + " To: " + headers[1] + " From: " + headers[2] + " To: " + headers[3] + "</th>";
    var secondHeader =  "<tr class='header_class' name='column_header' nowrap><td>Price</td><td>Flight Out</td><td>Airline</td>" +
        "<td>Time</td><td>Flight In</td><td>Airline</td><td>Time</td><td colspan='3'>Additional Stops</td></thead>";    

    // Pushing header in the rowData
    rowData.push(mainHeaderRow);
    rowData.push(secondHeader);

    // Pushing opening table row <tr> 
    rowData.push("<tbody><tr class='flights_tbl' name='selected_flight' id='" + rawRow + "'>");

    $(":checked").closest("tr").children('td').each( function()
    {
        if($(this).text() !== '')
        {
            rowData.push("<td>");

            rowData.push($(this).text());

            rowData.push("</td>");
        }

    });

    // Pushing closing table row <tr> 
    rowData.push("</tr></tbody>");

    return rowData;
}

// Setting the hidden label's value
function setHiddenLabel(data)
{
    $("#hidden_lbl").val(data);
}

/* 
 * Deprecated -- Using setHiddenLabel since scope
 * was changed (needed to use PHP)
 */
function setSelectedFlightTableRow(data)
{
    $("input[type='sbumit']").on('click', function()
    {
        var tableRow = [];
        
        for(i = 0; i<= data.length; i++) {
//            tableRow.push("<td>");
            tableRow.push(data[i]);
//            tableRow.push("</td>");
        }

        $('#selected_flight').text(tableRow);
    });
}

// Getting the price for the (user) selected flight
function getSelectedFlightPrice()
{
   return $('tr td:first-child').text().replace("Price€ ", "") + ".00";
}

// Working out price according to the number of people
function computePrice()
{
    var price = getSelectedFlightPrice();
    // Resetting to original border
    removeErrorOnInputField('no_of_ppl');
    
    var ppl = $('#no_of_ppl').val();
    var newPrice = null;
    
    if(ppl == 0)
    {
        newPrice = price * 1;
        setPriceLblText(newPrice + '.00');
        setHiddenTotalPriceText(newPrice + '.00')
    }
    else if(ppl > 5)
    {
        $('#no_of_ppl').val('Max 5!');
        // Resetting to error border
        setErrorOnInputField('no_of_ppl');
        newPrice = 'Invalid';
        setPriceLblText(newPrice);
    }
    else if(!$.isNumeric(ppl) && getPriceLblText() == 'Invalid')
    {
        // Avoiding to display NaN since the text box is not numerical
        setErrorOnInputField('no_of_ppl');        
        newPrice = 'Invalid';
        setPriceLblText(newPrice);
    }
    else if(!$.isNumeric(ppl))
    {
        // Avoiding to display NaN since the text box is not numerical
        setErrorOnInputField('no_of_ppl');        
        newPrice = 'Invalid';
        setPriceLblText(newPrice);
    }
    else
    {
        // Resetting to original border
        removeErrorOnInputField('no_of_ppl');
        newPrice = price * ppl;
        setPriceLblText(newPrice + '.00');
        setHiddenTotalPriceText(newPrice + '.00');
    }
}
// Setting an error on an input field
function setErrorOnInputField(id)
{
    $('#' + id).css('border', '3px solid red');
}

// Removing an error from an input field
function removeErrorOnInputField(id)
{
    $('#' + id).css('border', '0px solid #19A3D1');
}

// Setting the hidden total price label (will not be visible on screen)
function setHiddenTotalPriceText(newPrice)
{
    $('#hid_ttl_price').text(newPrice);    
}

// Setting the update price label (to show the updated price on screen)
function setPriceLblText(newPrice)
{
    $('#price_lbl').text(newPrice);    
}

// Getting the price label's text
function getPriceLblText()
{
    return $('#price_lbl').text();    
}

function getSelectedFlightData()
{
    // Cleaning the no_of_ppl field on initiating this function
    removeErrorOnInputField('no_of_ppl');
    
    var rawId = parseRow($('#sel_flight_tbl').find('tbody').find('tr').attr('id'));
    var coordinates = rawId[4];
    
    var head = $('#sel_flight_tbl').find('th').text();
    var row = '';
    var numPpl = $('#no_of_ppl').val();
    var totalPrice = 'EUR' + $('#hid_ttl_price').text();
    var data = [];
    
    if(numPpl == '')
    {
        setErrorOnInputField('no_of_ppl');
        return;
    }

    var amount = null;

    $('#sel_flight_tbl').find('tbody').find('tr').children('td').each(function()
    {
        if(row == '')
        {
            amount = $(this).text();
            
            row = amount.replace('€ ', 'EUR') + '.00';
        }
        else
        {
            row = row + "|" + $(this).text();                        
        }
    });
    
    data.push(coordinates, head, row, numPpl, totalPrice);
    
    return data;
}