function check() {
    document.getElementById("myCheck").checked = true;
}

function uncheck() {
    document.getElementById("myCheck").checked = false;
}

function start()
{
    let checkbox = document.getElementById("addPositive");
    checkbox.addEventListener( 'change', function() {
    if(this.checked) {
        document.getElementById("positiveTitle").required = true;
        document.getElementById("positiveDescription").required = true;
    } else {
        document.getElementById("positiveTitle").required = false;
        document.getElementById("positiveDescription").required = false;
    }
    });

    checkbox = document.getElementById("addNegative");
    checkbox.addEventListener( 'change', function() {
    if(this.checked) {
        document.getElementById("negativeTitle").required = true;
        document.getElementById("negativeDescription").required = true;
    } else {
        document.getElementById("negativeTitle").required = false;
        document.getElementById("negativeDescription").required = false;
    }
    });
}
