<!DOCTYPE html>
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
        <a class="btn btn-primary btn-sm" onClick="add()" href="{{ route('posts.show') }}" style="margin-left: 50%"><<--Back</a>
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
                    <th>PhoneNumber</th>
                    <th>Is Read ???</th>
                </tr>
            </thead>
            <tbody>
                {{-- {{ dd($id) }} --}}
                @forelse($users as $user)

                    <tr>
                        <td><strong>{{ $user->id }}</strong></td>
                        <td>{{ ucfirst($user->name) }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            {{ $user->phone_number ?? 'Null' }}
                        </td>

                        <td>
                            {{-- {{ dd($user->id) }} --}}
                            @php
                                // Fetch the read column value for the user from the post_notification_user table
                                $readValue = DB::table('post_notification_user')
                                    ->where('user_id', $user->id)
                                    ->where('post_notification_id', $id)
                                    ->value('read');

                                // Convert the read column value to 'Yes' or 'No'
                                $isRead = ($readValue === 1) ? 'Yes' : 'No';

                                // Set the color based on the read value
                                $color = ($readValue === 1) ? 'green' : 'red';
                            @endphp
                            {{-- {{ dd($readValue) }} --}}
                            <b><span style="color: {{ $color }}">{{ $isRead }}</span></b>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No notifications found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
             <!-- Add pagination links at the bottom of the table -->
             {{ $users->links() }}
    </div>

    <!-- Add the Bootstrap JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Add the DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script>
       $(document).ready(function() {
            // Initialize DataTable with search and pagination options
            $('#notificationsTable').DataTable({
                searching: true,
                paging: true
            });
        });
    </script>
</body>
</html>




{{-- Loop through the $users data and display the table rows --}}
@forelse($users as $user)
    <tr>
        <td><strong>{{ $user->id }}</strong></td>
        <td>{{ ucfirst($user->name) }}</td>
        <td>{{ $user->email }}</td>
        <td>
            {{ $user->phone_number ?? 'Null' }}
        </td>
        <td>
            {{-- Check if the user has read the notification --}}
            @php
                // Fetch the read column value for the user from the post_notification_user table
                $readValue = DB::table('post_notification_user')
                    ->where('user_id', $user->id)
                    ->where('post_notification_id', $id)
                    ->value('read');

                // Convert the read column value to 'Yes' or 'No'
                $isRead = ($readValue === 1) ? 'Yes' : 'No';

                // Set the color based on the read value
                $color = ($readValue === 1) ? 'green' : 'red';
            @endphp
            {{-- Display whether the notification is read or not --}}
            <b><span style="color: {{ $color }}">{{ $isRead }}</span></b>
        </td>
    </tr>
@empty
    {{-- Display a message when no notifications are found --}}
    <tr>
        <td colspan="5">No notifications found.</td>
    </tr>
@endforelse
