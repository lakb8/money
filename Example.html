<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRM Protected Video Playback with Shaka Player</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.0.10/shaka-player.compiled.js"></script>
</head>
<body>
    <h1>狂復盤 12月15日</h1>
    <video id="video" width="1920" controls></video>
    <script>
        var manifestUri = 'https://d394h7idhxrvzy.cloudfront.net/2024-12/c5f42e37-5727-45ae-9e54-1d242e9efea6/stream.mpd';
        shaka.polyfill.installAll();
        if (shaka.Player.isBrowserSupported()) {
            var video = document.getElementById('video');
            var player = new shaka.Player(video);
            player.configure({
                drm: {
                    servers: {
                        'com.widevine.alpha': 'https://widevine-dash.ezdrm.com/widevine-php/widevine-foreignkey.php?pX=FC68E4'
                    }
                }
            });
            player.load(manifestUri).then(function() {
                console.log('The video has now been loaded!');
            }).catch(function(error) {
                console.error('Error code', error.code, 'object', error);
            });
        } else {
            console.error('Browser not supported!');
        }
    </script>
</body>
</html>
