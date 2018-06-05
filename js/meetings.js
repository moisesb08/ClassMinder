var parentID;

$(document).on('change','.selectDate', function () {
    let startDate = document.getElementById("startDate").value;
    let endDate = document.getElementById("endDate").value;
    getTable(startDate, endDate);
    //let message = " start: "+start+"\n end:"+end;
    //$("#slotsDiv").html(message);
    //var myDate = new Date($(this).val());
    //alert(myDate, myDate.getTime());

});

function overBtn(obj)
{
    obj.value = "Schedule Now";
}

function outBtn(obj)
{
    obj.value = "Select Meeting";
}

function getDescription(obj) {
    let description = prompt("Please enter the reason for the scheduling a meeting:", "");
    if (description=='') {
        alert("A reason is needed to schedule a meeting.");
        return false;
    }
    else if(description == null)
    {
        return false;
    }
    else
    {
        obj.value = description;
        return true;
    }
}

function getTable(startDate, endDate)
{
    $.ajax({
        url:"./meetingsTable.php",
        data: { 
        'parentID': parentID,
        'startDate': startDate,
        'endDate': endDate
        },
        method:"POST",
        success: function(data){
            $("#slotsDiv").html(data);
        }
    });
}

function begin(pID, startDate, endDate)
{
    parentID = pID;
    getTable(startDate, endDate);
}