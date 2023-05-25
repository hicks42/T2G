<?php

namespace App\Test\Controller;

use App\Entity\Events;
use App\Repository\EventsRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventsControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EventsRepository $repository;
    private string $path = '/events/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = (static::getContainer()->get('doctrine'))->getRepository(Events::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event index');

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
            'event[name]' => 'Testing',
            'event[description]' => 'Testing',
            'event[date_start]' => 'Testing',
            'event[date_end]' => 'Testing',
            'event[city_id]' => 'Testing',
            'event[house_id]' => 'Testing',
            'event[vote_id]' => 'Testing',
        ]);

        self::assertResponseRedirects('/events/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Events();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate_start('My Title');
        $fixture->setDate_end('My Title');
        $fixture->setCity_id('My Title');
        $fixture->setHouse_id('My Title');
        $fixture->setVote_id('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Event');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Events();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate_start('My Title');
        $fixture->setDate_end('My Title');
        $fixture->setCity_id('My Title');
        $fixture->setHouse_id('My Title');
        $fixture->setVote_id('My Title');

        $this->repository->add($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'event[name]' => 'Something New',
            'event[description]' => 'Something New',
            'event[date_start]' => 'Something New',
            'event[date_end]' => 'Something New',
            'event[city_id]' => 'Something New',
            'event[house_id]' => 'Something New',
            'event[vote_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/events/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDate_start());
        self::assertSame('Something New', $fixture[0]->getDate_end());
        self::assertSame('Something New', $fixture[0]->getCity_id());
        self::assertSame('Something New', $fixture[0]->getHouse_id());
        self::assertSame('Something New', $fixture[0]->getVote_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Events();
        $fixture->setName('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate_start('My Title');
        $fixture->setDate_end('My Title');
        $fixture->setCity_id('My Title');
        $fixture->setHouse_id('My Title');
        $fixture->setVote_id('My Title');

        $this->repository->add($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/events/');
    }
}
