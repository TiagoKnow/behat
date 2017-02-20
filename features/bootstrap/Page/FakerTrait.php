<?php

namespace Page;

use Faker\Factory as FakerFactory;
use Providers\Company;
use Providers\Person;

trait FakerTrait
{
    protected $faker;


    public function getFaker()
    {
        if (null === $this->faker) {
            $this->faker = FakerFactory::create('pt_BR');
            $this->faker->addProvider(new Company($this->faker));
            $this->faker->addProvider(new Person($this->faker));
        }

        return $this->faker;
    }
}