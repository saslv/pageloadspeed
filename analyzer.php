<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Page Load Speed Tester Analyzer</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <style>
        body{
            background-color: #000;
            color: #fff;
        }

        .chart .bar{
            background-color: #000;
            height: 100%;
            /*opacity: 0.5;*/
            position: absolute;
            top: 0;
            pointer-events: none;
            z-index: -1;
        }

        .chart{
            position: relative;
            z-index: 2;
        }

        .chart-holder{
            background-color: #888;
        }
    </style>
</head>
<body>
<div style="
    margin: 50px auto 0; width: 1150px;
    background-color: #474747; border-radius: 5px;
    box-shadow: 0 0 5px #fff; padding: 10px;
    box-sizing: border-box;
    ">
    <h1 style="margin-top: 0;">Page Load Speed Tester Analyzer</h1>
    <div style="border-bottom: 1px solid;" id="controls">Open file </div>

    <div id="results" style="display: none;">
        <div style="max-height: 300px; overflow-y: scroll; border: 1px solid;">
            <pre id="file-content" style="margin: 0;"></pre>
        </div>
        <div id="charts">

        </div>
    </div>
</div>

<script>
    function draw_charts(data, file_name){
        $('#results').slideDown();

        var longest = 0;

        for(var key in data){
            var val = data[key];
            if(longest < val.avg){
                longest = val.avg;
            }
        }

        var chart_holder = $('#charts').append('<div class="chart-holder"><div class="file_name">'+file_name+'</div></div>').find('.chart-holder');

        for(var key in data){
            var val = data[key];
            var percent = parseFloat(val.avg * 100 / longest);
            chart_holder.append(
                '<div class="chart">' +
                    val.name + ' (avg ' + val.avg + ' ms)' +
                    '<div class="bar" style="width: ' + percent + '%;"></div>' +
                '</div>'
            );
        }
    }

    function readSingleFile(e) {
        var file = e.target.files[0];
        if (!file) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e){
            var contents = e.target.result;
            console.log(e.target);
            $('#file-content').text(contents);
            draw_charts(parseResults(contents), '');
        };
        reader.readAsText(file);
    }

    function parseResults(input){
        var lines = input.split('\n');
        var output = [];

        for(var key in lines){
            var name_and_values = lines[key].split(' ');
            var values = name_and_values[1].replace('[', '').replace(']', '').split(',');
            var sum = 0;

            for(var k in values){
                sum += parseInt(values[k]);
            }

            var avg = sum/values.length;

            output.push({'name': name_and_values[0], 'values': name_and_values[1], 'avg': avg});
        }

        return output;
    }

    function getChartOpacity(percent){
        return parseInt(percent*0.5+20);
    }

    $('#controls').append('<input type="file" id="file-input">');

    document.getElementById('file-input').addEventListener('change', readSingleFile, false);
</script>
</body>
</html>