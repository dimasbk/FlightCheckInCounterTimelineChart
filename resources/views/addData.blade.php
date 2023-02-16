<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

</head>

<body>

    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <form class="p-4 bg-light" id="flight-form" action="/flight/insert" method="POST">
            @csrf
            <div class="form-group">
                <label for="flight-number">Flight Number:</label>
                <input type="text" class="form-control" id="flight_number" name="flight_number">
            </div>

            <div class="form-group">
                <label for="airport">Destination:</label>
                <select class="form-control" id="aiport" name="airport">
                    @foreach ($airport as $item)
                    <option value="{{$item -> id_aiport}}">{{$item -> airport_code}}</option>

                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="airplane-type">Airplane Type:</label>
                <input type="text" class="form-control" id="airplane_type" name="airplane_type">
            </div>

            <div class="form-group">
                <label for="airline">Airline:</label>
                <select class="form-control" id="airline" name="airline">
                    @foreach ($airline as $item)
                    <option value="{{$item -> id_airline}}">{{$item -> airline}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="schedule-time">Schedule Time:</label>
                <input type="datetime-local" class="form-control" id="schedule_time" name="schedule_time">
            </div>

            <div class="form-group form-check">
                @foreach ($checkinDesk as $item)
                <div class="col-md-6">
                    <input type="checkbox" class="form-check-input" id="check_in_desk_{{$item -> id}}"
                        name="check_in_desk" value="{{$item -> id}}">
                    <label class="form-check-label" for="check_in_desk_{{$item -> id}}">Check-in Desk {{$item ->
                        checkin_desk}}</label>
                </div>

                @endforeach

                <input type="hidden" value="" name="checkin" id="checkin">
                @foreach($checkinDesk as $desk)
                <input type="hidden" value="{{$desk -> type}}" name="checkinType" id="checkinType">
                @break
                @endforeach
            </div>

            <div class="form-group">
                <label for="gate">Gate:</label>
                <input type="text" class="form-control" id="gate" name="gate">
            </div>

            <div class="form-group">
                <label for="pax">Pax:</label>
                <input type="text" class="form-control" id="pax" name="pax">
            </div>

            <div class="form-group">
                <label for="cic">CIC:</label>
                <input type="text" class="form-control" id="cic" name="cic">
            </div>

            <div class="form-group">
                <label for="flight-type">Flight Type:</label>
                <select class="form-control" id="flight_type" name="flight_type">
                    <option value="Domestik">Domestik</option>
                    <option value="Internasional">Internasional</option>
                </select>
            </div>
            <button type="submit" id="btn" class="btn btn-primary">Submit</button>
        </form>

    </div>

    <script>
        $(document).ready(function() {
            $("input[type='checkbox']").on('change', function() {
                if (this.checked) {
                    var selectedID = [];
                    $(':checkbox[name="check_in_desk"]:checked').each (function () {
                        selectedID.push(this.value);
                    });
                    let id = String(selectedID);
                    $("#checkin").val(id);
                }
            });
            var type = $("#checkinType").val();
            if(type == "Domestik"){
                $("#flight_type").val("Domestik");
            }else{
                $("#flight_type").val("Internasional");
            }

        });
            

    </script>
</body>

</html>