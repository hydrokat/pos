$(document).ready(function(){
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
        var days = [];
        var lineData = [];
        
        for(i = 0; i < 30; i++) {
            days[i] = i + 1;
        }

        for(i = 0; i < 30; i++) {
            lineData[i] = randomScalingFactor();
        }

        var lineChartData = {
            labels : days,
            datasets : [
                {
                    label: "My Second dataset",
                    fillColor : "rgba(151,187,205,0.2)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(151,187,205,1)",
                    data : lineData
                }
            ]

        }

    var pieData = [
            {
                value: 300,
                color:"#F7464A",
                highlight: "#FF5A5E",
                label: "Rice"
            },
            {
                value: 50,
                color: "#46BFBD",
                highlight: "#5AD3D1",
                label: "Feeds"
            },
            {
                value: 100,
                color: "#FDB45C",
                highlight: "#FFC870",
                label: "Grains"
            },
            {
                value: 40,
                color: "#949FB1",
                highlight: "#A8B3C5",
                label: "Veterinaries"
            },

        ];

    if($('#canvas').length != 0){
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });
    }

    if($('#canvas2').length != 0){
        var ctx2 = document.getElementById("canvas2").getContext("2d");
        window.myPie = new Chart(ctx2).Pie(pieData, {
            responsive: true,
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"><%if(segments[i].label){%><%=segments[i].label%><%}%></span></li><%}%></ul>"
        });

        $('#legend').html(window.myPie.generateLegend());
    }
});