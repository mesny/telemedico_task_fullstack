<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Service\ExchangeRates404;
use App\Service\ExchangeRatesServiceInterface;

use InvalidArgumentException as InvalidArg;
use DateTime;

class ExchangeRatesController extends AbstractController
{
    public function getRatesJSON(Request $request, ExchangeRatesServiceInterface $nbpService): Response
    {
        $date = $this->getDate($request->query->get('date',
            (new DateTime('now'))->format('Y-m-d')));

        $data = array("date" => $date->format('Y-m-d'),
            "error" => false,
            "notFound" => false
        );
        $statusCode = Response::HTTP_OK;
        $rates = false;
        try {
            $rates = $nbpService->getRatesForDate($date);
        } catch (ExchangeRates404 $e) {
            $data = array_merge($data, array(
                "error" => true,
                "notFound" => true
            ));
            $statusCode = Response::HTTP_NOT_FOUND;
        } catch (\Throwable $e) {
            $data = array_merge($data, array(
                "error" => true,
                "notFound" => false
            ));
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        } finally {
            $data += array("rates" => $rates);
        }
        return new Response(json_encode((object) $data), $statusCode,
            ['Content-Type' => 'application/json']);
    }

    private function getDate(string $date): DateTime
    {
        if (!($dt = date_create_from_format('Y-m-d', $date)))
            throw new InvalidArg('Invalid date format: '.var_export($date, true));

        return $dt;
    }
}
