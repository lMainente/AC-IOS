<!DOCTYPE html>
<html>
<head>
  <title>Câmera do Celular</title>
  <style>
    #video {
      width: 100%;
      height: auto;
    }

    #capture-btn {
        position: absolute;
        bottom: 5%;
        left: 50%;
        transform: translateX(-50%);
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #fff;
        border: 4px solid #ddd;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }

    #capture-again-btn {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background-color: gray;
      color: white;
      font-size: 16px;
      border: none;
      margin: 0 auto;
      margin-top: 10px;
      display: none;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
    }

    #switch-camera-btn {
      position: absolute;
      top: 5%;
      right: 5%;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background-color: #fff;
      border: 2px solid #ddd;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      cursor: pointer;
    }

    @media only screen and (max-width: 600px) {
      #capture-btn {
        width: 60px;
        height: 60px;
        font-size: 14px;
        margin-top: 5px;
      }

      #capture-again-btn {
        width: 60px;
        height: 60px;
        font-size: 14px;
        margin-top: 5px;
      }

      #switch-camera-btn {
        width: 30px;
        height: 30px;
      }
    }
  </style>
</head>
<body>
  <div>
    <video id="video" autoplay playsinline></video>
    <button id="capture-btn"></button>
    <button id="capture-again-btn">Capturar Novamente</button>
    <canvas id="canvas" style="display: none;"></canvas>
    <img id="photo" style="display: none;">
    <button id="switch-camera-btn"></button>
  </div>

  <script>
    var videoStream;
    var currentCamera = 'environment';

    function startCamera(stream) {
      videoStream = stream;
      var video = document.getElementById('video');
      video.srcObject = stream;
    }

    function switchCamera() {
      if (currentCamera === 'user') {
        currentCamera = 'environment';
      } else {
        currentCamera = 'user';
      }

      if (videoStream) {
        videoStream.getTracks().forEach(function(track) {
          track.stop();
        });
      }

      navigator.mediaDevices.getUserMedia({ video: { facingMode: { exact: currentCamera } } })
        .then(function(stream) {
          startCamera(stream);
        })
        .catch(function(error) {
          console.error('Erro ao acessar a câmera:', error);
        });
    }

    navigator.mediaDevices.getUserMedia({ video: { facingMode: { exact: currentCamera } } })
      .then(function(stream) {
        startCamera(stream);
      })
      .catch(function(error) {
        console.error('Erro ao acessar a câmera:', error);
      });

    document.getElementById('capture-btn').addEventListener('click', function() {
      var video = document.getElementById('video');
      var canvas = document.getElementById('canvas');
      var photo = document.getElementById('photo');

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;

      var context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      photo.src = canvas.toDataURL('image/png');
      photo.style.display = 'block';
      video.style.display = 'none';
      document.getElementById('capture-btn').style.display = 'none';
      document.getElementById('capture-again-btn').style.display = 'block';
    });

    document.getElementById('capture-again-btn').addEventListener('click', function() {
      var video = document.getElementById('video');
      var photo = document.getElementById('photo');

      photo.src = '';
      photo.style.display = 'none';
      video.style.display = 'block';
      document.getElementById('capture-btn').style.display = 'block';
      document.getElementById('capture-again-btn').style.display = 'none';
    });

    document.getElementById('switch-camera-btn').addEventListener('click', function() {
      switchCamera();
    });
  </script>
</body>
</html>
