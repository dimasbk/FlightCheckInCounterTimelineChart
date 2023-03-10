$(document).ready(function () {
    var container = document.getElementById("newFlightData");
    var output = new vis.DataSet();
    var groups = new vis.DataSet();
    var items = new vis.DataSet();
    var options = {
        groupOrder: "id",
        orientation: { axis: "top" },
        maxHeight: "75%",
        verticalScroll: true,
        margin: {
            item: { horizontal: 0, vertical: 0 },
        },
        zoomKey: "shiftKey",
    };

    document.getElementById("dateButton").onclick = function () {
        items.clear();
        groups.clear();
        output.clear();

        from = document.getElementById("dateFrom").value;
        to = document.getElementById("dateTo").value;
        let dateFrom = new Date(from);
        let dateTo = new Date(to);
        var timestampFrom = parseInt((dateFrom.getTime() / 1000).toFixed(0));
        var timestampTo = parseInt((dateTo.getTime() / 1000).toFixed(0));
        //alert(height);
        timeline.setOptions({
            min: dateFrom,
            max: dateTo,
        });
        call(timestampFrom, timestampTo);
    };

    //console.log(options);
    var timeline = new vis.Timeline(container, items, groups, options);

    document.getElementById("searchButton").onclick = function () {
        let searchParam = $("#search").val();
        from = document.getElementById("dateFrom").value;
        to = document.getElementById("dateTo").value;
        let dateFrom = new Date(from);
        let dateTo = new Date(to);
        var timestampFrom = parseInt((dateFrom.getTime() / 1000).toFixed(0));
        var timestampTo = parseInt((dateTo.getTime() / 1000).toFixed(0));

        $.ajax({
            type: "GET",
            url: "/flight/data/gate/search",
            headers: {
                Accept: "application/json",
            },
            data: {
                param: searchParam,
                type: "Internasional",
                from: timestampFrom,
                to: timestampTo,
            },
            success: function (result) {
                if (!result) {
                    alert("Data Tidak Ditemukan");
                } else {
                    timeline.focus(parseInt(result));
                }
            },
            error: function (error) {
                console.log(error);
            },
        });
    };

    function call(from, to) {
        $.ajax({
            type: "GET",
            url: "/flight/data/gate/internasional",
            headers: {
                Accept: "application/json",
            },
            data: {
                from: from,
                to: to,
            },
            success: function (internasionalData) {
                console.log(internasionalData);
                Chart(internasionalData);
                timeline.redraw();
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function Chart(data) {
        var dataGate = data.gate;
        var dataSchedule = data.flightData;
        dataGate.forEach(function (row) {
            groups.add({ id: row.id, content: "Gate " + row.gate });
        });

        // create a dataset with items

        dataSchedule.forEach(function (row) {
            start = new Date(row.schedule_time);
            timeEnd = new Date(row.schedule_time);
            //group = parseInt(row.checkin_desk);
            end = new Date(timeEnd.setMinutes(timeEnd.getMinutes() + 30));
            items.add({
                id: row.id_departure,
                group: row.gate,
                content: row.flight_number,
                start: start,
                end: end,
                style: "background-color:" + row.chartColor + "; color: white",
            });

            output.add({
                flightNumber: row.flight_number,
                origin: row.airport_code,
                airplaneType: row.airplane_type,
                scheduleTime: row.schedule_time,
                gate: row.gate,
            });
        });

        console.log(items);
        // create visualization

        let itemsArray = [];
        itemsArray = output.get();
        if (itemsArray.length === 0) {
            const link = document.getElementById("downloadLink");
            link.removeAttribute("href");

            link.textContent = "No Data";
        } else {
            const array = [Object.keys(itemsArray[0])].concat(itemsArray);

            let array1 = array
                .map((it) => {
                    return Object.values(it).toString();
                })
                .join("\n");

            //console.log(array1);

            let currentDate = new Date().toJSON().slice(0, 10);
            const blob = new Blob([array1], {
                type: "text/csv;charset=utf-8,",
            });
            const objUrl = URL.createObjectURL(blob);
            const link = document.getElementById("downloadLink");
            link.setAttribute("href", objUrl);
            link.setAttribute(
                "download",
                "FlightScheduleDomestik" + currentDate + ".csv"
            );
            link.textContent = "Export to CSV";

            //document.querySelector("#download").append(link);
        }

        timeline.on("click", function (properties) {
            if (properties.item) {
                // An item was clicked, get the item from dataset
                const item = items.get(properties.item);
                console.log(item.id);
                $.ajax({
                    type: "GET",
                    url: "/flight/data/departure/modal",
                    headers: {
                        Accept: "application/json",
                    },
                    data: {
                        id: item.id,
                    },
                    success: function (data) {
                        console.log(data);

                        data.forEach(function (row) {
                            console.log(row.gate);
                            start = new Date(row.schedule_time);
                            //group = parseInt(row.checkin_desk);
                            timeEnd = new Date(row.schedule_time);
                            //group = parseInt(row.checkin_desk);

                            end = new Date(
                                timeEnd.setMinutes(timeEnd.getMinutes() + 30)
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
                                row.type;
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
