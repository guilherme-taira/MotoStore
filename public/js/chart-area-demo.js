// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';


var id = document.getElementById('id_user');

// Fazendo uma requisição AJAX usando jQuery
$.ajax({
    url: 'api/v1/getValueGraphic15days?id='+ id.value,
    method: 'GET',
    success: function(response) {
        // Area Chart Example
        console.table(response);
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels:response[0],
            datasets: [{
            label: "R$: ",
            lineTension: 0.5,
            backgroundColor: "rgba(2,117,216,0.2)",
            borderColor: "rgba(2,117,216,1)",
            pointRadius: 5,
            pointBackgroundColor: "rgba(2,117,216,1)",
            pointBorderColor: "rgba(255,255,255,0.8)",
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(2,117,216,1)",
            pointHitRadius: 50,
            pointBorderWidth: 2,
            data: response[1],
            }],
        },
        options: {
            scales: {
            xAxes: [{
                time: {
                unit: 'date'
                },
                gridLines: {
                display: false
                },
                ticks: {
                maxTicksLimit: 7
                }
            }],
            yAxes: [{
                ticks: {
                min: 0,
                max: response[3],
                maxTicksLimit: 5
                },
                gridLines: {
                color: "rgba(0, 0, 0, .125)",
                }
            }],
            },
            legend: {
            display: false
            }
        }
        });
    },
    error: function(xhr, status, error) {
        // Função a ser executada em caso de erro
        console.error(status, error); // Exemplo: '404 Not Found'
    }
});

