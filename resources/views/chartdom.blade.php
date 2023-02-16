<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js">
  </script>
  <link href="https://unpkg.com/vis-timeline@latest/styles/vis-timeline-graph2d.min.css" rel="stylesheet"
    type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
  <style>
    .block {
      margin-top: 20px;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link active" href="#">Domestik</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/flight/internasional">Internasional</a>
      </li>
      <li class="nav-item">
        <a href="/flight/add/domestik" class="btn">Add Data</a>
      </li>
    </ul>
    <a class="btn" id="downloadLink"></a>
    <div class="block">

      <label for="dateFrom">Date From:</label>
      <input type="datetime-local" class="form-control" id="dateFrom" name="dateFrom">
      <label for="dateTo">Date To:</label>
      <input type="datetime-local" class="form-control" id="dateTo" name="dateTo">
      <button id="dateButton">Set Date</button>
    </div>
    <div class="block" id="chartWrapper">
      <div id="flightData"></div>
      <div id="newFlightData"></div>
    </div>
  </div>

  <!-- Button trigger modal -->

  <!-- Modal -->
  <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
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
              <p class="data">Gate : </p>
              <p class="data" id="gate"></p>
            </div>
            <div>
              <p class="data">Flight Destination : </p>
              <p class="data" id="flightDest"></p>
            </div>
            <div>
              <p class="data">Flight Type : </p>
              <p class="data" id="flightType"></p>
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

  <script src="{{asset('JS')}}/chartdom.js"></script>
  <style>
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
  </style>

</body>