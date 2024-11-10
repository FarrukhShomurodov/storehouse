@extends('admin.layouts.app')

@section('title')
    <title>Статистика склада - Pc Maker</title>
@endsection

@section('content')
    <div class="container">
        <h4 class="mb-4">Статистика склада</h4>

        <div class="row">
            <div class="col-md-4">
                <h5>Продажи</h5>
                <ul class="list-group">
                    <li class="list-group-item">Дневные продажи: {{ number_format($salesStats['daily_sales'], 2) }} uzs</li>
                    <li class="list-group-item">Недельные продажи: {{ number_format($salesStats['weekly_sales'], 2) }} uzs</li>
                    <li class="list-group-item">Месячные продажи: {{ number_format($salesStats['monthly_sales'], 2) }} uzs</li>
                    <li class="list-group-item">Общая сумма продаж: {{ number_format($salesStats['total_sales'], 2) }} uzs</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5>Отмены</h5>
                <ul class="list-group">
                    <li class="list-group-item">Дневные отмены: {{ $cancellationStats['daily_cancellations'] }}</li>
                    <li class="list-group-item">Недельные отмены: {{ $cancellationStats['weekly_cancellations'] }}</li>
                    <li class="list-group-item">Месячные отмены: {{ $cancellationStats['monthly_cancellations'] }}</li>
                    <li class="list-group-item">Общее количество отмен: {{ $cancellationStats['total_cancellations'] }}</li>
                </ul>
            </div>

            <div class="col-md-4">
                <h5>Запасы</h5>
                <ul class="list-group">
                    @foreach($stockStats['stock_stats'] as $product)
                        <li class="list-group-item">
                            <strong>{{ $product['product_name'] }}</strong>: Остаток: {{ $product['available_units'] }} шт., Продано: {{ $product['sold_units'] }} шт., Отменено: {{ $product['cancelled_units'] }} шт.
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Графики -->
        <div class="row mt-5">
            <div class="col-md-6">
                <h5>Продажи за последнюю неделю</h5>
                <div id="sales-chart"></div>
            </div>

            <div class="col-md-6">
                <h5>Отмены за последнюю неделю</h5>
                <div id="cancellations-chart"></div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h5>Запасы по продуктам</h5>
                <div id="stock-chart"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        var salesChartOptions = {
            series: [{
                name: 'Продажи',
                data: @json($salesStats['sales_over_time'])
            }],
            chart: {
                type: 'line',
                height: 350
            },
            xaxis: {
                categories: @json($salesStats['time_labels']),
                title: {
                    text: 'Дата'
                }
            },
            yaxis: {
                title: {
                    text: 'Сумма (UZS)'
                }
            },
            title: {
                text: 'Продажи за последние 7 дней',
                align: 'center'
            }
        };

        var salesChart = new ApexCharts(document.querySelector("#sales-chart"), salesChartOptions);
        salesChart.render();

        var cancellationsChartOptions = {
            series: [{
                name: 'Отмены',
                data: @json($cancellationStats['cancellations_over_time'])
            }],
            chart: {
                type: 'line',
                height: 350
            },
            xaxis: {
                categories: @json($cancellationStats['time_labels']),
                title: {
                    text: 'Дата'
                }
            },
            yaxis: {
                title: {
                    text: 'Количество'
                }
            },
            title: {
                text: 'Отмены за последние 7 дней',
                align: 'center'
            }
        };

        var cancellationsChart = new ApexCharts(document.querySelector("#cancellations-chart"), cancellationsChartOptions);
        cancellationsChart.render();

        var stockChartOptions = {
            series: [{
                name: 'Продано',
                data: @json($stockStats['sold_units'])
            }, {
                name: 'Остатки',
                data: @json($stockStats['available_units'])
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            xaxis: {
                categories: @json($stockStats['product_names']),
                title: {
                    text: 'Продукты'
                }
            },
            yaxis: {
                title: {
                    text: 'Количество'
                }
            },
            title: {
                text: 'Запасы и продажи по продуктам',
                align: 'center'
            }
        };

        var stockChart = new ApexCharts(document.querySelector("#stock-chart"), stockChartOptions);
        stockChart.render();
    </script>
@endsection
