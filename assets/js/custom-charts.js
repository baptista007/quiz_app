/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//init parameters for highcharts
var options = {
    chart: {
        events: {
            drilldown: function (e) {
                if (!e.seriesOptions) {

                    var chart = this;

                    // Show the loading label
                    chart.showLoading('Loading ...');

                    setTimeout(function () {
                        chart.hideLoading();
                        chart.addSeriesAsDrilldown(e.point, series);
                    }, 1000);
                }

            }
        },
        plotBorderWidth: 0
    },
    title: {
        text: ''
    },
    //
    subtitle: {
        text: ''
    },
    //
    xAxis: {
        type: 'category'
    },
    exporting: {
        buttons: {
            contextButton: {
               text:'Download'
            }
        }
    },
    //
    yAxis: {
        min: 0,
        max: 1000,
        tickInterval: [],
        title: {
            margin: 10,
            text: '%'
        },
        categories: []
    },
    //
    legend: {
        enabled: true,
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -5,
        y: 5,
        borderWidth: 0
    },
    credits: {
        enabled: false
    },
    //
    plotOptions: {
        series: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: false,
                connectorAllowed: false,
                format: '{point.y:.1f}',
                formatter:function(){
                        if(this.y > 0)
                            return this.y;
                    }
            },
            pointStart: 0
        },
        pie: {
            plotBorderWidth: 5,
            allowPointSelect: true,
            cursor: 'pointer',
            size: '100%',
            dataLabels: {
                enabled: true,
                format: '<b>{point.name}</b>: {point.percentage:.1f} %'
            }
        }
    },
    //
    series: [{}],
    //
    drilldown: {
        series: []
    },    
    navigation: {
        buttonOptions: {
            align: 'left'
        }
    }
};

// function to dynamically change chart types e.g bar, column, pie and etc
chartfunc = function () {

    var column = document.getElementById('column');
    var bar = document.getElementById('bar');
    var pie = document.getElementById('pie');
    var line = document.getElementById('line');
    var area = document.getElementById('area');
    var spline = document.getElementById('spline');
    //var line = document.getElementById('line');


    if (column.checked) {
        options.chart.renderTo = 'comaprisonDashboardChart';
        options.chart.type = 'column';
        var column = new Highcharts.Chart(options);

    } 
    else if (bar.checked) {
        options.chart.renderTo = 'comaprisonDashboardChart';
        options.chart.type = 'bar';
        var bar = new Highcharts.Chart(options);
    } 
//    else if (pie.checked) {
//        createInitDatatable();
//        var table = document.getElementById('dashboardChartTable');
//        var columns = [];
//        var pieOptions = {
//            series: []
//        };
//        
//        Highcharts.each(table.getElementsByTagName('tr'), function (tr, rowNo) {
//            Highcharts.each(tr.children, function (item, colNo) {
//                if (item.tagName === 'TD' || item.tagName === 'TH') {
//                    if (!columns[colNo]) {
//                        columns[colNo] = [];
//                    }
//
//                    columns[colNo][rowNo] = item.innerHTML;                    
//                }
//            });
//        });
//        console.log('columns',columns);
//        var i, j,center = 0,
//        len = columns[1].length - 1,
//        colLen = columns.length - 1;
//       
//        for(j = 0; j < colLen; j++){
//            var seriesOptions = {
//               type: 'pie',
//               name: '',
//               data: [],
//               center: [100, 80],
//               size: 200,
//               showInLegend: true,
//               dataLabels: {
//                   enabled: false
//               },
//               title: {
//                    // align: 'left',
//                    // x: 0
//                    // style: { color: XXX, fontStyle: etc }
//                    align: 'left',
//                    format: '<b>{name}</b><br>Title left',
//                    verticalAlign: 'top',
//                    y: -40
//                }
//           };
//           
//           console.log(j+'col', columns[j+1]);
//           var col = columns[j+1];
//           
//           seriesOptions.name = col[0];
//           console.log('1st term',col[0]);
//           
//           for (i = 0; i < len; i++) {
//                seriesOptions.data.push(parseFloat(columns[j+1][i + 1]));
//            }
//            
//            console.log('seriesOptions',seriesOptions);
//    
//            if(pieOptions.series.length <= 0){
//                seriesOptions.center[0] = 100;
//            }
//            else{
//                seriesOptions.center[0] = center + 220;
//            }
//
//            pieOptions.series.push(seriesOptions);
//            center = seriesOptions.center[0];           
//        }
//        
//        console.log('pieOptions',pieOptions);
//        $('#comaprisonDashboardChart').highcharts(pieOptions);
//        
////        options.chart.renderTo = 'comaprisonDashboardChart';
////        options.chart.type = 'pie';
////        var pie = new Highcharts.Chart(options);
//    } 
    else if (area.checked) {
        options.chart.renderTo = 'comaprisonDashboardChart';
        options.chart.type = 'area';
        var area = new Highcharts.Chart(options);

    } 
    else if (spline.checked) {
        options.chart.renderTo = 'comaprisonDashboardChart';
        options.chart.type = 'spline';
        var spline = new Highcharts.Chart(options);
    }
    else if (line.checked) {
        options.chart.renderTo = 'comaprisonDashboardChart';
        options.chart.type = 'line';
        var line = new Highcharts.Chart(options);
    }

}

//removes a series from the charts
// chartElementId: DOM element id for the charts
// serieName: name of the series to be removed
function removeHighchartSeries(chartElementId, serieName) {
    var chart = $('#' + chartElementId).highcharts();
    var seriesLength = chart.series.length;

    for (var i = seriesLength - 1; i > -1; i--) {
        if (chart.series[i].name === serieName) {
            chart.series[i].remove();
        }
    }
}




