<?php
namespace App\System\Infrastructure\Http;

use App\System\Application\DTO\CoinStatusDT;
use App\System\Application\DTO\ItemStatusDT;
use App\System\Application\Service\GetTotalCollectedService;
use App\System\Application\Service\GetVendingStatusService;
use App\System\Application\Service\PurchaseItemService;
use App\System\Application\Service\SetCoinsService;
use App\System\Application\Service\SetItemsService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

#[AsController]
final class HomeController
{
    private GetVendingStatusService $getVendingStatusService;
    private CoinStatusDT $coinStatusDT;
    private ItemStatusDT  $itemStatusDT;
    private PurchaseItemService $purchaseItemService;
    private SetCoinsService $setCoinService;
    private SetItemsService $setItemsService;
    private GetTotalCollectedService $getTotalCollectedService;

    public function __construct(
        private Environment $twig, 
        GetVendingStatusService $getVendingStatusService,
        CoinStatusDT $coinStatusDT,
        ItemStatusDT $itemStatusDT,
        PurchaseItemService $purchaseItemService,
        SetCoinsService $setCoinService,
        SetItemsService $setItemsService,
        GetTotalCollectedService $getTotalCollectedService
    ) 
    {
        $this->getVendingStatusService = $getVendingStatusService;
        $this->coinStatusDT = $coinStatusDT;
        $this->itemStatusDT = $itemStatusDT;
        $this->purchaseItemService = $purchaseItemService;
        $this->setCoinService = $setCoinService;
        $this->setItemsService = $setItemsService;
        $this->getTotalCollectedService = $getTotalCollectedService;
    }

    #[Route('/', name: 'vending_home', methods: ['GET'])]
    public function index(): Response
    {
        list($availableCoins, $availableItems) = $this->getVendingStatusService->execute();

        $transformedCoins = $this->coinStatusDT->transform($availableCoins);
        $transformedItems = $this->itemStatusDT->transform($availableItems);

        $html = $this->twig->render('vending/index.html.twig', [
            'coins'    => $transformedCoins,
            'items'    => $transformedItems
        ]);

        return new Response($html);
    }

    #[Route('/api/items/purchase', name: 'vending_purchase', methods: ['POST'])]
    public function purchase(Request $request): JsonResponse
    {
        $itemId = (int) ($request->request->get('id') ?? 0);
        $insertedCents = explode(",", $request->request->get('inserted_cents'));

        try{
            $purchaseResponse = $this->purchaseItemService->execute($itemId, $insertedCents);

            $response = new JsonResponse(
                [
                    'ok' => true, 
                    'itemName' => $purchaseResponse['itemName'], 
                    'change' => $purchaseResponse['coins']
                ]
            );
        } catch(NotFoundHttpException | ConflictHttpException $e) {
            $response = new JsonResponse(['ok' => false, 'message' => $e->getMessage()]);
        } catch(Exception $e) {
            $response = new JsonResponse(['ok' => false, 'message' => $e->getMessage()], 500);
        }

        return $response;
    }

    #[Route('/api/service/config', name: 'vending_service_config', methods: ['GET'])]
    public function serviceConfig(): JsonResponse
    {
        list($availableCoins, $availableItems) = $this->getVendingStatusService->execute();

        $transformedCoins = $this->coinStatusDT->transform($availableCoins);
        $transformedItems = $this->itemStatusDT->transform($availableItems);

        $totalCentsCollected = $this->getTotalCollectedService->execute();
        $totalCollected = $totalCentsCollected / 100;

        $response = new JsonResponse(
                [
                    'coins' => $transformedCoins, 
                    'items' => $transformedItems,
                    'totalCollected' => $totalCollected
                ]
            );

        return $response;
    }

    #[Route('/api/service/set', name: 'vending_service_set', methods: ['POST'])]
    public function serviceSet(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent() ?: '[]', true);
        
        $coins = $payload['coins'];
        $items = $payload['items'];

        $coinsSaved = $this->setCoinService->execute($coins);
        $itemsSaved = $this->setItemsService->execute($items);

        $response = false;
        if ($coinsSaved && $itemsSaved) {
            $response = true;
        }

        return new JsonResponse(['ok' => $response]);
    } 
}
