<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <title>JLC CRUD</title>
  <style>
    html,
    body {
      height: 100%;
    }

    html {
      display: table;
      margin: auto;
    }

    body {
      display: table-cell;
      vertical-align: middle;
    }

    .divider:after,
    .divider:before {
      content: "";
      flex: 1;
      height: 1px;
      background: #eee;
    }

    .login-container {
      max-width: 100%;
      margin: auto;
      padding-left: 20px;
      padding-right: 20px;
      padding-top: 10px;
      border-radius: 15px;
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>

<body>
  <section>
    <div class="login-container">
      <div class="container py-5">
        <div class="row d-flex align-items-center justify-content-center">
          <div class="col-md-8 col-lg-7 col-xl-6">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.svg" class="img-fluid" alt="Phone image">
          </div>
          <div class="col-md-7 col-lg-5 col-xl-5 offset-xl-1">
            <h2 style="display: flex; justify-content: center; font-weight: 700;" class="mb-2">
              LOGIN
            </h2>
           
            <?php
            if (session()->getFlashdata('msgnotification')) {
              echo '
                    <div class="alert alert-danger" role="alert">
                      '.session()->getFlashdata('msgnotification').'
                    </div>';
            }
            ?>
            
            <form action="<?php echo base_url('login/verify'); ?>" method="post">

              <!-- Username input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="form1Example13" class="form-control form-control-lg" name="txtusername" />
                <label class="form-label" for="form1Example13">Username</label>
              </div>

              <!-- Password input -->
              <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="form1Example23" class="form-control form-control-lg" name="txtpassword" />
                <label class="form-label" for="form1Example23">Password</label>
              </div>

              <div class="d-flex justify-content-center align-items-center mt-3 ">
                <!-- Checkbox -->
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                  <label class="form-check-label" for="form1Example3"> Remember me </label>
                </div>
                <div class="mx-auto"></div> <!-- Add space -->
                <a href="#!">Forgot password?</a>
              </div>

              <!-- Submit button -->
              <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-lg w-100 mt-4">Sign in</button>

            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>