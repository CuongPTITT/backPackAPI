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
                @if(\Illuminate\Support\Facades\Auth::check())
                    <button type="button" class="btn btn-primary" onclick="logout()">Logout</button>
                @endif
                    <button type="button" class="btn btn-primary" onclick="login()">Login</button>
        </div>
        <table id='listpost'>
            <tr>
                <th style="padding-right: 150px;">ID</th>
                <th style="padding-right: 150px;">Title</th>
                <th style="padding-right: 150px;">Description</th>
                <th style="padding-right: 150px;">Image</th>
                <th style="padding-right: 150px;">Status</th>
                <th style="padding-right: 150px;">Action</th>
            </tr>

            @foreach($posts as $post)
            <tr>
                <td style="padding-right: 150px;">{{$post->id}}</td>
                <td style="padding-right: 150px;">{{$post->title}}</td>
                <td style="padding-right: 150px;">{{$post->description}}</td>
                <td style="padding-right: 150px;">
                    <img src="{{ asset($post->image) }}" height="100" width="100">
                <td style="padding-right: 150px;">
                    @if($post->status == 1)
                        Enable
                    @else
                        Disable
                    @endif
                </td>
                <td style="padding-right: 150px;">
                    <a href="{{ route('post.detail', ['id' => $post->id]) }}">show</a> |
                    <a href="{{ route('post.form_edit', ['id' => $post->id]) }}">edit</a>
                </td>
            </tr>
            @endforeach
        </table>
        <div class="row justify-content-center">
            <div class="col-md-3 offset-md-1 mt-3">
                <ul class="pagination ">
                    {{ $posts->links("pagination::bootstrap-4") }}
                </ul>
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
                        window.location.replace('/login');
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

        function login() {
            window.location.replace('/login');
        }
    </script>
</body>

</html>
