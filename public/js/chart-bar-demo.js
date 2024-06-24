// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';




var id = document.getElementById('id_user');

// Fazendo uma requisição AJAX usando jQuery
$.ajax({
    url: 'api/v1/getValueGraphic6Mounth?id=' + id.value,
    method: 'GET',
    success: function (response) {
        console.log(response);
        // Bar Chart Example
        var ctx = document.getElementById("myBarChart");
        var myLineChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: response[0],
                datasets: [{
                    label: "Receita R$:",
                    backgroundColor: "rgba(2,117,216,1)",
                    borderColor: "rgba(2,117,216,1)",
                    data: response[1],
                }],
            },
            options: {
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: true
                        },
                        ticks: {
                            maxTicksLimit: 6
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: parseFloat(response[2]),
                            maxTicksLimit: 6
                        },
                        gridLines: {
                            display: true
                        }
                    }],
                },
                legend: {
                    display: true
                }
            }
        });

    },
    error: function (xhr, status, error) {
        // Função a ser executada em caso de erro
        console.error(status, error); // Exemplo: '404 Not Found'
    }
});
