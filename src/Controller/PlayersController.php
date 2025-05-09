<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Application\Command\CreatePlayerCommandHandler;
use App\Player\Application\Query\ListPlayersQuery;
use App\Player\Application\Query\ListPlayersQueryHandler;
use App\Player\Domain\Enum\LimitsList;
use App\Player\Domain\ValueObject\Gender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Psr\Log\LoggerInterface;


#[Route('/api/players', name: 'api_players')]
final class PlayersController extends AbstractController
{
    private SerializerInterface $serializer;
    private MessageBusInterface $bus;
    private ValidatorInterface $validator;
    public function __construct(MessageBusInterface $bus, 
                                SerializerInterface $serializer, 
                                ValidatorInterface $validator)
    {
        $this->bus = $bus;
        $this->serializer = $serializer;
        $this->validator = $validator;
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

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, LoggerInterface $logger,CreatePlayerCommandHandler $createPlayerCommandHandler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        try {
            $command = $this->serializer->denormalize($data, CreatePlayerCommand::class);
            $player = $createPlayerCommandHandler($command);
            $responseData = $this->serializer->normalize($player, null, ['groups' => 'player_list']);
        } catch (\InvalidArgumentException $e) {
            $logger->error('InvalidArgumentException from handler: ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $logger->error('Unexpected error creating player: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Failed to create player.','message' =>$e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        return new JsonResponse($responseData, Response::HTTP_CREATED);
    }

    #[Route('/top/{gender}', name: 'top_players_by_gender', methods: ['GET'], requirements: ['gender' => 'male|female'])]
    public function getTopPlayersByGender(string $gender,
                                          ListPlayersQueryHandler $handler,
                                          SerializerInterface $serializer): JsonResponse
    {
        try {
            $genderVO = new Gender($gender);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        $query = new ListPlayersQuery($genderVO, 'points', 'DESC', LimitsList::TOP_PLAYERS->value);
        $topPlayers = $handler($query);
        $data = $serializer->normalize($topPlayers, null, ['groups' => 'player_list']);
        return new JsonResponse($data, Response::HTTP_OK);
    }


}
