@extends('components.app')
@section('content')

{{-- Title Header --}}
<div class="card bg-dark mb-5">
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md d-flex align-items-center">
                <i class='bx bxs-dashboard text-white' style="font-size: 24px;">&nbsp;</i>
                <h4 class="text-white mb-0">Sales Funnel Management</h4>
            </div>
        </div>
    </div>
</div>

{{-- Navlink Include --}}
@include('dashboard.dashboard_navlink')


{{-- Start Date - End Date Filter Group --}}
<div class="row mb-4">
    <div class="col-md d-flex justify-content-end gap-4">
        <div class="form-group text-end">
            <label for="defaultFormControlInput" class="form-label"><small>Select Start to End Date</small></label>
            <input type="text" id="date-range-picker" class="form-control form-control-sm" placeholder="Filter Date">
        </div>
        {{-- <div class="form-group text-end">
            <label for="defaultSelect" class="form-label"><small>Filter Group</small></label>
            <select id="selectGroup" class="form-control form-select-sm">
            </select>
        </div> --}}
        {{-- <div class="form-group text-end">
            <label for="defaultSelect" class="form-label"><small>Reset Filter</small></label><br>
            <button class="btn btn-sm btn-label-dark">Reset</button>
        </div> --}}
    </div>
</div>

<div class="row mb-4">
    <h5>Top MP/Agent Rankings</h5>
    <div class="col-md">
        <div class="row mb-2">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">
                                <div class="d-flex align-items-center gap-2">
                                    <i class='bx bx-trophy fs-2' style="color: #ff0055"></i>
                                    <label class="fs-4 fw-bold" style="color: #ff0055">Top 1 Agent</label><br>
                                </div>
                                <small>Agent with most released units</small>
                            </div>
                            <h3 class="fw-bold" id="top1Agent" style="color: #ff0055">Loading...</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">
                                <div class="d-flex align-items-center gap-2">
                                    <i class='bx bx-trophy fs-2' style="color: #ff0055"></i>
                                    <label class="fs-4 fw-bold" style="color: #ff0055">Top 2 Agent</label><br>
                                </div>
                                <small>Agent with most released units</small>
                            </div>
                            <h3 class="fw-bold" id="top2Agent" style="color: #ff0055">Loading...</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">
                                <div class="d-flex align-items-center gap-2">
                                    <i class='bx bx-trophy fs-2' style="color: #ff0055"></i>
                                    <label class="fs-4 fw-bold" style="color: #ff0055">Top 3 Agent</label><br>
                                </div>
                                <small>Agent with most released units</small>
                            </div>
                            <h3 class="fw-bold" id="top3Agent" style="color: #ff0055">Loading...</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row mb-2">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">
                                <div class="d-flex align-items-center gap-2">
                                    <i class='bx bx-trophy fs-2' style="color: #ff0055"></i>
                                    <label class="fs-4 fw-bold" style="color: #ff0055">Top 4 Agent</label><br>
                                </div>
                                <small>Agent with most released units</small>
                            </div>
                            <h3 class="fw-bold" id="deliveriesCountCard" style="color: #ff0055">Angelica Mae Bonganay</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="">
                                <div class="d-flex align-items-center gap-2">
                                    <i class='bx bx-trophy fs-2' style="color: #ff0055"></i>
                                    <label class="fs-4 fw-bold" style="color: #ff0055">Top 5 Agent</label><br>
                                </div>
                                <small>Agent with most released units</small>
                            </div>
                            <h3 class="fw-bold" id="deliveriesCountCard" style="color: #ff0055">Angelica Mae Bonganay</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="col-md">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="rankingTable" class="table table-hover">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md">
        <div class="card">
            <div class="card-body">
                <div id="rankingBarChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection



