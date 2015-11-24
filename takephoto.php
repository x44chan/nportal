<?php include("header.php");?>
    <script type="text/javascript" src="webcam.js"></script>
    <script>
        webcam.set_api_url( 'photo.php' );
        webcam.set_quality( 90 ); // JPEG quality (1 - 100)
        webcam.set_shutter_sound( true ); // play shutter click sound
        
        webcam.set_hook( 'onComplete', 'my_completion_handler' );
        
        function take_snapshot() {
            // take snapshot and upload to server
            document.getElementById('upload_results').innerHTML = 'Snapshot<br>'+
            '<img src="uploading.gif">';
            webcam.snap();
        }
        
        function my_completion_handler(msg) {
            // extract URL out of PHP output
            if (msg.match(/(http\:\/\/\S+)/)) {
                var image_url = RegExp.$1;
                // show JPEG image in page
                document.getElementById('upload_results').innerHTML = 
                    'Snapshot<br>' + 
                    '<a href="'+image_url+'" target"_blank"><img src="' + image_url + '?time"></a>';
                
                // reset camera for another shot
                webcam.reset();
            }
            else alert("PHP Error: " + msg);
        }
    </script>
	<table class="main" align = "center">
        <tr>
            <td valign="top">
	            <div class="border">
                Live Webcam<br>
                <script>
                document.write( webcam.get_html(320, 240) );
                </script>
                </div>
                <br/><input type="button" class="btn btn-primary" value="SNAP IT" onClick="take_snapshot()">
                <br/><br/><input type="button" class="btn btn-primary" id = "upload" value="Done?">
                <br/><br/><a href = "index.php" type="button" class="btn btn-danger" id = "upload" value="Back">Back</a>
            </td>
            <td width="50">&nbsp;</td>
            <td valign="top">
                <div id="upload_results" class="border">
                    Snapshot<br>
                    <img src="images/default.jpg" />
                </div>
            </td>
        </tr>
    </table>
<script type="text/javascript">
	$(document).ready(function(){	
    	$("#upload").on("click", function(){
    		$(location).attr('href', 'index.php')
	    });
	});
</script>
<?php include("footer.php");?>