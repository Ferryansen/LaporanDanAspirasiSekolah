<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urgent Report</title>

    {{-- Add Google Font --}}
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    
    <style>
        *{
            font-family: 'Open Sans', sans-serif;
        }
        .button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .listButton {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #btn-submit{
            background-color: #0D6CF9;
        }
        #btn-close{
            background-color: #BB2D3B;
        }
        #cameraFeed {
            width: 100%;
            height: 86vh;
            object-fit: cover; 
        }
        #previewContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 20px;
        }
        .previewItem {
            margin: 10px;
            max-width: 300px;
        }
        #laporkanButton {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        #popupOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        #popupContent {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        #popupContent input,
        #popupContent textarea,
        #popupContent select {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        #popupContent input:focus,
        #popupContent textarea:focus,
        #popupContent select:focus {
            outline: none;
            border-color: #0D6CF9;
        }
        
        #popupContent form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        #popupContent .button {
            margin: 10px;
        }

        #mediaPreview {
            margin-bottom: 20px;
        }
        .invalid-feedback {
            color: red;
            font-size: 14px;
        }

        /* Added styles for circular buttons */
        #videoButton, #takePhotoButton {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 24px;
            margin: 10px;
        }
        #videoButton{
            background-color: #BB2D3B;
        }
        #takePhotoButton{
            background-color: black;
        }
    </style>
</head>
<body>
    
    
    <video id="cameraFeed" autoplay></video>
    <div class="listButton">
        <button id="videoButton" class="button"><i class="fa-solid fa-video"></i></button>
        <button id="takePhotoButton" class="button"><i class="fa-solid fa-camera"></i></button>
    </div>

    <div id="previewContainer"></div>

    <div id="popupOverlay">
        <div id="popupContent">
            <div id="mediaPreview"></div>
            <form id="reportForm" enctype="multipart/form-data" method="POST" action="{{ route('student.createReportUrgent') }}">
                @csrf
                <input type="text" class="form-control" name="reportName" placeholder="Judul" required value="{{ old('reportName') }}">
                <textarea class="form-control @error('reportDescription') is-invalid @enderror" name="reportDescription" placeholder="Deskripsi" required>{{ old('reportDescription') }}</textarea>
                    @error('reportDescription')
                        <div class="invalid-feedback">
                            {{ "Maksimal 200 Karakter" }} 
                        </div>
                    @enderror
                <select name="reportCategory" class="form-select" aria-label="Default select example" required>
                    <option selected disabled value>Pilih Kategori Laporan</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="file" id="mediaFile" name="mediaFile" accept="image/jpeg, image/png, image/jpg, image/webp, video/webm, video/mp4, video/avi, video/quicktime" max="40960" class="form-control @error('mediaFile') is-invalid @enderror" required hidden>
                <input type="hidden" name="mediaType" id="mediaType">
                <button type="button" onclick="closePopup()" class="button" id="btn-close">Close</button>
                <button type="submit" class="button" id="btn-submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        const videoButton = document.getElementById('videoButton');
        const takePhotoButton = document.getElementById('takePhotoButton');
        const cameraFeed = document.getElementById('cameraFeed');
        const previewContainer = document.getElementById('previewContainer');
        const popupOverlay = document.getElementById('popupOverlay');
        const mediaPreview = document.getElementById('mediaPreview');
        const reportForm = document.getElementById('reportForm');
        const mediaFileInput = document.getElementById('mediaFile');
        const mediaTypeInput = document.getElementById('mediaType');
        let stream;
        let recorder;
        let recordedChunks = [];

        window.onload = startCamera;

        videoButton.addEventListener('click', toggleVideoRecording);
        takePhotoButton.addEventListener('click', takePhoto);

        function startCamera() {
            navigator.mediaDevices.getUserMedia({ video: true, audio: true })
                .then(function(mediaStream) {
                    stream = mediaStream;
                    cameraFeed.srcObject = mediaStream;
                })
                .catch(function(error) {
                    console.error('Error accessing camera:', error);
                });
        }

        function toggleVideoRecording() {
            if (!recorder || recorder.state !== 'recording') {
                startRecording();
                videoButton.innerHTML = '<i class="fa-solid fa-stop"></i>';
            } else {
                stopRecording();
                videoButton.innerHTML = '<i class="fa-solid fa-video"></i>';
            }
        }

        function startRecording() {
            recordedChunks = [];
            recorder = new MediaRecorder(stream, {
                mimeType:"video/webm; codecs=vp8"
            });

            recorder.ondataavailable = function(event) {
                if (event.data.size > 0) {
                    recordedChunks.push(event.data);
                }
            };
            recorder.onstop = function() {
                const blob = new Blob(recordedChunks, { type: 'video/webm' });
                const videoURL = URL.createObjectURL(blob);
                const video = document.createElement('video');
                video.src = videoURL;
                video.controls = true;
                video.classList.add('previewItem');
                previewContainer.appendChild(video);

                mediaTypeInput.value = 'video';
                saveFile(blob, 'video.webm'); // Change the file name here
                showPopup(video);
            };
            recorder.start();
        }

        function stopRecording() {
            recorder.stop();
        }

        function takePhoto() {
            if (stream) {
                clearPreview();
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = cameraFeed.videoWidth;
                canvas.height = cameraFeed.videoHeight;
                context.drawImage(cameraFeed, 0, 0, canvas.width, canvas.height);
                canvas.toBlob(function(blob) {
                    const photoURL = URL.createObjectURL(blob);
                    const previewImage = document.createElement('img');
                    previewImage.src = photoURL;
                    previewImage.classList.add('previewItem');
                    previewContainer.appendChild(previewImage);

                    mediaTypeInput.value = 'image';
                    saveFile(blob, 'photo.jpg');
                    showPopup(previewImage);
                }, 'image/jpeg');
            }
        }

        function saveFile(blob, fileName) {
            const file = new File([blob], fileName, { type: blob.type });
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            mediaFileInput.files = dataTransfer.files;
        }

        function clearPreview() {
            while (previewContainer.firstChild) {
                previewContainer.removeChild(previewContainer.firstChild);
            }
        }

        function showPopup(mediaElement) {
            mediaPreview.innerHTML = '';
            mediaPreview.appendChild(mediaElement);
            popupOverlay.style.display = 'flex';
        }

        function closePopup() {
            popupOverlay.style.display = 'none';
        }
    </script>
    <script src="https://kit.fontawesome.com/f98710255c.js" crossorigin="anonymous"></script>
</body>
</html>