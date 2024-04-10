<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('contains', [$this, 'containsCategory']),
        ];
    }

    public function containsCategory($string, $word)
    {    
        // Cherche la position de la première occurence dans une chaîne
        $position = strpos($string, $word); 
        
        if($position !== false) {
            // Retourne un segment de chaîne 
            return substr($string, $position);
        }
        return ''; // Retourne une chaîne vide si le mot n'est pas trouvé
    }
}