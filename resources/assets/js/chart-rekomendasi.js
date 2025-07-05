import ApexCharts from 'apexcharts';
  
  document.addEventListener("DOMContentLoaded", function () {
    const chartData = JSON.parse(document.getElementById('chartRekomendasi').dataset.chartData);
  const chartLabels = JSON.parse(document.getElementById('chartRekomendasi').dataset.chartLabels);

    const options = {
      chart: {
        type: 'line',
        height: 350
      },
      series: [{
        name: 'Rekomendasi',
        data: chartData
      }],
      xaxis: {
        categories: chartLabels
      },
      yaxis: {
        labels: {
          formatter: function (value) {
            return Math.round(value); 
          }
        },
      },
      stroke: {
        curve: 'smooth'
      },
      colors: ['#696CFF']
    };

    const chart = new ApexCharts(document.querySelector("#chartRekomendasi"), options);
    chart.render();
  });