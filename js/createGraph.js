/*window.onload = function(){
$(document).ready(function(){
    const CHART1 = document.getElementById("chart1");
    const CHART2 = document.getElementById("chart2");
    const CHART3 = document.getElementById("chart3");
    const CHART4 = document.getElementById("chart4");
    const CHART5 = document.getElementById("chart5");
    const CHART6 = document.getElementById("chart6");
    const CHART7 = document.getElementById("chart7");
    getData(CHART1, 'line', 3, -3, 1, 10, 'Moises', 'Bernal', true, true);
    createPieChart(CHART2, 3, 1, 10);
    getData(CHART3, 'bar', 3, -3, 1, 10, 'Moises', 'Bernal', true, true);
    commonBehaviorsChart(CHART4, 'both', 3, 1, 10);
    commonBehaviorsChart(CHART5, 'positive', 3, 1, 10);
    commonBehaviorsChart(CHART6, 'negative', 3, 1, 10);
    //getData(CHART3, 'line', 3, 0, 1, 10, 'Moises', 'Bernal', true, false);
    getData(CHART7, 'bar', 3, 0, 1, 10, 'Moises', 'Bernal', true, false);
    //window.print();
});
};*/

function begin(startDate, endDate, studentID, classroomID, firstName, lastName)
{
    if(studentID==null)
        studentID=1;
    if(classroomID==null)
        classroomID='x';
    const CHART1 = document.getElementById("chart1");
    const CHART2 = document.getElementById("chart2");
    const CHART3 = document.getElementById("chart3");
    const CHART4 = document.getElementById("chart4");
    const CHART5 = document.getElementById("chart5");
    const CHART6 = document.getElementById("chart6");
    const CHART7 = document.getElementById("chart7");
    getData(CHART1, 'line', 3, -3, studentID, classroomID, firstName, lastName, true, true, startDate, endDate);
    createPieChart(CHART2, 3, studentID, classroomID, startDate, endDate);
    getData(CHART3, 'bar', 3, -3, studentID, classroomID, firstName, lastName, true, true, startDate, endDate);
    commonBehaviorsChart(CHART4, 'both', 3, studentID, classroomID, startDate, endDate);
    commonBehaviorsChart(CHART5, 'positive', 3, studentID, classroomID, startDate, endDate);
    commonBehaviorsChart(CHART6, 'negative', 3, studentID, classroomID, startDate, endDate);
    //getData(CHART3, 'line', 3, 0, 1, 10, 'Moises', 'Bernal', true, false);
    getData(CHART7, 'bar', 3, 0, studentID, classroomID, firstName, lastName, true, false, startDate, endDate);
}

function beginSingleChart(studentID, classroomID, startDate, endDate)
{
    const CHART1 = document.getElementById("chart1");
    if(classroomID=='')
        createPieChartNoClass(CHART1, studentID, startDate, endDate);
    else
        createPieChart(CHART1, 3, studentID, classroomID, startDate, endDate);
}

