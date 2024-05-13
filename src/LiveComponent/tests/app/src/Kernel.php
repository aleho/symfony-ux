<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App;

use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use Symfony\UX\LiveComponent\LiveComponentBundle;
use Symfony\UX\StimulusBundle\StimulusBundle;
use Symfony\UX\TwigComponent\TwigComponentBundle;
use Symfony\WebpackEncoreBundle\WebpackEncoreBundle;

/**
 * @author Alexander Hofbauer <alex@derhofbauer.at>
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function getProjectDir(): string
    {
        return __DIR__.'/../';
    }

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new TwigBundle();
        yield new WebpackEncoreBundle();
        yield new WebProfilerBundle();
        yield new DebugBundle();
        yield new StimulusBundle();
        yield new TwigComponentBundle();
        yield new LiveComponentBundle();
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'secret' => 'ChangeMe',
            'test' => 'test' === ($_SERVER['APP_ENV'] ?? 'dev'),
            'router' => [
                'utf8' => true,
            ],
            'profiler' => [
                'only_exceptions' => false,
            ],
        ]);

        $container->extension('web_profiler', [
            'toolbar' => true,
            'intercept_redirects' => false,
        ]);

        $container->extension('twig_component', [
            'anonymous_template_directory' => 'components/',
            'defaults' => [
                'App\\Twig\\Component\\' => 'components/',
            ],
        ]);

        $container->extension('webpack_encore', ['output_path' => 'build']);

        $container->services()
            ->defaults()
            ->autowire()
            ->autoconfigure()
            ->load(__NAMESPACE__.'\\Twig\\Component\\', __DIR__.'/Twig/Component');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('@LiveComponentBundle/config/routes.php')->prefix('/_components');
        $routes->import('@WebProfilerBundle/Resources/config/routing/wdt.xml')->prefix('/_wdt');
        $routes->import('@WebProfilerBundle/Resources/config/routing/profiler.xml')->prefix('/_profiler');

        $routes->add('whitespace_classes', '/whitespace-classes')->controller(TemplateController::class)->defaults(['template' => 'whitespace_classes.html.twig']);
    }
}
