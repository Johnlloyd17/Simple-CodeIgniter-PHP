<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CABAN</title>
  <!-- Favicon -->
  <link rel="shortcut icon" href="./img/svg/logo.svg" type="image/x-icon">
  <!-- Custom styles -->
  <link rel="stylesheet" href="./css/style.min.css">

  <!-- Include jQuery library -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

  <link rel="stylesheet" href="./css/my_style.css">
  <style>
    /* CSS */
    .del-button {
      background-color: red;
      /* Blue color, adjust as needed */
      color: white;
      /* Text color */
      border: none;
      /* Remove default button border */
      padding: 8px 16px;
      /* Add padding */
      cursor: pointer;
      /* Show pointer on hover */
      /* Add some spacing between input and button */
      border-radius: 20px;
      /* Make the button round */
    }

    .del-button:hover {
      background-color: #AA0000;
      /* Darker blue color on hover */
    }
  </style>
  <script>
    $(document).ready(function() {
      var modal = $(".modal");
      var overlay = $(".overlay");
      var openModalBtn = $(".btn-open");
      var closeModalBtn = $(".btn-close");

      // Function to close modal
      function closeModal() {
        modal.addClass("hidden");
        overlay.addClass("hidden");
        modal.removeClass("shake"); // Remove the shake class in case it was added
      }

      // Function to shake modal
      function shakeModal() {
        modal.addClass("shake"); // Add shake animation
        // Remove the shake class after the animation duration
        setTimeout(function() {
          modal.removeClass("shake");
        }, 820); // Duration of the shake animation
      }

      // Event handler to close the modal when the close button is clicked
      closeModalBtn.click(closeModal);

      // Function to open modal
      function openModal() {
        modal.removeClass("hidden");
        overlay.removeClass("hidden");
      }

      // Event handler to open the modal when the open button is clicked
      openModalBtn.click(openModal);

      // Prevent modal close on overlay click, shake instead
      overlay.click(shakeModal);

      // Prevent modal close on Escape key, shake instead
      $(document).keydown(function(e) {
        if (e.key === "Escape" && !modal.hasClass("hidden")) {
          e.preventDefault(); // Prevent the default action
          shakeModal();
        }
      });
    });

    $(function() {
      $("#birthdate").datepicker({
        dateFormat: "dd/mm/yy", // Format the date shown in the input
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+0" // Example range: from 100 years ago to the current year
      });
    });
    $(document).ready(function() {
      var modalTitle = $('#title-modal');
      var submitButton = $('.btn-add-user');
      var form = $('#userForm');

      $('.add-button').on('click', function(e) {
        e.preventDefault();
        modalTitle.text('ADD USER');
        submitButton.text('ADD USER');
        form.attr('action', '<?= site_url('users/add'); ?>');
        form[0].reset();
        $('#addEditModal').removeClass('hidden');
        $('.overlay').removeClass('hidden');
      });

      $(document).on('click', '.edit-button', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
        $.ajax({
          url: '<?= site_url('users/getUser/') ?>' + userId,
          method: 'GET',
          dataType: 'json',
          success: function(data) {
            $('#fname').val(data.first_name);
            $('#mname').val(data.middle_name);
            $('#lname').val(data.last_name);
            $('#age').val(data.age);
            $('#gender').val(data.gender);
            var dateParts = data.birthdate.split('-').reverse();
            $('#birthdate').val(dateParts.join('/'));

            modalTitle.text('EDIT USER');
            submitButton.text('SAVE');
            form.attr('action', '<?= site_url('users/update'); ?>');
            form.append('<input type="hidden" name="id" value="' + userId + '">'); // Include user ID in the form data
            $('#addEditModal').removeClass('hidden');
            $('.overlay').removeClass('hidden');
          }
        });
      });

      form.on('submit', function(e) {
        e.preventDefault();
        var actionUrl = $(this).attr('action'); // Ensure this fetches the correct URL

        console.log('Form submitting to:', actionUrl); // Check the URL in the console
        $.ajax({
          url: actionUrl, // This should be '/users/update' when updating
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            alert('User saved successfully');
            location.reload(); // Or redirect as needed
          },
          error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText, 'Status:', status, 'Error:', error);
            alert('Failed to save user: ' + (xhr.responseText || 'Unknown error'));
          }
        });
      });
    });


    $(document).ready(function() {
      // Event listener for delete button
      $(document).on('click', '.delete-button', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
        var confirmation = confirm('Are you sure you want to delete this user?');
        if (confirmation) {
          deleteUser(userId);
        }
      });

      // Event listener for delete all button
      $('.del-button').on('click', function() {
        var confirmation = confirm('Are you sure you want to delete all selected users?');
        if (confirmation) {
          var selectedUserIds = [];
          $('.check:checked').each(function() {
            selectedUserIds.push($(this).closest('tr').find('.delete-button').data('user-id'));
          });
          if (selectedUserIds.length > 0) {
            deleteUsers(selectedUserIds);
          } else {
            alert('Please select at least one user to delete.');
          }
        }
      });

      // Function to delete a single user
      function deleteUser(userId) {
        $.ajax({
          url: '<?= site_url('users/delete/') ?>' + userId,
          method: 'POST',
          dataType: 'json',
          success: function(response) {
            // Reload the page or update the table as needed
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText, 'Status:', status, 'Error:', error);
            alert('Failed to delete user: ' + (xhr.responseText || 'Unknown error'));
          }
        });
      }

      // Function to delete multiple users
      function deleteUsers(userIds) {
        $.ajax({
          url: '<?= site_url('users/deleteAll'); ?>',
          method: 'POST',
          dataType: 'json',
          data: {
            ids: userIds
          },
          success: function(response) {
            if (response.success) {
              // Reload the page or update the table as needed
              location.reload();
            } else {
              alert('Failed to delete users: ' + (response.error || 'Unknown error'));
            }
          },
          error: function(xhr, status, error) {
            console.error('AJAX Error:', xhr.responseText, 'Status:', status, 'Error:', error);
            alert('Failed to delete users: ' + (xhr.responseText || 'Unknown error'));
          }
        });
      }
    });
  </script>


