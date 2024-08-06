<?php

namespace App\Entity\DTO;

class RecipeSummary
{
    private int $id;
    private string $title;
    private string $image;
    private string $date;

    public function __construct(int $id, string $title, string $image, string $date)
    {
        $this->id = $id;
        $this->title = $title;
        $this->image = $image;
        $this->date = $date;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'date' => $this->date,
        ];
    }
}