@section('components.specific_page_scripts')
<script>

        function showLoader() {
            Swal.fire({
                title: 'Loading...',
                text: 'Please wait while we fetch the data.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
        function hideLoader() {
            Swal.close();
        }
        // Initialize flatpickr for date range picker
        flatpickr("#date-range-picker", {
            mode: "range",
            dateFormat: "m/d/Y",
            onChange: function (selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const startDate = selectedDates[0];
                    const endDate = selectedDates[1];

                    showLoader();

                    if (selectedDates[1] <= selectedDates[0]) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Warning!',
                            text: 'Please select a valid date range.',
                        });
                    } else {
                        fetchTopAgents();
                        rankingTable.ajax.reload(null, false);
                        fetchAgentData();
                    }

                    hideLoader();
                }
            },
            onReady: function (selectedDates, dateStr, instance) {
                // Create a "Clear" button
                const clearButton = document.createElement("button");
                clearButton.innerHTML = "Clear";
                clearButton.classList.add("clear-btn");

                // Create a "Close" button
                const closeButton = document.createElement("button");
                closeButton.innerHTML = "Close";
                closeButton.classList.add("close-btn");

                // Append the buttons to the flatpickr calendar
                instance.calendarContainer.appendChild(clearButton);
                instance.calendarContainer.appendChild(closeButton);

                // Add event listener to clear the date and reload the tables
                clearButton.addEventListener("click", function () {
                    instance.clear(); // Clear the date range
                    fetchTopAgents();
                    rankingTable.ajax.reload(null, false);
                    fetchAgentData();

                });

                // Add event listener to close the calendar
                closeButton.addEventListener("click", function () {
                    instance.close(); // Close the flatpickr calendar
                });
            }
        });


        function fetchTopAgents() {
            let url = '{{ route("dashboard.ranking-dashboard.topAgent") }}';
            
            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    date_range: $('#date-range-picker').val()
                },
                success: function (response) {
                    if (response.agents) {
                        $('#top1Agent').text(response.agents[0] ? `${response.agents[0].agent.first_name} ${response.agents[0].agent.last_name}` : 'N/A');
                        $('#top2Agent').text(response.agents[1] ? `${response.agents[1].agent.first_name} ${response.agents[1].agent.last_name}` : 'N/A');
                        $('#top3Agent').text(response.agents[2] ? `${response.agents[2].agent.first_name} ${response.agents[2].agent.last_name}` : 'N/A');
                    } else {
                        $('#top1Agent').text('N/A');
                        $('#top2Agent').text('N/A');
                        $('#top3Agent').text('N/A');
                    }
                },
                error: function () {
                    $('#top1Agent').text('N/A');
                    $('#top2Agent').text('N/A');
                    $('#top3Agent').text('N/A');
                }
            });
        }
        fetchTopAgents();

        // Datatable Initilization
        const rankingTable = $('#rankingTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("dashboard.ranking-dashboard.topAgentList") }}',
                data: function(d) {
                    d.date_range = $('#date-range-picker').val();
                },
            },
            pageLength: 10,
            paging: true,
            responsive: true,
            dom: '<"top"lf>rt<"bottom"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search..."
            },

            columns: [
                { data: 'rank', title: 'Rank' },
                { data: 'agent', title: 'MP/Agent' },
                { data: 'total', title: 'Number of Released Units' },
                
            ],
            columnDefs: [
                
            ],
        });
    

        // Total Inquiries in Inquiries Bar Graph
        function fetchAgentData() {
            $.ajax({
                url: '{{ route("dashboard.ranking-dashboard.topAgentBarChart") }}',
                type: 'GET',
                data: {
                    date_range: $('#date-range-picker').val(),
                },
                success: function(response) {
                    const labels = response.agents.map(item => `${item.agent.first_name} ${item.agent.last_name}`);
                    const data = response.agents.map(item => item.total);
                    renderAgentDataChart(labels, data);
                }
            });
        }

        var AgentData = null;

        function renderAgentDataChart(labels, data){
             // Render the bar chart with the fetched data
            var options = {
            series: [{
            name: 'Inflation',
            data: data,
                }],
                chart: {
                height: 350,
                type: 'bar',
                },
                plotOptions: {
                bar: {
                    borderRadius: 10,
                    dataLabels: {
                    position: 'top', // top, center, bottom
                    },
                }
                },
                dataLabels: {
                enabled: true,
                formatter: function (val) {
                    return val;
                },
                offsetY: -20,
                style: {
                            fontSize: '12px',
                            colors: ["#ff0055"] // Data label color
                        }
                },

                xaxis: {
                categories: labels,
                position: 'top',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                crosshairs: {
                    fill: {
                    type: 'gradient',
                    gradient: {
                        colorFrom: '#D8E3F0',
                        colorTo: '#BED1E6',
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    }
                    }
                },
                tooltip: {
                    enabled: true,
                }
                },
                yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: true,
                },
                labels: {
                    show: true,
                    formatter: function (val) {
                    return val;
                    }
                }

                },
                colors: ['#282830'], // Set the base bar color
                    states: {
                        hover: {
                            filter: {
                                type: 'lighten', // Lighten the color on hover
                                value: 0.2 // Adjust the amount of lightening
                            }
                        },
                        active: {
                            allowMultipleDataPointsSelection: false,
                            filter: {
                                type: 'darken', // Darken the color on selection
                                value: 0.3 // Adjust the amount of darkening
                            }
                        }
                    },
                    title: {
                        text: 'TOP PERFORMING MP/AGENTS BY UNITS RELEASED',
                        floating: true,
                        offsetY: 330,
                        align: 'center',
                        style: {
                            color: '#ff0055'
                        }
                    }
            };

            if (AgentData) {
                AgentData.destroy();
            }

            // Create a new chart instance
            AgentData = new ApexCharts(document.querySelector("#rankingBarChart"), options);
            AgentData.render();

        }
        fetchAgentData();


       

        
</script>
@endsection