function createChart(constCHART, chartType, chartTitle, minVal, label1, label2, dataArr1, dataArr2, labelsArr, colorsArr,
    hoverColorsArr, borderColorsArr, colorsArr2, hoverColorsArr2, borderColorsArr2, borderW, hoverBorderW, scaleLbl, isResponsive, isStacked)
{
    let lineChart = new Chart(constCHART, {
        type: chartType,
        data: {
            labels: labelsArr,
            datasets: [{
                label: label1,
                data: dataArr1,
                hoverBackgroundColor: hoverColorsArr,
                backgroundColor: colorsArr,
                borderColor: borderColorsArr,
                borderWidth: borderW,
                hoverBorderWidth:hoverBorderW
            },
            {
                label: label2,
                data: dataArr2,
                hoverBackgroundColor: hoverColorsArr2,
                backgroundColor: colorsArr2,
                borderColor: borderColorsArr2,
                borderWidth: borderW,
                hoverBorderWidth:hoverBorderW
            }]
        },
        options: {
            /*elements: {
            line: {
                tension: 0
            }
        },*/
        cubicInterpolationMode: 'default',
            title: {
                display: true,
                text: chartTitle,
                fontColor: "black",
                fontSize: 18
            },
            scales: {
                yAxes: [{
                    stacked: isStacked,
                    ticks: {
                        autoSkip: false,
                        scaleBeginAtZero: false,
                        min: minVal
                    },
                    scaleLabel: {
                        display: true,
                        labelString: scaleLbl,
                        fontColor: "black",
                        fontSize: 16 
                    }
                }],
                xAxes: [{
                    stacked: isStacked,
                    ticks: {
                        autoSkip: false
                    }
                }]
            }
        }
    });
}
function createLineChart(constCHART, chartType, chartTitle, minVal, maxVal, label1, dataArr1, labelsArr, colorsArr,
    hoverColorsArr, borderColorsArr, borderW, hoverBorderW, scaleLbl, isResponsive)
{
    let lineChart = new Chart(constCHART, {
        type: chartType,
        data: {
            labels: labelsArr,
            onAnimationComplete: function() {
        alert('animation complete')
    },
            datasets: [{
                label: label1,
                data: dataArr1,
                hoverBackgroundColor: hoverColorsArr,
                backgroundColor: ['rgba(0,0,0,0)'],
                borderColor: ['rgba(0,0,0,1)'],
                pointBackgroundColor: [colorsArr],
                borderWidth: borderW,
                hoverBorderWidth:hoverBorderW,
            }]
        },
        options: {
            elements: {
            line: {
                tension: 0
            }
            },
            cubicInterpolationMode: 'default',
            legend: {
                display: false,
            },
            title: {
                display: true,
                text: chartTitle,
                fontColor: "black",
                fontSize: 18
            },
            scales: {
                yAxes: [{
                    ticks: {
                        autoSkip: false,
                        scaleBeginAtZero: false,
                        min: minVal,
                        max: maxVal
                    },
                    scaleLabel: {
                        display: true,
                        labelString: scaleLbl,
                        fontColor: "black",
                        fontSize: 16 
                    }
                }],
                xAxes: [{
                    ticks: {
                        autoSkip: false
                    }
                }]
            }
        }
    });
    for (i in lineChart.data.datasets[0].data)
    {
        if(lineChart.data.datasets[0].data[i] > 0)
            lineChart.data.datasets[0].pointBackgroundColor[i] =   'rgba(102, 255, 51, 0.6)' ;
        else if(lineChart.data.datasets[0].data[i] == 0)
            lineChart.data.datasets[0].pointBackgroundColor[i] =   "rgba(255, 204, 0, 1)" ;
        else
            lineChart.data.datasets[0].pointBackgroundColor[i] =   "red" ;
    }
    lineChart.update();
}
function getData(constCHART, chartType, numOfWeeks, minVal, studentID, classroomID, firstName, lastName, isResponsive, isStacked, startDate, endDate)
{
    $.ajax({
        url:"./queryWeeks.php",
        data: { 
        'studentID': studentID, 
        'classroomID': classroomID,
        'weeks': numOfWeeks,
        'startDate': startDate,
        'endDate': endDate
        },
        method:"POST",
        success: function(data){
            let allData = jQuery.parseJSON(data);
            console.log(allData);
            let dataArr1 = [];
            let dataArr2 = [];
            let dataArrCombined = [];
            let labelsArr = [];
            let bgColors = [];
            let bgColors2 = [];
            let hColors = [];
            let hColors2 = [];
            let colors = [];
            let colors2 = [];
            let minVal = 0;
            let maxVal = 0;
            let minValCombined = 0;
            let maxValCombined = 0;
            let tempVal = 0;
            for(let i in allData)
            {
                labelsArr.push(allData[i]['day']+ ", " + allData[i]['date']);
                dataArr1.push(parseInt(allData[i]['posBehaviors']));
                if(isStacked)
                    dataArr2.push(0-parseInt(allData[i]['negBehaviors']));
                else
                    dataArr2.push(parseInt(allData[i]['negBehaviors']));
                colors.push("rgba(102, 255, 51, 0.9)");
                colors2.push("rgba(255, 0, 0, 0.9)");
                hColors.push("rgba(102, 255, 51, 0.6)");
                hColors2.push("rgba(255, 0, 0, 0.6)");
                bgColors.push("rgba(102, 255, 51, 0.3)");
                bgColors2.push("rgba(255, 0, 0, 0.3)");
                tempVal = dataArr1[i]+3*dataArr2[i];
                dataArrCombined.push(tempVal);
                minVal = Math.min(dataArr2[i], minVal);
                maxVal = Math.max(dataArr1[i], maxVal);
                minValCombined = Math.min(tempVal, minValCombined);
                maxValCombined = Math.max(tempVal, maxValCombined);
            }
            if(constCHART==document.getElementById("chart1"))
            {
                createLineChart(constCHART, chartType, "Overall Student Score", Math.min(-2, minValCombined)-1, Math.max(2, maxValCombined)+1, 'Behavior Score', dataArrCombined, labelsArr, bgColors,
                hColors, colors, 1, 2, "points", isResponsive);
            }
            else if(constCHART==document.getElementById("chart3"))
            {
                createChart(constCHART, chartType, "Behavior Trend by Day (Stacked)", Math.min(-3, minVal), 'Positive Behaviors', 'Negative Behaviors', dataArr1, dataArr2, labelsArr, bgColors,
                hColors, colors, bgColors2, hColors2, colors2, 1, 2, "# of incidents", isResponsive, isStacked);
            }
            else if(constCHART==document.getElementById("chart4"))
            {
                createChart(constCHART, chartType, "Behavior Trend", minVal, 'Positive Behaviors', 'Negative Behaviors', dataArr1, dataArr2, labelsArr, bgColors,
                hColors, colors, bgColors2, hColors2, colors2, 1, 2, "# of incidents", isResponsive, isStacked);
            }
            else if(constCHART==document.getElementById("chart7"))
            {
                createChart(constCHART, chartType, "Behavior Count by Day Comparison", minVal, 'Positive Behaviors', 'Negative Behaviors', dataArr1, dataArr2, labelsArr, bgColors,
                hColors, colors, bgColors2, hColors2, colors2, 1, 2, "# of incidents", isResponsive, isStacked);
            }

        }
    });
}

