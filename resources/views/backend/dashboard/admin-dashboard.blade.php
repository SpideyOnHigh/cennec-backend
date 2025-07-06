@extends('layouts.master')
@section('title')
    {{ __('Dashboard') }}
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/libs/select2.min.css') }}">
    <link href="{{ URL::asset('build/libs/alertifyjs/build/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        canvas {
            height: 100% !important;
            width: 100% !important;
        }
    </style>
@endsection
@section('content')
    <div class="main-content group-data-[sidebar-size=sm]:ml-[70px]">
        <div class="page-content dark:bg-zinc-700">
            <div class="container-fluid px-[0.625rem]">
                <x-page-title title="Dashboard" pagetitle="Home" />

                @include('backend.dashboard.dashboard-reports')
                @can('dashboard-reports')
                    <p id="chart-title" class="ml-1 font-semibold text-gray-800 mt-4">
                        New Users in past 40 Days: <span id="date-range"></span>
                    </p>
                    <div class="chart-container">
                        <canvas id="userChart"></canvas>
                    </div>
                @endcan

                @include('layouts.footer')
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Include jQuery -->
    <script type="text/javascript" src="{{ asset('js/libs/jquery-3.7.1.min.js') }}"></script>
    <!-- Include Chart.js -->
    <script type="text/javascript" src="{{ asset('js/libs/chart.js') }}"></script>
    <script>
        $(document).ready(function() {
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 40);
            const endDate = new Date();

            const options = {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            };
            const startDateString = startDate.toLocaleDateString('en-US', options);
            const endDateString = endDate.toLocaleDateString('en-US', options);

            $('#date-range').text(`${startDateString} - ${endDateString}`);

            $.getJSON('/user-registrations', function(data) {
                const ctx = $('#userChart').get(0).getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Users Created',
                            data: data.values,
                            borderColor: 'rgba(75, 192, 192, 1)', // Line color
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light background color for the area under the line
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Users Created: ${context.raw}`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: {
                                    display: false // Disable grid lines for a cleaner look
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.1)' // Light grid lines
                                }
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
