<!DOCTYPE html>
<html>
<head>
    <title>Posted Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add the Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add the DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">

    <style>
        .yes-read {
            color: green;
        }
        .no-read {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>View Notifications</h2>
        <a class="btn btn-primary btn-sm" href="{{ route('posts.show') }}"><<--Back</a>
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered mt-3" id="notificationsTable" data-id="{{ $id }}">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Read</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Add the Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add the DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = $('#notificationsTable').data('id');

            $('#notificationsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("user.notification.detail") }}',
                    type: 'GET',
                    data: {
                        id: id
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    {

                    data: 'read',
                    name: 'read',
                    render: function (data, type, row) {
                        if (data === 'No') {
                            // console.log(row.id);

                            return '<div id="loading-div"></div> <button class="btn btn-primary seen-btn" data-user-id="' + row.id + '">Seen</button>';
                        } else {
                            return 'Yes Read';
                        }
                    }
                }

                    // {
                    //     data: 'read',
                    //     name: 'read',
                    //     render: function (data, type, row) {
                    //         var color = data === 'Yes Read Post' ? 'green' : 'red';
                    //         return '<span style="color: ' + color + '">' + data + '</span>';
                    //     }
                    // },

                    // { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                // createdRow: function (row, data, index) {
                //     // Add a class to the 'td' element in the 'read' column based on the value
                //     $(row).find('td:eq(4)').addClass(data.read ? 'yes-read' : 'no-read');
                // },

                pagingType: 'full_numbers', // Display full pagination
                pageLength: 10, // Number of records to show per page
                lengthMenu: [10, 25, 50, 100], // Option to choose number of records per page
            });
        });

        $('#notificationsTable').on('click', '.seen-btn', function() {
            $('#loading-div').html('<img src="{{ asset('img/Fidget-spinner.gif') }}" />');

    var button = $(this); // Store a reference to the clicked button
    console.log(button);
    var userId = button.data('user-id');
    var id = "{{ $id }}";
    $.ajax({
        url: '{{ route("update.notification.status") }}',
        method: 'POST',

        data: {
            _token: "{{ csrf_token() }}",
            userId: userId,
            notificationId: id
        },
        success: function(response) {
            if (response.success) {
                // Update the DataTables row and reload the table
                // var row = table.row(button.closest('tr'));
                // row.data().read = 'Yes Read';
                // table.draw();
                $('#loading-div').html('');
                var newTextElement = $("<span>").text("Yes read"); // Create the new text element
            $(button).replaceWith(newTextElement);
            }
        }
    });
});

    </script>
</body>
</html>


































































{{-- <!DOCTYPE html>
<html>
<head>
    <title>Posted Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add the Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add the DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>View Notifications</h2>
        <a class="btn btn-primary btn-sm" href="{{ route('posts.show') }}"><<--Back</a>
        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered mt-3" id="notificationsTable" data-id="{{ $id }}">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- Add the Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add the DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            var id = $('#notificationsTable').data('id');

            $('#notificationsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("user.notification.detail") }}', // Replace with your route for fetching data
                    type: 'GET',
                    data: {
                        id: id
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });
        });
    </script>
</body>
</html>



 --}}
