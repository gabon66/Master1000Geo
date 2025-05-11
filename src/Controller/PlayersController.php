<?php

namespace App\Controller;

use ApiPlatform\Validator\ValidatorInterface;
use App\Player\Application\Command\CreatePlayerCommand;
use App\Player\Application\Command\CreatePlayerCommandHandler;
use App\Player\Application\Command\DeletePlayerCommand;
use App\Player\Application\Command\DeletePlayerCommandHandler;
use App\Player\Application\Command\UpdatePlayerSkillsCommand;
use App\Player\Application\Command\UpdatePlayerSkillsCommandHandler;
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
use OpenApi\Attributes as OA;


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


    #[OA\Get(
        path: '/api/players',
        summary: 'Lists players, optionally filtered by gender.',
        parameters: [
            new OA\Parameter(
                name: 'gender',
                in: 'query',
                description: 'Filter players by gender (male or female).',
                schema: new OA\Schema(type: 'string', enum: ['male', 'female'])
            ),
            new OA\Parameter(
                name: 'orderBy',
                in: 'query',
                description: 'Field to order players by.',
                schema: new OA\Schema(type: 'string', enum: ['name', 'points', 'age', 'strength', 'velocity', 'reaction'])
            ),
            new OA\Parameter(
                name: 'orderDirection',
                in: 'query',
                description: 'Order direction (ASC or DESC). Defaults to ASC.',
                schema: new OA\Schema(type: 'string', enum: ['ASC', 'DESC'])
            ),
            new OA\Parameter(
                name: 'limit',
                in: 'query',
                description: 'Maximum number of players to return.',
                schema: new OA\Schema(type: 'integer', format: 'int32')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of players.',
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid gender provided.'
            )
        ]
    )]
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

    #[OA\Post(
        path: '/api/players',
        summary: 'Creates a new player.',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Lionel Messi'),
                    new OA\Property(property: 'gender', type: 'string', enum: ['male', 'female'], example: 'male'),
                    new OA\Property(property: 'age', type: 'integer', format: 'int32', example: 36),
                    new OA\Property(property: 'ability', type: 'integer', format: 'int32', nullable: true, example: 85),
                    new OA\Property(property: 'strength', type: 'integer', format: 'int32', nullable: true, example: 85),
                    new OA\Property(property: 'velocity', type: 'integer', format: 'int32', nullable: true, example: 92),
                    new OA\Property(property: 'reaction', type: 'integer', format: 'int32', nullable: true, example: 88),
                ],
                required: ['name', 'gender', 'age']
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'The newly created player.',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'Lionel Messi'),
                        new OA\Property(property: 'gender', type: 'string', example: 'male'),
                        new OA\Property(property: 'age', type: 'integer', example: 36),
                        new OA\Property(property: 'ability', type: 'integer', nullable: true, example: 92),
                        new OA\Property(property: 'strength', type: 'integer', nullable: true, example: 85),
                        new OA\Property(property: 'velocity', type: 'integer', nullable: true, example: 92),
                        new OA\Property(property: 'reaction', type: 'integer', nullable: true, example: 88),
                        new OA\Property(property: 'id', type: 'integer', readOnly: true, example: 123), // Asumiendo que tu entidad tiene un ID
                        // ... otras propiedades de la entidad Player que quieras mostrar
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request data.'
            ),
            new OA\Response(
                response: 500,
                description: 'Failed to create player.'
            )
        ]
    )]
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


    #[OA\Response(
        response: 200,
        description: 'List of the top-ranked players by gender',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                type: 'object',
                properties: [
                    new OA\Property(property: 'id', type: 'integer', description: 'The ID of the player'),
                    new OA\Property(property: 'name', type: 'string', description: 'The name of the player'),
                    new OA\Property(property: 'points', type: 'integer', description: 'The points of the player'),
                    // ... other properties of the player ...
                ]
            )
        )
    )]
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


    #[OA\Delete(
        path: '/api/players/{id}',
        summary: 'Deletes a player by ID.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'The ID of the player to delete.',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Player deleted successfully.'
            ),
            new OA\Response(
                response: 404,
                description: 'Player not found.'
            ),
            new OA\Response(
                response: 500,
                description: 'Failed to delete player.'
            )
        ]
    )]
    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(int $id, DeletePlayerCommandHandler $handler): JsonResponse
    {
        try {
            $command = new DeletePlayerCommand($id);
            $handler($command);
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to delete player.', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Put(
        path: '/api/players/{id}/skills',
        summary: 'Updates the skills of a player by ID.',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                description: 'The ID of the player to update.',
                required: true,
                schema: new OA\Schema(type: 'integer', format: 'int64')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                properties: [
                    new OA\Property(property: 'strength', type: 'integer', format: 'int32', nullable: true, example: 88),
                    new OA\Property(property: 'velocity', type: 'integer', format: 'int32', nullable: true, example: 95),
                    new OA\Property(property: 'ability', type: 'integer', format: 'int32', nullable: true, example: 95),
                    new OA\Property(property: 'reaction', type: 'integer', format: 'int32', nullable: true, example: 90),
                ],
                required: [] // No skills are strictly required to be updated
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Player skills updated successfully.',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', readOnly: true, example: 123),
                        new OA\Property(property: 'name', type: 'string', example: 'Lionel Messi'),
                        new OA\Property(property: 'gender', type: 'string', example: 'male'),
                        new OA\Property(property: 'age', type: 'integer', example: 36),
                        new OA\Property(property: 'ability', type: 'integer', nullable: true, example: 95),
                        new OA\Property(property: 'strength', type: 'integer', nullable: true, example: 88),
                        new OA\Property(property: 'velocity', type: 'integer', nullable: true, example: 95),
                        new OA\Property(property: 'reaction', type: 'integer', nullable: true, example: 90),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request data.'
            ),
            new OA\Response(
                response: 404,
                description: 'Player not found.'
            ),
            new OA\Response(
                response: 500,
                description: 'Failed to update player skills.'
            )
        ]
    )]
    #[Route('/{id}/skills', name: 'update_skills', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function updateSkills(int $id, Request $request, LoggerInterface $logger,
                                 UpdatePlayerSkillsCommandHandler $updatePlayerSkillsCommandHandler): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $command = new UpdatePlayerSkillsCommand($id,
                $data['ability'] ?? null,
                $data['strength'] ?? null,
                $data['velocity'] ?? null,
                $data['reaction'] ?? null
            );
            $player = $updatePlayerSkillsCommandHandler($command);
            $responseData = $this->serializer->normalize($player, null, ['groups' => 'player_list']);
            return new JsonResponse($responseData, Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            $logger->error('InvalidArgumentException from handler: ' . $e->getMessage());
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            $logger->error('Unexpected error updating player skills: ' . $e->getMessage());
            return new JsonResponse(['error' => 'Failed to update player skills.', 'message' => $e->getMessage()],
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
