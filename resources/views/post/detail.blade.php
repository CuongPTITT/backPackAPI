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
                    <div class="card-header">{{ __('Detail Post') }}</div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            <table id='detailpost'>

                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Status</th>
                            </tr>

                            <tr>
                                <td style="padding-right: 150px;" id="id"></td>
                                <td style="padding-right: 150px;" id="title"></td>
                                <td style="padding-right: 150px;" id="description"></td>
                                <td style="padding-right: 150px;">
                                    <img id="image" src="" height="100" width="100">
                                <td style="padding-right: 150px;" id="status">
                                </td>
                            </tr>
                        </table>
                        <a href="/home">back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        me();
        detailPost();
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
                if (response.user == null) {
                    window.location.replace('/login');
                } else {
                    $("#username").text("Welcome " + response.user.name);
                }
            }
        });

    }
    function detailPost() {
        var url = window.location.href;
        var id = url.substring(url.lastIndexOf('/') + 1);

        $.ajax({
            type: 'GET',
            url: '/api/post/' + id,
            headers: {
                'Authorization': 'Bearer ' + user_token
            },
            success: function(response) {
                if (response.status === 200) {
                    var post = response.data;
                    $('#id').text(post.id);
                    $('#title').text(post.title);
                    $('#description').text(post.description);
                    $('#image').attr('src', 'http://127.0.0.1:8000/' + post.image);
                    $('#status').text(post.status === 1 ? 'Disable' : 'Enable');
                }
            },
            error: function(xhr, status, error) {
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
</script>


</body>

</html>
