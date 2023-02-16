<?php
class oh_hry{
    private $id;
    private $type;
    private $year;
    private $order;
    private $city;
    private $country;
    private $person_id;
    private $oh_id;
    private $placing;
    private $discipline;
    private $name;
    private $surname;
    private $birth_day;
    private $birth_place;
    private $birth_country;
    private $death_day;
    private $death_place;
    private $death_country;
    private $pocet_medaili;
    /**
     * @return mixed
     */
    public function getId(){
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * @return mixed
     */
    public function getOhId()
    {
        return $this->oh_id;
    }

    /**
     * @return mixed
     */
    public function getPlacing()
    {
        return $this->placing;
    }

    /**
     * @return mixed
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @return mixed
     */
    public function getBirthDay()
    {
        return $this->birth_day;
    }

    /**
     * @return mixed
     */
    public function getBirthPlace()
    {
        return $this->birth_place;
    }

    /**
     * @return mixed
     */
    public function getBirthCountry()
    {
        return $this->birth_country;
    }

    /**
     * @return mixed
     */
    public function getDeathDay()
    {
        return $this->death_day;
    }

    /**
     * @return mixed
     */
    public function getDeathPlace()
    {
        return $this->death_place;
    }

    /**
     * @return mixed
     */
    public function getDeathCountry()
    {
        return $this->death_country;
    }

    /**
     * @return mixed
     */
    public function getPocetMedaili()
    {
        return $this->pocet_medaili;
    }


}

