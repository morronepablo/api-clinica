<?php

namespace App\Console\Commands;

use App\Mail\NotificationAppoint;
use App\Models\Appointment\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class NotificationAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notification-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificar al cliente 1 hora antes de su cita medica';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        $simulet_hour_number = strtotime(date("2024-09-08 09:40:35"));
        //$simulet_hour_number = date("2024-09-11 11:00:35"); //strtotime(date("2023-10-24 09:35:35"));
        $appointments = Appointment::whereDate("date_appointment", "2024-09-18") //now()->format("Y-m-d")
            ->where("status", 1)
            ->get();
        $now_time_number = strtotime($simulet_hour_number); //now()->format("Y-m-d h:i:s")
        $patients = collect([]);
        foreach ($appointments as $key => $appointment) {
            $hour_start = $appointment->doctor_schedule_join_hour->doctor_schedule_hour->hour_start;
            $hour_end = $appointment->doctor_schedule_join_hour->doctor_schedule_hour->hour_end;

            // 2023-10-25 08:30:00 -> 2023-10-25 07:30:00
            $hour_start = strtotime(Carbon::parse(date("Y-m-d") . " " . $hour_start)->subHour());
            $hour_end = strtotime(Carbon::parse(date("Y-m-d") . " " . $hour_end)->subHour());
            error_log($hour_start . ' ---- ' . $hour_end . ' ---- ' . $simulet_hour_number);
            if ($hour_start <= $now_time_number && $hour_end >= $now_time_number) {
                $patients->push([
                    "name" => $appointment->patient->name,
                    "surname" => $appointment->patient->surname,
                    "avatar" => $appointment->avatar ? env("APP_URL") . "storage/" . $appointment->avatar : NULL,
                    "email" => $appointment->patient->email,
                    "mobile" => $appointment->patient->mobile,
                    "specialitie_name" => $appointment->specialitie->name,
                    "n_document" => $appointment->patient->n_document,
                    "hour_start_format" => Carbon::parse(date("Y-m-d") . " " . $appointment->doctor_schedule_join_hour->doctor_schedule_hour->hour_start)->format("h:i A"),
                    "hour_end_format" => Carbon::parse(date("Y-m-d") . " " . $appointment->doctor_schedule_join_hour->doctor_schedule_hour->hour_end)->format("h:i A"),
                ]);
            }
        }
        dd($patients->count());
        foreach ($patients as $key => $patient) {
            Mail::to($patient["email"])->send(new NotificationAppoint($patient));
        }
    }
}
