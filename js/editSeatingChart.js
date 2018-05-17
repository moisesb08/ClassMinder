//console.log(Object.keys(quesArr).length); //assoc array length
let keyValueFunc = function(item, index)
{
    alert("item:"+item +" index:"+index);
}

let getValue = function(divCell)//item
{
    let button = divCell.getElementsByTagName("BUTTON");
    //alert(button.length);
    if (button.length==1)
    {
        let targetVal = divCell.getAttribute('data-value');
        let id = button[0].value;
        addValues(id, targetVal);
        //alert("getValues() - id:"+id+" target:"+targetVal);
    }
    
}

function addToArr(item, index)
{
    let result = ',"'+index+'":"'+item+'"';
    return result;
}

function createArr(tempArr)
{
    let arr = '{"classroomID":"' + classroomID + '"';
    tempArr.forEach(function(item, index)
    {
        arr += addToArr(item, index);
    });
    arr+='}';
    return arr;
}

function getValues()
{
    classroomID = document.getElementById("classroomID").value;
    let seatDivs = document.getElementsByClassName("divCell");
    [].forEach.call(seatDivs, getValue);
}

function addValues(id, targetVal)
{
    //alert(targetVal);
    let partsOfStr = targetVal.split(':');
    values[id] = partsOfStr;
}

function allowDrop(ev)
{
    ev.preventDefault();
}

function drag(ev)
{
    ev.dataTransfer.setData("text", ev.target.id);
}

function drop(ev)
{
    var data = ev.dataTransfer.getData("text");
	if(ev.target.innerHTML.includes("<")||ev.target.tagName=="I")
    	return;
    ev.preventDefault();
    var partsOfStr = ev.target.getAttribute('data-value').split(':');
    let id = document.getElementById(data).value;
    addValues(id, ev.target.getAttribute('data-value'));
    ev.target.appendChild(document.getElementById(data));
}

function touchHandler(event)
{
    var touch = event.changedTouches[0];

    var simulatedEvent = document.createEvent("MouseEvent");
        simulatedEvent.initMouseEvent({
        touchstart: "mousedown",
        touchmove: "mousemove",
        touchend: "mouseup"
    }[event.type], true, true, window, 1,
        touch.screenX, touch.screenY,
        touch.clientX, touch.clientY, false,
        false, false, false, 0, null);

    touch.target.dispatchEvent(simulatedEvent);
}

function init()
{
    document.addEventListener("touchstart", touchHandler, true);
    document.addEventListener("touchmove", touchHandler, true);
    document.addEventListener("touchend", touchHandler, true);
    document.addEventListener("touchcancel", touchHandler, true);
}

function updateSeating()
{
    //values.forEach(updateStudentClass);
    resultArr = createArr(values);
    var obj = JSON.parse(resultArr);
    post("arr", obj, "../php/updateStudentClass.php");
}

let getResults = function(data)
{
    alert(data);
}
function post(postName, postValue, actionPage)
{
    let arr = JSON.stringify(postValue);
    $.post(actionPage, {"arr":arr}, getResults)
}
init();
let values = [];
let classroomID;