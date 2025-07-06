"use strict";

var feedback = function () {
    var initIndex = function () {
        var table = $('#feedback-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: getlist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'username', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'created_at', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'type', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'comment', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).addClass('p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                $('td:last', row).addClass('p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600');
            }
        });

        $(".dataTables_length select").addClass('form-select form-select-sm');
    };

    var feedbackRating = function () {
        var table = $('#feedback-rating-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [
                [0, 'desc']
            ],
            ajax: {
                url: getlist,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            },
            columns: [
                { data: 'username', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'created_at', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
                { data: 'rating', class: "p-4 pr-8 border border-t-0 border-l-0 border-gray-50 dark:border-zinc-600" },
            ],
            "createdRow": function (row, data, dataIndex) {
                $('td:eq(0)', row).addClass('p-4 pr-8 border border-t-0 rtl:border-l-0 border-gray-50 dark:border-zinc-600');
                $('td:last', row).addClass('p-4 pr-8 border border-t-0 border-l-0 rtl:border-l border-gray-50 dark:border-zinc-600');
            }
        });

        $(".dataTables_length select").addClass('form-select form-select-sm');
    };

    var ratingGraph = function () {
        $.getJSON('/feedback/graph', function (data) {
            // Prepare data for Chart.js
            const labels = data.labels.map(rating => `Rating ${rating}`);
            const values = data.values;

            const ctx = $('#userChart').get(0).getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Users',
                        data: values,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light background color for bars
                        borderColor: 'rgba(75, 192, 192, 1)', // Bar border color
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
                                label: function (context) {
                                    return `Number of Users: ${context.raw}`;
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
    };


    return {
        init: function () {
            initIndex();
        },
        rating: function () {
            ratingGraph();
            feedbackRating();
        }
    };
}();