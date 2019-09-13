<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Excception\TransformationFailedException;


class FrToDatetimeTransformer implements DataTransformerInterface{

    // Transformer les données originelles pour un affichage dans le formulaire    
    public function transform($date){
        if($date === null){
            return '';
        }
        // retourner une date en Fr
        return $date->format('d/m/Y');
    }

    // Transformer les données du formulaire pour un affichageau format demandé
    public function reverseTransform($dateFr){
        if($dateFr === null){
            //exception (pas eu de date)
            throw new TransformationFailedException("fournir une date");
        }
        $date = \DateTime::createFromFormat('d/m/Y',$dateFr);

        if($dateFr === false){
            //exception(format incorrecte)
            throw new TransformationFailedException("Le format de la date est incorrecte.");
        }
        return $date;
    }
        
}