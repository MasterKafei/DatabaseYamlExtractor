<?php

namespace AppBundle\Entity;

/**
 * Author
 */
class Author
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var int
     */
    private $age;

	/**
	 * @var Post
	 */
    private $posts;

    /**
     * @var Mark
     */
    private $mark;

    /**
     * @var Many[]
     */
    private $manies;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Author
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Author
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Author
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

	/**
	 * @return Post
	 */
	public function getPosts()
	{
		return $this->posts;
	}

	/**
	 * @param Post $posts
	 *
	 * @return Author
	 */
	public function setPosts($posts)
	{
		$this->posts = $posts;

		return $this;
	}

    /**
     * Get mark.
     *
     * @return Mark
     */
	public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set mark.
     *
     * @param Mark $mark
     * @return Author
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get manies.
     *
     * @return Many[]
     */
    public function getManies()
    {
        return $this->manies;
    }

    /**
     * Set manies.
     *
     * @param Many[] $manies
     * @return Author
     */
    public function setManies($manies)
    {
        $this->manies = $manies;

        return $this;
    }
}

