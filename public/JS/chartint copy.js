$(document).ready(function () {
    $.ajax({
        type: "GET",
        url: "/flight/data/internasional",
        headers: {
            Accept: "application/json",
        },
        success: function (internationalData) {
            console.log(internationalData);
            Chart(internationalData);
        },
        error: function (error) {
            console.log(error);
        },
    });
    function Chart(data) {
        var container = document.getElementById("newFlightData");
        var groups = new vis.DataSet();
        var dataCounter = data.counter;
        var dataSchedule = data.flightData;
        dataCounter.forEach(function (row) {
            //console.log(row.schedule_time);

            groups.add({ id: row.id, content: "Counter " + row.checkin_desk });
        });

        // create a dataset with items
        var items = new vis.DataSet();
        var output = new vis.DataSet();

        dataSchedule.forEach(function (row) {
            timeStart = new Date(row.schedule_time);
            timeEnd = new Date(row.schedule_time);
            //group = parseInt(row.checkin_desk);

            start = new Date(timeStart.setHours(timeStart.getHours() - 3));

            end = new Date(timeEnd.setMinutes(timeEnd.getMinutes() - 30));
            checkinDesk = row.id_checkin_desk;
            var checkinDeskArray = checkinDesk.split(",").map(Number);
            checkinDeskArray.forEach(function (itemData) {
                items.add({
                    id: row.id_schedule + row.flight_number + itemData,
                    group: itemData,
                    content: row.flight_number,
                    start: start,
                    end: end,
                    style:
                        "background-color:" + row.chartColor + "; color: white",
                });
            });

            var desk = checkinDesk.split(",").map(Number);
            deskFirst = desk[0];
            deskLast = desk[desk.length - 1];
            deskId = deskFirst + "-" + deskLast;
            console.log(deskId);
            //console.log(desk);
            output.add({
                flightNumber: row.flight_number,
                destination: row.airport_code,
                airplaneType: row.airplane_type,
                scheduleTime: row.schedule_time,
                checkinDeskId: deskId,
                gate: row.gate,
                pax: row.pax,
                cic: row.cic,
            });
        });

        console.log(items);
        // create visualization
        var options = {
            groupOrder: "id",
            orientation: { axis: "top" },
            //maxHeight: "75%",
            verticalScroll: true,
            margin: {
                item: { horizontal: 0, vertical: 0 },
                axis: 5,
            },
            zoomKey: "ctrlKey",
        };

        let itemsArray = output.get();
        var timeline = new vis.Timeline(container);
        timeline.setOptions(options);
        timeline.setGroups(groups);
        timeline.setItems(items);

        const array = [Object.keys(itemsArray[0])].concat(itemsArray);

        let array1 = array
            .map((it) => {
                return Object.values(it).toString();
            })
            .join("\n");

        console.log(array1);

        let currentDate = new Date().toJSON().slice(0, 10);
        const blob = new Blob([array1], {
            type: "text/csv;charset=utf-8,",
        });
        const objUrl = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", objUrl);
        link.setAttribute(
            "download",
            "FlightScheduleInternasional" + currentDate + ".csv"
        );
        link.textContent = "Export to CSV";

        document.querySelector("#download").append(link);

        document.getElementById("dateButton").onclick = function () {
            from = document.getElementById("dateFrom").value;
            to = document.getElementById("dateTo").value;
            let dateFrom = new Date(from);
            let dateTo = new Date(to);
            //alert(height);
            timeline.setOptions({
                min: dateFrom,
                max: dateTo,
            });
        };

        timeline.on("click", function (properties) {
            if (properties.item) {
                // An item was clicked, get the item from dataset
                const item = items.get(properties.item);
                console.log(item.id);
                $.ajax({
                    type: "GET",
                    url: "/flight/data/modal/" + item.id,
                    headers: {
                        Accept: "application/json",
                    },
                    success: function (data) {
                        //console.log(data);

                        data.forEach(function (row) {
                            timeStart = new Date(row.schedule_time);
                            timeEnd = new Date(row.schedule_time);
                            //group = parseInt(row.checkin_desk);

                            start = new Date(
                                timeStart.setHours(timeStart.getHours() - 3)
                            );

                            end = new Date(
                                timeEnd.setMinutes(timeEnd.getMinutes() - 30)
                            );
                            document.getElementById("flightNumber").innerHTML =
                                row.flight_number;
                            document.getElementById("fromDate").innerHTML =
                                start;
                            document.getElementById("toDate").innerHTML = end;
                            document.getElementById("airline").innerHTML =
                                row.airline;
                            document.getElementById("gate").innerHTML =
                                row.gate;
                            document.getElementById("flightDest").innerHTML =
                                row.airport_code;
                            document.getElementById("flightType").innerHTML =
                                row.flightType;
                            $("#detailModal").modal("show");
                        });
                    },
                    error: function (error) {
                        console.log(error);
                    },
                });
            }
        });
    }
});
