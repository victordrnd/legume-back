<?php

namespace App\Policies;

use App\Booking;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Status;
use Carbon\Carbon;
use Illuminate\Auth\Access\Response;
class BookingPolicy
{
    use HandlesAuthorization;


    public function before($user, $ability)
    {
        if ($user->role->slug == 'administrator') {
            return true;
        } else {
            return null;
        }
    }
    /**
     * Determine whether the user can view any bookings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the booking.
     *
     * @param  \App\User  $user
     * @param  \App\Booking  $booking
     * @return mixed
     */
    public function view(User $user, Booking $booking)
    {
        return $booking->user_id == $user->id;
    }

    /**
     * Determine whether the user can create bookings.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $count = Booking::where('schedule', ">=", Carbon::now())
            ->where('user_id', $user->id)
            ->where('status_id', '!=', Status::where('slug', 'canceled')->first()->id)
            ->count();
        if ($count >= 5) {
            return Response::deny("Vous avez dépassé la limite de 5 réservations à venir.");
        }else{
            return true;
        }
    }

    /**
     * Determine whether the user can update the booking.
     *
     * @param  \App\User  $user
     * @param  \App\Booking  $booking
     * @return mixed
     */
    public function update(User $user, Booking $booking)
    {
        return $booking->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the booking.
     *
     * @param  \App\User  $user
     * @param  \App\Booking  $booking
     * @return mixed
     */
    public function delete(User $user, Booking $booking)
    {
        return $booking->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the booking.
     *
     * @param  \App\User  $user
     * @param  \App\Booking  $booking
     * @return mixed
     */
    public function restore(User $user, Booking $booking)
    {
        return $booking->user_id == $user->id;
    }

    /**
     * Determine whether the user can permanently delete the booking.
     *
     * @param  \App\User  $user
     * @param  \App\Booking  $booking
     * @return mixed
     */
    public function forceDelete(User $user, Booking $booking)
    {
        return $booking->user_id == $user->id;
    }
}
