<script setup lang="ts">

import axios from 'axios'
import Chart from 'chart.js/auto'
import { onMounted, ref } from 'vue'

const chartRef = ref<HTMLCanvasElement | null>(null)

onMounted(async () => {
    const { data } = await axios.get('http://localhost:8000/api/chart-js')

    // const labels = data.map((item: any) => `Month ${item.month}`)
    const monthNames = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']
    const labels = data.map((item: any) => monthNames[item.month - 1])
    const values = data.map((item: any) => item.total)

    if (chartRef.value) {
        new Chart(chartRef.value, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Transaksi',
                    data: values,
                    backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                                'rgba(255, 159, 64, 0.2)',
                                'rgba(255, 205, 86, 0.2)',
                                'rgba(75, 192, 192, 0.2)',
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(153, 102, 255, 0.2)',
                                'rgba(201, 203, 207, 0.2)'
                            ],
                            borderColor: [
                                'rgb(255, 99, 132)',
                                'rgb(255, 159, 64)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(54, 162, 235)',
                                'rgb(153, 102, 255)',
                                'rgb(201, 203, 207)'
                            ],
                            borderWidth: 1,
                            maxBarThickness: 80,
                }]
            },
            options: {
                // maintainAspectRatio: false,
                 // maxBarThickness: 40,
                scales: {
                    // x: {
                    //     categoryPercentage: 0.6,
                    //     barPercentage: 0.7,
                    // },
                    y: { beginAtZero: true }
                }
            }
        })
    }
})


// import Chart from 'chart.js/auto';
// import {onMounted, ref} from "vue";
// import axios from "axios";
//
// const chartRef = ref<HTMLCanvasElement | null>(null);
//
// onMounted(async()  => {
//     const { data } = await axios.get('http://localhost:8000/api/chart-js')
//
//     const labels = data.map((item: any) => `Month ${item.month}`)
//     const values = data.map((item: any) => item.total)
//
//     // const Utils = {
//     //     months({ count = 12 } = {}) {
//     //         const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July',
//     //             'August', 'September', 'October', 'November', 'December'];
//     //         return months.slice(0, count);
//     //     }
//     // };
//     if (chartRef.value) {
//         new Chart(chartRef.value, {
//             type: 'bar',
//             data: {
//                 labels: labels,
//                 datasets: [{
//                     label: 'Data Penjualan',
//                     data: values,
//                     backgroundColor: [
//                         'rgba(255, 99, 132, 0.2)',
//                         'rgba(255, 159, 64, 0.2)',
//                         'rgba(255, 205, 86, 0.2)',
//                         'rgba(75, 192, 192, 0.2)',
//                         'rgba(54, 162, 235, 0.2)',
//                         'rgba(153, 102, 255, 0.2)',
//                         'rgba(201, 203, 207, 0.2)'
//                     ],
//                     borderColor: [
//                         'rgb(255, 99, 132)',
//                         'rgb(255, 159, 64)',
//                         'rgb(255, 205, 86)',
//                         'rgb(75, 192, 192)',
//                         'rgb(54, 162, 235)',
//                         'rgb(153, 102, 255)',
//                         'rgb(201, 203, 207)'
//                     ],
//                     borderWidth: 1
//                 }]
//             },
//             options: {
//                 scales: {
//                     y: {
//                         beginAtZero: true
//                     }
//                 }
//             }
//         })
//     }

    // const labels = Utils.months({count: 7});
    // const data = {
    //     labels: labels,
    //     datasets: [{
    //         label: 'Data Penjualan',
    //         data: [65, 59, 80, 81, 56, 55, 40],
    //         backgroundColor: [
    //             'rgba(255, 99, 132, 0.2)',
    //             'rgba(255, 159, 64, 0.2)',
    //             'rgba(255, 205, 86, 0.2)',
    //             'rgba(75, 192, 192, 0.2)',
    //             'rgba(54, 162, 235, 0.2)',
    //             'rgba(153, 102, 255, 0.2)',
    //             'rgba(201, 203, 207, 0.2)'
    //         ],
    //         borderColor: [
    //             'rgb(255, 99, 132)',
    //             'rgb(255, 159, 64)',
    //             'rgb(255, 205, 86)',
    //             'rgb(75, 192, 192)',
    //             'rgb(54, 162, 235)',
    //             'rgb(153, 102, 255)',
    //             'rgb(201, 203, 207)'
    //         ],
    //         borderWidth: 1
    //     }]
    // };

    // const ctx = document.getElementById('myChart') as HTMLCanvasElement

// })
</script>

<template>
  <main>
      <h1>Laporan Penjualan</h1>

      <div>
          <canvas ref='chartRef'></canvas>
      </div>

  </main>
</template>
<!--    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