</head>

<body>
  <div class="layer"></div>
  <!-- ! Body -->
  <a class="skip-link sr-only" href="#skip-target">Skip to content</a>
  <div class="page-flex">


    <!-- ! Sidebar -->
    <aside class="sidebar">
      <div class="sidebar-start">
        <div class="sidebar-head">
          <a href="<?php echo base_url('dashboard'); ?>" class="logo-wrapper" title="Home">
            <span class="sr-only">Home</span>
            <span class="icon logo" aria-hidden="true"></span>
            <div class="logo-text">
              <span class="logo-title">Elegant</span>
              <span class="logo-subtitle">Dashboard</span>
            </div>

          </a>
          <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
            <span class="sr-only">Toggle menu</span>
            <span class="icon menu-toggle" aria-hidden="true"></span>
          </button>
        </div>
        <div class="sidebar-body">
          <ul class="sidebar-body-menu">
            <li>
              <a class="active" href="<?php echo base_url('dashboard'); ?>"><span class="icon home" aria-hidden="true"></span>Dashboard</a>
            </li>

            <li>
              <a class="show-cat-btn" href="##">
                <span class="icon user-3" aria-hidden="true"></span>Users
                <span class="category__btn transparent-btn" title="Open list">
                  <span class="sr-only">Open list</span>
                  <span class="icon arrow-down" aria-hidden="true"></span>
                </span>
              </a>
              <ul class="cat-sub-menu">
                <li>
                  <a href="<?php echo base_url('users'); ?>">Customer</a>
                </li>
                <!-- <li>
                  <a href="users-02.html">Customer</a>
                </li> -->
              </ul>
            </li>

          </ul>
        </div>
      </div>
    </aside>






    <div class="main-wrapper">
      <!-- ! Main nav -->
      <nav class="main-nav--bg">
        <div class="container main-nav">
          <div class="main-nav-start">

          </div>
          <div class="main-nav-end">
            <button class="sidebar-toggle transparent-btn" title="Menu" type="button">
              <span class="sr-only">Toggle menu</span>
              <span class="icon menu-toggle--gray" aria-hidden="true"></span>
            </button>

            <button class="theme-switcher gray-circle-btn" type="button" title="Switch theme">
              <span class="sr-only">Switch theme</span>
              <i class="sun-icon" data-feather="sun" aria-hidden="true"></i>
              <i class="moon-icon" data-feather="moon" aria-hidden="true"></i>
            </button>
            <div class="notification-wrapper">

              <ul class="users-item-dropdown notification-dropdown dropdown">
                <li>
                  <a href="##">
                    <div class="notification-dropdown-icon info">
                      <i data-feather="check"></i>
                    </div>
                    <div class="notification-dropdown-text">
                      <span class="notification-dropdown__title">System just updated</span>
                      <span class="notification-dropdown__subtitle">The system has been successfully upgraded. Read more
                        here.</span>
                    </div>
                  </a>
                </li>
                <li>
                  <a href="##">
                    <div class="notification-dropdown-icon danger">
                      <i data-feather="info" aria-hidden="true"></i>
                    </div>
                    <div class="notification-dropdown-text">
                      <span class="notification-dropdown__title">The cache is full!</span>
                      <span class="notification-dropdown__subtitle">Unnecessary caches take up a lot of memory space and
                        interfere ...</span>
                    </div>
                  </a>
                </li>
                <li>
                  <a href="##">
                    <div class="notification-dropdown-icon info">
                      <i data-feather="check" aria-hidden="true"></i>
                    </div>
                    <div class="notification-dropdown-text">
                      <span class="notification-dropdown__title">New Subscriber here!</span>
                      <span class="notification-dropdown__subtitle">A new subscriber has subscribed.</span>
                    </div>
                  </a>
                </li>
                <li>
                  <a class="link-to-page" href="##">Go to Notifications page</a>
                </li>
              </ul>
            </div>
            <div class="nav-user-wrapper">
              <button href="##" class="nav-user-btn dropdown-btn" title="My profile" type="button">
                <span class="sr-only">My profile</span>
                <span class="nav-user-img">
                  <picture>
                    <source srcset="./img/avatar/avatar-illustrated-02.webp" type="image/webp"><img src="./img/avatar/avatar-illustrated-02.png" alt="User name">
                  </picture>
                </span>
              </button>
              <ul class="users-item-dropdown nav-user-dropdown dropdown">
                <!-- <li><a href="##">
                    <i data-feather="user" aria-hidden="true"></i>
                    <span>Profile</span>
                  </a></li> -->
                <!-- <li><a href="##">
                    <i data-feather="settings" aria-hidden="true"></i>
                    <span>Account settings</span>
                  </a></li> -->
                <li><a class="danger" href="<?php echo base_url('admin'); ?>">
                    <i data-feather="log-out" aria-hidden="true"></i>
                    <span>Log out</span>
                  </a></li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
      <!-- ! Main -->
      <main class="main users chart-page" id="skip-target">
        <div class="container">

          <div class="main-nav">
            <div class="main-nav-start">
              <h2 class="main-title">Users</h2>
            </div>

            <div class="main-nav-end">
              <div class="search-wrapper">
                <i data-feather="search" aria-hidden="true"></i>
                <input type="text" placeholder="Enter keywords ..." style="color: black;" required class="custom-input">
              </div>
              <button type="button" class="add-button btn-open">ADD</button>
              <button type="button" class="del-button">DETELE ALL</button>

            </div>
          </div>
