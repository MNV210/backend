<form action="/upload-image" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="image">Choose an image:</label>
    <input type="file" name="image" id="image" required>
    <button type="submit">Upload</button>
</form>
