<?php

namespace App\Data;

class SearchData {

    /**
     *  
     * @var string
     */

    public $q ='';

    /**
     * Retour des catégories sélectionnées
     *
     * @var array
     */
    public $categories = [];

    
    /**
     * Prix max sélectionné
     *
     * @var null/integer
     */
    public $max;

    /**
     * Prix min sélectionné
     *
     * @var null/integer
     */
    public $min;

    /**
     * @var boolean
     */

     public $promo = false;



}