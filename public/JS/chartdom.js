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
            url: "/flight/data/search",
            headers: {
                Accept: "application/json",
            },
            data: {
                param: searchParam,
                type: "Domestik",
                from: timestampFrom,
                to: timestampTo,
            },
            success: function (result) {
                let resultArray = [];
                result.forEach(function (row) {
                    //resultArray.push(row.)
                    desk_id = row.id_checkin_desk;
                    var deskArray = checkinDesk.split(",").map(Number);
                    let number = 10;
                    deskArray.forEach(function () {
                        let itemId = `${row.id_schedule}${number}`;
                        resultArray.push(itemId);
                        number++;
                    });
                });
                if (!resultArray.length) {
                    alert("Data Tidak Ditemukan");
                } else {
                    resultArray.toString();
                    console.log(resultArray);
                    timeline.focus(resultArray);
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
            url: "/flight/data/domestik",
            headers: {
                Accept: "application/json",
            },
            data: {
                from: from,
                to: to,
            },
            success: function (internationalData) {
                //console.log(internationalData);
                Chart(internationalData);
                timeline.redraw();
            },
            error: function (error) {
                console.log(error);
            },
        });
    }

    function Chart(data) {
        var dataCounter = data.counter;
        var dataSchedule = data.flightData;
        dataCounter.forEach(function (row) {
            //console.log(row.schedule_time);

            groups.add({ id: row.id, content: "Counter " + row.checkin_desk });
        });

        // create a dataset with items

        dataSchedule.forEach(function (row) {
            timeStart = new Date(row.schedule_time);
            timeEnd = new Date(row.schedule_time);
            //group = parseInt(row.checkin_desk);

            start = new Date(timeStart.setHours(timeStart.getHours() - 3));

            end = new Date(timeEnd.setMinutes(timeEnd.getMinutes() - 30));
            checkinDesk = row.id_checkin_desk;
            var checkinDeskArray = checkinDesk.split(",").map(Number);
            let number = 10;
            checkinDeskArray.forEach(function (itemData) {
                let itemId = `${row.id_schedule}${number}`;
                items.add({
                    id: itemId,
                    group: itemData,
                    content: row.flight_number,
                    start: start,
                    end: end,
                    style:
                        "background-color:" + row.chartColor + "; color: white",
                });
                number++;
            });

            var desk = checkinDesk.split(",").map(Number);
            deskFirst = desk[0];
            deskLast = desk[desk.length - 1];
            deskId = deskFirst + "-" + deskLast;
            //console.log(deskId);
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

        //console.log(items);
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
                let x = item.id;
                const newNum = Number(x.toString().slice(0, -2));
                console.log(newNum);
                $.ajax({
                    type: "GET",
                    url: "/flight/data/modal",
                    headers: {
                        Accept: "application/json",
                    },
                    data: {
                        id: newNum,
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
