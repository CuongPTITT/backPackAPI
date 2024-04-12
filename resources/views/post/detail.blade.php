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

                        <table>

                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Image</th>
                                <th>Status</th>
                            </tr>

                            <tr>
                                <td style="padding-right: 150px;">{{$post->id}}</td>
                                <td style="padding-right: 150px;">{{$post->title}}</td>
                                <td style="padding-right: 150px;">{{$post->description}}</td>
                                <td style="padding-right: 150px;">
                                    <img src="{{ asset($post->image) }}" height="100" width="100">
                                </td>
                                @if($post->status == 1)
                                    <td style="padding-right: 150px;">Enable</td>
                                @else
                                    <td style="padding-right: 150px;">Disable</td>
                                @endif
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
</script>


</body>

</html>
