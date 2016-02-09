$(document).ready(function(){
    $(".alert").on('click', function(){
        $(this).hide();
    });

    var menu_open = 0;
    $("#menu-toggle").on('click', function(){
        if(!menu_open){
            $(".sidebar").show();
            $(this).text('Hide Sidebar');
            $(".navbar-toggle").click();
            menu_open = 1;
        } else {
            $(".sidebar").hide();
            $(this).text('Show Sidebar');
            $(".navbar-toggle").click();
            menu_open = 0;
        }
    });

    window.onload = function() {
        $.get('reports/dailysalesgraphdata', function(data){

        data[0] = typeof data[0] === 'undefined' ? 0 : data[0];
        data[1] = typeof data[1] === 'undefined' ? 0 : data[1];
        data[2] = typeof data[2] === 'undefined' ? 0 : data[2];
        data[3] = typeof data[3] === 'undefined' ? 0 : data[3];
        data[4] = typeof data[4] === 'undefined' ? 0 : data[4];

        console.log(data);

        var d = new Date();
        var weekday = new Array(7);
        weekday[0] = "Sunday";
        weekday[1] = "Monday";
        weekday[2] = "Tuesday";
        weekday[3] = "Wednesday";
        weekday[4] = "Thursday";
        weekday[5] = "Friday";
        weekday[6] = "Saturday";

        var now = weekday[d.getDay()];
        var ys1 = d.getDay() - 1 <= -1 ? d.getDay() + 6 : d.getDay() - 1;
        var ys2 = d.getDay() - 2 <= -1 ? d.getDay() + 5 : d.getDay() - 2;
        var ys3 = d.getDay() - 3 <= -1 ? d.getDay() + 4 : d.getDay() - 3;
        var ys4 = d.getDay() - 4 <= -1 ? d.getDay() + 3 : d.getDay() - 4;

        //var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
        var lineChartData = {
            labels : [weekday[ys4],weekday[ys3],weekday[ys2],weekday[ys1], now],
            datasets : [
                {
                    label: "Daily Retail Sales",
                    fillColor : "rgba(151,187,205,0.2)",
                    strokeColor : "rgba(151,187,205,1)",
                    pointColor : "rgba(151,187,205,1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(151,187,205,1)",
                    data : [data[4], data[3], data[2], data[1], data[0]]
                }
            ]
        }
        
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx).Line(lineChartData, {
                responsive: true
            });
        }, "json");
    }
});