<!-- MODAL -->
          <section class="modal hidden" id="addEditModal">
            <div class="modal-header">
              <h3 id="title-modal">ADD USER</h3>

              <button class="btn-close">⨉</button>
            </div>

            <div class="modal-body">
              <form id="userForm" method="post">

                <div class="form-group">
                  <label for="name" class="form-label">First Name</label>
                  <input type="text" class="form-control" id="fname" name="firstName" placeholder="Enter the first name." required>
                </div>
                <div class="form-group">
                  <label for="name" class="form-label">Middle Name</label>
                  <input type="text" class="form-control" id="mname" name="middleName" placeholder="Enter the middle name." required>
                </div>
                <div class="form-group">
                  <label for="name" class="form-label">Last Name</label>
                  <input type="text" class="form-control" id="lname" name="lastName" placeholder="Enter the last name." required>
                </div>
                <div class="form-group">
                  <label for="age" class="form-label">Age</label>
                  <input type="number" class="form-control" id="age" name="age" placeholder="Enter the age." required>
                </div>
                <div class="form-group">
                  <label for="gender" class="form-label">Gender</label>
                  <select class="form-select" id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <input type="text" class="form-control" id="birthdate" name="birthdate" placeholder="dd/mm/yyyy" required>
                </div>


            </div>


            <div class="modal-footer">
              <button type="submit" class="btn btn-add-user btn-edit-user">ADD USER</button>

            </div>
            </form>
          </section>

          <div class="overlay hidden"></div>
