<?php


namespace AppBundle\Entity;


class Mark
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var Author
     */
    private $author;

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get value.
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value.
     *
     * @param integer $value
     * @return Mark
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get author.
     *
     * @return Author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set author.
     *
     * @param Author $author
     * @return Mark
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }
}