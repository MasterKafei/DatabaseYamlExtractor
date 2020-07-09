<?php


namespace AppBundle\Entity;


class Many
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var Author[]
     */
    private $authors;

    /**
     * Get authors.
     *
     * @return Author[]
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * Set authors.
     *
     * @param Author[] $authors
     * @return Many
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;

        return $this;
    }

    /**
     * Add author.
     *
     * @param Author $author
     * @return Many
     */
    public function addAuthor(Author $author)
    {
        $this->authors[] = $author;

        return $this;
    }
}