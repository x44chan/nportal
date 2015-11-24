// Put event listeners into place
        window.addEventListener("DOMContentLoaded", function() {
            // Grab elements, create settings, etc.
            var canvas = document.getElementById("canvas"),
                context = canvas.getContext("2d"),
                video = document.getElementById("video"),
                videoObj = { "video": true },
                image_format= "jpeg",
                jpeg_quality= 85,
                errBack = function(error) {
                    console.log("Video capture error: ", error.code); 
                };


            // Put video listeners into place
            if(navigator.getUserMedia) { // Standard
                navigator.getUserMedia(videoObj, function(stream) {
                    video.src = stream;
                    video.play();
                    $("#snap").show();
                }, errBack);
            } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
                navigator.webkitGetUserMedia(videoObj, function(stream){
                    video.src = window.URL.createObjectURL(stream);
                    video.play();
                    $("#snap").show();
                }, errBack);
            } else if(navigator.mozGetUserMedia) { // moz-prefixed
                navigator.mozGetUserMedia(videoObj, function(stream){
                    video.src = window.URL.createObjectURL(stream);
                    video.play();
                    $("#snap").show();
                }, errBack);
            }
                  // video.play();       these 2 lines must be repeated above 3 times
                  // $("#snap").show();  rather than here once, to keep "capture" hidden
                  //                     until after the webcam has been activated.  

            // Get-Save Snapshot - image 
            document.getElementById("snap").addEventListener("click", function() {
                context.drawImage(video, 0, 0, 400, 300);
                // the fade only works on firefox?
                $("#video").fadeOut("slow");
                $("#canvas").fadeIn("slow");
                $("#snap").hide();
                $("#reset").show();
                $("#upload").show();
            });
            // reset - clear - to Capture New Photo
            document.getElementById("reset").addEventListener("click", function() {
                $("#video").fadeIn("slow");
                $("#canvas").fadeOut("slow");
                $("#snap").show();
                $("#reset").hide();
                $("#upload").hide();
            });
            // Upload image to sever 
            document.getElementById("upload").addEventListener("click", function(){
                var dataUrl = canvas.toDataURL("image/jpeg", 0.85);
                $("#submit").show();
                $("#reset").hide();
                $("#upload").hide();
                $.ajax({
                  type: "POST",
                  url: "photo.php",
                  data: { 
                     imgBase64: dataUrl,
                           
                  }
                })
            });
        }, false);