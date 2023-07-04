<?php

declare(strict_types=1);
//
//declare(strict_types=1);
//
//namespace App\Tests;
//
//use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\ORM\Tools\SchemaValidator;
//use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
//
//class DoctrineMappingTest extends KernelTestCase
//{
//    public function testEntitiesHaveValidMappings(): void
//    {
//        self::bootKernel();
//
//        /** @var EntityManagerInterface $em */
//        $em = self::getContainer()->get('doctrine')->getManager();
//        $validator = new SchemaValidator($em);
//        $errors = $validator->validateMapping();
//
//        if (empty($errors)) {
//            self::assertTrue(true, 'No mappings errors found.');
//        } else {
//            $errorMessages = [];
//            foreach ($errors as $classErrors) {
//                $errorMessages = array_merge($errorMessages, $classErrors);
//            }
//            self::fail(implode("\n", $errorMessages));
//        }
//    }
//}