function createPieChart(constCHART, numOfWeeks, studentID, classroomID, startDate, endDate)
{
    console.log(studentID +" ~ "+ classroomID +" ~ "+ numOfWeeks +" ~ "+ startDate +" ~ "+ endDate);
    $.ajax({
        url:"./queryWeeks.php",
        data: { 
        'studentID': studentID, 
        'classroomID': classroomID,
        'weeks': numOfWeeks,
        'startDate': startDate,
        'endDate': endDate
        },
        method:"POST",
        success: function(data){
            let allData = jQuery.parseJSON(data);
            console.log(allData);
            let totalDays = 0;
            let totalPos = 0;
            let totalNeg = 0;
            let totalBoth = 0;
            let totalNone = 0;
            for(let i in allData)
            {
                totalDays=i;
                if(allData[i]['posBehaviors']>0)
                {
                    if(allData[i]['negBehaviors'] > 0)
                        totalBoth++;
                    else
                        totalPos++;
                }
                else
                {
                    if(allData[i]['negBehaviors'] > 0)
                        totalNeg++;
                    else
                        totalNone++;
                }
            }
            let lineChart = new Chart(constCHART, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [totalPos, totalNeg, totalBoth, totalNone],
                            backgroundColor: [
                                'rgba(57, 230, 0, 0.8)',
                                'rgba(255, 0, 0, 0.9)',
                                'rgba(255, 153, 0, 0.8)',
                                'rgb(217, 217, 217)'
                            ]
                        }],
                        // These labels appear in the legend and in the tooltips when hovering different arcs
                        labels: [
                            'Good',
                            'Bad',
                            'Both',
                            'None'
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: "Percentage Days with Types of Behavior",
                            fontColor: "black",
                            fontSize: 18
                        },
                        pieceLabel: {
                            render: 'percentage',
                            fontColor: 'white',
                            precision: 2
                        }
                    }
                });
        }
    });
    
}

function createPieChartNoClass(constCHART,studentID, startDate, endDate)
{
    $.ajax({
        url:"./queryWeeks.php",
        data: { 
        'studentID': studentID, 
        'startDate': startDate,
        'endDate': endDate
        },
        method:"POST",
        success: function(data){
            let allData = jQuery.parseJSON(data);
            console.log(allData);
            let totalDays = 0;
            let totalPos = 0;
            let totalNeg = 0;
            let totalBoth = 0;
            let totalNone = 0;
            for(let i in allData)
            {
                totalDays=i;
                if(allData[i]['posBehaviors']>0)
                {
                    if(allData[i]['negBehaviors'] > 0)
                        totalBoth++;
                    else
                        totalPos++;
                }
                else
                {
                    if(allData[i]['negBehaviors'] > 0)
                        totalNeg++;
                    else
                        totalNone++;
                }
            }
            let lineChart = new Chart(constCHART, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [totalPos, totalNeg, totalBoth, totalNone],
                            backgroundColor: [
                                'rgba(57, 230, 0, 0.8)',
                                'rgba(255, 0, 0, 0.9)',
                                'rgba(255, 153, 0, 0.8)',
                                'rgb(217, 217, 217)'
                            ]
                        }],
                        // These labels appear in the legend and in the tooltips when hovering different arcs
                        labels: [
                            'Good',
                            'Bad',
                            'Both',
                            'None'
                        ]
                    },
                    options: {
                        title: {
                            display: true,
                            text: "Percentage Days with Types of Behavior",
                            fontColor: "black",
                            fontSize: 18
                        },
                        pieceLabel: {
                            render: 'percentage',
                            fontColor: 'white',
                            precision: 2
                        }
                    }
                });
        }
    });
    
}

