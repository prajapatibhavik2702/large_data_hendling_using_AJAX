<!DOCTYPE html>
<html>
<head>
    <title>Posted Notifications</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Add the Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body >
    {{-- <div id="myDiv" > --}}

    {{-- </div> --}}
    <div class="container mt-5">
        <h2>View Notifications</h2>

        @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered mt-3" id="notificationsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    {{-- <th>PhoneNumber</th>     --}}
                    <th>Is Read ???</th>
                </tr>
            </thead>
            <tbody>
                <img id="loading-image" src="https://assets.materialup.com/uploads/163595e3-140e-4334-af76-cf7902795c51/preview.gif" style="display:none; margin-left:15%;" />

                <!-- Content will be fetched using AJAX -->
            </tbody>
        </table>
    </div>

    <!-- Add the Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add the DataTables JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

     <!-- Add the DataTables JavaScript -->
     <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>


    <script>
        $(document).ready(function() {
            // Fetch data using AJAX when the page loads
            $("#loading-image").show();
            fetchNotificationsData();

        });

        function fetchNotificationsData() {
            const userId = "{{ $id }}";
            $.ajax({
                url: `/usernotificationdetails/${userId}`, // Replace with your Laravel route URL to fetch data
                method: 'GET',
                dataType: 'json',
                beforeSend: function() {
                    $("#loading-image").show();
                },
                success: function(response) {
                    // Process the received data and update the content

                    if (response.post_notification_data.length > 0) {
                        let html = '';
                        $.each(response.post_notification_data, function(index, notification) {
                            // Create rows for the table dynamically

                            html += `
                                <tr>
                                    <td><strong>${notification.user_id}</strong></td>
                                    <td>${notification.user.name}</td>
                                    <td>${notification.user.email}</td>
                                    <td>
                                        <b><span style="color: ${notification.isRead === 1 ? 'green' : 'red'}">${notification.isRead === 1 ? 'Yes' : 'No'}</span></b>
                                    </td>
                                </tr>
                            `;
                        });

                        // Update the table body with the dynamically generated rows
                        $("#notificationsTable tbody").html(html);

                        $('#notificationsTable').DataTable();
                        $("#loading-image").hide();

                    } else {
                        // Handle the case when no notifications are available
                        $("#notificationsTable tbody").html('<tr><td colspan="5">No notifications found.</td></tr>');
                        $("#loading-image").hide();
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error case if necessary
                    console.error(error);
                }
            });
        }
    </script>
</body>
</html>
