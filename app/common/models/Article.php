<?php

namespace Models;

class Article extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=32, nullable=false)
     */
    protected $id;

    /**
     *
     * @var string
     * @Column(column="url", type="string", length=128, nullable=false)
     */
    protected $url;

    /**
     *
     * @var string
     * @Column(column="title", type="string", length=128, nullable=false)
     */
    protected $title;

    /**
     *
     * @var string
     * @Column(column="excerpt", type="string", nullable=true)
     */
    protected $excerpt;

    /**
     *
     * @var string
     * @Column(column="content", type="string", nullable=false)
     */
    protected $content;

    /**
     *
     * @var string
     * @Column(column="content_type", type="string", length=32, nullable=false)
     */
    protected $content_type;

    /**
     *
     * @var string
     * @Column(column="tags", type="string", length=256, nullable=true)
     */
    protected $tags;

    /**
     *
     * @var string
     * @Column(column="state", type="string", length=32, nullable=false)
     */
    protected $state;

    /**
     *
     * @var integer
     * @Column(column="author_id", type="integer", length=32, nullable=false)
     */
    protected $author_id;

    /**
     *
     * @var string
     * @Column(column="created_at", type="string", nullable=false)
     */
    protected $created_at;

    /**
     *
     * @var string
     * @Column(column="updated_at", type="string", nullable=false)
     */
    protected $updated_at;

    /**
     *
     * @var string
     * @Column(column="published_at", type="string", nullable=true)
     */
    protected $published_at;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field url
     *
     * @param string $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Method to set the value of field title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Method to set the value of field excerpt
     *
     * @param string $excerpt
     * @return $this
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Method to set the value of field content
     *
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Method to set the value of field content_type
     *
     * @param string $content_type
     * @return $this
     */
    public function setContentType($content_type)
    {
        $this->content_type = $content_type;

        return $this;
    }

    /**
     * Method to set the value of field tags
     *
     * @param string $tags
     * @return $this
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Method to set the value of field state
     *
     * @param string $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Method to set the value of field author_id
     *
     * @param integer $author_id
     * @return $this
     */
    public function setAuthorId($author_id)
    {
        $this->author_id = $author_id;

        return $this;
    }

    /**
     * Method to set the value of field created_at
     *
     * @param string $created_at
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field updated_at
     *
     * @param string $updated_at
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Method to set the value of field published_at
     *
     * @param string $published_at
     * @return $this
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Returns the value of field title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of field excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Returns the value of field content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Returns the value of field content_type
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->content_type;
    }

    /**
     * Returns the value of field tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Returns the value of field state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Returns the value of field author_id
     *
     * @return integer
     */
    public function getAuthorId()
    {
        return $this->author_id;
    }

    /**
     * Returns the value of field created_at
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field updated_at
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Returns the value of field published_at
     *
     * @return string
     */
    public function getPublishedAt()
    {
        return $this->published_at;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("public");
        $this->setSource("article");
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Article[]|Article|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Article|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'article';
    }

}
