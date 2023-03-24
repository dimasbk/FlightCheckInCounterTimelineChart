<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript"
        src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js">
    </script>
    <link href="https://unpkg.com/vis-timeline@latest/styles/vis-timeline-graph2d.min.css" rel="stylesheet"
        type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <style>
        .block {
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .interaction {
            margin-top: 10px
        }

        .flightData {
            display: block
        }

        .data {
            display: inline
        }

        #chartWrapper {
            overflow-x: scroll;
            overflow-y: hidden;
            width: 100%;
        }

        .vis-item.duplicate {
            border-color: red;
            animation: blink 1s linear infinite;
        }

        @keyframes blink {
            0% {
                border-color: red;
            }

            16.666% {
                border-color: orange;
            }

            33.333% {
                border-color: yellow;
            }

            50% {
                border-color: green;
            }

            66.666% {
                border-color: blue;
            }

            83.333% {
                border-color: indigo;
            }

            100% {
                border-color: violet;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="/flight/departure/domestik">Departure Domestik</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/flight/departure/internasional">Departure Internasional</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="/flight/arrival/domestik">Arrival Domestik</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#">Arrival Internasional</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/flight/gate/domestik">Departure Gate Domestik</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/flight/gate/internasional">Departure Gate Internasional</a>
            </li>
            <li class="nav-item">
                <a href="/flight/add/internasional" class="btn">Add Data</a>
            </li>
            <li>
                <a data-toggle="modal" data-target="#modalExport">
                    Export All Data
                </a>
            </li>
            <li id="download">
                <a id="downloadLink"></a>
            </li>
        </ul>
        <div class="modal fade" id="modalExport" tabindex="-1" role="dialog" aria-labelledby="modalExportLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalExportLabel">Export All Data</h5>

                    </div>
                    <div class="modal-body">
                        <form action="/flight/add/export" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-3">
                                <label for="schedule-time">Schedule Time:</label>
                                <input type="date" class="form-control" id="exportDate" name="exportDate">
                            </div>
                            <button class="btn btn-primary" type="submit">Download</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="block">
            <h2>International Arrival</h2>
            <label class="interaction" for="dateFrom">Date From:</label>
            <input class="interaction" type="datetime-local" class="form-control" id="dateFrom" name="dateFrom"><br>
            <label class="interaction" for="dateTo">Date To:</label>
            <input class="interaction" type="datetime-local" class="form-control" id="dateTo" name="dateTo">
            <button class="interaction" id="dateButton">Set Date</button>
        </div>
        <div>
            <label class="interaction" for="search">Search For Flight</label>
            <input class="interaction" type="text" class="form-control" id="search" name="search">
            <button class="interaction" id="searchButton">Search</button>
        </div>
        <div class="block" id="chartWrapper">
            <div id="newFlightData"></div>
        </div>
    </div>

    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Flight Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="flightData">
                        <div>
                            <p class="data">Flight Number : </p>
                            <p class="data" id="flightNumber"></p>
                        </div>
                        <div>
                            <p class="data">From : </p>
                            <p class="data" id="fromDate"></p>
                        </div>
                        <div>
                            <p class="data">To : </p>
                            <p class="data" id="toDate"></p>
                        </div>
                        <div>
                            <p class="data">Airline : </p>
                            <p class="data" id="airline"></p>
                        </div>
                        <div>
                            <p class="data">Origin : </p>
                            <p class="data" id="origin"></p>
                        </div>
                        <div>
                            <p class="data">Belt : </p>
                            <p class="data" id="Belt"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <script src="{{asset('JS')}}/InternasionalArrival.js"></script>

</body>