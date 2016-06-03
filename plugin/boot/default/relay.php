<?php

	use Dotink\Flourish;
	use Psr\Http\Message\RequestInterface;
	use Psr\Http\Message\ResponseInterface;


	return Affinity\Action::create(['core', 'routing'], function($app, $broker) {

		$relay_queue = array();

		foreach ($app['engine']->fetch('@middleware', 'providers') as $id => $providers) {
			if (!is_array($providers)) {
				throw new Flourish\ProgrammerException('Invalid providers type, not an array');
			}

			$relay_queue += $providers;
		}

		if (isset($app['router'])) {
			$relay_queue[] = [$app['router'], 'run'];
		}

		$relay_resolver = new Inkwell\Relay\Resolver($broker);
		$relay_runner   = new Relay\Runner($relay_queue, $relay_resolver);

		$app['engine.handler'] = function($app, $broker) use ($relay_runner){
			return $app['gateway']->transport($relay_runner($app['request'], $app['response']));
		};
	});
