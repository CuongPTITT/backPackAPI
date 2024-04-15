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

            <tr>
                <td style="padding-right: 150px;" id="id"></td>
                <td style="padding-right: 150px;" id="title"></td>
                <td style="padding-right: 150px;" id="description"></td>
                <td style="padding-right: 150px;">
                    <img id="image" src="" height="100" width="100">
                <td style="padding-right: 150px;" id="status">
                </td>
                <td style="padding-right: 150px;">
                    <a href="">show</a> |
                    <a href="">edit</a>
                </td>
            </tr>
        </table>
        <div class="row justify-content-center">
            <div class="col-md-3 offset-md-1 mt-3">
                <ul class="pagination" id="pagination">
                </ul>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            me();
            getListPost();
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

        function getListPost(page = 1) {
            $.ajax({
                type: 'GET',
                url: '/api/home?page=' + page,
                headers: {
                    'Authorization': 'Bearer ' + user_token
                },
                success: function(response) {
                    console.log(response);
                    if (response.status === 200) {
                        var data = response.data;
                        $('#listpost').empty();
                        $.each(data, function(index, post) {
                            var row = '<tr>' +
                                '<td style="padding-right: 150px;">' + post.id + '</td>' +
                                '<td style="padding-right: 150px;">' + post.title + '</td>' +
                                '<td style="padding-right: 150px;">' + post.description + '</td>' +
                                '<td style="padding-right: 150px;"><img src="' + post.image + '" height="100" width="100"></td>' +
                                '<td style="padding-right: 150px;">';

                            if (post.status === 1) {
                                row += '<span>' + 'Disable' + '</span>';
                            } else {
                                row +='<span>' + 'Enable' + '</span>';
                            }

                            row += '</td>' +
                                '<td style="padding-right: 150px;">' +
                                '<a href="/posts/' + post.id + '">show</a> | ' +
                                '<a href="/posts/' + post.id + '/edit">edit</a>' +
                                '</td>' +
                                '</tr>';

                            $('#listpost').append(row);
                        });

                        var pagination = response.links;
                        $('#pagination').empty();

                        if (pagination.prev_page_url) {
                            var prevPage = '<li class="page-item"><a class="page-link" href="#" onclick="getListPost(' + (pagination.current_page - 1) + '); return false;">Previous</a></li>';
                            $('#pagination').append(prevPage);
                        }

                        for (var i = 1; i <= pagination.last_page; i++) {
                            var page = '<li class="page-item' + (i === pagination.current_page ? ' active' : '') + '"><a class="page-link" href="#" onclick="getListPost(' + i + '); return false;">' + i + '</a></li>';
                            $('#pagination').append(page);
                        }

                        if (pagination.next_page_url) {
                            var nextPage = '<li class="page-item"><a class="page-link" href="#" onclick="getListPost(' + (pagination.current_page + 1) + '); return false;">Next</a></li>';
                            $('#pagination').append(nextPage);
                        }
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

        function login() {
            window.location.replace('/login');
        }
    </script>
</body>

</html>
