<?php
/*
 * This file is part of the SofHad package.
 *
 * (c) Sofiane HADDAG <sofiane.haddag@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace So\BeautyLogBundle\Profiler\Engine;

use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\PropertyAccess\PropertyAccess;


class SymfonyLogEngine implements EngineInterface {

    protected $currentProfile;
    protected $profiler;
    protected $accessor;
    private $currentToken;
    private $comparators;
    private $profiles;
    private $comparatorsCount;

    public function __construct( Profiler $profiler) {

        $this->profiler = $profiler;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    public function loadProfiles($currentToken, $comparatorsCount){

        $this->currentToken = $currentToken;
        $this->comparatorsCount = $comparatorsCount;
        $this->currentProfile = $this->profiler->loadProfile($this->currentToken);
        $this->loadComparators();
        $this->heapUp();

        return $this->profiles;
    }

    public function loadComparators(){


        $this->comparators = $this->profiler->find($this->currentProfile->getIp(), $this->currentProfile->getUrl(), $this->comparatorsCount, $this->currentProfile->getMethod(),  null, \date("Y-m-d H:i:s"));
    }

    public function heapUp(){

        $this->profiles["current"]["token"] = $this->currentToken;
        $this->profiles["current"]["profile"] = $this->currentProfile;

        foreach($this->comparators as $comparator){

            $this->profiles[$comparator["time"]]['token'] = $this->accessor->getValue($comparator, '[token]');
            $this->profiles[$comparator["time"]]['profile'] = $this->profiler->loadProfile($this->accessor->getValue($comparator, '[token]'));
        }
    }
}
