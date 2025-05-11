<?php

namespace App\Controller;


use ApiPlatform\JsonLd\Serializer\ObjectNormalizer;
use App\Player\Domain\Entity\Player;
use App\Player\Domain\ValueObject\Gender;
use App\Tournament\Application\Command\SimulateTournamentCommand;
use App\Tournament\Application\Command\SimulateTournamentCommandHandler;
use App\Tournament\Application\Query\GetTournamentsQuery;
use App\Tournament\Application\Query\GetTournamentsQueryHandler;
use OpenApi\Attributes as OA;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

// Para normalizar el ganador

#[Route('/api/tournaments', name: 'api_tournaments')]
final class TournamentController extends AbstractController
{
    private SerializerInterface $serializer; // Inyectamos el Serializer

    public function __construct( SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Obtiene todos los torneos, con opción de filtrar por género.
     */
    #[OA\Parameter(
        name: 'gender',
        in: 'query',
        description: 'Filtrar torneos por género (male o female)',
        required: false,
        schema: new OA\Schema(type: 'string', enum: ['male', 'female'])
    )]
    #[OA\Parameter(
        name: 'startDate',
        in: 'query',
        description: 'Filtrar torneos a partir de esta fecha (YYYY-MM-DD)',
        required: false,
        schema: new OA\Schema(type: 'string', format: 'date')
    )]
    #[OA\Parameter(
        name: 'endDate',
        in: 'query',
        description: 'Filtrar torneos hasta esta fecha (YYYY-MM-DD)',
        required: false,
        schema: new OA\Schema(type: 'string', format: 'date')
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista de todos los torneos con su información y ganador.',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(
                properties: [
                    'id' => new OA\Property(property: 'id', type: 'integer', example: 1),
                    'name' => new OA\Property(property: 'name', type: 'string', example: 'Master 1000 Edición 1 - Male'),
                    'startDate' => new OA\Property(property: 'startDate', type: 'string', format: 'date-time', example: '2025-05-10 02:00:00'),
                    'endDate' => new OA\Property(property: 'endDate', type: 'integer', nullable: true, example: 5),
                    'gender' => new OA\Property(property: 'gender', type: 'string', enum: ['male', 'female'], example: 'male'),
                    'winner' => new OA\Property(
                        property: 'winner',
                        type: 'object',
                        nullable: true,
                        properties: [
                            'id' => new OA\Property(property: 'id', type: 'integer', example: 51),
                            'name' => new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        ]
                    ),
                ]
            )
        )
    )]
    #[Route('/', name: 'get_all_tournaments', methods: ['GET'])]
    public function getAllTournaments(Request $request, GetTournamentsQueryHandler $handler): JsonResponse
    {
        try {
            $genderFilter = $request->query->get('gender');
            $startDateFilter = $request->query->get('startDate');
            $endDateFilter = $request->query->get('endDate');

            $query = new GetTournamentsQuery($genderFilter, $startDateFilter, $endDateFilter);
            $data = $handler($query);

            return $this->json($data);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to retrieve tournaments.', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(
        path: '/api/tournaments/simulate/male',
        summary: 'Simulates a male tournament and returns the winner.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'The winner of the simulated male tournament.',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', readOnly: true, example: 123),
                        new OA\Property(property: 'name', type: 'string', example: 'Lionel Messi'),
                        new OA\Property(property: 'gender', type: 'string', example: 'male'),
                        new OA\Property(property: 'age', type: 'integer', example: 36),
                        new OA\Property(property: 'ability', type: 'integer', example: 95),
                        new OA\Property(property: 'strength', type: 'integer', nullable: true, example: 92),
                        new OA\Property(property: 'velocity', type: 'integer', nullable: true, example: 98),
                        new OA\Property(property: 'reaction', type: 'integer', nullable: true, example: 96),
                        // ... otras propiedades del jugador ganador
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request parameters.'
            ),
            new OA\Response(
                response: 500,
                description: 'Failed to simulate the tournament.'
            )
        ]
    )]
    #[Route('/simulate/male', name: 'simulate_male_tournament', methods: ['POST'])]
    public function simulateMaleTournament(SimulateTournamentCommandHandler $simulateTournamentCommandHandler): JsonResponse
    {
        return $this->simulateTournament("male", $simulateTournamentCommandHandler);
    }

    private function simulateTournament(string $gender ,SimulateTournamentCommandHandler $simulateTournamentCommandHandler)
    {
        try {
            $command = new SimulateTournamentCommand($gender);
            $winner = $simulateTournamentCommandHandler($command);

            if ($winner instanceof Player) {
                $normalizedWinner = $this->serializer->normalize($winner, null, ['groups' => 'player_list']);
                return new JsonResponse($normalizedWinner, Response::HTTP_OK);
            } else {
                return new JsonResponse(['error' => 'Tournament simulation failed to produce a winner.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Failed to simulate male tournament.', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Post(
        path: '/api/tournaments/simulate/female',
        summary: 'Simulates a female tournament and returns the winner.',
        responses: [
            new OA\Response(
                response: 200,
                description: 'The winner of the simulated female tournament.',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', readOnly: true, example: 456),
                        new OA\Property(property: 'name', type: 'string', example: 'Serena Williams'),
                        new OA\Property(property: 'gender', type: 'string', example: 'female'),
                        new OA\Property(property: 'age', type: 'integer', example: 39),
                        new OA\Property(property: 'ability', type: 'integer', example: 92),
                        new OA\Property(property: 'strength', type: 'integer', nullable: true, example: 95),
                        new OA\Property(property: 'velocity', type: 'integer', nullable: true, example: 90),
                        new OA\Property(property: 'reaction', type: 'integer', nullable: true, example: 88),
                        // ... otras propiedades de la jugadora ganadora
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid request parameters.'
            ),
            new OA\Response(
                response: 500,
                description: 'Failed to simulate the tournament.'
            )
        ]
    )]
    #[Route('/simulate/female', name: 'simulate_female_tournament', methods: ['POST'])]
    public function simulateFemaleTournament( SimulateTournamentCommandHandler $simulateTournamentCommandHandler): JsonResponse
    {
        return $this->simulateTournament("female", $simulateTournamentCommandHandler);
    }
}
