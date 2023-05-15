<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drag and Drop Image Upload Using PHP</title>
    
    <!-- Style CSS -->
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .upload-container {
            text-align: center;
            width: 400px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .drop-area {
            border: 2px dashed #007bff;
            padding: 50px;
            background-color: #fff;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .drop-area.dragover {
            background-color: #e9f7fe;
        }

        .drop-area p {
            font-size: 16px;
            color: #666;
        }

        .drop-area span {
            color: #007bff;
            cursor: pointer;
        }

        .preview-container {
            display: none;
            margin-top: 20px;
            text-align: center;
        }

        .preview-container img {
            max-width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .preview-container button {
            padding: 10px 20px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-upload {
            background-color: #28a745;
            color: #fff;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: #fff;
        }

        .gallery {
            margin-top: 30px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .gallery img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

    <div class="upload-container">
        <h1>Drag and Drop Image Upload</h1>
        <div id="drop-area" class="drop-area">
            <p>Drag & Drop your images here or <span id="browse">Browse</span></p>
            <input type="file" id="fileElem" accept="image/*" style="display:none">
        </div>

        <!-- Preview and confirmation buttons -->
        <div id="preview-container" class="preview-container">
            <img id="preview-image" alt="Image Preview">
            <div>
                <button class="btn-upload" id="upload-btn">Upload</button>
                <button class="btn-cancel" id="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Gallery to display uploaded images -->
    <div class="gallery" id="gallery"></div>

    <!-- Script JS -->
    <script>
        const dropArea = document.getElementById('drop-area');
        const fileElem = document.getElementById('fileElem');
        const previewContainer = document.getElementById('preview-container');
        const previewImage = document.getElementById('preview-image');
        const uploadBtn = document.getElementById('upload-btn');
        const cancelBtn = document.getElementById('cancel-btn');
        const gallery = document.getElementById('gallery');
        let selectedFile;

        // Highlight the drop area when dragging over
        dropArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropArea.classList.add('dragover');
        });

        dropArea.addEventListener('dragleave', () => {
            dropArea.classList.remove('dragover');
        });

        dropArea.addEventListener('drop', (e) => {
            e.preventDefault();
            dropArea.classList.remove('dragover');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });

        document.getElementById('browse').addEventListener('click', () => {
            fileElem.click();
        });

        fileElem.addEventListener('change', () => {
            handleFiles(fileElem.files);
        });

        function handleFiles(files) {
            if (files.length > 0) {
                selectedFile = files[0];
                previewImage.src = URL.createObjectURL(selectedFile);
                previewContainer.style.display = 'block';
                dropArea.style.display = 'none';
            }
        }

        cancelBtn.addEventListener('click', () => {
            selectedFile = null;
            previewContainer.style.display = 'none';
            dropArea.style.display = 'block';
            previewImage.src = '';
        });

        uploadBtn.addEventListener('click', () => {
            if (selectedFile) {
                const formData = new FormData();
                formData.append('image', selectedFile);
                uploadImage(formData);
            }
        });

        function uploadImage(formData) {
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Append the uploaded image to the gallery
                    const img = document.createElement('img');
                    img.src = 'uploads/' + data.file;  // Assuming the image is saved in 'uploads' directory
                    gallery.appendChild(img);

                    // Reset the preview and form
                    previewContainer.style.display = 'none';
                    previewImage.src = '';
                    selectedFile = null;
                    dropArea.style.display = 'block';
                } else {
                    console.error(data.message);  // Log any error message from the server
                }
            })
            .catch(error => {
                console.error('Error uploading image:', error);
            });
        }

    </script>
</body>
</html>
