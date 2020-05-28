<?php

declare(strict_types=1);

namespace App\Core\UI\Rest\Task;

use App\Core\Application\Command\Task\DeleteTask\DeleteTaskCommand;
use App\Shared\Infrastructure\Http\HttpSpec;
use App\Shared\Infrastructure\Http\ParamFetcher;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteTaskAction
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * @Route("/api/tasks/{id}", methods={"DELETE"}, requirements={"id": "\d+"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Response(response=Response::HTTP_NO_CONTENT, description=HttpSpec::STR_HTTP_NO_CONTENT)
     * @SWG\Response(response=Response::HTTP_NOT_FOUND, description=HttpSpec::STR_HTTP_NOT_FOUND)
     * @SWG\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @SWG\Tag(name="Task")
     */
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);

        $this->handle(new DeleteTaskCommand($route->getRequiredInt('id')));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}