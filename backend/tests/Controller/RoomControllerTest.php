<?php

namespace App\Test\Controller;

use App\Entity\Room;
use App\Repository\RoomRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RoomControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private RoomRepository $repository;
    private string $path = '/room/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Room::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Room index');

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
            'room[name]' => 'Testing',
            'room[room_no]' => 'Testing',
            'room[floor_no]' => 'Testing',
            'room[size]' => 'Testing',
            'room[beds]' => 'Testing',
            'room[balcony]' => 'Testing',
            'room[breakfast]' => 'Testing',
            'room[pets_allowed]' => 'Testing',
            'room[price]' => 'Testing',
        ]);

        self::assertResponseRedirects('/room/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Room();
        $fixture->setName('My Title');
        $fixture->setRoom_no('My Title');
        $fixture->setFloor_no('My Title');
        $fixture->setSize('My Title');
        $fixture->setBeds('My Title');
        $fixture->setBalcony('My Title');
        $fixture->setBreakfast('My Title');
        $fixture->setPets_allowed('My Title');
        $fixture->setPrice('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Room');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Room();
        $fixture->setName('My Title');
        $fixture->setRoom_no('My Title');
        $fixture->setFloor_no('My Title');
        $fixture->setSize('My Title');
        $fixture->setBeds('My Title');
        $fixture->setBalcony('My Title');
        $fixture->setBreakfast('My Title');
        $fixture->setPets_allowed('My Title');
        $fixture->setPrice('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'room[name]' => 'Something New',
            'room[room_no]' => 'Something New',
            'room[floor_no]' => 'Something New',
            'room[size]' => 'Something New',
            'room[beds]' => 'Something New',
            'room[balcony]' => 'Something New',
            'room[breakfast]' => 'Something New',
            'room[pets_allowed]' => 'Something New',
            'room[price]' => 'Something New',
        ]);

        self::assertResponseRedirects('/room/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getRoom_no());
        self::assertSame('Something New', $fixture[0]->getFloor_no());
        self::assertSame('Something New', $fixture[0]->getSize());
        self::assertSame('Something New', $fixture[0]->getBeds());
        self::assertSame('Something New', $fixture[0]->getBalcony());
        self::assertSame('Something New', $fixture[0]->getBreakfast());
        self::assertSame('Something New', $fixture[0]->getPets_allowed());
        self::assertSame('Something New', $fixture[0]->getPrice());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Room();
        $fixture->setName('My Title');
        $fixture->setRoom_no('My Title');
        $fixture->setFloor_no('My Title');
        $fixture->setSize('My Title');
        $fixture->setBeds('My Title');
        $fixture->setBalcony('My Title');
        $fixture->setBreakfast('My Title');
        $fixture->setPets_allowed('My Title');
        $fixture->setPrice('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/room/');
    }
}
