<?php

declare(strict_types=1);

namespace Adexos\SyliusPostgreCompatibilityPlugin\DataProvider;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\OrderRepository;
use Sylius\Component\Core\Dashboard\Interval;
use Sylius\Component\Core\Dashboard\SalesDataProviderInterface;
use Sylius\Component\Core\Dashboard\SalesSummary;
use Sylius\Component\Core\Dashboard\SalesSummaryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\OrderPaymentStates;

/**
 * @experimental
 */
final class SalesDataProvider implements SalesDataProviderInterface
{
    private OrderRepository $orderRepository;

    /**
     * SalesDataProvider constructor.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getSalesSummary(
        ChannelInterface $channel,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        Interval $interval
    ): SalesSummaryInterface {
        $queryBuilder = $this->orderRepository->createQueryBuilder('o')
            ->select('SUM(o.total) AS total')
            ->andWhere('o.paymentState = :state')
            ->andWhere('o.channel = :channel')
            ->setParameter('state', OrderPaymentStates::STATE_PAID)
            ->setParameter('channel', $channel)
        ;

        switch ($interval->asString()) {
            case 'year':
                $queryBuilder
                    ->addSelect('YEAR(o.checkoutCompletedAt) as year')
                    ->groupBy('year')
                    ->andHaving('YEAR(o.checkoutCompletedAt) >= :startYear AND YEAR(o.checkoutCompletedAt) <= :endYear')
                    ->setParameter('startYear', $startDate->format('Y'))
                    ->setParameter('endYear', $endDate->format('Y'))
                ;
                $dateFormatter = static function (\DateTimeInterface $date): string {
                    return $date->format('Y');
                };
                $resultFormatter = static function (array $data): string {
                    return (string) $data['year'];
                };

                break;
            case 'month':
                $queryBuilder
                    ->addSelect('YEAR(o.checkoutCompletedAt) as year')
                    ->addSelect('MONTH(o.checkoutCompletedAt) as month')
                    ->groupBy('year')
                    ->addGroupBy('month')
                    ->andHaving($queryBuilder->expr()->orX(...[
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) = :endYear AND MONTH(o.checkoutCompletedAt) >= :startMonth AND MONTH(o.checkoutCompletedAt) <= :endMonth',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) != :endYear AND MONTH(o.checkoutCompletedAt) >= :startMonth',
                        'YEAR(o.checkoutCompletedAt) = :endYear AND YEAR(o.checkoutCompletedAt) != :startYear AND MONTH(o.checkoutCompletedAt) <= :endMonth',
                        'YEAR(o.checkoutCompletedAt) > :startYear AND YEAR(o.checkoutCompletedAt) < :endYear'
                        ]
                    ))
                    ->setParameter('startYear', $startDate->format('Y'))
                    ->setParameter('startMonth', $startDate->format('n'))
                    ->setParameter('endYear', $endDate->format('Y'))
                    ->setParameter('endMonth', $endDate->format('n'))
                ;
                $dateFormatter = static function (\DateTimeInterface $date): string {
                    return $date->format('n.Y');
                };
                $resultFormatter = static function (array $data): string {
                    return "{$data['month']}.{$data['year']}";
                };

                break;
            case 'week':
                // @phpstan-ignore-next-line
                $startWeek = ltrim($startDate->format('W'), '0') ?: '0';
                // @phpstan-ignore-next-line
                $endWeek = ltrim($endDate->format('W'), '0') ?: '0';
                $queryBuilder
                    ->addSelect('YEAR(o.checkoutCompletedAt) as year')
                    ->addSelect('WEEK(o.checkoutCompletedAt) as week')
                    ->groupBy('year')
                    ->addGroupBy('week')
                    ->andHaving($queryBuilder->expr()->orX(...[
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) = :endYear AND WEEK(o.checkoutCompletedAt) >= :startWeek AND WEEK(o.checkoutCompletedAt) <= :endWeek',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) != :endYear AND WEEK(o.checkoutCompletedAt) >= :startWeek',
                        'YEAR(o.checkoutCompletedAt) = :endYear AND YEAR(o.checkoutCompletedAt) != :startYear AND WEEK(o.checkoutCompletedAt) <= :endWeek',
                        'YEAR(o.checkoutCompletedAt) > :startYear AND YEAR(o.checkoutCompletedAt) < :endYear'
                        ]
                    ))
                    ->setParameter('startYear', $startDate->format('Y'))
                    ->setParameter('startWeek', $startWeek)
                    ->setParameter('endYear', $endDate->format('Y'))
                    ->setParameter('endWeek', $endWeek)
                ;
                $dateFormatter = static function (\DateTimeInterface $date): string {
                    // @phpstan-ignore-next-line
                    return (ltrim($date->format('W'), '0') ?: '0') . ' ' . $date->format('Y');
                };

                $resultFormatter = static function (array $data): string {
                    return "{$data['week']} {$data['year']}";
                };
                break;
            case 'day':
                $queryBuilder
                    ->addSelect('YEAR(o.checkoutCompletedAt) as year')
                    ->addSelect('MONTH(o.checkoutCompletedAt) as month')
                    ->addSelect('DAY(o.checkoutCompletedAt) as day')
                    ->groupBy('year')
                    ->addGroupBy('month')
                    ->addGroupBy('day')
                    ->andHaving($queryBuilder->expr()->orX(...[
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) = :endYear AND MONTH(o.checkoutCompletedAt) = :startMonth AND MONTH(o.checkoutCompletedAt) = :endMonth AND day >= :startDay AND day <= :endDay',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) = :endYear AND MONTH(o.checkoutCompletedAt) = :startMonth AND MONTH(o.checkoutCompletedAt) != :endMonth AND day >= :startDay',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) = :endYear AND MONTH(o.checkoutCompletedAt) = :endMonth AND MONTH(o.checkoutCompletedAt) != :startMonth AND day <= :endDay',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) = :endYear AND MONTH(o.checkoutCompletedAt) > :startMonth AND MONTH(o.checkoutCompletedAt) < :endMonth',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) != :endYear AND MONTH(o.checkoutCompletedAt) = :startMonth AND DAY(o.checkoutCompletedAt) >= :startDay',
                        'YEAR(o.checkoutCompletedAt) = :startYear AND YEAR(o.checkoutCompletedAt) != :endYear AND MONTH(o.checkoutCompletedAt) > :startMonth',
                        'YEAR(o.checkoutCompletedAt) = :endYear AND YEAR(o.checkoutCompletedAt) != :startYear AND MONTH(o.checkoutCompletedAt) = :endMonth AND DAY(o.checkoutCompletedAt) <= :endDay',
                        'YEAR(o.checkoutCompletedAt) = :endYear AND YEAR(o.checkoutCompletedAt) != :startYear AND MONTH(o.checkoutCompletedAt) < :endMonth',
                        'YEAR(o.checkoutCompletedAt) > :startYear AND YEAR(o.checkoutCompletedAt) < :endYear'
                        ]
                    ))
                    ->setParameter('startYear', $startDate->format('Y'))
                    ->setParameter('startMonth', $startDate->format('n'))
                    ->setParameter('startDay', $startDate->format('j'))
                    ->setParameter('endYear', $endDate->format('Y'))
                    ->setParameter('endMonth', $endDate->format('n'))
                    ->setParameter('endDay', $endDate->format('j'))
                ;
                $dateFormatter = static function (\DateTimeInterface $date): string {
                    return $date->format('j.n.Y');
                };
                $resultFormatter = static function (array $data): string {
                    return "{$data['day']}.{$data['month']}.{$data['year']}";
                };

                break;
            default:
                throw new \RuntimeException(sprintf('Interval "%s" not supported.', $interval->asString()));
        }

        $ordersTotals = $queryBuilder->getQuery()->getArrayResult();

        $salesData = [];

        $period = new \DatePeriod($startDate, \DateInterval::createFromDateString(sprintf('1 %s', $interval->asString())), $endDate);
        /** @psalm-suppress all */
        foreach ($period as $date) {
            /** @psalm-suppress all */
            $salesData[$dateFormatter($date)] = 0;
        }

        /** @var array $item */
        foreach ($ordersTotals as $item) {
            $salesData[$resultFormatter($item)] = (int) $item['total'];
        }

        $salesData = array_map(
            static function (int $total): string {
                return number_format(abs($total / 100), 2, '.', '');
            },
            $salesData
        );

        return new SalesSummary($salesData);
    }
}
