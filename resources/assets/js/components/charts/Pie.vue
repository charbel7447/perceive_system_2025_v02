<template>
    <div>
        <canvas ref="pie"></canvas>
    </div>
</template>
<script type="text/javascript">
    import Chart from 'chart.js'

    export default {
        props: ['datasets_account', 'labels_account'],
        data() {
            return {
                chart: {
                    datasets: this.datasets_account,
                    // labels: ['USD Account','LBP Account']
                    labels: this.labels_account
                },
            }
        },
        mounted() {
            const myChart = new Chart(this.$refs.pie, {
                type: 'pie',
                data: this.chart,
                options: {
                    animation: {
                        duration: 2, // general animation time
                    },
                    hover: {
                        animationDuration: 2, // duration of animations when hovering an item
                    },
                    responsiveAnimationDuration: 2, // animation duration after a resize
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            })
        }
    }
</script>
