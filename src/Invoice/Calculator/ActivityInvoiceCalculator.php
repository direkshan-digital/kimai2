<?php

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Invoice\Calculator;

use App\Entity\Timesheet;
use App\Invoice\CalculatorInterface;

/**
 * A calculator that sums up the timesheet records by activity.
 */
class ActivityInvoiceCalculator extends AbstractMergedCalculator implements CalculatorInterface
{
    /**
     * @return Timesheet[]
     */
    public function getEntries()
    {
        $entries = $this->model->getEntries();
        if (empty($entries)) {
            return [];
        }

        /** @var Timesheet[] $timesheets */
        $timesheets = [];

        foreach ($entries as $entry) {
            if (!isset($timesheets[$entry->getActivity()->getId()])) {
                $timesheets[$entry->getActivity()->getId()] = new Timesheet();
            }
            $timesheet = $timesheets[$entry->getActivity()->getId()];
            $this->mergeTimesheets($timesheet, $entry);
        }

        return array_values($timesheets);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return 'activity';
    }
}
