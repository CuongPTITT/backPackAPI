<html>

<head>
    <title>Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
</head>

<body>
<div class="card-body">
    <div class="mb-3 text-end">
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <p id='username'> You are Logged In</p>

        <button type="button" class="btn btn-primary" onclick="logout()">Logout</button>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Edit Post') }}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $post->title }}">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description">{{ $post->description }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="image">Image</label>
                                <input type="file" class="form-control-file" id="image" name="image">
                                @if ($post->image)
                                    <img src="{{ asset($post->image) }}" height="100" width="100" id="image-preview">
                                @else
                                    <img src="" height="100" width="100" id="image-preview" style="display: none;">
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ $post->status == 1 ? 'selected' : '' }}>Enable</option>
                                    <option value="0" {{ $post->status == 0 ? 'selected' : '' }}>Disable</option>
                                </select>
                            </div>
                    <a id="submit" class="btn btn-primary">Update</a>
                    </div>
                    <a href="/home" class="btn btn-primary">back</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        me();
    });

    user_token = window.localStorage.getItem('token');

    function me() {
        $.ajax({
            type: 'GET',
            url: '/api/me',
            headers: {
                'Authorization': 'Bearer ' + user_token
            },
            success: function(response) {
                console.log(response);
                if (response.user == null) {
                    window.location.replace('/');
                } else {
                    $("#username").text("Welcome " + response.user.name);
                }
            }
        });

    }

    function logout() {
        $.ajax({
            type: 'POST',
            url: '/api/auth/logout',
            headers: {
                'Authorization': 'Bearer ' + user_token
            },
            success: function(response) {
                window.location.replace('/login');
            }
        });
    }

    $(document).ready(function() {
        $('#image').change(function() {
            var input = $(this);
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input[0].files[0]);
        });
    });

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#submit').click(function(e) {
            e.preventDefault();

            var post_id = {{$post->id}};
            var title = $('#title').val();
            var description = $('#description').val();
            var image = $('#image')[0].files[0];
            var status = $('#status').val();

            var formData = new FormData();
            formData.append('post_id', post_id);
            formData.append('title', title);
            formData.append('description', description);
            formData.append('image', image);
            formData.append('status', status);

            var url = '{{ route('post.update_form') }}';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    window.location.replace('/home');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
</body>

</html>