<!-- MODAL -->


          <div class="row">
            <div class="col-lg-12">

              <div class="users-table table-wrapper">
                <table class="posts-table">
                  <thead>
                    <tr class="users-table-info">
                      <th>
                        <label class="users-table__checkbox ms-20">
                          <input type="checkbox" class="check-all">ID
                        </label>
                      </th>
                      <th>Name</th>
                      <th>Age</th>
                      <th>Gender</th>
                      <th>Birthdate</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <style>
                    .id-checkbox-cell {
                      white-space: nowrap;
                      /* Prevent line break */
                    }

                    .id-checkbox-cell input[type="checkbox"],
                    .id-checkbox-cell .id {
                      display: inline-block;
                      /* Display elements inline */
                      vertical-align: middle;
                      /* Align elements vertically in the middle */
                      margin-right: 15px;
                      /* Add some space between the checkbox and ID */
                    }
                  </style>
                  <tbody>
                    <?php
                    // Initialize a counter variable
                    $count = 1;
                    if (!empty($users)) {
                      foreach ($users as $row) { ?>
                        <tr>
                          <td class="id-checkbox-cell">
                            <input type="checkbox" class="check" value="<?= $row['id'] ?>"> <span class="id"><?= $count ?></span>
                          </td>
                          <td><?= esc($row['first_name']) . ' ' . esc($row['middle_name']) . '. ' . esc($row['last_name']) ?></td>
                          <td><?= esc($row['age']) ?></td>
                          <td><?= esc($row['gender']) ?></td>
                          <td><?= date("m.d.Y", strtotime($row['birthdate'])) ?></td>
                          <td>
                            <span class="p-relative">
                              <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                <i data-feather="more-horizontal" aria-hidden="true"></i>
                              </button>
                              <ul class="users-item-dropdown dropdown">
                                <a class="edit-button" href="#" data-user-id="<?= $row['id']; ?>">Edit</a>
                                <li><a class="delete-button" href="#" data-user-id="<?= $row['id']; ?>">Trash</a></li>
                              </ul>
                            </span>
                          </td>
                        </tr>
                    <?php
                        // Increment the counter after each iteration
                        $count++;
                      }
                    }
                    ?>

                  </tbody>

                </table>
              </div>

            </div>








          </div>
      </main>
      <!-- ! Footer -->
      <footer class="footer">
        <div class="container footer--flex">
          <div class="footer-start">
            <p>2024 © Caban Dashboard - <a href="caban-dashboard.com" target="_blank" rel="noopener noreferrer">caban-dashboard.com</a></p>
          </div>
          <ul class="footer-end">
            <li><a href="##">About</a></li>
            <li><a href="##">Support</a></li>
            <li><a href="##">Puchase</a></li>
          </ul>
        </div>
      </footer>
    </div>
  </div>
  <!-- Chart library -->
  <script src="./plugins/chart.min.js"></script>
  <!-- Icons library -->
  <script src="plugins/feather.min.js"></script>
  <!-- Custom scripts -->
  <script src="js/script.js"></script>
</body>

</html>