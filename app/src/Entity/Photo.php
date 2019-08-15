<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="photos", uniqueConstraints={@ORM\UniqueConstraint(name="photo_slug", columns={"slug"})}))
 */
class Photo {
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @ORM\Column(type="string")
     */
    protected $image;

    /**
     * @ORM\Column(type="string")
     */
    protected $slug;

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getImage() {
        return $this->image;
    }
}
