
Warning: Module 'zip' already loaded in Unknown on line 0

Warning: Module 'mongodb' already loaded in Unknown on line 0
+--------+----------+------------------+------+------------------------------------------------------+----------------+
| Domain | Method   | URI              | Name | Action                                               | Middleware     |
+--------+----------+------------------+------+------------------------------------------------------+----------------+
|        | GET|HEAD | api/auth/current |      | App\Http\Controllers\AuthController@getCurrentUser   | api,jwt.verify |
|        | POST     | api/auth/login   |      | App\Http\Controllers\AuthController@login            | api            |
|        | POST     | api/auth/signup  |      | App\Http\Controllers\AuthController@signup           | api            |
|        | POST     | api/booking/book |      | App\Http\Controllers\BookingController@createBooking | api,jwt.verify |
|        | GET|HEAD | api/booking/my   |      | App\Http\Controllers\BookingController@getMyBookings | api,jwt.verify |
|        | GET|HEAD | api/schedule     |      | App\Http\Controllers\ScheduleController@getAll       | api,jwt.verify |
+--------+----------+------------------+------+------------------------------------------------------+----------------+
