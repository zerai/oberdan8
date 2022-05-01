<?php declare(strict_types=1);


namespace Booking\Adapter\Web\Free\Form\Dto;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @codeCoverageIgnore
 */
class BookDto
{
    /**
     * @Assert\Isbn(
     *     type = "null",
     * )
     */
    public string $isbn;

    /**
     * Assert\NotNull(message="Il campo titolo è obbligatorio")
     * @Assert\NotBlank(message="Inserisci il titolo")
     */
    public string $title;

    public string $author = '';

    public string $volume = '';

    /**
     * @return null|string
     */
    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     */
    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return null|string
     */
    public function getAuthor(): ?string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return null|string
     */
    public function getVolume(): ?string
    {
        return $this->volume;
    }

    /**
     * @param string $volume
     */
    public function setVolume(string $volume): void
    {
        $this->volume = $volume;
    }
}
