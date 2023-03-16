<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js">
  </script>
  <link href="https://unpkg.com/vis-timeline@latest/styles/vis-timeline-graph2d.min.css" rel="stylesheet"
    type="text/css" />
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@grabzit/js@3.5.2/grabzit.min.js"></script>
  <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script>

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
  </style>
</head>

<body>
  <div class="container-fluid">
    <ul class="nav nav-tabs">
      <li class="nav-item">
        <a class="nav-link" href="#">Departure Domestik</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/flight/departure/internasional">Departure Internasional</a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="/flight/arrival/domestik">Arrival Domestik</a>
      </li>
      <li class="nav-item">
        <a class="nav-link " href="/flight/arrival/internasional">Arrival Internasional</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/flight/gate/domestik">Departure Gate Domestik</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/flight/gate/internasional">Departure Gate Internasional</a>
      </li>
      <li class="nav-item">
        <a href="/flight/add/domestik" class="btn">Add Data</a>
      </li>
      <li>
        <a data-toggle="modal" data-target="#modalExport">
          Export All Data
        </a>
      </li>
      <li>
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
      <h2>Domestic Departure</h2>
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
    <div><button id="pdf">Export to PDF</button></div>
    <div class="block" id="chartWrapper">
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
              <p class="data">Pax : </p>
              <p class="data" id="pax"></p>
            </div>
            <div>
              <p class="data">CIC : </p>
              <p class="data" id="cic"></p>
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
  <script src="http://cdn.jsdelivr.net/g/filesaver.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dom-to-pdf@0.3.2/index.min.js"></script>
  <script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
  <script src="{{asset('JS')}}/DomestikDeparture.js"></script>
  <script src="{{asset('JS')}}/lib/dom-to-image.min.js"></script>
</body>