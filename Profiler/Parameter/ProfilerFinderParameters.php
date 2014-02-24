<?php
/**
 * This file is part of the SofHad package.
 *
 * (c) Sofiane HADDAG <sofiane.haddag@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace So\LogboardBundle\Profiler\Parameter;

use Symfony\Component\HttpKernel\Profiler\Profile;

/**
 * Symfony logs handler
 *
 * @author Sofiane HADDAG <sofiane.haddag@yahoo.fr>
 */
class ProfilerFinderParameters implements ParametersHandlerInterface
{

    /**
     * @param integer $dataCount The count
     * @param string  $panel     The panel
     */
    public function __construct($dataCount, $panel)
    {
        $this->dataCount = $dataCount;
        $this->panel     = $panel;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function getParameters(Profile $profile = null)
    {
        //There are no settings in the version 1.*
        return array(
            'ip'         => null,
            'url'        => null,
            'method'     => null,
            'start'      => null,
            'end'        => null,
            'data_count' => $this->dataCount,
            'panel'      => $this->panel,
        );
    }
}
