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
                    <div id="login-box" class="col-md-14">
                        <form name="frm_register" class="form" id="frm_register">
                            @csrf
                            <h3 class="text-center text-primary">Register</h3>
                            <div class="mb-3">
                                <label for="name" class="text-primary">Name:</label>
                                <input type="text" class="form-control" size="10px" id="name" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="text-primary">Email:</label>
                                <input type="text" class="form-control" size="10px" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="text-primary">Password:</label>
                                <input type="password" class="form-control" size="10px" id="password" name="password" required>
                            </div>
                            <div class="mb-3 text-center">
                                <button type="button" class="btn btn-primary" onclick="register()">Register</button>
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
        function register() {
            var data = $("#frm_register").serialize();

            $.ajax({
                type: 'POST',
                url: '/api/register',
                data: data,
                success: function(response) {
                    console.log(response);
                    if (response.status == 200) {
                        user_token = response.authorisation.token
                        window.localStorage.setItem('token', user_token);
                        window.location.replace('/home');
                    } else {
                        $("#err").hide().html("Something went wrong, please try again").fadeIn('slow');
                    }
                }
            });
        }
    </script>
</body>

</html>
