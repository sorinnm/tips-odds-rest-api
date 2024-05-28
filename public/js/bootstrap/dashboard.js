/* globals Chart:false */

(() => {
  'use strict'

    // Graphs
    const fixtures = document.getElementById('fixtures')
    // eslint-disable-next-line no-unused-vars
    const fixturesChart = new Chart(fixtures, {
    type: 'line',
    data: {
      labels: window.fixturesChartKeys,
      datasets: [{
        data: window.fixturesChartValues,
        lineTension: 0,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        borderWidth: 4,
        pointBackgroundColor: '#007bff'
      }]
    },
    options: {
        plugins: {
        legend: {
          display: false
        },
        tooltip: {
          boxPadding: 3
        },
        decimation: {
            enabled: false,
            algorithm: 'min-max',
        }
      },
        scales: {
            y: {
                ticks: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    }
    });

    const generations = document.getElementById('generations')
    // eslint-disable-next-line no-unused-vars
    const generationsChart = new Chart(generations, {
        type: 'line',
        data: {
            labels: window.chatGptChartKeys,
            datasets: [{
                data: window.chatGptChartValues,
                lineTension: 0,
                backgroundColor: 'transparent',
                borderColor: '#007bff',
                borderWidth: 4,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    boxPadding: 3
                }
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        }
    });

    const pages = document.getElementById('pages')
    // eslint-disable-next-line no-unused-vars
    const pagesChart = new Chart(pages, {
        type: 'line',
        data: {
            labels: window.wordpressChartKeys,
            datasets: [{
                data: window.wordpressChartValues,
                lineTension: 0,
                backgroundColor: 'transparent',
                borderColor: '#007bff',
                borderWidth: 4,
                pointBackgroundColor: '#007bff'
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    boxPadding: 3
                }
            },
            scales: {
                y: {
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        }
    });
})()
