<?php

namespace MezzoLabs\Mezzo\Console\Commands;

use Illuminate\Console\Command;
use MezzoLabs\Mezzo\Core\Mezzo;

abstract class MezzoCommand extends Command
{

    /**
     * @var Mezzo
     */
    protected $mezzo;

    public function abstractName()
    {
        $signature = $this->signature;

        $signature = str_replace('mezzo:', 'mezzo.commands.', $signature);
        $signature = str_replace(':', '.', $signature);

        return $signature;
    }

    /**
     * @return Mezzo
     */
    public function getMezzo()
    {
        return $this->mezzo;
    }

    /**
     * @param Mezzo $mezzo
     */
    public function setMezzo(Mezzo $mezzo)
    {
        $this->mezzo = $mezzo;
    }


} 