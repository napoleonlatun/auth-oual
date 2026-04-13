<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
</head>
<body>
    <h1>Edit Post</h1>

    <form action="/post/{{ $post->id }}" method="POST">
        @csrf
        @method('PUT')

        <textarea name="content" required maxlength="255">{{ $post->content }}</textarea>

        @error('content')
            <p>{{ $message }}</p>
        @enderror

        <button type="submit">Update</button>
    </form>

    <a href="/">Cancel</a>
</body>
</html>
