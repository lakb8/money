<?php

// 处理用户输入的文档内容
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 获取输入的文档内容
    $documentContent = $_POST['documentContent'];

    // 提取 MPD 链接、HLS 链接 和 DRM 密钥
    preg_match_all('/https?:\/\/[^\s]+\.mpd/', $documentContent, $mpdLinks);  // 提取 MPD 链接
    preg_match_all('/https?:\/\/[^\s]+\.m3u8/', $documentContent, $hlsLinks); // 提取 HLS 链接
    preg_match_all('/https?:\/\/widevine[^\s]+/', $documentContent, $drmLinks); // 提取 DRM 链接
    
    // 合并视频数据
    $videos = [];
    $videoCount = count($mpdLinks[0]);
    for ($i = 0; $i < $videoCount; $i++) {
        $videos[] = [
            'mpd' => $mpdLinks[0][$i],
            'hls' => $hlsLinks[0][$i] ?? '',
            'drm' => $drmLinks[0][$i] ?? ''
        ];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRM Protected Video Playback with Shaka Player</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.0.10/shaka-player.compiled.js"></script>
</head>
<body>

    <h1>输入文档内容</h1>

    <form method="POST">
        <textarea name="documentContent" rows="10" cols="50" placeholder="在此处输入文档内容..."></textarea><br>
        <input type="submit" value="提取视频并生成 HTML">
    </form>

    <?php if (isset($videos)): ?>
        <h2>选择要生成 HTML 的视频</h2>
        <form method="POST">
            <select name="videoIndex">
                <?php foreach ($videos as $index => $video): ?>
                    <option value="<?= $index ?>">视频 <?= $index + 1 ?></option>
                <?php endforeach; ?>
            </select>
            <input type="submit" value="生成 HTML">
        </form>

        <?php if (isset($_POST['videoIndex'])): ?>
            <?php
                // 根据选择的视频索引生成对应的视频 HTML
                $selectedVideo = $videos[$_POST['videoIndex']];
                $html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DRM Protected Video Playback with Shaka Player</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/shaka-player/3.0.10/shaka-player.compiled.js"></script>
</head>
<body>

    <h1>视频标题</h1>
    <video id="video" width="1920" controls></video>

    <script>
        // 设置视频链接
        var manifestUri = '{$selectedVideo['mpd']}';

        // 检查 Shaka Player 是否被支持
        shaka.polyfill.installAll();
        if (shaka.Player.isBrowserSupported()) {
            // 获取视频元素
            var video = document.getElementById('video');
            // 创建 Shaka Player 实例
            var player = new shaka.Player(video);

            // 配置 DRM
            player.configure({
                drm: {
                    servers: {
                        'com.widevine.alpha': '{$selectedVideo['drm']}' // Widevine DRM 许可证链接
                    }
                }
            });

            // 加载并播放视频
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
HTML;

                // 输出生成的 HTML 内容
                echo "<hr><h2>生成的 HTML 页面：</h2><pre>$html</pre>";
            ?>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
