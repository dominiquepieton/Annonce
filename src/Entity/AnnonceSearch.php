<?php

namespace App\Entity;

class AnnonceSearch {

    /**
     * @var string|null
     */
    private $title;

    /**
     * @var string|null
     */
    private $user;

    

    /**
     * @return  string|null
     */ 
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param  string|null
     * @return  AnnonceSearch
     */ 
    public function setTitle($title): AnnonceSearch
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return  string|null
     */ 
    public function getUser(): ?string
    {
        return $this->user;
    }

    /**
     * @param  string|null
     * @return AnnonceSearch
     */ 
    public function setUser($user): AnnonceSearch
    {
        $this->user = $user;

        return $this;
    }
}