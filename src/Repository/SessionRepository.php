<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    public function getNbOpenDays(\DateTimeInterface $dateStart, \DateTimeInterface $dateEnd)
    {
        $arrBankHolidays = array(); // Tableau des jours fériés

        // Calcul de la différence en années entre les deux dates
        $diff = $dateStart->diff($dateEnd);
        $diffYear = $diff->y; // Différence en années

        for ($i = 0; $i <= $diffYear; $i++) {
            $year = (int)$dateStart->format('Y') + $i; // Utilisation de format() au lieu de date()
            
            // Liste des jours fériés (format j_n_Y)
            $arrBankHolidays[] = '1_1_' . $year; // Jour de l'an
            $arrBankHolidays[] = '1_5_' . $year; // Fête du travail
            $arrBankHolidays[] = '8_5_' . $year; // Victoire 1945
            $arrBankHolidays[] = '14_7_' . $year; // Fête nationale
            $arrBankHolidays[] = '15_8_' . $year; // Assomption
            $arrBankHolidays[] = '1_11_' . $year; // Toussaint
            $arrBankHolidays[] = '11_11_' . $year; // Armistice 1918
            $arrBankHolidays[] = '25_12_' . $year; // Noël

            // Jours calculés à partir de Pâques
            $easter = easter_date($year);
            $arrBankHolidays[] = date('j_n_' . $year, $easter); // Pâques
            $arrBankHolidays[] = date('j_n_' . $year, $easter + (86400 * 39)); // Ascension
            $arrBankHolidays[] = date('j_n_' . $year, $easter + (86400 * 50)); // Pentecôte
        }

        $nbDaysOpen = 0;
        $currentDate = clone $dateStart; // Clone pour éviter de modifier l'original

        // Parcours des jours
        while ($currentDate < $dateEnd) {
            // Vérifier si ce n'est pas un week-end (samedi/dimanche)
            if ($currentDate->format('N') < 6) { // 'N' : 1 (lundi) -> 7 (dimanche)
                $formattedDate = $currentDate->format('j_n_' . $currentDate->format('Y'));

                // Vérifier si ce n'est pas un jour férié
                if (!in_array($formattedDate, $arrBankHolidays)) {
                    $nbDaysOpen++;
                }
            }
            // Incrémenter d'un jour
            $currentDate->modify('+1 day');
        }

        return $nbDaysOpen;
    }

    //    /**
    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
