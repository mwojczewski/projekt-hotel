<?php

namespace App\Test\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ReservationRepository $repository;
    private string $path = '/reservation/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Reservation::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'reservation[created_at]' => 'Testing',
            'reservation[starts_at]' => 'Testing',
            'reservation[ends_at]' => 'Testing',
            'reservation[paid]' => 'Testing',
            'reservation[room_id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/reservation/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setCreated_at('My Title');
        $fixture->setStarts_at('My Title');
        $fixture->setEnds_at('My Title');
        $fixture->setPaid('My Title');
        $fixture->setRoom_id('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Reservation');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Reservation();
        $fixture->setCreated_at('My Title');
        $fixture->setStarts_at('My Title');
        $fixture->setEnds_at('My Title');
        $fixture->setPaid('My Title');
        $fixture->setRoom_id('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'reservation[created_at]' => 'Something New',
            'reservation[starts_at]' => 'Something New',
            'reservation[ends_at]' => 'Something New',
            'reservation[paid]' => 'Something New',
            'reservation[room_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/reservation/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getStarts_at());
        self::assertSame('Something New', $fixture[0]->getEnds_at());
        self::assertSame('Something New', $fixture[0]->getPaid());
        self::assertSame('Something New', $fixture[0]->getRoom_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Reservation();
        $fixture->setCreated_at('My Title');
        $fixture->setStarts_at('My Title');
        $fixture->setEnds_at('My Title');
        $fixture->setPaid('My Title');
        $fixture->setRoom_id('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/reservation/');
    }
}
