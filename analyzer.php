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
    </div>
</div>

<script>
    function draw_charts(){
        $('#results').slideDown();
    };

    function readSingleFile(e) {
        var file = e.target.files[0];
        if (!file) {
            return;
        }
        var reader = new FileReader();
        reader.onload = function(e){
            var contents = e.target.result;
            $('#file-content').text(contents);
            draw_charts();
        };
        reader.readAsText(file);
    }

    $('#controls').append('<input type="file" id="file-input">');

    document.getElementById('file-input').addEventListener('change', readSingleFile, false);
</script>
</body>
</html>