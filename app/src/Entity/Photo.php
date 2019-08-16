<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="photos", uniqueConstraints={@ORM\UniqueConstraint(name="photo_slug", columns={"slug"})}))
 */
class Photo extends Entity {

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $image;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Photo")
     * @ORM\JoinColumn(name="parentId")
     */
    private $parent;

    public function __construct($slug) {
        $this->slug = $slug;
    }

    // TODO: temporary, move to abstract Entity class
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }
}
