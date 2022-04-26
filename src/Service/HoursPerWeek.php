<?php 

namespace App\Service;

use App\Entity\User;

Class HoursPerWeek 
{
   /**
     * Calculate total hours Planned per Week
     *
     * @return string
     */
    public function hoursPerWeek(User $user)
    {
        if($user->getPlannedWorkDays()[0] !== null) {
        // on récupère tout les totaux d'heures par jour
        foreach($user->getPlannedWorkDays() as $user) {
            $workHours[] =$user->getHoursplanned();
        }
        
        for($i = 0; $i<count($workHours); $i++) {
            $arraySecond[] = $this->hoursToSeconds($workHours[$i]->format('H:i'));
            $totalHoursWeek = $this->totalHours(array_sum($arraySecond));
        } 

        return $totalHoursWeek;
        } else {
            $totalHoursWeek = '';
        }
        
    }

    /**
     * Transformation des heures en seconde
     *
     * @param [type] $duration
     * @return void
     */
    public function hoursToSeconds($duration){
        $array_duration=explode(":",$duration);
        $seconds=3600*$array_duration[0]+60*$array_duration[1];
        return $seconds;
    }

    /**
     * Transforme les secondes en heures et minutes
     *
     * @param [type] $seconds
     * @return void
     */
    public function totalHours($seconds){
        $s=$seconds % 60; //reste de la division en minutes => secondes
        $m1=($seconds-$s) / 60; //minutes totales
        $m=$m1 % 60;//reste de la division en heures => minutes
        $h=($m1-$m) / 60; //heures
        $resultat=$h."h".$m;
        return $resultat;
    }

    
}