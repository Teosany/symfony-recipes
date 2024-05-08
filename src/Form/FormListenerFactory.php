<?php

namespace App\Form;

use App\Entity\Recipe;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class FormListenerFactory
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function autoSlug(string $field): callable
    {
        return function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();

            if (empty($data['slug'])) {
                $data['slug'] = strtolower($this->slugger->slug($data[$field]));

                $event->setData($data);
            }
        };
    }

    public function timestamp(): callable
    {
        return function (PostSubmitEvent $event) {
            $data = $event->getData();
            $now = new \DateTimeImmutable();
            $data->setUpdatedAt($now);
            if (!($data->getId())) {
                $data->setCreatedAt($now);
            }
        };
    }
}