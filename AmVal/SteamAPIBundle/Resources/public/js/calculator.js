
$(document).ready(function () {

    $("#calculator-activite-form").submit(function(e){
        e.preventDefault();
        var newData = {raw: [], pure: [], pureDisplaysHelper: [], };
        for (var i = 1; i <= $("#number-input-total").val(); i++) {
            var input = $("#number-input-" + i);
            var label = input.siblings('label').html();
            var number = input.val();
            var display = $('#calculated-' + i);
            var result = parseInt(display.data('total'));
            // Result calculation
            if(number != '') {
                result += parseInt(number);
                display.data('total', result);
                display.html(formatResult(result));
                input.val('');
            }
            if (result != 0) {
                // First chart data
                newData.raw.push({
                    name: label,
                    color: display.data('color'),
                    y: result
                });
                // Second chart data (pure)
                newData.pure.push({
                    name: label,
                    color: display.data('color'),
                    y: result
                });
                // Second chart display helper (pure)
                newData.pureDisplaysHelper.push('#calculated-pure-' + i);
            }
        }
        for (i = 0; i < newData.pure.length; i++) {
            // Pure result calculations
            var displayPure = $(newData.pureDisplaysHelper[i]);
            var resultPure = parseInt(displayPure.data('total'));
            // TODO : calculate resultPure from newData.raw
            // if(resultPure == 0) { resultPure = newData.pure[i].y;} //Provisory (for init.)
            // for (i = 0; i < newData.raw.length; i++) {
            //     if (newData.raw[i].name.indexOf("Orale") != -1) {
            //         console.log(newData.pure[i].y);
            //     }
            //     if (newData.raw[i].name.indexOf("Vaginale") != -1) {
            //         console.log(newData.pure[i].y);
            //     }
            //     if (newData.raw[i].name.indexOf("Anale") != -1) {
            //         console.log(newData.pure[i].y);
            //     }
            // }
            newData.pure[i].y = resultPure;
            displayPure.data('total', resultPure);
            displayPure.html(formatResult(resultPure));
        }
        Charts.updateCharts(newData);
    });
    function formatResult(result) {
        var minutes = Math.floor(result / 60);
        var seconds = result - minutes * 60;
        if (seconds.toString().length < 2) {
            seconds = '0' + seconds;
        }
        return minutes + ':' + seconds;
    }

    /* Charts management */
    var Charts = {
        initCharts: function() {
            this.chart = new Highcharts.Chart({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    renderTo:'activite-chart'
                },
                title: {
                    text: 'Activité'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Activité',
                    colorByPoint: true,
                    data: []
                }]
            });
            this.pureChart = new Highcharts.Chart({
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie',
                    renderTo:'activite-pure-chart'
                },
                title: {
                    text: 'Activité pure'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                            }
                        }
                    }
                },
                series: [{
                    name: 'Activité pure',
                    colorByPoint: true,
                    data: []
                }]
            });
        },
        updateCharts: function(newData) {
            this.chart.series[0].setData(newData.raw);
            this.pureChart.series[0].setData(newData.pure);
            this.chart.redraw();
            this.pureChart.redraw();
        }
    };
    Charts.initCharts();
});