<?php
/**
 * This file is part of the SofHad package.
 *
 * (c) Sofiane HADDAG <sofiane.haddag@yahoo.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace So\LogboardBundle\Profiler;

use So\LogboardBundle\Profiler\Engine\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

/**
 * Class QueryManager
 *
 * @package So\LogboardBundle\Profiler
 * @author Sofiane HADDAG <sofiane.haddag@yahoo.fr>
 */
class QueryManager implements QueryManagerInterface
{

    /**
     * Icons chart urls
     * @var string
     */
    protected $iconSwitcherUrl;
    /**
     * The token
     * @var string
     */
    protected $token;
    /**
     * The request
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;
    /**
     * The default chart
     * @var string
     */
    protected $defaultChart;
    /**
     * The chart
     * @var string
     */
    protected $chart;
    /**
     * Preview value
     * @var Boolean
     */
    protected $preview;
    /**
     * The engine links urls
     * @var string
     */
    protected $engineSwitcherUrl;
    /**
     * Engine
     * @var \So\LogboardBundle\Profiler\Engine\EngineInterface
     */
    protected $engine = null;
    /**
     * The engine service id
     * @var string
     */
    protected $engineServiceId = null;
    /**
     * The engine submission status
     * @var Boolean
     */
    protected $isEngineSubmitted = false;
    /**
     * The chart submission status
     * @var Boolean
     */
    protected $isChartSubmitted = false;
    /**
     * Isser preview
     * @var Boolean
     */
    protected $isPreview = false;
    /**
     * The index variables (Titles, labels, services names)
     * @var Array
     */
    protected $index;

    /**
     * @param \Symfony\Component\Routing\Router $router         The router
     * @param string $panel          The panel
     * @param string $defaultChart   The default chart
     * @param Array $index          The index
     */
    public function __construct(Router $router, $panel, $defaultChart, $index)
    {
        $this->router = $router;
        $this->panel = $panel;
        $this->defaultChart = $defaultChart;
        $this->index = $index;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function handleQueries(Request $request, $token)
    {
        $this->request = $request;
        $this->token = $token;

        $this->checkEngine();

        $this->selectChart();

        $this->generateSwitcherUrls();

        $this->checkPreview();
    }

    /**
     * {@inheritdoc}
     *
     */
    public function checkEngine()
    {
        if ($this->request->query->has('engine')) {
            $this->isEngineSubmitted = true;
        }

        $this->engineServiceId = $this->request->query->get('engine', null);
    }

    /**
     * {@inheritdoc}
     *
     */
    public function selectChart()
    {
        $chart = $this->request->query->get('chart');

        if (null !== $chart) {
            $this->isChartSubmitted = true;
            $this->chart = $chart;
        } else {
            $this->chart = $this->defaultChart;
        }
    }

    /**
     * {@inheritdoc}
     *
     */
    public function generateSwitcherUrls()
    {
        $currentRoute = $this->request->attributes->get('_route');

        $this->router
            ->generate($currentRoute, array('token' => $this->token), true);

        $this->iconSwitcherUrl = $this
            ->engineSwitcherUrl .= "?panel=" . $this->panel;

        if ($this->isEngineSubmitted) {
            $this->iconSwitcherUrl .= sprintf('&engine=%s', $this->engineServiceId);
        }

        if ($this->isChartSubmitted) {
            $this->engineSwitcherUrl .= sprintf('&chart=%s', $this->chart);
        }
    }

    /**
     * {@inheritdoc}
     *
     */
    public function checkPreview()
    {
        $this->preview = $this->request->query->get('preview');
        $this->isPreview = null !== $this->preview ? true : false;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getPreview()
    {
        return $this->preview;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function isPreview()
    {
        return $this->isPreview;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getEngineServiceId()
    {
        return $this->engineServiceId;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function hasEngine()
    {
        return null === $this->engine ? false : true;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getChart()
    {
        return $this->chart;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getIconSwitcherUrl()
    {
        return $this->iconSwitcherUrl;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getEngineSwitcherUrl()
    {
        return $this->engineSwitcherUrl;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function setEngine(EngineInterface $engine)
    {
        $this->engine = $engine;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function isViewer()
    {
        return true;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     *
     */
    public function getCurrentTitle()
    {
        foreach ($this->index as $menu) {
            foreach ($menu as $subMenu) {
                if ($subMenu['engine_service'] === $this->engineServiceId) {
                    return $subMenu['title'];
                }
            }
        }
    }

}