function commonBehaviorsChart(constCHART, behaviorType, numOfWeeks, studentID, classroomID, startDate, endDate)
{
    let isPositive = 0;
    let backgroundColor = [];
    let borderColor = [];
    let titleText = "";
    if(behaviorType == 'both')
    {
        titleText="Most Common Behaviors";
        isPositive = 2;
        backgroundColor = [
            'rgba(255, 159, 64, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 99, 132, 0.2)'
        ];
        borderColor = [
            'rgba(255, 159, 64, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255,99,132,1)'
        ];
    }
    else if(behaviorType == 'negative')
    {
        titleText="Most Common Negative Behaviors";
        isPositive = 0;
        backgroundColor = [
            'rgba(255, 0, 0, 0.6)',
            'rgba(255, 0, 0, 0.6)',
            'rgba(255, 0, 0, 0.6)',
            'rgba(255, 0, 0, 0.6)',
            'rgba(255, 0, 0, 0.6)'
        ];
        borderColor = [
            'rgba(255, 0, 0, 1)',
            'rgba(255, 0, 0, 1)',
            'rgba(255, 0, 0, 1)',
            'rgba(255, 0, 0, 1)',
            'rgba(255, 0, 0, 1)'
        ];
    }
    else
    {
        titleText="Most Common Positive Behaviors";
        isPositive = 1;
        backgroundColor = [
            "rgba(102, 255, 51, 0.6)",
            "rgba(102, 255, 51, 0.6)",
            "rgba(102, 255, 51, 0.6)",
            "rgba(102, 255, 51, 0.6)",
            "rgba(102, 255, 51, 0.6)"
        ];
        borderColor = [
            "rgba(102, 255, 51, 1)",
            "rgba(102, 255, 51, 1)",
            "rgba(102, 255, 51, 1)",
            "rgba(102, 255, 51, 1)",
            "rgba(102, 255, 51, 1)"
        ];
    }
    $.ajax({
        url:"./queryWeeks.php",
        data: { 
        'studentID': studentID, 
        'classroomID': classroomID,
        'weeks': numOfWeeks+2,
        'isPositive': isPositive,
        'topBehaviors': '1',
        'startDate': startDate,
        'endDate': endDate
        },
        method:"POST",
        success: function(data){
            let allData = jQuery.parseJSON(data);
            console.log(allData);
            let dataArr1 = [];
            let labelsArr = [];
            let minVal = 0;
            let maxVal = 0;
            let lastIndex = 0;
            for(let i in allData)
            {
                labelsArr.push(allData[i]['title']);
                dataArr1.push(parseInt(allData[i]['total']));
                minVal = Math.min(dataArr1[i], minVal);
                maxVal = Math.max(dataArr1[i], maxVal);
                lastIndex = i;
            }
            for(i = lastIndex+1; i < 5; i++)
            {
                labelsArr.push('');
                dataArr1.push('');
            }
            let lineChart = new Chart(constCHART, {
                    type: 'horizontalBar',
                    data: {
                        datasets: [{
                            label: 'Total behaviors',
                            data: dataArr1,
                            backgroundColor: backgroundColor,
                            borderColor: borderColor
                        }],
                        // These labels appear in the legend and in the tooltips when hovering different arcs
                        labels: labelsArr
                    },
                    options: {
                        title: {
                            display: true,
                            text: titleText,
                            fontColor: "black",
                            fontSize: 18
                        },
                        scales: {
                            xAxes: [{
                                ticks: {
                                    autoSkip: false,
                                    scaleBeginAtZero: false,
                                    min: minVal,
                                    max: Math.max(3, maxVal)
                                },
                                scaleLabel: {
                                    display: false
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    autoSkip: false
                                }
                            }]
                        }
                    }
                });
        }
    });
    
}