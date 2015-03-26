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
            background-color: #f00;
            height: 100%;
            opacity: 0.5;
            position: absolute;
            top: 0;
        }

        .chart{
            position: relative;
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
    function draw_charts(data){
        $('#results').slideDown();

        var longest = 0;

        for(var key in data){
            var val = data[key];
            if(longest < val.avg){
                longest = val.avg;
            }
        }

        for(var key in data){
            var val = data[key];
            $('#charts').append(
                '<div class="chart">' +
                    val.name + ' (avg ' + val.avg + ' ms)' +
                    '<div class="bar" style="width: ' + parseFloat(val.avg * 100 / longest) + '%;"></div>' +
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
            $('#file-content').text(contents);
            draw_charts(parseResults(contents));
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

    $('#controls').append('<input type="file" id="file-input">');

    document.getElementById('file-input').addEventListener('change', readSingleFile, false);
</script>
</body>
</html>