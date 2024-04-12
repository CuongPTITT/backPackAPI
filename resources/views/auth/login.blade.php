<html>

<head>
    <title>Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #525758;
            height: 100vh;
        }

        #login .container #login-row #login-column #login-box {
            margin-top: 90px;
            max-width: 600px;
            height: 320px;
            border: 1px solid #9C9C9C;
            background-color: #EAEAEA;
        }

        #login .container #login-row #login-column #login-box #frm_login {
            padding: 20px;
        }

        .has-error {
            border: 1px solid red;
        }
    </style>
</head>

<body>
    <div id="login">
        <div class="container">
            <div id="login-row" class="row justify-content-center align-items-center">
                <div id="login-column" class="col-md-6">
                    <div id="login-box" class="col-md-12">
                        <form name="frm_login" class="form" id="frm_login">
                            @csrf
                            <h3 class="text-center text-primary">Login</h3>
                            <div class="mb-3">
                                <label for="email" class="text-primary">Email:</label>
                                <input type="text" class="form-control" size="10px" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="text-primary">Password:</label>
                                <input type="password" class="form-control" size="10px" id="password" name="password" required>
                            </div>
                            <div class="mb-3 text-center">
                                <button type="button" class="btn btn-primary" onclick="login()">Sign In</button>
                                <button type="button" class="btn btn-primary" onclick="register()">Sign Up</button>
                            </div>
                            <div id="err" style="color: red"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        function login() {
            const data = new FormData(document.getElementById('frm_login'));

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: '/api/auth/login',
                data: data,
                processData: false,
                contentType: false,
                success: (response) => {
                    console.log(response);
                    if (response.status === 200) {
                        const userToken = response.authorisation.token;
                        window.localStorage.setItem('token', userToken);
                        window.location.replace('/home');
                    } else {
                        $("#err").hide().html("Email or password is incorrect. Please check.").fadeIn('slow');
                    }
                },
                error: (xhr, status, error) => {
                    console.log(xhr.responseText);
                }
            });
        }

        function register()
        {
            window.location.replace('/register');
        }
    </script>
</body>

</html>
