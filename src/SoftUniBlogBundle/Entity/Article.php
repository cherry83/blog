<?php

namespace SoftUniBlogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="articles")
 * @ORM\Entity(repositoryClass="SoftUniBlogBundle\Repository\ArticleRepository")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    /**
     * @var string
     */
    private $summary;

    /**
     * @param string
     */
    public function setSummary()
    {
        $this->summary = substr($this->getContent(), 0, strlen($this->getContent()) / 2) . "...";
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        if ($this->summary === null) {
            $this->setSummary();
        }
        return $this->summary;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="authorId", type="integer")
     */
    private $authorId;

    /**
     * @param integer $authorId
     *
     * @return Article
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param integer $authorId
     *
     * @return Article
     */
    public function setArticle($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getArticle()
    {
        return $this->authorId;
    }

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="SoftUniBlogBundle\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(name="authorId", referencedColumnName="id")
     */
    private $author;

    /**
     * @param \SoftUniBlogBundle\Entity\User $author
     *
     * @return Article
     */
    public function setAuthor(User $author = null)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return \SoftUniBlogBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->tags = new ArrayCollection();
    }

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="SoftUniBlogBundle\Entity\Tag", inversedBy="articles")
     * @ORM\JoinTable(name="articles_tags",
     *     joinColumns={@ORM\JoinColumn(name="article_id",referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id",referencedColumnName="id")}
     *     )
     */
    private $tags;

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @var int
     * @ORM\Column(name="category_id", type="integer")
     */
    private $categoryId;

    /**
     * @return int
     */
    public function getCategoryId(){
        return $this->categoryId;
    }

    /**
     * @param int $categoryId
     */
    public function setCategoryId(int $categoryId){
        $this->categoryId = $categoryId;
    }

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="SoftUniBlogBundle\Entity\Category", inversedBy="articles")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @return Category
     */
    public function getCategory(){
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public  function setCategory(Category $category){
        $this->category = $category;
    }
}

