<?php

namespace App\Controller;

use App\Player\Application\Query\ListPlayersQuery;
use App\Player\Application\Query\ListPlayersQueryHandler;
use App\Player\Domain\ValueObject\Gender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[Route('/api/players', name: 'api_players')]
final class PlayersController extends AbstractController
{

    public function __construct(MessageBusInterface $queryBus, SerializerInterface $serializer)
    {
        $this->queryBus = $queryBus;
        $this->serializer = $serializer;
    }

    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request , ListPlayersQueryHandler $handler): JsonResponse
    {
        $genderParam = $request->query->get('gender');
        $gender = null;

        if ($genderParam !== null) {
            try {
                $gender = new Gender($genderParam);
            } catch (\InvalidArgumentException $exception) {

                return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
            }
        }

        $query = new ListPlayersQuery($gender);
        $players = $handler($query);

        $data = $this->serializer->normalize($players, null, ['groups' => 'player_list']);

        return new JsonResponse($data, Response::HTTP_OK);
    }
}
