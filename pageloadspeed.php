<?php
if(isset($_POST['save_to_file'])){
    header("Content-disposition: attachment; filename=speed_tester_" . $_POST['file_name'] . '_' . date('Y-m-d_H-i-s') . '.txt');
    header("Content-type: text/plain");
    echo $_POST['data'];
    die();
}
?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Page Load Speed Tester</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <style>
        body{
            background-color: #000;
            color: #fff;
        }

        body > iframe{
            width: 0;
            height: 0;
            border: none;
        }

        #results .result:nth-child(2n){
            background-color: #686868;
        }

        #results .result .time{
            float: right;
            font-weight: bold;
            text-overflow: ellipsis;
            overflow: hidden;
            max-width: 200px;
        }

        #results .result .url{
            text-overflow: ellipsis;
            overflow: hidden;
            max-width: 900px;
        }

        #results .result .loading{
            float: right;
            background-image: url(data:image/gif;base64,R0lGODlhEAALAPQAAP///wAAANra2tDQ0Orq6gYGBgAAAC4uLoKCgmBgYLq6uiIiIkpKSoqKimRkZL6+viYmJgQEBE5OTubm5tjY2PT09Dg4ONzc3PLy8ra2tqCgoMrKyu7u7gAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCwAAACwAAAAAEAALAAAFLSAgjmRpnqSgCuLKAq5AEIM4zDVw03ve27ifDgfkEYe04kDIDC5zrtYKRa2WQgAh+QQJCwAAACwAAAAAEAALAAAFJGBhGAVgnqhpHIeRvsDawqns0qeN5+y967tYLyicBYE7EYkYAgAh+QQJCwAAACwAAAAAEAALAAAFNiAgjothLOOIJAkiGgxjpGKiKMkbz7SN6zIawJcDwIK9W/HISxGBzdHTuBNOmcJVCyoUlk7CEAAh+QQJCwAAACwAAAAAEAALAAAFNSAgjqQIRRFUAo3jNGIkSdHqPI8Tz3V55zuaDacDyIQ+YrBH+hWPzJFzOQQaeavWi7oqnVIhACH5BAkLAAAALAAAAAAQAAsAAAUyICCOZGme1rJY5kRRk7hI0mJSVUXJtF3iOl7tltsBZsNfUegjAY3I5sgFY55KqdX1GgIAIfkECQsAAAAsAAAAABAACwAABTcgII5kaZ4kcV2EqLJipmnZhWGXaOOitm2aXQ4g7P2Ct2ER4AMul00kj5g0Al8tADY2y6C+4FIIACH5BAkLAAAALAAAAAAQAAsAAAUvICCOZGme5ERRk6iy7qpyHCVStA3gNa/7txxwlwv2isSacYUc+l4tADQGQ1mvpBAAIfkECQsAAAAsAAAAABAACwAABS8gII5kaZ7kRFGTqLLuqnIcJVK0DeA1r/u3HHCXC/aKxJpxhRz6Xi0ANAZDWa+kEAA7AAAAAAAAAAAA);
            width: 16px;
            height: 16px;
            margin-right: 15px;
            margin-left: 15px;
            background-position: center center;
            background-repeat: no-repeat;
        }

        #results .result{
            clear: both;
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
        <h1 style="margin-top: 0;">Page Load Speed Tester</h1>
        <?php
        $show_default = true;

        if(isset($_GET['file'])){
            if($_GET['file'] !== 'none' && file_exists($_GET['file'])){
                $urls = implode('', file($_GET['file']));
            }
        }else{
            if($handle = opendir('.')){
                $files = array();
                while(false !== ($entry = readdir($handle))){
                    if(strpos($entry, 'speed_tester_') === 0){
                        $files[] = $entry;
                    }
                }
                closedir($handle);

                if(count($files) > 0){
                    $show_default = false;

                    echo '<h3>Select urls file</h3>';

                    sort($files);

                    foreach($files as $file){
                        echo '<a href="?file=' . $file . '" style="color: #fff; text-decoration: none;">' . str_replace('speed_tester_', '', $file) . '</a><br>';
                    }

                    echo '<br><a href="?file=none" style="color: #fff; text-decoration: none;">skip</a><br>';
                }
            }
        }

        if($show_default):
        ?>
        <div>
            <textarea id="urls" placeholder="Input url's here (one url per line)" style="
            width: 100%; resize: vertical; height: 300px; box-sizing: border-box;
            "><?php if(isset($urls)){ echo $urls; } ?></textarea>
            <div style="margin: 5px; clear: both;">
                <form target="_blank" action="?" method="post">
                    <input type="hidden" name="data" id="form-data">
                    <input type="hidden" name="file_name" id="form-name">
                    <input type="hidden" name="save_to_file" value="true">
                </form>
                <button style="font-size: 18px;" id="go" disabled="disabled">Test url's</button>
                <button style="font-size: 18px;" id="save-results" disabled="disabled">Download results</button>
                <button style="font-size: 18px;" id="save-urls">Download urls</button>
                <span style="float: right; margin-top: 5px;">Status: <b id="status">loading</b></span>
                <span style="float: right; margin-right: 25px;">Number of repeats
                    <input id="repeats" value="1" style="font-size: 18px; width: 25px;">
                </span>
            </div>
            <div id="results" style="border: 1px solid; margin: 5px; display: none; min-height: 20px;">

            </div>
            <div id="results-note" style="font-size: 12px; display: none; margin: 5px;">* - results are in milliseconds</div>
        </div>
        <?php endif; ?>

        <hr>
        <div style="margin: 5px;">(c)saslv 2015</div>
    </div>

    <script>
        var processed = 0;
        var urls = false;
        var repeats = false;

        function measure(i){
            if(typeof urls[i] !== 'undefined'){
                var iteration_repeats = 1;
                var iframe = document.createElement('iframe');
                iframe.setAttribute('t1', (new Date()).getTime());
                iframe.setAttribute('src', urls[i]);
                iframe.onload = function () {
                    var t2 = (new Date()).getTime();
                    var dt = t2 - parseInt(this.getAttribute('t1'));

                    var result_holder = $('.result-' + i);
                    var result_time_holder = result_holder.find('.time');
                    if(result_time_holder.html().length > 0){
                        result_time_holder.html(result_time_holder.html() + ',' + dt);
                    }else{
                        result_time_holder.html(dt);
                    }

                    if(iteration_repeats < repeats){
                        iteration_repeats++;
                        iframe.setAttribute('t1', (new Date()).getTime());
                        iframe.src = urls[i];
                    }else{
                        processed++;
                        result_holder.find('.loading').remove();
                        if(processed == urls.length){
                            $('#status').text('done, ready');
                            $('button, input, textarea').removeAttr('disabled');
                        }
                        iframe.remove();
                        measure(i + 1);
                    }


                };
                document.body.appendChild(iframe);

                $('#status').text('processing [' + (i + 1) + ' of ' + urls.length + ']');
                $('#results').append(
                    '<div class="result result-' + i + '">' +
                        '<span class="url">' + urls[i] + '</span> ' +
                        '<span class="time"></span>' +
                        '<span class="loading"></span>' +
                    '</div>'
                );
            }
        }
        $('#go').click(function(){
            $('button, input, textarea').attr('disabled', 'disabled');
            $('#results').html('').slideDown();
            $('#results-note').slideDown();
            urls = $('#urls').val().split(/\n/);
            processed = 0;
            repeats = parseInt($('#repeats').val());
            measure(0);
        });
        $('#go, input, textarea').removeAttr('disabled');
        $('#status').text('ready');

        $('#save-results').attr('disabled', 'disabled').click(function(){
            var data = [];
            $('#results .result').each(function(){
                data.push($(this).find('.url').text() + ' [' + $(this).find('.time').text() + ']');
            });
            var file_name = prompt('Enter file name for saving results', 'results');
            $('#form-data').val(data.join("\r\n"));
            $('#form-name').val(file_name);
            if(file_name) $('form').submit();
        });

        $('#save-urls').click(function(){
            var file_name = prompt('Enter file name for saving results', 'urls');
            $('#form-data').val($('#urls').val());
            $('#form-name').val(file_name);
            if(file_name) $('form').submit();
        });
    </script>
</body>
</html>